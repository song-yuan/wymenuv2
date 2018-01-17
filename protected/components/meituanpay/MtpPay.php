<?php
/**
 * 
 * 
 * @author dys
 * 收钱吧支付 接口类
 *activate 激活接口
 *checkin 签到接口
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
    	
    	$merchantId = '4282256';
    	//该字段为美团平台分配的商户id，系统后天查询。

    	$url = MtpConfig::SQB_DOMAIN.'/api/pay/micropay';
    	$appId = MtpConfig::MTP_APPID;
    	$key = MtpConfig::MTP_KEY;
    	$datas = array(
    				'channel'=>$channel,
    				'outTradeNo'=>$outTradeNo,
    				'authCode'=>$authCode,
    				'totalFee'=>$totalFee,
    				'subject'=>$subject,
    				'body'=>$body,
    				'opeexpireMinutesrator'=>$expireMinutes,
    				'merchantId'=>$merchantId,
    	);
    	
    	ksort($data);
    	$paramsStrs = '';
    	if(is_array($data)){
    		foreach($data as $k => $v)
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
    	Helper::writeLog($paramsStrs);
    	$result = MtpCurl::httpPost($url, $paramsStrs, $appId, $key);
    	Helper::writeLog($result);
    	return $result;
    	exit;
    	$body = json_encode($datas);
    	$bodystr = 't_sn:'.$terminal_sn.';sn:'.$clientSn.';t_a:'.$total_amount.';pt:'.$paytype;
    	Helper::writeLog($bodystr);
    	$result = SqbCurl::httpPost($url, $body, $terminal_sn , $terminal_key);
    	
    	Helper::writeLog($result);
    	
    	if(!empty($result)){
    		
	    	$obj = json_decode($result,true);
	    	//var_dump($obj);exit;
	    	$return_code = $obj['result_code'];
	    	
	    	$str = $clientSn.';返回信息：'.$return_code;
	    	Helper::writeLog($str);
	    	//判断支付返回状态...
	    	if($return_code == '200'){
	    		$result_codes = $obj['biz_response']['result_code'];
	    		
	    		if($result_codes == 'PAY_SUCCESS'){
	    			$result = array(
	    					"return_code"=>"SUCCESS",
	    					"result_code"=>"SUCCESS",
	    					"msg"=>"支付成功！",
	    					"transaction_id"=>$obj['biz_response']['data']['trade_no'],
	    					"data"=>$obj['biz_response']['data']);
	    			
	    		}elseif($result_codes == 'PAY_FAIL'){
	    			$result = array(
	    					"return_code"=>"SUCCESS",
	    					"result_code"=>"ERROR",
	    					"msg"=>"支付失败！",
	    					"data"=>$obj['biz_response']['data']);
	    			
	    		}elseif($result_codes == 'PAY_IN_PROGRESS'){
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
	    		}elseif($result_codes == 'FAIL'){
	    			$result = array(
			    			"return_code"=>"SUCCESS",
			    			"result_code"=>"ERROR",
			    			"msg"=>"操作失败！",
			    			"data"=>$obj['biz_response']['data']);
	    		}elseif($result_codes == 'SUCCESS'){
	    			$order_status = $obj['biz_response']['data']['order_status'];
	    			if($order_status == 'CREATED'){
	    				
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
	    			}elseif($order_status == 'PAID'){
	    				$result = array(
	    						"return_code"=>"SUCCESS",
	    						"result_code"=>"SUCCESS",
	    						"msg"=>"支付成功！",
	    						"transaction_id"=>$obj['biz_response']['data']['trade_no'],
	    						"data"=>$obj['biz_response']['data']);
	    			}else{
	    				$result = array(
	    						"return_code"=>"SUCCESS",
	    						"result_code"=>"ERROR",
	    						"msg"=>"支付失败！",
	    						"data"=>$obj['biz_response']['data']);
	    			}
	    		}else{
	    			$result = array(
	    					"return_code"=>"SUCCESS",
	    					"result_code"=>"CANCEL",
	    					'msg'=>'未知状态！');
	    		}
	
	    	}elseif($return_code == '200'){
	    		$msg = 'result_code=['.$obj['result_code'].'],error_code=['.$obj['error_code'].'],error_message=['.$obj['error_message'].']';
	    		$result = array("return_code"=>"ERROR","result_code"=>"EROOR","msg"=>$msg);
	    	}elseif($return_code == '200'){
	    		$msg = 'result_code=['.$obj['result_code'].'],error_code=['.$obj['error_code'].'],error_message=['.$obj['error_message'].']';
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
    public static function precreate($data){
    	
    	$dpid = $data['dpid'];
    	$clientSn = $data['out_trade_no'];
    	/*必须在商户系统内唯一；且长度不超过32字节*/
    	$total_amount = $data['should_total'];
    	/*以分为单位,不超过10位纯数字字符串,超过1亿元的收款请使用银行转账*/
    	$payway = $data['pay_way'];
    	$subpayway = $data['sub_pay_way'];
    	/*必传。内容为数字的字符串。一旦设置，则根据支付码判断支付通道的逻辑失效*/
    	$subject = $data['abstract'];
    	/*本次交易的简要介绍*/
    	$operator = $data['operator'];
    	/*发起本次交易的操作员*/
    	$notify_url = $data['notify_url'];
    	
    	$devicemodel = WxCompany::getSqbPayinfo($dpid);
    	if(!empty($devicemodel)){
    		$terminal_sn = $devicemodel['terminal_sn'];
    		$terminal_key = $devicemodel['terminal_key'];
    	}else{
    		$result = array('status'=>false, 'result'=>false);
    		return $result;
    	}
    	
    	$url = SqbConfig::SQB_DOMAIN.'/upay/v2/precreate';
    	$data = array(
    				'terminal_sn'=>$terminal_sn,
    				'client_sn'=>$clientSn,
    				'total_amount'=>$total_amount,
    				'payway'=>$payway,
    				'sub_payway'=>$subpayway,
    				'subject'=>$subject,
    				'operator'=>$operator,
    				'notify_url'=>$notify_url,
    	);
    	$body = json_encode($data);
    	$result = SqbCurl::httpPost($url, $body, $terminal_sn , $terminal_key);
    	$obj = json_decode($result);
    	if($obj->result_code=='200' && $obj->biz_response->result_code=="PRECREATE_SUCCESS"){
    		return array('status'=>true, 'result'=>$obj->biz_response->data);
    	}else{
    		return array('status'=>false, 'result'=>false);
    	}
    
    }
    public static function refund($data){
    	$device_id = $data['device_id'];
    	$refund_amount = $data['refund_amount'];
    	$clientSn = $data['clientSn'];
    	$dpid = $data['dpid'];
    	$operator = $data['operator'];
    	
    	/*查询设备对应的支付秘钥和支付平台对应的终端号*/
    	$devicemodel = WxCompany::getSqbPayinfo($dpid,$device_id);
    	if(!empty($devicemodel)){
    		$terminal_sn = $devicemodel['terminal_sn'];
    		$terminal_key = $devicemodel['terminal_key'];
    	}else{
    		$result = array(
    					"return_code"=>"ERROR",
    					"result_code"=>"ERROR",
    					'msg'=>'未知状态！');
    		return $result;
    	}
    	
//     	/*该接口用于退款，用到的SN及KEY为我们的商户的每一台设备对应的sn和key*/
//     	$terminal_sn = $data['terminal_sn'];
//     	$terminal_key = $data['terminal_key'];
//     	/*终端号及终端秘钥*/
//     	$sn = $data['sn'];
//     	/*收钱吧系统内部唯一订单号*/
//     	$clientSn = $data['clientSn'];
//     	/*商户系统订单号,必须在商户系统内唯一；且长度不超过32字节*/
//     	$refund_request_no = $data['refund_request_no'];
//     	/*商户退款所需序列号，用于唯一标识某次退款请求，以防止意外的重复退款。正常情况下，对同一笔订单进行多次退款请求时该字段不能重复；而当通信质量不佳，终端不确认退款请求是否成功，自动或手动发起的退款请求重试，则务必要保持序列号不变*/
//     	$operator = $data['userName'];
//     	/*发起本次退款的操作员*/
//     	$refund_amount = $data['refund_amount'];
//     	/*退款金额*/
    	
    	$url = SqbConfig::SQB_DOMAIN.'/upay/v2/refund';
    	$data = array(
    			'terminal_sn'=>$terminal_sn,
    			'client_sn'=>$clientSn,
    			'refund_request_no'=>$clientSn,
    			'operator'=>$operator,
    			'refund_amount'=>$refund_amount,
    	);
    	$body = json_encode($data);
    	$result = SqbCurl::httpPost($url, $body, $terminal_sn , $terminal_key);
    	
    	$obj = json_decode($result,true);
    	//var_dump($obj);exit;
    	$return_code = $obj['result_code'];
    	if($return_code == '200'){
    		$result_codes = $obj['biz_response']['result_code'];
    		if($result_codes == 'REFUND_SUCCESS'){
    			$result = array(
    					"return_code"=>"SUCCESS",
    					"result_code"=>"SUCCESS",
    					"msg"=>"退款成功！");
    		}elseif($result_codes == 'REFUND_ERROR'){
    			$result = array(
    					"return_code"=>"SUCCESS",
    					"result_code"=>"ERROR",
    					"msg"=>"退款失败！");
    		}elseif($result_codes == 'REFUND_IN_PROGRESS'){
    			/*发起轮询*/
    			do {
    				$i=1;
    				++$i;
    				$status = true;
    				$resultstatus = SqbPay::query(array(
    						'terminal_sn'=>$terminal_sn,
    						'terminal_key'=>$terminal_key,
    						'client_sn'=>$clientSn,
    				));
    				$rsts = json_decode($resultstatus,true);
    				$q_code = $rsts['result_code'];
    				if($q_code == '200'){
    					$q_re_code = $rsts['biz_response']['data']['order_status'];
    					if($q_re_code == 'REFUNDED'){
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
    						"msg"=>"成功！");
    			}
    		}elseif($result_codes == 'FAIL'){
    			$result = array(
    					"return_code"=>"SUCCESS",
    					"result_code"=>"ERROR",
    					"msg"=>"操作失败！");
    		}elseif($result_codes == 'SUCCESS'){
    			$order_status = $obj['biz_response']['data']['order_status'];
    			if($order_status == 'REFUNDED' || $order_status == 'PARTIAL_REFUNDED'){
    				$result = array(
    						"return_code"=>"SUCCESS",
    						"result_code"=>"SUCCESS",
    						"msg"=>"退款成功！");
    				
    			}elseif($order_status == 'REFUND_ERROR'){
    				$result = array(
    						"return_code"=>"SUCCESS",
    						"result_code"=>"ERROR",
    						"msg"=>"操作失败！");
    			}else{
    				$result = array(
    						"return_code"=>"SUCCESS",
    						"result_code"=>"ERROR",
    						"msg"=>"退款失败！");
    			}
    		}else{
    			$result = array(
    					"return_code"=>"SUCCESS",
    					"result_code"=>"ERROR",
    					'msg'=>'未知状态！');
    		}
    		
    	}else{
    		$result = array(
    					"return_code"=>"ERROR",
    					"result_code"=>"ERROR",
    					'msg'=>'未知状态！');
    	}
    	//var_dump($result);exit;
    	return $result;
    
    }
    // 线上web api
    public static function preOrder($data){
    	 
    	$dpid = $data['dpid'];
    	$client_sn = $data['client_sn'];
    	// 壹点吃后台 支付方式
    	$payChannel = $data['pay_channel'];
    	/*必须在商户系统内唯一；且长度不超过32字节*/
    	$total_amount = ''.$data['total_amount']*100;
    	/*以分为单位,不超过10位纯数字字符串,超过1亿元的收款请使用银行转账*/
    	$subject = $data['subject'];
    	/*本次交易的简要介绍*/
    	$payway = $data['payway'];
    	/*必传。内容为数字的字符串。一旦设置，则根据支付码判断支付通道的逻辑失效*/
    	$operator = $data['operator'];
    	/*发起本次交易的操作员*/
    	$reflect = $data['reflect'];
    	/*原样返回的参数*/
    	$notify_url = $data['notify_url'];
    	/*发起本次交易的回调地址*/
    	$return_url = $data['return_url'];
    	/*发起本次交易的返回地址*/
    	
    	$devicemodel = WxCompany::getSqbPayinfo($dpid);
    	if(!empty($devicemodel)){
    		$terminal_sn = $devicemodel['terminal_sn'];
    		$terminal_key = $devicemodel['terminal_key'];
    	}else{
    		$result = array(
    					"return_code"=>"ERROR",
    					"result_code"=>"ERROR",
    					'msg'=>'未知状态！');
    		return $result;
    	}
    	$data = array(
    			'terminal_sn'=>$terminal_sn,
    			'client_sn'=>$client_sn,
    			'total_amount'=>$total_amount,
    			'subject'=>$subject,
    			'payway'=>$payway,
    			'operator'=>$operator,
    			'reflect'=>$reflect,
    			'notify_url'=>$notify_url,
    			'return_url'=>$return_url,
    	);
    	ksort($data);
    	$paramsStrs = '';
    	if(is_array($data)){
    	foreach($data as $k => $v)
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
    	Helper::writeLog("所有参数拼接:".$paramsStrs);
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
    
    public static function cancel($type,$data){
    	/*该接口用于撤单，用到的SN及KEY为我们的商户的每一台设备对应的sn和key*/
    	$terminal_sn = $data['terminal_sn'];
    	$terminal_key = $data['terminal_key'];
    	/*终端号及终端秘钥*/
    	$sn = $data['sn'];
    	/*收钱吧系统内部唯一订单号*/
    	$clientSn = $data['clientSn'];
    	/*商户系统订单号,必须在商户系统内唯一；且长度不超过32字节*/
    	if($type){
    		$url = SqbConfig::SQB_DOMAIN.'/upay/v2/cancel';
    		/*自动撤单*/
    	}else{
    		$url = SqbConfig::SQB_DOMAIN.'/upay/v2/revoke';
    		/*收到撤单*/
    	}
    	
    	$data = array(
    			'terminal_sn'=>$terminal_sn,
    			'sn'=>$sn,
    			'client_sn'=>$clientSn,
    	);
    	$body = json_encode($data);
    	$result = SqbCurl::httpPost($url, $body, $terminal_sn , $terminal_key);
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
    public static function prequery($data){
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
}
?>