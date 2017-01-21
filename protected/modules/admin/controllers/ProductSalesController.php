<?php
class ProductSalesController extends BackendController
{
	
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionindex(){
		$categoryId = Yii::app()->request->getParam('cid',0);
		$criteria = new CDbCriteria;
		$criteria->with = array('company','category');
		$criteria->addCondition('t.dpid=:dpid and t.is_show=1 and t.delete_flag=0');
		if($categoryId){
			$criteria->addCondition('category_id=:cid');
			$criteria->params[':cid']=$categoryId;
		}
		$criteria->order = ' t.lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		
		$pages = new CPagination(Product::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = Product::model()->findAll($criteria);
		$categories = $this->getCategories();
//		var_dump($models[0]);exit;
		$this->render('index',array(
				'models'=>$models,
				'pages' => $pages,
				'categories'=>$categories,
				'categoryId'=>$categoryId
		));
	}
	
	public function actionUpdatedetail(){
		$productId = Yii::app()->request->getParam('id');
		$criteria = new CDbCriteria;
		$criteria->with = array('product','productSet');
		$criteria->addCondition('t.dpid=:dpid and t.delete_flag=0 and t.product_id=:productId and t.is_set=0');
		$criteria->order = ' t.lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		$criteria->params[':productId']=$productId;
		
		$pages = new CPagination(ProductDiscount::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = ProductDiscount::model()->findAll($criteria);
//		var_dump($models[0]);exit;
		$this->render('updatedetail',array(
				'models'=>$models,
				'pages' => $pages,
				'productId'=>$productId,
		));
	}
	public function actionCreate() {
		$productId = Yii::app()->request->getParam('productId');
		$model = new ProductDiscount ;
		$model->dpid = $this->companyId ;
		
		$product = Product::model()->find('lid=:lid and dpid=:dpid and delete_flag=0',array(':lid'=>$productId,':dpid'=>  $this->companyId));
//		$productSets = ProductSet::model()->findAll('dpid=:dpid and delete_flag=0 and is_discount=1 and status=0',array(':dpid'=>$this->companyId));
		if(Yii::app()->request->isPostRequest) {
			$postData = Yii::app()->request->getPost('ProductDiscount');
			 $model->attributes = $postData;
                        $se=new Sequence("retreat");
                        $model->lid = $se->nextval();
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->update_at = date('Y-m-d H:i:s',time());
			if($model->save()) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('productSales/updatedetail' , 'companyId' => $this->companyId,'id'=>$productId));
			}
		}
//		var_dump($products);exit;
		$this->render('create' , array(
				'model' => $model , 
				'product'=>$product,
		));
	}
	
//新加delate方法
	public function actionDetailDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
                $printset = Yii::app()->request->getParam('psid');
		$ids = Yii::app()->request->getPost('ids');
        //Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_product_discount set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('productSales/updatedetail' , 'companyId' => $companyId,'id'=>$printset)) ;
		} else {
			Yii::app()->user->setFlash('error' ,yii::t('app', '请选择要删除的项目'));
			$this->redirect(array('productSales/updatedetail' , 'companyId' => $companyId,'id'=>$printset)) ;
		}
	}	
        
	//delete方法新加
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('id');
        //Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		$model = ProductDiscount::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=>  $this->companyId));
		$product = Product::model()->find('lid=:lid and dpid=:dpid and delete_flag=0',array(':lid'=>$model->product_id,':dpid'=>  $this->companyId));
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductDiscount');
                        $model->update_at = date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('productSales/updatedetail' , 'companyId' => $this->companyId,'id'=>$model->product_id));
			}
		}
		$this->render('update' , array(
			'model'=>$model,
			'product'=>$product,
		));
	}
	public function actionRecommend(){
		$id = Yii::app()->request->getParam('id');
		$product = Product::model()->find('lid=:id and dpid=:companyId' , array(':id'=>$id,':companyId'=>$this->companyId));
		//Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if($product){
                        $product->saveAttributes(array('is_discount'=>$product->is_discount==0?1:0,'update_at'=>date('Y-m-d H:i:s',time())));
                        //$product->update_at = date('Y-m-d H:i:s',time());			
		}
		exit;
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
}