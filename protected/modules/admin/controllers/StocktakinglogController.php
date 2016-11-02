<?php
class StocktakinglogController extends BackendController
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
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d 00:00:00',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d 23:59:59',time()));
		//var_dump($begin_time);exit;
		//$categoryId = Yii::app()->request->getParam('cid',0);
		$criteria = new CDbCriteria;
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId;	
		$criteria->addCondition("t.create_at >='$begin_time '");
		$criteria->addCondition("t.create_at <='$end_time '");
		$criteria->order = ' t.lid desc ';	
		$pages = new CPagination(StockTaking::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = StockTaking::model()->findAll($criteria);
		//var_dump($models);exit;
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				//'categoryId'=>$categoryId
		
		));
	}
	public function actionDetailindex(){
		$stockTakingId = Yii::app()->request->getParam('id',0);
		$criteria = new CDbCriteria;
		$criteria->condition =  't.status = 0 and t.delete_flag=0 and t.dpid='.$this->companyId.' and t.logid ='.$stockTakingId;
		//	$criteria->condition.=' and t.lid = '.$categoryId;
		$pages = new CPagination(StockTakingDetail::model()->count($criteria));
		//$pages->setPageSize(1);
		//$pages->applyLimit($criteria);
		$models = StockTakingDetail::model()->findAll($criteria);
		$this->render('detailindex',array(
				'models'=>$models,
				//'pages'=>$pages,
	
		));
	}

}