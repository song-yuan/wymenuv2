<?php

class MtpayController extends Controller
{
	public function actionMtwappay(){
		//$result = SqbPay::pay($dpid,$_POST);
		//$obj = json_decode($result,true);
		$data = array(
				'outTradeNo'=>'20180131'.'-0000000027'.'-004',
				'totalFee'=>'1',
				'subject'=>'壹点吃',
				'body'=>'壹点吃支付测试',
				'channel'=>'wx_scan_pay',
				'expireMinutes'=>'3',
				'tradeType'=>'JSAPI',
				'notifyUrl'=>'http://menu.wymenu.com/wymenuv2/mtpay/mtwappayresult',
				'merchantId'=>'4282256',
		);
		
		$result = MtpPay::preOrder($data);
	}
	public function actionMtwappayresult(){
		
		$payStatus = Yii::app()->request->getParam('payStatus');
		Helper::writeLog('进入方法.返回参数'.$payStatus);
		echo $payStatus;
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