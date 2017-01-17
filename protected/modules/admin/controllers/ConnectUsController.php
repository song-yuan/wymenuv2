<?php
class ConnectUsController extends BackendController
{
	
		public function beforeAction($action) {
			parent::beforeAction($action);
			if(!$this->companyId && $this->getAction()->getId() != 'upload') {
				Yii::app()->user->setFlash('error' , '前选择公司');
				$this->redirect(array('company/index'));
			}
			return true;
		}   
        public function actionIndex(){
     	
			$criteria = new CDbCriteria;
			$criteria->condition =  ' t.delete_flag=0';
			$pages = new CPagination(ConnectUs::model()->count($criteria));
			//	    $pages->setPageSize(1);
			$pages->applyLimit($criteria);
	        $models = ConnectUs::model()->findAll($criteria);
	        $this->render("index",array(
	        	"models"=>$models,
	        	'pages'=>$pages
	        ));	
		}
        
    public function actionCreate(){
		$model = new ConnectUs();
		$model->create_at = date('Y-m-d H:i:s',time());
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ConnectUs');
			$se=new Sequence("connect_us");
			$model->lid = $se->nextval();
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('connectUs/index','lid' => $model->lid , 'companyId'=>$this->companyId));
			}
		}
		
		$this->render('create' , array(
			'model' => $model ,
			
		));
	}
    public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$model = ConnectUs::model()->find('lid=:lid' , array(':lid'=>$lid));
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ConnectUs');
			
			$model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				$this->redirect(array('connectUs/index' , 'companyId' => $this->companyId ));
			}
		}

		$this->render('update' , array(
				'model' => $model ,
		));
	}
        public function actionDelete(){
		$companyId = Yii::app()->request->getParam('companyId');
		$ids = Yii::app()->request->getPost('ids');
        //var_dump(Yii::app()->request->getPost('ids'));exit();
           
		if(!empty($ids)) {
			Yii::app()->db->createCommand('delete from nb_connect_us where lid in ('.implode(',' , $ids).') ')->execute();
			$this->redirect(array('connectUs/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('connectUs/index' , 'companyId' => $companyId)) ;
		}
	}
}
