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
		Helper::writeLog('美团result'.$data);
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
			
			$order = WxOrder::getOrder($orderid, $orderdpid);
			if(!empty($order)){
				if(in_array($order['order_type'], array(1,3,6))){
					$paytype = 12;
				}elseif($order['order_type']==2){
					$paytype = 13;
				}
				WxOrder::insertOrderPay($order,$paytype,$totalFee/100,0,$accountno);
				$user = WxBrandUser::getFromUserId($order['user_id']);
				WxOrder::dealOrder($user, $order);
				$order['order_status'] = 3;
				WxOrder::pushOrderToRedis($order);
				echo '{"status":"SUCCESS"}';
				exit;
			}
		}
		echo '{"status":"FAIL"}';exit;
	}
	/**
	 * 
	 */
	public function actionMtrechargeresult(){
		$data = file_get_contents("php://input");
		$accountno = $_POST['outTradeNo'];
		$transactionId = $_POST['transactionId'];
		$totalFee = $_POST['totalFee'];
		
		//订单号解析 relid 充值id 和 redpid 充值dpid
		$account_nos = explode('-',$accountno);
		$rlid = $account_nos[0];
		$redpid = $account_nos[1];
		$reuserid = $account_nos[2];
	
		$sql = 'select * from nb_mtpay_info where dpid ='.$redpid.' and accountno="'.$accountno.'" and transactionId ="'.$transactionId.'"';
		$notify = Yii::app()->db->createCommand($sql)->queryRow();
		if($notify){
			echo '{"status":"SUCCESS"}';
			exit;
		}
	
		$infos = MtpConfig::MTPAppKeyMid($redpid);
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
					'dpid' => $redpid,
					'create_at' => date ( 'Y-m-d H:i:s', time()),
					'update_at' => date ( 'Y-m-d H:i:s', time()),
					'accountno' => $accountno,
					'transactionId' => $transactionId,
					'content' => $data,
					'pay_status' => $pay_status
			);
			$result = Yii::app ()->db->createCommand ()->insert('nb_mtpay_info',$notifyWxwapData);
				
			$recharge = new WxRecharge($rlid,$redpid,$reuserid);
			echo '{"status":"SUCCESS"}';
			exit;
		}
		echo '{"status":"FAIL"}';exit;
	}
	/**
	 * 支付后跳转页面
	 * 
	 */
	public function actionMtpayreturn(){
		$companyId = Yii::app()->request->getParam('companyId');
		$payStatus = Yii::app()->request->getParam('payStatus');
		$orderId = Yii::app()->request->getParam('orderId');
		$orderDpid = Yii::app()->request->getParam('orderDpid');
		
		if($payStatus != 'ok'){
			$this->redirect(array('/user/orderInfo','companyId'=>$companyId,'orderId'=>$orderId,'orderDpid'=>$orderDpid));
		}
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