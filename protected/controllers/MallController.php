<?php

class MallController extends Controller
{
	public $companyId;
	public $userId = null;
	public $weixinServiceAccount;
	public $brandUser;
	public $layout = '/layouts/mallmain';
	
	public function init() 
	{
		$companyId = Yii::app()->request->getParam('companyId');
		$this->companyId = $companyId;
//		如果微信浏览器
		if(Helper::isMicroMessenger()){
			$this->weixinServiceAccount();
			$baseInfo = new WxUserBase($this->weixinServiceAccount['appid'],$this->weixinServiceAccount['appsecret']);
			$userInfo = $baseInfo->getSnsapiBase();
			$openid = $userInfo['openid'];
			
			$this->brandUser($openid);
			if(!$this->brandUser){
				$newBrandUser = new NewBrandUser($this->postArr['FromUserName'], $this->brandId);
	    		$this->brandUser = $newBrandUser->brandUser;
			}
			$this->userId = $this->brandUser['lid'];
		}
	}
	public function actionIndex()
	{
		$product = new WxProduct($this->companyId);
		$categorys = $product->categorys;
		$products = $product->categoryProductLists;
		$this->render('index',array('companyId'=>$this->companyId,'categorys'=>$categorys,'models'=>$products));
	}
	/**
	 * 
	 * 添加购物车
	 * 
	 */
	public function actionAddCart()
	{
		if(empty($this->userId)){
			Yii::app()->end(array('status'=>false,'msg'=>'请关注微信公众号我要点单进行点餐'));
			exit;
		}
		if(isset(Yii::app()->session['qrcode-'.$this->userId])){
			$siteId = Yii::app()->session['qrcode-'.$this->userId];
		}else{
			Yii::app()->end(array('status'=>false,'msg'=>'请先扫描餐桌二维码,然后再进行点单'));
			exit;
		}
		$productId = Yii::app()->request->getParam('productId');
		$promoteId = Yii::app()->request->getParam('promoteId');
		
		$productArr = array('product_id'=>$productId,'num'=>1,'privation_promotion_id'=>$promoteId);
		$cart = new WxCart($this->companyId,$this->userId,$productArr,$siteId);
		if($cart->addCart()){
			Yii::app()->end(array('status'=>true,'msg'=>'ok'));
		}else{
			Yii::app()->end(array('status'=>false,'msg'=>'点单失败,请重新点单'));
		}
	}
	/**
	 * 
	 * 删除购物车
	 * 
	 */
	public function actionDeleteCart()
	{
		if(empty($this->userId)){
			Yii::app()->end(array('status'=>false,'msg'=>'请关注微信公众号我要点单进行点餐'));
			exit;
		}
		if(isset(Yii::app()->session['qrcode-'.$this->userId])){
			$siteId = Yii::app()->session['qrcode-'.$this->userId];
		}else{
			Yii::app()->end(array('status'=>false,'msg'=>'请先扫描餐桌二维码,然后再进行点单'));
			exit;
		}
		
		$productArr = array('product_id'=>1,'num'=>1,'privation_promotion_id'=>-1);
		$cart = new WxCart($this->companyId,$this->userId,$productArr,$siteId);
		if($cart->deleteCart()){
			Yii::app()->end(array('status'=>true,'msg'=>'ok'));
		}else{
			Yii::app()->end(array('status'=>false,'msg'=>'请重新操作'));
		}
	}
	private function weixinServiceAccount() {	
		$sql = 'select * from nb_weixin_service_account where dpid = '.$this->companyId;
		$this->weixinServiceAccount = Yii::app()->db->createCommand($sql)->queryRow();
	}
	private function brandUser($openId) {	
		$sql = 'select * from nb_brand_user where openid = "'.$openId.'"';
		$this->brandUser = Yii::app()->db->createCommand($sql)->queryRow();
	}
}