<?php
class ProductBomController extends BackendController
{
	public function actionIndex(){
		//$pbId=Yii::app()->request->getParam('lid');
		$criteria = new CDbCriteria;
		//$criteria->with=array('product');
		$criteria->order='t.dpid desc';
		$criteria->condition =  't.dpid='.$this->companyId .' and t.delete_flag=0 ';
		$pages = new CPagination(Product::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		
		$models = Product::model()->findAll($criteria);
		//var_dump($models);
		$this->render('index',array(
			'models'=>$models,
			'pages'=>$pages
		));
	}

    public function actionDetailIndex(){
		$pblid = Yii::app()->request->getParam('lid');
        $criteria = new CDbCriteria;
        $criteria->with = array('material');
        $criteria->order =  't.lid';
        //$criteria->with = 'printer';
		$criteria->condition =  't.dpid='.$this->companyId .' and t.material_id='.$pblid.' and t.delete_flag=0';
		//$criteria2 = new CDbCriteria;
		//$criteria2->condition =  't.dpid='.$this->companyId .' and t.product_id='.$pblid.' and t.delete_flag=0';
       // $psmodel = ProductBom::model()->find($criteria2);
		$pages = new CPagination(ProductBom::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = ProductBom::model()->findAll($criteria);
     	$this->render('detailindex',array(
			'models'=>$models,
     		//'psmodel'=>$psmodel,
			'pages'=>$pages,
     	'pblid'=>$pblid,
		));
	}
	public function actionDetailCreate(){
		
		$pblid = Yii::app()->request->getParam('lid');
		//var_dump($pblid);exit;
		$model = new ProductBom();
		$model->dpid = $this->companyId ;
		//$pslid = Yii::app()->request->getParam('psid');
       // $model->set_id=$pslid;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductBom');
            $se=new Sequence("porduct_bom");
            $model->lid = $se->nextval();
            $model->create_at = date('Y-m-d H:i:s',time());
            $model->delete_flag = '0';
            $modelsp= Yii::app()->db->createCommand('select count(*) as num from nb_product_bom t where t.dpid='.$this->companyId.' and t.delete_flag=0')->queryRow();
           
			if($model->save()) {
                            
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('productBom/detailindex','companyId' => $this->companyId,'lid'=>$model->set_id));
			}
		}
                //$maxgroupno=$this->getMaxGroupNo($pslid);
                $categories = $this->getCategories();
                $categoryId=0;
                $products = $this->getProducts($categoryId);
                $productslist=CHtml::listData($products, 'lid', 'product_name');
		$this->render('detailcreate' , array(
				'model' => $model,
                'categories' => $categories,
                'categoryId' => $categoryId,
                'products' => $productslist,
		'pblid'=>$pblid,
                //'maxgroupno'=>$maxgroupno
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
                        $modelsp= Yii::app()->db->createCommand('select count(*) as num from nb_product_set_detail t where t.dpid='.$this->companyId.' and t.set_id='.$model->set_id.' and t.delete_flag=0')->queryRow();
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
        
        public function actionIsDoubleSetDetail(){
		$productId = Yii::app()->request->getParam('productid',0);
                $productSetId = Yii::app()->request->getParam('productSetId',0);
                $companyId = Yii::app()->request->getParam('companyId',0);
				
                $treeDataSource = array('data'=>FALSE,'delay'=>400);
		
                $product= ProductSetDetail::model()->find('t.dpid = :dpid and t.set_id = :setid and t.product_id = :productid and t.delete_flag=0',array(':dpid'=>$companyId,':setid'=>$productSetId,':productid'=>$productId));
                //var_dump($productId,$productSetId,$companyId,$product);exit;
                if(!empty($product))
                {
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