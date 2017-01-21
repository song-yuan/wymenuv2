<?php
class PadController extends BackendController
{
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex(){
		$criteria = new CDbCriteria;
		$criteria->condition =  't.dpid='.$this->companyId .' and t.delete_flag=0';
		$pages = new CPagination(Pad::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		
		$models = Pad::model()->with("printer")->findAll($criteria);
		//var_dump($models);exit;
		$this->render('index',array(
			'models'=>$models,
			'pages'=>$pages
		));
	}
	public function actionCreate(){
		$model = new Pad() ;
		$model->dpid = $this->companyId ;
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Pad');
                        $se=new Sequence("pad");
                        $model->lid = $se->nextval();
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->update_at=date('Y-m-d H:i:s',time());
                        $model->delete_flag = '0';
			if($model->save()) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('pad/index','companyId' => $this->companyId));
			}
		}
                $printers = $this->getPrinters();
		$this->render('create' , array(
				'model' => $model,
                                'printers'=>$printers
		));
	}
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
                $model = Pad::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
		//Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Pad');
                        $model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('pad/index' , 'companyId' => $this->companyId));
			}
		}
                $printers = $this->getPrinters();
		$this->render('update' , array(
				'model'=>$model,
                                'printers'=>$printers
		));
	}
        public function actionBind(){
		$padId = Yii::app()->request->getParam('padId',0);
        //Until::isUpdateValid(array($padId),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
                $model = Pad::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $padId,':dpid'=> $this->companyId));
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Pad');
                        $model->update_at=date('Y-m-d H:i:s',time());
                        $model->is_bind="0";
                        //($model->attributes);var_dump(Yii::app()->request->getPost('Pad'));exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
				$this->redirect(array('pad/index' , 'companyId' => $this->companyId));
			}
		}
                $printers = $this->getPrinters();
		$this->renderPartial('bind' , array(
				'model'=>$model                                
		));
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
        //Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
                //var_dump($ids);exit;
		if(!empty($ids)) {
			foreach ($ids as $id) {
				$model = Pad::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $companyId)) ;
				if($model) {
					$model->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
				}
			}
			$this->redirect(array('pad/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' ,yii::t('app', '请选择要删除的项目'));
			$this->redirect(array('pad/index' , 'companyId' => $companyId)) ;
		}
	}
	
	private function getPrinters(){
		$printers = Printer::model()->findAll('dpid=:companyId and delete_flag=0' , array(':companyId' => $this->companyId)) ;
		$printers = $printers ? $printers : array();
		return CHtml::listData($printers, 'lid', 'name');
	}
}