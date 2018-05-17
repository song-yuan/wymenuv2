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
				Yii::app ()->db->createCommand ( $sql )->execute ();
			}
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
					$msg = false;
				}
			}
			return $msg;
		}
		return true;
	}
}