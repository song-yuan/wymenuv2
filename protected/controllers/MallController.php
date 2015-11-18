<?php

class MallController extends Controller
{
	public $companyId;
	public $weixinServiceAccount;
	public $brandUser;
	
	public function init() 
	{
		$companyId = Yii::app()->request->getParam('companyId');
		$this->companyId = $companyId;
		$this->userId = null;
		//如果微信浏览器
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
		$product = new WxProduct($this->companyId,$this->userId);
		var_dump($product->productList);exit;
		$this->render('index',array('companyId'=>$this->companyId));
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