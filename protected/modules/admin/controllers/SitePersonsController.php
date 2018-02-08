<?php
class SitePersonsController extends BackendController
{
	public function beforeAction($action){
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index')) ;
		}
		return true;
	}
	public function actionIndex() {
		$criteria = new CDbCriteria;
		$criteria->with = 'company';
               // $criteria->select='t.lid as lid,t.dpid as dpid,t.name as name,c.company_name as company_name';
                //$criteria->join = 'LEFT JOIN nb_company c ON c.dpid=t.dpid';
		$criteria->condition = 't.dpid='.$this->companyId.' and t.delete_flag=0';
		$pages = new CPagination(SitePersons::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = SitePersons::model()->findAll($criteria);
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
		));
	}
	public function actionCreate() {
		$model = new SitePersons() ;
		$model->dpid = $this->companyId ;
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('SitePersons');
                        $se=new Sequence("site_type");
                        $model->lid = $se->nextval();
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->update_at = date('Y-m-d H:i:s',time());
                        //var_dump($model);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','添加成功'));
				$this->redirect(array('sitePersons/index' , 'companyId' => $this->companyId));
			}
		}
		$this->render('create' , array(
			'model' => $model,
		));
	}
	public function actionUpdate() {
		$lid = Yii::app()->request->getParam('lid');
                $dpid = Yii::app()->request->getParam('companyId');
        //Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		$model = SitePersons::model()->find('t.lid=:lid and t.dpid=:dpid', array(':lid' => $lid,':dpid'=>$dpid));
		//var_dump($model);
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('SitePersons');
                        $model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('sitePersons/index' , 'companyId' => $this->companyId));
			}
		}
		$this->render('update' , array(
			'model' => $model
		));
	}
	public function actionDelete() {
		//$ids = $_POST['type_id'] ;
                $ids = Yii::app()->request->getPost('type_id');
                //Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		//var_dump(implode(',' , $ids),$this->companyId);exit;
                //$sql='update nb_site_type set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId';
                //$command=Yii::app()->db->createCommand($sql);
                //$command->bindValue(":ids" , implode(',' , $ids));
                //$command->bindValue(":dpid" , $this->companyId);
                // var_dump($ids);exit;
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_site_persons set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
		}
		$this->redirect(array('sitePersons/index' , 'companyId' => $this->companyId));
	}
}