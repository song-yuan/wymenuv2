<?php 
/**
 * 
 * 
 * 发生短信类
 * 
 * 
 */
class WxSentMessage
{
	/**
	 * 
	 * 发送手机短信
	 * 
	 */
	public static function sentMessage($mobile,$type = 0){
		$code = rand(1000,9999);
		$contentTpl = array('您的验证码是：'.$code.'【物易科技】','');
		
		$userid = '';
		$account = 'jl01';
		$password = 'ab123456';
		$content = $contentTpl[$type];
		$url = 'http://sh2.ipyy.com/smsJson.aspx?action=send&userid='.$userid.'&account='.$account.'&password='.$password.'&mobile='.trim($mobile).'&content='.$content.'&sendTime=&extno=';
		$result = Curl::httpsRequest($url);
		return $result;
	}
	
}