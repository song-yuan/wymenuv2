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
 *
 */
class SqbPay{
	public $api_domain = 'https://api.shouqianba.com';
	
    public static function activate($code,$device_id){
    	$url = $this->api_domain+'/terminal/activate';
    	$data = array(
    			'app_id'=>SqbConfig::APPID,
    			'code'=>$code,
    			'device_id'=>$device_id,
    	);
    	$body = json_encode($data);
    	$vendorSn = SqbConfig::VENDER_SN;
    	$venderKey = SqbConfig::VENDER_KEY;
    	$sign = md5($body+$venderKey);
    	$result = SqbCurl::httpPost($url, $body, $vendorSn, $sign);
    	return $result;
    }
    public static function checkin(){
    	 
    }
    public static function pay(){
    
    }
    public static function precreate(){
    
    }
    public static function refund(){
    
    }
    public static function cancel(){
    
    }
    public static function query(){
    
    }
}
?>