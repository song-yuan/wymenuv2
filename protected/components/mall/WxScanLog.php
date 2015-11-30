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
		$time = date('Y-m-d H:i:s',time()-2*60*60);
		$sql = 'select * from nb_scene_scan_log where dpid=:dpid and user_id=:userId and create_at > :time order by lid desc';
		$categorys = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->bindValue(':userId',$userId)->bindValue(':time',$time)->queryRow();
		return $categorys;
	}
	public static function getScene($dpid,$sceneId){
		$sql = 'select * from nb_scene where dpid=:dpid and scene_id=:sceneId order by lid desc';
		$categorys = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->bindValue(':sceneId',$sceneId)->queryRow();
		return $categorys;
	}
}