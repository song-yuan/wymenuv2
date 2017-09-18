<?php
/**
* 
* $type 美团通知类型
* 
* 'new' 新订单推送
* 'confirm' 订单确认
* 
*/
class MtNotify
{
	public function Handle($type)
	{
		if($type=='new'){
			$result = MtOrder::callUserFunc(array($this, 'newOrderCallBack'));
		}else if($type=='confirm'){
			$result = MtOrder::callUserFunc(array($this, 'confirmOrderCallBack'));
		}
		if($result == false){
			$this->ReplyNotify(false);
			return;
		}
		$this->ReplyNotify(true);
	}
	public function newOrderCallBack($data){
		$remt = MtOrder::order($data);
		return $remt;
	}
	public function confirmOrderCallBack($data){
		$remt = MtOrder::orderconfirm($data);
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