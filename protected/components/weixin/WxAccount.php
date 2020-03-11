<?php
class WxAccount {
	/**
	 * 获取品牌的token
	 */
	public static function get($brandId, $type = 0) {
		$sql = 'select * from nb_weixin_service_account where dpid = '.$brandId.' and type='.$type;
		$weixinServiceAccount = Yii::app()->db->createCommand($sql)->queryRow();
		if(empty($weixinServiceAccount)){
				$weixinServiceAccount = self::getCompanyAccount($brandId,$type);
		}else{
			if(empty($weixinServiceAccount['appid'])){
				$weixinServiceAccount = self::getCompanyAccount($brandId,$type);
			}
		}
		if(empty($weixinServiceAccount)){
			throw new Exception('不存在该公司的微信信息');
		}
		return $weixinServiceAccount;
	}
	/**
	 * 获取总部的 微信信息
	 */ 
	public static function getCompanyAccount($brandId, $type = 0) {
		$companyId = WxCompany::getCompanyDpid($brandId);
		$sql = 'select * from nb_weixin_service_account where dpid = '.$companyId.' and type='.$type;
		$weixinServiceAccount = Yii::app()->db->createCommand($sql)->queryRow();
		return $weixinServiceAccount;
	}
	/**
	 * 通过appid获取微信信息
	 */
	public static function getAccountByAppid($appid) {
		$sql = 'select * from nb_weixin_service_account where appid = "'.$appid.'"';
		$weixinServiceAccount = Yii::app()->db->createCommand($sql)->queryRow();
		return $weixinServiceAccount;
	}
}
 
?>