<?php
class BackendController extends CController
{
	public $layout = '/layouts/main_admin';
	public $companyId = 0;
	public function beforeAction($action) {
		parent::beforeAction($action);
		$controllerId = Yii::app()->controller->getId();
		$action = Yii::app()->controller->getAction()->getId();                
                //$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId',"0000000000"));
		if(Yii::app()->user->isGuest) {
			if($controllerId != 'login' && $action != 'upload') {
				$this->redirect(Yii::app()->params['admin_return_url']);
			}
		} elseif(Yii::app()->user->role > User::WAITER &&$controllerId != 'login'){
			$this->redirect(Yii::app()->params['admin_return_url']);
		}else {
			$this->companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId',"0000000000"));
		}
                Until::isOperateValid($controllerId, $action,$this->companyId,$this);
                
		return true ;
	}
        
//        public function afterAction($action) {
//		parent::afterAction($action);
////		$controllerId = Yii::app()->controller->getId();
////		$action = Yii::app()->controller->getAction()->getId();                
////                
////		if(Yii::app()->user->isGuest) {
////			if($controllerId != 'login' && $action != 'upload') {
////				$this->redirect(Yii::app()->params['admin_return_url']);
////			}
////		} elseif(Yii::app()->user->role > User::WAITER &&$controllerId != 'login'){
////			$this->redirect(Yii::app()->params['admin_return_url']);
////		}else {
////			$this->companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId',"0000000000"));
////		}
//                //判断和上次同步的时间是否超过5分钟，
//                //判断云端网络是否连接
//                //如果超过则从云端下载数据；
//                //如果超过则将本地数据上传到云端；
//		return true ;
//	}
}