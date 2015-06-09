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
		$pslid = Yii::app()->request->getParam('psid');
                $model->set_id=$pslid;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductSetDetail');
                        $se=new Sequence("porduct_set_detail");
                        $model->lid = $se->nextval();
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->delete_flag = '0';
                        //var_dump($model);exit;
			if($model->save()) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('productSet/detailindex','companyId' => $this->companyId,'lid'=>$model->set_id));
			}
		}
                $maxgroupno=$this->getMaxGroupNo($pslid);
                $categories = $this->getCategories();
                $categoryId=0;
                $products = $this->getProducts($categoryId);
                $productslist=CHtml::listData($products, 'lid', 'product_name');
		$this->render('detailcreate' , array(
				'model' => $model,
                                'categories' => $categories,
                                'categoryId' => $categoryId,
                                'products' => $productslist,
                                'maxgroupno'=>$maxgroupno
		));
	}
	
        
	public function actionDetailDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
                $printset = Yii::app()->request->getParam('psid');
		$ids = Yii::app()->request->getPost('ids');
                //var_dump($ids);exit;
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_product_set_detail set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('productSet/detailindex' , 'companyId' => $companyId,'lid'=>$printset)) ;
		} else {
			Yii::app()->user->setFlash('error' ,yii::t('app', '请选择要删除的项目'));
			$this->redirect(array('productSet/detailindex' , 'companyId' => $companyId,'lid'=>$printset)) ;
		}
	}
}