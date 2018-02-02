<?php
/**
* 品牌原料区域价格分组
*/

class PricegroupMController extends BackendController
{
	/*
	 *区域价格分组列表
	 */
	public function actionIndex(){
		$criteria = new CDbCriteria;
		$criteria->addCondition('dpid=:dpid and delete_flag=0');
		$criteria->order = ' lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		$pages = new CPagination(MaterialPriceGroup::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = MaterialPriceGroup::model()->findAll($criteria);
		// p($models);
		$this->render('index',array(
			'models'=>$models,
			'pages'=>$pages,
		));
	}
	/*
	* 区域价格分组名添加
	*/
	public function actionCreate(){
		$model = new MaterialPriceGroup ;
		$dpid = Yii::app()->request->getParam('companyId');
		$db = Yii::app()->db;
		if(Yii::app()->request->isPostRequest) {
			$formdata = Yii::app()->request->getPost('MaterialPriceGroup');
			$transaction = $db->beginTransaction();
			try{
				$se=new Sequence("material_price_group");
	            $lid = $se->nextval();
	            $model->lid = $lid;
	            $model->dpid = $dpid;
	            $model->create_at = date('Y-m-d H:i:s');
	            $model->update_at = date('Y-m-d H:i:s');
	            $model->group_name = $formdata['group_name'];
	            $model->group_desc = $formdata['group_desc'];
				if ($model->save()) {
					$sql='select g.* from nb_goods g inner join nb_company c on(g.dpid=c.dpid) where g.delete_flag=0 and c.delete_flag=0  and c.type=2 and c.comp_dpid='.$dpid;
					$models = Yii::app()->db->createCommand($sql)->queryALL();
					foreach ($models as $key => $model) {
						$se=new Sequence("material_price_group_detail");
						$lidd = $se->nextval();
						// p($lid);
						$data = array(
								'lid'=>$lidd,
								'dpid'=>$dpid,
								'create_at'=>date('Y-m-d H:i:s',time()),
								'update_at'=>date('Y-m-d H:i:s',time()),
								'price_group_id'=>$lid,
								'depot_id'=>$model['dpid'],
								'price'=>$model['original_price'],
								'mb_price'=>$model['original_price'],
								// 'mb_price'=>$model['member_price'],
								'goods_id'=>$model['lid'],
								'delete_flag'=>'0',
						);
					// p($data);
						$command = $db->createCommand()->insert('nb_material_price_group_detail',$data);
					}
				}
				$transaction->commit();
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('pricegroupM/index' , 'companyId' => $dpid));
			}catch(Exception $e){
	                $transaction->rollBack();
	                Yii::app()->user->setFlash('error' ,yii::t('app', '添加失败,请重试'));
	                $this->redirect(array('pricegroupM/index','companyId' => $dpid));
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
		$models = MaterialPriceGroup::model();
		$model = $models->find('lid=:lid and dpid=:dpid',array(':lid'=>$lid,':dpid'=>$dpid));
		if(Yii::app()->request->isPostRequest) {
			$formdata = Yii::app()->request->getPost('MaterialPriceGroup');
		// p($formdata);
            $model->update_at = date('Y-m-d H:i:s');
            $model->group_name = $formdata['group_name'];
            $model->group_desc = $formdata['group_desc'];
			// p($model);
			if ($model->save()) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '编辑成功'));
				$this->redirect(array('pricegroupM/index' , 'companyId' => $this->companyId));
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
		$models = MaterialPriceGroup::model();
		$db = Yii::app()->db;
		// p($formdata);
		if(!empty($formdata)) {
			$transaction = $db->beginTransaction();
			try{
				foreach ($formdata as $lid) {
					$model = MaterialPriceGroup::model()->find('lid=:lid and dpid=:companyId' , array(':lid' => $lid , ':companyId' => $this->companyId)) ;
					if(!empty($model)) {
						$command = $model->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
						if (!empty($command)) {
							$modell = MaterialPriceGroupDetail::model()->findAll('price_group_id=:lid and dpid=:companyId' , array(':lid' => $lid , ':companyId' => $this->companyId)) ;
							foreach ($modell as $modells) {
								$commandl = $modells->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
							}
						}
					}
				}
				$transaction->commit();
				Yii::app()->user->setFlash('success' ,yii::t('app', '删除成功'));
				$this->redirect(array('pricegroupM/index' , 'companyId' => $this->companyId));
			}catch(Exception $e){
	                $transaction->rollBack();
	                Yii::app()->user->setFlash('error' ,yii::t('app', '删除失败'));
	                $this->redirect(array('pricegroupM/index','companyId' => $this->companyId));
	        }
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('pricegroupM/index' , 'companyId' => $this->companyId)) ;
		}
	}
	public function actionDetailIndex(){
		$pname = Yii::app()->request->getParam('pname',null);
		$page = Yii::app()->request->getParam('page');
		$pricegroupid = Yii::app()->request->getParam('pricegroupid');
		$categoryId = Yii::app()->request->getParam('cid',0);
		$str = Yii::app()->request->getParam('str');

		$groupname = MaterialPriceGroup::model()->find('lid=:lid and dpid=:companyId' , array(':lid' => $pricegroupid , ':companyId' => $this->companyId))->group_name ;
		$db = Yii::app()->db;
		if(!Yii::app()->request->isPostRequest) {
			if($pname==null) {
				$pname='';
			}else{
				$pname =' and t.goods_name like "%'.$pname.'%"';
			}

			if($categoryId){
				$cates = ' and category_id ='.$categoryId;
			}else{
				$cates = '';
			}
			$dpid = Yii::app()->request->getParam('dpid',$this->companyId);

			if ($dpid=='') {
				$dpid = $this->companyId;
			}
			$info = Company::model()->find('dpid=:dpid and delete_flag=0 and type=0',array(':dpid'=>$dpid));
			$dpids = Company::model()->findAll('comp_dpid=:dpid and delete_flag=0 and type=2',array(':dpid'=>$this->companyId));
			if ($info) {
				$dpidstr = '';
				foreach ($dpids as $key => $dp_id) {
					$dpidstr .= $dp_id->dpid.',';
				}
				$dpidstr = substr($dpidstr,0,strlen($dpidstr)-1);
			}else{
				$dpidstr = $dpid;
			}
			$sql = 'select k.* from (select mc.category_name, t.*,p.price as mprice,p.mb_price,p.lid as dlid  from nb_goods t left join nb_material_category mc on(t.category_id = mc.lid and mc.delete_flag =0 ) left join (select mpgd.* from nb_material_price_group mpg left join nb_material_price_group_detail mpgd on(mpg.lid=mpgd.price_group_id)) p on(p.goods_id=t.lid and  p.price_group_id='.$pricegroupid.') where t.dpid in('.$dpidstr.') and t.delete_flag =0 '.$cates.$pname.') k';
			$models = Yii::app()->db->createCommand($sql)->queryALL();
			// p($models);

			$count = count($models);
			$pages = new CPagination($count);
			$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
			$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
			$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
			$models = $pdata->queryAll();
		}else{
			if ($str) {
				$arr = explode(",",$str);
				$transaction = $db->beginTransaction();
	            try{
					foreach ($arr as $key => $value) {
						$info_arr = explode("_",$value);
						if(!empty($info_arr[0])){
							$sql ='update nb_material_price_group_detail set price='.$info_arr[1].',update_at="'.date('Y-m-d H:i:s',time()).'" where delete_flag=0 and lid='.$info_arr[0];
							$i =Yii::app()->db->createCommand($sql)->execute();
						}else{
							$se=new Sequence("material_price_group_detail");
							$lid = $se->nextval();
							$data = array(
									'lid'=>$lid,
									'dpid'=>$this->companyId,
									'create_at'=>date('Y-m-d H:i:s',time()),
									'update_at'=>date('Y-m-d H:i:s',time()),
									'price_group_id'=>$pricegroupid,
									'depot_id'=>$info_arr[3],
									'price'=>$info_arr[1],
									'mb_price'=>$info_arr[1],
									'goods_id'=>$info_arr[2],
									'delete_flag'=>'0',
							);
						$command = $db->createCommand()->insert('nb_material_price_group_detail',$data);
						}
					}
					$transaction->commit();
					echo json_encode(1);exit;
	            }catch (Exception $e){
	                $transaction->rollback();
					echo json_encode(0);exit;
	            }
			}
		}
		$categories = $this->getCategories();
		$this->render('detailindex',array(
			'dpid'=>$dpid,
			'dpids'=>$dpids,
			'models'=> $models,
			'groupname'=> $groupname,
			'categories'=>$categories,
			'categoryId'=>$categoryId,
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
	private function getCategories(){

		$comps = Yii::app()->db->createCommand('select comp_dpid from nb_company where delete_flag = 0 and dpid ='.$this->companyId)->queryRow();
		//var_dump($compid);exit;
		if(!empty($comps)){
			$compid = $comps['comp_dpid'];
		}else{
			Yii::app()->user->setFlash('error' , yii::t('app','读取总部信息失败！'));
			$this->redirect(array('goods/index' , 'companyId' => $this->companyId)) ;
		}

		$criteria = new CDbCriteria;
		$criteria->with = 'company';
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$compid ;
		$criteria->order = ' tree,t.lid asc ';

		$models = MaterialCategory::model()->findAll($criteria);

		//return CHtml::listData($models, 'lid', 'category_name','pid');
		$options = array();
		$optionsReturn = array(yii::t('app','--请选择分类--'));
		if($models) {
			foreach ($models as $model) {
				if($model->pid == '0') {
					$options[$model->lid] = array();
				} else {
					$options[$model->pid][$model->lid] = $model->category_name;
				}
			}
		}
		foreach ($options as $k=>$v) {
                    //var_dump($k,$v);exit;
			$model = MaterialCategory::model()->find('t.lid = :lid and dpid=:dpid',array(':lid'=>$k,':dpid'=> $compid));
			$optionsReturn[$model->category_name] = $v;
		}
		return $optionsReturn;
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