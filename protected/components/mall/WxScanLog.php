<?php 
/**
 * 
 * 
 * 微信端是否扫描二维码类
 * 
 */
class WxScanLog
{
	public static function get($dpid,$userId){
		$sql = 'select * from nb_scene_scan_log where dpid=:dpid and user_id=:userId and delete_flag=0 order by lid desc';
		$categorys = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->bindValue(':userId',$userId)->queryRow();
		return $categorys;
	}
	public static function getScene($dpid,$sceneId){
		$sql = 'select * from nb_scene where dpid=:dpid and scene_id=:sceneId order by lid desc';
		$categorys = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->bindValue(':sceneId',$sceneId)->queryRow();
		return $categorys;
	}
	/**
	 * 
	 * 结单后场景无效
	 * 
	 */
 	public static function invalidScene($dpid,$siteId){
 		$siteNo = WxSite::getSiteNoByLid($siteId, $dpid);
		$sql = 'update nb_scene_scan_log set delete_flag=1 where scene_id=(select scene_id from nb_scene where type=1 and scene_lid='.$siteNo['site_id'].' and scene_dpid='.$dpid.')';
		Yii::app ()->db->createCommand ($sql)->execute();
 	}
}