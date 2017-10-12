
<?php
class BaseYmallController extends CController
{
	public $layout = '/layouts/mainymall';
	public $companyId = 0;
	public $comptype = 1;
	public function beforeAction($action) {
		date_default_timezone_set('PRC');
		parent::beforeAction($action);
		$controllerId = Yii::app()->controller->getId();
		$action = Yii::app()->controller->getAction()->getId();
		if(Yii::app()->user->isGuest) {
			if($controllerId != 'login' && $action != 'upload') {
				$this->redirect(Yii::app()->params['ymall_return_url']);
			}
		}elseif(Yii::app()->user->role >= User::GROUPER &&$controllerId != 'login'){
			$this->redirect(Yii::app()->params['ymall_return_url']);
		}else{
			$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId',"0000000000"));
			$role = Yii::app()->user->role;
			if(Yii::app()->user->role > User::ADMIN && $controllerId != 'login' && Yii::app()->user->companyId != $companyId){
			}elseif(Yii::app()->user->role == User::ADMIN && $controllerId != 'login' && $action != 'upload'){
				$dpids = Helper::getCompanyIds(Yii::app()->user->companyId);
				if($dpids == null){
					$dpids = array(0);
				}
				$results =  in_array($companyId, $dpids);
				if($results){
					$this->companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId',"0000000000"));
					$comptype = Yii::app()->db->createCommand('select type from nb_company where delete_flag = 0 and  dpid ='.Yii::app()->request->getParam('companyId',"0000000000"))->queryRow();
					$this->comptype = $comptype['type'];
				}else{
					$this->redirect(Yii::app()->params['ymall_return_url']);
				}
			}
			else{
				$this->companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId',"0000000000"));
				$comptype = Yii::app()->db->createCommand('select type from nb_company where delete_flag = 0 and  dpid ='.Yii::app()->request->getParam('companyId',"0000000000"))->queryRow();
				$this->comptype = $comptype['type'];
			}
		}
		Until::isOperateValid($controllerId, $action,$this->companyId,$this);
		return true ;
	}

	/**
 * @Author    zhang
 * @DateTime  2017-09-19T16:46:25+0800
 * @copyright [copyright]
 * @license   [license]
 * @version   [version]
 * @return    [type]         导航购物车计数查询          [description]
 */
	public function getCartsnum(){
		$user_id = 88888888;
		$db = Yii::app()->db;
		$sql = 'select count(gc.lid) as num from nb_goods_carts gc '
				.' where gc.dpid='.$this->companyId
				.' and gc.user_id='.$user_id
				.' and gc.delete_flag=0';
		$count = $db->createCommand($sql)->queryRow();
		return $count['num'];
	}
}