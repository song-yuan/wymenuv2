<?php
/**
 * XCXServer.php
 *微信小程序 服务类
 */
 
class XCXServer {
	public static function getOpenId($companyId,$code) {
		$data = array();
		//$account = WxAccount::get($companyId,1);
		$account = array(
				'appid'=>'wx117661a0d7ff37e2',
				'appsecret'=>'ddfbdd040698d94d7964342ee7137e51'
		);
		$appid = $account['appid'];
		$secret = $account['appsecret'];
		$url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$secret.'&js_code='.$code.'&grant_type=authorization_code';
		$res = Curl::https($url);
		$result = json_decode($res,true);
		if(isset($result['openid'])){
			$data = $result;
		}
		return $data;
	}
}
 
?>