<?php 
if(isset($_POST)){
	
	$now = time();
	$rand = rand(100,999);
	$outTradeNo = $now.'-'.$this->companyId.'-'.$rand;
	
	$company = WxCompany::get($this->companyId);
	
	$subject = $company['company_name']."消费";
	$totalAmount = $_POST['total'];
	$authCode = $_POST['authcode'];
	
	$undiscountableAmount = "0.01";
	$sellerId = $this->alipay_config['seller_id'];
	$body = "购买商品共花费".$totalAmount."元";
	
	//商户操作员编号，添加此参数可以为商户操作员做销售统计
	$operatorId = isset($_POST['operator'])?$_POST['operator']:'admin';
	
	// (必填) 商户门店编号
	$storeId = "wy_".$this->companyId;
	
	$goodsDetailList = array();
	$goodsArr = json_decode($_POST['goods']);
	foreach ($goodsArr as $goods){
		$goodsDetai = new GoodsDetail();
		$goodsDetai->setGoodsId($goods['product_id']);
		$goodsDetai->setGoodsName($goods['product_name']);
		$goodsDetai->setPrice($goods['product_price']);
		$goodsDetai->setQuantity($goods['amount']);
		//得到商品1明细数组
		array_push($goodsDetailList,$goodsDetai->getGoodsDetail());
	}

	// 支付宝的店铺编号
	$alipayStoreId = "";
	
	// 业务扩展参数，目前可添加由支付宝分配的系统商编号(通过setSysServiceProviderId方法)
	$providerId = "2088811584894868"; //系统商pid,作为系统商返佣数据提取的依据
	$extendParams = new ExtendParams();
	$extendParams->setSysServiceProviderId($providerId);
	$extendParamsArr = $extendParams->getExtendParams();

	// 支付超时，线下扫码交易定义为5分钟
	$timeExpress = "5m";
	
	//第三方应用授权令牌,商户授权系统商开发模式下使用
	$appAuthToken = "";
	
	// 创建请求builder，设置请求参数
	$barPayRequestBuilder = new AlipayTradePayContentBuilder();
	$barPayRequestBuilder->setOutTradeNo($outTradeNo);
	$barPayRequestBuilder->setTotalAmount($totalAmount);
	$barPayRequestBuilder->setAuthCode($authCode);
	$barPayRequestBuilder->setTimeExpress($timeExpress);
	$barPayRequestBuilder->setSubject($subject);
	$barPayRequestBuilder->setBody($body);
	$barPayRequestBuilder->setUndiscountableAmount($undiscountableAmount);
	$barPayRequestBuilder->setExtendParams($extendParamsArr);
	$barPayRequestBuilder->setGoodsDetailList($goodsDetailList);
	$barPayRequestBuilder->setStoreId($storeId);
	$barPayRequestBuilder->setOperatorId($operatorId);
	$barPayRequestBuilder->setAlipayStoreId($alipayStoreId);
	
	$barPayRequestBuilder->setAppAuthToken($appAuthToken);
	
	// 调用barPay方法获取当面付应答
	$barPay = new AlipayTradeService($this->f2fpay_config);
	$barPayResult = $barPay->barPay($barPayRequestBuilder);
	
	switch ($barPayResult->getTradeStatus()) {
		case "SUCCESS":
			echo json_encode(array('status'=>true,'msg'=>''));
			break;
		case "FAILED":
			echo json_encode(array('status'=>false,'msg'=>''));
			break;
		case "UNKNOWN":
			echo json_encode(array('status'=>false,'msg'=>''));
			break;
		default:
			echo json_encode(array('status'=>false,'msg'=>''));
			break;
	}
	exit;
}
?>