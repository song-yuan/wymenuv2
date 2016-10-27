<?php
/**
 * Token.php
 *
 */
 
class AlipayAccount {
	
	/**
	 * 获取品牌的token
	 */
	public static function get($brandId) {
		$sql = 'select * from nb_alipay_service_account where dpid = '.$brandId;
		$alipayServiceAccount = Yii::app()->db->createCommand($sql)->queryRow();
		if(empty($alipayServiceAccount)){
				$alipayServiceAccount = self::getCompanyAccount($brandId);
		}else{
			if(empty($alipayServiceAccount['appid'])){
				// 获取总部的 微信信息
				$alipayServiceAccount = self::getCompanyAccount($brandId);
			}
		}
		if(empty($alipayServiceAccount)){
			throw new Exception('不存在该公司的支付宝信息');
		}
		return $alipayServiceAccount;
	}
	public static function getCompanyAccount($brandId) {
		$companyId = WxCompany::getCompanyDpid($brandId);
		$sql = 'select * from nb_alipay_service_account where dpid = '.$companyId;
		$alipayServiceAccount = Yii::app()->db->createCommand($sql)->queryRow();
		return $alipayServiceAccount;
	}
}
 
?>