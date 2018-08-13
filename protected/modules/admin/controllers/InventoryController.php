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
		$retreatId = Yii::app()->request->getParam('rid',0);
		$criteria = new CDbCriteria;
		$criteria->with = 'retreat';
		$criteria->addCondition('t.dpid='.$this->companyId.' and t.type =1 and t.delete_flag=0');
		if($retreatId){
			$criteria->addCondition('t.reason_id='.$retreatId);
		}
		$criteria->order = ' t.lid desc ';
		
		$pages = new CPagination(Inventory::model()->count($criteria));
		$pages->applyLimit($criteria);
		$models = Inventory::model()->findAll($criteria);
		
		$retreats = $this->getretreats();
		$this->render('index',array(
				'models'=>$models,
				'retreats'=>$retreats,
				'retreatId'=>$retreatId,
				'pages'=>$pages
		));
	}
	public function actionCreate(){
		$model = new Inventory();
		$model->dpid = $this->companyId ;

		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Inventory');
			$retreatId = Yii::app()->request->getParam('Inventory_reason_id');
			$se = new Sequence("inventory");
			$model->lid = $se->nextval();
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			//$model->reason_id = $retreatId;
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
		
		$retreatslist=CHtml::listData($retreats, 'lid', 'name');		
		$this->render('create' , array(
			'model' => $model ,
			'retreats'=>$retreats,
			'retreatId'=>$retreatId
		));
	}
	
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$model = Inventory::model()->find('lid=:inventoryId and dpid=:dpid' , array(':inventoryId' => $lid,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		$retreatId = $model->reason_id ;
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Inventory');
			$model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				$this->redirect(array('inventory/index' , 'companyId' => $this->companyId ));
			}
		}
		$retreats = $this->getretreats();
		$this->render('update' , array(
			'model' => $model ,
			'retreats'=>$retreats,
			'retreatId'=>$retreatId
		));
	}
	public function actionDelete(){
		$companyId = $this->companyId;
		$ids = Yii::app()->request->getPost('ids');
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
		$slid = Yii::app()->request->getParam('lid');
		$status = Yii::app()->request->getParam('status');
		
		$criteriaInventory = new CDbCriteria;
		$criteriaInventory->with = 'retreat';
		$criteriaInventory->addCondition('t.lid=:id and t.dpid=:dpid');
		$criteriaInventory->params = array(':id'=>$slid,':dpid'=>$this->companyId);
		$storage = Inventory::model()->find($criteriaInventory);
		
		$criteria = new CDbCriteria;
		$criteria->condition =  't.delete_flag = 0 and t.dpid='.$this->companyId .' and t.inventory_id='.$slid;
		$pages = new CPagination(InventoryDetail::model()->count($criteria));
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
		$rlid = Yii::app()->request->getParam('lid');
		$db = Yii::app()->db;
		if(Yii::app()->request->isPostRequest) {
			$m = Yii::app()->request->getPost('ms');
			if(!empty($m)){
				$ms = array();
				$ms = explode(';',$m);
				foreach ($ms as $mss){
					$m = explode(',',$mss);
					$mt = $m[1];
					$md = $m[0];
					$sql = 'select * from nb_inventory_detail where delete_flag =0 and material_id ='.$md.' and inventory_id='.$rlid;
					$mid = $db->createCommand($sql)->queryRow();
					if(empty($mid)){
						$idm = new InventoryDetail();
						$se = new Sequence("inventory_detail");
						$lid = $se->nextval();
						$idm->lid = $lid;
						$idm->dpid = $this->companyId;
						$idm->create_at = date('Y-m-d H:i:s',time());
						$idm->update_at = date('Y-m-d H:i:s',time());
						$idm->inventory_id = $rlid;
						$idm->material_id = $md;
						$idm->type = $mt;
						$idm->save();
					}
				}
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('inventory/detailindex' , 'companyId' => $this->companyId,'lid'=>$rlid ));
			}else{
				Yii::app()->user->setFlash('error' , yii::t('app','请选择要盘损的具体项'));
			}
		}
		$categories = $this->getCategories();
		$cateps = $this->getCateps();
		$retreats = $this->getretreats();
		$retreatId=0;
		$materials = $this->getMaterials();
		$products = $this->getProducts();
		$this->render('detailcreate' , array(
				'categories'=>$categories,
				'materials'=>$materials,
				'products'=>$products,
				'retreats'=>$retreats,
				'retreatId'=>$retreatId,
				'rlid'=>$rlid,
				'cateps'=>$cateps
		));
	}

	public function actionDetailUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$model = InventoryDetail::model()->find('lid=:storagedetailId and dpid=:dpid' , array(':storagedetailId' => $lid,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('InventoryDetail');
			$retreatId = Yii::app()->request->getParam('InventoryDetail_retreat_id');
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
		$companyId = $this->companyId;
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
	public function actionInventorylog(){
		$begintime = Yii::app()->request->getPost('begintime',date('Y-m-d',time()));
		$endtime = Yii::app()->request->getPost('endtime',date('Y-m-d',time()));
		$reasonid = Yii::app()->request->getPost('reasonid',0);
		
		$begintime = $begintime.' 00:00:00';
		$endtime = $endtime.' 23:59:59';
		
		$sql = 'select t.*,t1.opretion_id,t1.reason_id from nb_inventory_detail t,nb_inventory t1 where t.inventory_id=t1.lid and t.dpid=t1.dpid and t.dpid='.$this->companyId.' and t1.create_at>="'.$begintime.'" and t1.create_at<="'.$endtime.'" and t1.status=1';
		if($reasonid){
			$sql .= ' and t1.reason_id='.$reasonid;
		}
		$sql = 'select lid,dpid,opretion_id,type,material_id,reason_id,sum(inventory_stock) as inventory_stock from ('.$sql.')m group by type,material_id';
		$models = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($models as $key=>$model){
			$materialId = $model['material_id'];
			$reasonId = $model['reason_id'];
			$mtype = $model['type'];
			if($mtype==1){
				$material = Common::getmaterialUnit($materialId, $this->companyId, 0);
				$models[$key]['material_name'] = $material['material_name'];
				$models[$key]['unit_name'] = $material['unit_name'];
				$models[$key]['unit_specifications'] = $material['unit_specifications'];
			}else{
				$productName = Common::getproductName($materialId);
				$models[$key]['material_name'] = $productName;
				$models[$key]['unit_name'] = '个';
				$models[$key]['unit_specifications'] = '个';
			}
		}
		$retreats = $this->getRetreats();
		$this->render('inventorylog',array(
				'models'=>$models,
				'begintime'=>$begintime,
				'endtime'=>$endtime,
				'reasonid'=>$reasonid,
				'retreats'=>$retreats
		));
	}
	public function actionInventorylogdetail(){
		$slid = Yii::app()->request->getParam('lid');
		
		$sql = 'select t.lid,t.dpid,t1.name from nb_inventory t,nb_retreat t1 where t.reason_id=t1.lid and t.dpid=t1.dpid and t.lid='.$slid.' and t.dpid='.$this->companyId;
		$inventory = Yii::app()->db->createCommand($sql)->queryRow();
		
		$criteria = new CDbCriteria;
		$criteria->condition =  't.delete_flag = 0 and t.dpid='.$this->companyId .' and t.inventory_id='.$slid;
		$pages = new CPagination(InventoryDetail::model()->count($criteria));
		$pages->applyLimit($criteria);
		$models = InventoryDetail::model()->findAll($criteria);
		$this->render('inventorylogdetail',array(
				'inventory'=>$inventory,
				'models'=>$models,
				'pages'=>$pages,
		));
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

	public function actionAllStore(){
		$pid = Yii::app()->request->getParam('pid');
		$dpid = $this->companyId;
		$db = Yii::app()->db;
		
		$transaction = $db->beginTransaction();
		try
		{
			$sql = 'select t.*,ifnull(r.name,remark) as reason from nb_inventory_detail t left join nb_retreat r on(t.retreat_id = r.lid) where t.delete_flag = 0 and t.dpid ='.$dpid.' and t.inventory_id ='.$pid;
			$invends = $db->createCommand($sql)->queryAll();
			foreach ($invends as $opt){
				$tp = $opt['type'];//1 表示原料 2 表示产品
				$materialArr = array();
				if($tp==2){
					$sql = 'select * from nb_product_bom where dpid='.$dpid.' and product_id='.$opt['material_id'].' and delete_flag=0';
					$boms = $db->createCommand($sql)->queryAll();
					foreach ($boms as $bom){
						$tempArr = array();
						$tempArr['material_id'] = $bom['material_id'];
						$tempArr['inventory_stock'] = $opt['inventory_stock']*$bom['number'];
						$tempArr['reason'] = $opt['reason'];
						array_push($materialArr, $tempArr);
					}
				}else{
					$tempArr = array();
					$tempArr['material_id'] = $opt['material_id'];
					$tempArr['inventory_stock'] = $opt['inventory_stock'];
					$tempArr['reason'] = $opt['reason'];
					array_push($materialArr, $tempArr);
				}
				
				foreach ($materialArr as $material){
					$id = $material['material_id'];
					$nowNum = $material['inventory_stock'];//盘损的库存
					$damagereason = $material['reason'];//盘损原因
					
					// 盘损记录
					$originalNum = 0;
					$sql = 'select sum(stock) as stocks from nb_product_material_stock where stock != 0 and dpid ='.$dpid.' and material_id ='.$id;
					$ms = $db->createCommand($sql)->queryRow();
					if($ms){
						$originalNum = $ms['stocks'];//原始库存
					}
					
					$sql = 'select * from nb_product_material_stock where material_id='.$id.' and dpid='.$this->companyId.' and delete_flag=0 order by create_at desc limit 1';
					$stocks = $db->createCommand($sql)->queryRow();
					// 已经入库
					if(!empty($stocks)){
						//对该次盘损进行日志保存
						if($nowNum>0){
							$sql = 'select * from nb_product_material_stock where stock != 0 and dpid ='.$dpid.' and material_id = '.$id.' and delete_flag = 0 order by create_at asc';
							$stock2 = $db->createCommand($sql)->queryAll();
							$minusnum = $nowNum;
					
							foreach ($stock2 as $stock){
								$stockori = $stock['stock'];
								if($minusnum >= 0){
									$minusnums = $minusnum - $stockori ;
									if($stock['batch_stock'] == '0.00'){
										$unit_price = '0';
									}else{
										$unit_price = $stock['stock_cost'] / $stock['batch_stock'];
									}
									if($minusnums <= 0 ) {
										$changestock = $stock['stock'] - $minusnum;
											
										$sql = 'update nb_product_material_stock set stock = '.$changestock. ' where dpid ='.$this->companyId.' and lid='.$stock['lid'].' and delete_flag = 0';
										$command=$db->createCommand($sql)->execute();
										// 盘损成本
										//对该次盘损进行日志保存
										$se = new Sequence("material_stock_log");
										$stocktakingdetails = array(
												'lid'=>$se->nextval(),
												'dpid'=>$dpid,
												'create_at'=>date('Y-m-d H:i:s',time()),
												'update_at'=>date('Y-m-d H:i:s',time()),
												'type'=>4,
												'logid'=>$pid,
												'material_id'=>$id,
												'stock_num' => $minusnum,
												'original_num' => $stock['stock'],
												'unit_price'=>$unit_price,
												'resean'=>'盘损消耗',
										);
										$command = $db->createCommand()->insert('nb_material_stock_log',$stocktakingdetails);
										break;
									}else{
										$minusnum = $minusnums;
										$sql = 'update nb_product_material_stock set stock=0 where delete_flag = 0 and lid ='.$stock['lid'].' and dpid ='.$this->companyId;
										$command = $db->createCommand($sql)->execute();
											
										//对该次盘点进行日志保存
										$se = new Sequence("material_stock_log");
										$stocktakingdetails = array(
												'lid'=>$se->nextval(),
												'dpid'=>$dpid,
												'create_at'=>date('Y-m-d H:i:s',time()),
												'update_at'=>date('Y-m-d H:i:s',time()),
												'type'=>4,
												'logid'=>$pid,
												'material_id'=>$id,
												'stock_num' => $stock['stock'],
												'original_num' => $stock['stock'],
												'unit_price'=>$unit_price,
												'resean'=>'盘损消耗',
										);
										$command = $db->createCommand()->insert('nb_material_stock_log',$stocktakingdetails);
									}
								}
							}
						}
					}else{
						$se = new Sequence("material_stock_log");
						$stocktakingdetails = array(
								'lid'=>$se->nextval(),
								'dpid'=>$dpid,
								'create_at'=>date('Y-m-d H:i:s',time()),
								'update_at'=>date('Y-m-d H:i:s',time()),
								'type'=>4,
								'logid'=>$pid,
								'material_id'=>$id,
								'stock_num' => $nowNum,
								'original_num' => 0,
								'unit_price'=>0,
								'resean'=>'盘损消耗',
						);
						$command = $db->createCommand()->insert('nb_material_stock_log',$stocktakingdetails);
					}
				}
			}
			
			$sql = 'update nb_inventory set status=1 where lid='.$pid.' and dpid='.$dpid;
			$db->createCommand($sql)->execute();
			$transaction->commit();
			Yii::app()->end(true);
		}catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			Yii::app()->end(false);
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
				
				$sql = 'update nb_inventory_detail set inventory_stock='.$nowNum.' where lid='.$id.' and inventory_id ='.$pid.' and delete_flag=0';
				$db->createCommand($sql)->execute();
			}
			$transaction->commit();
			Yii::app()->end(json_encode(array("status"=>"success","msg"=>$nostockmsg,)));
		}catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			Yii::app()->end(json_encode(array("status"=>"fail")));
		}
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
	private function getCateps(){
		$criteria = new CDbCriteria;
		$criteria->with = 'company';
		$criteria->condition =  't.cate_type !=2 and t.delete_flag=0 and t.dpid='.$this->companyId ;
		$criteria->order = ' t.lid asc ';
		$models = ProductCategory::model()->findAll($criteria);
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
			$model = ProductCategory::model()->find('t.lid = :lid and dpid=:dpid',array(':lid'=>$k,':dpid'=>  $this->companyId));
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
	private function getProducts(){
		$materials = Product::model()->findAll('dpid=:companyId and delete_flag=0' , array(':companyId' => $this->companyId));
		$materials = $materials ? $materials : array();
		return $materials;
	}
	private function getRetreatss($dpid){
		$materials = Retreat::model()->findAll('type =2 and dpid=:companyId and delete_flag=0' , array(':companyId' => $dpid)) ;
		$materials = $materials ? $materials : array();
		return $materials;
	}
	private function getCategoryId($lid){
		$db = Yii::app()->db;
		$sql = "SELECT category_id from nb_inventory_detail so,nb_product_material pm where so.dpid=pm.dpid and so.material_id=pm.lid and so.lid=:lid";
		$command=$db->createCommand($sql);
		$command->bindValue(":lid" , $lid);
		return $command->queryScalar();
	}
	private function getRetreat($lid,$dpid){
		$db = Yii::app()->db;
		$sql = "SELECT * from nb_retreat where lid=:lid and dpid=:dpid";
		$command=$db->createCommand($sql);
		$command->bindValue(":lid" , $lid);
		$command->bindValue(":dpid" , $dpid);
		return $command->queryRow();
	}
	private function getRetreatId($lid){
		$db = Yii::app()->db;
		$sql = "SELECT retreat_id from nb_inventory_detail where lid=:lid";
		$command=$db->createCommand($sql);
		$command->bindValue(":lid" , $lid);
		//var_dump($command->queryScalar());exit;
		return $command->queryScalar();
	}
	
}