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
							$modell = PeisongGroupDetail::model()->findAll('price_group_id=:lid and dpid=:companyId' , array(':lid' => $lid , ':companyId' => $dpid)) ;
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
				$psname =' and s.material_name like "%'.$pname.'%"';
			}
			$sql='select pm.*,psgd.* from nb_product_material pm left join nb_peisong_group_detail psgd on(psgd.mphs_code=pm.mphs_code and psgd.peisong_group_id='.$peisonggroupid.') where  pm.delete_flag=0 and pm.dpid='.$dpid.$psname;
			
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
		}else{
			$formdata = $_POST;
			// p($formdata);
			/*
				批量插入新的产品价格
			*/
			$transaction = $db->beginTransaction();
			try{
			/*
				批量插入分组产品价格详情
			*/
			if (!empty($_POST['price'])) {
				foreach ($_POST['price'] as $lidp => $price) {
					foreach ($_POST['is_set'] as $lidi => $is_set) {
						foreach ($_POST['mb_price'] as $midl => $mb_price) {
							foreach ($_POST['plid'] as $lidl => $plid) {
								if ($lidp==$lidi&&$lidp==$lidl&&$lidp==$midl) {
									$se=new Sequence("price_group_detail");
									$lid = $se->nextval();
									// p($lid);
									$data = array(
											'lid'=>$lid,
											'dpid'=>$dpid,
											'create_at'=>date('Y-m-d H:i:s',time()),
											'update_at'=>date('Y-m-d H:i:s',time()),
											'price_group_id'=>$pricegroupid,
											'is_set'=>$is_set,
											'price'=>$price,
											'mb_price'=>$mb_price,
											'product_id'=>$plid,
											'delete_flag'=>'0',
									);
									$command = $db->createCommand()->insert('nb_price_group_detail',$data);
								}
							}
						}
					}
				}
			}
			/*
				批量修改分组产品价格详情
			*/
			$data = PriceGroupDetail::model();
			if (!empty($_POST['priced'])) {
				foreach ($_POST['priced'] as $lidp => $price) {
					foreach ($_POST['mb_priced'] as $mdip => $mb_price) {
						foreach ($_POST['is_seted'] as $lidi => $is_set) {
							if ($lidp==$lidi&&$mdip==$lidi) {
								$info = $data->find('lid=:lid',array(':lid'=>$lidp));
								// p($info);
								if($info) {
									$info->saveAttributes(array('price'=>$price,'mb_price'=>$mb_price,'update_at'=>date('Y-m-d H:i:s',time())));
								}
							}
						}
					}
				}
			}
			//执行事务
            $transaction->commit();
			Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
			$this->redirect(array('pricegroup/detailIndex' , 'companyId' => $dpid,'peisonggroupid'=>$peisonggroupid,'page'=>$page));
			// p($formdata);
			}catch(Exception $e){
                $transaction->rollBack();
                Yii::app()->user->setFlash('error' ,yii::t('app', '修改失败'));
                $this->redirect(array('pricegroup/detailIndex','companyId' => $this->companyId));
            }
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
		$lid = Yii::app()->request->getParam('lid');
		$price = Yii::app()->request->getParam('price');
		$mb_price = Yii::app()->request->getParam('mb_price');
		$ist = Yii::app()->request->getParam('ist');
		$pid = Yii::app()->request->getParam('pid');
		$pricegroupid = Yii::app()->request->getParam('pricegroupid');
		$dpid = Yii::app()->request->getParam('companyId');
		$db = Yii::app()->db;
		if (!empty($lid)) {
			$data = PriceGroupDetail::model();
			$info = $data->find('lid=:lid',array(':lid'=>$lid));
			if($info) {
				$command = $info->saveAttributes(array('price'=>$price,'mb_price'=>$mb_price,'update_at'=>date('Y-m-d H:i:s',time())));
				if($command){
					Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
					$this->redirect(array('pricegroup/detailIndex' , 'companyId' => $dpid,'pricegroupid'=>$pricegroupid));
				}else{
					Yii::app()->user->setFlash('error' ,yii::t('app', '修改失败'));
					$this->redirect(array('pricegroup/detailIndex' , 'companyId' => $dpid,'pricegroupid'=>$pricegroupid));
				}
			}
		}else{
			$se=new Sequence("price_group_detail");
			$lids = $se->nextval();
			$data = array(
					'lid'=>$lids,
					'dpid'=>$dpid,
					'create_at'=>date('Y-m-d H:i:s',time()),
					'update_at'=>date('Y-m-d H:i:s',time()),
					'price_group_id'=>$pricegroupid,
					'is_set'=>$ist,
					'price'=>$price,
					'mb_price'=>$mb_price,
					'product_id'=>$pid,
					'delete_flag'=>'0',
			);
			$command = $db->createCommand()->insert('nb_price_group_detail',$data);
			if(!empty($command)){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('pricegroup/detailIndex' , 'companyId' => $dpid,'pricegroupid'=>$pricegroupid));
			}else{
				Yii::app()->user->setFlash('error' ,yii::t('app', '修改失败'));
				$this->redirect(array('pricegroup/detailIndex' , 'companyId' => $dpid,'pricegroupid'=>$pricegroupid));
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