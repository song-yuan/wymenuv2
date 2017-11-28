<?php
/**
 * 
 * 微信刷卡支付
 * 
 */
$now = time();
$rand = rand(100,999);
$orderId = $now.'-'.$dpid.'-'.$rand;

$company = WxCompany::get($dpid);
$data = array(
		'dpid' => $dpid,
		'pay_type' => 0,
		'out_trade_no' => $orderId,
		'total_fee' => ''.$should_total
);
$result = MicroPayModel::insert($data);


if(isset($auth_code) && $auth_code != ""&&$result['status']){
	$compaychannel = WxCompany::getpaychannel($dpid);
	Helper::writeLog($dpid.'---paytype---'.json_encode($compaychannel));
	if($compaychannel['pay_type']==0){
		$msg = array('status'=>false, 'result'=>false);
		echo json_encode($msg);
		exit;
	}
	if($compaychannel['pay_channel']=='2'||$compaychannel['pay_channel']=='3'){
		$result = SqbPay::pay(array(
				'type'=>'3',
				'device_id'=>$poscode,
				'dynamicId'=>$auth_code,
				'totalAmount'=>''.$should_total*100,
				'clientSn'=>$orderId,
				'dpid'=>$dpid,
				'subject'=>$company['company_name'],
				'operator'=>$username,
		));
		
	}else{

		$input = new WxPayMicroPay();
		$input->SetAuth_code($auth_code);
		$input->SetBody($company['company_name']);
		$input->SetTotal_fee($should_total*100);
		$input->SetOut_trade_no($orderId);
		
		$microPay = new MicroPay();
		$result = $microPay->pay($input);
		
	}
	if($result){
		Helper::writeLog($dpid.'---payresult---'.json_encode($result));
		if($result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS"){
			$transactionId = $result["transaction_id"];
			MicroPayModel::update($dpid, $orderId, $transactionId, json_encode($result));
			$msg = array('status'=>true, 'result'=>true, 'trade_no'=>$orderId);
		}elseif($result["return_code"] == "SUCCESS" && $result["result_code"] == "CANCEL"){
			$msg = array('status'=>true, 'result'=>false, 'trade_no'=>$orderId);
		}elseif($result["return_code"] == "SUCCESS" && $result["result_code"] == "CANCEL_SUCCESS"){
			$msg = array('status'=>true, 'result'=>false, 'trade_no'=>$orderId);
		}else{
			$msg = array('status'=>false, 'result'=>false);
		}
	}else{
		$msg = array('status'=>false, 'result'=>false);
	}
}else{
	$msg = array('status'=>false, 'result'=>false);
}
echo json_encode($msg);
exit;
?>

