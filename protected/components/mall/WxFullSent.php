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
	public static function getAllFullsent($dpid,$orderType,$type){
		$fullsentArr = array();
		$time = date('Y-m-d H:i:s',time());
		$orderType = -1;
		if($orderType=='6'){
			$orderType = 2;// 微信堂食
		}elseif($orderType=='2'){
			$orderType = 3;// 微信外卖
		}
		$sql = 'select * from nb_full_sent where dpid='.$dpid.' and full_type='.$type.' and begin_time < "'.$time.'" and end_time > "'.$time.'" and is_available like "%'.$orderType.'%" and delete_flag=0 order by full_cost asc';
		$fullsent = Yii::app()->db->createCommand($sql)->queryAll();
		if($type==0){
			foreach ($fullsent as $key=>$sent){
				$sql = 'select t.*,t1.product_name,t1.original_price,t1.member_price from nb_full_sent_detail t,nb_product t1 where t.dpid=t1.dpid and t.product_id=t1.lid and t.dpid='.$dpid.' and t.full_sent_id='.$sent['lid'].' and t.delete_flag=0 and t1.is_show=1 and t1.is_show_wx=1 and t1.delete_flag=0';
				$fullsentDetail = Yii::app()->db->createCommand($sql)->queryAll();
				if(empty($fullsentDetail)){
					unset($fullsent[$key]);
					continue;
				}
				$fullsent[$key]['sent_product'] = $fullsentDetail;
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
	/**
	 *
	 *
	 * 检查活动是否生效
	 *
	 */
	public static function checkFullsentproduct($fullsentdetailId,$fullsentId,$dpid){
		$sql = 'select t.*,t1.product_name,t1.original_price,t1.member_price from nb_full_sent_detail t,nb_product t1 where t.dpid=t1.dpid and t.product_id=t1.lid and t.lid='.$fullsentdetailId.' and t.dpid='.$dpid.' and t.full_sent_id='.$fullsentId.' and t.delete_flag=0 and t1.is_show=1 and t1.is_show_wx=1 and t1.delete_flag=0';
		$fullsentDetail = Yii::app()->db->createCommand($sql)->queryRow();
		return $fullsent;
	}
	/**
	 *
	 * 满减满送活动列表 满足条件 金额 从小到大
	 * $type 0 满送 1 满减
	 * $dpid 
	 * price 订单价格
	 *
	 */
	public static function getFullsentActive($dpid,$price,$orderType,$type){
		$fullsentActive = array();
		if($type==0){
			// 获取满送活动
			$fullSents = self::getAllFullsent($dpid, $orderType, 0); 
			if(!empty($fullSents)){
				foreach ($fullSents as $sent){
					$fullcost = $sent['full_cost'];
					if($price <= $fullcost){
						break;
					}
					$fullsentActive = $sent;
				}
			}
		}else{
			// 获取满减活动
			$fullminus = self::getAllFullsent($dpid, $orderType, 1);
			if(!empty($fullminus)){
				foreach ($fullminus as $minus){
					$fullcost = $minus['full_cost'];
					if($price <= $fullcost){
						break;
					}
					$fullsentActive = $minus;
				}
			}
		}
		return $fullsentActive;
	}
}