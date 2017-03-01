<?php 
/**
 * 
 * 
 * 微信积分类
 *
 * 
 * 
 */
class WxPoints
{
	/**
	 * 
	 * 获取会员所有可用
	 * 
	 */
	public static function getAvaliablePoints($userId,$dpid){
		$date = date('Y-m-d H:i:s',time());
		$sql = 'select sum(point_num) as total from nb_point_record where brand_user_lid=:userId and dpid=:dpid and end_time >= '.$date.' and delete_flag=0';
		$point = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
				  ->bindValue(':dpid',$dpid)
				  ->queryRow();
	    return $point['total']?$point['total']:0;
	}
	
}