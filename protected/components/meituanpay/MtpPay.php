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
    			'sign'=>$sign,
    	);
    	$body = json_encode($datas);
    	$result = MtpCurl::httpPost($url, $body);
    	Helper::writeLog('mtpay返回结果：'.$result);
    	//return $result;

    	if(!empty($result)){
    		
	    	$obj = json_decode($result,true);
	    	$return_status = $obj['status'];
	    	$tradeNo = $obj['tradeNo'];
	    	$pay_status = $obj['orderStatus'];
	    	
	    	//$str = 'mt付款返回订单号：'.$outTradeNo.';mt订单号：'.$tradeNo.'返回信息：'.$return_status.';支付状态：'.$pay_status;
	    	//Helper::writeLog($str);
	    	//判断支付返回状态...
	    	if($return_status == 'SUCCESS'){
	    		
	    		if($pay_status == 'ORDER_SUCCESS'){
	    			$result = array(
	    					"return_code"=>"SUCCESS",
	    					"result_code"=>"SUCCESS",
	    					"result_msg"=>$pay_status,
	    					"msg"=>"支付成功！",
	    					"transaction_id"=>$obj['tradeNo'],
	    					"order_id"=>$obj['outTradeNo']);
	    			
	    		}elseif($pay_status == 'ORDER_CLOSE' || $pay_status == 'ORDER_FAILED'){
	    			$result = array(
	    					"return_code"=>"SUCCESS",
	    					"result_code"=>"ERROR",
	    					"result_msg"=>$pay_status,
	    					"msg"=>"支付失败！",
	    					"order_id"=>$obj['outTradeNo']);
	    			
	    		}else{
	    			/*发起轮询*/
		    		$i=1;
					$j=true;
					do{
						$i++;
						$results = MtpPay::query(array(
							'outTradeNo'=>$outTradeNo,
			    			'appId'=>$appId,
			    			'key'=>$key,
			    			'merchantId'=>$merchantId,
						));
						$return_code = $results['return_code'];
						$result_code = $results['result_code'];
						$result_msg = $results['result_msg'];
						if($result_msg == 'ORDER_SUCCESS'){
							$result = array(
									"return_code"=>"SUCCESS",
									"result_code"=>"SUCCESS",
	    							"result_msg"=>$result_msg,
									"transaction_id"=>$obj['tradeNo'],
									"msg"=>"支付成功！",
									"accountno"=>$outTradeNo
							);
							$j=false;
						}
						sleep(2);
					}while (($i<=15)&&$j);
					if(($i==15)&&$j){
						$result = array(
									"return_code"=>"SUCCESS",
									"result_code"=>"ERROR",
									"result_msg"=>'REQUERY',
									"msg"=>"查询失败！",
									"accountno"=>$outTradeNo);
					}
	    		}
	
	    	}elseif($return_status == 'FAIL'){
	    		$re_code = $obj['errCode'];
	    		if($re_code == "TRADE_PAY_UNKOWN_ERROR" || $re_code == "TRADE_PAYING_ERROR"){
	    			/*正在支付，发起轮询*/
	    		/*发起轮询*/
		    		$i=1;
					$j=true;
					do{
						$i++;
						$results = MtpPay::query(array(
							'outTradeNo'=>$outTradeNo,
			    			'appId'=>$appId,
			    			'key'=>$key,
			    			'merchantId'=>$merchantId,
						));
						$return_code = $results['return_code'];
						$result_code = $results['result_code'];
						$result_msg = $results['result_msg'];
						if($result_msg == 'ORDER_SUCCESS'){
							$result = array(
									"return_code"=>"SUCCESS",
									"result_code"=>"SUCCESS",
	    							"result_msg"=>$result_msg,
									"transaction_id"=>$obj['tradeNo'],
									"msg"=>"支付成功！",
									"accountno"=>$outTradeNo
							);
							$j=false;
						}
						sleep(2);
					}while (($i<=15)&&$j);
					if(($i==15)&&$j){
						$result = array(
									"return_code"=>"SUCCESS",
									"result_code"=>"ERROR",
									"result_msg"=>'REQUERY',
									"msg"=>"查询失败！",
									"accountno"=>$outTradeNo);
					}
	    		}else{
	    			$result = array("return_code"=>"ERROR","result_code"=>"EROOR","msg"=>$obj['errMsg']);
	    		}
	    		
	    	}else{
	    		$result = array(
	    				"return_code"=>"SUCCESS",
	    				"result_code"=>"CANCEL",
	    				'msg'=>'未知状态！');
	    	}
    	}else{
    		
    		$str = $outTradeNo.';无返回信息：进入轮询。';
    		Helper::writeLog($str);
    		/*正在支付，发起轮询*/
	    		$i=1;
				$j=true;
				do{
					$i++;
					$results = MtpPay::query(array(
							'outTradeNo'=>$outTradeNo,
			    			'appId'=>$appId,
			    			'key'=>$key,
			    			'merchantId'=>$merchantId,
						));
					$return_code = $results['return_code'];
					$result_code = $results['result_code'];
					$result_msg = $results['result_msg'];
					if($result_msg == 'ORDER_SUCCESS'){
						$result = array(
								"return_code"=>"SUCCESS",
								"result_code"=>"SUCCESS",
    							"result_msg"=>$result_msg,
								"transaction_id"=>$obj['tradeNo'],
								"msg"=>"支付成功！",
								"accountno"=>$outTradeNo
						);
						$j=false;
					}
					sleep(2);
				}while (($i<=15)&&$j);
				if(($i==15)&&$j){
					$result = array(
								"return_code"=>"SUCCESS",
								"result_code"=>"ERROR",
								"result_msg"=>'REQUERY',
								"msg"=>"查询失败！",
								"accountno"=>$outTradeNo);
				}
    	}
    	return $result;
    }

    // 线上web api
    public static function preOrder($data){
    	//     	/*该接口用于美团线上支付*/
    	//     	$channel = $data['channel'];
    	//     	/*支付渠道、必填项、最大64字符、'wx_barcode_pay':微信刷卡支付'ali_barcode_pay':支付宝刷卡支付*/
    	//     	$totalFee = $data['totalFee'];
    	//     	/*以分为单位,*/
    	//     	$outTradeNo = $data['outTradeNo'];
    	//     	/*必传。内容为字符串。接入方订单号 不超过64位*/
    	//     	$subject = $data['subject'];
    	//     	/*商品标题*/
    	//     	$body = $data['body'];
    	//		/*商品详情*/
    	//     	$channel = $data['channel'];
    	//		/*支付渠道：'wx_scan_pay':微信扫码支付 ；'ali_scan_pay':支付宝扫码支付*/
    	//     	$expireMinutes = $data['expireMinutes'];
    	//		/*创建支付订单后，订单关闭时间，单位为分钟。默认设置为5分钟,最长不超过30分钟，超过关单时间无法支付*/
    	//     	$tradeType = $data['tradeType'];
    	//		/*交易类型'NATIVE'： 返回二维码url (动态二维码)'JSAPI'： 返回会话标识等信息调起客户端支付sdk(静态二维码)*/
    	//     	$openId = $data['openId'];
    	//		/*微信主扫且tradeType为JSAPI时必填,为支付宝或微信各自的openId，获取方式参考H5接口*/
    	//     	$notifyUrl = $data['notifyUrl'];
    	//		/*支付成功通知回调地址*/
    	//     	$merchantId = $data['merchantId'];
    	//		/*开放平台分配的商户id, 目前是 美团POI ID*/
    	//     	$appId= $data['appId'];
    	//		/*接入方标识，由美团开放平台分配 参考 https://platform.meituan.com/buffet/list*/
    	//     	$sign= $data['sign'];
    	//		/*验证签名*/
    	//     	$random= $data['random'];
    	//		/*随机数*/
    	//     	$wxSubAppId= $data['wxSubAppId'];
    	//		/*用到小程序支付才有，申请小程序时微信分配的小程序的appid 此参数不参与签名*/
    	$merchantId = $data['merchantId'];
    	$appId = $data['appId'];
    	$key = $data['key'];
    	$outTradeNo = $data['outTradeNo'];
    	$totalFee = $data['totalFee'];
    	$subject = $data['subject'];
    	$body = $data['body'];
    	$channel = $data['channel'];
    	$expireMinutes = $data['expireMinutes'];
    	$tradeType = $data['tradeType'];
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
    		//Helper::writeLog('美团支付参数：'.$st);
    		$sign = hash("sha256", $st);
    		//Helper::writeLog('加密:'.$sign);
    		
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
    				'sign'=>$sign,
    		);
    		
    		$body = json_encode($datas);
    		Helper::writeLog('公众号支付传输参数：'.$body);
    		$result = MtpCurl::httpPost($url, $body);
    		Helper::writeLog('公众号支付返回结果：'.$result);
    		 
    		if(!empty($result)){
    			$obj = json_decode($result,true);
    			$status = $obj['status'];
    			if($status=='SUCCESS'){
    				$resulturl = urlencode($returnUrl);
    				//回调地址
    				//$wxappid = 'wxc57dd1ee95c70c2c';
    				$appIds = $obj['appId'];
    				$timeStamp = $obj['timeStamp'];
    				$nonceStr = $obj['nonceStr'];
    				$signType = $obj['signType'];
    				$paySign = $obj['paySign'];
    				$prepayId = $obj['prepayId'];
    				 
    				$url = "http://openpay.zc.st.meituan.com/pay/?bizId=".$appId."&appId=".$appIds."&nonceStr=".$nonceStr."&prepay_id=".$prepayId."&paySign=".$paySign."&timeStamp=".$timeStamp."&signType=".$signType."&redirect_uri=".$resulturl;
    				Helper::writeLog('已进入支付：'.$url);
    				header("Location:".$url);
    			}
    		}
    		return $result;exit;
    } 
    
    public static function close($data){
    	/*该接口用于关闭订单，*/
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
    	//Helper::writeLog('关闭订单参数：'.$st);
    	$sign = hash("sha256", $st);
    	//Helper::writeLog('关闭订单加密:'.$sign);
    	
    	$datas = array(
    			'outTradeNo'=>$outTradeNo,
    			'merchantId'=>$merchantId,
    			'appId'=>$appId,
    			'random'=>$random,
    			'sign'=>$sign,
    	);
    	
    	$body = json_encode($datas);
    	Helper::writeLog('关闭订单传输参数：'.$body);
    	$result = MtpCurl::httpPost($url, $body);
    	Helper::writeLog('关闭订单返回结果：'.$result);
    	return $result;
    
    }
    public static function query($data){
    	/*该接口用于查询订单状态，*/
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
    	
    	$datas = array(
    			'outTradeNo'=>$outTradeNo,
    			'merchantId'=>$merchantId,
    			'appId'=>$appId,
    			'random'=>$random,
    			'sign'=>$sign,
    	);
    	$body = json_encode($datas);
    	//Helper::writeLog('mt查询订单传输参数：'.$body);
    	$result = MtpCurl::httpPost($url, $body);
    	//Helper::writeLog('mt查询订单返回信息：'.$result);
    	if(!empty($result)){
    		$obj = json_decode($result,true);
    		$return_status = $obj['status'];
    		//Helper::writeLog('返回信息：'.$return_status);
	    	if($return_status == 'SUCCESS'){

	    		$tradeNo = $obj['tradeNo'];
	    		$outTradeNo = $obj['outTradeNo'];
	    		$pay_status = $obj['orderStatus'];
	    		//Helper::writeLog('返回信息订单号：'.$outTradeNo.'，'.$tradeNo.','.$pay_status);
	    		
	    		if($pay_status == 'ORDER_SUCCESS'){
	    			$results = array(
	    					'return_code'=>"SUCCESS",
	    					'result_code'=>"SUCCESS",
	    					'result_msg'=>$pay_status,
	    					"tradeNo"=>$tradeNo,
	    					'msg'=>'支付成功！',
	    			);
	    		}elseif ($pay_status == 'ORDER_FAILED' || $pay_status == 'ORDER_CLOSE'){
	    			$results = array(
	    					'return_code'=>"SUCCESS",
	    					'result_code'=>"ERROR",
	    					'result_msg'=>$pay_status,
	    					'msg'=>'交易关闭！',
	    			);
	    		}elseif ($pay_status == 'ORDER_PART_REFUND'){
	    			$results = array(
	    					'return_code'=>"SUCCESS",
	    					'result_code'=>"SUCCESS",
	    					'result_msg'=>$pay_status,
	    					"tradeNo"=>$tradeNo,
	    					'msg'=>'交易部分退款！',
	    			);
	    		}elseif ($pay_status == 'ORDER_ALL_REFUND'){
	    			$results = array(
	    					'return_code'=>"SUCCESS",
	    					'result_code'=>"SUCCESS",
	    					'result_msg'=>$pay_status,
	    					'msg'=>'交易全部退款！',
	    			);
	    		}elseif ($pay_status == 'ORDER_REFUNDING'){
	    			$results = array(
	    					'return_code'=>"SUCCESS",
	    					'result_code'=>"ERROR",
	    					'result_msg'=>$pay_status,
	    					'msg'=>'交易正在进行退款！',
	    			);
	    		}else{
	    			$results = array(
	    					'return_code'=>"SUCCESS",
	    					'result_code'=>"ERROR",
	    					'result_msg'=>'REQUERY',
	    					'msg'=>'重新查询！',
	    			);
	    		}
	    	}else{
	    		$errmsg = $obj['errMsg'];
	    		Helper::writeLog('美团支付查询订单错误信息：'.$outTradeNo.$errmsg);
	    		$results = array(
	    				'return_code'=>"SUCCESS",
	    				'result_code'=>"ERROR",
	    				'result_msg'=>'REQUERY',
	    				'msg'=>'查询失败！',
	    		);
	    	}
    	}
    	return $results;
    }

    public static function refund($data){
    	/*该接口用于订单退款，*/
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
    	 
    	$datas = array(
    			'outTradeNo'=>$outTradeNo,
    			'refundFee'=>$refundFee,
    			'refundNo'=>$refundNo,
    			'refundReason'=>$refundReason,
    			'merchantId'=>$merchantId,
    			'appId'=>$appId,
    			'random'=>$random,
    			'sign'=>$sign,
    	);
    	 
    	$body = json_encode($datas);
    	//Helper::writeLog('mt退款传输参数：'.$body);
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
    		}else{
    			$errCode = $obj['errCode'];
    			$errMsg = $obj['errMsg'];
    			if($errCode == 'TRADE_REFUND_NO_AOUNT_ERROR'){
    				$results = array(
    						'return_code'=>"SUCCESS",
    						'result_code'=>"SUCCESS",
    						'result_msg'=>'SUCCESS',
    						'msg'=>'退款成功！',
    				);
    			}elseif($errCode == 'TRADE_REFUNDING_ERROR'){
    				/*发起轮询*/
    				$i=1;
    				$j=true;
    				do{
    					$i++;
    					$results = MtpPay::query(array(
							'outTradeNo'=>$outTradeNo,
			    			'appId'=>$appId,
			    			'key'=>$key,
			    			'merchantId'=>$merchantId,
						));
    					$return_code = $results['return_code'];
    					$result_code = $results['result_code'];
    					$result_msg = $results['result_msg'];
    					if($result_msg == 'ORDER_PART_REFUND' || $result_msg == 'ORDER_ALL_REFUND'){
    						$result = array(
    								"return_code"=>"SUCCESS",
    								"result_code"=>"SUCCESS",
    								"result_msg"=>$result_msg,
    								"msg"=>"退款成功！",
    								"accountno"=>$outTradeNo);
    						$j=false;
    					}
    					sleep(2);
    				}while (($i<=15)&&$j);
    				if(($i==15)&&$j){
    					$result = array(
    							"return_code"=>"SUCCESS",
    							"result_code"=>"ERROR",
    							"result_msg"=>'REFUND',
    							"msg"=>"退款失败,重新操作！",
    							"accountno"=>$outTradeNo);
    				}
    			}else{
    				$results = array(
    						'return_code'=>"SUCCESS",
    						'result_code'=>"ERROR",
    						'result_msg'=>$errCode,
    						'msg'=>$errMsg,
    				);
    			}
    		}
    	}else{
    		$results = array(
    				'return_code'=>"SUCCESS",
    				'result_code'=>"ERROR",
    				'result_msg'=>"REFUND",
    				'msg'=>'请尝试重新操作！',
    		);
    	}
    	
    	return $results;
    
    }
    public static function getOpenId($data,$url){
    	/*该接口用于获取授权，*/
    		$merchantId = $data['merchantid'];
    		$appId = $data['appid'];
    		
    		$url = "Location: http://openpay.zc.st.meituan.com/auth?bizId=".$appId."&mchId=".$merchantId."&redirect_uri=".$url;
			//Helper::writeLog('获取授权：'.$url);
    		header($url);
    	 	exit;
    }
}
?>