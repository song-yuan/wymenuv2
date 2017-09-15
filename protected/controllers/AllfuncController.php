<?php

class AllfuncController extends Controller
{
	public function actionRijieCode(){
		/*
		 * POS日结时调用该方法，插入日结记录表
		 * 
		 * */
		
		$dpid = Yii::app()->request->getParam('dpid','');/*该店铺id*/
		$create_at = Yii::app()->request->getParam('create_at','');/*执行日结操作是的时间*/
		$poscode = Yii::app()->request->getParam('poscode','');/*执行日结操作的POS机编码*/
		$begintime = Yii::app()->request->getParam('btime','');/*前一次日结时间*/
		$endtime = Yii::app()->request->getParam('etime','');/*本次日结时间*/
		//$rjnum = Yii::app()->request->getParam('rjnum','');/*本次日结次数，默认为1*/
		$rjcode = Yii::app()->request->getParam('rjcode','');/*日结编码的结构：dpid(4)+日期(8)+次数(2)*/
		
		$is_true = true;
		
		if((!empty($dpid))&&$is_true&&(!empty($poscode))){
			$is_true = true;
		}else{
			$is_true = false;
		}
		
		if((!empty($begintime))&&$is_true&&(!empty($endtime))&&(!empty($create_at))){
			$is_true = true;
		}else{
			$is_true = false;
		}
		
		if((!empty($rjcode))&&$is_true){
			$is_true = true;
		}else{
			$is_true = false;
		}
		//var_dump($is_true);exit;
		if($is_true){
			$db = Yii::app()->db;
			$lid = new Sequence("rijie_code");
			$id = $lid->nextval();
			$data = array(
					'lid'=>$id,
					'dpid'=>$dpid,
					'create_at'=>$create_at,
					'update_at'=>date('Y-m-d H:i:s',time()),
					'pos_code'=>$poscode,
					'begin_time'=>$begintime,
					'end_time'=>$endtime,
					'rijie_num'=>1,
					'rijie_code'=>$rjcode,
					'is_rijie'=>'0',
					'delete_flag'=>'0',
					'is_sync'=>'11111',
			);
			$command = $db->createCommand()->insert('nb_rijie_code',$data);
			if($command){
				return true;
			}else{
				$msg = 'dpid:'.$dpid.';create_at:'.$create_at.';poscode:'.$poscode.';btime:'.$begintime.';etime:'.$endtime.';rjcode:'.$rjcode;
				Helper::writeLog('日结失败(保存有误)：'.$msg);
				return false;
			}
		}else{
			$msg = 'dpid:'.$dpid.';create_at:'.$create_at.';poscode:'.$poscode.';btime:'.$begintime.';etime:'.$endtime.';rjcode:'.$rjcode;
			Helper::writeLog('日结失败(参数有误)：'.$msg);
			return false;
		}
	}
	
	public function actionRijieing(){
		/*
		 * 调用该方法进行日结查询操作
		 * 然后进行遍历操作统计支付金额
		 * 
		 * */
		$db = Yii::app()->db;
		$sql = 'select * from nb_rijie_code where delete_flag =0 and is_rijie =0';
		$rijies = $db -> createCommand($sql)->queryAll();
		//var_dump($rijies);exit;
		if($rijies){
			foreach ($rijies as $rj){
				$dpid = $rj['dpid'];
				$create = $rj['create_at'];
				$poscode = $rj['pos_code'];
				$begin_time = $rj['begin_time'];
				$end_time = $rj['end_time'];
				$rjcode = $rj['rijie_code'];
				
				$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$dpid.' and k.create_at >="'.$begin_time.'" and k.create_at <="'.$end_time.'" group by k.account_no,k.create_at,k.user_id';
				
				$orders = $db->createCommand($sql)->queryAll();
				$ords ='0000000000';
				foreach ($orders as $order){
					$ords = $ords .','.$order['lid'];
				}
				
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
				if($modelops){
					foreach ($modelops as $model){
					
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
						//var_dump($data);exit;
						$command = $db->createCommand()->insert('nb_order_paytype_total',$data);
					}
				}
				
				
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
				if($modelors){
					foreach ($modelors as $model){
							
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
						//var_dump($data);exit;
						$command = $db->createCommand()->insert('nb_order_paytype_total',$data);
					}
				}
				
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
				//var_dump($models);exit;
				if($models){
					foreach ($models as $mod){
						
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
					}
					
					$result = $db->createCommand('update nb_rijie_code set is_rijie = 1 where lid ='.$rj['lid'].' and dpid ='.$dpid)->execute();
				}

			}
		}
		
	}
	
	public function actionSelfrj(){
		$etime = Yii::app()->request->getParam('etime','2017-09-15 23:59:59');
		$btime = Yii::app()->request->getParam('btime','2017-01-01 00:00:00');
		$dpids = Yii::app()->request->getParam('dpid','0');
		$db = Yii::app()->db;
		$sql = 'select * from nb_company where type =1 and delete_flag =0 and dpid in('.$dpids.')';
		$coms = $db->createCommand($sql)->queryAll();
		if($coms){
			foreach ($coms as $c){
				$dpid = $c['dpid'];
				//var_dump($dpid);
				$sqlor = 'select DATE_FORMAT(t.create_at,"%Y-%m-%d") as times, t.* from nb_order t where t.dpid ='.$dpid.' and t.order_status in(3,4,8) and t.create_at <= "'.$etime.'" and t.create_at >= "'.$btime.'" group by DATE_FORMAT(t.create_at,"%Y-%m-%d")';
				$orders = $db->createCommand($sqlor)->queryAll();
				
				$sqlpos = 'select t.* from nb_pad_setting t where t.dpid ='.$dpid.' and t.delete_flag =0';
				$pos = $db->createCommand($sqlpos)->queryRow();
				
				if($orders && $pos){
					 foreach ($orders as $order){
					 	$times = str_replace('-','',$order['times']);
					 	$rjcode = substr("0000".$dpid,-4).$times.'01';
					 	
					 	$rj = $db->createCommand('select * from nb_rijie_code where dpid ='.$dpid.' and rijie_code ='.$rjcode)->queryAll();
					 	if(!empty($rj)){
					 		
					 	}else{
						 	$lid = new Sequence("rijie_code");
						 	$id = $lid->nextval();
						 	$data = array(
						 			'lid'=>$id,
						 			'dpid'=>$dpid,
						 			'create_at'=>$order['times'].' 00:00:00',
						 			'update_at'=>date('Y-m-d H:i:s',time()),
						 			'pos_code'=>$pos['pad_code'],
						 			'begin_time'=>$order['times'].' 00:00:00',
						 			'end_time'=>$order['times'].' 23:59:59',
						 			'rijie_num'=>1,
						 			'rijie_code'=>$rjcode,
						 			'is_rijie'=>'0',
						 			'delete_flag'=>'0',
						 			'is_sync'=>'11111',
						 	);
						 	$command = $db->createCommand()->insert('nb_rijie_code',$data);
					 	}
					 }
				}
			}
			//exit;
			Yii::app()->end(json_encode(array("status"=>"true",'msg'=>'成功')));
		}
	}
}