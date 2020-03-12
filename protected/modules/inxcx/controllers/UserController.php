<?php
/**
 * 会员接口
 */
class UserController extends Controller
{
	public $companyId;
	public function init()
	{
		$companyId = Yii::app()->request->getParam('companyId');
		$this->companyId = $companyId;
	}
	public function actionXcxlogin()
	{
		$code = Yii::app()->request->getParam('code');
		$status = false;
		$user = array();
		$data = XCXServer::getOpenId($this->companyId, $code);
		if(!empty($data)){
			$status = true;
			$openId = $data['openid'];
			$user = WxBrandUser::getFromOpenId($openId);
			if(empty($user)){
				$newBrandUser = new NewBrandUser($openId, $this->companyId, 1);
				$user = $newBrandUser->brandUser;
			}
		}
		echo json_encode(array('status'=>$status,'user'=>$user,'login'=>$data));exit;
		
	}
	public function actionGetUserInfo()
	{
		
	}
}