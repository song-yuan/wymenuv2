<?php
$now = time();
$rand = rand(100,999);
$out_refund_no = $now.'-'.$dpid.'-'.$rand;
if(isset($admin_id) && $admin_id != "" ){
	$admin = WxAdminUser::get($dpid, $admin_id);
	if(!$admin){
		$msg = array('status'=>false,'msg'=>'不存在该管理员');
		echo json_encode($msg);
		exit;
	}
}else{
	$msg = array('status'=>false,'msg'=>'未传入admin_id');
	echo json_encode($msg);
	exit;
}
if(isset($out_trade_no) && $out_trade_no!="" && $out_trade_no!=0){
	$compaychannel = WxCompany::getpaychannel($dpid);
	if($compaychannel['pay_channel']=='2'){
		$result = SqbPay::refund(array(
				'device_id'=>$poscode,
				'refund_amount'=>''.$refund_fee*100,
				'clientSn'=>$out_trade_no,
				'dpid'=>$dpid,
				'operator'=>$admin_id,
		));
	}elseif($compaychannel['pay_channel']=='3'){
		$mtr = MtpConfig::MTPAppKeyMid($dpid);
		$mts = explode(',',$mtr);
		$merchantId = $mts[0];
		$appId = $mts[1];
		$key = $mts[2];
		$result = MtpPay::refund(array(
				'merchantId'=>$merchantId,
				'appId'=>$appId,
				'key'=>$key,
				'refundFee'=>''.$refund_fee*100,
				'outTradeNo'=>$out_trade_no,
				'refundReason'=>'商家退款',
				'refundNo'=>$out_refund_no,
		));
	}else{
		$input = new WxPayRefund();
		$input->SetOut_trade_no($out_trade_no);
		$input->SetTotal_fee($total_fee*100);
		$input->SetRefund_fee($refund_fee*100);
	    $input->SetOut_refund_no($out_refund_no);
	   
		$result = WxPayApi::refund($input);
	}
	//var_dump($result);exit;
	if($result['return_code']=='SUCCESS'&&$result['result_code']=='SUCCESS'){
		$msg = array('status'=>true, 'trade_no'=>$out_refund_no);
	}else{
		$msg = array('status'=>false,'msg'=>$result);
	}
}else{
	$msg = array('status'=>true,'msg'=>'手动确认错误,退款订单号不存在,直接退款');
}
echo json_encode($msg);
exit;
?>

