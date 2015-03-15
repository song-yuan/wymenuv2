<?php
class ProductCleanController extends BackendController
{
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
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId ;
		if($categoryId){
			$criteria->condition.=' and t.lid = '.$categoryId;
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
	public function actionStatus(){
		$id = Yii::app()->request->getParam('id');
		$product = Product::model()->find('lid=:id and company_id=:companyId' , array(':id'=>$id,':companyId'=>$this->companyId));
		var_dump($product->status);
		if($product){
			$product->saveAttributes(array('status'=>$product->status?0:1));
		}
		exit;
	}
	
	
}