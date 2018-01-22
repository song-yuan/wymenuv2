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
		//$st = 'http://www.wymenu.com/wymenuv2/mtpay/mtwappay';
// 		$st = urlencode("http://menu.wymenu.com/wymenuv2/mtpay/mtwappay");
// 		$url = "Location:http://openpay.zc.st.meituan.com/auth?bizId=31140&mchId=4282256&redirect_uri=".$st;
// 		Helper::writeLog($url);
// 		header($url);
		
		$result = MtpPay::preOrder($data);
	}
	public function actionMtwappayresult(){
		
		$payStatus = Yii::app()->request->getParam('payStatus');
		Helper::writeLog('进入方法.返回参数'.$payStatus);
		echo $payStatus;
	}
}