<?php
class FeedbackController extends BackendController
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
		$allflag = Yii::app()->request->getParam('allflag',0);
		$criteria = new CDbCriteria;
		$criteria->addCondition('t.dpid=:dpid and t.allflag=:allflag and t.delete_flag=0');
		$criteria->order = ' t.lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		$criteria->params[':allflag']=$allflag; 
		
		$pages = new CPagination(Feedback::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = Feedback::model()->findAll($criteria);
		
		$this->render('index',array(
				'models'=>$models,
				'pages' => $pages,
				'allflag'=>$allflag
		));
	}
	public function actionCreate() {
		$allflag = Yii::app()->request->getParam('allflag',0);
		$model = new Feedback ;
		$model->dpid = $this->companyId ;
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Feedback');
                        
                        $se=new Sequence("feedback");
                        $model->lid = $se->nextval();
                        $model->allflag = $allflag;
                        $model->create_at=date('Y-m-d H:i:s',time());
                        $model->update_at=date('Y-m-d H:i:s',time());
			$model->delete_flag = '0';
                        //var_dump($model);exit;
			if($model->save()) {
				Yii::app()->user->setFlash('success' , yii::t('app','添加成功'));
				$this->redirect(array('feedback/index' , 'companyId' => $this->companyId,'allflag'=>$allflag));
			}
		}
		$this->render('create' , array(
				'model' => $model , 
				'allflag' => $allflag
		));
	}
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$allflag = Yii::app()->request->getParam('allflag');
		$model = Feedback::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=>  $this->companyId));
		//Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。			
                        
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Feedback');
			$model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
				$this->redirect(array('feedback/index' , 'allflag'=>$allflag, 'companyId' => $this->companyId));
			}
		}
		$this->render('update' , array(
			'model'=>$model,
			'allflag' => $allflag
		));
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('lid');
		$allflag = Yii::app()->request->getParam('allflag',0);
		//Until::isUpdateValid($ids,$this->companyId,$this);//0,表示企业任何时候都在云端更新。			
                if(!empty($ids)) {
			foreach ($ids as $id) {
				$model = Feedback::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $companyId)) ;
				if($model) {
					$model->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
				}
			}
			$this->redirect(array('feedback/index' , 'companyId' => $companyId,'allflag'=>$allflag)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('feedback/index' , 'companyId' => $companyId,'allflag'=>$allflag)) ;
		}
	}
	
}