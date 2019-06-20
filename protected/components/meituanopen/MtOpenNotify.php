<?php
/**
* 
* $type 美团通知类型
* 
* 'new' 新订单推送
* 'confirm' 订单确认
* 
*/
class MtOpenNotify
{
	/**
	 * 通过回调函数 先返回结果
	 */
	public static function callUserFunc($callback,$type){
		Helper::writeLog(Yii::app()->request->getHostInfo().Yii::app()->request->url);
		$data = $_POST;
		if(empty($data)){
			$data = $_GET;
		}
		if(!empty($data)){
			$appid = $data['app_id'];
			$apppoicode = $data['app_poi_code'];
			$appidkey = 'mtwm-open-appid-'.$appid;
			$appsecret = Yii::app()->redis->get($appidkey);
			if(!$appsecret){
				$mtconfig = MtOpenUnit::getMtConfigByAppid($appid);
				$appsecret = $mtconfig['app_secret'];
				Yii::app()->redis->set($appidkey,$appsecret);
			}
			$hasSig = MtOpenUnit::checkSign($type, $data, $appsecret);
			if($hasSig){
				return call_user_func($callback,$data);
			}
		}
		return true;
	}
	public function Handle($type)
	{
		if($type=='new'){
			$result = self::callUserFunc(array($this, 'newOrderCallBack'),$type);
		}elseif($type=='confirm'){
			$result = self::callUserFunc(array($this, 'confirmOrderCallBack'),$type);
		}elseif($type=='cancel'){
			$result = self::callUserFunc(array($this, 'cancelOrderCallBack'),$type);
		}elseif($type=='shipper'){
			$result = self::callUserFunc(array($this, 'shipperOrderCallBack'),$type);
		}elseif($type=='reminder'){
			$result = self::callUserFunc(array($this, 'reminderOrderCallBack'),$type);
		}elseif($type=='refund'){
			$result = self::callUserFunc(array($this, 'refundOrderCallBack'),$type);
		}elseif($type=='privacynumber'){
			$result = self::callUserFunc(array($this, 'privacyNumberCallBack'),$type);
		}
		if($result){
			$this->ReplyNotify(true);
		}else{
			$this->ReplyNotify(false);
		}
	}
	/**
	 * 推送订单
	 */
	public function newOrderCallBack($data){
		$remt = MtOpenOrder::order($data);
		return $remt;
	}
	/**
	 * 确认订单
	 */
	public function confirmOrderCallBack($data){
		Helper::writeLog('confirm:'.json_encode($data));
		$remt = MtOpenOrder::orderconfirm($data);
		return $remt;
	}
	/**
	 * 取消订单
	 */
	public function cancelOrderCallBack($data){
		Helper::writeLog('cancel:'.json_encode($data));
		$remt = MtOpenOrder::orderCancel($data);
		return $remt;
	}
	/**
	 * 订单配送
	 */
	public function shipperOrderCallBack($data){
		Helper::writeLog('shipper:'.json_encode($data));
		$remt = MtOpenOrder::orderCancel($data);
		return $remt;
	}
	/**
	 * 催单
	 */
	public function reminderOrderCallBack($data){
		Helper::writeLog('reminder:'.json_encode($data));
		$remt = MtOpenOrder::orderReminder($data);
		return $remt;
	}
	/**
	 * 美团用户或客服退款流程操作
	 */
	public function refundOrderCallBack($data){
		Helper::writeLog('refund:'.json_encode($data));
		$remt = MtOpenOrder::orderRefund($data);
		return $remt;
	}
	/**
	 * 隐私号降级推送
	 */
	public function privacyNumberCallBack($data){
		$remt = MtOpenOrder::privacyNumber(json_encode($data));
		return $remt;
	}
	private function ReplyNotify($status = true)
	{
		if($status){
			echo '{ "data": "ok"}';
		}else {
			echo '{ "data": "ng"}';
		}	
	}
}