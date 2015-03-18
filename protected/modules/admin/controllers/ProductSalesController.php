<?php
class ProductSalesController extends BackendController
{
	
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , '请选择公司');
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionindex(){
		$criteria = new CDbCriteria;
		$criteria->with=array('product','productSet');
		$criteria->addCondition('t.dpid=:dpid ');
		$criteria->order = ' t.lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		
		$pages = new CPagination(ProductDiscount::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = ProductDiscount::model()->findAll($criteria);
//		var_dump($models[0]);exit;
		$this->render('index',array(
				'models'=>$models,
				'pages' => $pages,
		));
	}
	public function actionCreate() {
		$model = new ProductDiscount ;
		$model->dpid = $this->companyId ;
		
		$products = Product::model()->findAll('dpid=:dpid and delete_flag=0 and is_discount=1 and is_show=1 and status=0',array(':dpid'=>$this->companyId));
		$productSets = ProductSet::model()->findAll('dpid=:dpid and delete_flag=0 and is_discount=1 and status=0',array(':dpid'=>$this->companyId));
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Retreat');
                        $se=new Sequence("retreat");
                        $model->lid = $se->nextval();
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->delete_flag = '0';
			if($model->save()) {
				Yii::app()->user->setFlash('success' , '添加成功');
				$this->redirect(array('retreat/index' , 'companyId' => $this->companyId));
			}
		}
//		var_dump($products);exit;
		$this->render('create' , array(
				'model' => $model , 
				'products'=>$products,
				'productSets'=>$productSets,
		));
	}
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$model = Retreat::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=>  $this->companyId));
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Retreat');
			if($model->save()){
				Yii::app()->user->setFlash('success' , '修改成功');
				$this->redirect(array('retreat/index', 'companyId' => $this->companyId));
			}
		}
		$this->render('update' , array(
			'model'=>$model,
		));
	}
}