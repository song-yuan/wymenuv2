<?php
/*
 * Created on 2014-2-11
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class WxQrcode {
	const SITE_QRCODE = 1;

	
	public $db;
	public $account;
	
	public function __construct($brandId){
		$this->db = Yii::app()->db;
		$this->brandId = $brandId;
		$this->getWxAccount($this->brandId );
	}
	public function getWxAccount($brandId){
		$this->account = WeixinServiceAccount::model()->find('dpid=:brandId',array(':brandId'=>$this->brandId));
	}
	/**
	 * 生成access token
	 */
	public function genAccessToken(){
		$accessTokenUrl = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=APPID&secret=APPSECRET';
		if(!$this->account){
			return false;
		}
		if($this->isOverdue()){
			$accessTokenUrl = strtr($accessTokenUrl,array('APPID'=>$this->account['appid'],'APPSECRET'=>$this->account['appsecret']));
			$result = json_decode(file_get_contents($accessTokenUrl),true);
			if(!isset($result['access_token'])){
				return false;
			}
			$this->account->expire = time()+$result['expires_in'];
			$this->account->access_token = $result['access_token'];
			$this->account->save();
		}
		return $this->account->access_token;
	}
	/**
	 * token是否过期
	 */
	public function isOverdue(){
		return $this->account->expire < time();
	}
	/**
	 * 获取场景ID
	 */
	public function getSceneId($type,$id,$expireTime = null){
		$scene = Scene::model()->find('dpid=:brandId and type=:type and id=:id',array(':brandId'=>$this->brandId,':type'=>$type,':id'=>$id));
		$sceneId = $scene?$scene->scene_id:false;
		if($sceneId){
			$scene->expire_time = $expireTime;
		    $scene->update();
			return $sceneId;
		}else{
				$sql ='select max(scene_id) as maxId from nb_scene where dpid = '.$this->brandId;
				$maxSceneArr = $this->db->createCommand($sql)->queryRow();
				
				$maxSceneId = $maxSceneArr['maxId'];
				$newSceneId = $maxSceneId+1;
				
				$scene = new Scene;
				$time = time();
				$scene->attributes = array('dpid'=>$this->brandId,'scene_id'=>$newSceneId,'type'=>$type,'id'=>$id,'expire_time'=>$expireTime,'create_time'=>$time,'update_time'=>$time);
				$scene->save();				
		}
		return $scene->scene_id;
	}
	/**
	 * 生成限制二维码
	 */
	public function getLimitQrcodeTicket($sceneId){
		$accessToken = $this->genAccessToken();
		if(!$accessToken){
			return false;
		}
		$limitTicketUrl = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$accessToken;
		$postdata = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": '.$sceneId.'}}}';
		$options = array(   
	    	'http' => array(   
	      		'method' => 'POST',   
	      		'header' => 'Content-type:application/x-www-form-urlencoded',   
	      		'content' => $postdata,   
	      		'timeout' => 15 * 60 // 超时时间（单位:s）   
	    	)   
	  	);   
		$context = stream_context_create($options);   
		$result = json_decode(file_get_contents($limitTicketUrl, false, $context),true);
		$returnTickets = '';
		if(isset($result['ticket'])){
			return $result['ticket'];
		}else{
			return false;
		}
	}
	public function getTmpQrcodeTicket(){
		
	}
	/**
	 * 二维码存储路径
	 */
    public function genDir(){
   		$path = Yii::app()->basePath.'/wymenuv2/./uploads';
   		if($this->brandId){
   			$path .= '/company_'.$this->brandId;
   			if(!is_dir($path)){
   				mkdir($path, 0777,true);
   			}
			$path .= '/qrcode';
   			if(!is_dir($path)){
   				mkdir($path, 0777,true);
   			}
   		}
   		return $path;
    }
	public function getQrcode($type,$id,$expireTime = null,$limit = true){	
		$sceneId = $this->getSceneId($type,$id,$expireTime);
		if($limit){
			$ticket = $this->getLimitQrcodeTicket($sceneId);
		}else{
			$ticket = $this->getTmpQrcodeTicket($sceneId);
		}
		if(!$ticket){
			return false;
		}
		$url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($ticket);
		$qrcodeContents = file_get_contents($url);
		$dir = $this->genDir();
		$dir = substr($this->genDir(),strpos($this->genDir(),'upload'));
		
		$fileName = $dir.'/'.Helper::genFileName().'.jpg';
		
		file_put_contents($fileName,$qrcodeContents);
		return $fileName;
	}
} 
?>
