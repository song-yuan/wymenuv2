<?php
/**
 * 
 * 
 * @author dys
 * 美团支付配置文件
 * 
 */
class MtpConfig{
// 	const MTP_DOMAIN = 'http://payfront.zc.st.meituan.com';/*测试环境*/
	const MTP_DOMAIN = 'https://openpay.meituan.com/';/*正式环境*/
// 	const MTP_DOMAIN_SQ = 'http://openpay.zc.st.meituan.com'; /*测试环境*/
	const MTP_DOMAIN_SQ = 'https://openpay.meituan.com'; /*正式环境*/
	//美团支付的测试环境外网
	static function MTPAppKeyMid($dpid){
		//Helper::writeLog('进入查询参数');
		if($dpid){
			$db = Yii::app()->db;
			$sql = 'select * from nb_mtpay_config where delete_flag =0 and dpid ='.$dpid;
			$ms = $db->createCommand($sql)->queryRow();
			$sql = 'select * from nb_mtpay_config where delete_flag =0 and dpid in(select comp_dpid from nb_company where dpid ='.$dpid.')';
			$as = $db->createCommand($sql)->queryRow();
			if((!empty($ms))&&(!empty($as))){
				//var_dump($ms);exit;
				$merchantId = $ms['mt_merchantId'];
				$appId = $as['mt_appId'];
				$key = $as['mt_key'];
				//Helper::writeLog('查询到参数:'.$merchantId.','.$appId.','.$key);
				return $merchantId.','.$appId.','.$key;
			}else{
				Helper::writeLog('未查询到参数:'.$dpid);
				return false;
			}
		}else{
			return false;
		}
	}
}
?>