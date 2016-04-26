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
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex(){
		$categoryId = Yii::app()->request->getParam('cid',0);
		$criteria = new CDbCriteria;
		$criteria->with = array('company','category');
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId;
		if($categoryId){
			$criteria->condition.=' and t.category_id = '.$categoryId;
		}
		
		$pages = new CPagination(Product::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = Product::model()->findAll($criteria);
		
		$categories = $this->getCategories();
//                var_dump($categories);exit;
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
				'categories'=>$categories,
				'categoryId'=>$categoryId
		));
	}
	public function actionSetMealList() {
		
	}
	public function actionCreate(){
		$model = new Product();
		$model->dpid = $this->companyId ;
		//$model->create_time = time();
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Product');
			
			$cateID = $model->category_id;
			//if(!empty($cateID)){
				$db = Yii::app()->db;
				$sql = 'select t.* from nb_product_category t where t.delete_flag = 0 and t.lid = '.$cateID;
				$command = $db->createCommand($sql);
				$categoryId = $command->queryRow();
				//var_dump($categoryId['chs_code']);exit;
				
				$se=new Sequence("product");
				$lid = $se->nextval();
				$model->lid = $lid;
				$code=new Sequence("phs_code");
				$phs_code = $code->nextval();
				
				$model->create_at = date('Y-m-d H:i:s',time());
				$model->update_at = date('Y-m-d H:i:s',time());
				$model->chs_code = $categoryId['chs_code'];
				$model->phs_code = ProductCategory::getChscode($this->companyId, $lid, $phs_code);
				$model->delete_flag = '0';
				$py=new Pinyin();
				$model->simple_code = $py->py($model->product_name);
				//var_dump($model);exit;
				if($model->save()){
					Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
					$this->redirect(array('product/index' , 'companyId' => $this->companyId ));
				}
// 			}else{
// 				Yii::app()->user->setFlash('error',yii::t('app','请选择二级分类。。。'));
// 				$this->redirect(array('product/create' , 'companyId' => $this->companyId ));
// 			}
			
		}
		$categories = $this->getCategoryList();
		//$departments = $this->getDepartments();
                //echo 'ss';exit;
		$this->render('create' , array(
			'model' => $model ,
			'categories' => $categories
		));
	}
	
	public function actionUpdate(){
		$id = Yii::app()->request->getParam('id');
		$model = Product::model()->find('lid=:productId and dpid=:dpid' , array(':productId' => $id,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Product');
                        $py=new Pinyin();
                        $model->simple_code = $py->py($model->product_name);
			$model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				$this->redirect(array('product/index' , 'companyId' => $this->companyId ));
			}
		}
		$categories = $this->getCategoryList();
		//$departments = $this->getDepartments();
		$this->render('update' , array(
				'model' => $model ,
				'categories' => $categories
		));
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
                Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_product set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('product/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('product/index' , 'companyId' => $companyId)) ;
		}
	}
	public function actionStatus(){
		$id = Yii::app()->request->getParam('id');
		$product = Product::model()->find('lid=:id and dpid=:companyId' , array(':id'=>$id,':companyId'=>$this->companyId));
		//var_dump($product->status);
		if($product){
			$product->saveAttributes(array('status'=>$product->status?0:1,'update_at'=>date('Y-m-d H:i:s',time())));
		}
		exit;
	}
	public function actionRecommend(){
		$id = Yii::app()->request->getParam('id');
		$product = Product::model()->find('lid=:id and dpid=:companyId' , array(':id'=>$id,':companyId'=>$this->companyId));
		
		if($product){
			$product->saveAttributes(array('recommend'=>$product->recommend==0?1:0,'update_at'=>date('Y-m-d H:i:s',time())));
		}
		exit;
	}
	private function getCategoryList(){
		$categories = ProductCategory::model()->findAll('delete_flag=0 and dpid=:companyId' , array(':companyId' => $this->companyId)) ;
		//var_dump($categories);exit;
		return CHtml::listData($categories, 'lid', 'category_name');
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
	private function getDepartments(){
		$departments = Department::model()->findAll('company_id=:companyId',array(':companyId'=>$this->companyId)) ;
		return CHtml::listData($departments, 'department_id', 'name');
	}
}