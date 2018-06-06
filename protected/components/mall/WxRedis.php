<?php 
/**
 * 
 * 
 * redis-order-data- $dpid  每家店一个redis 队列
 * redis-third-platform- $dpid  每家店一个第三方订单 redis 队列 
 * order_online_total_operation_ $dpid 每家一个订单锁（优化成全部一个锁 order_online_total_operation_0）
 * 
 */
class WxRedis
{
	// 生成队列的 下标号
	public static function redisIndex($dpid){
		$ndpid = floor($dpid/10000);
		return $ndpid;
	}
	// 生成订单 redis数据
	public static function pushOrder($dpid,$data){
		$nIndex = self::redisIndex($dpid);
		$key = 'redis-order-data-'.$nIndex;
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
		$nIndex = self::redisIndex($dpid);
		$key = 'order_online_total_operation_'.$nIndex;
		$orderKey = 'redis-order-data-'.$nIndex;
		$orderSize = Yii::app()->redis->lLen($orderKey);
		if($orderSize > 0){
			$orderData = Yii::app()->redis->rPop($orderKey);
			if(!empty($orderData)){
				$orderDataArr = json_decode($orderData,true);
				if(is_array($orderDataArr)){
					$type = $orderDataArr['type'];
					if($type==2){
						$result = DataSyncOperation::operateOrder($orderDataArr);
					}elseif($type==3){
						$result = DataSyncOperation::addMemberCard($orderDataArr);
					}elseif($type==4){
						$result = DataSyncOperation::retreatOrder($orderDataArr);
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
						$msg = isset($resObj->msg)?$resObj->msg:'';
						Helper::writeLog('同步失败:'.$orderData.'-'.$msg);
						$data = array('dpid'=>$orderDataArr['dpid'],'jobid'=>$orderDataArr['posLid'],'pos_sync_lid'=>$orderDataArr['sync_lid'],'sync_type'=>$type,'sync_url'=>'','content'=>$orderData);
						DataSyncOperation::setSyncFailure($data);
					}
				}else{
					$data = array('dpid'=>$dpid,'jobid'=>0,'pos_sync_lid'=>0,'sync_type'=>0,'sync_url'=>'','content'=>$orderData);
					DataSyncOperation::setSyncFailure($data);
				}
			}
			self::dealRedisData($dpid);
		}else{
			Yii::app()->redis->set($key,'0');
		}
	}
}