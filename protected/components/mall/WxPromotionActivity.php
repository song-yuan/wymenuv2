<?php 
/**
 * 
 * 
 * 微信端代金券类
 *
 * 
 * 
 */
class WxPromotionActivity
{
	public static function get($dpid){
		$now = date('Y-m-d H:i:s',time());
		$sql = 'select * from nb_promotion_activity where dpid=:dpid and begin_time <=:now and :now <= end_time and delete_flag=0';
		$cupon = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':now',$now)
				  ->queryAll();
	    return $cupon;
	}
	public static function getActivity($dpid,$activityId){
		$now = date('Y-m-d H:i:s',time());
		$sql = 'select * from nb_promotion_activity where dpid=:dpid and lid=:activityId and begin_time <=:now and :now <= end_time and delete_flag=0';
		$activity = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':activityId',$activityId)
				  ->bindValue(':now',$now)
				  ->queryRow();
		return $activity;
	}
	/**
	 * 
	 * 获取营销活动 详情
	 * 
	 */
	public static function getDetail($dpid,$activityId){
		$now = date('Y-m-d H:i:s',time());
		$sql = 'select * from nb_promotion_activity_detail where dpid=:dpid and activity_lid=:activityId and promotion_type > 0 and delete_flag=0';
		$activitys = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':activityId',$activityId)
				  ->queryAll();
		foreach($activitys as $k=>$activity){
			if($activity['promotion_type']==1){
				$promotion = WxPromotion::getPromotion($dpid,$activity['promotion_lid']);
				if($promotion){
					if($promotion['to_group']==2){
						unset($activitys[$k]);
					}else{
						$activitys[$k]['title'] = isset($promotion['promotion_title'])?$promotion['promotion_title']:'';
						$activitys[$k]['begin_time'] = isset($promotion['begin_time'])?$promotion['begin_time']:'';
						$activitys[$k]['end_time'] = isset($promotion['end_time'])?$promotion['end_time']:'';
					}
				}else{
					unset($activitys[$k]);
				}
			}elseif($activity['promotion_type']==2){
				$promotion = WxCupon::getCupon($dpid,$activity['promotion_lid']);
				if($promotion){
					if($promotion['to_group']==2){
						unset($activitys[$k]);
					}else{
						$activitys[$k]['title'] = isset($promotion['cupon_title'])?$promotion['cupon_title']:'';
						$activitys[$k]['min_consumer'] = isset($promotion['min_consumer'])?$promotion['min_consumer']:'';
						$activitys[$k]['begin_time'] = isset($promotion['begin_time'])?$promotion['begin_time']:'';
						$activitys[$k]['end_time'] = isset($promotion['end_time'])?$promotion['end_time']:'';
					}
				}else{
					unset($activitys[$k]);
				}
			}elseif($activity['promotion_type']==3){
				$promotion = WxGiftCard::getGift($dpid,$activity['promotion_lid']);
				if($promotion){
						$activitys[$k]['title'] = isset($promotion['title'])?$promotion['title']:'';
						$activitys[$k]['begin_time'] = isset($promotion['begin_time'])?$promotion['begin_time']:'';
						$activitys[$k]['end_time'] = isset($promotion['end_time'])?$promotion['end_time']:'';
				}else{
					unset($activitys[$k]);
				}
			}
		}
	    return $activitys;
	}
	/**
	 * 
	 * 获取营销活动相
	 * 
	 */
	public static function getDetailItem($dpid,$detailId){
	 	$sql = 'select * from nb_promotion_activity_detail where lid=:lid and dpid=:dpid';
	 	$result = Yii::app()->db->createCommand($sql)
	 				->bindValue(':lid',$detailId)
				    ->bindValue(':dpid',$dpid)
				    ->queryRow();
		return $result;
	 }
	 /**
	  * 
	  * 发券
	  * type=1 特价活动 2 代金券
	  * 
	  */
	public static function sent($dpid,$userId,$type,$promotionId,$sourceId){
		$now = date('Y-m-d H:i:s',time());
		$result = self::getActivityUser($dpid,$userId,$type,$promotionId);
		if($result){
			return $result['lid'];
		}
		if($type==1){
			$se = new Sequence("private_branduser");
			$lid = $se->nextval();
			$data = array(
					'lid'=>$lid,
		        	'dpid'=>$dpid,
		        	'create_at'=>$now,
		        	'update_at'=>$now,
		        	'private_promotion_id'=>$promotionId,
		        	'to_group'=>3,
		        	'brand_user_lid'=>$userId,
		        	'cupon_source'=>0,
		        	'source_id'=>$sourceId,
		        	'is_used'=>0,
		        	'get_time'=>$now,
		        	'is_sync'=>DataSync::getInitSync(),
					);
			$result = Yii::app()->db->createCommand()->insert('nb_private_branduser', $data);
		}elseif($type==2){
			$se = new Sequence("cupon_branduser");
			$lid = $se->nextval();
			$data = array(
					'lid'=>$lid,
		        	'dpid'=>$dpid,
		        	'create_at'=>$now,
		        	'update_at'=>$now,
		        	'cupon_id'=>$promotionId,
		        	'to_group'=>3,
		        	'brand_user_lid'=>$userId,
		        	'cupon_source'=>0,
		        	'source_id'=>$sourceId,
		        	'is_used'=>0,
		        	'is_sync'=>DataSync::getInitSync(),
					);
			$result = Yii::app()->db->createCommand()->insert('nb_cupon_branduser', $data);
		}elseif($type==3){
			$lid = WxGiftCard::sent($dpid,$userId,$promotionId,$sourceId);
		}
		return $lid;
	}
	public static function getSubPush($dpid){
		$now = date('Y-m-d H:i:s',time());
		$sql = 'select * from nb_promotion_activity where dpid=:dpid and begin_time <=:now and :now <= end_time and is_first_push=0 and delete_flag=0';
		$cupon = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':now',$now)
				  ->queryAll();
	    return $cupon;
	}
	public static function getScanPush($dpid){
		$now = date('Y-m-d H:i:s',time());
		$sql = 'select * from nb_promotion_activity where dpid=:dpid and begin_time <=:now and :now <= end_time and is_scan_push=0 and delete_flag=0';
		$cupon = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':now',$now)
				  ->queryAll();
	    return $cupon;
	}
	/**
	 * 
	 * 更改is_used状态
	 * $type =1 private_branduser表 2 nb_cupon_branduser表
	 * 
	 */
	public static function getPromotionActivity($dpid,$promotionId,$type){
		$isSync = DataSync::getInitSync();
		if($type==1){
			$sql = 'update nb_private_branduser set is_used=1,is_sync='.$isSync.' where dpid='.$dpid.' and lid='.$promotionId;
		}elseif($type==2){
			$sql = 'update nb_cupon_branduser set is_used=1,is_sync='.$isSync.' where dpid='.$dpid.' and lid='.$promotionId;
		}
		$result = Yii::app()->db->createCommand($sql)->execute();
		return $result;
	}
	public static function getActivityUser($dpid,$userId,$type,$promotionId){
		if($type==1){
			$sql = 'select * from nb_private_branduser where dpid='.$dpid.' and ((brand_user_lid='.$userId.' and to_group=3) or to_group=2) and private_promotion_id='.$promotionId.' and delete_flag=0';
		}elseif($type==2){
			$sql = 'select * from nb_cupon_branduser where dpid='.$dpid.' and ((brand_user_lid='.$userId.' and to_group=3) or to_group=2) and cupon_id='.$promotionId.' and delete_flag=0';
		}
		$result = Yii::app()->db->createCommand($sql)->queryRow();
		return $result;
	}
}