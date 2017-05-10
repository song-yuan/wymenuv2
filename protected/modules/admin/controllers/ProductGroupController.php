<?php
class ProductGroupController extends BackendController
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
		$criteria->order = 't.lid asc';
		$pages = new CPagination(ProductGroup::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		
		$models = ProductGroup::model()->findAll($criteria);
		
		$this->render('index',array(
			'models'=>$models,
			'pages'=>$pages
		));
	}
	public function actionCreate(){
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('productGroup/index' , 'companyId' => $this->companyId)) ;
		}
		$msg = '';
		$model = new ProductGroup();
		$model->dpid = $this->companyId ;
		$status = '';
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductGroup');
			$se=new Sequence("product_group");
			$model->lid = $lid = $se->nextval();
			$code=new Sequence("phs_code");
			$pshs_code = $code->nextval();
			

			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			$model->pg_code = ProductCategory::getChscode($this->companyId, $lid, $pshs_code);
			$model->source = 0;
			$model->delete_flag = '0';
	
			
			if($model->save()) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('productGroup/index','lid' => $model->lid , 'companyId' => $model->dpid , 'status' => ''));
			}
		}
		$this->render('create' , array(
				'model' => $model,
				'status'=> $status,
		));
	}
	public function actionUpdate(){
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('productGroup/index' , 'companyId' => $this->companyId));
		}
		$msg = '';
		$lid = Yii::app()->request->getParam('lid');
		$status = Yii::app()->request->getParam('status');
		$papage = Yii::app()->request->getParam('papage');
                //echo 'ddd';
		$model = ProductGroup::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
		//Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductGroup');
            // $py=new Pinyin();
            // $model->simple_code = $py->py($model->set_name);
            $model->update_at = date('Y-m-d H:i:s',time());

            //var_dump($model->attributes);var_dump(Yii::app()->request->getPost('ProductSet'));exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('productGroup/index' , 'companyId' => $this->companyId ,'page' => $papage));
			}
		}
		$this->render('update' , array(
				'model'=>$model,
				'status'=>$status,
				'papage'=>$papage,
		));
	}
	
	public function actionDelete(){
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('productGroup/index' , 'companyId' => $this->companyId)) ;
		}
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		
		$papage = Yii::app()->request->getParam('papage');
		//var_dump($papage);exit;
		$ids = Yii::app()->request->getPost('ids');
        //Until::isUpdateValid(array($ids),$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_product_group set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			
			Yii::app()->db->createCommand('update nb_product_group_detail set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('productGroup/index' , 'companyId' => $companyId, 'page' => $papage));
		} else {
			Yii::app()->user->setFlash('error' ,yii::t('app', '请选择要删除的项目'));
			$this->redirect(array('productGroup/index' , 'companyId' => $companyId, 'page' => $papage));
		}
	}
        
        public function actionDetailIndex(){

		$pwlid = Yii::app()->request->getParam('lid');
		$status = Yii::app()->request->getParam('status',0);
		$papage = Yii::app()->request->getParam('papage');
		//var_dump($pwlid);exit;
		$criteria = new CDbCriteria;
        $criteria->with = array('product');
        $criteria->order = 't.prod_group_id';
        //var_dump($criteria);exit;
		$criteria->condition =  't.dpid='.$this->companyId .' and t.prod_group_id='.$pwlid.' and t.delete_flag=0 and product.delete_flag=0';
		$criteria2 = new CDbCriteria;
		$criteria2->condition = 't.dpid='.$this->companyId .' and t.lid='.$pwlid.' and t.delete_flag=0'; 

		$pages = new CPagination(ProductGroupDetail::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		
		$models = ProductGroupDetail::model()->findAll($criteria);
                
		$psmodel = ProductGroup::model()->find($criteria2);
        //var_dump($models);exit;
		$this->render('detailindex',array(
			'models'=>$models,
            'psmodel'=>$psmodel,
			'pages'=>$pages,
			'status'=>$status,
			'papage'=>$papage,
		));
	}

	public function actionDetailCreate(){
		
		$model = new ProductGroupDetail();
		$model->dpid = $this->companyId ;
		$prodgroupId = Yii::app()->request->getParam('prodgroupId');
		$papage = Yii::app()->request->getParam('papage');
		$status = '';
		$type = 0;
        $model->prod_group_id = $prodgroupId;
		//var_dump($model);exit;
        if(Yii::app()->user->role > User::SHOPKEEPER) {
        	Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
        	$this->redirect(array('productGroup/detailindex' , 'companyId' => $this->companyId,'prodgroupId' => $prodgroupId , 'papage'=>$papage)) ;
        }
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductGroupDetail');
			$isselect = Yii::app()->request->getParam('isselect');
			$number = Yii::app()->request->getParam('number');
			//var_dump($model);exit;
            $se=new Sequence("product_group_detail");
            $model->lid = $se->nextval();
            $model->create_at = date('Y-m-d H:i:s',time());
            $model->update_at = date('Y-m-d H:i:s',time());
            $model->delete_flag = '0';
            $model->is_select = $isselect;
            $model->number = $number;
            $model->prod_group_id = $prodgroupId;
    
			if($model->save()) {
                            
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('productGroup/detailindex','companyId' => $this->companyId,'lid'=>$model->prod_group_id,'papage'=>$papage));
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
				'prodgroupId'=>$prodgroupId,				
				'products' => $productslist,
				'type'=>$type,
				'status'=>$status,
				'papage'=>$papage,
		));
	}
	public function actionDetailUpdate(){
		
		$lid = Yii::app()->request->getParam('lid');
		$type = Yii::app()->request->getParam('type');
		$status = Yii::app()->request->getParam('status');
		$papage = Yii::app()->request->getParam('papage');
		
		
		$model = ProductGroupDetail::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('productGroup/detailindex' , 'companyId' => $this->companyId,'lid' => $model->prod_group_id)) ;
		}
        //Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductGroupDetail');
			$model->update_at = date('Y-m-d H:i:s',time());
			//只有一个时选中，如果第一个必须选中，后续的，判断是选中，必须取消其他选中
			$modelsp= Yii::app()->db->createCommand('select count(*) as num from nb_product_group_detail t where t.dpid='.$this->companyId.' and t.lid='.$model->prod_group_id.' and t.delete_flag=0')->queryRow();
		
			if($model->save()){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('productGroup/detailindex' , 'companyId' => $this->companyId,'lid' => $model->prod_group_id ,'status'=>$status));
			} 
		}
                $categories = $this->getCategories();
                $categoryId=  $this->getCategoryId($lid);
                $products = $this->getProducts($categoryId);
                $productslist=CHtml::listData($products, 'lid', 'product_name');
				$this->render('detailupdate' , array(
				'model' => $model,
                'categories' => $categories,
                'categoryId' => $categoryId,
				'prodgroupId' => $model->prod_group_id,
                'products' => $productslist,
				'type' => $type,
				'status' => $status,
				'papage' => $papage,
		));
	}
        
	public function actionDetailDelete(){
		
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
        $prodgroupId = Yii::app()->request->getParam('prodgroupId'); 
        //var_dump($prodgroupId);exit; 
        $papage = Yii::app()->request->getParam('papage');
		$ids = Yii::app()->request->getPost('ids');
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('productGroup/detailindex' , 'companyId' => $this->companyId,'lid'=>$prodgroupId,'papage'=>$papage)) ;
		}
        //Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_product_group_detail set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('productGroup/detailindex','companyId' => $this->companyId,'lid'=>$prodgroupId,'papage'=>$papage));
		} else {
			Yii::app()->user->setFlash('error' ,yii::t('app', '请选择要删除的项目'));
			$this->redirect(array('productGroup/detailindex' , 'companyId' => $this->companyId,'lid'=>$prodgroupId,'papage'=>$papage)) ;
		}
	}	
        
        public function actionGetChildren(){
		$categoryId = Yii::app()->request->getParam('pid',0);
		$prodgroupId = Yii::app()->request->getParam('prod_group_id',0);
           //var_dump($prodgroupId);exit;
		if(!$categoryId){
			Yii::app()->end(json_encode(array('data'=>array(),'delay'=>400)));
		}
        $treeDataSource = array('data'=>array(),'delay'=>400);
		$produts = $this->getProducts($categoryId);
		//var_dump($produts);exit;
		foreach($produts as $c){
			$tmp['name'] = $c['product_name'];
			$tmp['id'] = $c['lid'];
			$treeDataSource['data'][] = $tmp;
		}
		Yii::app()->end(json_encode($treeDataSource));
	}
        
   	public function actionIsDoubleGroupDetail(){
		$productId = Yii::app()->request->getParam('productid',0);
        $productgroupId = Yii::app()->request->getParam('productgroupId',0);
        $companyId = Yii::app()->request->getParam('companyId',0);
        $treeDataSource = array('data'=>FALSE,'delay'=>400);
        $product= ProductGroupDetail::model()->find('t.dpid = :dpid and t.product_id = :productid and t.prod_group_id = :prodgroupid and t.delete_flag=0',array(':dpid'=>$companyId ,':productid'=>$productId,':prodgroupid'=>$productgroupId));
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
        
        private function getSetProducts($categoryId,$prodgroupId){
                $db = Yii::app()->db;
                
                if($categoryId==0)
                {
                    $sql = "SELECT lid,name from nb_product_group where dpid=:companyId and delete_flag=0 and lid not in (select prod_group_id from nb_product_group_detail where lid=:lid and dpid=:dpid)";
                    $command=$db->createCommand($sql);
                    $command->bindValue(":companyId" , $this->companyId);
                    $command->bindValue(":dpid" , $this->companyId);
                    $command->bindValue(":prodgroupId" , $prodgroupId);
                }else{
                    $sql = "SELECT lid,name from nb_product_group where dpid=:companyId and category_id=:categoryId and delete_flag=0 and lid not in (select prod_group_id from nb_product_group_detail where lid=:lid and dpid=:dpid)";
                    $command=$db->createCommand($sql);
                    $command->bindValue(":companyId" , $this->companyId);
                    $command->bindValue(":dpid" , $this->companyId);
                    $command->bindValue(":prodId" , $productSetId);
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
                $sql = "SELECT category_id from nb_product_group_detail sd,nb_product p where sd.dpid=p.dpid and sd.product_id=p.lid and sd.lid=:lid";
                $command=$db->createCommand($sql);
                $command->bindValue(":lid" , $lid);
                return $command->queryScalar();
	}
        
        private function getMaxGroupNo($psid){
                $db = Yii::app()->db;
                $sql = "SELECT max(group_no) from nb_product_set_detail where delete_flag = 0 and dpid=:dpid and set_id=:psid";
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
	private function getGroupnos($setid){
		if($setid)
		{
			$sql = 'select t1.*,t.product_name from nb_product t left join nb_product_set_detail t1 on( t.dpid = t1.dpid and t1.delete_flag =0 and t.lid = t1.product_id and t1.set_id ='.$setid.' ) where t1.is_select = 1 and t1.lid is not null and t.dpid ='.$this->companyId.' and t.delete_flag = 0 group by t1.group_no' ;
			//$groupnos = ProductSetDetail::model()->findAll('left join nb_product t on(t.dpid = dpid and t.delete_flag = 0)dpid=:companyId and delete_flag=0 and set_id =:setId group by group_no' , array(':companyId' => $this->companyId,':setId'=>$setid));
			$command1 = Yii::app()->db->createCommand($sql);
			$groupnos = $command1->queryAll();
			//var_dump($sql);exit;
		}
		$groupnos = $groupnos ? $groupnos : array();
		return $groupnos;
	}
}