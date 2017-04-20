<?php 
/**
 * 
 * 
 * 礼品券类
 *
 * 
 * 
 */
class WxGiftCard
{
	public static function getUserAvailableGift($userId,$dpid){
		$now = date('Y-m-d H:i:s',time());
		$sql = 'select t.code,t.is_used,t.used_at,t1.* from nb_branduser_gift t,nb_gift t1 where t.gift_lid=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and  t1.begin_time <=:now and :now <= t1.end_time and t.branduser_lid=:userId and t.delete_flag=0 and t1.delete_flag=0 and t.is_used=0';
		$gift = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':now',$now)
				  ->queryAll();
	    return $gift;
	}
	public static function getUserUsedGift($userId,$dpid){
		$sql = 'select t.code,t.is_used,t.used_at,t1.* from nb_branduser_gift t,nb_gift t1 where t.gift_lid=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.branduser_lid=:userId and t.delete_flag=0 and t.is_used=1';
		$gift = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
				  ->bindValue(':dpid',$dpid)
				  ->queryAll();
	    return $gift;
	}
	public static function getUserExpireGift($userId,$dpid){
		$now = date('Y-m-d H:i:s',time());
		$sql = 'select t.code,t.is_used,t.used_at,t1.* from nb_branduser_gift t,nb_gift t1 where t.gift_lid=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and :now > t1.end_time and t.branduser_lid=:userId and t.delete_flag=0 and t1.delete_flag=0 and t.is_used=0';
		$gift = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':now',$now)
				  ->queryAll();
	    return $gift;
	}
	public static function getUserGift($dpid,$userId,$giftId){
		$sql = 'select t.lid,t.code,t.qrcode,t.is_used,t.used_at,t1.title,t1.intro,t1.price,t1.gift_pic,t1.count,t1.stock,t1.begin_time,t1.end_time from nb_branduser_gift t,nb_gift t1 where t.gift_lid=t1.lid and t.dpid=t1.dpid and t.branduser_lid=:userId and t.gift_lid=:lid  and t.dpid=:dpid';
		$gift = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
				  ->bindValue(':lid',$giftId)
				  ->bindValue(':dpid',$dpid)
				  ->queryRow();
	    return $gift;
	}
	/**
	 * 
	 * 获取 代金券
	 * 
	 */
	 public static function getGift($dpid,$lid){
		$now = date('Y-m-d H:i:s',time());
		$sql = 'select * from nb_gift where lid=:lid and dpid=:dpid and begin_time <=:now and :now <= end_time and delete_flag=0';
		$gift = Yii::app()->db->createCommand($sql)
				  ->bindValue(':lid',$lid)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':now',$now)
				  ->queryRow();
	    return $gift;
	}
	/**
	 * 
	 * 
	 * 获取自动发送到礼品券
	 * 
	 */
	public static function getAutoGift($dpid){
		$now = date('Y-m-d H:i:s',time());
		$sql = 'select * from nb_gift where dpid=:dpid and begin_time <=:now and :now <= end_time and is_sent=1 and delete_flag=0';
		$gifts = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':now',$now)
				  ->queryAll();
	    return $gifts;
	}
	/**
	 * 
	 * 获取会员领取总数
	 * 
	 */
	 public static function getUserGiftTotal($dpid,$userId,$giftId){
		$sql = 'select count(*) as total from nb_branduser_gift where gift_lid=:giftId and dpid=:dpid and branduser_lid=:userId';
		$gift = Yii::app()->db->createCommand($sql)
				  ->bindValue(':giftId',$giftId)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':userId',$userId)
				  ->queryRow();
		return $gift;
	 }
	 /**
	  * 
	  * 通过code查找
	  * 
	  */
	   public static function getUserGiftByCode($dpid,$code){
	   		$sql = 'select count(*) as total from nb_branduser_gift where dpid=:dpid and code=:code';
	   		$gift = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':code',$code)
				  ->queryRow();
			return $gift;
	   }
	   /**
	    * 
	    * 保存券信息
	    * 
	    */
	    public static function updateQrcode($dpid,$brandUserGiftId,$qrcode){
	    	$isSync = DataSync::getInitSync();
	   		$sql = 'update nb_branduser_gift set qrcode="'.$qrcode.'",is_sync='.$isSync.' where dpid=:dpid and lid=:lid';
	   		$result = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':lid',$brandUserGiftId)
				  ->execute();
			return $result;
	   }
	 /**
	  * 
	  * 
	  * 发送自动关注礼品券
	  * 
	  * 
	  */
	   public static function sentGift($dpid,$userId){
	   		$gifts = self::getAutoGift($dpid);
	   		foreach($gifts as $gift){
	   			$code = self::code(11);
	   			$total = self::getUserGiftTotal($dpid,$userId,$gift['lid']);
	   			if($gift['count'] > $total['total']){
	   					$time = time();
						$se = new Sequence("branduser_gift");
					    $lid = $se->nextval();
						$insertData = array(
											'lid'=>$lid,
								        	'dpid'=>$dpid,
								        	'create_at'=>date('Y-m-d H:i:s',$time),
								        	'update_at'=>date('Y-m-d H:i:s',$time), 
								        	'gift_lid'=>$gift['lid'],
								        	'branduser_lid'=>$userId,
								        	'code'=>$code,
								        	'gift_source'=>1,
								        	'is_used'=>0,
								        	'is_sync'=>DataSync::getInitSync(),
											);
						$result = Yii::app()->db->createCommand()->insert('nb_branduser_gift', $insertData);
	   			}
	   		}
	   }
	   /**
	  * 
	  * 
	  * 发送礼品券
	  * 
	  * 
	  */
	   public static function sent($dpid,$userId,$giftId,$sourceId){
	   		$lid = 0;
	   		$code = self::code(11);
	   		do{
	   			$result = self::getUserGiftByCode($dpid,$code);
	   		}while ($result);
	   		
	   		$gift = self::getGift($dpid,$giftId);
	   		if($gift){
	   			$total = self::getUserGiftTotal($dpid,$userId,$gift['lid']);
	   			$lid = $total['total'];
	   			if($gift['count'] > $total['total']){
	   					$time = time();
						$se = new Sequence("branduser_gift");
					    $lid = $se->nextval();
						$insertData = array(
											'lid'=>$lid,
								        	'dpid'=>$dpid,
								        	'create_at'=>date('Y-m-d H:i:s',$time),
								        	'update_at'=>date('Y-m-d H:i:s',$time), 
								        	'gift_lid'=>$gift['lid'],
								        	'brand_user_lid'=>$userId,
								        	'code'=>$code,
								        	'gift_source'=>0,
								        	'source_id'=>$sourceId,
								        	'is_used'=>0,
								        	'is_sync'=>DataSync::getInitSync(),
											);
						$result = Yii::app()->db->createCommand()->insert('nb_branduser_gift', $insertData);
		   		}
	   		}
	   		return $lid;
	   }
	 /**
	  * 
	  * 
	  * 生成核销码
	  * 
	  */
	   public static function code($length = 11){
   		   $chars = '0123456789';
		    $password = '1';
		    for ( $i = 0; $i < $length; $i++ ) 
		    {
		        $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
		    }
		    return $password;
	   }
}