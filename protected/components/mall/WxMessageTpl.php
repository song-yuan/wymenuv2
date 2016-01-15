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
	
	public function __construct($dpid,$userId,$type,$data){
		$this->dpid = $dpid;
		$this->userId = $userId;
		$this->type = $type;
		$this->data = $data;
	}
	public function getMsgTpl(){
		$sql = 'select * from nb_weixin_messagetpl where dpid=:dpid and message_type=:type and delete_flag=0';
		$this->msgTpl = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$this->dpid)
				  ->bindValue(':type',$this->type)
				  ->queryRow();
	}
	public function getData(){
		$openId = '';
		$msgTplId = '';
		$url = '';
		
		$megTplData = array(
			array(
				'touser'=>$openId,
	            'template_id'=>$msgTplId,
	            'url'=>$this->createAbsoluteUrl('/user/orderInfo',array('companyId'=>$this->dpid,'orderId'=>$this->data['lid'])),
	            'topcolor'=>'#FF0000',
	            'data' => array(
	                'first'=>array(
	                    'value'=>'您好，您已支付成功订单',
	                    'color'=>'#0A0A0A',
	                ),
	                'product'=>array(
	                    'value'=>'点餐订单',
	                    'color'=>'#CCCCCC',
	                ),
	                'price'=>array(
	                    'value'=>$this->data['should_total'],
	                    'color'=>'#CCCCCC',
	                ),
	                'time'=>array(
	                    'value'=>time(),
	                    'color'=>'#CCCCCC',
	                ),
	                'remark'=>array(
	                    'value'=>'正在尽快给您出菜~',
	                    'color'=>'#173177',
	                )
	            )
			),
		);
		
	}
	public function sent(){
		
	}
	
	
}