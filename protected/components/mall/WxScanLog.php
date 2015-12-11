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
		$time = date('Y-m-d H:i:s',time()-5*60*60);
		$sql = 'select * from nb_scene_scan_log where dpid=:dpid and user_id=:userId and create_at > :time and delete_flag=0 order by lid desc';
		$categorys = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->bindValue(':userId',$userId)->bindValue(':time',$time)->queryRow();
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
 		$sql = 'select * from nb_scene where dpid=:dpid and id=:siteId and type=1';
 		$scene = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->bindValue(':siteId',$siteId)->queryRow();
		if($scene){
			$sql = 'update nb_scene_scan_log set delete_flag=1 where dpid='.$dpid.' and scene_id='.$scene['scene_id'].' and delete_flag=0';
			Yii::app()->db->createCommand($sql)->execute();
		}
 	}
}