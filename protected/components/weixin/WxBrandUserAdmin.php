<?php
/**
 * 
 * BrandUserAdmin.php
 *
 */
 
class WxBrandUserAdmin {
	/**
	 * 返回brandUser数组
	 */
	public static function get($userId,$dpid) {
		$sql = 'SELECT * FROM nb_brand_user_admin WHERE brand_user_id = ' .$userId .' and dpid = '.$dpid.' and delete_flag = 0';
		$brandUserAdmin = Yii::app()->db->createCommand($sql)->queryAll();
		return $brandUserAdmin;
	}
}

 
?>