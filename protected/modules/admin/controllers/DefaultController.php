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
                
		$siteTypes = $this->getTypes();
		if(empty($siteTypes)) {
			$models = false;
		}
		$typeKeys = array_keys($siteTypes);
		$typeId = array_search($typeId, $typeKeys) ? $typeId : $typeKeys[0] ;
		
		$criteria = new CDbCriteria;
		$criteria->with = 'siteType';
                //echo $typeId; exit;
                if(empty($typeId)) {
			$criteria->condition =  't.delete_flag = 0 and t.dpid='.$this->companyId ;
		}else{
                        $criteria->condition =  't.delete_flag = 0 and t.type_id = '.$typeId.' and t.dpid='.$this->companyId ;
                }
		$criteria->order = ' t.type_id asc ';
		
		
		$models = Site::model()->findAll($criteria);
		$this->render('index',array(
				'siteTypes' => $siteTypes,
				'models'=>$models,
				'typeId' => $typeId
		));
	}
        
        private function getTypes(){
		$types = SiteType::model()->findAll('dpid=:companyId and delete_flag=0' , array(':companyId' => $this->companyId)) ;
		$types = $types ? $types : array();
		return CHtml::listData($types, 'lid', 'name');
	}
}