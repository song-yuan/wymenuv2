<?php
class BackendController extends CController
{
	public $layout = '/layouts/main_admin';
	public $companyId = 0;
	public $comptype = 1;
	public function beforeAction($action) {
		date_default_timezone_set('PRC');
		parent::beforeAction($action);
		$controllerId = Yii::app()->controller->getId();
		$action = Yii::app()->controller->getAction()->getId(); 
		
		$adminReturnUrl = Yii::app()->params['admin_return_url'];
		if(Yii::app()->user->isGuest) {
			if($controllerId != 'login' && $action != 'upload') {
				$this->redirect($adminReturnUrl);
			}
		}elseif(Yii::app()->user->role >= User::GROUPER &&$controllerId != 'login'){
			/**
			 * 服务员 收银员 无权限登录系统
			 */
			$this->redirect($adminReturnUrl);
		}else{
			$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId',"0000000000"));
			
			$role = Yii::app()->user->role;
			//var_dump($companyId);var_dump($role);
			
			
			if(Yii::app()->user->role > User::ADMIN && $controllerId != 'login' && Yii::app()->user->companyId != $companyId){
				//var_dump('111');exit;
				//$this->redirect(Yii::app()->request->urlReferrer);
			}elseif(Yii::app()->user->role == User::ADMIN && $controllerId != 'login' && $action != 'upload'){
				//$dpids = Helper::getCompanyIds(Yii::app()->request->getParam('companyId',"0000000000"));
				$dpids = Helper::getCompanyIds(Yii::app()->user->companyId);
				
				if($dpids == null){
					$dpids = array(0);
				}
				//var_dump($dpids);var_dump($companyId);
				//$results =  array_search($companyId, $dpids);in_array
				$results =  in_array($companyId, $dpids);
				//var_dump($results);exit;
				if($results){
					$this->companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId',"0000000000"));
					$comptype = Yii::app()->db->createCommand('select type from nb_company where delete_flag = 0 and  dpid ='.Yii::app()->request->getParam('companyId',"0000000000"))->queryRow();
					$this->comptype = $comptype['type'];
				}else{//var_dump('222');exit;
					//var_dump($companyId);var_dump($dpids);var_dump($results);var_dump(Yii::app()->user->role);exit;
					$this->redirect(Yii::app()->params['admin_return_url']);
					//$this->redirect(Yii::app()->request->urlReferrer);
				}
			}
			else{//var_dump($this->companyId);exit;
				$this->companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId',"0000000000"));
				$comptype = Yii::app()->db->createCommand('select type from nb_company where delete_flag = 0 and  dpid ='.Yii::app()->request->getParam('companyId',"0000000000"))->queryRow();
				$this->comptype = $comptype['type'];
				//var_dump($this->companyId);
			}
		}
                Until::isOperateValid($controllerId, $action,$this->companyId,$this);
                
		return true ;
	}

}