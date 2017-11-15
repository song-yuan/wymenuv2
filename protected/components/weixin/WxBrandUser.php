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
			if($companyId!=0){
				$brandUser = self:: get($userId,$companyId);
			}
		}
		if($brandUser){
			$brandUserLevel = self::getUserLevel($brandUser['user_level_lid'],$brandUser['dpid']);
			$brandUser['level'] = $brandUserLevel;
		}
		return $brandUser;
	}
	/**
	 * 返回会员等级
	 */
	public static function getUserLevel($userLevelId,$dpid) {
		$sql = 'SELECT * FROM nb_brand_user_level WHERE lid = ' .$userLevelId .' and dpid = '.$dpid.' and delete_flag=0';
		$brandUserLevel = Yii::app()->db->createCommand($sql)->queryRow();
		return $brandUserLevel;
	}
        
        /**
	 * 返回店铺所有等级
	 */
        public static function getAllLevel($dpid) {
            $sql = 'SELECT * FROM nb_brand_user_level WHERE dpid = ' .$dpid .' and level_type=1 and delete_flag=0 order by level_discount desc ';
            $result = Yii::app()->db->createCommand($sql)->queryAll();
			return $result;
        }
        
        
        /**
         * 返回会员卡图片
         */
        public static function getCardImg($style_id,$dpid) {

		$sql = 'SELECT * FROM nb_member_wxcard_style WHERE lid = ' .$style_id .' and dpid = '.$dpid.' and delete_flag=0';
		$card_img = Yii::app()->db->createCommand($sql)->queryRow();
		return $card_img;
	}
        /**
         * 返回满送
         */
        public static function getFullGive($dpid) {
                $now = date('Y-m-d H:i:s',time());
		$sql = 'SELECT * FROM nb_full_sent WHERE full_type = 0 and begin_time <=:now and end_time>=:now and dpid = '.$dpid.' and delete_flag=0';
		$full_give =  Yii::app()->db->createCommand($sql)->bindValue(':now',$now)->queryAll();
		return $full_give;
	}
         /**
         * 返回满减
         */
        public static function getFullMinus($dpid) {
                $now = date('Y-m-d H:i:s',time());
		$sql = 'SELECT * FROM nb_full_sent WHERE full_type = 1 and begin_time <=:now and end_time>=:now and dpid = '.$dpid.' and delete_flag=0 ';
		$full_minus = Yii::app()->db->createCommand($sql)->bindValue(':now',$now)->queryAll();
		return $full_minus;
	}
        
         /**
         * 返回账单
         */
        public static function getOrderPay($card_id,$dpid) {
        $dpid = WxCompany::getDpids($dpid);       
		$sql = 'SELECT * , sum(pay_amount) as amount FROM nb_order_pay WHERE  dpid in ( '.$dpid.') and remark = '.$card_id.' and paytype in (8,9,10) GROUP BY account_no ';
		$order_pay = Yii::app()->db->createCommand($sql)->queryAll();
		return $order_pay;
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
		if($brandUser){
			$brandUserLevel = self::getUserLevel($brandUser['user_level_lid'],$brandUser['dpid']);
			$brandUser['level'] = $brandUserLevel;
		}
		return $brandUser;
	}
	/**
	 * 通过userid查找用户
	 *
	 */
	public static function getFromUserId($userId) {
		$sql = 'select * from nb_brand_user where lid = "'.$userId.'"';
		$brandUser = Yii::app()->db->createCommand($sql)->queryRow();
		if($brandUser){
			$brandUserLevel = self::getUserLevel($brandUser['user_level_lid'],$brandUser['dpid']);
			$brandUser['level'] = $brandUserLevel;
		}
		return $brandUser;
	}
	/**
	 * 通过card_id查找用户
	 * 
	 */
	public static function getFromCardId($dpid,$cardId) {
		$comdpid = WxCompany::getCompanyDpid($dpid);
		$sql = 'select t.*,t1.level_name,t1.level_discount,t1.birthday_discount from nb_brand_user t left join nb_brand_user_level t1 on t.user_level_lid=t1.lid and t.dpid=t1.dpid and t1.level_type=1 and t1.delete_flag=0 where t.dpid in('.$dpid.','.$comdpid.') and t.card_id = "'.$cardId.'"';
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
		
		$cashback = $brandUser['remain_back_money'];
		
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
		$brandUser = self::get($userId,$dpid);
		$remainMoney = $brandUser['remain_back_money'];
		return $remainMoney;
	}
	/**
	 * 
	 * 获取会员的历史积分
	 * 
	 */
	public static function getHistoryPoints($userId,$dpid) {
		$sql = 'select sum(point_num) as total from nb_member_points where card_type=1 and card_id = '.$userId.' and dpid='.$dpid;
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
		$sql = 'select sum(point_num) as total from nb_member_points where card_type=1 and card_id = '.$userId.' and dpid='.$dpid.' and end_time > "'.$now.'"';
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
		$userName = $param['user_name'];
		$mobileNum = $param['mobile_num'];
		$userBirthday = str_replace('/','-',$param['user_birthday']);
		$insertData = array(
				        	'user_name'=>$userName,
				        	'mobile_num'=>$mobileNum,
				        	'user_birthday'=>$userBirthday,
				        	'is_sync'=>DataSync::getInitSync(),
							);
		$result = Yii::app()->db->createCommand()->update('nb_brand_user', $insertData,'lid=:lid and dpid=:dpid',array(':lid'=>$param['lid'],':dpid'=>$param['dpid']));
		return $result;
	}
	/**
	 * 
	 * 根据openid更新会员来源
	 * 
	 */
	public static function updateByOpenid($param){
		$openid = $param['openid'];
		$group = $param['group'];
		$sql = 'update nb_brand_user set scene_type=1,weixin_group='.$group.' where openid="'.$openid.'"';
		$result = Yii::app()->db->createCommand($sql)->execute();
		return $result;
	}
	public static function reduceYue($userId,$userDpId, $dpid,$total){
		$yue = WxBrandUser::getYue($userId,$userDpId);//余额
		$cashRecharge = WxBrandUser::getRechargeYue($userId,$userDpId);//储值余额
		$cashBack = WxBrandUser::getCashBackYue($userId,$userDpId);//返现余额
		if($cashRecharge > 0){
			// 储值余额 大于0
			if($cashRecharge >= $total){
				//储值余额大于等于支付
				WxCashBack::userCashRecharge($total,$userId,$userDpId,$dpid,0);
				$payMoney = $total;
			}else{
				WxCashBack::userCashRecharge($cashRecharge,$userId,$userDpId,$dpid,1);
				if($yue > $total){//剩余返现大于支付
					WxCashBack::userCashBack($total - $cashRecharge,$userId,$userDpId,$dpid,0);
					$payMoney = $total;
				}else{
					//剩余返现小于等于支付
					if($cashBack > 0){
						WxCashBack::userCashBack($cashBack,$userId,$userDpId,$dpid,1);
					}
					$payMoney = $yue;
				}
			}
		}else{
			// 储值余额 等于=0
			if($yue > $total){
				if($cashBack > 0){
					WxCashBack::userCashBack($total,$userId,$userDpId,$dpid,0);
				}
				$payMoney = $total;
			}else{
				if($cashBack > 0){
					WxCashBack::userCashBack($total,$userId,$userDpId,$dpid,1);
				}
				$payMoney = $yue;
			}
		}
		return $payMoney;
	}
	public static function dealYue($userId,$dpid,$money){
		$payMoney = self::reduceYue($userId,$dpid,-$money);
		return $payMoney;
	}
	/**
	 * 
	 * @param unknown $userId
	 * @param unknown $dpid
	 * @return Ambigous <number, unknown>
	 * 
	 */
	public static function getMemberCardByMobile($mobile) {
		$sql = 'select * from nb_member_card where mobile=:mobile and delete_flag=0';
		$memberCard = Yii::app()->db->createCommand($sql)->bindValue(':mobile',$mobile)->queryRow();
		return $memberCard;
	}
	/**
	 * 
	 * 获取实体卡 绑定的微信卡
	 * 
	 */
	public static function getMemberCardBind($mem_level_id,$dpid) {
		$sql = 'select * from nb_member_card_bind where membercard_level_id=:levelId and dpid=:dpid and delete_flag=0';
		$memberCardBind = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->bindValue(':levelId',$mem_level_id)->queryRow();
		return $memberCardBind;
	}
	public static function updateUserLevel($userId,$dpid,$userLevelId) {
		$sql = 'update nb_brand_user set user_level_lid='.$userLevelId.' where lid='.$userId.' and dpid='.$dpid;
		$result = Yii::app()->db->createCommand($sql)->execute();
		return $result;
	}
	/**
	 * 
	 * 绑定 会员升级直接
	 * 
	 */
	public static function brandUserBind($userId,$dpid,$rfid,$userLevelId,$points) {
		$sql = 'update nb_brand_user set user_level_lid='.$userLevelId.',consume_point_history='.$points.',member_card_rfid='.$rfid.' where lid='.$userId.' and dpid='.$dpid;
		$result = Yii::app()->db->createCommand($sql)->execute();
		return $result;
	}
	/**
	 *
	 * 会员退储值余额
	 *
	 */
	public static function refundYue($amount,$cardId) {
		$sql = 'update nb_brand_user set remain_back_money=remain_back_money+'.$amount.' where card_id="'.$cardId.'"';
		$result = Yii::app()->db->createCommand($sql)->execute();
		if(!$result){
			throw new Exception('储值退回失败!');
		}
	}
}

 
?>