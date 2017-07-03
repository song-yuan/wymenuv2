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
	public static function getSiteNo($siteId,$dpid){
		$sql = 'select * from nb_site_no where site_id=:siteId and dpid=:dpid and is_temp=0 and delete_flag=0 order by lid desc limit 1';
		$siteNo = Yii::app()->db->createCommand($sql)
				->bindValue(':siteId',$siteId)
				->bindValue(':dpid',$dpid)
				->queryRow();
		return $siteNo;
	}
	/**
	 * 
	 * 查找未开台的外卖台
	 * 
	 */
	public static function getTakeOut($dpid){
		$sql = 'select * from nb_site where dpid=:dpid and site_channel_lid > 0 and status not in (1,2,3) and delete_flag=0';
		$site = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->queryRow();
	    return $site;
	}
	public static function getSiteType($siteTypeId,$dpid){
		$sql = 'select * from nb_site_type where lid=:lid and dpid=:dpid and delete_flag=0';
		$siteType = Yii::app()->db->createCommand($sql)
				  ->bindValue(':lid',$siteTypeId)
				  ->bindValue(':dpid',$dpid)
				  ->queryRow();
	    return $siteType;
	}
	public static function getSiteNumber($spLid,$dpid){
		$sql = 'select * from nb_site_persons where lid=:lid and dpid=:dpid and delete_flag=0';
		$siteNum = Yii::app()->db->createCommand($sql)
				  ->bindValue(':lid',$spLid)
				  ->bindValue(':dpid',$dpid)
				  ->queryRow();
	    return $siteNum;
	}
	public static function getBySerial($searil,$dpid){
		$sql = 'select * from nb_site where serial=:serial and dpid=:dpid and delete_flag=0';
		$site = Yii::app()->db->createCommand($sql)
				  ->bindValue(':serial',$searil)
				  ->bindValue(':dpid',$dpid)
				  ->queryRow();
	    return $site;
	}
	/**
	 * 
	 * 更改固定台状态
	 * 
	 */
	public static function updateSiteStatus($siteId,$dpid,$status){
		$isSync = DataSync::getInitSync();
		$sql = 'update nb_site set status='.$status.',is_sync='.$isSync.' where lid='.$siteId.' and dpid='.$dpid;
		Yii::app()->db->createCommand($sql)->execute();
	}
	/**
	 * 
	 * 更改临时台状态
	 * 
	 */
	public static function updateTempSiteStatus($siteId,$dpid,$status){
		$isSync = DataSync::getInitSync();
		$sql = 'update nb_site_no set status='.$status.',is_sync='.$isSync.' where site_id='.$siteId.' and dpid='.$dpid.' and is_temp=1';
		Yii::app()->db->createCommand($sql)->execute();
	}
}