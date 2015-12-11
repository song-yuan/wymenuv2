<?php 
/**
 * 
 * 
 * 微信端活动设置类
 * 
 */
class WxTotalPromotion
{
	public static function get($dpid){
		$sql = 'select * from nb_total_promotion where dpid=:dpid and delete_flag=0';
		$totalPromotion = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->queryRow();
		return $totalPromotion;
	}
}