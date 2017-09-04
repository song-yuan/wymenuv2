
<?php
class BaseYmallController extends CController
{
	//public $layout = '/layouts/mainymall';
	public $companyId = 0;
	public $comptype = 1;
	public function beforeAction($action) {
		date_default_timezone_set('PRC');
		parent::beforeAction($action);
		$controllerId = Yii::app()->controller->getId();
		$action = Yii::app()->controller->getAction()->getId();
		//var_dump(Yii::app()->user->companyId);
		//$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId',"0000000000"));
		//var_dump(Yii::app()->user->role);exit;
		if(Yii::app()->user->isGuest) {
			if($controllerId != 'login' && $action != 'upload') {
				$this->redirect(Yii::app()->params['ymall_return_url']);
			}
		}elseif(Yii::app()->user->role >= User::GROUPER &&$controllerId != 'login'){
			$this->redirect(Yii::app()->params['ymall_return_url']);
		}else{
			$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId',"0000000000"));
				
			$role = Yii::app()->user->role;
			//var_dump($role);
			//exit;
				
				
			if(Yii::app()->user->role > User::ADMIN && $controllerId != 'login' && Yii::app()->user->companyId != $companyId){
				
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
					$this->redirect(Yii::app()->params['ymall_return_url']);
					//$this->redirect(Yii::app()->request->urlReferrer);
				}
			}
			else{
				$this->companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId',"0000000000"));
				$comptype = Yii::app()->db->createCommand('select type from nb_company where delete_flag = 0 and  dpid ='.Yii::app()->request->getParam('companyId',"0000000000"))->queryRow();
				$this->comptype = $comptype['type'];
				//var_dump($this->companyId);exit;
			}
		}
		Until::isOperateValid($controllerId, $action,$this->companyId,$this);

		return true ;
	}

}