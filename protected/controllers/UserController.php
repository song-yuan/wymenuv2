<?php

class UserController extends Controller
{

	public $companyId;
	public $brandUser;
	public $company;
	public $layout = '/layouts/mallmain';
	
	
	public function init() 
	{
		$companyId = Yii::app()->request->getParam('companyId');
		$this->companyId = $companyId; // 需为总部CompanyId（或者填公众号店铺）
		$this->company = WxCompany::get($this->companyId);
	}
	
	public function beforeAction($actin){
		$dpidSelf = Yii::app()->session['dpid_self_'.$this->companyId];
		if($dpidSelf==1){
			$comdpid = $this->company['dpid'];
		}else{
			$comdpid = $this->company['comp_dpid'];
		}
		$userId = Yii::app()->session['userId-'.$comdpid];
		//如果微信浏览器
		if(Helper::isMicroMessenger()){
			if(empty($userId)){
				$url = Yii::app()->request->url;
				$this->redirect(array('/weixin/redirect','companyId'=>$this->companyId,'url'=>urlencode($url)));
				exit;
			}
			
			$this->brandUser = WxBrandUser::get($userId, $this->companyId);
			if(empty($this->brandUser)){
				$url = Yii::app()->request->url;
				$this->redirect(array('/weixin/redirect','companyId'=>$this->companyId,'url'=>urlencode($url)));
				exit;
			}
		}else{
			//pc 浏览
			$userId = 2146;
			$this->brandUser = WxBrandUser::get($userId, $this->companyId);
			$userId = $this->brandUser['lid'];
			$userDpid = $this->brandUser['dpid'];
			Yii::app()->session['userId-'.$userDpid] = $userId;
		}
		return true;
	}
        /**
         *
         * 
         * 新的微信端
         * 
         */ 
    public function actionIndex(){
        $upLev = false;
        
        //$user就是brand_user表里的一行

        $user = $this->brandUser;
        //$userId就是brand_user表里的lid
        $userId = $user['lid'];
        
        $result = WxBrandUser::getAllLevel($user['dpid']);
        $lid = 0;
        if($result){
            $count=count($result);
            if($user['consume_point_history'] >= $result[0]['min_total_points']){
                for($i=0;$i<$count;$i++){
                    if( ($user['consume_point_history'] >= $result[$i]['min_total_points']) && ($user['consume_point_history'] <= $result[$i]['max_total_points'])){
                       $lid = $result[$i]['lid'];                       
                       if($user['user_level_lid']!=$lid){
                           $upLev = true;
                           WxBrandUser::updateUserLevel($userId, $user['dpid'], $lid);
                        }    
                    }
                }
            }
        }else{
        	WxBrandUser::updateUserLevel($userId, $user['dpid'], $lid);
        }    
               
        $userLevel =  WxBrandUser::getUserLevel($lid,$user['dpid']); 
   
        $img = array();
        if($userLevel){
            $style_id=$userLevel['style_id'];
            $img = WxBrandUser::getCardImg($style_id,$user['dpid']);
        }
        
        $give = WxBrandUser::getFullGive($this->companyId);
        $minus = WxBrandUser::getFullMinus($this->companyId);

        $remainMoney =  WxBrandUser::getYue($userId,$user['dpid']);
        
        $brandUserAdmin = WxBrandUserAdmin::get($userId, $user['dpid']);
        $this->render('index',array(
                                'userId'=>$userId,
                                'companyId'=>$this->companyId,
                                'user'=>$user,
                                'userLevel'=>$userLevel,
                                'remainMoney'=>$remainMoney, 
                                'img'=>$img,
                                'give'=>$give,
                                'minus'=>$minus,
                                'upLev'=>$upLev,
                             	'brandUserAdmin'=>$brandUserAdmin
                                
                ));
	
    }  
    public function actionMoney(){
    	//$user就是brand_user表里的一行

        $user = $this->brandUser;
        //$userId就是brand_user表里的lid
        $userId = $user['lid'];
    	$remainMoey = WxBrandUser::getYue($userId, $this->companyId);
    	$comments = WxRecharge::getWxRechargeComment($this->companyId,2,2);
    	$rechargeRecords = WxRecharge::getRechargeRecord($this->companyId,$userId);
        $this->render('money',array('remainMoey'=>$remainMoey,'user'=>$user,'comments'=>$comments,'records'=>$rechargeRecords));
    } 
    public function actionPoint(){
        //$user就是brand_user表里的一行

        $user = $this->brandUser;
        //$userId就是brand_user表里的lid
        $userId = $user['lid'];
        $remain_points = WxPoints::getAvaliablePoints($userId,$user['dpid']);  
        $this->render('point',array( 
                     'remain_points' => $remain_points,
                ));
    }
    public function actionPointRecord(){
         //$user就是brand_user表里的一行

        $user = $this->brandUser;
        //$userId就是brand_user表里的lid
        $userId = $user['lid'];
        $points = WxPoints::getPoints($userId,$user['dpid']);
        $this->render('pointRecord',array(      
                     'points'=>$points
                ));
    }
    public function actionTicket(){
	        $user = $this->brandUser;
	        $userId = $user['lid'];
            $not_useds = WxCupon::getUserNotUseCupon($userId,$user['dpid']);
            $expires = WxCupon::getUserExpireCupon($userId,$user['dpid']);
            $useds = WxCupon::getUserUseCupon($userId,$user['dpid']);
            $this->render('ticket',array('companyId'=>$this->companyId,
                                        'not_useds'=>$not_useds,
                                        'expires'=>$expires,
                                        'useds'=>$useds
                    ));       
    }
     public function actionBill(){
        $user = $this->brandUser;
        $userId = $user['lid'];
        $card_id = $user['card_id'];
        $order_pay = WxBrandUser::getOrderPay($card_id,$user['dpid']);
        $this->render('bill',array(   
                                    'order_pay'=>$order_pay,
	                    )
                ); 
    }
        
	/**
	 * 
	 * 订单列表
	 * 
	 */
	public function actionOrderList()
	{
		//$user就是brand_user表里的一行

        $user = $this->brandUser;
        //$userId就是brand_user表里的lid
        $userId = $user['lid'];
        $cardId = $user['card_id'];
		$type = Yii::app()->request->getParam('t',0);
		$page = Yii::app()->request->getParam('p',1);
		
		$orderLists = WxOrder::getUserOrderList($userId,$cardId,$type,$page);
		$this->render('orderlist',array('companyId'=>$this->companyId,'models'=>$orderLists,'type'=>$type,'userId'=>$userId,'cardId'=>$cardId));
	}
	/**
	 * 
	 * 订单详情
	 * 
	 */
	public function actionOrderInfo()
	{
		$siteType = false;
		$address = false;
		$seatingFee = 0;
		$packingFee = 0;
		$freightFee = 0;
		
		$orderId = Yii::app()->request->getParam('orderId');
		$orderDpid = Yii::app()->request->getParam('orderDpid');
		$order = WxOrder::getOrder($orderId,$orderDpid);
		$site = $site = WxSite::get($order['site_id'],$orderDpid);
		if($site){
			$siteType = WxSite::getSiteType($site['type_id'],$orderDpid);
		}
		
		$orderProducts = WxOrder::getOrderProduct($orderId,$orderDpid);
		
		if(in_array($order['order_type'],array(2,3))){
			$address =  WxOrder::getOrderAddress($orderId,$orderDpid);
		}
		
		if(in_array($order['order_type'],array(1,3))){
			$seatingProducts = WxOrder::getOrderProductByType($orderId,$orderDpid,1);
			foreach($seatingProducts as $seatingProduct){
				$seatingFee += $seatingProduct['price']*$seatingProduct['amount'];
			}
		}else{
			$packingProducts = WxOrder::getOrderProductByType($orderId,$orderDpid,2);
			foreach($packingProducts as $packingProduct){
				$packingFee += $packingProduct['price']*$packingProduct['amount'];
			}
			$freightProducts = WxOrder::getOrderProductByType($orderId,$orderDpid,3);
			foreach($freightProducts as $freightProduct){
				$freightFee += $freightProduct['price']*$freightProduct['amount'];
			}
		}
		
		$orderPays = WxOrderPay::get($orderDpid,$orderId);
		//查找分享红包
		$redPack = WxRedPacket::getOrderShareRedPacket($orderDpid,$order['should_total']);
		
		$this->render('orderinfo',array('companyId'=>$this->companyId,'order'=>$order,'orderProducts'=>$orderProducts,'orderPays'=>$orderPays,'site'=>$site,'address'=>$address,'siteType'=>$siteType,'redPack'=>$redPack,'seatingFee'=>$seatingFee,'packingFee'=>$packingFee,'freightFee'=>$freightFee));
	}
	/**
	 * 
	 * 完善个人资料
	 * 
	 */
	public function actionSetUserInfo()
	{
		//$user就是brand_user表里的一行

        $user = $this->brandUser;
        //$userId就是brand_user表里的lid
        $userId = $user['lid'];
		$back = Yii::app()->request->getParam('back',0);
		$type = Yii::app()->request->getParam('type',6);
		
		$this->render('updateuserinfo',array('companyId'=>$this->companyId,'user'=>$user,'type'=>$type,'back'=>$back));
	}
	/**
	 * 
	 * 保存个人资料
	 * 
	 */
	public function actionSaveUserInfo()
	{
		$back = Yii::app()->request->getParam('back');
		$type = Yii::app()->request->getParam('type',6);
		if(Yii::app()->request->isPostRequest){
			$userInfo = Yii::app()->request->getPost('user');
                       
			$result = WxBrandUser::update($userInfo);
			if($result){
				WxCupon::getWxSentCupon($this->companyId, 1, $userInfo['lid'],$this->brandUser['openid']);
				if($back){
					$this->redirect(array('/mall/checkOrder','companyId'=>$this->companyId,'type'=>$type));
				}else{
					$this->redirect(array('/user/index','companyId'=>$this->companyId));
				}
			}else{
				$this->redirect(array('/user/setUserInfo','companyId'=>$this->companyId));
			}
		}
	}
	/**
	 * 
	 * 会员地址列表
	 * 
	 */
	public function actionAddress()
	{
		//$user就是brand_user表里的一行

        $user = $this->brandUser;
        //$userId就是brand_user表里的lid
        $userId = $user['lid'];
		$addresss = WxAddress::get($userId,$user['dpid']);
		$this->render('address',array('companyId'=>$this->companyId,'addresss'=>$addresss,'user'=>$user));
	}
	/**
	 * 
	 * 编辑地址
	 * 
	 */
	public function actionSetAddress()
	{
		//$user就是brand_user表里的一行

        $user = $this->brandUser;
        //$userId就是brand_user表里的lid
        $userId = $user['lid'];
		$url = Yii::app()->request->getParam('url');
		$type = Yii::app()->request->getParam('type',1);
		$addresss = WxAddress::get($userId,$user['dpid']);
		$company = WxCompany::get($this->companyId);
		$this->render('setaddress',array('company'=>$company,'addresss'=>$addresss,'user'=>$user,'url'=>$url,'type'=>$type));
	}
	/**
	 * 
	 * 增加地址
	 * 
	 */
	public function actionAddAddress()
	{
		//$user就是brand_user表里的一行

        $user = $this->brandUser;
        //$userId就是brand_user表里的lid
        $userId = $user['lid'];
		$lid = Yii::app()->request->getParam('lid',0);
		$url = Yii::app()->request->getParam('url',0);
		$address = false;
		$user = $this->brandUser;
		if($lid){
			$address = WxAddress::getAddress($lid,$user['dpid']);
		}
		$this->render('addaddress',array('companyId'=>$this->companyId,'user'=>$user,'address'=>$address,'url'=>$url));
	}
	/**
	 * 
	 * 保存地址
	 * 
	 */
	public function actionGenerateAddress() {
		$goBack = Yii::app()->request->getParam('url');
		if(Yii::app()->request->isPostRequest) {
			$post = Yii::app()->request->getPost('address');
			if($goBack){
				$post['default_address'] = 1;
			}
			if($post['lid'] > 0){
				 $generateAddress = WxAddress::update($post);
			}else{
				 $generateAddress = WxAddress::insert($post);
			}
            if($goBack){
				$this->redirect(urldecode($goBack));	
			}else{
				$this->redirect(array('/user/address','companyId'=>$this->companyId));
			}
		};
	}
	/**
	 * 
	 * 实体卡绑定
	 * 
	 */
	public function actionBindMemberCard()
	{
		$user = $this->brandUser;
		$this->render('bindmemcard',array('companyId'=>$this->companyId,'user'=>$user));
	}
	/**
	 *
	 * 保存实体卡绑定
	 *
	 */
	public function actionSaveBindMemberCard()
	{
		if(Yii::app()->request->isPostRequest){
			$userInfo = Yii::app()->request->getPost('user');
			$userId = $userInfo['lid'];
			$dpid = $userInfo['dpid'];
            $mobile =   $userInfo['mobile_num'];       
			$member = WxBrandUser::getMemberCardByMobile($mobile);
			if($member){
				$memberCardBind = WxBrandUser::getMemberCardBind($member['level_id'],$member['dpid']);
				if($memberCardBind){
					$user = WxBrandUser::get($userId, $dpid);
					if($user['member_card_rfid']){
						$msg = '该会员卡已绑定微信';
					}else{
						$memLevel = WxBrandUser::getUserLevel($member['level_id'],$member['dpid']);
						$userLevel = WxBrandUser::getUserLevel($memberCardBind['branduser_level_id'],$user['dpid']);
						if($memLevel&&$userLevel){
							$result = WxBrandUser::brandUserBind($user['lid'], $user['dpid'], $member['rfid'],$userLevel['lid'],$userLevel['min_total_points']);
							if($result){
								$this->redirect(array('/user/index','companyId'=>$this->companyId));
							}else{
								$msg = '绑定失败请重新绑定';
							}
						}else{
							$msg = '该会员卡不能绑定微信,绑定等级不存在';
						}
					}
				}else{
					$msg = '该会员卡不能绑定微信';
				}
			}else{
				$msg = '不存在该手机号的会员';
			}
			$this->redirect(array('/user/bindMemberCard','companyId'=>$this->companyId,'msg'=>$msg));
		}else{
			$this->redirect(array('/user/bindMemberCard','companyId'=>$this->companyId));
		}
	}
	// 未使用现金券
	public function actionCupon()
	{
		//$user就是brand_user表里的一行

        $user = $this->brandUser;
        //$userId就是brand_user表里的lid
        $userId = $user['lid'];
		$cupons = WxCupon::getUserNotUseCupon($userId,$this->companyId);
		$this->render('cupon',array('companyId'=>$this->companyId,'cupons'=>$cupons));
	}
	// 已使用现金券
	public function actionUsedCupon()
	{
		//$user就是brand_user表里的一行

        $user = $this->brandUser;
        //$userId就是brand_user表里的lid
        $userId = $user['lid'];
		$cupons = WxCupon::getUserUseCupon($userId,$this->companyId);
		$this->render('usedcupon',array('companyId'=>$this->companyId,'cupons'=>$cupons));
	}
	// 已过期现金券
	public function actionExpireCupon()
	{
		//$user就是brand_user表里的一行

        $user = $this->brandUser;
        //$userId就是brand_user表里的lid
        $userId = $user['lid'];
		$cupons = WxCupon::getUserExpireCupon($userId,$this->companyId);
		$this->render('expirecupon',array('companyId'=>$this->companyId,'cupons'=>$cupons));
	}
	// 未使用礼品券
	public function actionGift()
	{
		//$user就是brand_user表里的一行

        $user = $this->brandUser;
        //$userId就是brand_user表里的lid
        $userId = $user['lid'];
		$gifts = WxGiftCard::getUserAvailableGift($userId,$this->companyId);
		$this->render('gift',array('companyId'=>$this->companyId,'gifts'=>$gifts));
	}
	// 已使用礼品券
	public function actionUsedGift()
	{
		//$user就是brand_user表里的一行

        $user = $this->brandUser;
        //$userId就是brand_user表里的lid
        $userId = $user['lid'];
		$gifts = WxGiftCard::getUserUsedGift($userId,$this->companyId);
		$this->render('usedgift',array('companyId'=>$this->companyId,'gifts'=>$gifts));
	}
	// 已过期礼品券
	public function actionExpireGift()
	{
		//$user就是brand_user表里的一行

        $user = $this->brandUser;
        //$userId就是brand_user表里的lid
        $userId = $user['lid'];
		$gifts = WxGiftCard::getUserExpireGift($userId,$this->companyId);
		$this->render('expiregift',array('companyId'=>$this->companyId,'gifts'=>$gifts));
	}
	/**
	 * 
	 * 手机报表统计
	 * 
	 */
	public function actionStatistic()
	{
		$now = time();
		//$user就是brand_user表里的一行

        $user = $this->brandUser;
        //$userId就是brand_user表里的lid
        $userId = $user['lid'];
		$day = Yii::app()->request->getParam('day',1);
		$t = Yii::app()->request->getParam('t',0);
		if($day==1){
			$yesterday = strtotime('-1 day');
		}else{
			if($day < 1){
				$day = 1;
			}
			$yesterday = strtotime('-'.$day.' day');
		}
		
		$start = date('Y-m-d',$now).' 00:00:00';
		$end = date('Y-m-d',$now).' 23:59:59';
		
		$ystart = date('Y-m-d',$yesterday).' 00:00:00';
		$yend = date('Y-m-d',$yesterday).' 23:59:59';
		
		$orderTypeStatistic = WxStatistic::getStatisticByOrderType($this->companyId,$start,$end);
		$payTypeStatistic = WxStatistic::getStatisticByOrderPayType($this->companyId,$start,$end);
		
		$yorderTypeStatistic = WxStatistic::getStatisticByOrderType($this->companyId,$ystart,$yend);
		$ypayTypeStatistic = WxStatistic::getStatisticByOrderPayType($this->companyId,$ystart,$yend);
		
		$this->render('statistic',array('companyId'=>$this->companyId,'orderTypeStatistic'=>$orderTypeStatistic,'payTypeStatistic'=>$payTypeStatistic,'yorderTypeStatistic'=>$yorderTypeStatistic,'ypayTypeStatistic'=>$ypayTypeStatistic,'day'=>$day));
	}
	/**
	 * 
	 * 
	 * 礼品券详情
	 * 
	 */
	public function actionGiftInfo()
	{
		//$user就是brand_user表里的一行

        $user = $this->brandUser;
        //$userId就是brand_user表里的lid
        $userId = $user['lid'];
		$giftId = Yii::app()->request->getParam('gid');
		
		$gift = WxGiftCard::getUserGift($this->companyId,$userId,$giftId);
		if(!$gift['qrcode']){
			$imgurl = './uploads';
			$imgurl .= '/company_'.$this->companyId;
   			if(!is_dir($imgurl)){
   				mkdir($imgurl, 0777,true);
   			}
			$imgurl .= '/qrcode';
   			if(!is_dir($imgurl)){
   				mkdir($imgurl, 0777,true);
   			}
   			$imgurl .= '/gift-'.$this->companyId.'-'.$giftId.'.png';
   			
			$code=new QRCode($gift['code']);
			$code->create($imgurl);
			WxGiftCard::updateQrcode($this->companyId,$gift['lid'],$imgurl);
			$gift['qrcode'] = $imgurl;
		}
		$this->render('giftinfo',array('companyId'=>$this->companyId,'gift'=>$gift));
	}
	/**
	 *
	 * 获取实体会员卡信息
	 *
	 */
	public function actionAjaxGetMemberCard()
	{
		$mobile = Yii::app()->request->getParam('mobile');
		$userId =  Yii::app()->request->getParam('user_id');
		$userdpid =  Yii::app()->request->getParam('user_dpid');
		$member = WxBrandUser::getMemberCardByMobile($mobile);
		if($member){
			$memberCardBind = WxBrandUser::getMemberCardBind($member['level_id'],$member['dpid']);
			if($memberCardBind){
				$user = WxBrandUser::get($userId, $userdpid);
				if($user['member_card_rfid']){
					$msg = array('status'=>false,'msg'=>'该会员卡已绑定微信');
				}else{
					$memLevel = WxBrandUser::getUserLevel($member['level_id'],$member['dpid']);
					$userLevel = WxBrandUser::getUserLevel($memberCardBind['branduser_level_id'],$user['dpid']);
					if($memLevel&&$userLevel){
						$msg = array('status'=>true,'member'=>array('name'=>$memLevel['level_name'],'level_discount'=>$memLevel['level_discount'],'birthday_discount'=>$memLevel['birthday_discount']),'branduser'=>array('name'=>$userLevel['level_name'],'level_discount'=>$userLevel['level_discount'],'birthday_discount'=>$userLevel['birthday_discount']));
					}else{
						$msg = array('status'=>false,'msg'=>'该会员卡不能绑定微信,绑定等级不存在');
					}
				}
			}else{
				$msg = array('status'=>false,'msg'=>'该会员卡不能绑定微信');
			}
		}else{
			$msg = array('status'=>false,'msg'=>'不存在该手机号的会员');
		}
		echo json_encode($msg);
		exit;
	}
	/**
	 * 
	 * 取消订单
	 * 
	 */
	 public function actionAjaxCancelOrder()
	{
		$orderId = Yii::app()->request->getParam('orderId');
		$dpid = Yii::app()->request->getParam('orderDpid');
		
		$transaction=Yii::app()->db->beginTransaction();
		try{
			 WxOrder::cancelOrder($orderId,$dpid);
			 $transaction->commit();
			 echo 1;
		}catch (Exception $e) {
			$transaction->rollback();
			echo 0;
		}
		exit;
	}
	/**
	 * 
	 * 获取会员二维码
	 * 
	 */
	public function actionAjaxGetUserCard()
	{
		$userId = Yii::app()->request->getParam('user_id');
		$userDpid = Yii::app()->request->getParam('user_dpid');
		
		$user = WxBrandUser::get($userId,$userDpid);
		if($user){
			$imgurl = './uploads';
			$imgurl .= '/company_'.$userDpid;
			if(!is_dir($imgurl)){
				mkdir($imgurl, 0777,true);
			}
			$imgurl .= '/qrcode';
			if(!is_dir($imgurl)){
				mkdir($imgurl, 0777,true);
			}
			$imgurl .= '/usercard-'.$userDpid.'-'.$userId.'.png';
			
			if(!file_exists($imgurl)){
				$code=new QRCode($user['card_id']);
				$code->create($imgurl);
			}
			$msg = array('status'=>true,'url'=>$imgurl);
		}else{
			$msg = array('status'=>false,'url'=>'');
		}
		echo json_encode($msg);
		exit;
	}
	/**
	 * 
	 * 点击头像 更新会员信息
	 * 
	 */
	public function actionAjaxHeadIcon()
	{
		$userId = Yii::app()->request->getParam('userId');
		$dpid = $this->companyId;
		
		$pullInfo = new PullUserInfo($dpid,$userId);
		if($pullInfo->response->headimgurl){
			echo $pullInfo->response->headimgurl;
		}else{
			echo false;
		}
		exit;
	}
	/**
	 * 
	 * 设置默认地址
	 * 
	 * 
	 */
	public function actionAjaxSetAddress()
	{
		$lid = Yii::app()->request->getPost('lid');
		$dpid = Yii::app()->request->getPost('dpid');
		$userId = Yii::app()->request->getPost('userId');
		
		$addresss = WxAddress::setDefault($userId,$lid,$dpid);
		
		if($addresss){
			echo 1;
		}else{
			echo 0;
		}
		exit;

	}
	/**
	 * 
	 * 删除地址
	 * 
	 */
	public function actionAjaxDeleteAddress()
	{
		$lid = Yii::app()->request->getParam('lid');
		$dpid = Yii::app()->request->getParam('dpid');;
		
		$addresss = WxAddress::deleteAddress($lid,$dpid);
		
		if($addresss){
			echo 1;
		}else{
			echo 0;
		}
		exit;
	}
	/**
	 * 
	 * 验证手机验证码
	 * 
	 */
	 public function actionAjaxVerifyCode()
	{
		$mobile = Yii::app()->request->getParam('mobile');
		$code = Yii::app()->request->getParam('code');
               
		$mobile = trim($mobile);
		$code = trim($code);
               
		$result = WxSentMessage::getCode($this->companyId,$mobile);
		if($result && $result['code'] == $code){
			echo 1;
		}else{
			echo 0;
		}
		exit;
	}
	/**
	 * 
	 * 发送短信
	 * 
	 */
	 public function actionAjaxSentMessage()                 
	{       
                $user_id = Yii::app()->request->getParam('user_id');
		$mobile = Yii::app()->request->getParam('mobile');
                $type = Yii::app()->request->getParam('type');
		$code = rand(1000,9999);
                $message =  WxSentMessage::insert($this->companyId,$mobile,$code,$type,$user_id);
                
		if($message['status']){
                        $lid= $message['lid'];
			$content = '【物易科技】您的验证码是：'.$code;
			$result = WxSentMessage::sentMessage($mobile,$content);
			$resArr = json_decode($result);
			if($resArr->returnstatus=='Success'){
                            WxSentMessage::update($lid ,1);
				echo 1;
			}else{
				echo 0;
			}
		}else{
			echo 0;
		}
              
                
		exit;
	}
	/**
	 *
	 * ajax订单列表
	 *
	 */
	public function actionAjaxOrderList()
	{
		$userId = Yii::app()->request->getParam('userId');
		$cardId = Yii::app()->request->getParam('cardId');
		$type = Yii::app()->request->getParam('t',0);
		$page = Yii::app()->request->getParam('p',1);
	
		$orderLists = WxOrder::getUserOrderList($userId,$cardId,$type,$page);
		echo json_encode($orderLists);
		exit;
	}
}