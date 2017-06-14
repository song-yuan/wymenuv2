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
		$sql = 'select sum(remain_points) as total from nb_member_points where card_type=1 and card_id=:userId and end_time >= "'.$date.'" and remain_points > 0 and delete_flag=0';
		$point = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
				  ->queryRow();
	    return $point['total']?$point['total']:0;
	}
        /**
	 * 
	 * 获取所有积分记录
	 * 
	 */
	public static function getPoints($userId,$dpid){
		$date = date('Y-m-d H:i:s',time());
		$sql = 'select create_at,points,remain_points,end_time from nb_member_points where card_type=1 and card_id=:userId and delete_flag=0';
		$point = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
				  ->bindValue(':dpid',$dpid)
				  ->queryAll();
                 return $point;
	}
        
        
	/**
	 * 
	 * 处理积分
	 * 
	 */
	public static function dealPoints($userId,$dpid,$point){
		$total = 0;
		$date = date('Y-m-d H:i:s',time());
		$sql = 'select * from nb_member_points where card_type=1 and card_id=:userId and dpid=:dpid and end_time >= "'.$date.'" and delete_flag=0 order by end_time asc';
		$points = Yii::app()->db->createCommand($sql)
				->bindValue(':userId',$userId)
				->bindValue(':dpid',$dpid)
				->queryAll();
		foreach ($points as $pObj){
			$total += $pObj['remain_points'];
			if($total > $point){
				$sql = 'update nb_member_points set remain_points='.($total-$point).' where lid='.$pObj['lid'].' and dpid='.$pObj['dpid'];
				$res = Yii::app()->db->createCommand($sql)->execute();
			}else{
				$sql = 'update nb_member_points set remain_points=0 where lid='.$pObj['lid'].' and dpid='.$pObj['dpid'];
				$res = Yii::app()->db->createCommand($sql)->execute();
			}
		}
		return $res;
	}
}