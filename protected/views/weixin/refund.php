<?php
$now = time();
$rand = rand(100,999);
$out_refund_no = $now.'-'.$dpid.'-'.$rand;
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
if(isset($out_trade_no) && $out_trade_no!="" && $out_trade_no!=0){
	$compaychannel = WxCompany::getpaychannel($dpid);
	if($compaychannel['pay_channel']=='2'||$compaychannel['pay_channel']=='3'){
		$result = SqbPay::refund(array(
				'device_id'=>$poscode,
				'refund_amount'=>''.$refund_fee*100,
				'clientSn'=>$out_trade_no,
				'dpid'=>$dpid,
				'operator'=>$admin_id,
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
		$msg = array('status'=>false);
	}
}else{
	$msg = array('status'=>false);
}
echo json_encode($msg);
exit;
?>

