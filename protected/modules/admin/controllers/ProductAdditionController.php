<?php
class ProductAdditionController extends BackendController
{
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex() {
		$categoryId = Yii::app()->request->getParam('cid',0);
		$criteria = new CDbCriteria;
		$criteria->with = 'productAddition';
                if($categoryId!=0)
                {
                    $criteria->addCondition('t.dpid=:dpid and t.delete_flag=0 and t.is_show=1 and t.category_id ='.$categoryId);
                }else{
                    $criteria->addCondition('t.dpid=:dpid and t.is_show=1 and t.delete_flag=0');
                }
		$criteria->order = ' lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		$categories = $this->getCategories();
		$pages = new CPagination(Product::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = Product::model()->findAll($criteria);
//		var_dump($models[0]);exit;
		$this->render('index',array(
				'models'=>$models,
                                'categories'=>$categories,
                                'categoryId'=>$categoryId,
				'pages' => $pages,
		));
	}
	
        public function actionDetail(){
		$pwlid = Yii::app()->request->getParam('lid',0);
                $criteria = new CDbCriteria;
                $criteria->with = array('sproduct');
                //$criteria->with = 'printer';
		$criteria->condition =  't.dpid='.$this->companyId .' and t.mproduct_id='.$pwlid.' and t.delete_flag=0 and sproduct.delete_flag=0';
                $criteria2 = new CDbCriteria;
		$criteria2->condition =  't.dpid='.$this->companyId .' and t.lid='.$pwlid.' and t.delete_flag=0';
		$pages = new CPagination(ProductAddition::model()->count($criteria));
		$pages->applyLimit($criteria);
		
		$models = ProductAddition::model()->findAll($criteria);
                
		$psmodel = Product::model()->find($criteria2);
               // var_dump($psmodel);exit;
		$this->render('detail',array(
			'models'=>$models,
                        'psmodel'=>$psmodel,
			'pages'=>$pages
		));
	}
        
        public function actionCreate(){
		$model = new ProductAddition();
		$model->dpid = $this->companyId ;
		$pslid = Yii::app()->request->getParam('psid');
                $model->mproduct_id=$pslid;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductAddition');
                        //var_dump($model->attributes);exit;
                        $se=new Sequence("product_addition");
                        $model->lid = $se->nextval();
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->update_at=date('Y-m-d H:i:s',time());
                        $model->delete_flag = '0';
                        //var_dump($model);exit;
			if($model->save()) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('productAddition/detail','companyId' => $this->companyId,'lid'=>$model->mproduct_id));
			}
		}
                $categories = $this->getCategories();
                $categoryId=0;
                $products = $this->getProducts($categoryId);
                $productslist=CHtml::listData($products, 'lid', 'product_name');
		$this->render('detailcreate' , array(
				'model' => $model,
                                'categories' => $categories,
                                'categoryId' => $categoryId,
                                'products' => $productslist
		));
	}
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
                //echo 'ddd';
		$model = ProductAddition::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
		//Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductAddition');
                        $model->update_at=date('Y-m-d H:i:s',time());
                        //var_dump($model);var_dump(Yii::app()->request->getPost('ProductSetDetail'));exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('productAddition/detail' , 'companyId' => $this->companyId,'lid' => $model->mproduct_id));
			}
		}
                //$printers = $this->getPrinters();
                $categories = $this->getCategories();
                $categoryId=  $this->getCategoryId($model->sproduct_id,  $this->companyId);
                $products = $this->getProducts($categoryId);
                $productslist=CHtml::listData($products, 'lid', 'product_name');
		$this->render('detailupdate' , array(
				'model'=>$model,
                                'categories' => $categories,
                                'categoryId' => $categoryId,
                                'products' => $productslist
		));
	}
        
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
                $printset = Yii::app()->request->getParam('psid');
		$ids = Yii::app()->request->getPost('ids');
        //Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。;
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_product_addition set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('productAddition/detail' , 'companyId' => $companyId,'lid'=>$printset)) ;
		} else {
			Yii::app()->user->setFlash('error' ,yii::t('app', '请选择要删除的项目'));
			$this->redirect(array('productAddition/detail' , 'companyId' => $companyId,'lid'=>$printset)) ;
		}
	}
        
        public function actionGetChildren(){
		$categoryId = Yii::app()->request->getParam('pid',0);
		if(!$categoryId){
			Yii::app()->end(json_encode(array('data'=>array(),'delay'=>400)));
		}
		
                $treeDataSource = array('data'=>array(),'delay'=>400);
		$produts=  $this->getProducts($categoryId);
	
		foreach($produts as $c){
			$tmp['name'] = $c['product_name'];
			$tmp['id'] = $c['lid'];
			$treeDataSource['data'][] = $tmp;
		}
		Yii::app()->end(json_encode($treeDataSource));
	}
        
        private function getCategories(){
		$criteria = new CDbCriteria;
		$criteria->with = 'company';
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId ;
		$criteria->order = ' tree,t.lid asc ';
		
		$models = ProductCategory::model()->findAll($criteria);
                
		//return CHtml::listData($models, 'lid', 'category_name','pid');
		$options = array();
		$optionsReturn = array(yii::t('app','--请选择分类--'));
		if($models) {
			foreach ($models as $model) {
				if($model->pid == '0') {
					$options[$model->lid] = array();
				} else {
					$options[$model->pid][$model->lid] = $model->category_name;
				}
			}
                        //var_dump($options);exit;
		}
		foreach ($options as $k=>$v) {
                    //var_dump($k,$v);exit;
			$model = ProductCategory::model()->find('t.lid = :lid and dpid=:dpid',array(':lid'=>$k,':dpid'=>  $this->companyId));
			$optionsReturn[$model->category_name] = $v;
		}
		return $optionsReturn;
	}
        
        private function getProducts($categoryId){
                if($categoryId==0)
                {
                    //var_dump ('2',$categoryId);exit;
                    $products = Product::model()->findAll('dpid=:companyId and is_show=0 and delete_flag=0' , array(':companyId' => $this->companyId));
                }else{
                    //var_dump ('3',$categoryId);exit;
                    $products = Product::model()->findAll('dpid=:companyId and is_show=0 and category_id=:categoryId and delete_flag=0' , array(':companyId' => $this->companyId,':categoryId'=>$categoryId)) ;
                }
                $products = $products ? $products : array();
                //var_dump($products);exit;
                return $products;
		//return CHtml::listData($products, 'lid', 'product_name');
	}
        
        private function getCategoryId($lid,$dpid){
                $db = Yii::app()->db;
                $sql = "SELECT category_id from nb_product where dpid=:dpid and lid=:lid";
                $command=$db->createCommand($sql);
                $command->bindValue(":lid" , $lid);
                $command->bindValue(":dpid" , $dpid);
                return $command->queryScalar();
	}
}