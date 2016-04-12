<?php
class BomController extends BackendController {
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId && $this->getAction()->getId() != 'upload') {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionBom() {
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
			$this->render('bom',array(
				'companyId' => $companyId
		));
	}
	public function actionStock() {
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
			$this->render('stock',array(
				'companyId' => $companyId
		));
	}
	public function actionSet() {
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
			$this->render('set',array(
				'companyId' => $companyId
		));
	}
}
