<?php
/**
 * Server.php
 * 本文件主要用于微信的验证-接收微信的消息
 * 
 * @property Integer $brandId 通过$_GET获取的品牌ID
 * @property String $token 通过品牌ID，查询数据库获得的品牌的token,用于跟微信通信验证
 * @property String $weixinUserName 由微信发送过来的数据提取出的普通微信用户的用户名
 * @property Array $brand 根据$brandId查询
 * @property Mixed $sceneId 场景值，默认为null
 */

class Server {
	public $brandId;
    public $token;
    
    /**
     * 初始化
     * 公众帐号接口信息中URL
     * 通过brandId,我们连接数据库查处出该品牌的token
     */
    public function __construct($brandId) {
    	$this->brandId = $brandId;
        if(isset($_GET['echostr'])){
        	$this->token();
        	$this->checkSignature();
       	 	$this->joinWeixinServer();
        }
        $this->postArr();
        $this->brandUser();
        $this->responseMsg();
    }
    
     /**
     * 通过$this->brandId的值，从数据库yk_public_account获取该品牌的 token
     * 因Token参与到加密算法，是区分大小写的，因此TOKEN全部为大写，小写当然也是可以的，规定大写用来避免混乱问题。
     */
    public function token() {
        $this->token = Token::get($this->brandId);
    }
   /**
     * 微信通信验证
     */
	private function checkSignature() {
		$tmpArr = array($this->token, $_GET['timestamp'], $_GET['nonce']);
		sort($tmpArr, SORT_STRING);
		$tmpStr = sha1( implode( $tmpArr ) );	
		if( $tmpStr != $_GET['signature'] )
			throw new Exception('通信不合法');
	}
    
    /**
     * 返回$_GET['echostr'],用于和微信服务器通信接入验证
     */
    public function joinWeixinServer() {
    	if(!empty($_GET['echostr'])) {
    		echo $_GET['echostr'];
       	 	exit;
    	}
    }

	/**
	 * 获取微信服务器传递过来的数据信息
	 * get post data, May be due to the different environments
	 */
	public function postArr() {
		$this->postArr = XML::getPostArr();
	}
	 /**
     * 通过$this->postArr['FromUserName']获取用户的信息 yk_brand_user
     * 注意：此处并没有注册新用户，新用户通过subscribe注册
     * 此处memcache()方法是设置用于登录的$this->login
     */
    public function brandUser() {
        $sql = 'select * from nb_brand_user where openid = "' . $this->postArr['FromUserName'] . '"';
        $this->brandUser = Yii::app()->db->createCommand($sql)->queryRow();
        $this->userId = $this->brandUser ? $this->brandUser['lid'] : null;
    }    
	/**
	 * 响应微信服务器
	 */
    public function responseMsg() {
    	//如果是even事件，则把小写的事件名称赋值给$this->event
    	$this->event = empty($this->postArr['Event']) ? null : strtolower($this->postArr['Event']);
      	//extract post data
		if (!empty($this->postArr)){
			$time = time();
            //添加关注，自动回复
            if($this->event == 'subscribe') {
            	$this->subscribe(); // 注册用户
                if(!empty($this->postArr['EventKey']) && (strpos($this->postArr['EventKey'], 'qrscene_')!==false)) {
                	$this->sceneRun();
                }else {
                	echo $this->generalResponse();
                }
            }
              
            //场景事件推送 此处注意参数必须加引号
			else if($this->event == 'scan'){
				$this->sceneRun();
			}
			
            
       		//取消关注，自动回复
			else if($this->event == 'unsubscribe') {
				$this->unsubscribe();
            }
            	
        	//存储用户地理位置信息
        	else if($this->event == 'location') {

            }
            
            //微信服务器事件推送群发结果，记录在数据库中
            else if($this->event == 'masssendjobfinish') {

            }
                
        	//菜单事件
        	else if($this->event == 'click') {
                 if($this->postArr['EventKey']=="click") {
                 	echo $this->generalResponse();
                 }
            }
            
            //微信卡券事件
        	else if($this->event == 'card_pass_check') {
				WxCardResult::cardPassCheck($this->postArr);
            }
            else if($this->event == 'card_not_pass_check') {
				WxCardResult::cardNotPassCheck($this->postArr);
            }
            else if($this->event == 'user_get_card') {
				WxCardResult::getCard($this->postArr,$this->brandId);
            }
            else if($this->event == 'user_del_card') {
				WxCardResult::delCard($this->postArr);
            }
                 
            // 消息回复,类型包括：文本、图片、语音、视频
			else if( !empty($this->postArr['MsgType']) && ($this->postArr['MsgType'] == 'text' || $this->postArr['MsgType'] == 'image' || $this->postArr['MsgType'] == 'voice' || $this->postArr['MsgType'] == 'video') ) {
  				if($this->postArr['MsgType'] == 'text'){
  					$data = array('dpid'=>$this->brandId,'user_id'=>$this->userId,'content'=>$this->postArr['Content']);
  					WxDiscuss::insert($data);
  				}
            }
    	}
        exit;
    }
    
    /**
     * 把场景回复集合在一起
     * 场景类型：
     * 1门店2打折3线上4线下5多倍6商品7渠道8微信墙
     * 注意：
     * 首先要写入记录到yk_scene_scan_log表，因为下面的输出终止程序的执行。
     */
  	public function sceneRun() {
  		$this->sceneId();
		$this->scene();
		$this->sceneScanLog();
	   	if(time() < $this->scene['expire_time']) {	//场景未过期
	   		// 推送消息：非门店场景推送场景消息
   			if($this->scene['type']){
   				echo $this->sceneResponse();
   			}
	   	}else	// 场景已过期
	   		$this->generalResponse();
  	}
    
    /**
     * 品牌一般通用回复
     * 如果品牌设置了关注推送，则优先采用
     * 否则，推送一条文本消息出去
     * @return String 
     */
	public function generalResponse() {
		$subPushs = array();
		$promotionPushs = WxPromotionActivity::getSubPush($this->brandId);
    	if(!empty($promotionPushs)){
    		foreach($promotionPushs as $push){
    			array_push($subPushs,array($push['activity_title'],$push['activity_memo'],'http://menu.wymenu.com'.$push['main_picture'],Yii::app()->createAbsoluteUrl('/mall/cupon',array('companyId'=>$this->brandId,'activeId'=>$push['lid']))));
    		}
    		return $this->news($subPushs);
    	}else{
    		array_push($subPushs,array('会员注册', 'comp_dpid', '恭喜你成为新会员,完善资料有惊喜哦', 'http://menu.wymenu.com/wymenuv2/img/pages/earth.jpg', Yii::app()->createAbsoluteUrl('user/index', array('companyId'=>$this->brandId))));
            return $this->news($subPushs);
    	}
	}
	
	/**
	 * 根据场景进行回复消息
	 */
	public function sceneResponse() {
		$subPushs = array();
		
		$tableArr = array(
			1=>array('serial', 'type_id','欢迎前来就餐', 'http://menu.wymenu.com/wymenuv2/img/pages/earth.jpg', 'nb_site', 'lid'),
			3=>array('company_name', 'comp_dpid', '恭喜你成为新会员,完善资料有惊喜哦', 'http://menu.wymenu.com/wymenuv2/img/pages/earth.jpg', 'nb_company'),
		);
		
		$sceneType = $this->scene['type'];
		
		$sql = 'SELECT '.$tableArr[$sceneType][0].' as title,'.$tableArr[$sceneType][1].', "'.$tableArr[$sceneType][2].'" as description, "'.$tableArr[$sceneType][3].'" as imgUrl FROM '.$tableArr[$sceneType][4].' WHERE dpid = ' .$this->brandId;
		if(isset($tableArr[$sceneType][5])){
			$sql.= ' AND '.$tableArr[$sceneType][5].' = ' .$this->scene['id'];
		}
		$query = Yii::app()->db->createCommand($sql)->queryRow();
		$query['description'] = mb_substr(preg_replace('/\s/', '', strip_tags($query['description'])), 0, 60, 'utf-8');

		if($query) { 
			$urlArr = array(
					1=>array('mall/index','companyId'),
					3=>array('user/index','companyId'),
			);
			$redirectUrl = Yii::app()->createAbsoluteUrl($urlArr[$sceneType][0], array($urlArr[$sceneType][1]=>$this->brandId));
			if($this->scene['type']==1){
				$sql = 'select * from nb_site_type where lid='.$query['type_id'].' and dpid='.$this->brandId;
				$siteType = Yii::app()->db->createCommand($sql)->queryRow();
				
				$typeName = isset($siteType['name'])?$siteType['name']:'';
				$siteArr = array('桌号:'.$typeName.$query['title'], $query['description'], $query['imgUrl'], $redirectUrl);
			}elseif ($this->scene['type']==3){
				if($this->brandUser['weixin_group']==0){
					$data = array('openid'=>$this->postArr['FromUserName'],'group'=>$this->scene['id']);
					WxBrandUser::updateByOpenid($data);
				}
				$siteArr = array('会员注册', $query['description'], $query['imgUrl'], $redirectUrl);
			}
			
			array_push($subPushs,$siteArr);
	    	
	    	 if(!empty($this->postArr['EventKey']) && (strpos($this->postArr['EventKey'], 'qrscene_')!==false)) {
	    	 	$promotionPushs = WxPromotionActivity::getSubPush($this->brandId);
	        	if(!empty($promotionPushs)){
	        		foreach($promotionPushs as $push){
	        			array_push($subPushs,array($push['activity_title'],$push['activity_memo'],'http://menu.wymenu.com'.$push['main_picture'],Yii::app()->createAbsoluteUrl('/mall/cupon',array('companyId'=>$this->brandId,'activeId'=>$push['lid']))));
	        		}
	        	}
	    	 }else{
	    	 	$promotionPushs = WxPromotionActivity::getScanPush($this->brandId);
		    	if(!empty($promotionPushs)){
		    		foreach($promotionPushs as $push){
		    			array_push($subPushs,array($push['activity_title'],$push['activity_memo'],'http://menu.wymenu.com'.$push['main_picture'],Yii::app()->createAbsoluteUrl('/mall/cupon',array('companyId'=>$this->brandId,'activeId'=>$push['lid']))));
		    		}
		    	}
	    	 }
			return $this->news($subPushs);
		}else{
			return $this->generalResponse();
		}
	}
	
    /**
     * 将通过以下两种方式的一种获取场景值
     * 如果用户还未关注公众号，则用户可以关注公众号，关注后微信会将带场景值关注事件推送给开发者。
	 * 如果用户已经关注公众号，则微信会将带场景值扫描事件推送给开发者。
     */
	public function sceneId() {
   		if(!empty($this->postArr['EventKey']) && (strpos($this->postArr['EventKey'], 'qrscene_')!==false)){
   			$this->sceneId = substr($this->postArr['EventKey'], 8);
   		}
   		else if($this->event == 'scan') {
   			$this->sceneId = $this->postArr['EventKey'];
   		}
	}
		
	/**
	 * 查询品牌场景关联的值
	 * @return Mixed array or null
	 */
	public function scene() {
		$sql = 'SELECT * FROM nb_scene WHERE scene_id = ' .$this->sceneId. ' AND dpid =' .$this->brandId;
		$this->scene = Yii::app()->db->createCommand($sql)->queryRow();
		if(!$this->scene)
			throw new Exception('该品牌没有此场景信息');
	}
    
    /**
     * 记录场景统计
     * 1.如果是渠道，同时插入到yk_promote_log表，因为scene_id日后可能不再关联这个渠道。
     */
	public function sceneScanLog() {
		if($this->sceneId) {
			$time = time();
			$se = new Sequence("scene_scan_log");
            $lid = $se->nextval();
            $isSync = DataSync::getInitSync();
			$sql = 'INSERT INTO nb_scene_scan_log(lid, dpid, create_at, update_at, scene_id, user_id, is_sync) VALUES(' . $lid . ','.$this->brandId.', "'.date('Y-m-d H:i:s',$time).'","'.date('Y-m-d H:i:s',$time).'",'.$this->sceneId.' , '.$this->userId.', '.$isSync.')';
			Yii::app()->db->createCommand($sql)->execute();
		}
	}
    

    /**
     * 当用户通过微信关注公众帐号
     * 当普通用户关注公众帐号时向数据库写入记录 用于区分微信用户的字段 weixin_user_name, 同一普通用户在不同公众号下的weixin_user_name不相同
     * 1.如果该普通用户之前没有关注公众帐号，则向数据库yk_brand_user写入一条新的用户记录
     * 2.如果该普通用户之前已经关注公众帐号，并且没有取消过关注unsubscribe=0,则不做记录
     * 3.如果该普通用户之前已经关注公众帐号，并且曾经取消过关注unsubscribe=1,则更新unsubscribe=0
     * @param String $weixinCode 普通用户微信号
     */
    public function subscribe() {
    	if($this->isFirstSubscribe()) {
    		$newBrandUser = new NewBrandUser($this->postArr['FromUserName'], $this->brandId);
    		$this->brandUser = $newBrandUser->brandUser;
    		$this->userId = $this->brandUser['lid'];
    	}else {
            if($this->brandUser['unsubscribe'])
            	$this->cancelUnsubscribe();
        }
    }
    
    /**
     * 判断用户是否为初次关注，也就是yk_brand_user中weixin_user_name字段值等于当前微信用户的weixin_user_name
     * @return Boolean 如果是如此关注返回true，否则返回失败
     */
    public function isFirstSubscribe() {
        return empty($this->brandUser) ? true : false ;
    }
    
    /**
     * 把普通用户对公众帐号的取消关注状态重新设置为关注状态 yk_brand_user unsubscribe = 0
     */
    public function cancelUnsubscribe() {
    	$isSync = DataSync::getInitSync();
    	$sql = 'update nb_brand_user set unsubscribe = 0,is_sync='.$isSync.' where openid = "' . $this->postArr['FromUserName'] .'"';
        Yii::app()->db->createCommand($sql)->execute();
    }
    
    /**
     * 当普通用户取消关注公众帐号时，需要在yk_brand_user中把unsubscribe设置为1
     */
    public function unsubscribe() {
    	$isSync = DataSync::getInitSync();
    	$sql = 'update nb_brand_user set unsubscribe = 1, unsubscribe_time =  ' . time() . ',is_sync='.$isSync.' where openid = "' . $this->postArr['FromUserName'] .'"';
        Yii::app()->db->createCommand($sql)->execute();
    }
    
	/**
	 * 回复文本消息
	 * 终止脚本执行
	 * @param String $content 文本内容
	 */
	public function text($content) {
        exit(ResponsePush::text($this->postArr['FromUserName'], $this->postArr['ToUserName'], $content));
	}
	
	/**
	 * 回复图文消息
	 * 未终止脚本执行
	 * @param Array $arr 二维数组，代表多条图文
	 * 注意：$arr的一维数组
	 * 可以是关联数组array('title'=>, 'description'=>, 'pic_url'=>, 'url'=>) 
	 * 或索引数组array(0=>, 1=>, 2=>, 3=>)
	 */
	public function news($arr) {
		exit(ResponsePush::news($this->postArr['FromUserName'], $this->postArr['ToUserName'], $arr, $this->brandId));
	}
	
}

?>