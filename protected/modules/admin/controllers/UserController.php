<?php
class UserController extends BackendController
{
	public $roles ;
	public $roles2;
	public $roles3;
	public $roles4;
	public function init(){
		$this->roles = array(
			'2' => yii::t('app','总部管理员'),
			//'3' => yii::t('app','服务员'),
		) ;
		
		$this->roles2 = array(
				'1' => yii::t('app','超级管理员'),
				'2' => yii::t('app','总部管理员'),
				'3' => yii::t('app','店长'),
				'4' => yii::t('app','收银员'),
				'5' => yii::t('app','服务员'),
		) ;
		$this->roles3 = array(
				//'1' => yii::t('app','超级管理员'),
				//'2' => yii::t('app','总部管理员'),
				'3' => yii::t('app','店长'),
				'4' => yii::t('app','收银员'),
				'5' => yii::t('app','服务员'),
		) ;
		$this->roles4 = array(
				//'1' => yii::t('app','超级管理员'),
				//'2' => yii::t('app','总部管理员'),
				//'3' => yii::t('app','店长'),
				'4' => yii::t('app','收银员'),
				'5' => yii::t('app','服务员'),
		) ;
		if(Yii::app()->user->role == User::POWER_ADMIN) {
                    
			$this->roles = array('1' => yii::t('app','超级管理员')) +$this->roles;
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
					$role = $model->role;
					if($role <=3){
						$username = $model->username;
						$ordusername = User::model()->find('username=:name and delete_flag=0' , array(':name'=>$username));
						if($ordusername){
							Yii::app()->user->setFlash('error' ,yii::t('app', '该登陆名已存在，请重新取名！！！'));
							$this->redirect(array('user/create' , 'companyId' => $companyId));
							//$this->render('create' , array('model' => $model));
							//$this->render('create' , array('model' => $model,'action' => $this->createUrl('user/create' , array('companyId'=>$this->companyId))));
						}
					}else{
						$username = $model->username;
						$ordusername = User::model()->find('dpid=:dpid and username=:name and delete_flag=0' , array(':dpid'=> $companyId,':name'=>$username));
						if($ordusername){
							Yii::app()->user->setFlash('error' ,yii::t('app', '该登陆名已存在，请重新取名！！！'));
							$this->redirect(array('user/create' , 'companyId' => $companyId));
							//$this->render('create' , array('model' => $model));
							//$this->render('create' , array('model' => $model,'action' => $this->createUrl('user/create' , array('companyId'=>$this->companyId))));
						}
					}
		                        //$model->create_at=date('Y-m-d H:i:s',time());
		                        //$model->update_at=date('Y-m-d H:i:s',time());
					if($model->save()){
						Yii::app()->user->setFlash('success',yii::t('app','添加成功'));
						$this->redirect(array('user/index' , 'companyId' => $companyId));
					}
				}
// 		if(Yii::app()->request->isPostRequest) {
				
// 			$model->attributes = Yii::app()->request->getPost('UserForm');
// 			if($model->username){
// 				$db = Yii::app()->db;
// 				$sqls = 'select t.dpid from nb_user t where t.delete_flag = 0 and t.username ="'.$model->username.'"';
// 				$command = $db->createCommand($sqls);
// 				$a = $command->queryAll();
// 				if(!empty($a)){
// 					Yii::app()->user->setFlash('error' , yii::t('app','该用户名已存在！！'));
// 					//$this->render('create' , array('model' => $model));
		
// 				}else{
// 					if($model->save()){
// 						Yii::app()->user->setFlash('success',yii::t('app','添加成功'));
// 						$this->redirect(array('user/index' , 'companyId' => $companyId));
// 					}
// 				}
// 			}
// 		}
		$this->render('create' , array(
				'model' => $model
				//'usernames' =>$usernames
				
		));

		
	}
	public function actionUpdate() {
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));		
		$id = Yii::app()->request->getParam('id');
                Until::isUpdateValid(array($id),$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->user->role > User::WAITER && Yii::app()->user->userId != $id) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限修改'));
			$this->redirect(array('user/index' , 'companyId' => $companyId)) ;
		}
		$model = new UserForm();
		$model->find('lid=:id and dpid=:dpid and status=1', array(':id' => $id,':dpid'=>$companyId));
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('UserForm');
			$pw = Yii::app()->request->getParam('hidden1');
			if($pw){
				$model->password = $pw;
			}
                        //$model->update_at=date('Y-m-d H:i:s',time());
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
		if(Yii::app()->user->role > User::WAITER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有删除权限'));
			$this->redirect(array('user/index' , 'companyId' => $companyId)) ;
		}
		$ids = Yii::app()->request->getPost('ids');
                Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			foreach ($ids as $id) {
				$model = User::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id,':companyId'=>$companyId)) ;
				if($model) {
					$model->saveAttributes(array('status'=>0,'update_at'=>date('Y-m-d H:i:s',time())));
				}
			}
			$this->redirect(array('user/index' , 'companyId' => $companyId));
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('user/index' , 'companyId' => $companyId));
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
                
                $user=User::model()->find(" dpid=:dpid and lid=:user_id",array(":dpid"=>  $this->companyId,":user_id"=>$pwlid));
		//var_dump($user);exit;
		$models = UserCompany::model()->findAll($criteria);
                		
		$this->render('companyindex',array(
                        'user'=>$user,
			'models'=>$models,
                        'pages'=>$pages
		));
	}
        
        public function actionCompanyCreate(){
		$model = new UserCompany();
		$model->dpid = $this->companyId ;
		$userid = Yii::app()->request->getParam('userid');
                $model->user_id=$userid;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('UserCompany');
                        $se=new Sequence("user_company");
                        $model->lid = $se->nextval();
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->update_at=date('Y-m-d H:i:s',time());
                        $model->delete_flag = '0';
                        if($model->save()) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('user/companyIndex','companyId' => $this->companyId,'lid'=>$model->user_id));
			}
                }
                $companys = $this->getCompanys();
                $companyslist=CHtml::listData($companys, 'dpid', 'company_name');
		$this->render('companycreate' , array(
				'model' => $model,
                                'companyslist' => $companyslist
		));
	}
	
        
	public function actionCompanyDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
                $ids = Yii::app()->request->getPost('ids');
                $userid=Yii::app()->request->getParam('userid',0);
                Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_user_company set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('user/companyIndex' , 'companyId' => $companyId,'lid'=>$userid)) ;
		} else {
			Yii::app()->user->setFlash('error' ,yii::t('app', '请选择要删除的项目'));
			$this->redirect(array('user/companyIndex' , 'companyId' => $companyId,'lid'=>$userid)) ;
		}
	}
        
        private function getCompanys(){
                $companys = Company::model()->findAll(' delete_flag=0') ;                
                $companys = $companys ? $companys : array();
                return $companys;		
	}
}