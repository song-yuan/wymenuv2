<?php 
/**
 * 
 * 
 * 微信端会员地址类
 * 
 * 
 */
class WxAddress
{
	public static function get($userId,$dpid){
		$sql = 'select * from nb_address where brand_user_lid=:userId and dpid=:dpid and delete_flag=0';
		$address = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
				  ->bindValue(':dpid',$dpid)
				  ->queryAll();
	    return $address;
	}
	public static function getDefault($userId,$dpid){
		$sql = 'select * from nb_address where brand_user_lid=:userId and dpid=:dpid and default_address=1 and delete_flag=0';
		$address = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
				  ->bindValue(':dpid',$dpid)
				  ->queryRow();
	    return $address;
	}
	public static function getAddress($lid,$dpid){
		$sql = 'select * from nb_address where lid=:lid and dpid=:dpid and delete_flag=0';
		$address = Yii::app()->db->createCommand($sql)
				  ->bindValue(':lid',$lid)
				  ->bindValue(':dpid',$dpid)
				  ->queryRow();
	    return $address;
	}
	public static function insert($param){
		if(isset($param['default_address'])){
			self::dealDefaultAddress($param['user_id'],$param['dpid']);
		}
		$time = time();
		$se = new Sequence("address");
	    $lid = $se->nextval();
		$insertData = array(
							'lid'=>$lid,
				        	'dpid'=>$param['dpid'],
				        	'create_at'=>date('Y-m-d H:i:s',$time),
				        	'update_at'=>date('Y-m-d H:i:s',$time), 
				        	'brand_user_lid'=>$param['user_id'],
				        	'name'=>$param['name'],
				        	'sex'=>$param['sex'],
				        	'province'=>$param['province'],
				        	'city'=>$param['city'],
				        	'area'=>$param['area'],
				        	'street'=>$param['street'],
				        	'postcode'=>isset($param['postcode'])?$param['postcode']:0,
				        	'mobile'=>$param['mobile'],
				        	'lng'=>$param['lng'],
				        	'lat'=>$param['lat'],
				        	'default_address'=>isset($param['default_address'])?1:0,
				        	'is_sync'=>DataSync::getInitSync(),
							);
		$result = Yii::app()->db->createCommand()->insert('nb_address', $insertData);
		return $result;
	}
	public static function update($param){
		if(isset($param['default_address'])){
			self::dealDefaultAddress($param['user_id'],$param['dpid']);
		}
		$insertData = array(
				        	'name'=>$param['name'],
				        	'sex'=>$param['sex'],
				        	'province'=>$param['province'],
				        	'city'=>$param['city'],
				        	'area'=>$param['area'],
				        	'street'=>$param['street'],
				        	'postcode'=>isset($param['postcode'])?$param['postcode']:0,
				        	'mobile'=>$param['mobile'],
				        	'lng'=>$param['lng'],
				        	'lat'=>$param['lat'],
				        	'default_address'=>isset($param['default_address'])?1:0,
				        	'is_sync'=>DataSync::getInitSync(),
							);
		$result = Yii::app()->db->createCommand()->update('nb_address', $insertData,'lid=:lid and dpid=:dpid',array(':lid'=>$param['lid'],':dpid'=>$param['dpid']));
		return $result;
	}
	public static function deleteAddress($lid,$dpid){
		$isSync = DataSync::getInitSync();
		$sql = 'update nb_address set delete_flag=1,is_sync='.$isSync.' where dpid='.$dpid.' and lid='.$lid;
		$result = Yii::app()->db->createCommand($sql)->execute();;
	    return $result;
	}
	public static function dealDefaultAddress($userId,$dpid){
		$isSync = DataSync::getInitSync();
		$sql = 'update nb_address set default_address=0,is_sync='.$isSync.' where dpid='.$dpid.' and brand_user_lid='.$userId;
		Yii::app()->db->createCommand($sql)->execute();
	}
	public static function setDefault($userId,$lid,$dpid){
		self::dealDefaultAddress($userId,$dpid);
		$isSync = DataSync::getInitSync();
		$sql = 'update nb_address set default_address=1,is_sync='.$isSync.' where dpid='.$dpid.' and lid='.$lid;
		$result = Yii::app()->db->createCommand($sql)->execute();
		return $result;
	}
	/** 
	* @desc 根据两点间的经纬度计算距离 
	* @param float $lat 纬度值 
	* @param float $lng 经度值 
	*/
	public static function getDistance($lat1, $lng1, $lat2, $lng2) 
	{ 
		$earthRadius = 6367000; 
		 
		$lat1 = ($lat1 * pi() ) / 180; 
		$lng1 = ($lng1 * pi() ) / 180; 
		 
		$lat2 = ($lat2 * pi() ) / 180; 
		$lng2 = ($lng2 * pi() ) / 180; 
		 
		$calcLongitude = $lng2 - $lng1; 
		$calcLatitude = $lat2 - $lat1; 
		$stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2); 
		$stepTwo = 2 * asin(min(1, sqrt($stepOne))); 
		$calculatedDistance = $earthRadius * $stepTwo; 
		 
		return round($calculatedDistance); 
	} 
}