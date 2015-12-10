<?php
/*
 * Created on 2014-2-11
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class WxCardQrcode {
	public $db;
	public $account;
	public $brand;
	
	public function __construct(Brand $brand,$cardId = null){
		$this->db = Yii::app()->db;
		$this->brand = $brand;
		$this->cardId = $cardId;
		$this->getWxAccount($this->brand->brand_id);
	}
	public function getWxAccount($brandId){
		$this->account = WeixinServiceAccount::model()->find('brand_id=:brandId',array(':brandId'=>$this->brand->brand_id));
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
		$scene = Scene::model()->find('brand_id=:brandId and type=:type and id=:id',array(':brandId'=>$this->brand->brand_id,':type'=>$type,':id'=>$id));
		$sceneId = $scene?$scene->scene_id:false;
		if($sceneId){
			$scene->expire_time = $expireTime;
		    $scene->update();
			return $sceneId;
		}else{
				$sql ='select max(scene_id) as maxId from yk_scene where brand_id = '.$this->brand->brand_id;
				$maxSceneArr = $this->db->createCommand($sql)->queryRow();
				
				$maxSceneId = $maxSceneArr['maxId'];
				if($maxSceneId>=100000){
					$scene = Scene::model()->find('brand_id=:brandId and expire_time <:expire_time',array(':brandId'=>$this->brand->brand_id,':type'=>$type,':expire_time'=>time()));
					if($scene){
						$scene->saveAttributes(array('id'=>$id,'expire_time'=>$expireTime));
					}else{
						for($i=0;$i<50;$i++){
							$sql = 'select scene_id from yk_scene where brand_id = '.$this->brand->brand_id.'limit '.($i*2000).',2000';
							$allSceneId = $this->db->createCommand($sql)->queryAll();
							$id = 1;
							foreach($allSceneId as $value){
								if($value==$id){
									$id++;
								}else{
									$newSceneId = $id;
									break 2;
								}
							}
						}
					}
				}else{
					$newSceneId = $maxSceneId+1;
				}
				$scene = new Scene;
				$time = time();
				$scene->attributes = array('brand_id'=>$this->brand->brand_id,'scene_id'=>$newSceneId,'type'=>$type,'id'=>$id,'expire_time'=>$expireTime,'create_time'=>$time,'update_time'=>$time);
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
		$postdata = '{"action_name": "QR_CARD", "action_info": {"card": {"card_id": "'.$this->cardId.'","expire_seconds": "1800"，"is_unique_code": false ,"outer_id" : '.$sceneId.'}}}';
		$result = Curl::postHttps($limitTicketUrl,$postdata);
		$result = json_decode($result);
		if(isset($result->ticket)){
			return $result->ticket;
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
   		$path = Yii::app()->basePath.'/../upload';
   		if($this->brand->company_id){
   			$path .= '/company_'.$this->brand->company_id;
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
//		$sceneId = $this->getSceneId($type,$id,$expireTime);
		if($limit){
			$ticket = $this->getLimitQrcodeTicket(0);
		}else{
			$ticket = $this->getTmpQrcodeTicket(0);
		}
		if(!$ticket){
			return false;
		}
		$url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($ticket);
		$qrcodeContents = file_get_contents($url);
		$dir = $this->genDir();
		$dir = substr($this->genDir(),strpos($this->genDir(),'upload'));
		
		$fileName = $dir.'/'.Until::genFileName().'.jpg';
		
		file_put_contents($fileName,$qrcodeContents);
		return $fileName;
	}
	
	//设置卡券白名单
	public function setOpenUser($user){
		$accessToken = $this->genAccessToken();
		if(!$accessToken){
			return false;
		}
		$url = 'https://api.weixin.qq.com/card/testwhitelist/set?access_token='.$accessToken;
		$postdata = '{"username":["'.$user.'"]}';
		$result = Curl::postHttps($url,$postdata);
		$result = json_decode($result);
		return $result;
	}
} 
?>
