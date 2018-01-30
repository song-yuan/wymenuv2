<?php

class MtpayController extends Controller
{
	public function actionMtwappay(){
		//$result = SqbPay::pay($dpid,$_POST);
		//$obj = json_decode($result,true);
		$data = array(
				'outTradeNo'=>'20180118'.time(),
				'dpid'=>'27',
				'totalFee'=>'1',
				'subject'=>'壹点吃',
				'body'=>'壹点吃支付测试',
				'channel'=>'wx_scan_pay',
				'expireMinutes'=>'3',
				'tradeType'=>'JSAPI',
				'openId'=>'ovmY7wzTPgk8U2NCopVlvF8yQePw',
				'notifyUrl'=>'http://menu.wymenu.com/wymenuv2/mtpay/mtwappayresult',
				'merchantId'=>'4282256',
				'appId'=>'31140',
				'random'=>'1234565432',
		);
		
		$result = MtpPay::preOrder($data);
	}
	public function actionMtwappayresult(){
		
		$payStatus = Yii::app()->request->getParam('payStatus');
		Helper::writeLog('进入方法.返回参数'.$payStatus);
		echo $payStatus;
	}
	public function actionMtopenidresult(){
		Helper::writeLog('美团回调openID');
		$dpid = Yii::app()->request->getParam('dpid');
		$openId = Yii::app()->request->getParam('openId');
		$sql = 'update nb_mtpay_config set mt_openId ="'.$openId.'" where dpid ='.$dpid;
		$re = Yii::app()->db->createCommand($sql)->execute();
		Helper::writeLog('该商户的授权码为：'.$openId);
	}
	public function actionMtopenid(){
		$dpid = Yii::app()->request->getParam('dpid');
		$mid = Yii::app()->request->getParam('mid');
		//var_dump($mid);exit;
		$data = array(
			'merchantId'=>$mid,
			'dpid'=>$dpid,
		);
		$result = MtpPay::getOpenId($data);
		return $result;
	}
}