<?php
class UserController extends BackendController
{
	public $roles ;
	public function init(){
		$this->roles = array(
			'2' => yii::t('app','管理员') ,
			'3' => yii::t('app','服务员'),
		) ;
		if(Yii::app()->user->role == User::POWER_ADMIN) {
                    
			$this->roles = array('1' => yii::t('app','系统管理员')) +$this->roles;
                        //var_dump($this->roles);exit;
		}
		$this->roles = array('' => yii::t('app','-- 请选择 --' )) +$this->roles;
	}
	
        public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId && $this->getAction()->getId() != 'upload') {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex() {
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$criteria = new CDbCriteria;
		//$criteria->with = 'company' ;
		//$criteria->condition = (Yii::app()->user->role == User::POWER_ADMIN ? '' : 't.dpid='.Yii::app()->user->companyId.' and ').'t.status=1 and t.role >='.Yii::app()->user->role ;
		$criteria->condition = 't.dpid='.$this->companyId.' and t.status=1 and t.role >='.Yii::app()->user->role ;
		$pages = new CPagination(User::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = User::model()->findAll($criteria);
		//var_dump($models);exit;
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
				'companyId' => $companyId
		));
	}
	public function actionCreate() {
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$model = new UserForm() ;
		$model->dpid = $companyId ;
		$model->status = 1;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('UserForm');
                        
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功'));
				$this->redirect(array('user/index' , 'companyId' => $companyId));
			}
		}
		$this->render('create' , array('model' => $model));
	}
	public function actionUpdate() {
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));		
		$id = Yii::app()->request->getParam('id');
		if(Yii::app()->user->role > User::ADMIN && Yii::app()->user->userId != $id) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限修改'));
			$this->redirect(array('user/index' , 'companyId' => $companyId)) ;
		}
		$model = new UserForm();
		$model->find('lid=:id and dpid=:dpid and status=1', array(':id' => $id,':dpid'=>$companyId));
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('UserForm');
                        //var_dump($model->attributes);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功'));
				$this->redirect(array('user/index' , 'companyId' => $companyId));
			}
		}
		$this->render('update' , array('model' => $model)) ;
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		if(Yii::app()->user->role > User::ADMIN) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有删除权限'));
			$this->redirect(array('user/index' , 'companyId' => $companyId)) ;
		}
		$ids = Yii::app()->request->getPost('ids');
                //var_dump($companyId);exit;
		if(!empty($ids)) {
			foreach ($ids as $id) {
				$model = User::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id,':companyId'=>$companyId)) ;
				if($model) {
					$model->saveAttributes(array('status'=>0));
				}
			}
			$this->redirect(array('user/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('user/index' , 'companyId' => $companyId)) ;
		}
	}
        
        public function actionCompanyIndex(){
		$pwlid = Yii::app()->request->getParam('lid');
                $criteria = new CDbCriteria;
                $criteria->with = array('user','company');
                //$criteria->with = 'printer';
		$criteria->condition =  't.dpid='.$this->companyId .' and t.user_id='.$pwlid.' and t.delete_flag=0 and company.delete_flag=0';
                $pages = new CPagination(UserCompany::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		
		$models = UserCompany::model()->findAll($criteria);
                		
		$this->render('companyindex',array(
			'models'=>$models,
                        'pages'=>$pages
		));
	}
}