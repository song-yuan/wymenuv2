<?php
/*
 * Created on 2013-11-28
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class WxcardController extends BackendController{
	public function actions() {
		return array(
				'upload'=>array(
						'class'=>'application.extensions.swfupload.SWFUploadAction',
						//注意这里是绝对路径,.EXT是文件后缀名替代符号
						'filepath'=>Helper::genFileName().'.EXT',
						//'onAfterUpload'=>array($this,'saveFile'),
				)
		);
	}
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId && $this->getAction()->getId() != 'upload') {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	/**
     * 
     * 微信卡券列表
     */
	public function actionIndex(){

		$criteria = new CDbCriteria;
		
		$criteria->addCondition('dpid=:brandId');
		$criteria->addCondition('delete_flag=0');
		$criteria->params[':brandId'] = $this->companyId;
		$criteria->order = 'lid desc';
		
	    $pages = new CPagination(WeixinCard::model()->count($criteria));
	    $pages->applyLimit($criteria);
	    $models = WeixinCard::model()->findAll($criteria);
		$this->render('index',array(
			'models'=>$models,
			'pages'=>$pages,
		));
	}
	public function actionAddCard(){
		$this->renderPartial('_addcard');
	}
	//更改卡券库存
	public function actionChangeSku(){
		$id = Yii::app()->request->getParam('id',0);
		$wxCard = WeixinCard::model()->findByPk($id);
		
		if(Yii::app()->request->isPostRequest){
			$data = array('msg'=>'修改失败','status'=>false);
			$type = Yii::app()->request->getPost('type');
			$sku = Yii::app()->request->getPost('sku');
			
			$wxSdk = new WxSdk($brand->brand_id);
	      	$accessToken = $wxSdk->getAccessToken();
	      	$url = 'https://api.weixin.qq.com/card/modifystock?access_token='.$accessToken;
	      	if($type){
	      		$postData = '{"card_id":"'.$wxCard->card_id.'","increase_stock_value":"'.$sku.'"}';
	      	}else{
	      		$postData = '{"card_id":"'.$wxCard->card_id.'","reduce_stock_value":"'.$sku.'"}';
	      	}
	      	$result = $wxSdk->https_request($url,$postData);
			$result = json_decode($result);
			if($result->errmsg=="ok"){
				if($type){
					$wxCard->sku_quantity = $wxCard->sku_quantity + $sku;
				}else{
					$wxCard->sku_quantity = $wxCard->sku_quantity - $sku;
				}
				$wxCard->update();
				$data = array('msg'=>'修改成功','status'=>true);
			}else{
				$data = array('msg'=>$result->errmsg,'status'=>false);
			}
			Yii::app()->end(json_encode($data));
		}
		$this->renderPartial('_changesku',array('id'=>$id));
	}
      /**
     * 
     * 创建微信卡券
     */
      public function actionCreate(){
      	$type = Yii::app()->request->getParam('type',0);
      	$model = new WeixinCard;
      	
      	$wxSdk = new WxSdk($this->companyId);
      	$accessToken = $wxSdk->getAccessToken();
      	if(Yii::app()->request->isPostRequest)
		{
			$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
     		$url = "$protocol$_SERVER[HTTP_HOST]";
	        $logoUrl = $url.'/'.$_POST['logo'];
	        $brandName = $_POST['brand_name'];
	        $codeType = (int)($_POST['code_type']);
	        $title = $_POST['title'];
	        $color = $_POST['color'];
	        $colorVal = $_POST['color_val'];
	        $notice = $_POST['notice'];
	        $servicePhone = $_POST['service_phone'];
	        $description = $_POST['description'];
	        //库存
	        $quantity = $_POST['quantity'];
	        $sku = new Sku($quantity);
	        //有效期
	        $dateType = (int)($_POST['date_info_type']);
	        if($dateType==1){
	        	$begin = str_replace('.','-',$_POST['begin_timestamp']);
	        	$beginDate = strtotime($begin);
	        	$end = str_replace('.','-',$_POST['end_timestamp']);
	        	$endDate = strtotime($end);
	        }elseif($dateType==2){
	        	$beginDate = (int)$_POST['fixed_term'];
	        	$endDate = (int)$_POST['fixed_begin_term'];
	        }
	      	$baseInfo = new BaseInfo($logoUrl, $brandName,$codeType, $title, $color, $notice, $servicePhone,$description,new DateInfo($dateType,$beginDate,$endDate), $sku);
//			var_dump($baseInfo);exit;
			if($_POST['sub_title']){
				$baseInfo->set_sub_title($_POST['sub_title']);
			}
			$baseInfo->set_use_limit(1);
			if($_POST['get_limit']){
				$baseInfo->set_get_limit((int)($_POST['get_limit']));
			}
			if($_POST['js_shop_type']==1){
				$shopIds = $_POST['shopIds'];
				$baseInfo->set_location_id_list($shopIds);
			}
			if(isset($_POST['can_share'])&&$_POST['can_share']){
				$baseInfo->set_can_share( true );
			}
			
			if(isset($_POST['can_give_friend'])&&$_POST['can_give_friend']){
				$baseInfo->set_can_give_friend( true );
			}
			if($type==1){
				$defaultDetail = $_POST['default_detail'];
				$card = new WxCard("GENERAL_COUPON", $baseInfo);
				$card->get_card()->set_default_detail($defaultDetail);
			}elseif($type==2){
				$gift = $_POST['default_detail'];
				$card = new WxCard("GIFT", $baseInfo);
				$card->get_card()->set_gift($gift);
			}else{
				$leastCost = $_POST['least_cost']*100;
				$reduceCost = $_POST['reduce_cost']*100;
				$card = new WxCard("CASH", $baseInfo);
				$card->get_card()->set_least_cost($leastCost);
				$card->get_card()->set_reduce_cost($reduceCost);
			}
			$cardJson = $card->toJson();
			$url = 'https://api.weixin.qq.com/card/create?access_token='.$accessToken;
			$result = Curl::httpsRequest($url,$cardJson);
			$result = json_decode($result);
			if($result->errmsg=="ok"){
				$transaction=Yii::app()->db->beginTransaction();
				try{
					$se=new Sequence("weixin_card");
                    $model->lid = $se->nextval();
                    $model->create_at = date('Y-m-d H:i:s',time());
                    $model->update_at = date('Y-m-d H:i:s',time());
                    $model->delete_flag = '0';
					$modelData = array(
				 				 'logo'=>$logoUrl,
				 				 'color'=>$colorVal,
				 				 'brand_name'=>$brandName,
				 				 'title'=>$title,
				 				 'sub_title'=>$_POST['sub_title']?$_POST['sub_title']:'',
				 				 'notice'=>$notice,
				 				 'description'=>$description,
				 				 'date_info_type'=>$dateType,
				 				 'begin_timestamp'=>isset($beginDate)?$beginDate:0,
				 				 'end_timestamp'=>isset($endDate)?$endDate:0,
				 				 'fixed_term'=>isset($_POST['fixed_term'])?$_POST['fixed_term']:0,
				 				 'fixed_begin_term'=>isset($_POST['fixed_begin_term'])?$_POST['fixed_begin_term']:0,
				 				 'sku_quantity'=>$quantity,
				 				 'can_share'=>isset($_POST['can_share'])?$_POST['can_share']:0,
				 				 'can_give_friend'=>isset($_POST['can_give_friend'])?$_POST['can_give_friend']:0,
				 				 'get_limit'=>$_POST['get_limit']?$_POST['get_limit']:'0',
				 				 'service_phone'=>$servicePhone,
				 				 'card_type'=>$type?2:1,
				 				 'least_cost'=>isset($leastCost)?$leastCost:0,
				 				 'reduce_cost'=>isset($reduceCost)?$reduceCost:0,
				 				 'gift'=>isset($defaultDetail)?$defaultDetail:'',
				 				 'card_id'=>$result->card_id,
				 				  );
					$model->attributes =  $modelData;
					$model->save();
					if($_POST['js_shop_type']==1){
						$shopIds = $_POST['shopIds'];
						foreach($shopIds as $shop){
							$cardShop = new WeixinCardShop;
							$se=new Sequence("weixin_card_shop");
		                    $cardShop->lid = $se->nextval();
		                    $cardShop->create_at = date('Y-m-d H:i:s',time());
		                    $cardShop->update_at = date('Y-m-d H:i:s',time());
		                    $cardShop->delete_flag = '0';
							$shopData = array(
											'card_id'=>$result->card_id,
											'wx_location_id'=>$shop,
											);
							$cardShop->attributes =  $shopData;
							$cardShop->save();
						}
					}
					$msg = '创建卡券成功等待审核!';
					Yii::app()->user->setFlash('success',$msg);
					$transaction->commit();
				}catch (Exception $e) {
            		$transaction->rollback();
            		$msg = '云卡服务器创建失败,请去微信后台!';
            		Yii::app()->user->setFlash('error',$msg);
            		$this->redirect(array('/brand/wxcard/index','cid'=>$this->companyId));
       			 }
				
			}else{
				Yii::app()->user->setFlash('error',$result->errmsg);
			}
			
			$this->redirect(array('/admin/wxcard/index','companyId'=>$this->companyId));
		}
		//获取卡券颜色列表
		$url = 'https://api.weixin.qq.com/card/getcolors?access_token='.$accessToken;
		$data = Curl::httpsRequest($url);
		$dataObj = json_decode($data);
		if($dataObj->errmsg=="ok"){
			$colors = $dataObj->colors;
		}else{
			Yii::app()->user->setFlash('error',$dataObj->errmsg);
			$this->redirect(array('/admin/wxcard/index','companyId'=>$this->companyId));
		}
//		$colors = array();
		$this->render('create',array(
			'model'=>$model,'colors'=>$colors,'type'=>$type,
		));
      }
      //卡券获取门店
      public function actionAddShop(){
		
		$criteria=new CDbCriteria;
		$criteria->with = 'shop';  
		$criteria->condition='brand_id=:brandId';
		$criteria->params=array(':brandId'=>$brand->brand_id);
		
		$shop = WeixinShop::model()->findAll($criteria);
		$this->renderPartial('_addshop',array('shops'=>$shop));
	}
      /**
       * 
       * 卡券详情
       * 
       */
      public function actionDetail(){
      	$id = Yii::app()->request->getParam('id');
		$brand = Yii::app()->admin->getBrand($this->companyId);
		$wxCard = WeixinCard::model()->findByPk($id);
		
		$wxSdk = new WxSdk($brand->brand_id);
      	$accessToken = $wxSdk->getAccessToken();
      	$url = 'https://api.weixin.qq.com/card/get?access_token='.$accessToken;
      	$postData = '{"card_id":"'.$wxCard->card_id.'"}';
      	$result = $wxSdk->https_request($url,$postData);
		$result = json_decode($result);
//		var_dump($result->card);exit;
		if($result->errmsg=="ok"){
			$this->render('detail',array('cardInfo'=>$result->card));
		}else{
			Yii::app()->user->setFlash('error',$dataObj->errmsg);
			$this->redirect(array('/brand/wxcard/index','cid'=>$this->companyId));
		}
      }
      /**
       * 
       * 卡券删除
       * 
       */
    public function actionDelete(){
       	$id = Yii::app()->request->getParam('id');
		$brand = Yii::app()->admin->getBrand($this->companyId);
		
		$wxCard = WeixinCard::model()->findByPk($id);
		$wxSdk = new WxSdk($brand->brand_id);
      	$accessToken = $wxSdk->getAccessToken();
      	$url = 'https://api.weixin.qq.com/card/delete?access_token='.$accessToken;
      	$postData = '{"card_id":"'.$wxCard->card_id.'"}';
      	$result = $wxSdk->https_request($url,$postData);
		$result = json_decode($result);
//		var_dump($result->card->cash);exit;
		if($result->errmsg=="ok"){
			$wxCard->delete_flag = 1;
			$wxCard->save();
			Yii::app()->admin->setFlash('success','删除成功!');
		}else{
			Yii::app()->admin->setFlash('error',$dataObj->errmsg);
		}
		$this->redirect(array('/brand/wxcard/index','cid'=>$this->companyId));
     }
     //卡券统计
      public function actionCardUser(){
       	$id = Yii::app()->request->getParam('id');
		$brand = Yii::app()->admin->getBrand($this->companyId);
		
		$wxCard = WeixinCard::model()->findByPk($id);
		
		$criteria = new CDbCriteria;
		$criteria->with = array('brandUser','friendUser');
		$criteria->order = ' t.id desc';
		$criteria->addCondition('t.card_id=:cardId');
		$criteria->addCondition('t.delete_flag=0');
		$criteria->params[':cardId'] = $wxCard->card_id;
		
	    $pages = new CPagination(WeixinCardUser::model()->count($criteria));
	    $pages->applyLimit($criteria);
		$models = WeixinCardUser::model()->findAll($criteria);
		$this->render('carduser',array(
			'models'=>$models,
			'pages'=>$pages,
		));
      }
      //卡券核销
      public function actionConsume(){
		$brand = Yii::app()->admin->getBrand($this->companyId);
		
		$this->render('consume',array('brand'=>$brand));
      }
       //卡券 查询code
      public function actionGetwxcard(){
      	$code = Yii::app()->request->getParam('code');
		$brand = Yii::app()->admin->getBrand($this->companyId);
		
		$wxSdk = new WxSdk($brand->brand_id);
      	$accessToken = $wxSdk->getAccessToken();
      	$url = 'https://api.weixin.qq.com/card/code/get?access_token='.$accessToken;
      	$postData = '{"code":"'.$code.'"}';
      	$result = $wxSdk->https_request($url,$postData);
		$result = json_decode($result);
		$data = array('status'=>0,'msg'=>'');
		if($result->errmsg=="ok"){
			$data = array('status'=>1,'msg'=>'');
			$model = WeixinCard::model()->find('card_id=:cardId',array(':cardId'=>$result->card->card_id));
			$data['html'] = $this->renderPartial('_wxcardForm',array('model'=>$model,'code'=>$code,'card'=>$result->card),true);
		}else{
			$data = array('status'=>1,'msg'=>$result->errmsg,'html'=>0);
			$modelUser = WeixinCardUser::model()->find('user_card_code=:code',array(':code'=>$code));
			if($modelUser){
				$model = WeixinCard::model()->find('card_id=:cardId',array(':cardId'=>$modelUser->card_id));
				$data['html'] = $this->renderPartial('_wxcardForm',array('model'=>$model,'code'=>$code,'card'=>0),true);
			}
		}
		Yii::app()->end(json_encode($data));
      }
       //卡券 消耗code
      public function actionConfirmCard(){
      	$code = Yii::app()->request->getParam('code');
		$brand = Yii::app()->admin->getBrand($this->companyId);
		
		$wxSdk = new WxSdk($brand->brand_id);
      	$accessToken = $wxSdk->getAccessToken();
      	$url = 'https://api.weixin.qq.com/card/code/consume?access_token='.$accessToken;
      	$postData = '{"code":"'.$code.'"}';
      	$result = $wxSdk->https_request($url,$postData);
		$result = json_decode($result);
		$data = array('status'=>0,'msg'=>'核销失败');
		if($result->errmsg=="ok"){
			$data = array('status'=>1,'msg'=>'已经核销');
			$model = WeixinCardUser::model()->find('user_card_code=:code',array(':code'=>$code));
			$model->status = 1;
			$model->use_time = time();
			$model->update();
		}
		Yii::app()->end(json_encode($data));
      }
    /**
	 * 卡券二维码
	 */
	public function actionPrintWeixinCard(){
		$id = Yii::app()->request->getParam('id');
		$brand = Yii::app()->admin->getBrand($this->companyId);
		$wxCard = WeixinCard::model()->findByPk($id);
		
		$data = array('msg'=>'请求失败！','status'=>false,'qrcode'=>'');
		$wxCardQrcode = new WxCardQrcode($brand,$wxCard->card_id);
		$qrcode = $wxCardQrcode->getQrcode(12,1,time()+7*24*3600);
		if($qrcode){
			$wxCard->saveAttributes(array('qrcode'=>$qrcode));
			$data['msg'] = '生成二维码成功！';
			$data['status'] = true;
			$data['qrcode'] = $qrcode;
		}
		
		Yii::app()->end(json_encode($data));
	}
	//设置白名单
	public function actionSetCardWhiteList(){
		$brand = Yii::app()->admin->getBrand($this->companyId);
		$user = Yii::app()->request->getParam('user');
		$wxCardQrcode = new WxCardQrcode($brand);
		$result = $wxCardQrcode->setOpenUser($user);
		var_dump($result);exit;
	}
	public function actionUploadfile(){
		$brand = Yii::app()->admin->getBrand($this->companyId);
		$this->renderPartial('uploadfile',array('brand'=>$brand));
	}
} 
?>
