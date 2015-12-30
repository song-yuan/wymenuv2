<?php
class SiteChannelController extends BackendController
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
		
		$db = Yii::app()->db;
		$sql = 'select k.* from(select t1.company_name,t.* from nb_channel t left join nb_company t1 on(t.dpid = t1.dpid) where t.delete_flag = 0 and t.dpid = '.$this->companyId.') k';
		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
		
// 		$criteria = new CDbCriteria;
// 		$criteria->select ='t.*';
// 		$criteria->with = 'company';
//                // $criteria->select='t.lid as lid,t.dpid as dpid,t.name as name,c.company_name as company_name';
//                 //$criteria->join = 'LEFT JOIN nb_company c ON c.dpid=t.dpid';
// 		$criteria->addCondition('t.delete_flag = 0 and t.dpid = '.$this->companyId);
//     	//$criteria->Condition('delete_flag=0');
// 		$pages = new CPagination(Channel::model()->count($criteria));
// 		//	    $pages->setPageSize(1);
// 		$pages->applyLimit($criteria);
		//$models = Channel::model()->findAll($criteria);
		//var_dump($models);
                //exit;
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
		));
	}
	public function actionCreate() {
		$model = new Channel() ;
		$model->dpid = $this->companyId ;
		$is_sync = DataSync::getInitSync();
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Channel');
                        $se=new Sequence("channel");
                        $model->lid = $se->nextval();
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->update_at = date('Y-m-d H:i:s',time());
                        $model->delete_flag = '0';
                        $model->is_sync = $is_sync;
                        //var_dump($model);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','添加成功'));
				$this->redirect(array('siteChannel/index' , 'companyId' => $this->companyId));
			}
		}
		$this->render('create' , array(
			'model' => $model,
		));
	}
	public function actionUpdate() {
		$lid = Yii::app()->request->getParam('lid');
        $dpid = Yii::app()->request->getParam('companyId');
        $is_sync = DataSync::getInitSync();
		//echo 'ddd';
		$model = Channel::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
		//var_dump($model);exit;
		Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Channel');
			$model->update_at=date('Y-m-d H:i:s',time());
			$model->is_sync = $is_sync;
			//var_dump($model->attributes);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
				$this->redirect(array('siteChannel/index' , 'companyId' => $this->companyId));
			}
		}
		$this->render('update' , array(
				'model'=>$model,
		));
	}
	public function actionDelete() {
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
		$is_sync = DataSync::getInitSync();
        //        Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_channel set delete_flag="1", is_sync ='.$is_sync.' where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('siteChannel/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('siteChannel/index' , 'companyId' => $companyId)) ;
		}
	}
}