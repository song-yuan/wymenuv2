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
		$stype = Yii::app()->request->getParam('stype','0');
		$status = Yii::app()->request->getParam('status','0');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d 00:00:00',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d 23:59:59',time()));
		//var_dump($begin_time);exit;
		//$categoryId = Yii::app()->request->getParam('cid',0);
		$criteria = new CDbCriteria;
		
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId;	
		$criteria->addCondition("t.create_at >='$begin_time '");
		$criteria->addCondition("t.create_at <='$end_time '");
		
		$criteria->addCondition("t.status ='$status'");
		if($stype){
			$criteria->addCondition("t.type =".$stype);
		}
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
				'status'=>$status,
				'stype'=>$stype
				//'categoryId'=>$categoryId
		));
	}
	public function actionDetailindex(){
		$status = Yii::app()->request->getParam('status','0');
		$stockTakingId = Yii::app()->request->getParam('id',0);
		$begin_time = Yii::app()->request->getParam('begin_time');
		$end_time = Yii::app()->request->getParam('end_time');
		$criteria = new CDbCriteria;
		$criteria->condition =  't.dpid='.$this->companyId.' and t.logid ='.$stockTakingId.' and t.delete_flag=0';
		//	$criteria->condition.=' and t.lid = '.$categoryId;
		$pages = new CPagination(StockTakingDetail::model()->count($criteria));
		//$pages->setPageSize(1);
		//$pages->applyLimit($criteria);
		$models = StockTakingDetail::model()->findAll($criteria);
		$this->render('detailindex',array(
				'models'=>$models,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'status'=>$status,
				//'pages'=>$pages,
	
		));
	}

	public function actionSavereason(){
		$detaillid = Yii::app()->request->getParam('detaillid');
		$reason = Yii::app()->request->getParam('reason');
		
		$stocktakinglog = StockTakingDetail::model()->find('lid=:id and dpid=:dpid and delete_flag=0',array(':id'=>$detaillid,':dpid'=>$this->companyId,));
		if($stocktakinglog){
			$stocktakinglog->update_at = date('Y-m-d H:i:s',time());
			$stocktakinglog->reasion = $stocktakinglog->reasion.'(新加)'.$reason;
			if($stocktakinglog->update()){
				Yii::app()->end(json_encode(array("status"=>true)));
			}
			
		}
	}
}