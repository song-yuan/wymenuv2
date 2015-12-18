<?php 
/**
 * 
 * 
 * 获取微信充值模板
 * 
 * 
 */
class WxRecharge
{
	/**
	 * 
	 * 获取微信充值模板
	 * 
	 */
	public static function getWxRecharge($dpid){
		$sql = 'select * from nb_weixin_recharge where dpid=:dpid and is_available=0 and delete_flag=0';
		$recharges = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->queryAll();
	    return $recharges;		  
	}
}