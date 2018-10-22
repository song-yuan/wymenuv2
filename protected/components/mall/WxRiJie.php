<?php 
/**
 * 
 * 
 * 微信端代金券类
 *
 * 
 * 
 */
class WxRiJie
{
	/**
	 * 
	 * 生成日结的Code 
	 * 根据Code进行日结
	 * create_at 执行日结操作的POS机编码
	 * poscode 执行日结操作的POS机编码
	 * btime 前一次日结时间
	 * etime 本次日结时间
	 * rjcode 日结编码的结构：dpid(4)+日期(8)+次数(2)
	 * 日结时检查软件是否到期
	 * 
	 */
	public static function setRijieCode($dpid,$create_at,$poscode,$btime,$etime,$rjcode){
		if(empty($dpid)||empty($create_at)||empty($poscode)||empty($btime)||empty($etime)||empty($rjcode)){
			return json_encode ( array (
					'status' => false,
					'msg' => '缺少参数'
			) );
		}
		$poscodeStatus = self::getPoscodeStatus($dpid,$poscode);
		
		$sql = 'select * from nb_rijie_code where dpid='.$dpid.' and rijie_code="'.$rjcode.'" and delete_flag=0';
		$result = Yii::app()->db->createCommand($sql)->queryRow();
		if($result){
			if($result['is_rijie']==1){
				return json_encode ( array (
						'status' => true,
						'msg' => '',
						'posstatus' => $poscodeStatus
				));
			}
			
			if($result['end_time'] < $etime){
				// 开始时间 小于传过来开始时间
				$sql = 'update nb_rijie_code set end_time="'.$etime.'" where lid='.$result['lid'].' and dpid='.$result['dpid'];
				$result = Yii::app()->db->createCommand($sql)->execute();
				if($result){
					return json_encode ( array (
							'status' => true,
							'msg' => '',
							'posstatus' => $poscodeStatus
					) );
				}else{
					return json_encode ( array (
							'status' => false,
							'msg' => '',
					) );
				}
			}else{
				return json_encode ( array (
						'status' => true,
						'msg' => '',
						'posstatus' => $poscodeStatus
				) );
			}
		}else{
			$sql = 'select * from nb_rijie_code where dpid='.$dpid.' and rijie_code!="'.$rjcode.'" and delete_flag=0 order by lid desc limit 1';
			$rijie = Yii::app()->db->createCommand($sql)->queryRow();
			if($rijie){
				$btime = $rijie['end_time'];
			}
			$lid = new Sequence("rijie_code");
			$id = $lid->nextval();
			$data = array(
					'lid'=>$id,
					'dpid'=>$dpid,
					'create_at'=>$create_at,
					'update_at'=>date('Y-m-d H:i:s',time()),
					'pos_code'=>$poscode,
					'begin_time'=>$btime,
					'end_time'=>$etime,
					'rijie_num'=>1,
					'rijie_code'=>$rjcode,
					'is_rijie'=>'0',
					'delete_flag'=>'0',
					'is_sync'=>'11111'
			);
			$result = Yii::app()->db->createCommand()->insert('nb_rijie_code',$data);
				
			if($result){
				return json_encode ( array (
						'status' => true,
						'msg' => '',
						'posstatus' => $poscodeStatus
				) );
			}
			return json_encode ( array (
					'status' => false,
					'msg' => '日结失败'
			) );
		}
	}
	public static function getPoscodeStatus($dpid,$poscode){
		$sql = 'select * from nb_poscode_fee where dpid='.$dpid.' and poscode="'.$poscode.'"';
		$poscodeStatus = Yii::app()->db->createCommand($sql)->queryRow();
		if(empty($poscodeStatus)){
			$poscodeStatus = '';
		}
		return $poscodeStatus;
	}
	/**
	 *
	 * 处理接单回调数据
	 * 记录 哪个收银员接的单
	 *
	 */
	public static function dealSyncDataCb($dpid) {
		$key = 'co-order-platformcb-'.(int)$dpid;
		$data = Yii::app()->redis->get($key);
		if(!empty($data)){
			$orderKeys = json_decode($data);
			foreach ($orderKeys as $orderKey){
				$keyArr = explode('-', $orderKey);
				$orderType = $keyArr[1];
				$accountNo = $keyArr[2];
				$userName = isset($keyArr[4])?$keyArr[4]:'';
				$sql = 'update nb_order set is_sync=0,username="'.$userName.'" where dpid='.$dpid.' and order_type='.$orderType.' and account_no="'.$accountNo.'" and is_sync!=0';
				$res = Yii::app ()->db->createCommand ( $sql )->execute ();
			}
			Yii::app()->redis->delete($key);
		}
	}
	/**
	 * 
	 * 跟据日结编码数据生成 日结统计数据
	 * 
	 */
	public static function rijieStatistics(){
		$db = Yii::app()->db;
		$sql = 'select * from nb_rijie_code where rijie_code!="null" and delete_flag =0 and is_rijie =0';
		$rijies = $db -> createCommand($sql)->queryAll();
		if(!empty($rijies)){
			foreach ($rijies as $rj){
				$dpid = $rj['dpid'];
				$create = $rj['rijie_code'];
				$poscode = $rj['pos_code'];
				$begin_time = $rj['begin_time'];
				$end_time = $rj['end_time'];
				$rjcode = $rj['rijie_code'];
				
				self::dealSyncDataCb($dpid);
				
				$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$dpid.' and k.create_at >="'.$begin_time.'" and k.create_at <="'.$end_time.'" group by k.account_no,k.create_at,k.user_id';
				$orders = $db->createCommand($sql)->queryColumn();
				if(empty($orders)){
					$sql = 'update nb_rijie_code set is_rijie = 1 where lid ='.$rj['lid'].' and dpid ='.$dpid;
					$result = $db->createCommand($sql)->execute();
					$msg = true;
					continue;
				}
				$ords = join(',', $orders);
				$sqlop = ' select t.dpid,t.paytype,t.payment_method_id,od.lid,od.username,od.order_type,od.user_id,ifnull(od.pad_code,od.user_id) as pad_code,sum(t.pay_amount) as all_price,count(distinct t.lid) as all_nums'
						.' from nb_order_pay t '
						.' left join '
							.'( select ps.pad_code,ord.* from nb_order ord '
								.' left join nb_pad_setting ps '
									.' on(ps.dpid = ord.dpid and ps.lid = ord.user_id)'
								.' where ord.dpid ='.$dpid.' and ord.lid in('.$ords.') ) '
							.' od '
							.' on( od.dpid = t.dpid and od.lid = t.order_id ) '
						.' where t.pay_amount >=0 and t.paytype != 11 and t.dpid ='.$dpid.' and t.order_id in('.$ords.') and od.order_type =0 '
						.' group by t.dpid,od.username,od.user_id '
						.' union all '
						.' select t.dpid,t.paytype,t.payment_method_id,od.lid,od.username,od.order_type,ifnull(null,0) as user_id,ifnull(null,0) as pad_code,sum(t.pay_amount) as all_price,count(distinct t.lid) as all_nums'
						.' from nb_order_pay t '
						.' left join nb_order od '
							.' on( od.dpid = t.dpid and od.lid = t.order_id ) '
						.' where t.pay_amount >=0 and t.paytype != 11 and t.dpid ='.$dpid.' and t.order_id in('.$ords.') and od.order_type !=0 '
						.' group by t.dpid,od.username '
						;
				$modelops = $db->createCommand($sqlop)->queryAll();
				
				
			$sqlor = ' select t.dpid,t.lid,t.username,t.order_type,t.user_id,ifnull(ps.pad_code,t.user_id) as pad_code,sum(t.should_total) as all_money,sum(t.reality_total) as all_price,count(distinct t.lid) as all_nums'
					.' from nb_order t '
					.' left join nb_pad_setting ps'
						.' on( ps.dpid = t.dpid and ps.lid = t.user_id) '
					.' where t.dpid ='.$dpid.' and t.lid in('.$ords.') and t.order_type =0 '
					.' group by t.dpid,t.username,t.user_id '
					.' union all '
					.' select t.dpid,t.lid,t.username,t.order_type,ifnull(null,0) as user_id,ifnull(null,0) as pad_code,sum(t.should_total) as all_money,sum(t.reality_total) as all_price,count(distinct t.lid) as all_nums'
					.' from nb_order t '
					.' where t.dpid ='.$dpid.' and t.lid in('.$ords.') and t.order_type !=0 '
					.' group by t.dpid,t.username '
					;
				$modelors = $db->createCommand($sqlor)->queryAll();
				
				$sql = ' select t.dpid,t.paytype,t.payment_method_id,od.lid,od.username,od.order_type,od.user_id,ifnull(od.pad_code,od.user_id) as pad_code,sum(t.pay_amount) as all_price,count(distinct t.order_id) as all_nums'
						.' from nb_order_pay t '
						.' left join '
							.'( select ps.pad_code,ord.* from nb_order ord '
								.' left join nb_pad_setting ps '
									.' on(ps.dpid = ord.dpid and ps.lid = ord.user_id)'
								.' where ord.dpid ='.$dpid.' and ord.lid in('.$ords.') ) '
							.' od '
							.' on( od.dpid = t.dpid and od.lid = t.order_id ) '
						.' where t.pay_amount >=0 and t.dpid ='.$dpid.' and t.order_id in('.$ords.') and od.order_type =0 '
						.' group by t.dpid,t.paytype,t.payment_method_id,od.username,od.user_id '
						.' union all '
						.' select t.dpid,t.paytype,t.payment_method_id,od.lid,od.username,od.order_type,od.user_id,ifnull(od.pad_code,od.user_id) as pad_code,sum(t.pay_amount) as all_price,count(distinct t.order_id) as all_nums'
						.' from nb_order_pay t '
						.' left join '
							.'( select ps.pad_code,ord.* from nb_order ord '
								.' left join nb_pad_setting ps '
									.' on(ps.dpid = ord.dpid and ps.lid = ord.user_id)'
								.' where ord.dpid ='.$dpid.' and ord.lid in('.$ords.') ) '
							.' od '
							.' on( od.dpid = t.dpid and od.lid = t.order_id ) '
						.' where t.pay_amount <0 and t.dpid ='.$dpid.' and t.order_id in('.$ords.') and od.order_type =0 '
						.' group by t.dpid,t.paytype,t.payment_method_id,od.username,od.user_id '
						.' union all '
						.' select t.dpid,t.paytype,t.payment_method_id,od.lid,od.username,od.order_type,ifnull(null,0) as user_id,ifnull(null,0) as pad_code,sum(t.pay_amount) as all_price,count(distinct t.order_id) as all_nums'
						.' from nb_order_pay t '
						.' left join nb_order od '
							.' on( od.dpid = t.dpid and od.lid = t.order_id ) '
						.' where t.pay_amount >=0 and t.dpid ='.$dpid.' and t.order_id in('.$ords.') and od.order_type !=0 '
						.' group by t.dpid,t.paytype,t.payment_method_id,od.username '
						.' union all '
						.' select t.dpid,t.paytype,t.payment_method_id,od.lid,od.username,od.order_type,ifnull(null,0) as user_id,ifnull(null,0) as pad_code,sum(t.pay_amount) as all_price,count(distinct t.order_id) as all_nums'
						.' from nb_order_pay t '
						.' left join nb_order od '
							.' on( od.dpid = t.dpid and od.lid = t.order_id ) '
						.' where t.pay_amount <0 and t.dpid ='.$dpid.' and t.order_id in('.$ords.') and od.order_type !=0 '
						.' group by t.dpid,t.paytype,t.payment_method_id,od.username ';
				
				//echo $sql;exit;
				$models = $db->createCommand($sql)->queryAll();
				
				$transaction = $db->beginTransaction();
				try{
					if($modelops){
						foreach ($modelops as $model){
							$sql = 'select * from nb_order_paytype_total where dpid='.$dpid.' and create_at="'.$create.'" and poscode="'.$model['pad_code'].'" and username="'.$model['username'].'" and rijie_code="'.$rjcode.'" and begin_time="'.$begin_time.'" and end_time="'.$end_time.'" and paytype=22 and payment_id=0 and pay_order_num="'.$model['all_nums'].'" and pay_amount_total="'.$model['all_price'].'"';
							$pResult = $db->createCommand($sql)->queryRow();
							if(!$pResult){
								$lid = new Sequence("order_paytype_total");
								$id = $lid->nextval();
								$data = array(
										'lid'=>$id,
										'dpid'=>$dpid,
										'create_at'=>$create,
										'update_at'=>date('Y-m-d H:i:s',time()),
										'poscode'=>$model['pad_code'],
										'username'=>$model['username'],
										'rijie_code'=>$rjcode,
										'begin_time'=>$begin_time,
										'end_time'=>$end_time,
										'paytype'=>'22',
										'payment_id'=>'0',
										'pay_order_num'=>$model['all_nums'],
										'pay_amount_total'=>$model['all_price'],
										'delete_flag'=>'0',
										'is_sync'=>'11111',
								);
								$command = $db->createCommand()->insert('nb_order_paytype_total',$data);
								if(!$command){
									throw new Exception('付款方式插入失败');
								}
							}
						}
					}
					if($modelors){
						foreach ($modelors as $model){
							$sql = 'select * from nb_order_paytype_total where dpid='.$dpid.' and create_at="'.$create.'" and poscode="'.$model['pad_code'].'" and username="'.$model['username'].'" and rijie_code="'.$rjcode.'" and begin_time="'.$begin_time.'" and end_time="'.$end_time.'" and paytype=20 and payment_id=0 and pay_order_num="'.$model['all_nums'].'" and pay_amount_total="'.$model['all_price'].'"';
							$pResult = $db->createCommand($sql)->queryRow();
							if(!$pResult){
								$lid = new Sequence("order_paytype_total");
								$id = $lid->nextval();
								$data = array(
										'lid'=>$id,
										'dpid'=>$dpid,
										'create_at'=>$create,
										'update_at'=>date('Y-m-d H:i:s',time()),
										'poscode'=>$model['pad_code'],
										'username'=>$model['username'],
										'rijie_code'=>$rjcode,
										'begin_time'=>$begin_time,
										'end_time'=>$end_time,
										'paytype'=>'20',
										'payment_id'=>'0',
										'pay_order_num'=>$model['all_nums'],
										'pay_amount_total'=>$model['all_price'],
										'delete_flag'=>'0',
										'is_sync'=>'11111',
								);
								$command = $db->createCommand()->insert('nb_order_paytype_total',$data);
								if(!$command){
									throw new Exception('付款方式插入失败');
								}
							}
						}
					}
					if($models){
						foreach ($models as $mod){
							$sql = 'select * from nb_order_paytype_total where dpid='.$dpid.' and create_at="'.$create.'" and poscode="'.$mod['pad_code'].'" and username="'.$mod['username'].'" and rijie_code="'.$rjcode.'" and begin_time="'.$begin_time.'" and end_time="'.$end_time.'" and paytype="'.$mod['paytype'].'" and payment_id="'.$mod['payment_method_id'].'" and pay_order_num="'.$mod['all_nums'].'" and pay_amount_total="'.$mod['all_price'].'"';
							$pResult = $db->createCommand($sql)->queryRow();
							if(!$pResult){
								$lid = new Sequence("order_paytype_total");
								$id = $lid->nextval();
								$data = array(
										'lid'=>$id,
										'dpid'=>$dpid,
										'create_at'=>$create,
										'update_at'=>date('Y-m-d H:i:s',time()),
										'poscode'=>$mod['pad_code'],
										'username'=>$mod['username'],
										'rijie_code'=>$rjcode,
										'begin_time'=>$begin_time,
										'end_time'=>$end_time,
										'paytype'=>$mod['paytype'],
										'payment_id'=>$mod['payment_method_id'],
										'pay_order_num'=>$mod['all_nums'],
										'pay_amount_total'=>$mod['all_price'],
										'delete_flag'=>'0',
										'is_sync'=>'11111',
								);
								//var_dump($data);exit;
								$command = $db->createCommand()->insert('nb_order_paytype_total',$data);
								if(!$command){
									throw new Exception('付款方式插入失败');
								}
							}
						}
					}
					$sql = 'update nb_order set order_status = 8 where lid in('.$ords.') and dpid ='.$dpid.' and order_status in(3,4)';
					Yii::app()->db->createCommand($sql)->execute();
					
					$sql = 'update nb_rijie_code set is_rijie = 1 where lid ='.$rj['lid'].' and dpid ='.$dpid;
					$result = $db->createCommand($sql)->execute();
					if(!$result){
						throw new Exception('更新日结编码失败');
					}
					$transaction->commit();
					$msg = true;
				}catch (Exception $e) {
					$transaction->rollback();
					$message = $e->getMessage();
					Helper::writeLog('日结失败:'.$dpid.' 日结编码:'.$rjcode.' 错误信息:'.$message);
				}
			}
		}
	}
	/**
	 * 查询前一天 盘点数据进行盘点
	 */
	public static function dealPandian(){
		$db = Yii::app()->db;
		$time = time();
		$sql = 'select * from nb_stock_taking where status=0 and delete_flag=0';
		$stockTakings = $db->createCommand($sql)->queryAll();
		foreach ($stockTakings as $stockTaking){
			$sqlArr = array();
			$logid = $stockTaking['lid'];
			$dpid = $stockTaking['dpid'];
			$sttype = $stockTaking['type'];
			$createAt = $stockTaking['create_at'];
	
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
				if($stockTakingDetail['number']>0){
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
						$sql = 'select * from nb_product_material_stock where material_id='.$id.' and dpid='.$dpid.' and delete_flag=0 order by create_at desc limit 1';
						$stocks = $db->createCommand($sql)->queryRow();
						if(empty($stocks)){
							continue;
						}
						//盘点操作，当盘点的库存比理论库存多时，直接在后进的库存批次上加上此次的盘点的差值。。。
						if($stocks['batch_stock'] == 0){
							$unit_price = '0';
						}else{
							$unit_price = $stocks['stock_cost'] / $stocks['batch_stock'];
						}
						$diffPrice = $unit_price*$difference;
	
						//下面是对该次盘点进行的操作。。。
						$sql = 'update nb_product_material_stock set stock=stock+'.$difference.' where lid='.$stocks['lid'].' and dpid='.$stocks['dpid'];
						array_push($sqlArr, $sql);
	
						$sql = 'update nb_stock_taking_detail set reality_stock='.$systemNum.',number='.$difference.',material_stock_id='.$stocks['lid'].' where lid='.$stockTakingDetail['lid'].' and dpid='.$stockTakingDetail['dpid'];
						array_push($sqlArr, $sql);
	
	
						$se = new Sequence("material_stock_log");
						$lid = $se->nextval();
						$sql = 'insert into nb_material_stock_log (lid,dpid,create_at,update_at,type,logid,material_id,stock_num,original_num,unit_price,resean)'.
							   ' values ('.$lid.','.$dpid.','.$createAt.','.date('Y-m-d H:i:s',$time).',3,'.$logid.','.$id.','.$difference.','.$systemNum.','.$unit_price.',"盘点溢出")';
						array_push($sqlArr, $sql);
					}else{
						//盘点库存小于系统的库存  查出所有库存不为0批次
						$sql = 'select * from nb_product_material_stock where stock!=0 and dpid ='.$dpid.' and material_id = '.$id.' and delete_flag = 0 order by create_at asc';
						$stock2 = $db->createCommand($sql)->queryAll();
							
						if(empty($stock2)){
							// 如果所有批次都为0 在最后这批扣减
							$sql = 'select * from nb_product_material_stock where material_id='.$id.' and dpid='.$dpid.' and delete_flag=0 order by create_at desc limit 1';
							$stocks = $db->createCommand($sql)->queryRow();
							if(empty($stocks)){
								continue;
							}
							if($stocks['batch_stock'] == 0){
								$unit_price = '0';
							}else{
								$unit_price = $stocks['stock_cost'] / $stocks['batch_stock'];
							}
							$diffPrice = $unit_price*$difference;
	
							//下面是对该次盘点进行的操作。。。
							$sql = 'update nb_product_material_stock set stock=stock+'.$difference.' where lid='.$stocks['lid'].' and dpid='.$stocks['dpid'];
							array_push($sqlArr, $sql);
	
							$sql = 'update nb_stock_taking_detail set reality_stock='.$systemNum.',number='.$difference.',material_stock_id='.$stocks['lid'].' where lid='.$stockTakingDetail['lid'].' and dpid='.$stockTakingDetail['dpid'];
							array_push($sqlArr, $sql);
								
							$se = new Sequence("material_stock_log");
							$lid = $se->nextval();
							$sql = 'insert into nb_material_stock_log (lid,dpid,create_at,update_at,type,logid,material_id,stock_num,original_num,unit_price,resean)'.
									' values ('.$lid.','.$dpid.','.$createAt.','.date('Y-m-d H:i:s',$time).',3,'.$logid.','.$id.','.$difference.','.$systemNum.','.$unit_price.',"盘点损失")';
							array_push($sqlArr, $sql);
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
								array_push($sqlArr, $sql);
									
								$sql = 'update nb_stock_taking_detail set reality_stock='.$systemNum.',number='.$difference.',material_stock_id='.$stock['lid'].' where lid='.$stockTakingDetail['lid'].' and dpid='.$stockTakingDetail['dpid'];
								array_push($sqlArr, $sql);
									
								$diffPrice += $unit_price*$minusnum;
									
								if($minusnum!=0){
									$se = new Sequence("material_stock_log");
									$lid = $se->nextval();
									$sql = 'insert into nb_material_stock_log (lid,dpid,create_at,update_at,type,logid,material_id,stock_num,original_num,unit_price,resean)'.
											' values ('.$lid.','.$dpid.','.$createAt.','.date('Y-m-d H:i:s',$time).',3,'.$logid.','.$id.','.(-$changestock).','.$stockori.','.$unit_price.',"盘点损失")';
									array_push($sqlArr, $sql);
								}
								break;
							}else{
								$sql = 'update nb_product_material_stock set stock=0 where lid ='.$stock['lid'].' and dpid ='.$stock['dpid'];
								array_push($sqlArr, $sql);
								$diffPrice += -$unit_price*$stockori;
									
								$se = new Sequence("material_stock_log");
								$lid = $se->nextval();
								$sql = 'insert into nb_material_stock_log (lid,dpid,create_at,update_at,type,logid,material_id,stock_num,original_num,unit_price,resean)'.
										' values ('.$lid.','.$dpid.','.$createAt.','.date('Y-m-d H:i:s',$time).',3,'.$logid.','.$id.','.(-$stockori).','.$stockori.','.$unit_price.',"盘点损失")';
								array_push($sqlArr, $sql);
							}
						}
					}
					// 插入盘点统计信息
					$totalNum = $damageNum + $salseNum;
					$se = new Sequence("stock_taking_statistics");
					$lid = $se->nextval();
					$sql = 'insert into nb_stock_taking_statistics (lid,dpid,create_at,update_at,type,material_id,sales_name,stock_taking_id,prestock_taking_num,stockin_num,stockin_price,damage_num,damage_price,salse_num,salse_price,total_num,system_num,stock_taking_num,stock_taking_difnum,stock_taking_difprice)'.
							' values ('.$lid.','.$dpid.','.$createAt.','.date('Y-m-d H:i:s',$time).','.$sttype.','.$id.','.$salesName.','.$logid.','.$presystemNum.','.$stockinNum.','.$stockinPrice.','.$damageNum.','.$damagePrice.','.$salseNum.','.$salsePrice.','.$totalNum.','.$systemNum.','.$nowNum.','.$difference.','.$diffPrice.')';
					array_push($sqlArr, $sql);
	
				}
			}
			$sql = 'update nb_stock_taking set status=1 where lid='.$logid.' and dpid='.$dpid;
			array_push($sqlArr, $sql);
			
			$transaction = $db->beginTransaction();
			try
			{
				foreach ($sqlArr as $sql){
					echo $sql;
					$db->createCommand($sql)->execute();
				}
				$transaction->commit();
			}catch (Exception $e) {
				$transaction->rollback(); //如果操作失败, 数据回滚
				echo $e->getMessage();
			}
		}
	}
}