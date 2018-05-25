<?php
$now = time();
$rand = rand(100,999);
$out_request_no = $now.'-'.$dpid.'-'.$rand;
if(isset($admin_id) && $admin_id != "" ){
	$admin = WxAdminUser::get($dpid, $admin_id);
	if(!$admin){
		$msg = array('status'=>false);
		echo json_encode($msg);
		exit;
	}
}else{
	$msg = array('status'=>false);
	echo json_encode($msg);
	exit;
}
if(isset($out_trade_no) && $out_trade_no != ""){
	//第三方应用授权令牌,商户授权系统商开发模式下使用
	if($this->compaychannel=='2'){
		$result = SqbPay::refund(array(
				'device_id'=>$poscode,
				'refund_amount'=>''.$refund_amount*100,
				'clientSn'=>$out_trade_no,
				'dpid'=>$dpid,
				'operator'=>$admin_id,
		));
		if($result['return_code']=='SUCCESS'&&$result['result_code']=='SUCCESS'){
			$msg = array('status'=>true, 'trade_no'=>$out_trade_no);
		}else{
			$msg = array('status'=>false,'msg'=>'支付宝退款失败!!!');
		}
	}elseif ($this->compaychannel=='3'){
		$mtr = MtpConfig::MTPAppKeyMid($dpid);
		$mts = explode(',',$mtr);
		$merchantId = $mts[0];
		$appId = $mts[1];
		$key = $mts[2];
		$result = MtpPay::refund(array(
				'merchantId'=>$merchantId,
				'appId'=>$appId,
				'key'=>$key,
				'refundFee'=>''.$refund_amount*100,
				'outTradeNo'=>$out_trade_no,
				'refundReason'=>'商家退款',
				'refundNo'=>$out_request_no,
		));
		if($result['return_code']=='SUCCESS'&&$result['result_code']=='SUCCESS'){
			$msg = array('status'=>true, 'trade_no'=>$out_trade_no);
		}else{
			$msg = array('status'=>false,'msg'=>'支付宝退款失败!!!');
		}
	}else{
		$appAuthToken = "";//根据真实值填写
		
		//创建退款请求builder,设置参数
		$refundRequestBuilder = new AlipayTradeRefundContentBuilder();
		$refundRequestBuilder->setOutTradeNo($out_trade_no);
		$refundRequestBuilder->setRefundAmount($refund_amount);
		$refundRequestBuilder->setOutRequestNo($out_request_no);
	
		$refundRequestBuilder->setAppAuthToken($appAuthToken);
	
		//初始化类对象,调用refund获取退款应答
		$refundResponse = new AlipayTradeService($this->f2fpay_config);
		$refundResult =	$refundResponse->refund($refundRequestBuilder);
		//根据交易状态进行处理
		switch ($refundResult->getTradeStatus()){
			case "SUCCESS":
				$msg = array('status'=>true, 'trade_no'=>$out_request_no);
				break;
			case "FAILED":
				$msg = array('status'=>false,'msg'=>'支付宝退款失败!!!');
				break;
			case "UNKNOWN":
				$msg = array('status'=>false,'msg'=>'系统异常，订单状态未知!!!');
				break;
			default:
				$msg = array('status'=>false,'msg'=>'不支持的交易状态，交易返回异常!!!');
				break;
		}
	}
}else{
	$msg = array('status'=>false,'msg'=>'缺少参数!!!');
}
echo json_encode($msg);
exit;
?>

