<?php 
/**
 * 
 * 
 * 微信端满减  满送
 *
 * 
 * 
 */
class WxFullSent
{
	/**
	 * 
	 * 获取会员所以代金券
	 * 
	 */
	public static function getAllFullsent($dpid){
		$sql = '';
		$cupon = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':userLevelId',$user['user_level_lid'])
				  ->queryAll();
	    return $cupon;
	}
}