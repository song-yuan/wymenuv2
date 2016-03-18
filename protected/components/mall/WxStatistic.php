<?php 
/**
 * 
 * 
 * 店铺信息统计
 * 
 * 
 */
class WxStatistic
{
	public static function getStatisticByOrderType($dpid,$start,$end){
		$sql = 'select t.order_type, sum(t1.pay_amount) as total from nb_order t right join nb_order_pay t1 on t.lid = t1.order_id and t.dpid=t1.dpid where t.dpid=:dpid and t.order_status in (3,4) and  t.create_at > :start and t.create_at < :end group by order_type';
		$statistic = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':start',$start)
				  ->bindValue(':end',$end)
				  ->queryAll();
	    return $statistic;
	}
	public static function getStatisticByOrderPayType($dpid,$start,$end){
		$sql = 'select t.paytype, sum(t1.pay_amount) as total from nb_order t right join nb_order_pay t1 on t.lid = t1.order_id and t.dpid=t1.dpid where t.dpid=:dpid and t.order_status in (3,4) and  t.create_at > :start and t.create_at < :end group by t.paytype';
		$statistic = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':start',$start)
				  ->bindValue(':end',$end)
				  ->queryAll();
	    return $statistic;
	}
}