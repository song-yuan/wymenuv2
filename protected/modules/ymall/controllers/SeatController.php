<?php

class SeatController extends BaseYmallController
{
	public $companyId;
	public $waiterId;
	public function init(){
	  
	  $this->companyId = isset(Yii::app()->user->companyId)?Yii::app()->user->companyId:0;
	  if(!$this->companyId){
	  	$this->redirect(array('/waiter/user/index'));
	  }
	  $this->waiterId = Yii::app()->user->userId;	
	}
	public function actionIndex()
	{
		$db = Yii::app()->db;
		$id = Yii::app()->request->getParam('id',0);
		$typeSql = 'select * from nb_site_type where company_id='.$this->companyId;
		$siteType = $db->createCommand($typeSql)->queryAll();
		if(!$id){
			$id = $siteType?$siteType[0]['type_id']:0;
		}
		$sql = 'select t1.*,t2.code,t2.order_id,t2.number from nb_site as t1 left join (select nb_site_no.*,nb_order.order_id from nb_site_no left join nb_order on nb_site_no.id=nb_order.site_no_id where nb_site_no.company_id='.$this->companyId.' and delete_flag=0)t2 on t1.site_id = t2.site_id where t1.company_id='.$this->companyId.' and t1.delete_flag=0 and t1.type_id='.$id;
		$models = $db->createCommand($sql)->queryAll();
		$this->render('index',array('models'=>$models,'cid'=>$this->companyId,'siteType'=>$siteType,'id'=>$id));
	}
	/**
	 * 
	 * 生成座次号
	 */
	public function actionCreateCode(){
		$id = Yii::app()->request->getParam('id');
		$number = Yii::app()->request->getParam('number');
		$model = SiteNo::model()->find('site_id=:siteId and company_id=:companyId and delete_flag=0',array(':siteId'=>$id,':companyId'=>$this->companyId));
		$model = $model?$model:new SiteNo;
		$code = rand(100000,999999);
		$model->company_id = $this->companyId;
		$model->site_id  = $id;
		$model->code = $code;
		$model->number = $number;
		$model->waiter_id = $this->waiterId;
		if($model->save()){
		   echo $code;
		}else{
			echo 0;
		}
		Yii::app()->end();
	}
}