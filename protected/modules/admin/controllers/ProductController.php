<?php
class ProductController extends BackendController
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
			Yii::app()->user->setFlash('error' , '请选择公司');
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex(){
		$categoryId = Yii::app()->request->getParam('cid',0);
		$criteria = new CDbCriteria;
		$criteria->with = array('company','category');
		$criteria->condition =  't.delete_flag=0 and t.company_id='.$this->companyId ;
		if($categoryId){
			$criteria->condition.=' and t.category_id = '.$categoryId;
		}
		
		$pages = new CPagination(Product::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = Product::model()->findAll($criteria);
		
		$categories = $this->getCategories();
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
				'categories'=>$categories,
				'categoryId'=>$categoryId
		));
	}
	
	public function actionCreate(){
		$model = new Product();
		$model->company_id = $this->companyId ;
		$model->create_time = time();
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Product');
			if($model->save()){
				Yii::app()->user->setFlash('success','添加成功！');
				$this->redirect(array('product/index' , 'companyId' => $this->companyId ));
			}
		}
		$categories = $this->getCategoryList();
		$departments = $this->getDepartments();
		$this->render('create' , array(
			'model' => $model ,
			'categories' => $categories,
			'departments' => $departments
		));
	}
	
	public function actionUpdate(){
		$id = Yii::app()->request->getParam('id');
		$model = Product::model()->find('product_id=:productId' , array(':productId' => $id));
		$model->company_id = $this->companyId ;
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Product');
			//var_dump($model->attributes);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success','修改成功！');
				$this->redirect(array('product/index' , 'companyId' => $this->companyId ));
			}
		}
		$categories = $this->getCategoryList();
		$departments = $this->getDepartments();
		$this->render('create' , array(
				'model' => $model ,
				'categories' => $categories,
				'departments' => $departments
		));
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
		if(!empty($ids)) {
			foreach ($ids as $id) {
				$model = Product::model()->find('product_id=:id and company_id=:companyId' , array(':id' => $id , ':companyId' => $companyId)) ;
				if($model) {
					$model->saveAttributes(array('delete_flag'=>1));
				}
			}
			$this->redirect(array('product/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , '请选择要删除的项目');
			$this->redirect(array('product/index' , 'companyId' => $companyId)) ;
		}
	}
	public function actionStatus(){
		$id = Yii::app()->request->getParam('id');
		$product = Product::model()->find('product_id=:id and company_id=:companyId' , array(':id'=>$id,':companyId'=>$this->companyId));
		var_dump($product->status);
		if($product){
			$product->saveAttributes(array('status'=>$product->status?0:1));
		}
		exit;
	}
	public function actionRecommend(){
		$id = Yii::app()->request->getParam('id');
		$product = Product::model()->find('product_id=:id and company_id=:companyId' , array(':id'=>$id,':companyId'=>$this->companyId));
		
		if($product){
			$product->saveAttributes(array('recommend'=>$product->recommend==0?1:0));
		}
		exit;
	}
	private function getCategoryList(){
		$categories = ProductCategory::model()->findAll('delete_flag=0 and company_id=:companyId' , array(':companyId' => $this->companyId)) ;
		//var_dump($categories);exit;
		return CHtml::listData($categories, 'category_id', 'category_name');
	}
	public function actionGetChildren(){
		$pid = Yii::app()->request->getParam('pid',0);
		if(!$pid){
			Yii::app()->end(json_encode(array('data'=>array(),'delay'=>400)));
		}
		$treeDataSource = array('data'=>array(),'delay'=>400);
		$categories = Helper::getCategories($this->companyId,$pid);
	
		foreach($categories as $c){
			$tmp['name'] = $c['category_name'];
			$tmp['id'] = $c['category_id'];
			$treeDataSource['data'][] = $tmp;
		}
		Yii::app()->end(json_encode($treeDataSource));
	}
	private function getCategories(){
		$criteria = new CDbCriteria;
		$criteria->with = 'company';
		$criteria->condition =  't.delete_flag=0 and t.company_id='.$this->companyId ;
		$criteria->order = ' tree,category_id asc ';
		
		$models = ProductCategory::model()->findAll($criteria);
		//return CHtml::listData($models, 'category_id', 'category_name','pid');
		$options = array();
		$optionsReturn = array('--请选择分类--');
		if($models) {
			foreach ($models as $model) {
				if($model->pid == 0) {
					$options[$model->category_id] = array();
				} else {
					$options[$model->pid][$model->category_id] = $model->category_name;
				}
			}
		}
		foreach ($options as $k=>$v) {
			$model = ProductCategory::model()->findByPk($k);
			$optionsReturn[$model->category_name] = $v;
		}
		return $optionsReturn;
	}
	private function getDepartments(){
		$departments = Department::model()->findAll('company_id=:companyId',array(':companyId'=>$this->companyId)) ;
		return CHtml::listData($departments, 'department_id', 'name');
	}
}