<?php
class ReportController extends BackendController {
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId && $this->getAction()->getId() != 'upload') {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
    public function actionIndex() {
        $companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
        $this->render('index',array(
            'companyId' => $companyId
        ));
    }
    /* 采购综合查询 */
    public function actionPurchase() {
        $companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
        $criteria = new CDbCriteria;
        $criteria->condition =  't.dpid='.$this->companyId .' and delete_flag=0';
        $pages = new CPagination(PurchaseOrder::model()->count($criteria));
        $pages->applyLimit($criteria);
        $this->render('purchase',array(
            'companyId' => $companyId,
            'pages' => $pages,
        ));
    }
    /* 厂商综合查询 */
    public function actionManufacturer() {
        $companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
        $criteria = new CDbCriteria;
        $criteria->condition =  't.dpid='.$this->companyId .' and delete_flag=0';
        $pages = new CPagination(PurchaseOrder::model()->count($criteria));
        $pages->applyLimit($criteria);
        $this->render('manufacturer',array(
            'companyId' => $companyId,
            'pages' => $pages,
        ));
    }
    /* 厂商零售查询 */
    public function actionRetail() {
        $companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
        $criteria = new CDbCriteria;
        $criteria->condition =  't.dpid='.$this->companyId .' and delete_flag=0';
        $pages = new CPagination(PurchaseOrder::model()->count($criteria));
        $pages->applyLimit($criteria);
        $this->render('retail',array(
            'companyId' => $companyId,
            'pages' => $pages,
        ));
    }
    /* 实时库存查询 */
    public function actionReal() {
        $companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
        $criteria = new CDbCriteria;
        $criteria->condition =  't.dpid='.$this->companyId .' and delete_flag=0';
        $pages = new CPagination(PurchaseOrder::model()->count($criteria));
        $pages->applyLimit($criteria);
        $this->render('real',array(
            'companyId' => $companyId,
            'pages' => $pages,
        ));
    }
    /* 库存综合查询 */
    public function actionMultiple() {
        $companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
        $criteria = new CDbCriteria;
        $criteria->condition =  't.dpid='.$this->companyId .' and delete_flag=0';
        $pages = new CPagination(PurchaseOrder::model()->count($criteria));
        $pages->applyLimit($criteria);
        $this->render('multiple',array(
            'companyId' => $companyId,
            'pages' => $pages,
        ));
    }
}
