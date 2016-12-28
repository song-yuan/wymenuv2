<?php
class UploadApkController extends BackendController
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

//         	$db = Yii::app()->db;
//         	$sql = 'select t.* from nb_app_version t where t.delete_flag = 0 and t.lid =(select max(k.lid) from nb_app_version k where delete_flag = 0 and k.app_type = 1) and t.app_type = 1';
//         	$command = $db->createCommand($sql);
//         	$appverifnos = $command->queryRow();
        	 
//         	$newverinfo = $appverifnos['app_version'];
//         	$newapptype = $appverifnos['app_type'];
//         	$newtype = $appverifnos['type'];
//         	$content = $appverifnos['content'];
//         	$url = $appverifnos['apk_url'];
//         	var_dump($appverifnos);exit;
        	
			$criteria = new CDbCriteria;
			$criteria->condition =  ' t.delete_flag=0';
			$pages = new CPagination(AppVersion::model()->count($criteria));
			//	    $pages->setPageSize(1);
			$pages->applyLimit($criteria);
	        $models = AppVersion::model()->findAll($criteria);
	        $this->render("index",array(
	        	"models"=>$models,
	        	'pages'=>$pages
	        ));	
		}
        
    public function actionCreate(){
    	$path = Yii::app()->basePath.'/../downloadApk';
		$model = new AppVersion();
                
		$model->create_at = date('Y-m-d H:i:s',time());
		if(Yii::app()->request->isPostRequest) {
                   
			$model->attributes = Yii::app()->request->getPost('AppVersion');
                         //var_dump(Yii::app()->request->getPost('PostableSync'));exit;
			$se=new Sequence("app_version");
			$model->lid = $se->nextval();
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			//$model->apk_url = $path.$model->apk_url;
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('uploadApk/index','lid' => $model->lid , 'companyId'=>$this->companyId));
			}
		}
		
		$this->render('create' , array(
			'model' => $model ,
			
		));
	}
    public function actionUpdate(){
    	//$path = Yii::app()->basePath.'/../downloadApk';
    	//$path = Yii::app()->request->baseUrl;
    	//var_dump($path);exit;
		$lid = Yii::app()->request->getParam('lid');
		$model = AppVersion::model()->find('lid=:lid' , array(':lid'=>$lid));
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('AppVersion');
			
			$model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				$this->redirect(array('uploadApk/index' , 'companyId' => $this->companyId ));
			}
		}

		$this->render('update' , array(
				'model' => $model ,
		));
	}
        public function actionDelete(){
		$companyId = Yii::app()->request->getParam('companyId');
		$ids = Yii::app()->request->getPost('ids');
              //  var_dump(Yii::app()->request->getPost('ids'));exit();
           
		if(!empty($ids)) {
			Yii::app()->db->createCommand('delete from nb_postable_sync where lid in ('.implode(',' , $ids).') ')->execute();
			$this->redirect(array('postable/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('postable/index' , 'companyId' => $companyId)) ;
		}
	}
}
