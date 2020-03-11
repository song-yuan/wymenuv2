<?php
/**
 * AccessToken.php
 * access_token是公众号的全局唯一票据，公众号调用各接口时都需使用access_token。
 * 正常情况下access_token有效期为7200秒，重复获取将导致上次获取的access_token失效。
 * 
 * @property Integer $brandId 品牌主键
 * @property String $accessToen
 */

class AccessToken {
	public $brandId;
	public $accessToken;
	
	/**
	 * 初始化
	 */
	public function __construct($brandId) {
		$this->brandId = $brandId;
		$this->weixinServiceAccount();
		$this->accessToken();
		$this->jsTicket();
	}
	
	/**
	 * 获取服务号的appid和appsecret
	 */
	public function weixinServiceAccount() {	
		$this->weixinServiceAccount = WxAccount::get($this->brandId);
		if(!$this->weixinServiceAccount){
			throw new Exception('请先填写公众号信息!');
		}
	}
	
	/**
	 * 获取$this->accessToken
	 * 微信access_token两小时过期
	 */
	public function accessToken() {
		if(time() > $this->weixinServiceAccount['expire'])
			$this->accessToken = $this->newAccessToken();
		else
			$this->accessToken = $this->weixinServiceAccount['access_token'];
	}
	
	/**
	 * 存在accessToken没到2个小时就过期的情况
	 * 因此需要重新获取accessToken
	 */
	public function newAccessToken() {
		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' .trim($this->weixinServiceAccount['appid']). '&secret=' .trim($this->weixinServiceAccount['appsecret']);
		if($weixinServerReturn = Curl::https($url)) {
			$accessTokenStdClass = json_decode($weixinServerReturn);
			if(isset($accessTokenStdClass->access_token)){
				$sql = 'UPDATE nb_weixin_service_account set expire = ' . strtotime('2 hours'). ', access_token = "' .$accessTokenStdClass->access_token . '" WHERE dpid = ' .$this->brandId;
				Yii::app()->db->createCommand($sql)->execute();
				return $accessTokenStdClass->access_token;
			}else{
				throw new Exception('请检查appid及appsecret是否填写正确!error:'.$weixinServerReturn);
			}
		}else
			throw new Exception('无法从微信服务器获取access_token的信息');
	}
	/**
	 * 获取$this->jsTicket
	 * 微信ticket两小时过期
	 */
	public function jsTicket() {
		if(time() > $this->weixinServiceAccount['ticket_expire'])
			$this->jsTicket = $this->newTicket();
		else
			$this->jsTicket = $this->weixinServiceAccount['ticket'];
	}
	
	/**
	 * 存在ticket没到2个小时就过期的情况
	 * 因此需要重新获取ticket
	 */
	public function newTicket() {
		$url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token='.$this->accessToken;
		if($weixinServerReturn = Curl::https($url)) {
			$ticketStdClass = json_decode($weixinServerReturn);
			$isSync = DataSync::getInitSync();
			$sql = 'UPDATE nb_weixin_service_account set ticket_expire = ' . strtotime('2 hours'). ', ticket = "' .$ticketStdClass->ticket . '",is_sync='.$isSync.' 
					WHERE dpid = ' .$this->brandId;
			Yii::app()->db->createCommand($sql)->execute();
			return $ticketStdClass->ticket;
		}else
			throw new Exception('无法从微信服务器获取ticket的信息');
	}
	/**
	 * 微信卡券 jsticket
	 * 
	 */
	public function jsCardTicket() {
		$sql = 'select * from nb_weixin_card_ticket where dpid='.$this->brandId;
		$result = Yii::app()->db->createCommand($sql)->queryRow();
		if($result){
			if(time() > $result['expire_time'])
				$jsCardTicket = $this->newCardTicket();
		    else
				$jsCardTicket = $result['ticket'];
		}else{
			$jsCardTicket = $this->newCardTicket();
		}
		return $jsCardTicket;
	}
	public function newCardTicket() {
		$url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$this->accessToken.'&type=wx_card';
		if($weixinServerReturn = Curl::https($url)) {
			$ticketStdClass = json_decode($weixinServerReturn);
			$sql = 'select * from nb_weixin_card_ticket where dpid='.$this->brandId;
			$result = Yii::app()->db->createCommand($sql)->queryRow();
			if($result){
				$isSync = DataSync::getInitSync();
				$sql = 'UPDATE nb_weixin_card_ticket set expire_time = ' . strtotime('2 hours'). ', ticket = "' .$ticketStdClass->ticket . '",is_sync='.$isSync.' 
					WHERE dpid = ' .$this->brandId;
				Yii::app()->db->createCommand($sql)->execute();
			}else{
				$time = time();
				$se = new Sequence("weixin_card_ticket");
			    $lid = $se->nextval();
				$data = array(
							'lid'=>$lid,
							'dpid'=>$this->brandId,
							'create_at'=>date('Y-m-d H:i:s',$time),
		        			'update_at'=>date('Y-m-d H:i:s',$time), 
							'ticket'=>$ticketStdClass->ticket,
							'expire_time'=>strtotime('2 hours'),
							'is_sync'=>DataSync::getInitSync(),	
							);
				Yii::app()->db->createCommand($sql)->insert('nb_weixin_card_ticket',$data);
			}
			return $ticketStdClass->ticket;
		}else
			throw new Exception('无法从微信服务器获取ticket的信息');
	}
	
}

?>