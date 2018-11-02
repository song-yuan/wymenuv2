<?php

class SqbpayController extends Controller
{
	public function actionWappayresultceshi(){
		$dpid = '0000000027';
		$orderid = Yii::app()->request->getParam('orderid');
		
		$now = time();
		$rand = rand(100,999);
		//$orderId = $now.'-'.$dpid.'-'.$rand;
		
		$orderId = $orderid.'-0000000027';
		
		$company = WxCompany::get($dpid);
		$data = array(
				'dpid' => $dpid,
				'pay_type' => 0,
				'out_trade_no' => $orderId,
				'total_fee' => '0.01',
		);
		$result = MicroPayModel::insert($data);
		$reflect = '000000026-0000027';
		
		if($result['status']){
			$result = SqbPay::preOrder(array(
					'dpid'=>$dpid,
					'client_sn'=>$orderId,
					'total_amount'=>'0.01',
					'payway'=>'3',
					'subject'=>'wymenu',
					'operator'=>'admin',
					'reflect'=>$reflect,
					'notify_url'=>'http://menu.wymenu.com/wymenuv2/sqbpay/wappayresult/companyId/0000000026/dpid/000000027',
					'return_url'=>'http://menu.wymenu.com/wymenuv2/sqbpay/wappayreturn/companyId/0000000026/dpid/000000027',
			));
		}else{
			echo 'error';
		}
	}
	// 预下单
	public function actionPrecreate(){
		$data = Yii::app()->request->getPost('data');
		$result = SqbPay::precreate($data);
		echo json_encode($result);exit;
	}
	public function actionWappayreturn(){
		$is_success = Yii::app()->request->getParam('is_success');
		$status = Yii::app()->request->getParam('status');
		$sign = Yii::app()->request->getParam('sign');
		$reflect = Yii::app()->request->getParam('reflect');
		$client_sn = Yii::app()->request->getParam('client_sn');
		Helper::writeLog('获取参数：is:'.$is_success.';reflect:'.$reflect.';client_sn:'.$client_sn);
		
		$reflect = json_decode($reflect);
		//var_dump($reflect);exit;
		$companyId = $reflect->companyId;
		$dpid = $reflect->dpid;
		//Helper::writeLog('获取参数：'.$companyId.';'.$dpid);
		if($is_success == 'F'){
			$error_code = Yii::app()->request->getParam('error_code');
			$error_message = Yii::app()->request->getParam('error_message');
			$terminal_sn = Yii::app()->request->getParam('terminal_sn');
			$result_message = Yii::app()->request->getParam('result_message');
			Helper::writeLog('获取参数：'.$error_code.';'.$error_message.':terminal_sn:'.$terminal_sn.':result_message:'.$result_message);
			
			//exit;
			$this->redirect(array('/user/orderinfo',
					'orderId'=>$client_sn,
					'orderDpid'=>$dpid,
					'companyId'=>$companyId,
			));
		}else{
			//Helper::writeLog('IS_success:T');
			$terminal_sn = Yii::app()->request->getParam('terminal_sn');
			$sn = Yii::app()->request->getParam('sn');
			$trade_no = Yii::app()->request->getParam('trade_no');
			$result_code = Yii::app()->request->getParam('result_code');
			$result_message = Yii::app()->request->getParam('result_message');
			$total_amount = Yii::app()->request->getParam('total_amount');
			
			$data = '{"收钱吧同步返回参数":"T";"is_success":"'.$is_success.'";"client_sn":"'.$client_sn.'";"trade_no":"'.$trade_no.'";"status":"'.$status.'";"result_code":"'.$result_code.'";}';
			Helper::writeLog($data);
			
			//接口调用成功,查询订单状态...
			$account_nos = explode('-',$client_sn);
			$orderid = $account_nos[0];
			$orderdpid = $account_nos[1];
			//Helper::writeLog('支付成功!orderid:['.$orderid.'],dpid:['.$orderdpid.']');
			
			$i = 0;
			$orderstatus = true;
			do{
				sleep(2);
				$i++;
				$sql = 'select * from nb_order where dpid ='.$orderdpid.' and lid ='.$orderid;
				$orders = Yii::app()->db->createCommand($sql)
				->queryRow();
				if(!empty($orders)){
					if(in_array($orders['order_status'],array(3,4,8))){
						
						
						$orderstatus = false;
						Helper::writeLog('轮询次数：'.$i.'结果：已支付！');
						//跳转到该页面。
						$this->redirect(array('/user/orderinfo',
								'orderId'=>$client_sn,
								'orderDpid'=>$dpid,
								'companyId'=>$companyId,
						));
						
					}else{
						$orderstatus = true;
						//Helper::writeLog('轮询次数：'.$i.'结果：未支付！');
						//echo '';
						if($i==50){
							Helper::writeLog('轮询次数：'.$i.'结果：错误！'.$client_sn.';dpid:'.$dpid);
							$this->redirect(array('/user/orderinfo',
									'orderId'=>$client_sn,
									'orderDpid'=>$dpid,
									'companyId'=>$companyId,
							));

						}
					}
				}else{
					if($i==50){
						Helper::writeLog('轮询次数：'.$i.'结果：错误！'.$client_sn.';dpid:'.$dpid);
						$this->redirect(array('/user/orderinfo',
								'orderId'=>$client_sn,
								'orderDpid'=>$dpid,
								'companyId'=>$companyId,
						));
					
					}
				}
			}
			while ($i<=50&&$orderstatus);
		}
	}
	public function actionWappayresult(){
		//收钱吧异步回调数据接收及解析...
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
		//Helper::writeLog('异步通知的参数:'.$xml);
		/*$mxl如下：
		 * {
		 * "sn":"7895259485469125",*
		 * "client_sn":"1490611690-0000000027-409",*
		 * "client_tsn":"1490611690-0000000027-409",
		 * "ctime":"1490611690929",*
		 * "status":"FAIL_CANCELED",*
		 * "payway":"3",*
		 * "sub_payway":"3",*
		 * "order_status":"PAY_CANCELED",*
		 * "payer_uid":"",
		 * "trade_no":"6521100249201703286121293325",
		 * "total_amount":"1",*
		 * "net_amount":"0",*
		 * "finish_time":"1490611957891",*
		 * "subject":"wymenu",*
		 * "store_id":"f35d19cb-a316-499f-b43d-76b882d7caf5",*
		 * "terminal_id":"1cfcd666-6aa8-42fc-b031-b3eadbf2c9ed",*
		 * "operator":"admin"*
		 * }
		 * 
		 * */
		//$obj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
		$obj = json_decode($xml,true);
		$sn = $obj['sn'];
		$client_sn = $obj['client_sn'];
		$client_tsn = $obj['client_tsn'];
		$ctime = $obj['ctime'];
		$status = $obj['status'];
		$payway = $obj['payway'];
		$sub_payway = $obj['sub_payway'];
		$order_status = $obj['order_status'];
		$payer_uid = $obj['payer_uid'];
		$trade_no = $obj['trade_no'];
		$total_amount = $obj['total_amount'];
		$net_amount = $obj['net_amount'];
		$finish_time = $obj['finish_time'];
		$subject = $obj['subject'];
		$store_id = $obj['store_id'];
		$terminal_id = $obj['terminal_id'];
		$operator = $obj['operator'];
		
		//订单号解析orderID和dpid
		$account_nos = explode('-',$client_sn);
		$orderid = $account_nos[0];
		$orderdpid = $account_nos[1];
		//Helper::writeLog('进入方法'.$sn.';店铺:'.$companyId);
		
		$sql = 'select * from nb_notify_wxwap where dpid ='.$orderdpid.' and sn="'.$sn.'"';
		//Helper::writeLog('进入方法'.$sql);
		$notify = Yii::app()->db->createCommand($sql)->queryRow();
		
		
		if(!empty($notify)){
			if($order_status == $notify['order_status']){
				//Helper::writeLog('相同的'.$sn);
			}else{
				//Helper::writeLog('不同的1:['.$sn.']');
				//像微信公众号支付记录表插入记录...
				$se = new Sequence ( "notify_wxwap" );
				$notifyWxwapId = $se->nextval ();
				$notifyWxwapData = array (
						'lid' => $notifyWxwapId,
						'dpid' => $orderdpid,
						'create_at' => date ( 'Y-m-d H:i:s', time()),
						'update_at' => date ( 'Y-m-d H:i:s', time()),
						'sn' => $sn,
						'client_sn' => $client_sn,
						'client_tsn' => $client_tsn,
						'ctime' => $ctime,
						'status' => $status,
						'payway' => $payway,
						'sub_payway' => $sub_payway,
						'order_status' => $order_status,
						'payer_uid' => $payer_uid,
						'trade_no' => $trade_no,
						'total_amount' => $total_amount,
						'net_amount' => $net_amount,
						'finish_time' => $finish_time,
						'subject' => $subject,
						'store_id' => $store_id,
						'terminal_id' => $terminal_id,
						'operator' => $operator,
				);
				$result = Yii::app ()->db->createCommand ()->insert ( 'nb_notify_wxwap', $notifyWxwapData );
				//Helper::writeLog('不同的2:['.$sn.']');
			}
		}else{
			$sql = 'select * from nb_order where dpid ='.$orderdpid.' and lid ='.$orderid;
			$orders = Yii::app()->db->createCommand($sql)->queryRow();
			if(!empty($orders)){
				if($orders['order_type'] == '1' || $orders['order_type'] == '6' || $orders['order_type'] == '3' ){
					$pay_type = '12';
				}elseif($orders['order_type'] == '2'){
					$pay_type = '13';
				}else{
					$pay_type = '1';
				}
				$sql = 'select * from nb_order_pay where dpid ='.$orderdpid.' and order_id ='.$orderid.' and account_no ="'.$orders['account_no'].'" and paytype ='.$pay_type;
				$ordpays = Yii::app()->db->createCommand($sql)->queryRow();

				$se = new Sequence ( "order_pay" );
				$orderpayId = $se->nextval();
				$orderpayData = array (
						'lid' => $orderpayId,
						'dpid' => $orderdpid,
						'create_at' => $orders['create_at'],
						'update_at' => $orders['update_at'],
						'order_id' => $orderid,
						'account_no' => $orders['account_no'],
						'pay_amount' => number_format($total_amount/100,2),
						'paytype' => $pay_type,
						'remark' => $client_sn,
				);
				$result = Yii::app ()->db->createCommand ()->insert ( 'nb_order_pay', $orderpayData );
					
			}else{
				Helper::writeLog('未查询到该条订单：'.$orderid);
			}
			
			//Helper::writeLog('第一次1:['.$sn.']');
			//像微信公众号支付记录表插入记录...
			$se = new Sequence("notify_wxwap");
			$notifyWxwapId = $se->nextval();
			//Helper::writeLog('第一次1:['.$sn.'],插入ID：'.$notifyWxwapId);
			$notifyWxwapData = array (
					'lid' => $notifyWxwapId,
					'dpid' => $orderdpid,
					'create_at' => date ( 'Y-m-d H:i:s', time()),
					'update_at' => date ( 'Y-m-d H:i:s', time()),
					'sn' => $sn,
					'client_sn' => $client_sn,
					'client_tsn' => $client_tsn,
					'ctime' => $ctime,
					'status' => $status,
					'payway' => $payway,
					'sub_payway' => $sub_payway,
					'order_status' => $order_status,
					'payer_uid' => $payer_uid,
					'trade_no' => $trade_no,
					'total_amount' => $total_amount,
					'net_amount' => $net_amount,
					'finish_time' => $finish_time,
					'subject' => $subject,
					'store_id' => $store_id,
					'terminal_id' => $terminal_id,
					'operator' => $operator,
			);
			$data = json_encode($notifyWxwapData);
			$result = Yii::app ()->db->createCommand ()->insert('nb_notify_wxwap',$notifyWxwapData);
			if($result){

				if($order_status == 'PAID'){
					//订单成功支付...
					Helper::writeLog('支付成功!orderid:['.$orderid.'],dpid:['.$orderdpid.']');
					$orders = WxOrder::getOrder($orderid, $orderdpid);
					if(!empty($orders)){
						$user = WxBrandUser::getFromUserId($orders['user_id']);
						WxOrder::dealOrder($user, $orders);
						WxOrder::pushOrderToRedis($orders);
					}
				}
			}
			//Helper::writeLog('第一次2:['.$result.']');
		}
	}
	/**
	 * 预订单通知地址
	 * 
	 */
	public function actionPreNotice(){
		$notify = new SqbNotify();
		$notify->Handle('pre-create');
		exit;
	}

	public function actionCreateOrderresult(){
		
		//Helper::writeLog('预下单回调通知参数：'.date('Y-m-d H:i:s',time()));
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
		$obj = json_decode($xml,true);
		
		$client_sn = $obj['client_sn'];
		$order_status = $obj['order_status'];
		if($order_status == 'PAID'){
			$sqlm = 'select * from nb_message_order where accountno='.$client_sn;
			$ms = Yii::app()->db->createCommand($sqlm)->queryRow();
			$sqlme = 'select lid from nb_message where accountno='.$client_sn;
			$mss = Yii::app()->db->createCommand($sqlme)->queryRow();
			if(!empty($mss)){
				
			}else{
				if(!empty($ms)){
				
					$sql = 'update nb_message_order set update_at="'.date('Y-m-d H:i:s',time()).'",pay_status =1 where accountno='.$client_sn;
					$res = Yii::app()->db->createCommand($sql)->execute();
					if($res){
						$sql = 'select * from nb_message_set where lid ='.$ms['message_set_id'];
						$re = Yii::app()->db->createCommand($sql)->queryRow();
						if(!empty($re)){
							$all = $re['all_message_no'] + $re['send_message_no'];
							
							$se = new Sequence('message');
							$dat = array(
								'lid' => $se->nextval(),
								'dpid' => $ms['dpid'],
								'create_at' => date('Y-m-d H:i:s',time()),
								'update_at' => date('Y-m-d H:i:s',time()),
								'accountno' => $client_sn,
								'downdate_at' => date('Y-m-d H:i:s',strtotime("+".$re['downdate']." years", time())),
								'buy_no' => $re['all_message_no'],
								'sent_no' => $re['send_message_no'],
								'odd_message_no' => $all,
								
							);
							$result = Yii::app ()->db->createCommand ()->insert('nb_message',$dat);
						}
						
					}else{
						Helper::writeLog('购买短息套餐支付成功,但更改状态失败，'.$client_sn);
					}
				}
			}
			return 'SUCCESS';
		}

	}
}