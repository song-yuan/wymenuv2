<?php
/**
 * 
 * 
 * @author dys
 * 收钱吧支付 接口类
 *pay 支付接口
 *precreate 预下单接口
 *refund 退款接口
 *cancel 撤单接口
 *query 查询接口
 *code 14599168
 */
class MtpPay{
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
    	$channel = $data['channel'];
    	$outTradeNo = $data['outTradeNo'];
    	$authCode = $data['authCode'];
    	$totalFee = $data['totalFee'];
    	$subject = $data['subject'];
    	$body = $data['body'];
    	$expireMinutes = $data['expireMinutes'];
    	$dpid =$data['dpid'];
    	$random = $data['random'];
    	
    	$merchantId = '4282256';
    	//该字段为美团平台分配的商户id，系统后天查询。

    	$url = MtpConfig::MTP_DOMAIN.'/api/pay/micropay';
    	$appId = MtpConfig::MTP_APPID;
    	$key = MtpConfig::MTP_KEY;
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
    				'msg'=>'未知状态！');
    		return $result;
    	}
    	$st = $paramsStrs.'key='.$key;
    	Helper::writeLog('参数：'.$st);
    	$sign=hash('sha256', $st , false);
    	Helper::writeLog('加密:'.$sign);
    	
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
    	Helper::writeLog('返回结果：'.$result);
    	return $result;

    	Helper::writeLog($result);
    	
    	if(!empty($result)){
    		
	    	$obj = json_decode($result,true);
	    	//var_dump($obj);exit;
	    	$return_status = $obj['status'];
	    	
	    	$str = $clientSn.';返回信息：'.$return_status;
	    	Helper::writeLog($str);
	    	//判断支付返回状态...
	    	if($return_status == 'SUCCESS'){
	    		$pay_status = $obj['orderStatus'];
	    		
	    		if($pay_status == 'ORDER_SUCCESS'){
	    			$result = array(
	    					"return_code"=>"SUCCESS",
	    					"result_code"=>"SUCCESS",
	    					"msg"=>"支付成功！",
	    					"transaction_id"=>$obj['tradeNo'],
	    					"order_id"=>$obj['outTradeNo']);
	    			
	    		}elseif($pay_status == 'ORDER_CLOSE'){
	    			$result = array(
	    					"return_code"=>"SUCCESS",
	    					"result_code"=>"ERROR",
	    					"msg"=>"支付失败！",
	    					"order_id"=>$obj['outTradeNo']);
	    			
	    		}elseif($pay_status == 'ORDER_FAILED'){
	    			$result = array(
	    					"return_code"=>"SUCCESS",
	    					"result_code"=>"ERROR",
	    					"msg"=>"支付失败！",
	    					"order_id"=>$obj['outTradeNo']);
	    			
	    		}elseif($pay_status == 'ORDER_REVERSALING'){
	    			/*发起轮询*/
	    			do {
	    				$i=1;
	    				++$i;
	    				$status = true;
	    				$resultstatus = SqbPay::query(array(
	    						'terminal_sn'=>$terminal_sn,
	    						'terminal_key'=>$terminal_key,
	    						'sn'=>$obj['biz_response']['data']['sn'],
	    						'client_sn'=>$clientSn,
	    				));
	    				$rsts = json_decode($resultstatus,true);
	    				$q_code = $rsts['result_code'];
	    				if($q_code == '200'){
	    					$q_re_code = $rsts['biz_response']['data']['order_status'];
	    					if($q_re_code == 'CREATED'){
	    						'支付中...';
	    						$status = true;
	    					}elseif($q_re_code == 'PAID'){
	    						'支付成功';
	    						$status = false;
	    					}elseif($q_re_code == 'REFUNDED'){
	    						'成功退款';
	    						$status = false;
	    					}elseif($q_re_code == 'PARTIAL_REFUNDED'){
	    						'成功部分退款';
	    						$status = false;
	    					}else{
	    						'其他';
	    						$status = true;
	    					}
	    				}else{
	    					$status = true;
	    				}
	    				
	    			}while ($i<=6&&$status);
	    			
	    			if($status){
	    				$result = array(
	    						"return_code"=>"SUCCESS",
	    						"result_code"=>"ERROR",
	    						"msg"=>"失败！",
	    						"data"=>'');
	    			}else{
	    				$result = array(
	    						"return_code"=>"SUCCESS",
	    						"result_code"=>"SUCCESS",
	    						"msg"=>"支付成功！",
	    						"transaction_id"=>$rsts['biz_response']['data']['trade_no'],
	    						"data"=>$rsts['biz_response']['data']);
	    			}
	    		}else{
	    			$result = array(
	    					"return_code"=>"SUCCESS",
	    					"result_code"=>"CANCEL",
	    					'msg'=>'未知状态！');
	    		}
	
	    	}elseif($return_status == 'FAIL'){
	    		$msg = 'result_code=['.$obj['errCode'].'],error_code=['.$obj['errCode'].'],error_message=['.$obj['errMsg'].']';
	    		$result = array("return_code"=>"ERROR","result_code"=>"EROOR","msg"=>$msg);
	    	}else{
	    		$result = array(
	    				"return_code"=>"SUCCESS",
	    				"result_code"=>"CANCEL",
	    				'msg'=>'未知状态！');
	    	}
    	}else{
    		
    		$str = $clientSn.';无返回信息：进入轮询。';
    		Helper::writeLog($str);
    		/*发起轮询*/
    		do {
    			$i=1;
    			++$i;
    			$status = true;
    			$resultstatus = SqbPay::query(array(
    					'terminal_sn'=>$terminal_sn,
    					'terminal_key'=>$terminal_key,
    					'sn'=>'',
    					'client_sn'=>$clientSn,
    			));
    			$rsts = json_decode($resultstatus,true);
    			$q_code = $rsts['result_code'];
    			if($q_code == '200'){
    				$q_re_code = $rsts['biz_response']['data']['order_status'];
    				if($q_re_code == 'CREATED'){
    					'支付中...';
    					$status = true;
    				}elseif($q_re_code == 'PAID'){
    					'支付成功';
    					$status = false;
    				}elseif($q_re_code == 'REFUNDED'){
    					'成功退款';
    					$status = false;
    				}elseif($q_re_code == 'PARTIAL_REFUNDED'){
    					'成功部分退款';
    					$status = false;
    				}else{
    					'其他';
    					$status = true;
    				}
    			}else{
    				$status = true;
    			}
    			sleep(1);
    		}while ($i<=3&&$status);
    		
    		if($status){
    			$result = array(
    					"return_code"=>"SUCCESS",
    					"result_code"=>"ERROR",
    					"msg"=>"失败！",
    					"data"=>'');
    		}else{
    			$result = array(
    					"return_code"=>"SUCCESS",
    					"result_code"=>"SUCCESS",
    					"msg"=>"支付成功！",
    					"transaction_id"=>$rsts['biz_response']['data']['trade_no'],
    					"data"=>$rsts['biz_response']['data']);
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
    	$outTradeNo = $data['outTradeNo'];
    	$totalFee = $data['totalFee'];
    	$subject = $data['subject'];
    	$body = $data['body'];
    	$channel = $data['channel'];
    	$expireMinutes = $data['expireMinutes'];
    	$tradeType = $data['tradeType'];
    	$notifyUrl = $data['notifyUrl'];
    	$random = $data['random'];
    	 
    	$merchantId = '4282256';
    	//该字段为美团平台分配的商户id，系统后天查询。
    	
    	$dpid =$data['dpid'];
    	
    	$url = MtpConfig::MTP_DOMAIN.'/api/precreate';
    	$appId = MtpConfig::MTP_APPID;
    	$key = MtpConfig::MTP_KEY;
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
    			'openId'=>'oIj93t8fhn5tW00Ts5rSrFyEPbZo',
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
    	Helper::writeLog('参数：'.$st);
    	$sign=hash('sha256', $st , false);
    	Helper::writeLog('加密:'.$sign);
    	 
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
    			'openId'=>'oIj93t8fhn5tW00Ts5rSrFyEPbZo',
    			'sign'=>$sign,
    	);

    	$body = json_encode($datas);
    	Helper::writeLog('公众号支付传输参数：'.$body);
    	$result = MtpCurl::httpPost($url, $body);
    	Helper::writeLog('公众号支付返回结果：'.$result);
    	return $result;
    	exit;
    	if(!empty($paramsStrs)){
    		$paramsStr = rtrim($paramsStrs,"&");
    		$sign = strtoupper(md5($paramsStr.'&key='.$terminal_key));
    		$paramsStr = $paramsStr."&sign=".$sign;
    		
    		if($payChannel==2){
    			Helper::writeLog($client_sn.'&&'.$terminal_sn);
	    		$string = "Location:https://m.wosai.cn/qr/gateway?".$paramsStr;
	    		Helper::writeLog("支付请求链接:".$string);
	    		header("Location:https://m.wosai.cn/qr/gateway?".$paramsStr);
    		}else{
    			Helper::writeLog($client_sn.'&&'.$terminal_sn);
    			$string = "Location:https://qr.shouqianba.com/gateway?".$paramsStr;
    			Helper::writeLog("支付请求链接:".$string);
    			header("Location:https://qr.shouqianba.com/gateway?".$paramsStr);
    		}
    		//exit;
    	}else{
    		$result = array(
    				"return_code"=>"ERROR",
    				"result_code"=>"ERROR",
    				'msg'=>'未知状态！');
    		
    		return $result;
    	}
    	
    } 
    
    public static function close($data){
    	/*该接口用于关闭订单，*/
    	$outTradeNo = $data['outTradeNo'];
    	$random = $data['random'];
    	
    	$merchantId = '4282256';
    	$url = SqbConfig::SQB_DOMAIN.'/api/close';
    	$appId = MtpConfig::MTP_APPID;
    	$key = MtpConfig::MTP_KEY;
    	
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
    	Helper::writeLog('关闭订单参数：'.$st);
    	$sign=hash('sha256', $st , false);
    	Helper::writeLog('关闭订单加密:'.$sign);
    	
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
    	/*该接口用于查询，用到的SN及KEY为我们的商户的每一台设备对应的sn和key*/
    	$terminal_sn = $data['terminal_sn'];
    	$terminal_key = $data['terminal_key'];
    	/*终端号及终端秘钥*/
    	$sn = $data['sn'];
    	/*收钱吧系统内部唯一订单号*/
    	$clientSn = $data['client_sn'];
    	/*商户系统订单号,必须在商户系统内唯一；且长度不超过32字节*/
    	
    	$url = SqbConfig::SQB_DOMAIN.'/upay/v2/query';
    	$data = array(
    			'terminal_sn'=>$terminal_sn,
    			'sn'=>$sn,
    			'client_sn'=>$clientSn,
    	);
    	$body = json_encode($data);
    	$result = SqbCurl::httpPost($url, $body, $terminal_sn , $terminal_key);
    	return $result;
    
    }

    public static function refund($data){
    	/*该接口用于关闭订单，*/
    	$outTradeNo = $data['outTradeNo'];
    	$refundFee = $data['refundFee'];
    	$refundNo = $data['refundNo'];
    	$refundReason = $data['refundReason'];
    	$merchantId = $data['merchantId'];
    	$random = $data['random'];
    	 
    	$merchantId = '4282256';
    	$url = SqbConfig::SQB_DOMAIN.'/api/refund';
    	$appId = MtpConfig::MTP_APPID;
    	$key = MtpConfig::MTP_KEY;
    	 
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
    		$result = array(
    				"return_code"=>"ERROR",
    				"result_code"=>"ERROR",
    				'msg'=>'未知状态！');
    		return $result;
    	}
    	$st = $paramsStrs.'key='.$key;
    	Helper::writeLog('关闭订单参数：'.$st);
    	$sign=hash('sha256', $st , false);
    	Helper::writeLog('关闭订单加密:'.$sign);
    	 
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
}
?>