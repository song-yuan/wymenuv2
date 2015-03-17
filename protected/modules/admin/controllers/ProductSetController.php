<?php
class ProductSetController extends BackendController
{
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , '请选择公司');
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex(){
		$criteria = new CDbCriteria;
		$criteria->condition =  't.dpid='.$this->companyId .' and delete_flag=0';
		$pages = new CPagination(ProductSet::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		
		$models = ProductSet::model()->findAll($criteria);
		
		$this->render('index',array(
			'models'=>$models,
			'pages'=>$pages
		));
	}
	public function actionCreate(){
		$model = new ProductSet();
		$model->dpid = $this->companyId ;
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductSet');
                        $se=new Sequence("product_set");
                        $model->lid = $se->nextval();
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->delete_flag = '0';
                        //var_dump($model);exit;
			if($model->save()) {
				Yii::app()->user->setFlash('success' , '添加成功');
				$this->redirect(array('productSet/index','companyId' => $this->companyId));
			}
		}
		$this->render('create' , array(
				'model' => $model 
		));
	}
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
                //echo 'ddd';
		$model = ProductSet::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
		//var_dump($model);exit;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductSet');
                        //($model->attributes);var_dump(Yii::app()->request->getPost('Printer'));exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' , '修改成功');
				$this->redirect(array('productSet/index' , 'companyId' => $this->companyId));
			}
		}
		$this->render('update' , array(
				'model'=>$model,
		));
	}
        
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
                //var_dump($ids);exit;
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_product_set set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			
			Yii::app()->db->createCommand('update nb_product_set_detail set delete_flag=1 where set_id in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('productSet/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , '请选择要删除的项目');
			$this->redirect(array('productSet/index' , 'companyId' => $companyId)) ;
		}
	}
        
        public function actionDetailIndex(){
		$pwlid = Yii::app()->request->getParam('lid');
                $criteria = new CDbCriteria;
                $criteria->with = array('product');
                //$criteria->with = 'printer';
		$criteria->condition =  't.dpid='.$this->companyId .' and t.set_id='.$pwlid.' and t.delete_flag=0';
                $criteria2 = new CDbCriteria;
		$criteria2->condition =  't.dpid='.$this->companyId .' and t.lid='.$pwlid.' and t.delete_flag=0';
		$pages = new CPagination(ProductSetDetail::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		
		$models = ProductSetDetail::model()->findAll($criteria);
		$psmodel = ProductSet::model()->find($criteria2);
		$this->render('detailindex',array(
			'models'=>$models,
                        'psmodel'=>$psmodel,
			'pages'=>$pages
		));
	}
	public function actionDetailCreate(){
		$model = new ProductSetDetail();
		$model->dpid = $this->companyId ;
		$pslid = Yii::app()->request->getParam('psid');
                $model->set_id=$pslid;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductSetDetail');
                        $se=new Sequence("product_set_detail");
                        $model->lid = $se->nextval();
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->delete_flag = '0';
                        //var_dump($model);exit;
			if($model->save()) {
				Yii::app()->user->setFlash('success' , '添加成功');
				$this->redirect(array('productSet/detailindex','companyId' => $this->companyId,'lid'=>$model->print_way_id));
			}
		}
                //$printers = $this->getPrinters();
                $products = $this->getProducts();
		$this->render('detailcreate' , array(
				'model' => $model,
                                'products' => $products
		));
	}
	public function actionDetailUpdate(){
		$lid = Yii::app()->request->getParam('lid');
                //echo 'ddd';
		$model = ProductSetDetail::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
		//var_dump($model);exit;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductSetDetail');
                        //var_dump($model);var_dump(Yii::app()->request->getPost('ProductSetDetail'));exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' , '修改成功');
				$this->redirect(array('productSet/detailindex' , 'companyId' => $this->companyId,'lid' => $model->set_id));
			}
		}
                //$printers = $this->getPrinters();
                $products = $this->getProducts();
		$this->render('detailupdate' , array(
				'model'=>$model,
                                'products' => $products
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
			$this->redirect(array('printerSet/detailindex' , 'companyId' => $companyId,'lid'=>$printset)) ;
		} else {
			Yii::app()->user->setFlash('error' , '请选择要删除的项目');
			$this->redirect(array('productSet/detailindex' , 'companyId' => $companyId,'lid'=>$printset)) ;
		}
	}
	
        
	private function getProducts(){
		$products = Printer::model()->findAll('dpid=:companyId and delete_flag=0' , array(':companyId' => $this->companyId)) ;
		$products = $products ? $products : array();
		return CHtml::listData($products, 'lid', 'name');
	}
}