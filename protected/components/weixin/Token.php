<?php
/**
 * Token.php
 *
 */
 
class Token {
	
	/**
	 * 获取品牌的token
	 */
	public static function get($brandId) {
		$sql = 'select * from nb_weixin_service_account where dpid = '.$this->brandId;
		$weixinServiceAccount = Yii::app()->db->createCommand($sql)->queryRow();
		if(empty($weixinServiceAccount['token']))
			throw new Exception('未设置该品牌的token');
		return $weixinServiceAccount['token'];
	}
}
 
?>