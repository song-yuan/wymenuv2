<?php 
/**
 * 
 * 
 * 门店管理员类
 * 
 * 
 */
class WxAdminUser
{
	public static function get($dpid,$userId){
		$sql = 'select * from nb_user where lid='.$userId.' and dpid='.$dpid.' and delete_flag=0';
		$user = Yii::app()->db->createCommand($sql)->queryRow();
	    return $user;
	}
}