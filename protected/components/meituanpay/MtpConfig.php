<?php
/**
 * 
 * 
 * @author dys
 * 美团支付配置文件
 * 
 */
class MtpConfig{
	const MTP_DOMAIN = 'http://payfront.zc.st.meituan.com';
	//美团支付的测试环境外网
	public function MTPAppKeyMid($dpid){
		if($dpid){
			$db = Yii::app()->db;
			$sql = 'select * from nb_mtpay_config where delete_flag =0 and dpid ='.$dpid;
			$ms = $db->createCommand($sql)->query();
			$sql = 'select * from nb_mtpay_config where delete_flag =0 and dpid in(select comp_dpid where dpid ='.$dpid.')';
			$as = $db->createCommand($sql)->query();
			if((!empty($ms))&&(!empty($as))){
				$merchantId = $ms['mt_merchantId'];
				$appId = $as['mt_appId'];
				$key = $as['mt_key'];
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