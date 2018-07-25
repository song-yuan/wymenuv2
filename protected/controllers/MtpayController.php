<?php

class MtpayController extends Controller
{
	public $layout = '/layouts/screenmain';
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
		$data = file_get_contents("php://input");
// 		Helper::writeLog('美团result'.$data);
		$accountno = $_POST['outTradeNo'];
		$transactionId = $_POST['transactionId'];
		$totalFee = $_POST['totalFee'];
		//订单号解析orderID和dpid
		$account_nos = explode('-',$accountno);
		$orderid = $account_nos[0];
		$orderdpid = $account_nos[1];
		
		$sql = 'select * from nb_mtpay_info where dpid ='.$orderdpid.' and accountno="'.$accountno.'" and transactionId ="'.$transactionId.'"';
		$notify = Yii::app()->db->createCommand($sql)->queryRow();
		if($notify){
			echo '{"status":"SUCCESS"}';
			exit;
		}

		$infos = MtpConfig::MTPAppKeyMid($orderdpid);
		$info = explode(',',$infos);
		$appId = $info[1];
		$merchantId = $info[0];
		$key = $info[2];
		
		$returnRes = MtpPay::query(array(
				'outTradeNo'=>$accountno,
				'appId'=>$appId,
				'key'=>$key,
				'merchantId'=>$merchantId,
		));
		$obj = json_decode($returnRes,true);
		
		$return_status = $obj['status'];
		$pay_status = $obj['orderStatus'];
		if($return_status=='SUCCESS' && $pay_status=='ORDER_SUCCESS'){
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
					'pay_status' => $pay_status
			);
			$result = Yii::app ()->db->createCommand ()->insert('nb_mtpay_info',$notifyWxwapData);
			
			$orders = WxOrder::getOrder($orderid, $orderdpid);
			if(!empty($orders)){
				if($orders['order_type'] == '1' || $orders['order_type'] == '6' || $orders['order_type'] == '3' ){
					$pay_type = '12';
				}elseif($orders['order_type'] == '2'){
					$pay_type = '13';
				}else{
					$pay_type = '1';
				}
			
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
				
				$user = WxBrandUser::getFromUserId($orders['user_id']);
				WxOrder::dealOrder($user, $orders);
				echo '{"status":"SUCCESS"}';
				exit;
			}
		}
		echo '{"status":"FAIL"}';exit;
	}
	public function actionMtpayreturn(){
		$companyId = Yii::app()->request->getParam('companyId');
		$orderId = Yii::app()->request->getParam('orderId');
		$orderDpid = Yii::app()->request->getParam('orderDpid');
		
		$order = WxOrder::getOrder($orderId, $orderDpid);
		
		if(empty($order)){
			throw new Exception('该订单不存在');
		}
		// 订单已支付
		if(in_array($order['order_status'],array(3,4,8))){
			$this->redirect(array('/user/orderInfo','companyId'=>$companyId,'orderId'=>$orderId,'orderDpid'=>$orderDpid));
		}
		
		$this->render('mtpayreturn',array(
				'companyId'=>$companyId,
				'orderId'=>$orderId,
				'orderDpid'=>$orderDpid,
		));
	}

}