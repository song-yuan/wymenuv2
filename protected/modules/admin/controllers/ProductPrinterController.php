<?php
class ProductPrinterController extends BackendController
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
		$criteria = new CDbCriteria;
		//$criteria->with = 'printerWay';
		$criteria->addCondition('t.dpid=:dpid and t.delete_flag=0 ');                
		$criteria->order = ' t.lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		
		$pages = new CPagination(Product::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
                $criteria->with='productPrinterway';
		$models = Product::model()->findAll($criteria);
		//var_dump($models,$pages);exit;
		$this->render('productPrinter',array(
				'models'=>$models,
				'pages' => $pages,
		));
	}
	public function actionUpdate(){
        $printerway=array();
		$lid = Yii::app()->request->getParam('lid');
		$papage = Yii::app()->request->getParam('papage');
		$model = Product::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=>  $this->companyId));
		//Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			if(Yii::app()->user->role > User::SHOPKEEPER) {
				Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
				$this->redirect(array('productPrinter/index' , 'companyId' => $this->companyId, 'page'=>$papage)) ;
			}
			$postData = Yii::app()->request->getPost('ProductPrinterway');
			//$model->printer_way_id = $postData;
			if(ProductPrinterway::saveProductPrinterway($this->companyId, $lid, $postData)){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('productPrinter/index' , 'companyId' => $this->companyId, 'page'=>$papage));
			}
		}
		$printerWays = PrinterWay::getPrinterWay($this->companyId);
                
        $productPrinterway=  ProductPrinterway::getProductPrinterWay($lid,$this->companyId);
		foreach($productPrinterway as $ppw){
			array_push($printerway,$ppw['printer_way_id']);
		}
		$this->render('updateProductPrinter' , array(
			'model'=>$model,
			'printerWays'=>$printerWays,
            'printerway'=>$printerway,
			'papage'=>$papage,
		));
	}
}