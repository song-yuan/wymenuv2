<?php
/**
* 区域价格分组
*/
class AreaGroupController extends BackendController
{
	/*
	 *区域价格分组列表
	 */
	public function actionIndex(){
		$type = Yii::app()->request->getParam('type',1);
		$criteria = new CDbCriteria;
		$criteria->addCondition('dpid=:dpid and type=:type and delete_flag=0');
		$criteria->order = ' lid desc ';
		$criteria->params = array(':dpid'=>$this->companyId,':type'=>$type);


		$pages = new CPagination(AreaGroup::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = AreaGroup::model()->findAll($criteria);
		$this->render('index',array(
			'models'=>$models,
			'type'=>$type,
			'pages'=>$pages,
		));
	}
	/*
	* 区域价格分组名添加
	*/
	public function actionCreate(){
		$model = new AreaGroup ;
		$dpid = Yii::app()->request->getParam('companyId');
		$type = Yii::app()->request->getParam('type',1);
		if(Yii::app()->request->isPostRequest) {
			$formdata = Yii::app()->request->getPost('AreaGroup');
			$se=new Sequence("area_group");
            $lid = $se->nextval();
            $model->lid = $lid;
            $model->dpid = $dpid;
            $model->create_at = date('Y-m-d H:i:s');
            $model->update_at = date('Y-m-d H:i:s');
            $model->group_name = $formdata['group_name'];
            $model->group_desc = $formdata['group_desc'];
            $model->type = $type;
			// p($model);
			if ($model->save()) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('areaGroup/index' , 'companyId' => $this->companyId,'type'=>$type));
			}
		}
		$this->render('create',array(
			'model' => $model,
			'type'=>$type
		));
	}
	/*
	* 区域分组名编辑更新
	*/
	public function actionUpdate(){
		$dpid = Yii::app()->request->getParam('companyId');
		$lid = Yii::app()->request->getParam('lid');
		$type = Yii::app()->request->getParam('type',1);
		$models = AreaGroup::model();
		$model = $models->find('lid=:lid and dpid=:dpid',array(':lid'=>$lid,':dpid'=>$dpid));
		if(Yii::app()->request->isPostRequest) {
			$formdata = Yii::app()->request->getPost('AreaGroup');
		// p($formdata);
            $model->update_at = date('Y-m-d H:i:s');
            $model->group_name = $formdata['group_name'];
            $model->group_desc = $formdata['group_desc'];
			// p($model);
			if ($model->save()) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '编辑成功'));
				$this->redirect(array('areaGroup/index' , 'companyId' => $this->companyId,'type'=>$type));
			}
		}
		$this->render('create',array(
			'model' => $model,
		));
	}
	/*
	* 区域分组名删除,并且删除分组的详情
	*
	*/
	public function actionDelete(){
		$formdata = Yii::app()->request->getParam('lid');
		$dpid = Yii::app()->request->getParam('companyId');
		$models = AreaGroup::model();
		$db = Yii::app()->db;
		// p($formdata);
		if(!empty($formdata)) {
			$transaction = $db->beginTransaction();
			try{
				foreach ($formdata as $lid) {
					$model = AreaGroup::model()->find('lid=:lid and dpid=:companyId' , array(':lid' => $lid , ':companyId' => $dpid)) ;
					if($model) {
						$command = $model->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
						if ($command) {
							$modelc = AreaGroupCompany::model()->findAll('area_group_id=:lid and dpid=:companyId' , array(':lid' => $lid , ':companyId' => $dpid)) ;
							foreach ($modelc as $modelcc) {
								$commandc = $modelcc->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
							}
							$modeld = AreaGroupDepot::model()->findAll('area_group_id=:lid and dpid=:companyId' , array(':lid' => $lid , ':companyId' => $dpid)) ;
							foreach ($modeld as $modeldd) {
								$commandd = $modeldd->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
							}
						}
					}
				}
				$transaction->commit();
				Yii::app()->user->setFlash('success' ,yii::t('app', '删除成功'));
				$this->redirect(array('areaGroup/index' , 'companyId' => $dpid));
			}catch(Exception $e){
	                $transaction->rollBack();
	                Yii::app()->user->setFlash('error' ,yii::t('app', '删除失败'));
	                $this->redirect(array('areaGroup/index','companyId' => $dpid));
	        }
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('areaGroup/index' , 'companyId' => $dpid)) ;
		}
	}


	/**
	*分组中店铺或者仓库列表
	*/
	public function actionDetailIndex(){
		$dpid = Yii::app()->request->getParam('companyId');
		$page = Yii::app()->request->getParam('page');
		$type = Yii::app()->request->getParam('type');
		$areagroupid = Yii::app()->request->getParam('areagroupid');
		$groupname = AreaGroup::model()->find('lid=:lid and dpid=:companyId' , array(':lid' => $areagroupid , ':companyId' => $dpid))->group_name ;
		// p($groupname);
		$db = Yii::app()->db;

		if ($type==1) {
			$sql='select c.*,a.* from nb_area_group_company a left join nb_company c on(c.dpid=a.company_id and c.delete_flag=0 and c.type=1) where a.dpid='.$dpid.' and a.area_group_id='.$areagroupid.' and a.delete_flag=0';
		// p($sql);
		}elseif($type==2){
			$sql='select c.*,a.* from nb_area_group_depot a left join nb_company c on(c.dpid=a.depot_id and c.delete_flag=0 and c.type=2) where a.dpid='.$dpid.' and a.area_group_id='.$areagroupid.' and a.delete_flag=0';
		}
		$models = Yii::app()->db->createCommand($sql)->queryALL();
		$count = count($models);
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();


		$this->render('detailindex',array(
			'models'=> $models,
			'type'=> $type,
			'groupname'=> $groupname,
			'areagroupid'=> $areagroupid,
			'pages'=>$pages,
		));
	}
	/**
	*店铺或者仓库分组添加店铺或者仓库
	*/
	public function actionAdd(){
		$dpid = Yii::app()->request->getParam('companyId');
		$type = Yii::app()->request->getParam('type');
		$areagroupid = Yii::app()->request->getParam('areagroupid');
		$models = Company::model()->findAll('comp_dpid=:dpid and delete_flag=0 and type=:type',array(':dpid'=>$dpid,':type'=>$type));
		if (Yii::app()->request->isPostRequest) {
			// p($_POST);
			if ($type==1) {
				foreach ($_POST['dpid'] as $key => $comp_id) {
					$info = AreaGroupCompany::model()->find('area_group_id=:areagroupid and company_id=:comp_id and dpid=:dpid ',array(':areagroupid'=>$areagroupid,':comp_id'=>$comp_id,':dpid'=>$dpid));
					if ($info) {
						$info->update_at=date('Y-m-d H:i:s',time());
						$info->delete_flag=0;
						$info->save();
					}else{
						$se=new Sequence("area_group_company");
						$lid = $se->nextval();
						$is_sync = DataSync::getInitSync();
						$data=array(
							'lid'=>$lid,
							'dpid'=>$dpid,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'area_group_id'=>$areagroupid,
							'company_id'=>$comp_id,
							'delete_flag'=>0,
							'is_sync'=>$is_sync
						);
						Yii::app()->db->createCommand()->insert('nb_area_group_company',$data);
					}
				}
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('areaGroup/detailIndex' , 'companyId' => $dpid,'areagroupid'=>$areagroupid,'type'=>$type));
			}elseif($type==2){
				foreach ($_POST['dpid'] as $key => $comp_id) {
					$info = AreaGroupDepot::model()->find('area_group_id=:areagroupid and depot_id=:depot_id and dpid=:dpid ',array(':areagroupid'=>$areagroupid,':depot_id'=>$comp_id,':dpid'=>$dpid));
					if ($info) {
						$info->update_at=date('Y-m-d H:i:s',time());
						$info->delete_flag=0;
						$info->save();
					}else{
						$se=new Sequence("area_group_depot");
						$lid = $se->nextval();
						$is_sync = DataSync::getInitSync();
						$data=array(
							'lid'=>$lid,
							'dpid'=>$dpid,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'area_group_id'=>$areagroupid,
							'depot_id'=>$comp_id,
							'delete_flag'=>0,
							'is_sync'=>$is_sync
						);
						Yii::app()->db->createCommand()->insert('nb_area_group_depot',$data);
					}
				}
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('areaGroup/detailIndex' , 'companyId' => $dpid,'areagroupid'=>$areagroupid,'type'=>$type));
			}
		}
		$this->render('add',array(
			'models'=> $models,
			'type'=> $type,
			'areagroupid'=> $areagroupid,
		));
	}

	/*
	* 分组详情产品删除
	*/
	public function actionDelete_detail(){
		$lid = Yii::app()->request->getParam('lid');
		$dpid = Yii::app()->request->getParam('companyId');
		$type = Yii::app()->request->getParam('type');
		$areagroupid = Yii::app()->request->getParam('areagroupid');
		if ($type==1){
			$model = AreaGroupCompany::model();
		}elseif($type==2){
			$model = AreaGroupDepot::model();
		}
		if(!empty($lid)) {
				$info= $model->find('lid=:lid and dpid=:companyId' , array(':lid' => $lid , ':companyId' => $dpid)) ;
				if($info) {
					$info->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
				}
			Yii::app()->user->setFlash('success' ,yii::t('app', '删除成功'));
			$this->redirect(array('areaGroup/detailIndex' , 'companyId' => $dpid,'areagroupid'=>$areagroupid,'type'=>$type)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('areaGroup/detailIndex' , 'companyId' => $dpid,'areagroupid'=>$areagroupid,'type'=>$type)) ;
		}
	}

	public function actionDefault(){
		$lid = Yii::app()->request->getParam('lid');
		$dpid = Yii::app()->request->getParam('companyId');
		$type = Yii::app()->request->getParam('type');
		$areagroupid = Yii::app()->request->getParam('areagroupid');
		$model = AreaGroupDepot::model();
		$db =Yii::app()->db;
		$transaction = $db->beginTransaction();
        try{

			$infod= $model->find('is_selected=1 and dpid=:companyId and area_group_id=:areagroupid' , array(':companyId' => $dpid,':areagroupid'=>$areagroupid)) ;
			// p($infod);
			if($infod) {
				$infod->saveAttributes(array('is_selected'=>0,'delete_flag'=>0,'update_at'=>date('Y-m-d H:i:s',time())));
			}
			$info= $model->find('lid=:lid and dpid=:companyId and area_group_id=:areagroupid' , array(':lid' => $lid , ':companyId' => $dpid,':areagroupid'=>$areagroupid)) ;
			if($info) {
				$info->saveAttributes(array('is_selected'=>1,'delete_flag'=>0,'update_at'=>date('Y-m-d H:i:s',time())));
			}
            $transaction->commit();
            Yii::app()->user->setFlash('success' , yii::t('app','设置成功！！！'));
            $this->redirect(array('areaGroup/detailIndex' , 'companyId' => $dpid,'areagroupid'=>$areagroupid,'type'=>$type)) ;
        }catch (Exception $e){
            $transaction->rollback();
            Yii::app()->user->setFlash('error' , yii::t('app','设置失败！！！'));
            $this->redirect(array('areaGroup/detailIndex' , 'companyId' => $dpid,'areagroupid'=>$areagroupid,'type'=>$type)) ;
        }

	}


}
?>