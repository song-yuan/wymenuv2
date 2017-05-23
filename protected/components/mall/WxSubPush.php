<?php 
/**
 * 
 * 
 * 微信端代金券类
 *
 * 
 * 
 */
class WxSubPush
{
	/**
	 * 
	 * 关注推送
	 * 
	 * 
	 */
	public static function getSubPush($dpid){
		$sql = 'select * from nb_weixin_subpush where dpid=:dpid and is_available=0 and delete_flag=0';
		$push = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->queryAll();
	    return $push;
	}
}