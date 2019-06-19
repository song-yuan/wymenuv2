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
	public static function callUserFunc($callback){
		$data = file_get_contents('php://input');
		if(empty($data)){
			return true;
		}
		return call_user_func($callback,$data);
	}
	public function Handle($type)
	{
		if($type=='new'){
			$this->ReplyNotify(true);
			$result = self::callUserFunc(array($this, 'newOrderCallBack'));
		}elseif($type=='confirm'){
			$result = self::callUserFunc(array($this, 'confirmOrderCallBack'));
		}elseif($type=='cancel'){
			$result = self::callUserFunc(array($this, 'cancelOrderCallBack'));
		}elseif($type=='reminder'){
			$result = self::callUserFunc(array($this, 'reminderOrderCallBack'));
		}elseif($type=='refund'){
			$result = self::callUserFunc(array($this, 'refundOrderCallBack'));
		}elseif($type=='privacynumber'){
			$result = self::callUserFunc(array($this, 'privacyNumberCallBack'));
		}
		if($result){
			$this->ReplyNotify(true);
			return;
		}
		$this->ReplyNotify(false);
	}
	/**
	 * 推送订单
	 */
	public function newOrderCallBack($data){
		Helper::writeLog('new:'.$data);
		$remt = MtOpenOrder::order($data);
		return $remt;
	}
	/**
	 * 确认订单
	 */
	public function confirmOrderCallBack($data){
		Helper::writeLog('confirm:'.$data);
		$remt = MtOpenOrder::orderconfirm($data);
		return $remt;
	}
	/**
	 * 取消订单
	 */
	public function cancelOrderCallBack($data){
		Helper::writeLog('cancel:'.$data);
		$remt = MtOpenOrder::orderCancel($data);
		return $remt;
	}
	/**
	 * 催单
	 */
	public function reminderOrderCallBack($data){
		Helper::writeLog('reminder:'.$data);
		$remt = MtOpenOrder::orderReminder($data);
		return $remt;
	}
	/**
	 * 美团用户或客服退款流程操作
	 */
	public function refundOrderCallBack($data){
		Helper::writeLog('refund:'.$data);
		$remt = MtOpenOrder::orderRefund($data);
		return $remt;
	}
	/**
	 * 隐私号降级推送
	 */
	public function privacyNumberCallBack($data){
		$remt = MtOpenOrder::orderCancel($data);
		return $remt;
	}
	private function ReplyNotify($status = true)
	{
		if($status){
			echo '{ "data": "OK"}';
		}else {
			echo '{ "data": "ERROR"}';
		}	
	}
}