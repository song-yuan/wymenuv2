<?php
class ScreenController extends BackendController
{
	public function actions() {
		return array(
				'upload'=>array(
						'class'=>'application.extensions.swfupload.SWFUploadAction',
						//注意这里是绝对路径,.EXT是文件后缀名替代符号
						'filepath'=>Helper::genFileName().'.EXT',
						//'onAfterUpload'=>array($this,'saveFile'),
				)
		);
	}
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId && $this->getAction()->getId() != 'upload') {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex(){
		
		$criteria = new CDbCriteria;
		
		$criteria->addCondition('dpid=:dpid and delete_flag=0');
		$criteria->order = 'lid desc';
		$criteria->params[':dpid'] = $this->companyId;
		
		$pages = new CPagination(Screen::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = Screen::model()->findAll($criteria);
		
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
		));
	}
	
	public function actionCreate(){
		$model = new Screen();
		$model->dpid = $this->companyId ;
		//$model->create_time = time();
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Screen');
			
            $se=new Sequence("screen");
            $model->lid = $se->nextval();
            $model->create_at = date('Y-m-d H:i:s',time());
            $model->update_at = date('Y-m-d H:i:s',time());
                       
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('screen/index' , 'companyId' => $this->companyId ));
			}
		}
		$this->render('create' , array(
			'model' => $model ,
		));
	}
	
	public function actionUpdate(){
		$id = Yii::app()->request->getParam('id');
		$model = Screen::model()->find('lid=:screenId and dpid=:dpid' , array(':screenId' => $id,':dpid'=>$this->companyId));
		
		$model->dpid = $this->companyId;
		Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Screen');
			$model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				$this->redirect(array('screen/index' , 'companyId' => $this->companyId ));
			}
		}
		
		$this->render('update' , array(
				'model' => $model ,
		));
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
            Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_screen set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('screen/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('screen/index' , 'companyId' => $companyId)) ;
		}
		
	}
	public function actionDiscuss(){
		$content = Yii::app()->request->getPost('content',null);
		
		$criteria = new CDbCriteria;
		
		$criteria->addCondition('dpid=:dpid and delete_flag=0');
		if($content){
			$criteria->addSearchCondition('content',$content);
		}
		$criteria->order = 'lid desc';
		$criteria->params[':dpid'] = $this->companyId;
		
		$pages = new CPagination(Discuss::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = Discuss::model()->findAll($criteria);
		
		$this->render('discuss',array(
				'models'=>$models,
				'pages'=>$pages,
				'content'=>$content
		));
	}
	
	public function actionDeleteDiscuss(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		if(Yii::app()->request->isPostRequest){
			$ids = Yii::app()->request->getPost('ids');
                Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
			if(!empty($ids)) {
				Yii::app()->db->createCommand('update nb_screen set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
					->execute(array( ':companyId' => $this->companyId));
				Yii::app()->user->setFlash('success' , yii::t('app','删除成功'));
				$this->redirect(array('screen/discuss' , 'companyId' => $companyId)) ;
			} else {
				Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
				$this->redirect(array('screen/discuss' , 'companyId' => $companyId)) ;
			}
		}else{
			$id =  Yii::app()->request->getParam('id');
			Yii::app()->db->createCommand('update nb_discuss set delete_flag=1 where lid = '.$id.' and dpid = :companyId')
				->execute(array( ':companyId' => $this->companyId));
			Yii::app()->user->setFlash('success' , yii::t('app','删除成功'));
			$this->redirect(array('screen/discuss' , 'companyId' => $companyId)) ;
		}
	}

}