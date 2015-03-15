<?php
class ProductPrinterController extends BackendController
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
		$criteria->with = 'printerWay';
		$criteria->addCondition('t.dpid=:dpid and t.delete_flag=0 ');
		$criteria->order = ' t.lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		
		$pages = new CPagination(Product::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = Product::model()->findAll($criteria);
//		var_dump($models[0]);exit;
		$this->render('productPrinter',array(
				'models'=>$models,
				'pages' => $pages,
		));
	}
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$model = Product::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=>  $this->companyId));
		
		if(Yii::app()->request->isPostRequest) {
			$postData = Yii::app()->request->getPost('printerWay');
			$model->printer_way_id = $postData;
			if($model->save()){
				Yii::app()->user->setFlash('success' , '修改成功');
				$this->redirect(array('productPrinter/index' , 'companyId' => $this->companyId));
			}
		}
		$printerWays = PrinterWay::getPrinterWay($this->companyId);
		
		$this->render('updateProductPrinter' , array(
			'model'=>$model,
			'printerWays'=>$printerWays,
		));
	}
}