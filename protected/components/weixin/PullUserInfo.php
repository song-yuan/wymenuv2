<?php
/**
 * PullUserInfo.php
 * 拉取微信用户的信息
 * 
 * 注意：
 * 拉取用户的信息有两种方式
 * 1.关注后，通过openId(包括unionId)来取用的基本信息
 * 	 请求接口：https://api.weixin.qq.com/cgi-bin/user/info?access_token=ACCESS_TOKEN&openid=OPENID&lang=zh_CN
 * 2.oauth协议拉取用户的基本信息
 *   请求接口：https://api.weixin.qq.com/sns/userinfo?access_token=ACCESS_TOKEN&openid=OPENID&lang=zh_CN
 * 本处采用的第一种方式，没有涉及到第二种方式
 * 
 * http请求方式: GET
 * https://api.weixin.qq.com/cgi-bin/user/info?access_token=ACCESS_TOKEN&openid=OPENID&lang=zh_CN
 * 
 * @property Integer $result 代表是否成功拉取用户信息并插入到数据库中
 */

class PullUserInfo {
	public $result = 0;
	
	/**
	 * 初始化
	 */
	public function __construct($brandId, $userId) {
		$this->brandId = $brandId;
		$this->userId = $userId;
		$this->accessToken();
		$this->openId();
		$this->curl();
		$this->update();
	}
	
	/**
	 * 获取access_token
	 */
	public function accessToken() {
		$accessToken = new AccessToken($this->brandId);
		$this->accessToken = $accessToken->accessToken;
	}
	
	/**
	 * 获取openId
	 * 此处注意传递的userId直接是openId，则直接赋值给$this->openId
	 */
	public function openId() {
		$this->openId = WxBrandUser::openId($this->userId,$this->brandId);
	}
	
	/**
	 * 拉取用户信息
	 */
	public function curl() {
		$url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->accessToken.'&openid='.$this->openId.'&lang=zh_CN';
		$this->response = json_decode(Curl::https($url));
	}
	
	/**
	 * 存储数据
	 * 更改yk_brand_user表
	 */
	public function update() {
		if(!empty($this->response->nickname)) {
			//更改yk_brand_user表
			$updateArr = array(
				'nickname'=>$this->response->nickname,
				'head_icon'=>$this->response->headimgurl,
				'sex'=>$this->response->sex,
				'country'=>$this->response->country,
				'province'=>$this->response->province,
				'city'=>$this->response->city,
				'is_sync'=>DataSync::getInitSync(),
			);
			Yii::app()->db->createCommand()->update('nb_brand_user', $updateArr, 'lid='.$this->userId.' and dpid='.$this->brandId);
			
			$this->result = 1;
		}
	}
	
	
}
 
 
?>