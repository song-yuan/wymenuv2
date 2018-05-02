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
	/**
	 *
	 * 饿了么 美团 还有收款机订单保存
	 * redis 数据
	 * type  2 同步云端    3新增会员卡 4 退款失败 5 日结
	 *
	 */
	public static function dealRedisData($dpid){
		$key = 'order_online_total_operation_'.(int)$dpid;
		$orderKey = 'redis-order-data-'.(int)$dpid;
		$orderSize = Yii::app()->redis->lSize($orderKey);
		if($orderSize > 0){
			Yii::app()->redis->set($key,true);
			$orderData = Yii::app()->redis->rPop($orderKey);
			$orderDataArr = json_decode($orderData,true);
			$type = $orderDataArr['type'];
			if($type==2){
				$result = self::operateOrder($orderDataArr);
			}elseif($type==3){
				$result = self::addMemberCard($orderDataArr);
			}elseif($type==4){
				$result = self::retreatOrder($orderDataArr);
			}elseif($type==5){
				$content = $orderDataArr['data'];
				$contentArr = explode('::', $content);
				$rjDpid = $contentArr[0];
				$rjUserId = $contentArr[1];
				$rjCreateAt = $contentArr[2];
				$rjPoscode = $contentArr[3];
				$rjBtime = $contentArr[4];
				$rjEtime = $contentArr[5];
				$rjcode = $contentArr[6];
				$result = WxRiJie::setRijieCode($rjDpid,$rjCreateAt,$rjPoscode,$rjBtime,$rjEtime,$rjcode);
			}
			$resObj = json_decode($result);
			if(!$resObj->status){
				Yii::app()->redis->lPush('redis-order-data-'.(int)$dpid,$orderData);
			}
			self::dealRedisData($dpid);
		}else{
			Yii::app()->redis->set($key,false);
		}
	}
}