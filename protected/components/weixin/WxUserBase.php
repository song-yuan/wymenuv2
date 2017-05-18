<?php
/*
 * Created on 2015-8-21
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class WxUserBase{
 	/**
 	 * 
 	 * 获取用户基础信息
 	 * 
 	 */
 	public $appId;
 	public $appsecret;
 	
 	public function __construct($appId,$appsecret){
 		$this->appId = $appId;
 		$this->appsecret = $appsecret;
 	}
 	public function getSnsapiBase()
	{
		//通过code获得openid
		if (!isset($_GET['code'])){
			//触发微信返回code码
			$baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
			$url = $this->__CreateOauthUrlForCode($baseUrl,'snsapi_base');
			$res = file_get_contents($url);
			var_dump($res);exit;
			//header("Location: ".$url, true, 302);
			exit();
		} else {
			//获取code码，以获取openid
		    $code = $_GET['code'];
			$snsapiBase = $this->getOpenidFromMp($code);
			if(!isset($snsapiBase['openid'])){
				$baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
				$baseUrl = substr($baseUrl,0,strrpos($baseUrl,'code'));
				$url = $this->__CreateOauthUrlForCode($baseUrl,'snsapi_base');
				header("Location: ".$url, true, 302);
			    exit();
			}
			return $snsapiBase;
		}
	}
	/**
 	 * 
 	 * 获取用户详细信息
 	 * 
 	 */
	public function getSnsapiUserinfo()
	{
		//通过code获得openid
		if (!isset($_GET['code'])){
			//触发微信返回code码
			$baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
			$url = $this->__CreateOauthUrlForCode($baseUrl,'snsapi_userinfo');
			header("Location: $url");
			exit();
		} else {
			//获取code码，以获取openid
		    $code = $_GET['code'];
			$snsapiBase = $this->getOpenidFromMp($code);
			$snsapiUserinfo = '';
			if(isset($snsapiBase['openid'])){
				$snsapiUserinfo = $this->getUserInfoFromMp($snsapiBase['openid'],$snsapiBase['access_token']);
			}else{
				$baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
				$baseUrl = substr($baseUrl,strrpos($baseUrl,0,'code'));
				$url = $this->__CreateOauthUrlForCode($baseUrl,'snsapi_userinfo');
				header("Location: $url");
			    exit();
			}
			return $snsapiUserinfo;
		}
	}
	/**
	 * 
	 * 通过code从工作平台获取openid机器access_token
	 * @param string $code 微信跳转回来带上的code
	 * 
	 * @return openid
	 */
	public function getOpenidFromMp($code)
	{
		$url = $this->__CreateOauthUrlForOpenid($code);
		//初始化curl
		$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		//运行curl，结果以jason形式返回
		$res = curl_exec($ch);
		curl_close($ch);
		//取出openid
		$data = json_decode($res,true);
		return $data;
	}
	/**
	 * 
	 * 通过code从工作平台获取openid机器access_token
	 * @param string $code 微信跳转回来带上的code
	 * 
	 * @return openid
	 */
	public function getUserInfoFromMp($openid,$access_token)
	{
		$url = $this->__CreateOauthUrlForUserInfo($openid,$access_token);
		//初始化curl
		$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		
		//运行curl，结果以jason形式返回
		$res = curl_exec($ch);
		curl_close($ch);
		//取出openid
		$data = json_decode($res,true);
		return $data;
	}
	/**
	 * 
	 * 拼接签名字符串
	 * @param array $urlObj
	 * 
	 * @return 返回已经拼接好的字符串
	 */
	private function ToUrlParams($urlObj)
	{
		$buff = "";
		foreach ($urlObj as $k => $v)
		{
			if($k != "sign"){
				$buff .= $k . "=" . $v . "&";
			}
		}
		
		$buff = trim($buff, "&");
		return $buff;
	}
	/**
	 * 
	 * 构造获取code的url连接
	 * @param string $redirectUrl 微信服务器回跳的url，需要url编码
	 * 
	 * @return 返回构造好的url
	 */
	private function __CreateOauthUrlForCode($redirectUrl,$scope)
	{
		$urlObj = array();
		$urlObj["appid"] = $this->appId;
		$urlObj["redirect_uri"] = $redirectUrl;
		$urlObj["response_type"] = "code";
		$urlObj["scope"] = $scope;
		$urlObj["state"] = "STATE#wechat_redirect";
		$bizString = $this->ToUrlParams($urlObj);
		return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
	}
	/**
	 * 
	 * 构造获取open和access_toke的url地址
	 * @param string $code，微信跳转带回的code
	 * 
	 * @return 请求的url
	 */
	private function __CreateOauthUrlForOpenid($code)
	{
		$urlObj = array();
		$urlObj["appid"] = $this->appId;
		$urlObj["secret"] = $this->appsecret;
		$urlObj["code"] = $code;
		$urlObj["grant_type"] = "authorization_code";
		$bizString = $this->ToUrlParams($urlObj);
		return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
	}
	/**
	 * 
	 * 构造获取open和access_toke的url地址
	 * @param string $code，微信跳转带回的code
	 * 
	 * @return 请求的url
	 */
	private function __CreateOauthUrlForUserInfo($openid,$access_token)
	{
		$urlObj = array();
		$urlObj["access_token"] = $access_token;
		$urlObj["openid"] = $openid;
		$urlObj["lang"] = "zh_CN";
		$bizString = $this->ToUrlParams($urlObj);
		return "https://api.weixin.qq.com/sns/userinfo?".$bizString;
	}
 }
?>
