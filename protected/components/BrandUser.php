<?php
/**
 * BrandUser.php
 *
 */
 
class BrandUser {
	
	/**
	 * 返回brandUser数组
	 */
	public static function get($userId) {
		$sql = 'SELECT * FROM nb_brand_user
				WHERE id = ' .$userId;
		$brandUser = Yii::app()->db->createCommand($sql)->queryRow();
		if(!$brandUser)
			throw new Exception('不存在该会员信息');
		return $brandUser;
	}
	
	/**
	 * 返回对应的openId 
	 */
	public static function openId($userId) {
		$brandUser = self:: get($userId);
		return $brandUser['openid'];
	}
}

 
?>