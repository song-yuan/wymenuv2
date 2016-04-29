<?php
class ProductSetController extends BackendController
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
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
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
			$se=new Sequence("porduct_set");
			$model->lid = $se->nextval();
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			$model->delete_flag = '0';
			$py=new Pinyin();
			$model->simple_code = $py->py($model->set_name);
			//var_dump($model);exit;
			if($model->save()) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
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
		Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductSet');
                        $py=new Pinyin();
                        $model->simple_code = $py->py($model->set_name);
                        $model->update_at=date('Y-m-d H:i:s',time());
                        //var_dump($model->attributes);var_dump(Yii::app()->request->getPost('ProductSet'));exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
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
                Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_product_set set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			
			Yii::app()->db->createCommand('update nb_product_set_detail set delete_flag=1 where set_id in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('productSet/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' ,yii::t('app', '请选择要删除的项目'));
			$this->redirect(array('productSet/index' , 'companyId' => $companyId)) ;
		}
	}
        
        public function actionDetailIndex(){
		$pwlid = Yii::app()->request->getParam('lid');// var_dump($pwlid);exit;
                $criteria = new CDbCriteria;
                $criteria->with = array('product');
                $criteria->order =  't.group_no';
                //$criteria->with = 'printer';
		$criteria->condition =  't.dpid='.$this->companyId .' and t.set_id='.$pwlid.' and t.delete_flag=0 and product.delete_flag=0';
                $criteria2 = new CDbCriteria;
		$criteria2->condition =  't.dpid='.$this->companyId .' and t.lid='.$pwlid.' and t.delete_flag=0';
                
		$pages = new CPagination(ProductSetDetail::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		
		$models = ProductSetDetail::model()->findAll($criteria);
                
		$psmodel = ProductSet::model()->find($criteria2);
               // var_dump($psmodel);exit;
		$this->render('detailindex',array(
			'models'=>$models,
            'psmodel'=>$psmodel,
			'pages'=>$pages
		));
	}

	public function actionDetailCreate(){
		$model = new ProductSetDetail();
		$model->dpid = $this->companyId ;
		$pslid = Yii::app()->request->getParam('psid'); //var_dump($pslid);exit;
        $model->set_id=$pslid;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductSetDetail');
            $se=new Sequence("porduct_set_detail");
            $model->lid = $se->nextval();
            $model->create_at = date('Y-m-d H:i:s',time());
            $model->delete_flag = '0';
            $modelsp= Yii::app()->db->createCommand('select count(*) as num from nb_product_set_detail t where t.dpid='.$this->companyId.' and t.set_id='.$pslid.' and t.delete_flag=0 and group_no='.$model->group_no)->queryRow();
            //var_dump($modelsp);exit;
            if($model->is_select=="1")
            {
                $sqlgroup="update nb_product_set_detail set is_select=0 where group_no=".$model->group_no." and dpid=".$this->companyId." and set_id=".$model->set_id;
                Yii::app()->db->createCommand($sqlgroup)->execute();
            }
			if($model->save()) {
                            
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('productSet/detailindex','companyId' => $this->companyId,'lid'=>$model->set_id));
			}
		}
                $maxgroupno=$this->getMaxGroupNo($pslid);
                $categories = $this->getCategories();
                $categoryId=0;
                $products = $this->getProducts($categoryId);
                $productslist=CHtml::listData($products, 'lid', 'product_name');
		$this->render('detailcreate' , array(
				'model' => $model,
				'categories' => $categories,
				'categoryId' => $categoryId,
				'products' => $productslist,
				'maxgroupno'=>$maxgroupno
		));
	}
	public function actionDetailUpdate(){
		$lid = Yii::app()->request->getParam('lid');
                //echo 'ddd';
		$model = ProductSetDetail::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
		//var_dump($model);exit;
                Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductSetDetail');
			$model->update_at = date('Y-m-d H:i:s',time());
			//只有一个时选中，如果第一个必须选中，后续的，判断是选中，必须取消其他选中
			$modelsp= Yii::app()->db->createCommand('select count(*) as num from nb_product_set_detail t where t.dpid='.$this->companyId.' and t.set_id='.$model->set_id.' and t.delete_flag=0 and group_no='.$model->group_no)->queryRow();
			//var_dump($modelsp);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('productSet/detailindex' , 'companyId' => $this->companyId,'lid' => $model->set_id));
			}
		}
                $maxgroupno=$this->getMaxGroupNo($model->set_id);
                //$printers = $this->getPrinters();
                $categories = $this->getCategories();
                $categoryId=  $this->getCategoryId($lid);
                $products = $this->getProducts($categoryId);
                $productslist=CHtml::listData($products, 'lid', 'product_name');
		$this->render('detailupdate' , array(
				'model'=>$model,
                'categories' => $categories,
                'categoryId' => $categoryId,
                'products' => $productslist,
                'maxgroupno' => $maxgroupno
		));
	}
        
	public function actionDetailDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
                $printset = Yii::app()->request->getParam('psid');                
		$ids = Yii::app()->request->getPost('ids');
                Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_product_set_detail set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('productSet/detailindex' , 'companyId' => $companyId,'lid'=>$printset)) ;
		} else {
			Yii::app()->user->setFlash('error' ,yii::t('app', '请选择要删除的项目'));
			$this->redirect(array('productSet/detailindex' , 'companyId' => $companyId,'lid'=>$printset)) ;
		}
	}	
        
        public function actionGetChildren(){
		$categoryId = Yii::app()->request->getParam('pid',0);
		$productSetId = Yii::app()->request->getParam('$productSetId',0);
           // var_dump($productSetId);exit;
		if(!$categoryId){
			Yii::app()->end(json_encode(array('data'=>array(),'delay'=>400)));
		}
        $treeDataSource = array('data'=>array(),'delay'=>400);
		$produts=  $this->getProducts($categoryId);
		//var_dump($produts);exit;
		foreach($produts as $c){
			$tmp['name'] = $c['product_name'];
			$tmp['id'] = $c['lid'];
			$treeDataSource['data'][] = $tmp;
		}
		Yii::app()->end(json_encode($treeDataSource));
	}
        
   public function actionIsDoubleSetDetail(){
		$productId = Yii::app()->request->getParam('productid',0);
        $productSetId = Yii::app()->request->getParam('productSetId',0);
        $companyId = Yii::app()->request->getParam('companyId',0);
        $treeDataSource = array('data'=>FALSE,'delay'=>400);
        $product= ProductSetDetail::model()->find('t.dpid = :dpid and t.set_id = :setid and t.product_id = :productid and t.delete_flag=0',array(':dpid'=>$companyId,':setid'=>$productSetId,':productid'=>$productId));
        //var_dump($productId,$productSetId,$companyId,$product);exit;
        if(!empty($product)){
            $treeDataSource['data'] = TRUE;
		}
		Yii::app()->end(json_encode($treeDataSource));
	}
        
	private function getProducts($categoryId){
                if($categoryId==0)
                {
                    //var_dump ('2',$categoryId);exit;
                    $products = Product::model()->findAll('dpid=:companyId and delete_flag=0' , array(':companyId' => $this->companyId));
                }else{
                    //var_dump ('3',$categoryId);exit;
                    $products = Product::model()->findAll('dpid=:companyId and category_id=:categoryId and delete_flag=0' , array(':companyId' => $this->companyId,':categoryId'=>$categoryId)) ;
                }
                $products = $products ? $products : array();
                //var_dump($products);exit;
                return $products;
		//return CHtml::listData($products, 'lid', 'product_name');
	}
        
        private function getSetProducts($categoryId,$productSetId){
                $db = Yii::app()->db;
                
                if($categoryId==0)
                {
                    $sql = "SELECT lid,product_name from nb_product where dpid=:companyId and delete_flag=0 and lid not in (select product_id from nb_product_set_detail where set_id=:productSetId and dpid=:dpid)";
                    $command=$db->createCommand($sql);
                    $command->bindValue(":companyId" , $this->companyId);
                    $command->bindValue(":dpid" , $this->companyId);
                    $command->bindValue(":productSetId" , $productSetId);
                }else{
                    $sql = "SELECT lid,product_name from nb_product where dpid=:companyId and category_id=:categoryId and delete_flag=0 and lid not in (select product_id from nb_product_set_detail where set_id=:productSetId and dpid=:dpid)";
                    $command=$db->createCommand($sql);
                    $command->bindValue(":companyId" , $this->companyId);
                    $command->bindValue(":dpid" , $this->companyId);
                    $command->bindValue(":productSetId" , $productSetId);
                    $command->bindValue(":categoryId" , $categoryId);
                }                
                $products=$command->queryAll();
                $products = $products ? $products : array();
                //var_dump($sql);exit;
                return $products;
		//return CHtml::listData($products, 'lid', 'product_name');
	}
        
        private function getCategoryId($lid){
                $db = Yii::app()->db;
                $sql = "SELECT category_id from nb_product_set_detail sd,nb_product p where sd.dpid=p.dpid and sd.product_id=p.lid and sd.lid=:lid";
                $command=$db->createCommand($sql);
                $command->bindValue(":lid" , $lid);
                return $command->queryScalar();
	}
        
        private function getMaxGroupNo($psid){
                $db = Yii::app()->db;
                $sql = "SELECT max(group_no) from nb_product_set_detail where dpid=:dpid and set_id=:psid";
                $command=$db->createCommand($sql);
                $command->bindValue(":dpid" , $this->companyId);
                $command->bindValue(":psid" , $psid);
                return $command->queryScalar();
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
        
        private function getCategoryList(){
		$categories = ProductCategory::model()->findAll('delete_flag=0 and dpid=:companyId' , array(':companyId' => $this->companyId)) ;
		//var_dump($categories);exit;
		return CHtml::listData($categories, 'lid', 'category_name');
	}
}