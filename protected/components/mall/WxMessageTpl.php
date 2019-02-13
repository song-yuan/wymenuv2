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
		$this->dpids = WxCompany::getCompanyDpid($dpid);
		$this->type = $type;
		$this->data = $data;
		$this->getMsgTpl();
		$this->getData();
	}
	public function getMsgTpl(){
		$sql = 'select * from nb_weixin_messagetpl where dpid in(:dpid) and message_type=:type and delete_flag=0';
		$this->msgTpl = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$this->dpids.','.$this->dpid)
				  ->bindValue(':type',$this->type)
				  ->queryRow();
	}
	public function getData(){
		if(!$this->msgTpl){
			return;
		}
		$accessToken = new AccessToken($this->dpid);
		$access_token = $accessToken->accessToken;
		$msgTplId = $this->msgTpl['message_tpl_id'];
		
		foreach ($this->data as $data){
			$megTplData = array(
					'touser'=>$data['touser'],
					'template_id'=>$msgTplId,
					'url'=>$data['url'],
					'data' => array(
							'first'=>array(
									'value'=>$data['first'],
									'color'=>'#0A0A0A',
							),
							'keyword1'=>array(
									'value'=>'',
									'color'=>'#0A0A0A',
							),
							'keyword2'=>array(
									'value'=>'',
									'color'=>'#0A0A0A',
							),
							'keyword3'=>array(
									'value'=>'',
									'color'=>'#0A0A0A',
							),
							'keyword4'=>array(
									'value'=>'',
									'color'=>'#0A0A0A',
							),
							'remark'=>array(
									'value'=>$data['remark'],
									'color'=>'#173177',
							)
					)
			);
			if($data['keyword1']){
				$megTplData['data']['keyword1']['value'] = $data['keyword1'];
			}else{
				unset($megTplData['data']['keyword1']);
			}
			
			if($data['keyword2']){
				$megTplData['data']['keyword2']['value'] = $data['keyword2'];
			}else{
				unset($megTplData['data']['keyword2']);
			}
			
			if($data['keyword3']){
				$megTplData['data']['keyword3']['value'] = $data['keyword3'];
			}else{
				unset($megTplData['data']['keyword3']);
			}
			
			if($data['keyword4']){
				$megTplData['data']['keyword4']['value'] = $data['keyword4'];
			}else{
				unset($megTplData['data']['keyword4']);
			}
			$this->sent($access_token,$megTplData);
		}
	}
	public function sent($access_token,$megTplData){
		$tplUrl = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token;
		Curl::httpsRequest($tplUrl, json_encode($megTplData));
	}
	
	
}