<?php 
$now = time();
$rand = rand(100,999);
$orderId = $now.'-'.$this->companyId.'-'.$rand;

$company = WxCompany::get($this->companyId);

$data = array(
		'dpid' => $this->companyId,
		'pay_type' => 1,
		'out_trade_no' => $orderId,
		'total_fee' => $totalAmount
);
$result = MicroPayModel::insert($data);

if($result['status']){
	$companyName = trim($company['company_name']);
	if($this->compaychannel['pay_type']==0){
		$msg = array('status'=>false);
		echo json_encode($msg);
		exit;
	}
	if($this->compaychannel['pay_channel']=='2'){
		
	}elseif ($this->compaychannel['pay_channel']=='3'){
		//美团 支付宝
		$channel = 'ali_scan_pay';
		$mtr = MtpConfig::MTPAppKeyMid($dpid);
		if($mtr){
			$notifyUrl = 'http://'.$_SERVER['HTTP_HOST'].$this->createUrl('/mtpay/nativenotify');
			$data = array(
					'outTradeNo'=>$orderId,
					'totalFee'=>$totalAmount*100,
					'subject'=>'payorder',
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
		$subject = $companyName;
		
		$undiscountableAmount = "0.01";
		$sellerId = $this->alipay_config['seller_id'];
		$body = "购买商品共花费".$totalAmount."元";
		
		//商户操作员编号，添加此参数可以为商户操作员做销售统计
		$operatorId = '';
		
		// (必填) 商户门店编号
		$storeId = "wy_".$this->companyId;
		
		$goodsDetailList = array();
		if($goodStr!=''){
			$goodsArr = json_decode($goodStr);
			foreach ($goodsArr as $goods){
				$goodsDetai = new GoodsDetail();
				$goodsDetai->setGoodsId($goods[0]);
				$goodsDetai->setGoodsName($goods[1]);
				$goodsDetai->setPrice($goods[2]);
				$goodsDetai->setQuantity($goods[3]);
				//得到商品1明细数组
				array_push($goodsDetailList,$goodsDetai->getGoodsDetail());
			}
		}
	
		// 支付宝的店铺编号
		$alipayStoreId = "";
		// 业务扩展参数，目前可添加由支付宝分配的系统商编号(通过setSysServiceProviderId方法)
		$providerId = ""; //系统商pid,作为系统商返佣数据提取的依据 
		$extendParams = new ExtendParams();
		$extendParams->setSysServiceProviderId($providerId);
		$extendParamsArr = $extendParams->getExtendParams();
	
		// 支付超时，线下扫码交易定义为5分钟
		$timeExpress = "5m";
		
		//第三方应用授权令牌,商户授权系统商开发模式下使用
		$appAuthToken = ""; //201701BBda91f2d7e6964c37b616687e75858C86
		
		// 创建请求builder，设置请求参数
		$qrPayRequestBuilder = new AlipayTradePayContentBuilder();
		$qrPayRequestBuilder->setOutTradeNo($orderId);
		$qrPayRequestBuilder->setTotalAmount($totalAmount);
		$qrPayRequestBuilder->setTimeExpress($timeExpress);
		$qrPayRequestBuilder->setSubject($subject);
		$qrPayRequestBuilder->setBody($body);
		$qrPayRequestBuilder->setUndiscountableAmount($undiscountableAmount);
		$qrPayRequestBuilder->setExtendParams($extendParamsArr);
		$qrPayRequestBuilder->setGoodsDetailList($goodsDetailList);
		$qrPayRequestBuilder->setStoreId($storeId);
		$qrPayRequestBuilder->setOperatorId($operatorId);
		$qrPayRequestBuilder->setAlipayStoreId($alipayStoreId);
		
		$qrPayRequestBuilder->setAppAuthToken($appAuthToken);
		
		// 调用qrPay方法获取当面付应答
		$qrPay = new AlipayTradeService($this->f2fpay_config);
		$qrPayResult = $qrPay->qrPay($qrPayRequestBuilder);
		
		$response = $qrPayResult->getResponse();
		switch ($qrPayResult->getTradeStatus()) {
			case "SUCCESS":
				$codeUrl = $response->qr_code;
				echo json_encode(array('status'=>true,'trade_no'=>$orderId,'code_url'=>$codeUrl));
				break;
			case "FAILED":
				echo json_encode(array('status'=>false));
				break;
			case "UNKNOWN":
				echo json_encode(array('status'=>false));
				break;
			default:
				echo json_encode(array('status'=>false));
				break;
		}
		exit;
	}
	
}else{
	$msg = array('status'=>false);
}
echo json_encode($msg);
exit;
?>