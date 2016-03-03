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
	public static function sentMessage($mobile,$content = ''){
		$userid = '';
		$account = 'jksc344';
		$password = 'wymenu6688';
		$url = 'http://sh2.ipyy.com/smsJson.aspx?action=send&userid='.$userid.'&account='.$account.'&password='.$password.'&mobile='.trim($mobile).'&content='.$content.'&sendTime=&extno=';
		$result = Curl::httpsRequest($url);
		return $result;
	}
	
}