<?php
/**
 * 
 * @author dys
 *系统定时任务
 *
 */
class  CrontabController extends Controller
{   
	public function actionSentCuponToBirthDay(){
		//生日赠券 提前一周发券
		WxCupon::getOneMonthByBirthday();
	} 
	// 生成日结统计数据
	public function actionRijieStatistics(){
		WxRiJie::rijieStatistics();
		WxRiJie::dealPandian();
	}
	// 同步失败的数据 重新同步
	public function actionRedisOrder(){
		$syncData = DataSyncOperation::getAllSyncFailure($dpid, 1);
		$syncArr = json_decode($syncData,true);
		if(!empty($syncArr)){
			foreach ($syncArr as $sync){
				$lid = $sync['lid'];
				$dpid = $sync['dpid'];
				$orderData = $sync['content'];
				$orderDataArr = json_decode($orderData,true);
				if(!is_array($orderDataArr)){
					continue;
				}
				$type = $orderDataArr['type'];
				if($type==2){
					// 新增订单
					$result = DataSyncOperation::operateOrder($orderDataArr);
				}elseif($type==4){
					// 退款
					$result = DataSyncOperation::retreatOrder($orderDataArr);
				}elseif($type==3){
					// 增加会员卡
					$result = DataSyncOperation::addMemberCard($orderDataArr);
				}elseif($type==5){
					$content = $orderDataArr['data'];
					$contentArr = explode('::', $content);
					$rjDpid = $contentArr[0];
					$rjUserId = $contentArr[1];
					$rjCreateAt = $contentArr[2];
					$rjPoscode = $contentArr[3];
					$rjBtime = $contentArr[4];
					$rjEtime = $contentArr[5];
					$rjcode = $contentArr[6];
					$result = WxRiJie::setRijieCode($rjDpid,$rjCreateAt,$rjPoscode,$rjBtime,$rjEtime,$rjcode);
				}
				$resObj = json_decode($result);
				if($resObj->status){
					DataSyncOperation::delSyncFailure($lid,$dpid);
				}else{
					Helper::writeLog('再次同步失败:同步内容:'.$dpid.json_encode($sync).'错误信息:'.$resObj->msg);
				}
			}
		}
	}
	/**
	 * 盘点数据处理 
	 * 
	 */
	public function actionStockTaking(){
		$db = Yii::app()->db;
		$transaction = $db->beginTransaction();
		try
		{
			$time = time();
			$sql = 'select * from nb_stock_taking where status=0 and delete_flag=0';
			$stockTakings = $db->createCommand($sql)->queryAll();
			foreach ($stockTakings as $stockTaking){
				$logid = $stockTaking['lid'];
				$dpid = $stockTaking['dpid'];
				$sttype = $stockTaking['type'];
				
				$presystemNum = '0.00';
				$stockinNum = '0.00'; // 入库库存
				$stockinPrice = '0.00';// 入库成本
				$damageNum = '0'; //损耗数量
				$damagePrice = '0';//损耗成本
				$salseNum = '0.00';//销售数量
				$salsePrice = '0.00';//销售成本
				$totalNum = '0.00';//总消耗量
				$systemNum = 0;//系统库存
				$nowNum = 0;// 盘点库存
				$diffPrice = '0.00';//损溢成本
				
				$sql = 'select * from nb_stock_taking_detail where dpid='.$dpid.' and logid='.$logid.' and delete_flag=0';
				$stockTakingDetails = Yii::app()->db->createCommand($sql)->queryAll();
				foreach ($stockTakingDetails as $stockTakingDetail){
					if($stockTakingDetail['number']){
						$systemNum = $stockTakingDetail['reality_stock'];
						$nowNum = $stockTakingDetail['taking_stock'];
						$id = $stockTakingDetail['material_id'];
						$salesName = $stockTakingDetail['reasion'];
						
						$sql = 'select sum(stock) from nb_product_material_stock where material_id='.$id.' and dpid='.$dpid.' and delete_flag=0';
						$originalNum = $db->createCommand($sql)->queryScalar();
						if($originalNum!=$systemNum){
							$systemNum = $originalNum;
						}
						// 获取上一次盘点统计信息
						$sql = 'select * from nb_stock_taking_statistics where dpid='.$dpid.' and type='.$sttype.' and material_id='.$id.' and delete_flag=0 order by lid desc limit 1';
						$statict = $db->createCommand($sql)->queryRow();
						if($statict){
							$presystemNum = $statict['stock_taking_num'];//上次盘点库存
							$preStockTime = $statict['create_at'];// 上次盘点时间
							
							// 获取两次盘点之间其他类型的盘点 损耗总和  其他盘点不能影响该盘点的系统库存
							$sql = 'select sum(stock_taking_difnum) as stock_taking_difnum from nb_stock_taking_statistics where dpid='.$dpid.' and create_at>="'.$preStockTime.'" and type!='.$sttype.' and material_id='.$id.' and delete_flag=0';
							$stDifnum = Yii::app()->db->createCommand($sql)->queryScalar();
							$systemNum = $systemNum - $stDifnum;
							
							// 从库存日志记录表 查询上次盘点到本次盘点的 入库库存 盘损库存 销售库存
							$sql = 'select type,sum(stock_num) as stock_num,sum(stock_num*unit_price) as stock_cost from nb_material_stock_log where dpid='.$dpid.' and material_id='.$id.' and type in(0,1,2,4) and create_at>"'.$preStockTime.'" group by type';
							$mStockLogs = $db->createCommand($sql)->queryAll();
							if($mStockLogs){
								foreach ($mStockLogs as $mStockArr){
									$mtype = $mStockArr['type'];
									if($mtype==0){
										// 采购入库
										$stockinNum = $mStockArr['stock_num'];
										$stockinPrice = $mStockArr['stock_cost'];
									}elseif($mtype==1){
										// 堂食销售出库
										$salseNum +=$mStockArr['stock_num'];
										$salsePrice +=$mStockArr['stock_cost'];
									}elseif($mtype==2){
										// 外卖销售出库
										$salseNum +=$mStockArr['stock_num'];
										$salsePrice +=$mStockArr['stock_cost'];
									}elseif($mtype==4){
										// 盘损库存
										$damageNum = $mStockArr['stock_num'];
										$damagePrice = $mStockArr['stock_cost'];
									}
								}
							}
						}
						
						// 超过原始库存
						$difference = $nowNum - $systemNum;// 损溢库存
						if($difference > 0 ){
							//盘点操作，当盘点的库存比理论库存多时，直接在后进的库存批次上加上此次的盘点的差值。。。
							if($stocks['batch_stock'] == 0){
								$unit_price = '0';
							}else{
								$unit_price = $stocks['stock_cost'] / $stocks['batch_stock'];
							}
							$diffPrice = $unit_price*$difference;
						
							//下面是对该次盘点进行的操作。。。
							$sql = 'update nb_product_material_stock set stock=stock+'.$difference.' where lid='.$stocks['lid'].' and dpid='.$stocks['dpid'];
							$db->createCommand($sql)->execute();
						
							$sql = 'update nb_stock_taking_detail set reality_stock='.$systemNum.',number='.$difference.',material_stock_id='.$stocks['lid'].' where lid='.$stockTakingDetail['lid'].' and dpid='.$stockTakingDetail['dpid'];
							$db->createCommand($sql)->execute();
						
								
							$se = new Sequence("material_stock_log");
							$lid = $se->nextval();
							$stocktakingdetails = array(
									'lid'=>$lid,
									'dpid'=>$dpid,
									'create_at'=>date('Y-m-d H:i:s',$time),
									'update_at'=>date('Y-m-d H:i:s',$time),
									'type'=>3,
									'logid'=>$logid,
									'material_id'=>$id,
									'stock_num' => $difference,
									'original_num' => $systemNum,
									'unit_price'=>$unit_price,
									'resean'=>'盘点溢出',
							);
							$command = $db->createCommand()->insert('nb_material_stock_log',$stocktakingdetails);
						}else{
							//盘点库存小于系统的库存  查出所有库存不为0批次
							$sql = 'select * from nb_product_material_stock where stock!=0 and dpid ='.$dpid.' and material_id = '.$id.' and delete_flag = 0 order by create_at asc';
							$stock2 = $db->createCommand($sql)->queryAll();
							
							if(empty($stock2)){
								// 如果所有批次都为0 在最后这批扣减
								if($stocks['batch_stock'] == 0){
									$unit_price = '0';
								}else{
									$unit_price = $stocks['stock_cost'] / $stocks['batch_stock'];
								}
								$diffPrice = $unit_price*$difference;
								
								//下面是对该次盘点进行的操作。。。
								$sql = 'update nb_product_material_stock set stock=stock+'.$difference.' where lid='.$stocks['lid'].' and dpid='.$stocks['dpid'];
								$db->createCommand($sql)->execute();
								
								$sql = 'update nb_stock_taking_detail set reality_stock='.$systemNum.',number='.$difference.',material_stock_id='.$stocks['lid'].' where lid='.$stockTakingDetail['lid'].' and dpid='.$stockTakingDetail['dpid'];
								$db->createCommand($sql)->execute();
									
								$se = new Sequence("material_stock_log");
								$lid = $se->nextval();
								$stocktakingdetails = array(
										'lid'=>$lid,
										'dpid'=>$dpid,
										'create_at'=>date('Y-m-d H:i:s',$time),
										'update_at'=>date('Y-m-d H:i:s',$time),
										'type'=>3,
										'logid'=>$logid,
										'material_id'=>$id,
										'stock_num' => $difference,
										'original_num' => $systemNum,
										'unit_price'=>$unit_price,
										'resean'=>'盘点损失',
								);
								$command = $db->createCommand()->insert('nb_material_stock_log',$stocktakingdetails);
							}
							
							// 盘点差异 取正
							$minusnum = -$difference;
							foreach ($stock2 as $stock){
								$stockori = $stock['stock'];//该批次库存
								if($stockori < 0){
									$minusnum = -$minusnum + $stockori;
								}else{
									$minusnum = $minusnum - $stockori ;
								}
								
								if($stock['batch_stock'] == 0){
									$unit_price = '0';
								}else{
									$unit_price = $stock['stock_cost'] / $stock['batch_stock'];
								}
								// 该批库存 大于 差值的库存
								if($minusnum <= 0 ) {
									if($stockori < 0){
										$changestock = $minusnum - $stockori;
									}else{
										$changestock = $stockori + $minusnum;
									}
									
									$sql = 'update nb_product_material_stock set stock = stock-'.$changestock. ' where lid ='.$stock['lid'].' and dpid='.$stock['dpid'];
									$command = $db->createCommand($sql)->execute();
									
									$sql = 'update nb_stock_taking_detail set reality_stock='.$systemNum.',number='.$difference.',material_stock_id='.$stock['lid'].' where lid='.$stockTakingDetail['lid'].' and dpid='.$stockTakingDetail['dpid'];
									$db->createCommand($sql)->execute();
									
									$diffPrice += $unit_price*$minusnum;
									
									if($minusnum!=0){
										$se = new Sequence("material_stock_log");
										$lid = $se->nextval();
										$stocktakingdetails = array(
												'lid'=>$lid,
												'dpid'=>$dpid,
												'create_at'=>date('Y-m-d H:i:s',$time),
												'update_at'=>date('Y-m-d H:i:s',$time),
												'type'=>3,
												'logid'=>$logid,
												'material_id'=>$id,
												'stock_num' => -$changestock,
												'original_num' => $stockori,
												'unit_price'=>$unit_price,
												'resean'=>'盘点损失',
										);
										$command = $db->createCommand()->insert('nb_material_stock_log',$stocktakingdetails);
									}
									break;
								}else{
									$sql = 'update nb_product_material_stock set stock=0 where lid ='.$stock['lid'].' and dpid ='.$stock['dpid'];
									$command = $db->createCommand($sql)->execute();
									$diffPrice += -$unit_price*$stockori;
									
									$se = new Sequence("material_stock_log");
									$lid = $se->nextval();
									$stocktakingdetails = array(
											'lid'=>$lid,
											'dpid'=>$dpid,
											'create_at'=>date('Y-m-d H:i:s',$time),
											'update_at'=>date('Y-m-d H:i:s',$time),
											'type'=>3,
											'logid'=>$logid,
											'material_id'=>$id,
											'stock_num' => -$stockori,
											'original_num' => $stockori,
											'unit_price'=>$unit_price,
											'resean'=>'盘点损失',
									);
									$command = $db->createCommand()->insert('nb_material_stock_log',$stocktakingdetails);
								}
							}
						}
						// 插入盘点统计信息
						$totalNum = $damageNum + $salseNum;
						$se = new Sequence("stock_taking_statistics");
						$lid = $se->nextval();
						$statictsArr = array(
								'lid'=>$lid,
								'dpid'=>$dpid,
								'create_at'=>date('Y-m-d H:i:s',$time),
								'update_at'=>date('Y-m-d H:i:s',$time),
								'type'=>$sttype,
								'material_id'=>$id,
								'sales_name'=>$salesName,
								'stock_taking_id'=>$logid,
								'prestock_taking_num'=>$presystemNum,
								'stockin_num'=>$stockinNum,
								'stockin_price'=>$stockinPrice,
								'damage_num'=>$damageNum,
								'damage_price'=>$damagePrice,
								'salse_num'=>$salseNum,
								'salse_price'=>$salsePrice,
								'total_num'=>$totalNum,
								'system_num'=>$systemNum,
								'stock_taking_num'=>$nowNum,
								'stock_taking_difnum'=>$difference,
								'stock_taking_difprice'=>$diffPrice,
						);
						$command = $db->createCommand()->insert('nb_stock_taking_statistics',$statictsArr);
						
					}
				}
				$sql = 'update nb_stock_taking set status=1 where lid='.$logid.' and dpid='.$dpid;
				$db->createCommand($sql)->execute();
			}
			$transaction->commit();
			$msg = json_encode(array("status"=>"success","msg"=>$nostockmsg,"logid"=>$logid));
		}catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			$msg = json_encode(array("status"=>"fail",'msg'=>$e->getMessage()));
		}
		echo $msg;
	}
	/**
	 *
	 * 新上铁接口
	 *
	 */
	public function actionOrderToXst(){
		$yesterDateBegain = date('Y-m-d 00:00:00',strtotime("-1 day"));
		$yesterDateEnd = date('Y-m-d 23:59:59',strtotime("-1 day"));
		$platforms = ThirdPlatform::getXstInfo();
		foreach ($platforms as $platform){
			$sql = 'Select t.lid,t.dpid,t.create_at,t.order_type,t.should_total,t1.paytype,t2.is_retreat from nb_order t left join nb_order_pay t1 on t.dpid=t1.dpid and t.lid=t1.order_id left join nb_order_product t2 on t.dpid=t2.dpid and t.lid=t2.order_id where t.dpid='.$platform['dpid'].' and t.create_at >= "'.$yesterDateBegain.'" and t.create_at <= "'.$yesterDateEnd.'" and t.order_status in (3,4,8) and t1.paytype!=11 group by lid,dpid';
			$orders = Yii::app()->db->createCommand($sql)->queryAll();
			foreach ($orders as $order){
				$sourcetype = 'POS机';
				if($order['paytype']==0){
					$payment = '现金';
				}else{
					$payment = '非现金';
				}
				if($order['is_retreat']==0){
					$transtype = '销售';
				}else{
					$transtype = '退货';
				}
				$xstData = array(
						'lid'=>$order['lid'],
						'create_at'=>$order['create_at'],
						'total'=>$order['should_total'],
						'payment'=>$payment,
						'transtype'=>$transtype,
						'sourcetype'=>$sourcetype,
				);
				ThirdPlatform::xst($xstData,$platform);
			}
		}
	}
	/**
	 * 查询饿了么token如果过期了 系统自动刷新token
	 * 
	 */
	public function actionGetelemeToken(){
		$time = strtotime('+2 day')+600;
		$sql = 'select * from nb_eleme_token where expires_in < '.$time.' and delete_flag=0';
		$elemeTokens = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($elemeTokens as $token){
			$dpid = $token['dpid'];
			$refresh_token = $token['refresh_token'];
			$key = ElmConfig::key;
			$secret = ElmConfig::secret;
			$token_url = ElmConfig::token;
			$header = array(
					"Authorization: Basic " . base64_encode(urlencode($key) . ":" . urlencode($secret)),
					"Content-Type: application/x-www-form-urlencoded; charset=utf-8",
					"Accept-Encoding: gzip");
			$body = array(
					"grant_type" => "refresh_token",
					"refresh_token"=>$refresh_token
			);
			$re = ElUnit::postHttpsHeader($token_url,$header,$body);
			$obj = json_decode($re);
			if(isset($obj->access_token)){
				$access_token = $obj->access_token;
				$expires_in = time() + $obj->expires_in;
				$refresh_token = $obj->refresh_token;
				$sql = 'update nb_eleme_token set access_token="'.$access_token.'",expires_in='.$expires_in.',refresh_token="'.$refresh_token.'" where dpid='.$dpid.' and delete_flag=0';
				$res = Yii::app()->db->createCommand($sql)->execute();
				Helper::writeLog('eleme_token--'.$dpid.'--'.$access_token);
			}
		}
	}
}