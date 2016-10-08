<?php
class MuchupdateProdController extends BackendController
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
		
		//$pages = new CPagination(Product::model()->count($criteria));
		//	    $pages->setPageSize(1);
		//$pages->applyLimit($criteria);
		$models = Product::model()->findAll($criteria);
		
		$db = Yii::app()->db;
		$sql = 'select t.dpid,t.company_name from nb_company t where t.delete_flag = 0 and t.comp_dpid = '.$this->companyId;
		$command = $db->createCommand($sql);
		$dpids = $command->queryAll();
		//var_dump($dpids);exit;
		$categories = $this->getCategories();
//                var_dump($categories);exit;
		$this->render('index',array(
				'models'=>$models,
				'dpids'=>$dpids,
				'categories'=>$categories,
				'categoryId'=>$categoryId
		));
	}

	public function actionStorProduct(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$is_sync = DataSync::getInitSync();
		//var_dump($companyId);exit;
		$opids = Yii::app()->request->getParam('pids');
		$oprodids = Yii::app()->request->getParam('prodids');
		$onums = Yii::app()->request->getParam('nums');
		//$dpid = Yii::app()->request->getParam('dpids');
		//var_dump($pids);var_dump($prodids);var_dump($nums);exit;
		$pids = array();
		$pids = explode(',',$opids);
		$prodids = array();
		$prodids = explode(',',$oprodids);
		$nums = array();
		$nums = explode(',',$onums);
		//var_dump($pids,$prodids,$nums);exit;
		
		//****查询公司的产品分类。。。****
		$db = Yii::app()->db;
		
		//var_dump($catep1,$catep2,$products);exit;
                Until::isUpdateValid($pids,$companyId,$this);//0,表示企业任何时候都在云端更新。
        if((!empty($pids))&&(!empty($prodids))&&(Yii::app()->user->role < User::WAITER)){
        	foreach ($nums as $num){
        		$prodid = array();
        		$prodnums = explode('@',$num);
        		$prodnum1 = $prodnums[0];
        		$prodnum2 = $prodnums[1];
        		if($prodnum1 == "price"){
        			$updatename = 'original_price';
        		}elseif($prodnum1 == "memberprice"){
        			$updatename = 'member_price';
        		}
        		elseif($prodnum1 == "sort"){
        			$updatename = 'sort';
        		}
        		elseif($prodnum1 == "dabaofee"){
        			$updatename = 'dabao_fee';
        		}
        		elseif($prodnum1 == "ismemberdiscount"){
        			$updatename = 'is_member_discount';
        		}
        		elseif($prodnum1 == "isdiscount"){
        			$updatename = 'is_discount';
        		}

        		elseif($prodnum1 == "isshow"){
        			$updatename = 'is_show';
        		}
        		//var_dump($updatename);exit;
	        	foreach ($pids as $pid){
	        		$product =  Product::model()->find('lid=:lid and dpid=:companyId and delete_flag=0' , array(':lid'=>$pid,':companyId'=>$this->companyId));
	        		//var_dump($pid);//exit;	
	        		if($product){
	        			$product->$updatename = $prodnum2;
	        			$product->save();

	        		}
	        	}
        	}
        	Yii::app()->user->setFlash('success' , yii::t('app','菜品批量修改成功！！！'));
        	$this->redirect(array('muchupdateProd/index' , 'companyId' => $companyId)) ;
        	
        }else{
        	Yii::app()->user->setFlash('error' , yii::t('app','无权限进行此项操作！！！'));
        	$this->redirect(array('muchupdateProd/index' , 'companyId' => $companyId)) ;
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