<?php 
/**
 * 
 * 
 * 消息模板类
 * 
 * $type 0 支付成功通知
 * 
 */
class WxMessageTpl
{
	
	public function __construct($dpid,$type,$data){
		$this->dpid = $dpid;
		$this->type = $type;
		$this->data = $data;
	}
	public function getMsgTpl(){
		$sql = 'select * from nb_discuss where dpid=:dpid and message_type=:type and delete_flag=0';
		$this->msgTpl = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$this->dpid)
				  ->bindValue(':type',$this->type)
				  ->queryRow();
	}
	public function getData(){
		
	}
	public function sent(){
		
	}
	
	
}