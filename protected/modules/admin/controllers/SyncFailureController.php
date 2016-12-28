<?php
class SyncFailureController extends BackendController
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
			$pages = new CPagination(SyncFailure::model()->count($criteria));
			//	    $pages->setPageSize(1);
			$pages->applyLimit($criteria);
	        $models = SyncFailure::model()->findAll($criteria);
	        $this->render("index",array(
	        	"models"=>$models,
	        	'pages'=>$pages
	        ));	
		}
        

        public function actionDelete(){
		$companyId = Yii::app()->request->getParam('companyId');
		$ids = Yii::app()->request->getPost('ids');
        //var_dump(Yii::app()->request->getPost('ids'));exit();
           
		if(!empty($ids)) {
			Yii::app()->db->createCommand('delete from nb_sync_failure where lid in ('.implode(',' , $ids).') ')->execute();
			$this->redirect(array('syncFailure/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('syncFailure/index' , 'companyId' => $companyId)) ;
		}
	}
}
