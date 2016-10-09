<?php
class MemberWxlevelController extends BackendController
{
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' ,yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex() {
		$criteria = new CDbCriteria;
		$criteria->addCondition('t.level_type = 0 and t.dpid=:dpid and t.delete_flag=0');
		$criteria->order = ' t.lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		
		$pages = new CPagination(BrandUserLevel::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = BrandUserLevel::model()->findAll($criteria);
		
		$this->render('index',array(
				'models'=>$models,
				'pages' => $pages
		));
	}
	public function actionCreate() {
		$model = new BrandUserLevel ;
		$model->dpid = $this->companyId ;
		$is_sync = DataSync::getInitSync();
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('BrandUserLevel');
                        
                        $se=new Sequence("brand_user_level");
                        $model->lid = $se->nextval();
                        $model->create_at=date('Y-m-d H:i:s',time());
                        $model->update_at=date('Y-m-d H:i:s',time());
			$model->delete_flag = '0';
            $model->is_sync = $is_sync;
			if($model->save()) {
				Yii::app()->user->setFlash('success' , yii::t('app','添加成功'));
				$this->redirect(array('memberWxlevel/index' , 'companyId' => $this->companyId));
			}
		}
        
		$this->render('create' , array(
				'model' => $model
		));
	}
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$model = BrandUserLevel::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=>  $this->companyId));
		Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。			
                        
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('BrandUserLevel');
			$model->update_at=date('Y-m-d H:i:s',time());
                        //var_dump($model->attributes);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
				$this->redirect(array('memberWxlevel/index' , 'companyId' => $this->companyId));
			}
		}
		$this->render('update' , array(
			'model'=>$model
		));
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('lid');
		Until::isUpdateValid($ids,$this->companyId,$this);//0,表示企业任何时候都在云端更新。			
                if(!empty($ids)) {
			foreach ($ids as $id) {
				$model = BrandUserLevel::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $companyId)) ;
				if($model) {
					$model->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
				}
			}
			$this->redirect(array('memberWxlevel/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('memberWxlevel/index' , 'companyId' => $companyId)) ;
		}
	}
	
}