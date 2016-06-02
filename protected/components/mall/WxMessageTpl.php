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
		$accessToken = new AccessToken($dpid);
        $this->access_token = $accessToken->accessToken;
		$this->getMsgTpl();
		$this->getData();
		$this->sent();
	}
	public function getMsgTpl(){
		$sql = 'select * from nb_weixin_messagetpl where dpid=:dpid and message_type=:type and delete_flag=0';
		$this->msgTpl = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$this->dpid)
				  ->bindValue(':type',$this->type)
				  ->queryRow();
		if(!$this->msgTpl){
			return ;
		}
	}
	public function getData(){
		$company = WxCompany::get($this->dpid);
		$user = WxBrandUser::get($this->userId,$this->dpid);
		
		if(!$user){
			$this->megTplData = array(array());
			return;
		}
		$openId = $user['openid'];
		$msgTplId = $this->msgTpl['message_tpl_id'];
		
		$this->megTplData = array(
			array(
				'touser'=>$openId,
	            'template_id'=>$msgTplId,
	            'url'=>Yii::app()->createAbsoluteUrl('/user/orderInfo',array('companyId'=>$this->dpid,'orderId'=>$this->data['lid'])),
	            'data' => array(
	                'first'=>array(
	                    'value'=>'您好，您已支付成功订单',
	                    'color'=>'#0A0A0A',
	                ),
	                'keyword1'=>array(
	                    'value'=>$this->data['lid'],
	                    'color'=>'#0A0A0A',
	                ),
	                'keyword2'=>array(
	                    'value'=>$this->data['should_total'].'元',
	                    'color'=>'#FF0000',
	                ),
	                'keyword3'=>array(
	                    'value'=>$company['company_name'],
	                    'color'=>'#0A0A0A',
	                ),
	                'keyword4'=>array(
	                    'value'=>date('Y-m-d H:i:s',time()),
	                    'color'=>'#0A0A0A',
	                ),
	                'remark'=>array(
	                    'value'=>'小二正在尽快给您出菜~请耐心等候~',
	                    'color'=>'#173177',
	                )
	            )
			),
		);
		
	}
	public function sent(){
		$tplUrl = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->access_token;
		Curl::httpsRequest($tplUrl, json_encode($this->megTplData[$this->type]));
	}
	
	
}