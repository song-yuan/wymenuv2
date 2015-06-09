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
    public $token = 'zhouchao';
    
    /**
     * 初始化
     * 公众帐号接口信息中URL
     * 通过brandId,我们连接数据库查处出该品牌的token
     */
    public function __construct() {
        $this->checkSignature();
        $this->joinWeixinServer();
        $this->postArr();
        $this->responseMsg();
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
                if(!empty($this->postArr['EventKey']) && (strpos($this->postArr['EventKey'], 'qrscene_')!==false)) {
                	
                }else 
                	echo $this->generalResponse();
            }
              
            //场景事件推送 此处注意参数必须加引号
			else if($this->event == 'scan')
				exit;
            
       		//取消关注，自动回复
			else if($this->event == 'unsubscribe') {

            }
            	
        	//存储用户地理位置信息
        	else if($this->event == 'location') {

            }
            
            //微信服务器事件推送群发结果，记录在数据库中
            else if($this->event == 'masssendjobfinish') {

            }
                
        	//菜单事件
        	else if($this->event == 'click') {
                 if($this->postArr['EventKey']=="MENUMAIN") {
                 	echo $this->generalResponse();
                 }
            }
            
                 
            // 消息回复,类型包括：文本、图片、语音、视频
			else if( !empty($this->postArr['MsgType']) && ($this->postArr['MsgType'] == 'text' || $this->postArr['MsgType'] == 'image' || $this->postArr['MsgType'] == 'voice' || $this->postArr['MsgType'] == 'video') ) {
  				
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
	   		// 在场景有效时，并且之前无记录来源时，更新用户场景信息
   			if(!$this->brandUser['scene_type'])	// 通过此判断之前是否有来源
   				$this->updateUserScene();
   			// 如果场景是门店，且用户没有来源门店，则此门店作为用户来源门店
   			if($this->scene['type']==1 && !$this->brandUser['from_shop'])
   				$this->updateUserFromShop();
	   	
	   		// 推送消息：非门店场景推送场景消息
   			if($this->scene['type'] > 1 && $this->scene['type'] <= 11 ){
   				echo $this->sceneResponse();
   			}
   			else
   				$this->generalResponse();
   			
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
    		$this->text('欢迎关注我要点单官方微信！');
	}
	
	/**
	 * 根据场景进行回复消息
	 */
	public function sceneResponse() {
		$tableArr = array(
			2=>array('title', 'intro', 'pic', 'yk_discount', 'discount_id'),
			3=>array('title', 'intro', 'pic', 'yk_online_activity', 'id'),
			4=>array('title', 'intro', 'pic', 'yk_offline_activity', 'id'),
			5=>array('title', 'intro', 'pic', 'yk_multipoint', 'multipoint_id'),
			6=>array('goods_name', 'goods_intro', 'goods_pic_small', 'yk_goods', 'goods_id'),
			7=>array('channel_name', 'content', 'pic', 'yk_promote', 'promote_id'),
			8=>array('title', 'intro', 'pic', 'yk_wall', 'wall_id'),
			9=>array('name', 'name', 'banner_img', 'yk_second_kill', 1),
			11=>array('name', 'depict', 'banner_img', 'yk_bespeak', 'bespeak_id'),
		);
		$sceneType = $this->scene['type'];
		$sql = 'SELECT '.$tableArr[$sceneType][0].' as title, '.$tableArr[$sceneType][1].' as description, '.$tableArr[$sceneType][2].' as imgUrl FROM '.$tableArr[$sceneType][3].' WHERE brand_id = ' .$this->brandId. ' AND '.$tableArr[$sceneType][4].' = ' .$this->scene['id'];
		$query = Yii::app()->db->createCommand($sql)->queryRow();
		$query['description'] = mb_substr(preg_replace('/\s/', '', strip_tags($query['description'])), 0, 60, 'utf-8');

		if($query && $sceneType != 1) { 
			$urlArr = array(
				2=>array('market/brands/discountlistinfo', 'discountId'),
				3=>array('market/brands/online', 'onlineId'),
				4=>array('market/brands/offlinelistinfo', 'offlineId'),
				5=>array('market/brands/multipointlistinfo', 'multipointId'),
				6=>array('member/brand/goods', 'goodsId'),
				7=>array('member/brand/promote', 'promoteId'),
				8=>array('monitor/mobile/getinfo', 'simulate'),
				9=>array('member/brand/secondkill', 'scenceId'),
				11=>array('market/brands/bespeak', 'bespeak_id'),
			);
			if($sceneType == 8) {
				JoinWall::insert($this->scene['id'], $this->userId);
				return $this->text($query['description']);
			}
			$redirectUrl = Yii::app()->createAbsoluteUrl($urlArr[$sceneType][0], array($urlArr[$sceneType][1]=>$this->scene['id']));
			return $this->news(array($query['title'], $query['description'], $query['imgUrl'], $redirectUrl));
		}else
			return $this->generalResponse();
	}
	
    /**
     * 将通过以下两种方式的一种获取场景值
     * 如果用户还未关注公众号，则用户可以关注公众号，关注后微信会将带场景值关注事件推送给开发者。
	 * 如果用户已经关注公众号，则微信会将带场景值扫描事件推送给开发者。
     */
	public function sceneId() {
   		if(!empty($this->postArr['EventKey']) && (strpos($this->postArr['EventKey'], 'qrscene_')!==false))
    		$this->sceneId = substr($this->postArr['EventKey'], 8);
   		else if($this->event == 'scan') 
   			$this->sceneId = $this->postArr['EventKey'];
	}
		
	/**
	 * 查询品牌场景关联的值
	 * @return Mixed array or null
	 */
	public function scene() {
		$sql = 'SELECT * FROM yk_scene 
				WHERE scene_id = ' .$this->sceneId. '
				AND brand_id =' .$this->brandId;
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
			$sql = 'INSERT INTO yk_scene_scan_log(scene_id, brand_id, user_id, create_time, update_time) VALUES('.$this->sceneId.', '.$this->brandId.', '.$this->userId.', '.$time.', '.$time.')';
			Yii::app()->db->createCommand($sql)->execute();
			
			/* 如果此时（有时效性）数据库记录的场景类型是渠道，则向渠道记录表插入记录
			 * 同时需要判断，基本有微信推送过来sceneId，但可能我们数据库已经不存在该场景记录，因此需进行empty判断
			 * 同时给yk_promote表，scan_times加1
			 */
			if(!empty($this->scene['type']) && $this->scene['type']==7) {
				$insertArr = array(
								'brand_id'=>$this->brandId,
								'promote_id'=>$this->scene['id'],
								'user_id'=>$this->userId,
								'create_time'=>$time,
								'update_time'=>$time,
							 );
				Yii::app()->db->createCommand()->insert('yk_promote_log', $insertArr);
				
				$sql = 'UPDATE yk_promote SET scan_times = scan_times + 1
						WHERE brand_id = ' .$this->brandId. '
						AND promote_id = ' .$this->scene['id'];
				Yii::app()->db->createCommand($sql)->execute();
			}
			
		}
	}
    
    /**
     * 记录会员门店归属和场景来源
     * 如果场景是门店的时候特别注意要同时设置会员来源门店和默认门店。
     * 同时unsubscribe置0
     */
   public function updateUserScene() {
		$updateArr = array(
			'scene_type'=>$this->scene['type'],
			'scene_value'=>$this->scene['id'],		
			'unsubscribe'=>0,						// 关注置0
			'update_time'=>time(),					// 修改时间
		);
		if($this->scene['type']==1)	// 如果是门店类型，则同时设置会员来源门店和默认门店。
			$updateArr = array_merge($updateArr, array('from_shop'=>$this->scene['id'], 'default_shop'=>$this->scene['id']));
		Yii::app()->db->createCommand()->update('yk_brand_user', $updateArr, 'id='.$this->userId);			
   }
   
	/**
     * 如果场景是门店，且用户没有来源门店，则此门店作为用户来源门店
     */
  	public function updateUserFromShop() {
		$updateArr = array(
			'from_shop'=>$this->scene['id'],		// 来源门店
			'default_shop'=>$this->scene['id'],		// 默认门店
			'unsubscribe'=>0,						// 关注置0
			'update_time'=>time(),					// 修改时间
		);
		Yii::app()->db->createCommand()->update('yk_brand_user', $updateArr, 'id='.$this->userId);	
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
    		$this->userId = $this->brandUser['id'];
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
        $this->brandUser();
        return empty($this->brandUser) ? true : false ;
    }
    
    /**
     * 把普通用户对公众帐号的取消关注状态重新设置为关注状态 yk_brand_user unsubscribe = 0
     */
    public function cancelUnsubscribe() {
    	$sql = 'update yk_brand_user set unsubscribe = 0 where weixin_user_name = "' . $this->postArr['FromUserName'] .'"';
        Yii::app()->db->createCommand($sql)->execute();
    }
    
    /**
     * 当普通用户取消关注公众帐号时，需要在yk_brand_user中把unsubscribe设置为1
     */
    public function unsubscribe() {
    	$sql = 'update yk_brand_user set unsubscribe = 1, unsubscribe_time =  ' . time() . ' where weixin_user_name = "' . $this->postArr['FromUserName'] .'"';
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