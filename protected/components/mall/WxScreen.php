<?php 
/**
 * 
 * 
 * 视频类
 * 
 * 
 */
class WxScreen
{
	public static function get($dpid){
		$sql = 'select * from nb_screen where dpid=:dpid and delete_flag=0 order by lid desc';
		$screens = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->queryAll();
	    return $screens;
	}
	public static function getScreen($dpid,$screenId){
		$sql = 'select * from nb_screen where lid=:lid and dpid=:dpid and delete_flag=0';
		$screen = Yii::app()->db->createCommand($sql)
				  ->bindValue(':lid',$screenId)
				  ->bindValue(':dpid',$dpid)
				  ->queryRow();
	    return $screen;
	}
}