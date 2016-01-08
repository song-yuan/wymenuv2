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
	 * 返回brandUser数组
	 */
	public static function getUserLevel($userLevelId,$dpid) {
		$sql = 'SELECT * FROM nb_brand_user_level WHERE lid = ' .$userLevelId .' and dpid = '.$dpid.' and delete_flag=0';
		$brandUserLevel = Yii::app()->db->createCommand($sql)->queryRow();
		return $brandUserLevel;
	}
	/**
	 * 返回对应的openId 
	 */
	public static function openId($userId,$dpid) {
		$brandUser = self:: get($userId,$dpid);
		return $brandUser['openid'];
	}
	/**
	 * 通过openid查找用户
	 * 
	 */
	public static function getFromOpenId($openId) {
		$sql = 'select * from nb_brand_user where openid = "'.$openId.'"';
		$brandUser = Yii::app()->db->createCommand($sql)->queryRow();
		return $brandUser;
	}
	/**
	 * 
	 * 获取会员的历史积分
	 * 
	 */
	public static function getHistoryPoints($userId,$dpid) {
		$sql = 'select sum(point_num) as total from nb_point_record where brand_user_lid = '.$userId.' and dpid='.$dpid;
		$points = Yii::app()->db->createCommand($sql)->queryRow();
		return $points['total']?$points['total']:0;
	}
	/**
	 * 
	 * 获取会员的可用积分
	 * 
	 */
	public static function getAvaliablePoints($userId,$dpid) {
		$now = date('Y-m-d H:i:s',time());
		$sql = 'select sum(point_num) as total from nb_point_record where brand_user_lid = '.$userId.' and dpid='.$dpid.' and end_time > "'.$now.'"';
		$points = Yii::app()->db->createCommand($sql)->queryRow();
		return $points['total']?$points['total']:0;
	}
}

 
?>