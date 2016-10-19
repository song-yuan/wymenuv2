<?php
/**
 * Token.php
 *
 */
 
class WxAccount {
	
	/**
	 * 获取品牌的token
	 */
	public static function get($brandId) {
		$sql = 'select * from nb_weixin_service_account where dpid = '.$brandId;
		$weixinServiceAccount = Yii::app()->db->createCommand($sql)->queryRow();
		if(empty($weixinServiceAccount)){
				$weixinServiceAccount = self::getCompany($brandId);
		}else{
			if(empty($weixinServiceAccount['appid'])){
				// 获取总部的 微信信息
				$weixinServiceAccount = self::getCompany($brandId);
			}
		}
		if(empty($weixinServiceAccount)){
			throw new Exception('不存在该公司的微信信息');
		}
		return $weixinServiceAccount;
	}
	public static function getCompany($brandId) {
		$companyId = WxCompany::getCompanyDpid($brandId);
		$sql = 'select * from nb_weixin_service_account where dpid = '.$companyId;
		$weixinServiceAccount = Yii::app()->db->createCommand($sql)->queryRow();
		return $weixinServiceAccount;
	}
}
 
?>