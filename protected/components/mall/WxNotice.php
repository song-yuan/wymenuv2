<?php 
/**
 * 
 * 
 * 获取单品口味
 * 
 * 
 */
class WxNotice
{
	/**
	 * 
	 * 获取全单的口味
	 * 
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