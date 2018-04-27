<?php 
/**
 * 
 * 
 * 获取单品口味
 * 
 * 
 */
class WxRedis
{
	// 生成订单 redis数据
	public static function pushOrder($dpid,$data){
		$key = 'redis-order-data-'.(int)$dpid;
		$result = Yii::app()->redis->lPush($key,$data);
		return $result;
	}
	// 第三方订单 redis数据  收款机接收
	public static function pushPlatform($dpid,$data){
		$key = 'redis-third-platform-'.(int)$dpid;
		$result = Yii::app()->redis->lPush($key,$data);
		return $result;
	}
	
}