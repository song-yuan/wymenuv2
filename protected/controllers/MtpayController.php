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
		$data = file_get_contents("php://input");
		Helper::writeLog('美团result'.$data);
		$accountno = $_POST['outTradeNo'];
		$transactionId = $_POST['transactionId'];
		$totalFee = $_POST['totalFee'];
		//订单号解析orderID和dpid
		$account_nos = explode('-',$accountno);
		$orderid = $account_nos[0];
		$orderdpid = $account_nos[1];
		$ords = false;$nots = false;$inf =false; 
		
		$sql = 'select * from nb_mtpay_info where dpid ='.$orderdpid.' and accountno="'.$accountno.'" and transactionId ="'.$transactionId.'"';
		$notify = Yii::app()->db->createCommand($sql)->queryRow();
		if($notify){
			return '{"status":"SUCCESS"}';
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
				return '{"status":"SUCCESS"}';
			}
		}
		return '{"status":"FAIL"}';
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