<?php
/**
 * 
 * 
 * @author dys
 * 美团支付 接口类
 *pay 支付接口
 *precreate 预下单接口
 *refund 退款接口
 *cancel 撤单接口
 *query 查询接口
 *code 14599168
 */
class MtpPay{
	/**
	 *
	 * 产生随机字符串，不长于32位
	 * @param int $length
	 * @return 产生的随机字符串
	 */
	public static function getNonceStr($length = 32)
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$str ="";
		for ( $i = 0; $i < $length; $i++ )  {
			$str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);
		}
		return $str;
	}
	/**
	 * 刷卡支付
	 * 扫顾客条码
	 */
    public static function pay($data){
    	//     	/*该接口用于美团支付*/
    	//     	$channel = $data['channel'];
    	//     	/*支付渠道、必填项、最大64字符、'wx_barcode_pay':微信刷卡支付'ali_barcode_pay':支付宝刷卡支付*/
    	//     	$totalFee = $data['totalFee'];
    	//     	/*以分为单位,*/
    	//     	$outTradeNo = $data['outTradeNo'];
    	//     	/*必传。内容为字符串。接入方订单号 不超过64位*/
    	//     	$authCode = $data['authCode'];
    	//     	/*条码内容、支付条码、最大不超过128位*/
    	//     	$subject = $data['subject'];
    	//     	/*商品标题*/
    	//     	$body = $data['body'];
    	//		/*商品详情*/
    	//     	$expireMinutes = $data['expireMinutes'];
    	//		/*创建支付订单后，订单关闭时间，单位为分钟。默认设置为5分钟,最长不超过30分钟，超过关单时间无法支付*/
    	//     	$merchantId = $data['merchantId'];
    	//		/*开放平台分配的商户id, 目前是 美团POI ID*/
    	//     	$appId= $data['appId'];
    	//		/*接入方标识，由美团开放平台分配 参考 https://platform.meituan.com/buffet/list*/
    	//     	$sign= $data['sign'];
    	//		/*验证签名*/
    	//     	$random= $data['random'];
    	//		/*随机数*/
    	$merchantId = $data['merchantId'];
    	$appId = $data['appId'];
    	$key = $data['key'];
    	$channel = $data['channel'];
    	$outTradeNo = $data['outTradeNo'];
    	$authCode = $data['authCode'];
    	$totalFee = $data['totalFee'];
    	$subject = $data['subject'];
    	$body = $data['body'];
    	$expireMinutes = $data['expireMinutes'];
    	$dpid =$data['dpid'];
    	$random = self::getNonceStr();

    	$url = MtpConfig::MTP_DOMAIN.'/api/pay/micropay';

    	$datas = array(
    				'channel'=>$channel,
    				'outTradeNo'=>$outTradeNo,
    				'authCode'=>$authCode,
    				'totalFee'=>$totalFee,
    				'subject'=>$subject,
    				'body'=>$body,
    				'expireMinutes'=>$expireMinutes,
    				'merchantId'=>$merchantId,
    				'appId'=>$appId,
    				'random'=>$random,
    	);
    	
    	ksort($datas);
    	$paramsStrs = '';
    	if(is_array($datas)){
    		foreach($datas as $k => $v)
    		{
    			$paramsStrs .= $k.'='.$v.'&';
    		}
    	}else{
    		$result = array(
    				"return_code"=>"ERROR",
    				"result_code"=>"ERROR",
    				'result_msg'=>'REPAY',
    				'msg'=>'未知状态！');
    		return $result;
    	}
    	$st = $paramsStrs.'key='.$key;
    	$sign = hash("sha256", $st);
    	
    	$datas['sign'] = $sign;
    	
    	$body = json_encode($datas);
    	$result = MtpCurl::httpPost($url, $body);
    	Helper::writeLog('mtpay返回结果：'.$dpid.$body.$result);
    	

    	if(!empty($result)){
	    	$obj = json_decode($result,true);
	    	$return_status = $obj['status'];
	    	$pay_status = $obj['orderStatus'];
	    	$tradeNo = $obj['tradeNo'];
	    	$errCode = $obj['errCode'];
	    	
	    	if($return_status=='SUCCESS'){
	    		//交易成功
	    		if($pay_status=='ORDER_SUCCESS'){
	    			$result = array(
	    					"return_code"=>"SUCCESS",
	    					"result_code"=>"SUCCESS",
	    					"result_msg"=>$pay_status,
	    					"msg"=>"支付成功！",
	    					"transaction_id"=>$obj['tradeNo'],
	    					"order_id"=>$obj['outTradeNo']
	    			);
	    			return $result;
	    		}elseif($pay_status=='ORDER_CLOSE' || $pay_status=='ORDER_FAILED'){
	    			// 交易失败
	    			$result = array(
	    					"return_code"=>"SUCCESS",
	    					"result_code"=>"ERROR",
	    					"result_msg"=>$pay_status,
	    					"msg"=>"支付失败！",
	    					"order_id"=>$obj['outTradeNo']
	    			);
	    			return $result;
	    		}
	    	}else{
	    		// TRADE_PAY_ERROR TRADE_PAY_UNKOWN_ERROR TRADE_PAYING_ERROR TRANSFER_TIMEOUT_ERROR
	    		// 上述情况需要轮询查询订单
	    		if($errCode!='TRADE_PAY_ERROR' && 
	    			$errCode!='TRADE_PAY_UNKOWN_ERROR' && 
	    			$errCode!='TRADE_PAYING_ERROR' &&
	    			$errCode!='TRANSFER_TIMEOUT_ERROR'){
	    				$result = array(
	    						"return_code"=>"SUCCESS",
	    						"result_code"=>"ERROR",
	    						"result_msg"=>$pay_status,
	    						"msg"=>"支付失败！",
	    						"order_id"=>$obj['outTradeNo']
	    				);
	    				return $result;
	    		}
	    	}
    	}
    	
    	// 15次查询确认
    	$queryTimes = 15;
    	while ($queryTimes > 0){
    		$queryTimes--;
    		$returnRes = self::query(array(
    				'outTradeNo'=>$outTradeNo,
    				'appId'=>$appId,
    				'key'=>$key,
    				'merchantId'=>$merchantId,
    		));
    		$obj = json_decode($returnRes,true);
    		
    		$return_status = $obj['status'];
	    	$pay_status = $obj['orderStatus'];
	    	$tradeNo = $obj['tradeNo'];
	    	$errCode = $obj['errCode'];
	    	
	    	if($return_status=='SUCCESS'){
	    		// 交易成功
	    		if($pay_status=='ORDER_SUCCESS'){
	    			$result = array(
	    					"return_code"=>"SUCCESS",
	    					"result_code"=>"SUCCESS",
	    					"result_msg"=>$pay_status,
	    					"msg"=>"支付成功！",
	    					"transaction_id"=>$obj['tradeNo'],
	    					"order_id"=>$obj['outTradeNo']
	    			);
	    			return $result;
	    		}elseif($pay_status=='ORDER_CLOSE' || $pay_status=='ORDER_FAILED'){
	    			// 交易失败
	    			$result = array(
	    					"return_code"=>"SUCCESS",
	    					"result_code"=>"ERROR",
	    					"result_msg"=>$pay_status,
	    					"msg"=>"支付失败！",
	    					"order_id"=>$obj['outTradeNo']
	    			);
	    			return $result;
	    		}
	    	}else{
	    		if($errCode != 'TRADE_PAY_QUERY_ERROR'){
	    			$result = array(
	    					"return_code"=>"SUCCESS",
	    					"result_code"=>"ERROR",
	    					"result_msg"=>$pay_status,
	    					"msg"=>"支付失败！",
	    					"order_id"=>$obj['outTradeNo']
	    			);
	    			return $result;
	    		}
	    	}
    		sleep(2);
    	}
    	$cancelData = array(
    			'outTradeNo'=>$outTradeNo,
    			'appId'=>$appId,
    			'merchantId'=>$merchantId,
    			'key'=>$key
    	);
    	
    	// 超过15次查询 撤单
    	if(self::cancel($cancelData)){
    		$result = array(
    				"return_code"=>"SUCCESS",
    				"result_code"=>"CANCEL_SUCCESS",
    				"result_msg"=>'',
    				"msg"=>"撤单成功！",
    				"order_id"=>''
    		);
    	}else{
    		$result = array(
    				"return_code"=>"SUCCESS",
    				"result_code"=>"CANCEL",
    				"result_msg"=>'',
    				"msg"=>"撤单失败！",
    				"order_id"=>''
    		);
    	}
    	return $result;
    }
	
    /**
     * 预下单接口
     * 公众号支付
     */
    public static function preOrder($data){
    	$merchantId = $data['merchantId'];
    	$appId = $data['appId'];
    	$key = $data['key'];
    	$outTradeNo = $data['outTradeNo'];
    	$totalFee = $data['totalFee'];
    	$subject = $data['subject'];
    	$body = $data['body'];
    	$channel = $data['channel'];
    	$expireMinutes = $data['expireMinutes'];
    	$tradeType = 'JSAPI';
    	$notifyUrl = $data['notifyUrl'];
    	$returnUrl = $data['return_url'];
    	$openId = $data['openId'];
    	//      /*支付完成后的回调地址*/
    	$random = self::getNonceStr();
    	$url = MtpConfig::MTP_DOMAIN.'/api/precreate';
    	Helper::writeLog('return_url==='.$returnUrl);
    	
    	//获取美团支付参数
    	$datas = array(
    				'outTradeNo'=>$outTradeNo,
    				'totalFee'=>$totalFee,
    				'subject'=>$subject,
    				'body'=>$body,
    				'channel'=>$channel,
    				'expireMinutes'=>$expireMinutes,
    				'tradeType'=>$tradeType,
    				'notifyUrl'=>$notifyUrl,
    				'merchantId'=>$merchantId,
    				'appId'=>$appId,
    				'random'=>$random,
    				'openId'=>$openId,
    		);
    		
    		ksort($datas);
    		$paramsStrs = '';
    		if(is_array($datas)){
    			foreach($datas as $k => $v)
    			{
    				$paramsStrs .= $k.'='.$v.'&';
    			}
    		}else{
    			$result = array(
    					"return_code"=>"ERROR",
    					"result_code"=>"ERROR",
    					'msg'=>'未知状态！');
    			return $result;
    		}
    		$st = $paramsStrs.'key='.$key;
    		$sign = hash("sha256", $st);

    		$datas['sign'] = $sign;
    		
    		$body = json_encode($datas);
    		$result = MtpCurl::httpPost($url, $body);
    		Helper::writeLog('公众号支付返回结果：'.$result);
    		 
    		if(!empty($result)){
    			$obj = json_decode($result,true);
    			$status = $obj['status'];
    			if($status=='SUCCESS'){
    				$resulturl = urlencode($returnUrl);
    				//回调地址
    				$appIds = $obj['appId'];
    				$timeStamp = $obj['timeStamp'];
    				$nonceStr = $obj['nonceStr'];
    				$signType = $obj['signType'];
    				$paySign = $obj['paySign'];
    				$prepayId = $obj['prepayId'];
    				 
    				$url = "https://openpay.meituan.com/pay/?bizId=".$appId."&appId=".$appIds."&nonceStr=".$nonceStr."&prepay_id=".$prepayId."&paySign=".$paySign."&timeStamp=".$timeStamp."&signType=".$signType."&redirect_uri=".$resulturl;
    				header("Location:".$url);
    				exit;
    			}
    		}
    		return $result;exit;
    } 
    /**
     * native方式生产动态二维码
     * 
     */
    public static function preOrderNative($data){
    	$merchantId = $data['merchantId'];
    	$appId = $data['appId'];
    	$key = $data['key'];
    	$outTradeNo = $data['outTradeNo'];
    	$totalFee = $data['totalFee'];
    	$subject = $data['subject'];
    	$body = $data['body'];
    	$channel = $data['channel'];
    	$expireMinutes = $data['expireMinutes'];
    	$tradeType = 'NATIVE';
    	$notifyUrl = $data['notifyUrl'];
    	$random = self::getNonceStr();
    	
    	$url = MtpConfig::MTP_DOMAIN.'/api/precreate';
    	 
    	//获取美团支付参数
    	$datas = array(
    			'outTradeNo'=>$outTradeNo,
    			'totalFee'=>$totalFee,
    			'subject'=>$subject,
    			'body'=>$body,
    			'channel'=>$channel,
    			'expireMinutes'=>$expireMinutes,
    			'tradeType'=>$tradeType,
    			'notifyUrl'=>$notifyUrl,
    			'merchantId'=>$merchantId,
    			'appId'=>$appId,
    			'random'=>$random,
    	);
    
    	ksort($datas);
    	$paramsStrs = '';
    	if(is_array($datas)){
    		foreach($datas as $k => $v)
    		{
    			$paramsStrs .= $k.'='.$v.'&';
    		}
    	}else{
    		$result = array(
    				"return_code"=>"ERROR",
    				"result_code"=>"ERROR",
    				'msg'=>'未知状态！');
    		return $result;
    	}
    	$st = $paramsStrs.'key='.$key;
    	$sign = hash("sha256", $st);
    
    	$datas['sign'] = $sign;
    
    	$body = json_encode($datas);
    	$result = MtpCurl::httpPost($url, $body);
    	Helper::writeLog('native支付返回结果：'.$result);
    	$obj = json_decode($result,true);
    	return $obj;
    }
    /**
     * 该接口用于查询订单状态
     * 查询订单状态
     * 
     */
    public static function query($data){
    	$merchantId = $data['merchantId'];
    	$appId = $data['appId'];
    	$key = $data['key'];
    	$outTradeNo = $data['outTradeNo'];
    	$random = self::getNonceStr();
    	
    	$url = MtpConfig::MTP_DOMAIN.'/api/pay/query';
    	
    	$datas = array(
    			'outTradeNo'=>$outTradeNo,
    			'appId'=>$appId,
    			'random'=>$random,
    			'merchantId'=>$merchantId,
    	);
    	 
    	ksort($datas);
    	$paramsStrs = '';
    	if(is_array($datas)){
    		foreach($datas as $k => $v)
    		{
    			$paramsStrs .= $k.'='.$v.'&';
    		}
    	}else{
    		$result = array(
    				"return_code"=>"ERROR",
    				"result_code"=>"ERROR",
    				'result_msg'=>'REQUERY',
    				'msg'=>'未知状态！');
    		return $result;
    	}
    	$st = $paramsStrs.'key='.$key;
    	$sign = hash("sha256", $st);
    	
    	$datas['sign'] = $sign;
    	
    	$body = json_encode($datas);
    	$result = MtpCurl::httpPost($url, $body);
    	Helper::writeLog('mt订单查询返回结果：'.$result);
    	
    	return $result;
    }
    /**
     * 该接口用于查询订单状态
     * 查询订单状态
     *
     */
    public static function refundQuery($data){
    	$merchantId = $data['merchantId'];
    	$appId = $data['appId'];
    	$key = $data['key'];
    	$outTradeNo = $data['outTradeNo'];
    	$refundNo = $data['refundNo'];
    	$random = self::getNonceStr();
    	 
    	$url = MtpConfig::MTP_DOMAIN.'/api/refund/query';
    	 
    	$datas = array(
    			'outTradeNo'=>$outTradeNo,
    			'refundNo'=>$outTradeNo,
    			'appId'=>$appId,
    			'random'=>$random,
    			'merchantId'=>$merchantId,
    	);
    
    	ksort($datas);
    	$paramsStrs = '';
    	if(is_array($datas)){
    		foreach($datas as $k => $v)
    		{
    			$paramsStrs .= $k.'='.$v.'&';
    		}
    	}else{
    		$result = array(
    				"return_code"=>"ERROR",
    				"result_code"=>"ERROR",
    				'result_msg'=>'REQUERY',
    				'msg'=>'未知状态！');
    		return $result;
    	}
    	$st = $paramsStrs.'key='.$key;
    	$sign = hash("sha256", $st);
    	 
    	$datas['sign'] = $sign;
    	 
    	$body = json_encode($datas);
    	$result = MtpCurl::httpPost($url, $body);
    	Helper::writeLog('mt退单查询返回结果：'.$result);
    	
    	return $result;
    }
    /**
     * 该接口用于订单取消
     * 刷卡（条码）支付场景，如果支付超时或失败，用来撤销当前订单
	 * 当日订单允许撤销，隔日请使用退款接口
	 * 
     */
    public static function cancel($data, $depth = 0){
    	if($depth > 10){
    		return false;
    	}
    	
    	$merchantId = $data['merchantId'];
    	$appId = $data['appId'];
    	$key = $data['key'];
    	$outTradeNo = $data['outTradeNo'];
    	$random = self::getNonceStr();
    
    	$url = MtpConfig::MTP_DOMAIN.'/api/cancel';
    
    	$datas = array(
    			'outTradeNo'=>$outTradeNo,
    			'merchantId'=>$merchantId,
    			'appId'=>$appId,
    			'random'=>$random,
    	);
    
    	ksort($datas);
    	$paramsStrs = '';
    	if(is_array($datas)){
    		foreach($datas as $k => $v)
    		{
    			$paramsStrs .= $k.'='.$v.'&';
    		}
    	}else{
    		return false;
    	}
    	$st = $paramsStrs.'key='.$key;
    	$sign = hash("sha256", $st);
    
    	$datas['sign'] = $sign;
    	 
    	$body = json_encode($datas);
    	$result = MtpCurl::httpPost($url, $body);
    	Helper::writeLog('mt撤单返回结果：'.$result);
    	
    	$obj = json_decode($result);
    	if($obj->status=='SUCCESS'){
    		return true;
    	}else{
    		return self::cancel($datas, ++$depth);
    	}
    	return false;
    }
    /**
     * 该接口用于订单退款
     * 
     */
    public static function refund($data){
    	$merchantId = $data['merchantId'];
    	$appId = $data['appId'];
    	$key = $data['key'];
    	$outTradeNo = $data['outTradeNo'];
    	$refundFee = $data['refundFee'];
    	$refundNo = $data['refundNo'];
    	$refundReason = $data['refundReason'];
    	$random = self::getNonceStr();

    	$url = MtpConfig::MTP_DOMAIN.'/api/refund';

    	$datas = array(
    			'outTradeNo'=>$outTradeNo,
    			'refundFee'=>$refundFee,
    			'refundNo'=>$refundNo,
    			'refundReason'=>$refundReason,
    			'merchantId'=>$merchantId,
    			'appId'=>$appId,
    			'random'=>$random,
    	);
    
    	ksort($datas);
    	$paramsStrs = '';
    	if(is_array($datas)){
    		foreach($datas as $k => $v)
    		{
    			$paramsStrs .= $k.'='.$v.'&';
    		}
    	}else{
    		$results = array(
    				"return_code"=>"ERROR",
    				"result_code"=>"ERROR",
    				'msg'=>'未知状态！');
    		return $result;
    	}
    	$st = $paramsStrs.'key='.$key;
    	$sign = hash("sha256", $st);
    	
    	$datas['sign'] = $sign;
    	 
    	$body = json_encode($datas);
    	$result = MtpCurl::httpPost($url, $body);
    	Helper::writeLog('mt退款返回结果：'.$result);
    	
    	if(!empty($result)){
    		$obj = json_decode($result,true);
    		$status = $obj['status'];
    		
    		if($status == 'SUCCESS'){
    			$results = array(
	    				'return_code'=>"SUCCESS",
	    				'result_code'=>"SUCCESS",
	    				'result_msg'=>'SUCCESS',
	    				'msg'=>'退款成功！',
	    		);
    			return $results;
    		}else{
    			$errCode = $obj['errCode'];
    			$errMsg = $obj['errMsg'];
    			if($errCode == 'TRADE_ORDER_REFUNDED_ERROR'){
    				// 订单已全部退款
    				$results = array(
    						'return_code'=>"SUCCESS",
    						'result_code'=>"SUCCESS",
    						'result_msg'=>'SUCCESS',
    						'msg'=>'退款成功！',
    				);
    				return $results;
    			}elseif($errCode == 'TRADE_REFUNDING_ERROR'){
    				// 15次退款查询确认
			    	$queryTimes = 15;
			    	while ($queryTimes > 0){
			    		$queryTimes--;
			    		$returnRes = self::refundQuery(array(
			    				'outTradeNo'=>$outTradeNo,
			    				'refundNo'=>$refundNo,
			    				'appId'=>$appId,
			    				'key'=>$key,
			    				'merchantId'=>$merchantId,
			    		));
			    		$obj = json_decode($returnRes,true);
			    		
			    		$return_status = $obj['status'];
				    	$orderStatus = $obj['orderStatus'];
				    	$tradeNo = $obj['tradeNo'];
				    	// 交易成功
			    		if($return_status=='SUCCESS' && $orderStatus=='ORDER_SUCCESS'){
			    			$results = array(
	    						'return_code'=>"SUCCESS",
	    						'result_code'=>"SUCCESS",
	    						'result_msg'=>'SUCCESS',
	    						'msg'=>'退款成功！',
			    			);
			    			return $results;
			    		}
			    		// 交易失败
			    		if($return_status=='SUCCESS' && ($orderStatus=='ORDER_CLOSE' || $orderStatus=='ORDER_FAILED')){
			    			$results = array(
			    					'return_code'=>"SUCCESS",
		    						'result_code'=>"SUCCESS",
		    						'result_msg'=>'SUCCESS',
		    						'msg'=>'退款失败！',
			    			);
			    			return $results;
			    		}
			    		sleep(2);
			    	}
    			}
    		}
    	}
    	$results = array(
    			'return_code'=>"SUCCESS",
    			'result_code'=>"ERROR",
    			'result_msg'=>"REFUND",
    			'msg'=>'请尝试重新操作！',
    	);
    	return $results;
    }
    /**
     * 该接口用于关闭订单
     */
    public static function close($data){
    	$merchantId = $data['merchantId'];
    	$appId = $data['appId'];
    	$key = $data['key'];
    	$outTradeNo = $data['outTradeNo'];
    	$random = self::getNonceStr();
    
    	$url = MtpConfig::MTP_DOMAIN.'/api/close';
    
    	$datas = array(
    			'outTradeNo'=>$outTradeNo,
    			'merchantId'=>$merchantId,
    			'appId'=>$appId,
    			'random'=>$random,
    	);
    
    	ksort($datas);
    	$paramsStrs = '';
    	if(is_array($datas)){
    		foreach($datas as $k => $v)
    		{
    			$paramsStrs .= $k.'='.$v.'&';
    		}
    	}else{
    		$result = array(
    				"return_code"=>"ERROR",
    				"result_code"=>"ERROR",
    				'msg'=>'未知状态！');
    		return $result;
    	}
    	$st = $paramsStrs.'key='.$key;
    	$sign = hash("sha256", $st);
    
    	$datas['sign'] = $sign;
    	 
    	$body = json_encode($datas);
    	$result = MtpCurl::httpPost($url, $body);
    	Helper::writeLog('关闭订单返回结果：'.$result);
    	return $result;
    }
    /**
     * 该接口用于获取授权用户的Openid
     */
    public static function getOpenId($data,$url){
    	$merchantId = $data['merchantid'];
    	$appId = $data['appid'];
    		
    	$url = "Location: ".MtpConfig::MTP_DOMAIN_SQ."/auth?bizId=".$appId."&mchId=".$merchantId."&redirect_uri=".$url;
    	header($url);
    	exit;
    }
}
?>