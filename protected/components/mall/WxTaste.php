<?php 
/**
 * 
 * 
 * 获取单品口味
 * 
 * 
 */
class WxTaste
{
	/**
	 * 
	 * 获取全单的口味
	 * 
	 */
	public static function getOrderTastes($dpid){
		$sql = 'select lid as taste_group_id,name from nb_taste_group where dpid=:dpid and allflae=1 and delete_flag=0';
		$tasteGroups = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->queryAll();
		foreach($tasteGroups as $k=>$group){
			$tastes = self::getTastes($group['taste_group_id'],$dpid,1);
			$tasteGroups[$k]['tastes'] = $tastes;
		}
	    return $tasteGroups;		  
	}
	/**
	 * 
	 * 获取单品的口味
	 * 
	 */
	public static function getProductTastes($productId,$dpid){
		$sql = 'select t.taste_group_id,t.product_id,t1.name,t1.allflae from nb_product_taste t,nb_taste_group t1 where t.taste_group_id=t1.lid and t.dpid=t1.dpid and t.product_id=:productId and t.dpid=:dpid and t1.allflae=0 and t1.delete_flag=0 and t.delete_flag=0';
		$sql .=' union select lid as taste_group_id,0 as product_id,name,allflae from nb_taste_group where dpid=:dpid and allflae=1 and delete_flag=0';
		$tasteGroups = Yii::app()->db->createCommand($sql)
				  ->bindValue(':productId',$productId)
				  ->bindValue(':dpid',$dpid)
				  ->queryAll();
		foreach($tasteGroups as $k=>$group){
			$tastes = self::getTastes($group['taste_group_id'],$dpid,$group['allflae']);
			$tasteGroups[$k]['tastes'] = $tastes;
		}
	    return $tasteGroups;
	}
	/**
	 * 
	 * 获取口味 组里的口味
	 * 
	 */
	public static function getTastes($tasteGroupId,$dpid,$allflae = 0){
		$sql = 'select lid,name,price from nb_taste where taste_group_id=:tasteGroupId and dpid=:dpid and allflae=:allflae and delete_flag=0';
		$tastes = Yii::app()->db->createCommand($sql)
				  ->bindValue(':tasteGroupId',$tasteGroupId)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':allflae',$allflae)
				  ->queryAll();
		return $tastes;
	}
	
}