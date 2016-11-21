<?php
class PostableController extends BackendController
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
			$pages = new CPagination(PostableSync::model()->count($criteria));
			//	    $pages->setPageSize(1);
			$pages->applyLimit($criteria);
	        $models = PostableSync::model()->findAll($criteria);
	        $this->render("index",array(
	        	"models"=>$models,
	        	'pages'=>$pages
	        ));	
		}
        
        public function actionCreate(){
		$model = new PostableSync();
                
		$model->create_at = date('Y-m-d H:i:s',time());
		if(Yii::app()->request->isPostRequest) {
                   
			$model->attributes = Yii::app()->request->getPost('PostableSync');
                         //var_dump(Yii::app()->request->getPost('PostableSync'));exit;
			$se=new Sequence("postable_sync");
			$model->lid = $se->nextval();
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('postable/index','lid' => $model->lid , 'companyId'=>$this->companyId));
			}
		}
		
		$this->render('create' , array(
			'model' => $model ,
			
		));
	}
}
