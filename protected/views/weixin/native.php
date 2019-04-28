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
		'total_fee' => $should_total
);
$result = MicroPayModel::insert($data);


if($result['status']){
	$companyName = trim($company['company_name']);
	$compaychannel = WxCompany::getpaychannel($dpid);
	if($compaychannel['pay_type']==0){
		$msg = array('status'=>false, 'result'=>false);
		echo json_encode($msg);
		exit;
	}
	if($compaychannel['pay_channel']=='2'){
		$result = SqbPay::pay(array(
				'type'=>'3',
				'device_id'=>$poscode,
				'dynamicId'=>$auth_code,
				'totalAmount'=>$should_total*100,
				'clientSn'=>$orderId,
				'dpid'=>$dpid,
				'subject'=>$companyName,
				'operator'=>$username,
		));
		
	}elseif ($compaychannel['pay_channel']=='3'){
		//美团 微信
		$channel = 'wx_scan_pay';
		$mtr = MtpConfig::MTPAppKeyMid($dpid);
		if($mtr){
			$notifyUrl = 'http://'.$_SERVER['HTTP_HOST'].$this->createUrl('/mtpay/nativenotify');
			$data = array(
					'outTradeNo'=>$orderId,
					'totalFee'=>$should_total*100,
					'subject'=>'posfee',
					'body'=>'order',
					'channel'=>$channel,
					'expireMinutes'=>'5',
					'notifyUrl'=>$notifyUrl,
			);
			$mts = explode(',',$mtr);
			$merchantId = $mts[0];
			$appId = $mts[1];
			$key = $mts[2];
			$data['merchantId'] = $merchantId;
			$data['appId'] = $appId;
			$data['key'] = $key;
			$result = MtpPay::preOrderNative($data);
			if($result['status'] == 'SUCCESS'){
				$codeUrl = $result['qrCode'];
				$msg = array('status'=>true, 'trade_no'=>$orderId,'code_url'=>$codeUrl);
			}else{
				$msg = array('status'=>false);
			}
		}else{
			$msg = array('status'=>false);
		}
	}else{
		$notifyUrl = 'http://'.$_SERVER['HTTP_HOST'].$this->createUrl('/weixin/nativenotify');
		$notify = new WxPayNativePay();
		$input = new WxPayUnifiedOrder();
		$input->SetBody("支付");
		$input->SetAttach("3");
		$input->SetOut_trade_no($orderId);
		$input->SetTotal_fee($payPrice);
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag("支付订单");
		$input->SetNotify_url($notifyUrl);
		$input->SetTrade_type("NATIVE");
		$input->SetProduct_id('123456789');
			
		$result = $notify->GetPayUrl($input);
		if($result['return_code']=='SUCCESS'&&$result['result_code']=='SUCCESS'){
			$codeUrl = $result["code_url"];
			$msg = array('status'=>true, 'trade_no'=>$orderId,'code_url'=>$codeUrl);
		}else{
			$msg = array('status'=>false);
		}
	}
}else{
	$msg = array('status'=>false);
}
echo json_encode($msg);
exit;
?>

