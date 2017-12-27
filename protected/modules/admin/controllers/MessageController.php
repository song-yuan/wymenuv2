<?php
class MessageController extends BackendController
{

	public function actionIndex() {

		$db=Yii::app()->db;
		$sql='select s.lid,s.downdate,s.dpid,s.all_message_no,s.send_message_no,s.money from nb_message_set s  where  s.delete_flag=0 and dpid='.$this->companyId;

		$models = $db->createCommand($sql)->queryALL();
		// p($models);
		$sql2 = 'select * from nb_message where delete_flag=0 and dpid='.$this->companyId.' and unix_timestamp(downdate_at) >'.time();
		$infos = $db->createCommand($sql2)->queryALL();
		$this->render('index',array(
				'models'=>$models,
				'infos'=>$infos,
		));
	}



	public function actionSetindex() {

		$db=Yii::app()->db;
		$sql='select c.company_name,s.lid,s.downdate,s.dpid,s.all_message_no,s.send_message_no,s.money from nb_message_set s left join nb_company c on(c.dpid=s.dpid and c.delete_flag=0 and c.type=0) where  s.delete_flag=0 order by c.company_name asc';//s.dpid='.$dpid.' and


		$models = $db->createCommand($sql)->queryALL();
		$count = count($models);

		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());
		$models = $pdata->queryAll();
		// p($models);
		$this->render('setindex',array(
				'models'=>$models,
				'pages' => $pages,
		));
	}




	public function actionSetcreate() {
		$model = new MessageSet ;
		$db=Yii::app()->db;
		$sql='select c.company_name,c.dpid from nb_company c where c.delete_flag=0 and c.type=0 ';
		$dpids = $db->createCommand($sql)->queryALL();
		$companyId = Yii::app()->request->getParam('companyId');
		if(Yii::app()->request->isPostRequest) {
			$dpid = Yii::app()->request->getParam('dpid');
			if ($dpid=='') {
				Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
				$this->redirect(array('message/setcreate' , 'companyId' => $companyId, )) ;
			}
			$model->attributes = Yii::app()->request->getPost('MessageSet');
            $se=new Sequence("message_set");
            $model->lid = $se->nextval();
            $model->dpid = $dpid;
            $model->create_at = date('Y-m-d H:i:s',time());
            $model->update_at=date('Y-m-d H:i:s',time());
            $model->delete_flag = '0';
// p($model);
			if($model->save()) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('message/setindex' , 'companyId' => $this->companyId));
			}
		}
		$this->render('setcreate' , array(
				'model' => $model ,
				'dpids' => $dpids ,
		));
	}


	public function actionSetupdate(){
		$model = MessageSet::model();
		$lid = Yii::app()->request->getParam('id');
		$model = $model->find('lid=:lid and delete_flag=0',array(':lid'=>$lid));
		$db=Yii::app()->db;
		$sql='select c.company_name,c.dpid from nb_company c where c.delete_flag=0 and c.type=0 ';
		$dpids = $db->createCommand($sql)->queryALL();

		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('MessageSet');
			$dpid = Yii::app()->request->getParam('dpid');
            $se=new Sequence("message_set");
            $model->lid = $se->nextval();
            $model->dpid = $dpid;
            $model->create_at = date('Y-m-d H:i:s',time());
            $model->update_at=date('Y-m-d H:i:s',time());
            $model->delete_flag = '0';
			if($model->save()) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('message/setindex' , 'companyId' => $this->companyId));
			}
		}
		$this->render('setupdate' , array(
			'model'=>$model,
			'dpids' => $dpids ,
		));
	}


	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$lids = Yii::app()->request->getParam('ids');
		// p($lids);
		$papage = Yii::app()->request->getParam('papage');
		if(!empty($lids)) {
			foreach ($lids as $key => $lid) {
				$model = MessageSet::model()->find('lid=:lid and delete_flag=0' , array(':lid' => $lid )) ;
				if($model) {
					$model->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
				}
			}
			$this->redirect(array('message/setindex' , 'companyId' => $companyId, 'page'=>$papage)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('message/setindex' , 'companyId' => $companyId, 'page'=>$papage)) ;
		}
	}


}