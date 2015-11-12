<?php
/*
 * Created on 2013-12-12
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class WxSdk {
	 private $appid = "";
     private $appsecret = "";
     private $brandId;
 
     //构造函数，获取Access Token
     public function __construct($brandId = 0)
     {
     	 $this->brandId=$brandId;
        
         $sql = 'SELECT * FROM yk_weixin_service_account WHERE brand_id = ' .$this->brandId;
		 $weiXinServiceAccount = Yii::app()->db->createCommand($sql)->queryRow();
		 $this->appid = trim($weiXinServiceAccount['appid']);
         $this->appsecret = trim($weiXinServiceAccount['appsecret']);
		 if(!$weiXinServiceAccount)
			throw new Exception('没有该品牌的服务号');
         if(time() > $weiXinServiceAccount['expire']){
         	$this->access_token = $this->newAccessToken();
         }else{
			$this->access_token = $weiXinServiceAccount['access_token'];
         }
           
     }
     protected function newAccessToken() {
		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' .$this->appid. '&secret=' .$this->appsecret;
		
		if($weixinServerReturn = $this->https_request($url)) {
			$accessTokenStdClass = json_decode($weixinServerReturn);
			if(isset($accessTokenStdClass->access_token)){
				$sql = 'UPDATE yk_weixin_service_account set expire = ' . strtotime('2 hours'). ', access_token = "' .$accessTokenStdClass->access_token . '"
						WHERE brand_id = ' .$this->brandId;
				Yii::app()->db->createCommand($sql)->execute();
				return $accessTokenStdClass->access_token;
			}else{
				throw new Exception('从微信服务器获取access_token的失败重新获取');
			}
		}else
			throw new Exception('无法从微信服务器获取access_token的信息');
	}
	 //创建菜单
     public function create_menu($data)
     {
         $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this->access_token;
         $res = $this->https_request($url, $data);
         return json_decode($res, true);
     }
      //获取access_token
     public function getAccessToken()
     {
         return $this->access_token;
     }
    //创建分组
    public function createGroup($name)
    {
        $data = '{"group": {"name": "'.$name.'"}}';
        $url = "https://api.weixin.qq.com/cgi-bin/groups/create?access_token=".$this->access_token;
        $res = $this->https_request($url, $data);
        return json_decode($res, true);
    }
    //移动用户分组
    public function updateGroup($openid, $to_groupid)
    {
        $data = '{"openid":"'.$openid.'","to_groupid":'.$to_groupid.'}';
        $url = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=".$this->access_token;
        $res = $this->https_request($url, $data);
        return json_decode($res, true);
    }
    //查询用户分组
     public function queryGroup()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/groups/get?access_token=".$this->access_token;
        $res = $this->https_request($url);
        return json_decode($res, true);
    }
     //重命名用户分组
    public function renameGroup($id, $name)
    {
        $data = '{"group":{"id":'.$id.',"name":"'.$name.'"}}';
        $url = "https://api.weixin.qq.com/cgi-bin/groups/update?access_token=".$this->access_token;
        $res = $this->https_request($url, $data);
        return json_decode($res, true);
    }
	//https请求（支持GET和POST）
     public function https_request($url, $data = null)
     {
        $ch = curl_init();									// 创建一个新cURL资源
		curl_setopt($ch, CURLOPT_URL, $url);				// 需要获取的URL地址，也可以在curl_init()函数中设置
		curl_setopt($ch, CURLOPT_HEADER, false);			// 不启用头文件的信息作为数据流输出
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);		// 将curl_exec()获取的信息以文件流的形式返回，而不是直接输出
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);	// https注意需要设置关于SSL的opition
		if(!empty($data)){
			curl_setopt($ch, CURLOPT_POST, true);				// 启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);	// 传递post数据 
		}	
		$returnTransfer = curl_exec($ch);					// 因设置CURLOPT_RETURNTRANSFER为TURE，curl_exec()返回获取的内容
		curl_close($ch);									// 关闭cURL资源
		return $returnTransfer;	

     }
} 
?>
