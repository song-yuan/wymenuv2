<?php
$now = time();
$rand = rand(100,999);
$out_refund_no = $now.'-'.$dpid.'-'.$rand;

$company = WxCompany::get($dpid);
if(isset($out_trade_no) && $out_trade_no != ""){
	$input = new WxPayRefund();
	$input->SetOut_trade_no($out_trade_no);
	$input->SetTotal_fee($total_fee);
	$input->SetRefund_fee($refund_fee);
    $input->SetOut_refund_no($out_refund_no);
   
	$result = WxPayApi::refund($input);
	var_dump($result);exit;
	if($result){
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

