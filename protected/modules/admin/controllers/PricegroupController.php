<?php
/**
* 区域价格分组
*/
class PricegroupController extends BackendController
{
	/*
	 *区域价格分组列表
	 */
	public function actionIndex(){

		$criteria = new CDbCriteria;
		$criteria->addCondition('dpid=:dpid and delete_flag=0');
		$criteria->order = ' lid desc ';
		$criteria->params[':dpid']=$this->companyId;

		
		$pages = new CPagination(PriceGroup::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = PriceGroup::model()->findAll($criteria);
		$this->render('index',array(
			'models'=>$models,
			'pages'=>$pages,
		));
	}
	/*
	* 区域价格分组名添加
	*/
	public function actionCreate(){
		$model = new PriceGroup ;
		$dpid = Yii::app()->request->getParam('companyId');
		if(Yii::app()->request->isPostRequest) {
			$formdata = Yii::app()->request->getPost('PriceGroup');
			$se=new Sequence("price_group");
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
				$this->redirect(array('pricegroup/index' , 'companyId' => $this->companyId));
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
		$models = PriceGroup::model();
		$model = $models->find('lid=:lid and dpid=:dpid',array(':lid'=>$lid,':dpid'=>$dpid));
		if(Yii::app()->request->isPostRequest) {
			$formdata = Yii::app()->request->getPost('PriceGroup');
		// p($formdata);
            $model->update_at = date('Y-m-d H:i:s');
            $model->group_name = $formdata['group_name'];
            $model->group_desc = $formdata['group_desc'];
			// p($model);
			if ($model->save()) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '编辑成功'));
				$this->redirect(array('pricegroup/index' , 'companyId' => $this->companyId));
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
		$models = PriceGroup::model();
		$db = Yii::app()->db;
		// p($formdata);
		if(!empty($formdata)) {
			$transaction = $db->beginTransaction();
			try{
				foreach ($formdata as $lid) {
					$model = PriceGroup::model()->find('lid=:lid and dpid=:companyId' , array(':lid' => $lid , ':companyId' => $dpid)) ;
					if($model) {
						$command = $model->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
						if ($command) {
							$modell = PriceGroupDetail::model()->findAll('price_group_id=:lid and dpid=:companyId' , array(':lid' => $lid , ':companyId' => $dpid)) ;
							foreach ($modell as $modells) {
								$commandl = $modells->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
							}
						}
					}
				}
				$transaction->commit();
				Yii::app()->user->setFlash('success' ,yii::t('app', '删除成功'));
				$this->redirect(array('pricegroup/index' , 'companyId' => $dpid));
			}catch(Exception $e){
	                $transaction->rollBack();
	                Yii::app()->user->setFlash('error' ,yii::t('app', '删除失败'));
	                $this->redirect(array('pricegroup/index','companyId' => $dpid));
	        }
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('pricegroup/index' , 'companyId' => $dpid)) ;
		}
	}
	public function actionDetailIndex(){
		$dpid = Yii::app()->request->getParam('companyId');
		$page = Yii::app()->request->getParam('page');
		$pricegroupid = Yii::app()->request->getParam('pricegroupid');
		$groupname = PriceGroup::model()->find('lid=:lid and dpid=:companyId' , array(':lid' => $pricegroupid , ':companyId' => $dpid))->group_name ;
		// p($groupname);
		$db = Yii::app()->db;
		if(!Yii::app()->request->isPostRequest) {
			$sql='select  d.lid,s.lid as plid,s.member_price,s.set_name as name,s.main_picture,1 as is_set ,s.set_price as yuanjia,d.price,d.mb_price from nb_product_set s left JOIN nb_price_group_detail d on (s.lid=d.product_id and s.dpid=d.dpid and d.is_set=1 and d.delete_flag=0 and d.price_group_id='.$pricegroupid.')  where s.delete_flag=0 and s.dpid='.$dpid
				. ' union ' .
				' select  d.lid,t.lid as plid,t.member_price,t.product_name as name,t.main_picture,0 as is_set,t.original_price as yuanjia,d.price,d.mb_price from nb_product t left JOIN nb_price_group_detail d on (t.lid=d.product_id and t.dpid=d.dpid and d.is_set=0 and d.delete_flag=0 and d.price_group_id='.$pricegroupid.' )  where t.delete_flag=0 and t.dpid='.$dpid;//.' limit 10';
			// p($sql);
			$models = Yii::app()->db->createCommand($sql)->queryALL();

			$count = count($models);
			//var_dump($count);exit;
			$pages = new CPagination($count);
			$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
			$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
			$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
			$models = $pdata->queryAll();
		}else{
			$formdata = $_POST;
			// p($_GET);
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
			$this->redirect(array('pricegroup/detailIndex' , 'companyId' => $dpid,'pricegroupid'=>$pricegroupid,'page'=>$page));
			// p($formdata);
			}catch(Exception $e){
                $transaction->rollBack();
                Yii::app()->user->setFlash('error' ,yii::t('app', '修改失败'));
                $this->redirect(array('pricegroup/detailIndex','companyId' => $this->companyId));
            }
		}

		$this->render('detailindex',array(
			'models'=> $models,
			'groupname'=> $groupname,
			'pricegroupid'=> $pricegroupid,
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
		if ($lid) {
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
			if($command){
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