<?php

class DefaultController extends BackendController
{
        public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , '请选择公司');
			$this->redirect(array('company/index'));
		}
		return true;
	}
    
	public function actionIndex()
	{
		$typeId = Yii::app()->request->getParam('typeId');
                /*$stypeId = Yii::app()->request->getParam('stypeId','0');
                $sistemp = Yii::app()->request->getParam('sistemp','0');
                $ssid = Yii::app()->request->getParam('ssid','0');
                $op = Yii::app()->request->getParam('op','0');
                $title='请选择餐桌';
                $geturl='/op/'.$op.'/sistemp/'.$sistemp.'/ssid/'.$ssid.'/stypeId/'.$stypeId;*/
                //$siteNmae='';
                $siteTypes = SiteClass::getTypes($this->companyId);
                if(empty($siteTypes)) {
			$typeId='tempsite';
		}
                //$modelsitet = SiteType::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $typeId,':dpid'=>  $this->companyId));
                /*if($op=='switch')
                {
                    if($sistemp=='0')
                    {
                        $title='被换餐桌：'.$siteTypes[$stypeId];
                        $modelsite = Site::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $ssid,':dpid'=>  $this->companyId));
                        $title=$title.'-->'.$modelsite->serial.'('.$modelsite->site_level.')'.'::请选择目标餐桌';
                    }else{
                        $title='被换餐桌：临时台/排队-->'.($ssid%1000).'：：请选择目标餐桌';
                    }
                } */               
		
                if($typeId != 'tempsite')
                {
                    $typeKeys = array_keys($siteTypes);
                    $typeId = array_search($typeId, $typeKeys) ? $typeId : $typeKeys[0] ;
                }
                /*
		$criteria = new CDbCriteria;
		$models=array();
                if($typeId == 'tempsite'){
                        $criteria->condition =  't.delete_flag = 0 and t.status in ("1","2","3") and t.is_temp = 1 and t.dpid='.$this->companyId ;
                        $criteria->order = ' t.create_at desc ';
                        $models = SiteNo::model()->findAll($criteria);
                }else{
                        $criteria->with = 'siteType';
                        $criteria->condition =  't.delete_flag = 0 and t.type_id = '.$typeId.' and t.dpid='.$this->companyId ;
                        $criteria->order = ' t.create_at desc ';
                        $models = Site::model()->findAll($criteria);
                }*/
                //var_dump($models);exit;
		$this->render('index',array(
				'siteTypes' => $siteTypes,
				//'models'=>$models,
				'typeId' => $typeId,
                                //'title' => $title,
                                //'geturl' => $geturl,
                                //'ssid' => $ssid,
                                //'sistemp' => $sistemp
		));
	}
        
        public function actionMessage()
	{
		$companyId = Yii::app()->request->getParam('companyId');
                $criteria = new CDbCriteria;
		//$criteria->with = array('siteNo','siteNo.site') ;
		$criteria->condition =  't.dpid='.$this->companyId ;
		$criteria->order = 'msg_type DESC,is_deal ASC';
                $msg=  GuestMessage::model()->findAll($criteria);
                //var_dump($msg);exit;
                $this->renderPartial('message',array(
				'model' => $msg
				//'typeId' => $typeId,                                
		));
        }
}