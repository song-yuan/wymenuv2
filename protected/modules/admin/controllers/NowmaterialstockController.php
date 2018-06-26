<?php
class NowmaterialstockController extends BackendController
{
	
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
		$criteria->with = array('category');
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId;
		if($categoryId){
			$criteria->condition.=' and t.category_id = '.$categoryId;
		}
	//	$criteria->condition.=' and t.lid = '.$categoryId;
		$pages = new CPagination(ProductMaterial::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = ProductMaterial::model()->findAll($criteria);
		$categories = $this->getCategories();
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
				'categories'=>$categories,
				'categoryId'=>$categoryId

		));
	}


	public function actionDetailindex(){
		$materialId = Yii::app()->request->getParam('id',0);
		
		$criteria = new CDbCriteria;
		$criteria->condition =  't.dpid='.$this->companyId.' and t.material_id ='.$materialId.' and t.delete_flag=0';
		$pages = new CPagination(GoodsMaterialStock::model()->count($criteria));
		$pages->applyLimit($criteria);
		$models = GoodsMaterialStock::model()->findAll($criteria);
		$this->render('ckdetailindex',array(
				'models'=>$models,
				'pages'=>$pages,
	
		));
	}
	// 仓库实时库存
	public function actionCkindex(){
		$categoryId = Yii::app()->request->getParam('cid',0);
		$compid = WxCompany::getCompanyDpid($this->companyId);
		
		$criteria = new CDbCriteria;
		$criteria->with = array('category');
		$criteria->condition = 'category.dpid='.$compid.' and category.delete_flag=0';
		$criteria->condition = 't.delete_flag=0 and t.dpid='.$this->companyId;
		if($categoryId){
			$criteria->condition.=' and t.category_id = '.$categoryId;
		}
		$pages = new CPagination(Goods::model()->count($criteria));
		$pages->applyLimit($criteria);
		$models = Goods::model()->findAll($criteria);
		$categories = $this->getGoodsCategories();
		$this->render('ckindex',array(
				'models'=>$models,
				'pages'=>$pages,
				'categories'=>$categories,
				'categoryId'=>$categoryId
	
		));
	}
	
	public function actionCkdetailindex(){
		$materialId = Yii::app()->request->getParam('id',0);
		$criteria = new CDbCriteria;
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId.' and t.goods_id ='.$materialId;
		$pages = new CPagination(GoodsMaterialStock::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = GoodsMaterialStock::model()->findAll($criteria);
		$this->render('ckdetailindex',array(
				'models'=>$models,
				'pages'=>$pages,
	
		));
	}
	//库存预警
	public function actionCaution(){
		$criteria = new CDbCriteria;
		$criteria->with = array('category');
		$criteria->condition = 't.delete_flag=0 and t.dpid='.$this->companyId;
		$models = ProductMaterial::model()->findAll($criteria);
		//var_dump($models);exit;
		$this->renderPartial('caution',array(
				'models'=>$models
		));
	}
	private function getCategoryList(){
		$categories = MaterialCategory::model()->findAll('delete_flag=0 and dpid=:companyId' , array(':companyId' => $this->companyId)) ;
		//var_dump($categories);exit;
		return CHtml::listData($categories, 'lid', 'category_name');
	}
	public function actionGetChildren(){
		$pid = Yii::app()->request->getParam('pid',0);
		if(!$pid){
			Yii::app()->end(json_encode(array('data'=>array(),'delay'=>400)));
		}
		$treeDataSource = array('data'=>array(),'delay'=>400);
		$categories = Helper::getCategory($this->companyId,$pid);

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
		$criteria->order = ' t.lid asc ';

		$models = MaterialCategory::model()->findAll($criteria);

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
			$model = MaterialCategory::model()->find('t.lid = :lid and dpid=:dpid',array(':lid'=>$k,':dpid'=>  $this->companyId));
			$optionsReturn[$model->category_name] = $v;
		}
		return $optionsReturn;
	}
	private function getGoodsCategories()
	{
		$compid = WxCompany::getCompanyDpid($this->companyId);
	
		$criteria = new CDbCriteria;
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$compid ;
		$criteria->order = ' tree,t.lid asc ';
	
		$models = MaterialCategory::model()->findAll($criteria);
	
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
		}
		foreach ($options as $k=>$v) {
			$model = MaterialCategory::model()->find('t.lid = :lid and dpid=:dpid',array(':lid'=>$k,':dpid'=> $compid));
			$optionsReturn[$model->category_name] = $v;
		}
		return $optionsReturn;
	}
	
}
