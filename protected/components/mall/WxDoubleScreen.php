<?php 
/**
 * 
 * 
 * 副屏类
 * 
 * 
 */
class WxDoubleScreen
{
	public static function get($dpid){
		$sql = 'select * from nb_double_screen_detail ds,nb_double_screen d where ds.double_screen_id=d.lid and ds.dpid=d.dpid and ds.dpid='.$dpid.' and d.is_able=2 and ds.delete_flag=0 and d.delete_flag=0';
		$doubles = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->queryAll();
	    return $doubles;
	}
}