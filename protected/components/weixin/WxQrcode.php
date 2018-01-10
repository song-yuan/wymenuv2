<?php
/*
 * Created on 2014-2-11
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class WxQrcode {
	const SITE_QRCODE = 1; // 座位
	const SCREEN_QRCODE = 2; //大屏幕
	const COMPANY_QRCODE = 3; // 店铺

	
	public $db;
	public $account;
	
	public function __construct($brandId){
		$this->db = Yii::app()->db;
		$this->brandId = $brandId;
		$this->getWxAccount($this->brandId );
	}
	public function getWxAccount($brandId){
		$this->account = WxAccount::get($this->brandId);
	}
	/**
	 * 获取场景ID
	 */
	public function getSceneId($type,$id,$expireTime = null){
		$scene = WxScene::get($this->brandId,$type,$id);
		$sceneId = $scene?$scene['scene_id']:false;
		if($sceneId){
			WxScene::update($scene['lid'],$scene['dpid'],$expireTime);
			return $sceneId;
		}else{
		    $sql ='select max(scene_id) as maxId from nb_scene where dpid = '.$this->brandId;
			$maxSceneArr = $this->db->createCommand($sql)->queryRow();
				
			$maxSceneId = $maxSceneArr['maxId'];
			$sceneId = $maxSceneId+1;
				
			$time = time();
			$isSync = DataSync::getInitSync();
			$se=new Sequence("scene");
            $lid = $se->nextval();
            
			$sceneData = array('lid'=>$lid,'dpid'=>$this->brandId,'create_at'=>date('Y-m-d H:i:s',$time),'update_at'=>date('Y-m-d H:i:s',$time),'scene_id'=>$sceneId,'type'=>$type,'id'=>$id,'expire_time'=>$expireTime,'is_sync'=>$isSync);
			WxScene::insert($sceneData);				
		}
		return $sceneId.'-'.$this->brandId;
	}
	/**
	 * 生成限制二维码
	 */
	public function getLimitQrcodeTicket($sceneId){
		 $accessTokenObj = new AccessToken($this->brandId);
         $accessToken = $accessTokenObj->accessToken;
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
   		$path = Yii::app()->basePath.'/../uploads';
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
