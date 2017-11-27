<?php 
$now = time();
$rand = rand(100,999);
$outTradeNo = $now.'-'.$this->companyId.'-'.$rand;

$company = WxCompany::get($this->companyId);

$data = array(
		'dpid' => $this->companyId,
		'pay_type' => 1,
		'out_trade_no' => $outTradeNo,
		'total_fee' => $totalAmount
);
$result = MicroPayModel::insert($data);

if($authCode!=''&&$result['status']){
	if($this->compaychannel['pay_type']==0){
		$msg = array('status'=>false);
		echo json_encode($msg);
		exit;
	}
	if($this->compaychannel['pay_channel']=='2'||$this->compaychannel['pay_channel']=='2'){
		$result = SqbPay::pay(array(
				'type'=>'1',
				'device_id'=>$poscode,
				'dynamicId'=>$authCode,
				'totalAmount'=>''.$totalAmount*100,
				'clientSn'=>$outTradeNo,
				'dpid'=>$dpid,
				'subject'=>$company['company_name'],
				'operator'=>$username,
		));
		if($result){
			if($result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS"){
				$transactionId = $result["transaction_id"];
				MicroPayModel::update($dpid, $outTradeNo, $transactionId, json_encode($result));
				echo json_encode(array('status'=>true, 'result'=>true, 'trade_no'=>$outTradeNo));
				exit;
			}elseif($result["return_code"] == "SUCCESS" && $result["result_code"] == "CANCEL"){
				echo json_encode(array('status'=>true, 'result'=>false, 'trade_no'=>$outTradeNo));
				exit;
			}elseif($result["return_code"] == "SUCCESS" && $result["result_code"] == "CANCEL_SUCCESS"){
				echo json_encode(array('status'=>true, 'result'=>false, 'trade_no'=>$outTradeNo));
				exit;
			}else{
				echo json_encode(array('status'=>false));
				exit;
			}
		}else{
			echo json_encode(array('status'=>false));
			exit;
		}
		
	}else{
		$subject = $company['company_name']."-扫码";
		
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
		
		$response = $barPayResult->getResponse();
		switch ($barPayResult->getTradeStatus()) {
			case "SUCCESS":
				$transactionId = $response->trade_no;
				MicroPayModel::update($this->companyId, $outTradeNo, $transactionId, json_encode($response));
				echo json_encode(array('status'=>true, 'result'=>true, 'trade_no'=>$outTradeNo));
				break;
			case "FAILED":
				echo json_encode(array('status'=>false));
				break;
			case "UNKNOWN":
				echo json_encode(array('status'=>true, 'result'=>false, 'trade_no'=>$outTradeNo));
				break;
			default:
				echo json_encode(array('status'=>false));
				break;
		}
	}
	exit;
}
?>