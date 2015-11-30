<?php 
/**
 * 
 * 
 * 微信端会员地址类
 * 
 * 
 */
class WxAddress
{
	public static function get($userId,$dpid){
		$sql = 'select * from nb_address where brand_user_lid=:userId and dpid=:dpid and delete_flag=0';
		$site = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
				  ->bindValue(':dpid',$dpid)
				  ->queryAll();
	    return $site;
	}
}