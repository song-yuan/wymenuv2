<?php
/*
 * Created on 2013-12-12
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class WxSdk {
     //构造函数，获取Access Token
     public function __construct($brandId = 0)
     {
     	 $accessToken = new AccessToken($brandId);
         $this->access_token = $accessToken->accessToken;
     }
	 //创建菜单
     public function createMenu($data)
     {
         $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this->access_token;
         $res = Curl::httpsRequest($url, $data);
         return json_decode($res, true);
     }
    //创建分组
    public function createGroup($name)
    {
        $data = '{"group": {"name": "'.$name.'"}}';
        $url = "https://api.weixin.qq.com/cgi-bin/groups/create?access_token=".$this->access_token;
        $res = Curl::httpsRequest($url, $data);
        return json_decode($res, true);
    }
    //移动用户分组
    public function updateGroup($openid, $to_groupid)
    {
        $data = '{"openid":"'.$openid.'","to_groupid":'.$to_groupid.'}';
        $url = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=".$this->access_token;
        $res = Curl::httpsRequest($url, $data);
        return json_decode($res, true);
    }
    //查询用户分组
     public function queryGroup()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/groups/get?access_token=".$this->access_token;
        $res = Curl::httpsRequest($url);
        return json_decode($res, true);
    }
     //重命名用户分组
    public function renameGroup($id, $name)
    {
        $data = '{"group":{"id":'.$id.',"name":"'.$name.'"}}';
        $url = "https://api.weixin.qq.com/cgi-bin/groups/update?access_token=".$this->access_token;
        $res = Curl::httpsRequest($url, $data);
        return json_decode($res, true);
    }
} 
?>
