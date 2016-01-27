<?php 
/**
 * 
 * 
 * 微信端代金券类
 *
 * 
 * 
 */
class WxCupon
{
	public static function getUserAllCupon($userId,$dpid){
		$user = WxBrandUser::get($userId,$dpid);
		$sql = 'select m.lid,m.is_used,n.cupon_title,n.main_picture,n.min_consumer,n.cupon_money,n.begin_time,n.end_time from (select * from nb_cupon_branduser where dpid=:dpid and to_group=3 and brand_user_lid=:userId and is_used > 0 and delete_flag=0' .
				' union select * from nb_cupon_branduser where dpid=:dpid and to_group=2 and brand_user_lid=:userLevelId and is_used > 0 and delete_flag=0)m ,nb_cupon n' .
				' where m.cupon_id=n.lid and m.dpid=n.dpid and n.delete_flag=0';
		$cupon = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':userLevelId',$user['user_level_lid'])
				  ->queryAll();
	    return $cupon;
	}
	/**
	 * 
	 * 未使用
	 * 
	 */
	public static function getUserNotUseCupon($userId,$dpid){
		$now = date('Y-m-d H:i:s',time());
		$user = WxBrandUser::get($userId,$dpid);
		$sql = 'select m.lid,m.is_used,n.cupon_title,n.main_picture,n.min_consumer,n.cupon_money,n.begin_time,n.end_time from (select * from nb_cupon_branduser where dpid=:dpid and to_group=3 and brand_user_lid=:userId and is_used = 1 and delete_flag=0' .
				' union select * from nb_cupon_branduser where dpid=:dpid and to_group=2 and brand_user_lid=:userLevelId and is_used = 1 and delete_flag=0)m ,nb_cupon n' .
				' where m.cupon_id=n.lid and m.dpid=n.dpid and n.begin_time <=:now and :now <= n.end_time and n.delete_flag=0';
		$cupon = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':now',$now)
				  ->bindValue(':userLevelId',$user['user_level_lid'])
				  ->queryAll();
	    return $cupon;
	}
	/**
	 * 
	 * 已使用
	 * 
	 */
	public static function getUserUseCupon($userId,$dpid){
		$user = WxBrandUser::get($userId,$dpid);
		$sql = 'select m.lid,m.is_used,n.cupon_title,n.main_picture,n.min_consumer,n.cupon_money,n.begin_time,n.end_time from (select * from nb_cupon_branduser where dpid=:dpid and to_group=3 and brand_user_lid=:userId and is_used =2 and delete_flag=0' .
				' union select * from nb_cupon_branduser where dpid=:dpid and to_group=2 and brand_user_lid=:userLevelId and is_used = 2 and delete_flag=0)m ,nb_cupon n' .
				' where m.cupon_id=n.lid and m.dpid=n.dpid and n.delete_flag=0';
		$cupon = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':userLevelId',$user['user_level_lid'])
				  ->queryAll();
	    return $cupon;
	}
	/**
	 * 
	 * 已过期
	 * 
	 */
	 public static function getUserExpireCupon($userId,$dpid){
	 	$now = date('Y-m-d H:i:s',time());
		$user = WxBrandUser::get($userId,$dpid);
		$sql = 'select m.lid,m.is_used,n.cupon_title,n.main_picture,n.min_consumer,n.cupon_money,n.begin_time,n.end_time from (select * from nb_cupon_branduser where dpid=:dpid and to_group=3 and brand_user_lid=:userId and is_used > 0 and delete_flag=0' .
				' union select * from nb_cupon_branduser where dpid=:dpid and to_group=2 and brand_user_lid=:userLevelId and is_used > 0 and delete_flag=0)m ,nb_cupon n' .
				' where m.cupon_id=n.lid and m.dpid=n.dpid and :now > n.end_time and n.delete_flag=0';
		$cupon = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':now',$now)
				  ->bindValue(':userLevelId',$user['user_level_lid'])
				  ->queryAll();
	    return $cupon;
	}
	public static function getUserAvaliableCupon($total,$userId,$dpid){
		$isCanUse = true;
		$set = WxTotalPromotion::get($dpid);
		
		if($set){
			$orders = WxOrder::getOrderUseCupon($userId,$dpid);
			if($set['is_cupon'] >= 0 && count($orders) >= $set['is_cupon']){
				$isCanUse = false;
			}
		}
		if($isCanUse){
			$now = date('Y-m-d H:i:s',time());
			$user = WxBrandUser::get($userId,$dpid);
			$sql = 'select m.lid,n.cupon_title,n.main_picture,n.min_consumer,n.cupon_money from (select * from nb_cupon_branduser where dpid=:dpid and to_group=3 and brand_user_lid=:userId and is_used=1 and delete_flag=0' .
					' union select * from nb_cupon_branduser where dpid=:dpid and to_group=2 and brand_user_lid=:userLevelId and is_used=1 and delete_flag=0)m ,nb_cupon n' .
					' where m.cupon_id=n.lid and m.dpid=n.dpid and n.begin_time <=:now and :now <=n.end_time and n.min_consumer <=:total and n.delete_flag=0 and n.is_available=0';
			$cupon = Yii::app()->db->createCommand($sql)
					  ->bindValue(':userId',$userId)
					  ->bindValue(':dpid',$dpid)
					  ->bindValue(':userLevelId',$user['user_level_lid'])
					  ->bindValue(':now',$now)
					  ->bindValue(':total',$total)
					  ->queryAll();
		}else{
			$cupon = array();
		}
	    return $cupon;
	}
	public static function getCuponList($dpid){
		$now = date('Y-m-d H:i:s',time());
		$sql = 'select * from nb_cupon where dpid=:dpid and begin_time <=:now and :now <= end_time and delete_flag=0';
		$cupon = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':now',$now)
				  ->queryAll();
	    return $cupon;
	}
	public static function getCupon($dpid,$cuponId){
		$now = date('Y-m-d H:i:s',time());
		$sql = 'select * from nb_cupon where lid=:lid and dpid=:dpid and begin_time <=:now and :now <= end_time and delete_flag=0';
		$cupon = Yii::app()->db->createCommand($sql)
				  ->bindValue(':lid',$cuponId)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':now',$now)
				  ->queryRow();
	    return $cupon;
	}
}