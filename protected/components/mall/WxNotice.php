<?php 
/**
 * 公告
 */
class WxNotice
{
	/**
	 * 获取公告
	 */
	public static function getNotice($dpid,$type,$userType){
		$sql = 'select * from nb_announcement where dpid=:dpid and type=:type and use_type=:useType and delete_flag=0';
		$notices = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':type',$type)
				  ->bindValue(':useType',$userType)
				  ->queryAll();
	    return $notices;		  
	}
}