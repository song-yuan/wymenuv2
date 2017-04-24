<?php
class PrinterWayController extends BackendController
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
		$criteria->condition =  't.dpid='.$this->companyId .' and delete_flag=0';
		$pages = new CPagination(PrinterWay::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		
		$models = PrinterWay::model()->findAll($criteria);
		
		$this->render('index',array(
			'models'=>$models,
			'pages'=>$pages
		));
	}
	public function actionCreate(){
		$model = new PrinterWay();
		$model->dpid = $this->companyId ;
		
		if(Yii::app()->request->isPostRequest) {
			if(Yii::app()->user->role > User::SHOPKEEPER) {
				Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
				$this->redirect(array('printerway/index' , 'companyId' => $this->companyId)) ;
			}
			$model->attributes = Yii::app()->request->getPost('PrinterWay');
                        $se=new Sequence("print_way");
                        $model->lid = $se->nextval();
                        $code=new Sequence("phs_code");
                        $phs_code = $code->nextval();
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->update_at=date('Y-m-d H:i:s',time());
                        $model->phs_code = ProductCategory::getChscode($this->companyId, $model->lid, $phs_code);
                        $model->source = '0';
                        $model->delete_flag = '0';
                        //var_dump($model);exit;
			if($model->save()) {
				Yii::app()->user->setFlash('success' , yii::t('app','添加成功'));
				$this->redirect(array('printerWay/index','companyId' => $this->companyId));
			}
		}
		$this->render('create' , array(
				'model' => $model 
		));
	}
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
                //echo 'ddd';
		$model = PrinterWay::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
		//Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			if(Yii::app()->user->role > User::SHOPKEEPER) {
				Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
				$this->redirect(array('printerway/index' , 'companyId' => $this->companyId)) ;
			}
			$model->attributes = Yii::app()->request->getPost('PrinterWay');
                        $model->update_at=date('Y-m-d H:i:s',time());
                        //($model->attributes);var_dump(Yii::app()->request->getPost('Printer'));exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
				$this->redirect(array('printerWay/index' , 'companyId' => $this->companyId));
			}
		}
		$this->render('update' , array(
				'model'=>$model,
		));
	}
        
	public function actionDelete(){
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('printerway/index' , 'companyId' => $this->companyId)) ;
		}
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
        //Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
                //var_dump($ids);exit;
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_printer_way set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			
			Yii::app()->db->createCommand('update nb_printer_way_detail set delete_flag=1 where print_way_id in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('printerWay/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('printerWay/index' , 'companyId' => $companyId)) ;
		}
	}
        
        public function actionDetailIndex(){
		$pwlid = Yii::app()->request->getParam('lid');
                $criteria = new CDbCriteria;
                $criteria->with = array('floor','printer');
                //$criteria->with = 'printer';
		$criteria->condition =  't.dpid='.$this->companyId .' and t.print_way_id='.$pwlid.' and t.delete_flag=0';
                $criteria2 = new CDbCriteria;
		$criteria2->condition =  't.dpid='.$this->companyId .' and t.lid='.$pwlid.' and t.delete_flag=0';
		$pages = new CPagination(PrinterWayDetail::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		
		$models = PrinterWayDetail::model()->findAll($criteria);
		$pwmodel = PrinterWay::model()->find($criteria2);
		$this->render('detailindex',array(
			'models'=>$models,
                        'pwmodel'=>$pwmodel,
			'pages'=>$pages
		));
	}
	public function actionDetailCreate(){
		$model = new PrinterWayDetail();
		$model->dpid = $this->companyId ;
		$pwlid = Yii::app()->request->getParam('pwid');
                $model->print_way_id=$pwlid;
		if(Yii::app()->request->isPostRequest) {
			if(Yii::app()->user->role > User::SHOPKEEPER) {
				Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
				$this->redirect(array('printerway/detailindex' , 'companyId' => $this->companyId,'lid'=>$pwlid)) ;
			}
			$model->attributes = Yii::app()->request->getPost('PrinterWayDetail');
                        $se=new Sequence("print_way_detail");
                        $model->lid = $se->nextval();
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->delete_flag = '0';
                        $model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('printerWay/detailindex','companyId' => $this->companyId,'lid'=>$model->print_way_id));
			}
		}
                $printers = $this->getPrinters();
                $floors = $this->getFloors();
		$this->render('detailcreate' , array(
				'model' => $model,
                                'printers' => $printers,
                                'floors' => $floors
		));
	}
	public function actionDetailUpdate(){
		$lid = Yii::app()->request->getParam('lid');
        //Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		$model = PrinterWayDetail::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
		//var_dump($model);exit;
		if(Yii::app()->request->isPostRequest) {
			if(Yii::app()->user->role > User::SHOPKEEPER) {
				Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
				$this->redirect(array('printerway/detailindex' , 'companyId' => $this->companyId,'lid'=>$model->print_way_id)) ;
			}
			$model->attributes = Yii::app()->request->getPost('PrinterWayDetail');
            $model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('printerWay/detailindex' , 'companyId' => $this->companyId,'lid' => $model->print_way_id));
			}
		}
                $printers = $this->getPrinters();
                $floors = $this->getFloors();
		$this->render('detailupdate' , array(
				'model'=>$model,
                                'printers' => $printers,
                                'floors' => $floors
		));
	}
        
	public function actionDetailDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
        $printway = Yii::app()->request->getParam('pwid');
		$ids = Yii::app()->request->getPost('ids');
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('printerway/detailindex' , 'companyId' => $this->companyId,'lid'=>$printway)) ;
		}
                //Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_printer_way_detail set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('printerWay/detailindex' , 'companyId' => $companyId,'lid'=>$printway)) ;
		} else {
			Yii::app()->user->setFlash('error' ,yii::t('app', '请选择要删除的项目'));
			$this->redirect(array('printerWay/detailindex' , 'companyId' => $companyId,'lid'=>$printway)) ;
		}
	}
	
        private function getFloors(){
		$floors = Floor::model()->findAll('dpid=:companyId and delete_flag=0' , array(':companyId' => $this->companyId)) ;
		$floors = $floors ? $floors : array();
		return CHtml::listData($floors, 'lid', 'name');
	}
        
	private function getPrinters(){
		$printers = Printer::model()->findAll('dpid=:companyId and delete_flag=0' , array(':companyId' => $this->companyId)) ;
		$printers = $printers ? $printers : array();
		return CHtml::listData($printers, 'lid', 'name');
	}
}