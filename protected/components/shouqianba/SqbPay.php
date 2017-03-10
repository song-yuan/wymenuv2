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
 *code 98093516
 */
class SqbPay{
    public static function activate($code,$device_id){
    	$url = SqbConfig::SQB_DOMAIN.'/terminal/activate';
    	$data = array(
	    			'app_id'=>SqbConfig::APPID,
	    			'code'=>$code,
	    			'device_id'=>$device_id
    			);
    	$body = json_encode($data);
    	$vendorSn = SqbConfig::VENDER_SN;
    	$venderKey = SqbConfig::VENDER_KEY;
    	$result = SqbCurl::httpPost($url, $body, $vendorSn, $venderKey);
    	return $result;
    }
    public static function checkin(){
    	$url = SqbConfig::SQB_DOMAIN.'/terminal/checkin';
    	$data = array(
    				'terminal_sn'=>SqbConfig::VENDER_SN,
    				'device_id'=>$device_id,
    	);
    	$body = json_encode($data);
    	$vendorSn = SqbConfig::VENDER_SN;
    	$venderKey = SqbConfig::VENDER_KEY;
    	$result = SqbCurl::httpPost($url , $body, $vendorSn , $venderKey);
    	return $result;
    }
    public static function pay($dpid,$data){
    	
    	$clientSn = $data['clientSn'];
    	/*必须在商户系统内唯一；且长度不超过32字节*/
    	$total_amount = $data['totalAmount'];
    	/*以分为单位,不超过10位纯数字字符串,超过1亿元的收款请使用银行转账*/
    	$paytype = $data['payType'];
    	/*非必传。内容为数字的字符串。一旦设置，则根据支付码判断支付通道的逻辑失效*/
    	$dynamicId = $data['dynamicId'];
    	/*条码内容*/
    	$subject = $data['abstract'];
    	/*本次交易的简要介绍*/
    	$operator = $data['userName'];
    	/*发起本次交易的操作员*/
    	
    	$url = SqbConfig::SQB_DOMAIN.'/upay/v2/pay';
    	$data = array(
    				'terminal_sn'=>SqbConfig::VENDER_SN,
    				'client_sn'=>$clientSn,
    				'total_amount'=>$total_amount,
    				'payway'=>$paytype,
    				'dynamic_id'=>$dynamicId,
    				'subject'=>$subject,
    				'operator'=>$operator,
    	);
    	$body = json_encode($data);
    	$vendorSn = SqbConfig::VENDER_SN;
    	$venderKey = SqbConfig::VENDER_KEY;
    	$result = SqbCurl::httpPost($url, $body, $vendorSn, $venderKey);
    	return $result;
    }
    public static function precreate($data){
    	
    	$clientSn = $data['clientSn'];
    	/*必须在商户系统内唯一；且长度不超过32字节*/
    	$total_amount = $data['totalAmount'];
    	/*以分为单位,不超过10位纯数字字符串,超过1亿元的收款请使用银行转账*/
    	$paytype = $data['payType'];
    	/*非必传。内容为数字的字符串。一旦设置，则根据支付码判断支付通道的逻辑失效*/
    	$openId = $data['openId'];
    	/*消费者在支付通道的唯一id,微信WAP支付必须传open_id*/
    	$subject = $data['abstract'];
    	/*本次交易的简要介绍*/
    	$operator = $data['userName'];
    	/*发起本次交易的操作员*/
    	
    	$url = SqbConfig::SQB_DOMAIN.'/upay/v2/precreate';
    	$data = array(
    				'terminal_sn'=>SqbConfig::VENDER_SN,
    				'client_sn'=>$clientSn,
    				'total_amount'=>$total_amount,
    				'payway'=>$paytype,
    				'payer_uid'=>$openId,
    				'subject'=>$subject,
    				'operator'=>$operator,
    	);
    	$body = json_encode($data);
    	$vendorSn = SqbConfig::VENDER_SN;
    	$venderKey = SqbConfig::VENDER_KEY;
    	$result = SqbCurl::httpPost($url, $body, $vendorSn, $venderKey);
    	return $result;
    
    }
    public static function refund($data){

    	$sn = $data['sn'];
    	/*收钱吧系统内部唯一订单号*/
    	$clientSn = $data['clientSn'];
    	/*商户系统订单号,必须在商户系统内唯一；且长度不超过32字节*/
    	$refund_request_no = $data['refund_request_no'];
    	/*商户退款所需序列号，用于唯一标识某次退款请求，以防止意外的重复退款。正常情况下，对同一笔订单进行多次退款请求时该字段不能重复；而当通信质量不佳，终端不确认退款请求是否成功，自动或手动发起的退款请求重试，则务必要保持序列号不变*/
    	$operator = $data['userName'];
    	/*发起本次退款的操作员*/
    	$refund_amount = $data['refund_amount'];
    	/*退款金额*/
    	
    	$url = SqbConfig::SQB_DOMAIN.'/upay/v2/refund';
    	$data = array(
    			'terminal_sn'=>SqbConfig::VENDER_SN,
    			'sn'=>$sn,
    			'client_sn'=>$clientSn,
    			'refund_request_no'=>$refund_request_no,
    			'operator'=>$operator,
    			'refund_amount'=>$refund_amount,
    	);
    	$body = json_encode($data);
    	$vendorSn = SqbConfig::VENDER_SN;
    	$venderKey = SqbConfig::VENDER_KEY;
    	$result = SqbCurl::httpPost($url, $body, $vendorSn, $venderKey);
    	return $result;
    
    }
    public static function cancel($type,$data){

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
    			'terminal_sn'=>SqbConfig::VENDER_SN,
    			'sn'=>$sn,
    			'client_sn'=>$clientSn,
    	);
    	$body = json_encode($data);
    	$vendorSn = SqbConfig::VENDER_SN;
    	$venderKey = SqbConfig::VENDER_KEY;
    	$result = SqbCurl::httpPost($url, $body, $vendorSn, $venderKey);
    	return $result;
    
    }
    public static function query($data){

    	$sn = $data['sn'];
    	/*收钱吧系统内部唯一订单号*/
    	$clientSn = $data['clientSn'];
    	/*商户系统订单号,必须在商户系统内唯一；且长度不超过32字节*/
    	
    	$url = SqbConfig::SQB_DOMAIN.'/upay/v2/query';
    	$data = array(
    			'terminal_sn'=>SqbConfig::VENDER_SN,
    			'sn'=>$sn,
    			'client_sn'=>$clientSn,
    	);
    	$body = json_encode($data);
    	$vendorSn = SqbConfig::VENDER_SN;
    	$venderKey = SqbConfig::VENDER_KEY;
    	$result = SqbCurl::httpPost($url, $body, $vendorSn, $venderKey);
    	return $result;
    
    }
}
?>