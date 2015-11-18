<?php
/**
 * BrandUser.php
 *
 */
 
class WxBrandUser {
	
	/**
	 * 返回brandUser数组
	 */
	public static function get($userId,$dpid) {
		$sql = 'SELECT * FROM nb_brand_user WHERE lid = ' .$userId .' and dpid = '.$dpid;
		$brandUser = Yii::app()->db->createCommand($sql)->queryRow();
		if(!$brandUser)
			throw new Exception('不存在该会员信息');
		return $brandUser;
	}
	
	/**
	 * 返回对应的openId 
	 */
	public static function openId($userId,$dpid) {
		$brandUser = self:: get($userId,$dpid);
		return $brandUser['openid'];
	}
}

 
?>