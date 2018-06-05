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
			$this->ReplyNotify(true);
			$result = MtOrder::callUserFunc(array($this, 'newOrderCallBack'));
		}elseif($type=='confirm'){
			$result = MtOrder::callUserFunc(array($this, 'confirmOrderCallBack'));
			if($result){
				$this->ReplyNotify(true);
				return;
			}
			$this->ReplyNotify(false);
		}
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