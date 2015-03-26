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
                //echo $typeId; exit;
		$siteTypes = $this->getTypes();
		if(empty($siteTypes)) {
			$models = false;
		}
		$typeKeys = array_keys($siteTypes);
                if($typeId!='tempsite')
                    $typeId = array_search($typeId, $typeKeys) ? $typeId : $typeKeys[0] ;
		
		$criteria = new CDbCriteria;
		$models=array();
                //echo $typeId; exit;
                if(empty($typeId)) {
                        $criteria->with = 'siteType';
			$criteria->condition =  't.delete_flag = 0 and t.dpid='.$this->companyId ;
                        $criteria->order = ' t.type_id asc ';
                        $models = Site::model()->findAll($criteria);
		}else if($typeId == 'tempsite'){
                        $criteria->condition =  't.delete_flag = 0 and t.is_temp = 1 and t.dpid='.$this->companyId ;
                        $criteria->order = ' t.site_id asc ';
                        //echo '22';exit;
                        $models = SiteNo::model()->findAll($criteria);
                }else{
                        $criteria->with = 'siteType';
                        $criteria->condition =  't.delete_flag = 0 and t.type_id = '.$typeId.' and t.dpid='.$this->companyId ;
                        $criteria->order = ' t.type_id asc ';
                        $models = Site::model()->findAll($criteria);
                }
                //var_dump($models);exit;
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