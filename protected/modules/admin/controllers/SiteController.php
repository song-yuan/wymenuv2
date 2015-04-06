<?php
class SiteController extends BackendController
{
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , '请选择公司');
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex() {
               
		$typeId = Yii::app()->request->getParam('typeId',0);
                //echo $typeId; exit;
		$siteTypes = $this->getTypes();
                
		if(!empty($siteTypes)) {
			
                        $typeKeys = array_keys($siteTypes);
                        $typeId = array_search($typeId, $typeKeys) ? $typeId : $typeKeys[0] ;

                        
                }
		$criteria = new CDbCriteria;
                $criteria->with = array('siteType', 'floor');
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
                        $model->delete_flag = '0';
                        //var_dump($model);exit;
			if($model->save()) {
				Yii::app()->user->setFlash('success' , '添加成功');
				$this->redirect(array('site/index' , 'typeId'=>$typeId,'companyId' => $this->companyId));
			}
		}
		$types = $this->getTypes();
                $floors = $this->getFloors();
                //var_dump($floors);
                //var_dump($types);exit;
		$this->render('create' , array(
				'model' => $model , 
				'types' => $types ,
                                'floors'=> $floors
		));
	}
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$model = Site::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=>  $this->companyId));
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Site');
                        //var_dump(Yii::app()->request->getPost('Site'));exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' , '修改成功');
				$this->redirect(array('site/index' , 'typeId'=>$model->type_id, 'companyId' => $this->companyId));
			}
		}
		$types = $this->getTypes();
                $floors = $this->getFloors();
		$this->render('update' , array(
			'model'=>$model,
			'types' => $types,
                        'floors'=> $floors
		));
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
		if(!empty($ids)) {
			foreach ($ids as $id) {
				$model = Site::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $companyId)) ;
				if($model) {
					$model->saveAttributes(array('delete_flag'=>1));
				}
			}
			$this->redirect(array('site/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , '请选择要删除的项目');
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
}