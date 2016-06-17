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
		$type = Yii::app()->request->getParam('type');
			$this->render('bom',array(
				'companyId' => $this->companyId,
				'type'=>$type,
		));
	}

}
