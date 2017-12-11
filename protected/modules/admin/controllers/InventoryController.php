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
		$criteria->with = 'company';
		$mid=0;
		$oid=0;
		$begintime=0;
		$endtime=0;
		$storage=0;
		$purchase=0;
		$criteria = new CDbCriteria;
		$criteria->addCondition('dpid=:dpid and delete_flag=0');
		if(Yii::app()->request->isPostRequest){
			$mid = Yii::app()->request->getPost('mid',0);
			if($mid){
				$maname = ManufacturerInformation::model()->findAll('manufacturer_name like "%'.$mid.'%" and dpid=:dpid and delete_flag = 0 ' , array(':dpid'=>  $this->companyId));
				//var_dump($maname);exit;
				if($maname){
					$malides = '';
					foreach ($maname as $manames){
						$malid = $manames->lid;
						$malides = $malid .','. $malides; 
						//var_dump($malides);
					}
					$malides = substr($malides, 0,strlen($malides)-1);
					$criteria->addCondition('manufacturer_id in ('.$malides.')');
					//var_dump($criteria);exit;
				}else{
					$criteria->addSearchCondition('manufacturer_id',$mid);
				}
			}
			//var_dump($criteria);exit;
			$oid = Yii::app()->request->getPost('oid',0);
			if($oid){
				//echo($oid);
				$ogname = Company::model()->find('company_name like "%'.$oid.'%" and delete_flag = 0');
				//var_dump($ogname);exit;
				if($ogname){
					$malides = '';
					foreach ($ogname as $manames){
						$malid = $manames->lid;
						$malides = $malid .','. $malides;
						//var_dump($malides);
					}
					$malides = substr($malides, 0,strlen($malides)-1);
					$criteria->addCondition('opertion_id in ('.$malides.')');
					//var_dump($criteria);exit;
				}else{
					$criteria->addSearchCondition('opertion_id',$oid);
				}
				//$criteria->addSearchCondition('organization_id',$ogname->dpid);
			}
			$storage = Yii::app()->request->getPost('storage',0);
			if($storage){
				//echo($oid);
				//$ogname = Company::model()->find('company_name like "%'.$oid.'%" and delete_flag = 0');
				//var_dump($ogname);exit;
				$criteria->addSearchCondition('inventory_account_no',$storage);
			}
			$purchase = Yii::app()->request->getPost('purchase',0);
			if($purchase){
				//echo($oid);
				//$ogname = Company::model()->find('company_name like "%'.$oid.'%" and delete_flag = 0');
				//var_dump($ogname);exit;
				$criteria->addSearchCondition('purchase_account_no',$purchase);
			}
			$begintime = Yii::app()->request->getPost('begintime',0);
			if($begintime){
				$criteria->addCondition('storage_date >= "'.$begintime.'" ');
			}
			$endtime = Yii::app()->request->getPost('endtime',0);
			if($endtime){
				$criteria->addCondition('storage_date <= "'.$endtime.'" ');
			}
		}
		$criteria->order = ' lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		$pages = new CPagination(Inventory::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = Inventory::model()->findAll($criteria);
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
				'mid'=>$mid,
				'oid'=>$oid,
				'begintime'=>$begintime,
				'endtime'=>$endtime,
				'storage'=>$storage,
				'purchase'=>$purchase,
		));
	}
	public function actionSetMealList() {
		
	}
	public function actionCreate(){
		$model = new Inventory();
		$model->dpid = $this->companyId ;

		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Inventory');
			$se=new Sequence("inventory");
			$model->lid = $se->nextval();
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			$model->inventory_account_no = date('YmdHis',time()).substr($model->lid,-4);
			$model->status = 0;

			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('inventory/detailindex','lid' => $model->lid , 'companyId' => $model->dpid));
			}
		}
		//$categories = $categories = StorageOrder::model()->findAll('delete_flag=0 and dpid=:companyId' , array(':companyId' => $this->companyId)) ;
		//var_dump($categories);exit;
		$this->render('create' , array(
			'model' => $model ,
			//'categories' => $categories
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
		$model = new InventoryDetail();
		//var_dump($model);exit;
		$model->dpid = $this->companyId ;
		$rlid = Yii::app()->request->getParam('lid');//var_dump($polid);exit;
		$model->inventory_id=$rlid;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('InventoryDetail');
			$retreatId = Yii::app()->request->getParam('InventoryDetail_retreat_id');
			$se=new Sequence("inventory_detail");
			$model->lid = $se->nextval();
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			$model->retreat_id = $retreatId;
			//var_dump($model);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('inventory/detailindex' , 'companyId' => $this->companyId, 'lid'=>$model->inventory_id ));
			}
		}
		$categories = $this->getCategories();
		$retreats = $this->getretreats();
		$categoryId=0;
		$retreatId=0;
		$materials = $this->getMaterials($categoryId);
		$materialslist=CHtml::listData($materials, 'lid', 'material_name');
		$retreats = $this->getretreats();
		$retreatslist=CHtml::listData($retreats, 'lid', 'name');
		$this->render('detailcreate' , array(
				'model' => $model ,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
				'materials'=>$materialslist,
				'retreats'=>$retreats,
				'retreatId'=>$retreatId
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
		
		//Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_inventory_detail set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('inventory/detailindex' , 'companyId' => $companyId,'lid'=>$slid,'status'=>$status, )) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
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
	private function getMaterials($categoryId){
		if($categoryId==0)
		{
			$materials = ProductMaterial::model()->findAll('dpid=:companyId and delete_flag=0' , array(':companyId' => $this->companyId));
		}else{
			$materials = ProductMaterial::model()->findAll('dpid=:companyId and category_id=:categoryId and delete_flag=0' , array(':companyId' => $this->companyId,':categoryId'=>$categoryId)) ;
		}
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
// 		$transaction = $db->beginTransaction();
// 		try
// 		{
// 			$is_sync = DataSync::getInitSync();
// 			//盘点日志
// 			$stocktaking = new StockTaking();
// 			$se=new Sequence("stock_taking");
// 			$logid = $stocktaking->lid = $se->nextval();
// 			$stocktaking->dpid = $dpid;
// 			$stocktaking->create_at = date('Y-m-d H:i:s',time());
// 			$stocktaking->update_at = date('Y-m-d H:i:s',time());
// 			$stocktaking->username = $username ;
// 			$stocktaking->title =''.date('m月d日 H时i分',time()).' 盘点操作记录';
// 			$stocktaking->status = 0;
// 			$stocktaking->is_sync = $is_sync;
// 			$stocktaking->save();

			$sql = 'select * from nb_inventory_detail where delete_flag = 0 and dpid ='.$dpid.' and inventory_id ='.$pid;
			$invends = $db->createCommand($sql)->queryAll();
			var_dump($invends);exit;
			foreach ($invends as $in){
				$opt = array();
				$opt = explode(',',$opts);
				$id = $opt[0];
				$difference = $opt[1];
				$nowNum = $opt[2];
				$originalNum = $opt[3];
	
				$all_num = '0.00';
				$laststocks = '0.00';
				$laststockid = '0';
				$laststocktime = '0';
				$psstock = '0.00';
				$allpansun_price = '0';
				$all_price = '0';
	
				$stocks = ProductMaterialStock::model()->find('material_id=:sid and dpid=:dpid and delete_flag=0 and t.create_at =(select max(t1.create_at) from nb_product_material_stock t1 where t1.delete_flag = 0 and t1.dpid='.$this->companyId.' and t1.material_id ='.$id.' )',array(':sid'=>$id,':dpid'=>$this->companyId,));
				if(!empty($stocks)){
						
					$sql = 'select sum(t.stock_num) as all_stock,sum(t.unit_price*t.stock_num) as all_price from nb_material_stock_log t where t.delete_flag = 0 and t.st_status = 0 and t.type = 1 and t.dpid ='.$dpid.' and t.material_id ='.$id;
					$salesstock = $db->createCommand($sql)->queryRow();
						
					$laststocksql = 'select * from nb_stock_taking_detail t where t.logid in(select tt.lid from nb_stock_taking tt where tt.status =0 and tt.delete_flag =0 and tt.dpid ='.$dpid.') and t.delete_flag = 0 and t.status = 0 and t.dpid ='.$dpid.' and t.material_id ='.$id.' order by lid desc';
					$laststock = $db->createCommand($laststocksql)->queryRow();
						
	
					if(!empty($salesstock)){
						$all_num = $salesstock['all_stock'];
						$all_price = $salesstock['all_price'];
						if(!$all_num){
							$all_num = '0.00';
						}
					}
					if(!empty($laststock)){
						$laststocks = $laststock['taking_stock'];
						$laststockid = $laststock['lid'];
						$laststocktime = $laststock['create_at'];
						if(!$laststocks){
							$laststocks = '0.00';
							$laststockid = '0';
						}else{
							$pandunstocksql = 'select sum(t.number) as all_pansun_num from nb_stock_taking_detail t where t.logid in(select tt.lid from nb_stock_taking tt where tt.status =1 and tt.delete_flag =0 and tt.dpid ='.$dpid.') and t.delete_flag = 0 and t.status = 0 and t.dpid ='.$dpid.' and t.material_id ='.$id.' and t.create_at >="'.$laststocktime.'"';
							$pansunstock = $db->createCommand($pandunstocksql)->queryRow();
							//查询此次盘点至上次盘点之间的盘损总量。。。
								
							$psstpricesql = 'select sum(t.demage_price) as all_pansun_price from nb_stock_taking_detail t where t.logid in(select tt.lid from nb_stock_taking tt where tt.status =1 and tt.delete_flag =0 and tt.dpid ='.$dpid.') and t.delete_flag = 0 and t.status = 1 and t.dpid ='.$dpid.' and t.material_id ='.$id.' and t.create_at >="'.$laststocktime.'"';
							$pansunprice = $db->createCommand($psstpricesql)->queryRow();
							//查询此次盘点之上次盘点之间的盘损总成本...
							if(!empty($pansunstock)){
								$psstock = $pansunstock['all_pansun_num'];
							}
							if(!empty($pansunprice)){
								$allpansun_price = $pansunprice['all_pansun_price'];
							}
						}
					}
					//var_dump($pansunstock);exit;
					//对该次盘点进行日志保存
					$stocktakingdetail = new StockTakingDetail();
					$se=new Sequence("stock_taking_detail");
					$detailid = $se->nextval();
					$stocktakingdetail = array(
							'lid'=>$detailid,
							'dpid'=>$dpid,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'type'=>'0',
							'logid'=>$logid,
							'material_id'=>$id,
							'material_stock_id' => $stocks->lid,
							'last_stock_id'=>$laststockid,
							'last_stock_time'=>$laststocktime,
							'last_stock'=>$laststocks,
							'reality_stock' => $originalNum,
							'taking_stock' => $nowNum,
							'number'=>$difference,
							'sales_stocks'=>$all_num,
							'sales_price'=>$all_price,
							'demage_stock'=>$psstock,
							'demage_price'=>$allpansun_price,
							'reasion'=>'',
							'status' => 0,
							'is_sync'=>$is_sync,
					);
					//var_dump($stocktakingdetail);exit;
					$command = $db->createCommand()->insert('nb_stock_taking_detail',$stocktakingdetail);
					//var_dump($command);exit;
					if($command){
						$sqlupdate = 'update nb_material_stock_log set st_status="'.$detailid.'" where delete_flag = 0 and st_status = 0 and type = 1 and dpid ='.$dpid.' and material_id ='.$id;
						$result = $db->createCommand($sqlupdate)->execute();
					}
						
					if($difference > 0 ){
						//盘点操作，当盘点的库存比理论库存多时，直接在后进的库存批次上加上此次的盘点的差值。。。
						if($stocks->batch_stock == '0.00'||$stocks->batch_stock == null){
							$unit_price = '0';
						}else{
							$unit_price = $stocks->stock_cost / $stocks->batch_stock;
						}
						$all_price = $unit_price*$difference;
						//下面是对该次盘点进行的操作。。。
						$stocks->stock = $stocks->stock + $difference;
						$stocks->update_at = date('Y-m-d H:i:s',time());
	
						if($stocks->update()){
	
							//对该次盘点进行日志保存
							$stocktakingdetails = new StockTakingDetail();
							$se=new Sequence("stock_taking_detail");
							$stocktakingdetails = array(
									'lid'=>$se->nextval(),
									'dpid'=>$dpid,
									'create_at'=>date('Y-m-d H:i:s',time()),
									'update_at'=>date('Y-m-d H:i:s',time()),
									'type'=>'0',
									'logid'=>$logid,
									'detail_id'=>$detailid,
									'material_id'=>$id,
									'material_stock_id' => $stocks->lid,
									'reality_stock' => $stocks->stock,
									'taking_stock' => ''.$nowNum,
									'sales_price'=>$all_price,
									'number'=>''.$difference,
									'reasion'=>'',
									'status' => 1,
									'is_sync'=>$is_sync,
							);
							//var_dump($stocktakingdetails);
							$command = $db->createCommand()->insert('nb_stock_taking_detail',$stocktakingdetails);
						}
	
							
					}else{
	
						$sql = 'select t.* from nb_product_material_stock t where t.stock != "0.00" and t.delete_flag = 0 and t.dpid ='.$dpid.' and t.material_id = '.$id.' order by t.create_at asc';
						$command = $db->createCommand($sql);
						$stock2 = $command->queryAll();
						$minusnum = -$difference;
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
									$all_price = $unit_price*$minusnum;
									//对该次盘点进行日志保存
									$stocktakingdetails = new StockTakingDetail();
									$se=new Sequence("stock_taking_detail");
									$stocktakingdetails = array(
											'lid'=>$se->nextval(),
											'dpid'=>$dpid,
											'create_at'=>date('Y-m-d H:i:s',time()),
											'update_at'=>date('Y-m-d H:i:s',time()),
											'type'=>'0',
											'logid'=>$logid,
											'detail_id'=>$detailid,
											'material_id'=>$id,
											'material_stock_id' => $stock->lid,
											'reality_stock' => $stock->stock,
											'taking_stock' => ''.$changestock,
											'sales_price'=>$all_price,
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
									$all_price = -$unit_price*$stockori;
									//对该次盘点进行日志保存
									$materialStockLog = new StockTakingDetail();
									$se=new Sequence("stock_taking_detail");
									$materialStockLog = array(
											'lid'=>$se->nextval(),
											'dpid'=>$dpid,
											'create_at'=>date('Y-m-d H:i:s',time()),
											'update_at'=>date('Y-m-d H:i:s',time()),
											'type'=>'0',
											'logid'=>$logid,
											'detail_id'=>$detailid,
											'material_id'=>$id,
											'material_stock_id' => $stock->lid,
											'reality_stock' => $stock->stock,
											'taking_stock' => $stockori,
											'sales_price'=>$all_price,
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
							'type'=>'0',
							'logid'=>$logid,
							'material_id'=>$id,
							'material_stock_id' => '0000000000',
							'reality_stock' => $originalNum,
							'taking_stock' => $nowNum,
							'number'=>'0',
							'reasion'=>'该次盘点['.$matername.']尚未入库，无法进行盘点,请先入库.',
							'status' => 0,
							'is_sync'=>$is_sync,
					);
					//var_dump($stocktakingdetail);exit;
					$command = $db->createCommand()->insert('nb_stock_taking_detail',$stocktakingdetail);
				}
			}
// 			$transaction->commit();
// 			Yii::app()->end(json_encode(array("status"=>"success","msg"=>$nostockmsg,"logid"=>$logid)));
	
// 			return true;
// 		}catch (Exception $e) {
// 			$transaction->rollback(); //如果操作失败, 数据回滚
// 			exit;
// 			Yii::app()->end(json_encode(array("status"=>"fail")));
// 			return false;
// 		}
	}
	
	
}