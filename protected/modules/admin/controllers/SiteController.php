<?php
class SiteController extends BackendController
{
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex() {
               
		$typeId = Yii::app()->request->getParam('typeId',0);
                $siteTypes = $this->getTypes();
                
		if(!empty($siteTypes)) {
		        $typeKeys = array_keys($siteTypes);
                        $typeId = array_search($typeId, $typeKeys) ? $typeId : $typeKeys[0] ;                      
                }
		$criteria = new CDbCriteria;
                $criteria->with = array('siteType', 'floor','sitePersons');
                $criteria->condition =  't.delete_flag = 0 and t.type_id = '.$typeId.' and t.dpid='.$this->companyId ;
                $criteria->order = ' t.type_id asc ';		
                $models = Site::model()->findAll($criteria);
                $pages = new CPagination(Site::model()->count($criteria));
                $pages->applyLimit($criteria);
                
		//var_dump($models);exit;
		$this->render('index',array(
				'siteTypes' => $siteTypes,
				'models'=>$models,
				'typeId' => $typeId,
                                'pages' => $pages
		));
	}
	public function actionCreate() {
		$typeId = Yii::app()->request->getParam('typeId',0);
		$model = new Site() ;
		$model->dpid = $this->companyId ;
		$model->type_id = $typeId;
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Site');
                        $se=new Sequence("site");
                        $model->lid = $se->nextval();
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->update_at = date('Y-m-d H:i:s',time());
                        $model->delete_flag = '0';
                        //var_dump($model);exit;
			if($model->save()) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('site/index' , 'typeId'=>$typeId,'companyId' => $this->companyId));
			}
		}
		$types = $this->getTypes();
                $floors = $this->getFloors();
                $sitepersons = $this->getSitePersons();
                //var_dump($floors);
                //var_dump($types);exit;
		$this->render('create' , array(
				'model' => $model , 
				'types' => $types ,
                                'floors'=> $floors,
                                'sitepersons'=>$sitepersons
		));
	}
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
                Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		$model = Site::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=>  $this->companyId));
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Site');
                        $model->update_at = date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('site/index' , 'typeId'=>$model->type_id, 'companyId' => $this->companyId));
			}
		}
		$types = $this->getTypes();
                $floors = $this->getFloors();
                $sitepersons = $this->getSitePersons();
                //var_dump($sitepersons,$floors);exit;
		$this->render('update' , array(
			'model'=>$model,
			'types' => $types,
                        'floors'=> $floors,
                        'sitepersons'=>$sitepersons
		));
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
                Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			foreach ($ids as $id) {
				$model = Site::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $companyId)) ;
				if($model) {
					$model->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
				}
			}
			$this->redirect(array('site/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' ,yii::t('app', '请选择要删除的项目'));
			$this->redirect(array('site/index' , 'companyId' => $companyId)) ;
		}
	}
	private function getTypes(){
		$types = SiteType::model()->findAll('dpid=:companyId and delete_flag=0' , array(':companyId' => $this->companyId)) ;
		$types = $types ? $types : array();
		return CHtml::listData($types, 'lid', 'name');
	}
        private function getFloors(){
		$floors = Floor::model()->findAll('dpid=:companyId and delete_flag=0' , array(':companyId' => $this->companyId)) ;
		$floors = $floors ? $floors : array();
		return CHtml::listData($floors, 'lid', 'name');
	}
        
        private function getSitePersons(){
		$sitepersons = SitePersons::model()->findAll('dpid=:companyId and delete_flag=0' , array(':companyId' => $this->companyId)) ;
		$sitepersons = $sitepersons ? $sitepersons : array();
                $splist=array();
                if(!empty($sitepersons))
                {
                    foreach($sitepersons as $sp)
                    {
                        array_push($splist,array("lid"=>$sp->lid,"persons"=>$sp->min_persons."-".$sp->max_persons));
                    }
                }
               // var_dump($sp)
		return CHtml::listData($splist, 'lid', 'persons');
	}
}