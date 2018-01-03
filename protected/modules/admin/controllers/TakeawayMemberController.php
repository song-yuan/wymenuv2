<?php
class TakeawayMemberController extends BackendController {
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
		$types = Yii::app()->request->getParam('types',0);
		$criteria = new CDbCriteria;
		//$criteria->with = 'company' ;
		//$criteria->condition = Yii::app()->user->role == User::POWER_ADMIN ? '' : 't.dpid='.Yii::app()->user->companyId ;
		if($types){
			$criteria->condition = 't.type=1 and t.dpid='.$this->companyId ;
		}else{
			$criteria->condition = 't.type=0 and t.dpid='.$this->companyId ;
		}
		$pages = new CPagination(TakeawayMember::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = TakeawayMember::model()->findAll($criteria);
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
				'companyId' => $companyId,
				'types'=>$types,
		));
		
	}
	public function actionCreate(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$types = Yii::app()->request->getParam('types',0);
		$model = new TakeawayMember() ;
		$model->dpid = $companyId ;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('TakeawayMember');
			$se=new Sequence("takeaway_member");
                        $model->lid = $se->nextval();
			$model->create_at = date('Y-m-d H:i:s');
				$model->type = $types;
                        $model->update_at=date('Y-m-d H:i:s',time());
//			var_dump($model->attributes);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','添加成功'));
				$this->redirect(array('takeawayMember/index' , 'companyId' => $companyId,'types'=>$types));
			}
		}
		$this->render('create' , array('model' => $model,'types'=>$types));
	}
	public function actionUpdate(){
		$types = Yii::app()->request->getParam('types');
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('takeawayMember/index' , 'companyId' => $this->companyId,'types'=>$types)) ;
		}
		$id = Yii::app()->request->getParam('id');
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$model = TakeawayMember::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $companyId));
		//Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
                if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('TakeawayMember');
                        $model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
				$this->redirect(array('takeawayMember/index' , 'companyId' => $companyId,'types'=>$types));
			}
		}
		$this->render('update' , array('model' => $model,'types'=>$types ));
	}
	public function actionDelete(){
		$types = Yii::app()->request->getParam('types');
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('takeawayMember/index' , 'companyId' => $this->companyId,'types'=>$types)) ;
		}
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
        //Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
				foreach ($ids as $id) {
					$model = TakeawayMember::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $companyId)) ;
					if($model) {
						$model->delete();
					}
				}
				$this->redirect(array('takeawayMember/index' , 'companyId' => $companyId,'types'=>$types)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('takeawayMember/index' , 'companyId' => $companyId,'types'=>$types)) ;
		}
	}
}
