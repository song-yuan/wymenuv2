<?php

class MtpayController extends Controller
{
	public function actionMtwappay(){
		//$result = SqbPay::pay($dpid,$_POST);
		//$obj = json_decode($result,true);
		$data = array(
				'outTradeNo'=>'20180131'.'-0000000027'.'-'.rand(100,999),
				'totalFee'=>'1',
				'subject'=>'ydc',
				'body'=>'ydc',
				'channel'=>'wx_scan_pay',
				'expireMinutes'=>'3',
				'tradeType'=>'JSAPI',
				'notifyUrl'=>'http://menu.wymenu.com/wymenuv2/mtpay/mtwappayresult',
		);
		
		$result = MtpPay::preOrder($data);
	}

	public function actionMtwappayresult(){
		$data=file_get_contents("php://input");
		//Helper::writeLog('美团result'.$data);
		$accountno = $_POST['outTradeNo'];
		$transactionId = $_POST['transactionId'];
		$totalFee = $_POST['totalFee'];
		//订单号解析orderID和dpid
		$account_nos = explode('-',$accountno);
		$orderid = $account_nos[0];
		$orderdpid = $account_nos[1];
		Helper::writeLog('账单号:'.$accountno.';第三方订单号:'.$orderid);
	
		$sql = 'select * from nb_mtpay_info where dpid ='.$orderdpid.' and accountno="'.$accountno.'" and transactionId ="'.$transactionId.'"';
		Helper::writeLog('查询：'.$sql);
		$notify = Yii::app()->db->createCommand($sql)->queryRow();

		$ords = false;$nots = false;
		if(!empty($notify)){
			Helper::writeLog('未查到信息！');
		}else{
			Helper::writeLog('查询支付信息！');
			$results = MtpPay::query(array(
					'outTradeNo'=>$accountno
			));
			//Helper::writeLog('返回支付信息！'.$results);
			$return_code = $results['return_code'];
			$result_code = $results['result_code'];
			$result_msg = $results['result_msg'];
				
			if($result_msg == 'ORDER_SUCCESS'){
				//像微信公众号支付记录表插入记录...
				$se = new Sequence("mtpay_info");
				$notifyWxwapId = $se->nextval();
				$notifyWxwapData = array (
						'lid' => $notifyWxwapId,
						'dpid' => $orderdpid,
						'create_at' => date ( 'Y-m-d H:i:s', time()),
						'update_at' => date ( 'Y-m-d H:i:s', time()),
						'accountno' => $accountno,
						'transactionId' => $transactionId,
						'content' => $data,
						'pay_status' => $result_msg
				);
				//$data = json_encode($notifyWxwapData);
				$result = Yii::app ()->db->createCommand ()->insert('nb_mtpay_info',$notifyWxwapData);
				Helper::writeLog('插入数据库：'.$result);
				if($result){
					//订单成功支付...
					Helper::writeLog('支付成功!orderid:['.$orderid.'],dpid:['.$orderdpid.']');
					$sql = 'select * from nb_order where dpid ='.$orderdpid.' and lid ='.$orderid;
					$orders = Yii::app()->db->createCommand($sql)->queryRow();
					if(!empty($orders)){
						$user = WxBrandUser::getFromUserId($orders['user_id']);
						WxOrder::dealOrder($user, $orders);
						$nots = true;
					}
				}
			}else{
				$i=1;
				$j=true;
				do{
					sleep(1);
					$i++;
					$results = MtpPay::query(array(
							'outTradeNo'=>$accountno
					));
					if($result_msg == 'ORDER_SUCCESS'){
						//像微信公众号支付记录表插入记录...
						$se = new Sequence("mtpay_info");
						$notifyWxwapId = $se->nextval();
						//Helper::writeLog('第一次1:['.$sn.'],插入ID：'.$notifyWxwapId);
						$notifyWxwapData = array (
								'lid' => $notifyWxwapId,
								'dpid' => $orderdpid,
								'create_at' => date ( 'Y-m-d H:i:s', time()),
								'update_at' => date ( 'Y-m-d H:i:s', time()),
								'accountno' => $accountno,
								'transactionId' => $transactionId,
								'content' => $data,
								'pay_status' => $result_msg
						);
						//$data = json_encode($notifyWxwapData);
						$result = Yii::app ()->db->createCommand ()->insert('nb_mtpay_info',$notifyWxwapData);
						if($result){
							//订单成功支付...
							Helper::writeLog('支付成功!orderid:['.$orderid.'],dpid:['.$orderdpid.']');
							//exit;
							$sql = 'select * from nb_order where dpid ='.$orderdpid.' and lid ='.$orderid;
							$orders = Yii::app()->db->createCommand($sql)->queryRow();
							if(!empty($orders)){
								$user = WxBrandUser::getFromUserId($orders['user_id']);
								WxOrder::dealOrder($user, $orders);
								$nots = true;
							}
						}
						$j=false;
					}
				}while (($i<=5)&&$j);
				if(($i==5)&&$j){
					//像微信公众号支付记录表插入记录...
					$se = new Sequence("mtpay_info");
					$notifyWxwapId = $se->nextval();
					//Helper::writeLog('第一次1:['.$sn.'],插入ID：'.$notifyWxwapId);
					$notifyWxwapData = array (
							'lid' => $notifyWxwapId,
							'dpid' => $orderdpid,
							'create_at' => date ( 'Y-m-d H:i:s', time()),
							'update_at' => date ( 'Y-m-d H:i:s', time()),
							'accountno' => $accountno,
							'transactionId' => $transactionId,
							'content' => $data,
							'pay_status' => $result_msg
					);
					//$data = json_encode($notifyWxwapData);
					$result = Yii::app ()->db->createCommand ()->insert('nb_mtpay_info',$notifyWxwapData);
					if($result){
						$nots = true;
						//订单成功支付...
						Helper::writeLog('支付失败!orderid:['.$orderid.'],dpid:['.$orderdpid.']');
					}
				}
			}
		}
		
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
			$ordpays = Yii::app()->db->createCommand($sql)
			->queryRow();
			if(!empty($ordpays)){
				
			}else{
				$se = new Sequence ( "order_pay" );
				$orderpayId = $se->nextval();
				$orderpayData = array (
						'lid' => $orderpayId,
						'dpid' => $orderdpid,
						'create_at' => $orders['create_at'],
						'update_at' => $orders['update_at'],
						'order_id' => $orderid,
						'account_no' => $orders['account_no'],
						'pay_amount' => number_format($totalFee/100,2),
						'paytype' => $pay_type,
						'remark' => $accountno,
				);
				$result = Yii::app ()->db->createCommand ()->insert ( 'nb_order_pay', $orderpayData );
				if($result){
					$ords = true;
				}
			}
				
		}else{
			Helper::writeLog('未查询到该条订单：'.$orderid);
		}

		if($nots&&$ords){
			return $nots = '{"status":"SUCCESS"}';
		}

	}
	public function actionMtopenidresult(){
		$db = Yii::app()->db;
		Helper::writeLog('美团回调openID');
		$dpid = Yii::app()->request->getParam('dpid');
		$accountno = Yii::app()->request->getParam('accountno');
		$order_id = Yii::app()->request->getParam('orderid');
		$openId = Yii::app()->request->getParam('openId');
		
		$se = new Sequence("mtpay_openid");
		$lid = $se->nextval();
		$tgdata = array(
				'lid'=>$lid,
				'dpid'=>$dpid,
				'create_at'=>date('Y-m-d H:i:s',time()),
				'update_at'=>date('Y-m-d H:i:s',time()),
				'account_no'=>$accountno,
				'order_id'=>$order_id,
				'mt_openId'=>$openId,
				'delete_flag'=>'0',
				'is_sync'=>'11111',
		);
		$command = $db->createCommand()->insert('nb_mtpay_openid',$tgdata);
		Helper::writeLog('该商户的授权码为：'.$openId);
	}

}