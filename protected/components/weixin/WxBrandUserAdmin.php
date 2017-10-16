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
		$sql = 'SELECT t1.* FROM nb_brand_user_admin t,nb_company t1 WHERE t.admin_dpid=t1.dpid and t.brand_user_id = ' .$userId .' and t.dpid = '.$dpid.' and t.delete_flag = 0 and t1.delete_flag=0';
		$brandUserAdmin = Yii::app()->db->createCommand($sql)->queryAll();
		return $brandUserAdmin;
	}
}

 
?>