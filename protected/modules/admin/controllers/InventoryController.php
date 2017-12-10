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
	
	
	
	
}