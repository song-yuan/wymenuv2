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
	/**
	 * 
	 * 获取会员所以代金券
	 * 
	 */
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
	 * pos机微信会员卡
	 * 
	 */
	public static function getUserPosCupon($userId,$dpid,$productIds=''){
		$now = date('Y-m-d H:i:s',time());
		$productcode = 0;
		if($productIds!=''){
			$sql = 'select phs_code from nb_product where dpid='.$dpid.' and lid in('.$productIds.')';
			$productIdArr = Yii::app()->db->createCommand($sql)->queryColumn();
			$productcode = join(',',$productIdArr);
		}
		$sql = 'select m.lid,m.dpid,m.is_used,m.valid_day,m.close_day,n.cupon_title,n.main_picture,n.min_consumer,n.cupon_money,n.begin_time,n.end_time,n.cupon_memo from nb_cupon_branduser m ,(select * from nb_cupon where type_dpid=0 and type_prod=0 and delete_flag=0'.
				' union select t.* from nb_cupon t left join nb_cupon_product t1 on t.lid=t1.cupon_id and t.dpid=t1.dpid where t.type_dpid=0 and t.type_prod=1 and t1.prod_code in('.$productcode.') and t.delete_flag=0 and t1.delete_flag=0'.
				' union select t.* from nb_cupon t left join nb_cupon_dpid t1 on t.lid=t1.cupon_id and t.dpid=t1.dpid where t.type_dpid=1 and t.type_prod=0 and t1.cupon_dpid in('.$dpid.') and t.delete_flag=0 and t1.delete_flag=0) n' .
				' where m.cupon_id=n.lid and m.dpid=n.dpid and m.to_group=3 and m.brand_user_lid=:userId and m.is_used = 1 and m.delete_flag=0 and m.valid_day <=:now and :now <= m.close_day';
	
		$cupon = Yii::app()->db->createCommand($sql)
		->bindValue(':userId',$userId)
		->bindValue(':now',$now)
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
		$sql = 'select m.lid,m.dpid,m.is_used,m.valid_day,m.close_day,n.cupon_title,n.main_picture,n.min_consumer,n.cupon_money,n.begin_time,n.end_time,n.cupon_memo from nb_cupon_branduser m ,nb_cupon n' .
			   ' where m.cupon_id=n.lid and m.dpid=n.dpid and m.to_group=3 and m.brand_user_lid=:userId and m.is_used=1 and m.delete_flag=0 and n.delete_flag=0 and m.valid_day <=:now and :now <= m.close_day';
		
        $cupon = Yii::app()->db->createCommand($sql)
				  	->bindValue(':userId',$userId)
				  	->bindValue(':now',$now)
				  	->queryAll();
	    return $cupon;
	}
	/**
	 * 
	 * 已使用
	 * 
	 */
	public static function getUserUseCupon($userId,$dpid){
		$sql = 'select m.lid,m.dpid,m.is_used,m.valid_day,m.close_day,n.cupon_title,n.main_picture,n.min_consumer,n.cupon_money,n.begin_time,n.end_time,n.cupon_memo from nb_cupon_branduser m ,nb_cupon n' .
			   ' where m.cupon_id=n.lid and m.dpid=n.dpid and m.to_group=3 and m.brand_user_lid=:userId and m.is_used = 2 and m.delete_flag=0';
		$cupon = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
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
		$sql = 'select m.lid,m.is_used,m.valid_day,m.close_day,n.cupon_title,n.main_picture,n.min_consumer,n.cupon_money,n.begin_time,n.end_time ,n.cupon_memo from nb_cupon_branduser m ,nb_cupon n' .
				' where m.cupon_id=n.lid and m.dpid=n.dpid and m.to_group=3 and m.brand_user_lid=:userId and :now > m.close_day and n.delete_flag=0';
		$cupon = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
				  ->bindValue(':now',$now)
				  ->queryAll();
	    return $cupon;
	}
	/**
	 * 
	 * 获取会员该订单可用
	 * @dpid 点单的门店id
	 * 
	 */
	public static function getUserAvaliableCupon($proCodeArr,$total,$userId,$dpid){
		$isCanUse = true;
		$set = WxTotalPromotion::get($dpid);
		
		if($set){
			$orders = WxOrder::getOrderUseCupon($userId,$dpid);
			if($set['is_cupon'] >= 0 && count($orders) >= $set['is_cupon']){
				$isCanUse = false;
			}
		}
		if($isCanUse){
			$proCodeStr = join(',', $proCodeArr);
			$now = date('Y-m-d H:i:s',time());
			$sql = 'select m.lid,m.dpid,n.cupon_title,n.main_picture,n.min_consumer,n.cupon_money from (select * from nb_cupon_branduser where to_group=3 and brand_user_lid=:userId and is_used=1 and delete_flag=0)m , '.
					'(select * from nb_cupon where type_dpid=0 and type_prod=0'.
					' union select * from nb_cupon where type_dpid=1 and type_prod=0 and dpid=:dpid'.
					' union select t.* from nb_cupon t left join nb_cupon_dpid t1 on t.lid=t1.cupon_id and t.dpid=t1.dpid where t.type_dpid > "1" and t.type_prod = "0" and t1.cupon_dpid=:dpid'.
					' union select t.* from nb_cupon t left join nb_cupon_product t2 on t.lid=t2.cupon_id and t.dpid=t2.dpid where t.type_dpid = "0" and t.type_prod > "0" and t2.prod_code in ('.$proCodeStr.')'.
					' union select t.* from nb_cupon t left join nb_cupon_product t2 on t.lid=t2.cupon_id and t.dpid=t2.dpid where t.type_dpid = "1" and t.type_prod > "0" and t.dpid=:dpid and t2.prod_code in ('.$proCodeStr.')'.
					' union select t.* from nb_cupon t left join nb_cupon_dpid t1 on t.lid=t1.cupon_id and t.dpid=t1.dpid left join nb_cupon_product t2 on t.lid=t2.cupon_id and t.dpid=t2.dpid where t.type_dpid > "1" and t.type_prod > "0" and t1.cupon_dpid=:dpid and t2.prod_code in ('.$proCodeStr.'))n ' .
					' where m.cupon_id=n.lid and m.dpid=n.dpid and m.valid_day <=:now and :now <=m.close_day and n.min_consumer <=:total and n.delete_flag=0 and n.is_available=0';
			$cupon = Yii::app()->db->createCommand($sql)
					  ->bindValue(':userId',$userId)
					  ->bindValue(':dpid',$dpid)
					  ->bindValue(':now',$now)
					  ->bindValue(':total',$total)
					  ->queryAll();
		}else{
			$cupon = array();
		}
	    return $cupon;
	}
	/**
	 * 正在进行代金券列表
	 * 
	 */
	public static function getCuponList($dpid){
		$now = date('Y-m-d H:i:s',time());
		$sql = 'select * from nb_cupon where dpid=:dpid and begin_time <=:now and :now <= end_time and delete_flag=0';
		$cupon = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':now',$now)
				  ->queryAll();
	    return $cupon;
	}
	/**
	 * 代金券详情
	 * 
	 */
	public static function getCupon($dpid,$cuponId){
		$sql = 'select * from nb_cupon where lid=:lid and dpid=:dpid and delete_flag=0';
		$cupon = Yii::app()->db->createCommand($sql)
				  ->bindValue(':lid',$cuponId)
				  ->bindValue(':dpid',$dpid)
				  ->queryRow();
	    return $cupon;
	}
	/**
	 * 
	 * 使用核销代金券
	 * 
	 */
	public static function dealCupon($dpid,$cuponId,$status){
		$date = date('Y-m-d H:i:s',time());
		$sql = 'update nb_cupon_branduser set is_used='.$status.',used_time="'.$date.'" where lid='.$cuponId.' and dpid='.$dpid;
		$result = Yii::app()->db->createCommand($sql)->execute();
		return $result;
	}
	/**
	 *
	 * 退还代金券
	 *
	 */
	public static function refundCupon($cuponId,$userId){
		$sql = 'update nb_cupon_branduser set is_used=1 where lid='.$cuponId.' and brand_user_lid='.$userId;
		$result = Yii::app()->db->createCommand($sql)->execute();
		if(!$result){
			throw new Exception('现金券退回失败!');
		}
	}
	/**
	 * 
	 * 获取发放的代金券 活动
	 * 
	 */
	public static function getWxSentCupon($dpid,$type,$userId,$openId){
		$now = date('Y-m-d H:i:s',time());
		$sql = 'select t.* from nb_sentwxcard_promotion_detail t,nb_sentwxcard_promotion t1 where t.sentwxcard_pro_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t1.type=:type and t1.begin_time <=:now and :now <= t1.end_time and t.delete_flag=0 and t1.delete_flag=0';
		$sentPromotion = Yii::app()->db->createCommand($sql)
							->bindValue(':dpid',$dpid)
							->bindValue(':now',$now)
							->bindValue(':type',$type)
							->queryAll();
		foreach ($sentPromotion as $promotion){
			self::sentCupon($dpid,$userId,$promotion['wxcard_id'],2,$promotion['sentwxcard_pro_id'],$openId);
		}
	}
	public static function getOneMonthByBirthday(){
		$monthBegain = date('m-d 00:00:00',strtotime('+1 month'));
		
		$sql = 'select * from nb_brand_user where user_birthday like "%-'.$monthBegain.'" and unsubscribe = 0';
		$users = Yii::app()->db->createCommand($sql)->queryAll();
		if(!empty($users)){
			foreach ($users as $user){
				self::getWxSentCupon($user['dpid'],2,$user['lid'],$user['openid']);
			}
		}
	}
	/**
	 * 
	 * 发放代金券
	 */
	public static function sentCupon($dpid,$userId,$cuponId,$source,$source_id,$openId){
		$company = WxCompany::get($dpid);
		$now = date('Y-m-d H:i:s',time());
		$cupon = self::getCupon($dpid, $cuponId);
		if($cupon){
			$validDay = '';
			$closeDay = '';
			if($cupon['time_type']==1){
				$validDay = $cupon['begin_time'];
				$closeDay = $cupon['end_time'];
			}else{
				$day = $cupon['day'];
				$dayBegin = $cupon['day_begin'];
					
				$validDay = date('Y-m-d H:i:s',strtotime('+'.$dayBegin.' day'));
				$closeDay = date('Y-m-d H:i:s',strtotime('+'.($dayBegin+$day).' day'));
			}
			if($closeDay>$now&&$now>$validDay){
				$se = new Sequence("cupon_branduser");
				$lid = $se->nextval();
				$data = array(
						'lid'=>$lid,
						'dpid'=>$dpid,
						'create_at'=>$now,
						'update_at'=>$now,
						'cupon_id'=>$cuponId,
						'to_group'=>3,
						'brand_user_lid'=>$userId,
						'cupon_source'=>$source,
						'source_id'=>$source_id,
						'valid_day'=>$validDay,
						'close_day'=>$closeDay,
						'is_used'=>1,
						'is_sync'=>DataSync::getInitSync(),
				);
				$result = Yii::app()->db->createCommand()->insert('nb_cupon_branduser', $data);
				if($source==0){
					$sourceStr = '活动领取';
				}elseif($source==1){
					$sourceStr = '红包领取';
				}else{
					$sourceStr = '商家赠送';
				}
				$data = array(
						'touser'=>$openId,
						'url'=>Yii::app()->createAbsoluteUrl('/user/ticket',array('companyId'=>$dpid)),
						'first'=>'现金券已经领取成功',
						'keyword1'=>$cupon['cupon_money'].'元现金券一张',
						'keyword2'=>$sourceStr,
						'keyword3'=>$closeDay,
						'keyword4'=>$cupon['cupon_abstract'],
						'remark'=>'如果有任何疑问,欢迎拨打电话'.$company['telephone'].'咨询'
				);
				new WxMessageTpl($dpid,$userId,1,$data);
			}
		}
	}
}