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
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		
		$criteria = new CDbCriteria;
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId;	
		$criteria->addCondition('t.create_at >="'.$begin_time.' 00:00:00"');
		$criteria->addCondition('t.create_at <="'.$end_time.' 23:59:59"');
		
		if($stype){
			$criteria->addCondition("t.type =".$stype);
		}
		$criteria->order = ' t.lid desc ';	
		$pages = new CPagination(StockTaking::model()->count($criteria));
		$pages->applyLimit($criteria);
		$models = StockTaking::model()->findAll($criteria);
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'stype'=>$stype
				//'categoryId'=>$categoryId
		));
	}
	public function actionDetailindex(){
		$status = Yii::app()->request->getParam('status','0');
		$stockTakingId = Yii::app()->request->getParam('id',0);
		
		$sql = 'select lid,logid,material_id,material_stock_id,sum(reality_stock) as reality_stock,sum(taking_stock) as taking_stock,sum(number) as number,reasion from nb_stock_taking_detail where dpid='.$this->companyId.' and logid ='.$stockTakingId.' and delete_flag=0 group by material_id';
		$models = Yii::app()->db->createCommand($sql)->queryAll();
// 		$criteria = new CDbCriteria;
// 		$criteria->condition =  't.dpid='.$this->companyId.' and t.logid ='.$stockTakingId.' and t.delete_flag=0';
// 		$models = StockTakingDetail::model()->findAll($criteria);
		$this->render('detailindex',array(
				'models'=>$models,
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