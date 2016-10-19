<?php
/**
 * BrandUser.php
 *
 */
 
class WxBrandUser {
	
	/**
	 * 返回brandUser数组
	 */
	public static function get($userId,$dpid) {
		$sql = 'SELECT * FROM nb_brand_user WHERE lid = ' .$userId .' and dpid = '.$dpid;
		$brandUser = Yii::app()->db->createCommand($sql)->queryRow();
		if(empty($brandUser)){
			$companyId = WxCompany::getCompanyDpid($dpid);
			$brandUser = self:: get($userId,$companyId);
		}
		return $brandUser;
	}
	/**
	 * 返回brandUser数组
	 */
	public static function getUserLevel($userLevelId,$dpid) {
		$sql = 'SELECT * FROM nb_brand_user_level WHERE lid = ' .$userLevelId .' and dpid = '.$dpid.' and delete_flag=0';
		$brandUserLevel = Yii::app()->db->createCommand($sql)->queryRow();
		return $brandUserLevel;
	}
	/**
	 * 返回对应的openId 
	 */
	public static function openId($userId,$dpid) {
		$brandUser = self:: get($userId,$dpid);
		if(empty($brandUser)){
			$companyId = WxCompany::getCompanyDpid($dpid);
			$brandUser = self:: get($userId,$companyId);
		}
		return $brandUser['openid'];
	}
	/**
	 * 通过openid查找用户
	 * 
	 */
	public static function getFromOpenId($openId) {
		$sql = 'select * from nb_brand_user where openid = "'.$openId.'"';
		$brandUser = Yii::app()->db->createCommand($sql)->queryRow();
		return $brandUser;
	}
	/**
	 * 通过openid查找用户
	 * 
	 */
	public static function getFromCardId($cardId) {
		$sql = 'select * from nb_brand_user where card_id = "'.$cardId.'"';
		$brandUser = Yii::app()->db->createCommand($sql)->queryRow();
		return $brandUser;
	}
	/**
	 * 
	 * 获取会员总余额
	 * 
	 */
	public static function getYue($userId,$dpid) {

		$brandUser = self::get($userId,$dpid);
		$remainMoney = $brandUser['remain_money'];
		
		$cashback = self::getCashBackYue($userId,$dpid);
		
		return $cashback ? $cashback + $remainMoney : $remainMoney;
	}
	/**
	 * 
	 * 获取会员充值余额
	 * 
	 */
	public static function getRechargeYue($userId,$dpid) {
		$brandUser = self::get($userId,$dpid);
		$remainMoney = $brandUser['remain_money'];
		
		return $remainMoney;
	}
	/**
	 * 
	 * 获取会员返现余额
	 * 
	 */
	public static function getCashBackYue($userId,$dpid) {
		$now = date('Y-m-d H:i:s',time());
		$sql = 'select sum(remain_cashback_num) as total from nb_cashback_record where brand_user_lid = '.$userId.' and dpid='.$dpid.' and delete_flag=0 and ((point_type=0 and begin_timestamp < "'.$now.'" and end_timestamp > "'.$now.'") or point_type=1)';
		$cashback = Yii::app()->db->createCommand($sql)->queryRow();
		return $cashback['total'] ? $cashback['total']:0;
	}
	/**
	 * 
	 * 获取会员的历史积分
	 * 
	 */
	public static function getHistoryPoints($userId,$dpid) {
		$sql = 'select sum(point_num) as total from nb_point_record where brand_user_lid = '.$userId.' and dpid='.$dpid;
		$points = Yii::app()->db->createCommand($sql)->queryRow();
		return $points['total']?$points['total']:0;
	}
	/**
	 * 
	 * 获取会员的可用积分
	 * 
	 */
	public static function getAvaliablePoints($userId,$dpid) {
		$now = date('Y-m-d H:i:s',time());
		$sql = 'select sum(point_num) as total from nb_point_record where brand_user_lid = '.$userId.' and dpid='.$dpid.' and end_time > "'.$now.'"';
		$points = Yii::app()->db->createCommand($sql)->queryRow();
		return $points['total']?$points['total']:0;
	}
	/**
	 * 
	 * 保存会员资料
	 * 
	 * 
	 */
	public static function update($param){
		$insertData = array(
				        	'user_name'=>$param['user_name'],
				        	'mobile_num'=>$param['mobile_num'],
				        	'user_birthday'=>$param['user_birthday'],
				        	'is_sync'=>DataSync::getInitSync(),
							);
		$result = Yii::app()->db->createCommand()->update('nb_brand_user', $insertData,'lid=:lid and dpid=:dpid',array(':lid'=>$param['lid'],':dpid'=>$param['dpid']));
		return $result;
	}
}

 
?>