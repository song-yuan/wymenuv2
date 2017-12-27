<?php
class InventoryController extends BackendController
{
	public function actions() {
		return array(
				'upload'=>array(
						'class'=>'application.extensions.swfupload.SWFUploadAction',
						//注意这里是绝对路径,.EXT是文件后缀名替代符号
						'filepath'=>Helper::genFileName().'.EXT',
						//'onAfterUpload'=>array($this,'saveFile'),
				)
		);
	}

	public function actionIndex(){
		$criteria = new CDbCriteria;
		$criteria->with = 'retreat';
		$mid=0;
		$oid=0;
		$begintime=0;
		$endtime=0;
		$storage=0;
		$purchase=0;
		$criteria->addCondition('t.dpid='.$this->companyId.' and t.delete_flag=0 and t.type =1');
		if(Yii::app()->request->isPostRequest){
			
			$storage = Yii::app()->request->getPost('reasonid',0);
			if($storage){
				$criteria->addCondition('t.reason_id ='.$storage);
			}
			$purchase = Yii::app()->request->getPost('purchase',0);
			if($purchase){
				$criteria->addSearchCondition('t.purchase_account_no',$purchase);
			}
			$begintime = Yii::app()->request->getPost('begintime',0);
			if($begintime){
				$criteria->addCondition('t.storage_date >= "'.$begintime.'" ');
			}
			$endtime = Yii::app()->request->getPost('endtime',0);
			if($endtime){
				$criteria->addCondition('t.storage_date <= "'.$endtime.'" ');
			}
		}
		$criteria->order = ' t.lid desc ';
		$pages = new CPagination(Inventory::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = Inventory::model()->findAll($criteria);
		
		$retreats = $this->getRets();
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
				'oid'=>$oid,
				'begintime'=>$begintime,
				'endtime'=>$endtime,
				'storage'=>$storage,
				'purchase'=>$purchase,
				'retreats'=>$retreats,
		));
	}
	public function actionSetMealList() {
		
	}
	public function actionCreate(){
		$model = new Inventory();
		$model->dpid = $this->companyId ;

		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Inventory');
			$retreatId = Yii::app()->request->getParam('Inventory_reason_id');
			$se=new Sequence("inventory");
			$model->lid = $se->nextval();
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			$model->reason_id = $retreatId;
			$model->inventory_account_no = date('YmdHis',time()).substr($model->lid,-4);
			$model->status = 0;
			//var_dump($model);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('inventory/detailindex','lid' => $model->lid , 'companyId' => $model->dpid));
			}
		}
		$retreatId =0;
		$retreats = $this->getretreats();
		$retreatslist=CHtml::listData($retreats, 'lid', 'name');		$this->render('create' , array(
			'model' => $model ,
			'retreats'=>$retreats,
			'retreatId'=>$retreatId
		));
	}
	
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$model = Inventory::model()->find('lid=:inventoryId and dpid=:dpid' , array(':inventoryId' => $lid,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		//Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Inventory');
			$model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				$this->redirect(array('inventory/index' , 'companyId' => $this->companyId ));
			}
		}

		$this->render('update' , array(
				'model' => $model ,
		));
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
        //Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_inventory set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('inventory/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('inventory/index' , 'companyId' => $companyId)) ;
		}
	}
	public function actionDetailIndex(){
		$criteria = new CDbCriteria;
		$slid = Yii::app()->request->getParam('lid');
		$status = Yii::app()->request->getParam('status');
		$criteria->with = 'material';
		$storage = Inventory::model()->find('lid=:id and dpid=:dpid',array(':id'=>$slid,':dpid'=>$this->companyId));
		$criteria->condition =  't.delete_flag = 0 and t.dpid='.$this->companyId .' and t.inventory_id='.$slid;
		$pages = new CPagination(InventoryDetail::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = InventoryDetail::model()->findAll($criteria);
		$this->render('detailindex',array(
				'storage'=>$storage,
				'models'=>$models,
				'pages'=>$pages,
				'slid'=>$slid,
				'status'=>$status,
		));
	}
	public function actionDetailCreate(){
		//var_dump($model);exit;
		$rlid = Yii::app()->request->getParam('lid');//var_dump($polid);exit;
		$db = Yii::app()->db;
		if(Yii::app()->request->isPostRequest) {
			$m = Yii::app()->request->getPost('ms');
			$ms = array();
			$ms = explode(',',$m);
			foreach ($ms as $m){
				$sql = 'select * from nb_inventory_detail where delete_flag =0 and material_id ='.$m.' and inventory_id='.$rlid;
				$mid = $db->createCommand($sql)->queryRow();
				if(empty($mid)){
					$idm = new InventoryDetail();
					$se=new Sequence("inventory_detail");
					$idm->lid = $se->nextval();
					$idm->dpid = $this->companyId;
					$idm->create_at = date('Y-m-d H:i:s',time());
					$idm->update_at = date('Y-m-d H:i:s',time());
					$idm->inventory_id = $rlid;
					$idm->material_id = $m;
					$idm->save();
				}
			}
			
			Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
			$this->redirect(array('inventory/detailindex' , 'companyId' => $this->companyId,'lid'=>$rlid ));
			
		}
		$categories = $this->getCategories();
		$retreats = $this->getretreats();
		$retreatId=0;
		$materials = $this->getMaterials();
		$this->render('detailcreate' , array(
				'categories'=>$categories,
				'materials'=>$materials,
				'retreats'=>$retreats,
				'retreatId'=>$retreatId,
				'rlid'=>$rlid
		));
	}

	public function actionDetailUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$model = InventoryDetail::model()->find('lid=:storagedetailId and dpid=:dpid' , array(':storagedetailId' => $lid,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		//Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('InventoryDetail');
			$retreatId = Yii::app()->request->getParam('InventoryDetail_retreat_id');
			//var_dump($model);exit;
			$model->update_at=date('Y-m-d H:i:s',time());
			$model->retreat_id = $retreatId;
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				$this->redirect(array('inventory/detailindex' , 'companyId' => $this->companyId, 'lid'=>$model->inventory_id));
			}
		}
		
		$categories = $this->getCategories();
		$retreats = $this->getretreats();
		$categoryId=  $this->getCategoryId($lid);
		$retreatId=  $this->getRetreatId($lid);
		$materials = $this->getMaterials($categoryId);
		$materialslist=CHtml::listData($materials, 'lid', 'material_name');
		
		//$retreatslist=CHtml::listData($retreats, 'lid', 'name');
		//var_dump($model);var_dump($categoryId);var_dump($materials);exit;
		$this->render('detailupdate' , array(
				'model' => $model ,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
				'materials'=>$materialslist,
				'retreats'=>$retreats,
				'retreatId'=>$retreatId
		));
	}
	public function actionDetailDelete(){
		$slid = Yii::app()->request->getParam('slid');
		$status = Yii::app()->request->getParam('status');
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_inventory_detail set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('inventory/detailindex' , 'companyId' => $companyId,'lid'=>$slid,'status'=>$status, )) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请勾选要删除的项目'));
			$this->redirect(array('inventory/detailindex' , 'companyId' => $companyId,'lid'=>$slid,'status'=>$status, )) ;
		}
	}
	public function actionStorageIn(){
		$sid = Yii::app()->request->getParam('sid');
		$storage = StorageOrder::model()->find('lid=:id and dpid=:dpid and delete_flag=0',array(':id'=>$sid,':dpid'=>$this->companyId));
		if($storage->status){
			$storageDetails = StorageOrderDetail::model()->findAll('storage_id=:sid and dpid=:dpid and delete_flag=0',array(':sid'=>$sid,':dpid'=>$this->companyId));
			$transaction = Yii::app()->db->beginTransaction();
			try{
				
				foreach ($storageDetails as $detail){
					$stock = $detail['stock'];
					$stockCost = ($detail['stock']-$detail['free_stock'])*$detail['price'];
					ProductMaterialStock::updateStock($storage->organization_id, $detail['material_id'], $stock, $stockCost);
					
					//入库日志
					$materialStockLog = new MaterialStockLog();
					$se=new Sequence("material_stock_log");
					$materialStockLog->lid = $se->nextval();
					$materialStockLog->dpid = $storage->organization_id;
					$materialStockLog->create_at = date('Y-m-d H:i:s',time());
					$materialStockLog->update_at = date('Y-m-d H:i:s',time());
					$materialStockLog->material_id = $detail['material_id'];
					$materialStockLog->type = 0;
					$materialStockLog->stock_num = $stock;
					$materialStockLog->resean = '入库单入库';
					$materialStockLog->save();
				}
				StorageOrder::updateStatus($this->companyId, $sid);
				$transaction->commit();
				echo 'true';exit;
			}catch (Exception $e){
				$transaction->rollback();
				echo 'false';exit;
			}
		}
		echo 'false';
		exit;
	}
	private function getCategories(){
		$criteria = new CDbCriteria;
		$criteria->with = 'company';
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId ;
		$criteria->order = ' t.lid asc ';
		$models = MaterialCategory::model()->findAll($criteria);
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
			//var_dump($options);exit;
		}
		foreach ($options as $k=>$v) {
			//var_dump($k,$v);exit;
			$model = MaterialCategory::model()->find('t.lid = :lid and dpid=:dpid',array(':lid'=>$k,':dpid'=>  $this->companyId));
			$optionsReturn[$model->category_name] = $v;
		}
		return $optionsReturn;
	}
	private function getRetreats(){
		$criteria = new CDbCriteria;
		$criteria->condition =  't.type=2 and t.delete_flag=0 and t.dpid='.$this->companyId ;
		$criteria->order = ' t.lid asc ';
		$models = Retreat::model()->findAll($criteria);
		//var_dump($models);exit;
		$options = array();
		$optionsReturn = array(yii::t('app','--请选择原因--'));
		if($models) {
			foreach ($models as $model) {
				$optionsReturn[$model->lid] = $model->name;
			}
		}
		//var_dump($optionsReturn);exit;
		return $optionsReturn;
	}
	private function getRets(){
		$criteria = new CDbCriteria;
		$criteria->condition =  't.type=2 and t.delete_flag=0 and t.dpid='.$this->companyId ;
		$criteria->order = ' t.lid asc ';
		$models = Retreat::model()->findAll($criteria);
		return $models;
	}
	private function getMaterials(){
		$materials = ProductMaterial::model()->findAll('dpid=:companyId and delete_flag=0' , array(':companyId' => $this->companyId));
		$materials = $materials ? $materials : array();
		return $materials;
	}
	private function getRetreatss($dpid){
		$materials = Retreat::model()->findAll('type =2 and dpid=:companyId and delete_flag=0' , array(':companyId' => $dpid)) ;
		$materials = $materials ? $materials : array();
		return $materials;
	}

	public function actionGetChildren(){
		$categoryId = Yii::app()->request->getParam('pid',0);
		//$productSetId = Yii::app()->request->getParam('$productSetId',0);
		if(!$categoryId){
			Yii::app()->end(json_encode(array('data'=>array(),'delay'=>400)));
		}

		$treeDataSource = array('data'=>array(),'delay'=>400);
		$produts=  $this->getMaterials($categoryId);

		foreach($produts as $c){
			$tmp['name'] = $c['material_name'];
			$tmp['id'] = $c['lid'];
			$treeDataSource['data'][] = $tmp;
		}
		Yii::app()->end(json_encode($treeDataSource));
	}
	private function getCategoryId($lid){
		$db = Yii::app()->db;
		$sql = "SELECT category_id from nb_inventory_detail so,nb_product_material pm where so.dpid=pm.dpid and so.material_id=pm.lid and so.lid=:lid";
		$command=$db->createCommand($sql);
		$command->bindValue(":lid" , $lid);
		return $command->queryScalar();
	}
	private function getRetreatId($lid){
		$db = Yii::app()->db;
		$sql = "SELECT retreat_id from nb_inventory_detail where lid=:lid";
		$command=$db->createCommand($sql);
		$command->bindValue(":lid" , $lid);
		//var_dump($command->queryScalar());exit;
		return $command->queryScalar();
	}
	public function actionStorageVerify(){
		$pid = Yii::app()->request->getParam('pid');
		$type = Yii::app()->request->getParam('type');
		//echo $type;exit;
		$storage = Inventory::model()->find('lid=:id and dpid=:dpid and delete_flag=0',array(':id'=>$pid,':dpid'=>$this->companyId));
		$storage->status = $type;
		//$storage->update();
		//$sql = 'update nb_inventory set status = 0 where dpid='.$this->companyId.' and lid='.$pid;
		//Yii::app()->db->createCommand($sql)->execute();
		//var_dump($storage);
		//echo $storage;
		if($storage->update()){
			echo 'true';
		}else{
			echo 'false';
		}
		exit;
	}
	


	public function actionAllStore(){
		$pid = Yii::app()->request->getParam('pid');
		$username = Yii::app()->user->username;
		$dpid = $this->companyId;
		$db = Yii::app()->db;
		$nostockmsg = '';
		$transaction = $db->beginTransaction();
		try
		{
			$is_sync = DataSync::getInitSync();
			//盘点日志
			$stocktaking = new StockTaking();
			$se=new Sequence("stock_taking");
			$logid = $stocktaking->lid = $se->nextval();
			$stocktaking->dpid = $dpid;
			$stocktaking->create_at = date('Y-m-d H:i:s',time());
			$stocktaking->update_at = date('Y-m-d H:i:s',time());
			$stocktaking->username = $username ;
			$stocktaking->title =''.date('m月d日 H时i分',time()).' 盘损记录';
			$stocktaking->status = 1;
			$stocktaking->is_sync = $is_sync;
			$stocktaking->save();

			$sql = 'select t.*,ifnull(r.name,remark) as reason from nb_inventory_detail t left join nb_retreat r on(t.retreat_id = r.lid) where t.delete_flag = 0 and t.dpid ='.$dpid.' and t.inventory_id ='.$pid;
			$invends = $db->createCommand($sql)->queryAll();
			//var_dump($invends);exit;
			foreach ($invends as $opt){
				$id = $opt['material_id'];
				$originalNum = '0.00';
				$sql = 'select sum(pms.stock) as stocks from nb_product_material_stock pms where pms.stock>=0 and pms.dpid ='.$dpid.' and pms.material_id ='.$id;
				$ms = $db->createCommand($sql)->queryRow();
				if($ms){
					$originalNum = $ms['stocks'];//原始库存
				}
				//$difference = $opt[1];//盘损库存差值
				$nowNum = $opt['inventory_stock'];//盘损的库存
				
				$damagereason = $opt['reason'];//盘损原因
	
				$stocks = ProductMaterialStock::model()->find('material_id=:sid and dpid=:dpid and delete_flag=0 and t.create_at =(select max(t1.create_at) from nb_product_material_stock t1 where t1.delete_flag = 0 and t1.dpid='.$this->companyId.' and t1.material_id ='.$id.' )',array(':sid'=>$id,':dpid'=>$this->companyId,));
				if(!empty($stocks)){
					//对该次盘损进行日志保存
					$stocktakingdetail = new StockTakingDetail();
					$se=new Sequence("stock_taking_detail");
					$detailid = $se->nextval();
					$stocktakingdetail = array(
							'lid'=>$detailid,
							'dpid'=>$dpid,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'type'=>'1',
							'logid'=>$logid,
							'material_id'=>$id,
							'material_stock_id' => $stocks->lid,
							'reality_stock' => $originalNum,
							'taking_stock' => $nowNum,
							'number'=>$nowNum,
							'reasion'=>$damagereason,
							'status' => 0,
							'is_sync'=>$is_sync,
					);
					//var_dump($stocktakingdetails);
					$command = $db->createCommand()->insert('nb_stock_taking_detail',$stocktakingdetail);
						
					if($nowNum>0){
	
						$sql = 'select t.* from nb_product_material_stock t where t.stock != "0.00" and t.delete_flag = 0 and t.dpid ='.$dpid.' and t.material_id = '.$id.' order by t.create_at asc';
						$command = $db->createCommand($sql);
						$stock2 = $command->queryAll();
						$minusnum = $nowNum;
						//var_dump($minusnum.'@');
						foreach ($stock2 as $stockid){
							//print_r($stockid);exit;
							//var_dump($stockid);
							$stockori = $stockid['stock'];
							if($minusnum >= 0 && $stockori > 0){
								$minusnums = $minusnum - $stockori ;
								//var_dump($stockori.'@@');
								//var_dump($minusnums);exit;
								$stock = ProductMaterialStock::model()->find('material_id=:sid and dpid=:dpid and delete_flag=0 and lid=:lid',array(':sid'=>$id,':dpid'=>$this->companyId,':lid'=>$stockid['lid'],));
	
								if($stock->batch_stock == '0.00'||$stock->batch_stock == null){
									$unit_price = '0';
								}else{
									$unit_price = $stock->stock_cost / $stock->batch_stock;
								}
								if($minusnums <= 0 ) {
									//var_dump($minusnums.'@3');
									$changestock = $stock->stock - $minusnum;
									$sql1 = 'update nb_product_material_stock set stock = '.$changestock. ' where delete_flag = 0 and material_id ='.$id.' and dpid ='.$this->companyId.' and lid='.$stockid['lid'];
									//var_dump($sql1);
									//Yii::app()->db->createCommand($sql)->execute();
									$command=$db->createCommand($sql1);
									$command->execute();
									//$stock->update_at = date('Y-m-d H:i:s',time());
									//$stock->update();
									$all_price = -$unit_price *$minusnum;
									//对该次盘点进行日志保存
									$stocktakingdetails = new StockTakingDetail();
									$se=new Sequence("stock_taking_detail");
									$stocktakingdetails = array(
											'lid'=>$se->nextval(),
											'dpid'=>$dpid,
											'create_at'=>date('Y-m-d H:i:s',time()),
											'update_at'=>date('Y-m-d H:i:s',time()),
											'type'=>'1',
											'logid'=>$logid,
											'detail_id'=>$detailid,
											'material_id'=>$id,
											'material_stock_id' => $stock->lid,
											'reality_stock' => $stock->stock,
											'taking_stock' => ''.$changestock,
											'demage_price'=>$all_price,
											'number'=>'-'.$minusnum,
											'reasion'=>'',
											'status' => 1,
											'is_sync'=>$is_sync,
									);
									//var_dump($stocktakingdetails);
									$command = $db->createCommand()->insert('nb_stock_taking_detail',$stocktakingdetails);
										
									$minusnum = -1;
								}else{
									//var_dump($minusnums.'4');
									$minusnum = $minusnums;
									//var_dump($minusnum.'5');
									$sql2 = 'update nb_product_material_stock set stock=0 where delete_flag = 0 and lid ='.$stockid['lid'].' and dpid ='.$this->companyId.' and material_id ='.$id;
									//var_dump($sql2);
									$command=$db->createCommand($sql2);
									$command->execute();
									//Yii::app()->db->createCommand($sql)->execute();
									//$stock = ProductMaterialStock::model()->find('material_id=:sid and dpid=:dpid and delete_flag=0 and lid=:lid',array(':sid'=>$id,':dpid'=>$this->companyId,':lid'=>$stockid['lid'],));
									$all_price = -$unit_price *$stockori;
									//对该次盘点进行日志保存
									$materialStockLog = new StockTakingDetail();
									$se=new Sequence("stock_taking_detail");
									$materialStockLog = array(
											'lid'=>$se->nextval(),
											'dpid'=>$dpid,
											'create_at'=>date('Y-m-d H:i:s',time()),
											'update_at'=>date('Y-m-d H:i:s',time()),
											'type'=>'1',
											'logid'=>$logid,
											'detail_id'=>$detailid,
											'material_id'=>$id,
											'material_stock_id' => $stock->lid,
											'reality_stock' => $stock->stock,
											'taking_stock' => $stockori,
											'demage_price'=>$all_price,
											'number'=>'-'.$stockori,
											'reasion'=>'',
											'status' => 1,
											'is_sync'=>$is_sync,
	
									);
									//var_dump($materialStockLog);
									$command = $db->createCommand()->insert('nb_stock_taking_detail',$materialStockLog);
										
								}
							}
						}
						//exit;
					}
				}else{
					$matername = Common::getmaterialName($id);
					$nostockmsg = $nostockmsg.','.$matername;
					//对该次盘点进行日志保存
					$stocktakingdetail = new StockTakingDetail();
					$se=new Sequence("stock_taking_detail");
					$detailid = $se->nextval();
					$stocktakingdetail = array(
							'lid'=>$detailid,
							'dpid'=>$dpid,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'type'=>'1',
							'logid'=>$logid,
							'material_id'=>$id,
							'material_stock_id' => '0000000000',
							'reality_stock' => $originalNum,
							'taking_stock' => $nowNum,
							'number'=>'0',
							'reasion'=>'该次盘损['.$matername.']尚未入库，无法进行盘损,请先入库.',
							'status' => 0,
							'is_sync'=>$is_sync,
					);
					//var_dump($stocktakingdetail);exit;
					$command = $db->createCommand()->insert('nb_stock_taking_detail',$stocktakingdetail);
				}
			}
			$storage = Inventory::model()->find('lid=:id and dpid=:dpid and delete_flag=0',array(':id'=>$pid,':dpid'=>$dpid));
			$storage->status = '1';
			$storage->update();
			$transaction->commit();
			Yii::app()->end(true);
	
			return true;
		}catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			exit;
			Yii::app()->end(false);
			return false;
		}
	}

	public function actionSaveStore(){
	
		$username = Yii::app()->user->username;
		$optvals = Yii::app()->request->getParam('optval');
		$pid = Yii::app()->request->getParam('pid');
		$optval = array();
		$optval = explode(';',$optvals);
		//var_dump($optval);
		$dpid = $this->companyId;
		$db = Yii::app()->db;
		$nostockmsg = '';
		$transaction = $db->beginTransaction();
		try
		{
			foreach ($optval as $opts){
				$opt = array();
				$opt = explode(',',$opts);
				$id = $opt[0];
				$nowNum = $opt[1];
	
				$sts = InventoryDetail::model()->find('lid='.$id.' and delete_flag=0 and inventory_id ='.$pid);
				if(!empty($sts)){
					$sts->update_at = date('Y-m-d H:i:s',time());
					$sts->inventory_stock = $nowNum;
					$sts-> update();
				}
	
			}
			$transaction->commit();
			Yii::app()->end(json_encode(array("status"=>"success","msg"=>$nostockmsg,)));
	
			return true;
		}catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			exit;
			Yii::app()->end(json_encode(array("status"=>"fail")));
			return false;
		}
	}
	
}