<?php
class BackendController extends CController
{
	public $layout = '/layouts/main_admin';
	public $companyId = 0;
	public $comptype = 1;
	public $company_dpid = 0;
	public function beforeAction($action) {
		date_default_timezone_set('PRC');
		parent::beforeAction($action);
		$controllerId = Yii::app()->controller->getId();
		$action = Yii::app()->controller->getAction()->getId(); 
		
		$adminReturnUrl = Yii::app()->params['admin_return_url'];
		$oauthCallback = Yii::app()->request->url;
		
		if(Yii::app()->user->isGuest) {
			// 游客 未登录用户
			if($controllerId != 'login' && $action != 'upload') {
				$this->redirect($adminReturnUrl);
			}
		}elseif(Yii::app()->user->role >= User::GROUPER && $controllerId != 'login'){
			 //服务员 收银员 无权限登录系统
			$this->redirect($adminReturnUrl);
		}else{
			$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId',"0000000000"));
			$role = Yii::app()->user->role;
			
			$company = Company::model()->find('dpid=:dpid and delete_flag=0',array(':dpid'=>$companyId));
			
			if($company){
				$this->companyId = $companyId;
				$this->comptype = $company['type'];
				$this->company_dpid = $company['comp_dpid'];
				if($role < 5){
					// 超级管理员 所有权限
					return true;
				}elseif ($role >=5 && $role < 11){
					// 总部管理员权限
					if($company['type']==0 && $companyId==Yii::app()->user->companyId){
						//总部 管理员  总部管理员所管理的 品牌
						return true;
					}
					if($company['type']!=0 && $company['comp_dpid']==Yii::app()->user->companyId){
						// 店铺或者仓库   总部直属的仓库
						return true;
					}
				}elseif($role >=11 && $role < 15){
					// 店铺管理员
					return true;
				}
			}
			// 不符合条件 跳转登录界面
			$this->redirect($adminReturnUrl);
		}
	}
}