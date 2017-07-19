<?php
class ElemeController extends BackendController
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
	public function actionIndex(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$this->render('index',array('companyId'=>$companyId));
	}
	public function actionCpfl(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$sql = "select lid,category_name from nb_product_category where dpid=$companyId and pid=0 and delete_flag=0";
		$model = Yii::app()->db->createCommand($sql)->queryAll();
		$this->render('cpfl',array(
			'companyId'=>$companyId,
			'model'=>$model
			));
	}
	public function actionCpdy(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$sql = "dpid=$companyId and delete_flag=0 order by category_id ASC";
		$model = Product::model()->findAll($sql);
		$this->render('cpdy',array(
			'companyId'=>$companyId,
			'model'=>$model
			));
	}
	public function actionCpgx(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$sql = "dpid=$companyId and delete_flag=0 order by category_id ASC";
		$model = Product::model()->findAll($sql);
		$this->render('cpgx',array(
			'companyId'=>$companyId,
			'model'=>$model
			));
	}
	public function actionSccp(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$sql = "dpid=$companyId and delete_flag=0 order by category_id ASC";
		$model = Product::model()->findAll($sql);
		$this->render('sccp',array(
			'companyId'=>$companyId,
			'model'=>$model
			));
	}
	public function actionFlgx(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$sql = "select lid,category_name from nb_product_category where dpid=$companyId and pid=0 and delete_flag=0";
		$model = Yii::app()->db->createCommand($sql)->queryAll();
		$this->render('flgx',array(
			'companyId'=>$companyId,
			'model'=>$model
			));
	}
	public function actionFlsc(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$sql = "select lid,category_name from nb_product_category where dpid=$companyId and pid=0 and delete_flag=0";
		$model = Yii::app()->db->createCommand($sql)->queryAll();
		$this->render('flsc',array(
			'companyId'=>$companyId,
			'model'=>$model
			));
	}
	public function actionDpsq(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$url = Yii::app()->createAbsoluteUrl('/eleme/elemetoken');
		$url = urlencode($url);
		$clintId = ElmConfig::key;
		$sqUrl = ElmConfig::squrl;
		$this->render('dpsq',array(
				'companyId'=>$companyId,
				'url'=>$url,
				'clintId'=>$clintId,
				'sqUrl'=>$sqUrl,
			));
	}
}
?>