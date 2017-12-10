<?php
/**
* 区域价格分组
*/
class PeisonggroupController extends BackendController
{
	/*
	 *区域价格分组列表
	 */
	public function actionIndex(){

		$criteria = new CDbCriteria;
		$criteria->addCondition('dpid=:dpid and delete_flag=0');
		$criteria->order = ' lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		$pages = new CPagination(PeisongGroup::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = PeisongGroup::model()->findAll($criteria);
		$comp = Company::model()->find('dpid=:dpid and delete_flag=0',array(':dpid'=>$this->companyId));
		if ($comp->type!=0) {
			$models=0;
		}
		$this->render('index',array(
			'models'=>$models,
			'pages'=>$pages,
		));
	}
	/*
	* 区域价格分组名添加
	*/

	public function actionCreate(){
		$model = new PeisongGroup ;
		$dpid = Yii::app()->request->getParam('companyId');
		$db = Yii::app()->db;
		if(Yii::app()->request->isPostRequest) {
			$formdata = Yii::app()->request->getPost('PeisongGroup');

				$se=new Sequence("peisong_group");
	            $lid = $se->nextval();
	            $model->lid = $lid;
	            $model->dpid = $dpid;
	            $model->create_at = date('Y-m-d H:i:s');
	            $model->update_at = date('Y-m-d H:i:s');
	            $model->group_name = $formdata['group_name'];
	            $model->group_desc = $formdata['group_desc'];
				// p($model);
				if ($model->save()) {
					Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
					$this->redirect(array('peisonggroup/index' , 'companyId' => $dpid));
				}else{
	                Yii::app()->user->setFlash('error' ,yii::t('app', '添加失败,请重试'));
	                $this->redirect(array('peisonggroup/index','companyId' => $dpid));
	        	}
		}
		$this->render('create',array(
			'model' => $model,
		));
	}
	/*
	* 区域价格分组名编辑更新
	*/
	public function actionUpdate(){
		$dpid = Yii::app()->request->getParam('companyId');
		$lid = Yii::app()->request->getParam('lid');
		$models = PeisongGroup::model();
		$model = $models->find('lid=:lid and dpid=:dpid',array(':lid'=>$lid,':dpid'=>$dpid));
		if(Yii::app()->request->isPostRequest) {
			$formdata = Yii::app()->request->getPost('PeisongGroup');
            $model->update_at = date('Y-m-d H:i:s');
            $model->group_name = $formdata['group_name'];
            $model->group_desc = $formdata['group_desc'];
			// p($model);
			if ($model->save()) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '编辑成功'));
				$this->redirect(array('peisonggroup/index' , 'companyId' => $this->companyId));
			}else{
                Yii::app()->user->setFlash('error' ,yii::t('app', '编辑失败,请重试'));
                $this->redirect(array('peisonggroup/index','companyId' => $dpid));
        	}
		}
		$this->render('create',array(
			'model' => $model,
		));
	}




	/*
	* 区域价格分组名删除,并且删除分组的价格详情
	*/
	public function actionDelete(){
		$formdata = Yii::app()->request->getParam('lid');
		$dpid = Yii::app()->request->getParam('companyId');
		$models = PeisongGroup::model();
		$db = Yii::app()->db;
		// p($formdata);
		if(!empty($formdata)) {
			$transaction = $db->beginTransaction();
			try{
				foreach ($formdata as $lid) {
					$model = PeisongGroup::model()->find('lid=:lid and dpid=:companyId' , array(':lid' => $lid , ':companyId' => $dpid)) ;
					if(!empty($model)) {
						$command = $model->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
						if (!empty($command)) {
							$modell = PeisongGroupDetail::model()->findAll('peisong_group_id=:lid and dpid=:companyId' , array(':lid' => $lid , ':companyId' => $dpid)) ;
							foreach ($modell as $modells) {
								$commandl = $modells->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
							}
						}
					}
				}
				$transaction->commit();
				Yii::app()->user->setFlash('success' ,yii::t('app', '删除成功'));
				$this->redirect(array('peisonggroup/index' , 'companyId' => $dpid));
			}catch(Exception $e){
	                $transaction->rollBack();
	                Yii::app()->user->setFlash('error' ,yii::t('app', '删除失败'));
	                $this->redirect(array('peisonggroup/index','companyId' => $dpid));
	        }
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('peisonggroup/index' , 'companyId' => $dpid)) ;
		}
	}






	public function actionDetailIndex(){
		$istaocan = Yii::app()->request->getParam('istaocan',2);
		$pname = Yii::app()->request->getParam('pname',null);
		$dpid = Yii::app()->request->getParam('companyId');
		$page = Yii::app()->request->getParam('page');
		$peisonggroupid = Yii::app()->request->getParam('peisonggroupid');
		$categoryId = Yii::app()->request->getParam('cid',0);
		$groupname = PeisongGroup::model()->find('lid=:lid and dpid=:companyId' , array(':lid' => $peisonggroupid , ':companyId' => $dpid))->group_name ;
		$db = Yii::app()->db;
		if(!Yii::app()->request->isPostRequest) {
			if($pname==null) {
				$psname='';
			}else{
				$psname =' and pm.material_name like "%'.$pname.'%"';
			}
			$sql='select pm.mphs_code,pm.lid as pm_material_id,pm.material_name,psgd.material_id,psgd.lid,psgd.stock_dpid,psgd.peisong_group_id from nb_product_material pm left join nb_peisong_group_detail psgd on(psgd.mphs_code=pm.mphs_code and psgd.peisong_group_id='.$peisonggroupid.') where  pm.delete_flag=0 and pm.dpid='.$dpid.$psname;
			$models = Yii::app()->db->createCommand($sql)->queryALL();
			// p($models);
			$sql1='select t.dpid,t.company_name from nb_company t where t.type=2 and t.delete_flag=0 and t.comp_dpid='.$dpid;
			$stock_dpids = Yii::app()->db->createCommand($sql1)->queryALL();
			// p($stock_dpids);

			$count = count($models);
			$pages = new CPagination($count);
			$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
			$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
			$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
			$models = $pdata->queryAll();
		}
		$this->render('detailindex',array(
			'models'=> $models,
			'stock_dpids'=> $stock_dpids,
			'istaocan'=> $istaocan,
			'groupname'=> $groupname,
			'peisonggroupid'=> $peisonggroupid,
			'pages'=>$pages,
		));
	}
	public function actionSaved(){
		$lid = Yii::app()->request->getParam('lid');//配送详情表的id
		$peisonggroupid = Yii::app()->request->getParam('peisonggroupid');
		$stock_dpid = Yii::app()->request->getParam('stock_dpid');
		$material_id = Yii::app()->request->getParam('material_id');
		$mphs_code = Yii::app()->request->getParam('mphs_code');
		// p($lid);
		$dpid = Yii::app()->request->getParam('companyId');
		$db = Yii::app()->db;
		if (!empty($lid)) {
			$data = PeisongGroupDetail::model();
			$info = $data->find('lid=:lid',array(':lid'=>$lid));
			if($info) {
				$command = $info->saveAttributes(array('stock_dpid'=>$stock_dpid,'update_at'=>date('Y-m-d H:i:s',time())));
				if(!empty($command)){
					echo json_encode(array(1,$lid));exit;
				}else{
					echo json_encode(array(0));exit;
				}
			}
		}else{
			$se=new Sequence("peisong_group_detail");
			$lids = $se->nextval();
			$data = array(
					'lid'=>$lids,
					'dpid'=>$dpid,
					'create_at'=>date('Y-m-d H:i:s',time()),
					'update_at'=>date('Y-m-d H:i:s',time()),
					'peisong_group_id'=>$peisonggroupid,
					'stock_dpid'=>$stock_dpid,
					'material_id'=>$material_id,
					'mphs_code'=>$mphs_code,
					'delete_flag'=>'0',
			);
			$command = $db->createCommand()->insert('nb_peisong_group_detail',$data);
			if(!empty($command)){
				echo json_encode(array(1,$lids));exit;
			}else{
				echo json_encode(array(0));exit;
			}
		}
	}


	/*
	* 分组详情产品删除
	
	public function actionDetailDelete(){
		$formdata = Yii::app()->request->getParam('lid');
		$dpid = Yii::app()->request->getParam('companyId');
		$pricegroupid = Yii::app()->request->getParam('pricegroupid');
		$models = PriceGroup::model();
		if(!is_array($formdata)){
			$formdata=array($formdata);
		}
		// p($formdata);
		if(!empty($formdata)) {
			foreach ($formdata as $lid) {
				$model = PriceGroupDetail::model()->find('lid=:lid and dpid=:companyId' , array(':lid' => $lid , ':companyId' => $dpid)) ;
				if($model) {
					$model->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
				}
			}
			Yii::app()->user->setFlash('success' ,yii::t('app', '删除成功'));
			$this->redirect(array('priceGroup/detailIndex' , 'companyId' => $dpid,'pricegroupid'=>$pricegroupid)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('priceGroup/detailIndex' , 'companyId' => $dpid,'pricegroupid'=>$pricegroupid)) ;
		}
	}
	*/
}
?>