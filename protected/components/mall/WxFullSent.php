<?php 
/**
 * 
 * 
 * 微信端满减  满送
 *
 * 
 * 
 */
class WxFullSent
{
	/**
	 * 
	 * 获取所有有效期内的 满送活动
	 * 0 满送 1满减
	 * 
	 */
	public static function getAllFullsent($dpid,$type){
		$time = date('Y-m-d H:i:s',time());
		$sql = 'select * from nb_full_sent where dpid=:dpid and full_type=:full_type and begin_time < "'.$time.'" and end_time > "'.$time.'" and delete_flag=0 order by full_cost asc';
		$fullsent = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':full_type',$type)
				  ->queryAll();
		if($type==0){
			foreach ($fullsent as $key=>$sent){
				$sql = 'select t.* from nb_full_sent_detail t,nb_product t1 where t.dpid=t1.dpid and t.product_id=t1.lid and t.dpid=:dpid and t.full_sent_id=:fullsentId and t.delete_flag=0 and t1.is_show=1 and t1.is_show_wx=1 and t1.delete_flag=0';
				$fullsentDetail = Yii::app()->db->createCommand($sql)
					->bindValue(':dpid',$dpid)
					->bindValue(':fullsentId',$sent['lid'])
					->queryAll();
				if(empty($fullsentDetail)){
					unset($fullsent[$key]);
					continue;
				}
				$fullsent['sent_product'] = $fullsentDetail;
			}
		}
	    return array_merge($fullsent);
	}
	/**
	 * 
	 * 
	 * 检查活动是否生效
	 * 
	 */
	public static function checkFullsent($fullsentId,$dpid){
		$sql = 'select * from nb_full_sent where lid=:lid and dpid=:dpid and delete_flag=0';
		$fullsent = Yii::app()->db->createCommand($sql)
					->bindValue(':lid',$fullsentId)
					->bindValue(':dpid',$dpid)
					->queryRow();
		return $fullsent;
	}
}