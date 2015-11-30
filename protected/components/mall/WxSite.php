<?php 
/**
 * 
 * 
 * 微信端餐桌类
 * //堂吃必须有siteId
 * productArr = array('product_id'=>1,'num'=>1,'privation_promotion_id'=>-1)
 * 
 */
class WxSite
{
	public static function get($siteId,$dpid){
		$sql = 'select * from nb_site where lid=:lid and dpid=:dpid and delete_flag=0';
		$site = Yii::app()->db->createCommand($sql)
				  ->bindValue(':lid',$siteId)
				  ->bindValue(':dpid',$dpid)
				  ->queryRow();
	    return $site;
	}
	public static function getBySerial($searil,$dpid){
		$sql = 'select * from nb_site where serial=:serial and dpid=:dpid and delete_flag=0';
		$site = Yii::app()->db->createCommand($sql)
				  ->bindValue(':serial',$searil)
				  ->bindValue(':dpid',$dpid)
				  ->queryRow();
	    return $site;
	}
}