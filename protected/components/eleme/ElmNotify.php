<?php
/**
* 
* $type 饿了么通知类型
* 
* 'new' 新订单推送
* 'confirm' 订单确认
* 
*/
class ElmNotify
{
	public function Handle($type)
	{
		$result = Elm::callUserFunc(array($this, 'elmOrderCallBack'));
		if($result){
			$this->ReplyNotify(true);
			return;
		}
		$this->ReplyNotify(false);
	}
	public function elmOrderCallBack($data){
		$remt = Elm::dealElmData($data);
		return $remt;
	}
	private function ReplyNotify($status = true)
	{
		if($status){
			echo '{"message":"ok"}';
		}else {
			echo '{"message":"error"}';
		}	
	}
}