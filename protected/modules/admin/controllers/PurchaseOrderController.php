<?php
class PurchaseOrderController extends BackendController
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
	// 门店采购订单 列表
	public function actionIndex(){
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
				$manames = ManufacturerInformation::model()->findAll('manufacturer_name like "%'.$mid.'%" and dpid=:dpid and delete_flag = 0 ' , array(':dpid'=>  $this->companyId));
				if($manames){
					$malides = '';
					foreach ($manames as $maname){
						$malid = $maname->lid;
						$malides = $malid .','. $malides; 
					}
					$malides = substr($malides, 0,strlen($malides)-1);
					$criteria->addCondition('manufacturer_id in ('.$malides.')');
				}else{
					$criteria->addSearchCondition('manufacturer_id',$mid);
				}
			}
			
			$oid = Yii::app()->request->getPost('oid',0);
			if($oid){
				$criteria->addSearchCondition('organization_id',$oid);
			}
			$storage = Yii::app()->request->getPost('storage',0);
			if($storage){
				$criteria->addSearchCondition('storage_account_no',$storage);
			}
			$purchase = Yii::app()->request->getPost('purchase',0);
			if($purchase){
				$criteria->addSearchCondition('purchase_account_no',$purchase);
			}
			$begintime = Yii::app()->request->getPost('begintime',0);
			if($begintime){
				$criteria->addCondition('delivery_date >= "'.$begintime.'" ');
			}
			$endtime = Yii::app()->request->getPost('endtime',0);
			if($endtime){
				$criteria->addCondition('delivery_date <= "'.$endtime.'" ');
			}
		}
		$criteria->order = ' lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		$pages = new CPagination(PurchaseOrder::model()->count($criteria));
		$pages->applyLimit($criteria);
		$models = PurchaseOrder::model()->findAll($criteria);
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
	// 仓库采购订单
	public function actionCkindex(){
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
				$manames = ManufacturerInformation::model()->findAll('manufacturer_name like "%'.$mid.'%" and dpid=:dpid and delete_flag = 0 ' , array(':dpid'=>  $this->companyId));
				if($manames){
					$malides = '';
					foreach ($manames as $maname){
						$malid = $maname->lid;
						$malides = $malid .','. $malides;
					}
					$malides = substr($malides, 0,strlen($malides)-1);
					$criteria->addCondition('manufacturer_id in ('.$malides.')');
				}else{
					$criteria->addSearchCondition('manufacturer_id',$mid);
				}
			}
				
			$storage = Yii::app()->request->getPost('storage',0);
			if($storage){
				$criteria->addSearchCondition('storage_account_no',$storage);
			}
			$purchase = Yii::app()->request->getPost('purchase',0);
			if($purchase){
				$criteria->addSearchCondition('purchase_account_no',$purchase);
			}
			$begintime = Yii::app()->request->getPost('begintime',0);
			if($begintime){
				$criteria->addCondition('delivery_date >= "'.$begintime.'" ');
			}
			$endtime = Yii::app()->request->getPost('endtime',0);
			if($endtime){
				$criteria->addCondition('delivery_date <= "'.$endtime.'" ');
			}
		}
		$criteria->order = ' lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		$pages = new CPagination(PurchaseOrder::model()->count($criteria));
		$pages->applyLimit($criteria);
		$models = PurchaseOrder::model()->findAll($criteria);
		$this->render('ckindex',array(
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
	// 门店新增采购单
	public function actionCreate(){
		$model = new PurchaseOrder();
		$model->dpid = $this->companyId ;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('PurchaseOrder');
			$se=new Sequence("purchase_order");
			$model->lid = $se->nextval();
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			$model->purchase_account_no = date('YmdHis',time()).substr($model->lid,-4);
			$model->delete_flag = '0';
			$model->status = '0';
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('purchaseOrder/index' , 'companyId' => $this->companyId ));
			}
		}
		$this->render('ckcreate' , array(
			'model' => $model ,
		));
	}
	// 仓库新增采购单
	public function actionCkcreate(){
		$model = new PurchaseOrder();
		$model->dpid = $this->companyId ;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('PurchaseOrder');
			$se=new Sequence("purchase_order");
			$model->lid = $se->nextval();
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			$model->purchase_account_no = date('YmdHis',time()).substr($model->lid,-4);
			$model->delete_flag = '0';
			$model->status = '0';
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('purchaseOrder/ckindex' , 'companyId' => $this->companyId ));
			}
		}
		$this->render('ckcreate' , array(
				'model' => $model ,
		));
	}
	// 门店编辑采购单
	public function actionUpdate(){
		$id = Yii::app()->request->getParam('lid');
		$model = PurchaseOrder::model()->find('lid=:materialId and dpid=:dpid' , array(':materialId' => $id,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('PurchaseOrder');
			$model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				$this->redirect(array('purchaseOrder/index' , 'companyId' => $this->companyId ));
			}
		}

		$this->render('update' , array(
				'model' => $model ,
		));
	}
	// 仓库编辑采购单
	public function actionCkupdate(){
		$id = Yii::app()->request->getParam('lid');
		$model = PurchaseOrder::model()->find('lid=:materialId and dpid=:dpid' , array(':materialId' => $id,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('PurchaseOrder');
			$model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				$this->redirect(array('purchaseOrder/ckindex' , 'companyId' => $this->companyId ));
			}
		}
	
		$this->render('ckupdate' , array(
				'model' => $model ,
		));
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_purchase_order set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('purchaseOrder/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('purchaseOrder/index' , 'companyId' => $companyId)) ;
		}
	}
	public function actionCkdelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_purchase_order set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('purchaseOrder/cdindex' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('purchaseOrder/cdindex' , 'companyId' => $companyId)) ;
		}
	}
	public function actionDetailIndex(){
		$polid = Yii::app()->request->getParam('lid');
		$status = Yii::app()->request->getParam('status');
		$purchase = PurchaseOrder::model()->find('lid=:id and dpid=:dpid and delete_flag=0',array(':id'=>$polid,':dpid'=>$this->companyId));
		$criteria = new CDbCriteria;
        $criteria->condition =  't.delete_flag = 0 and t.dpid='.$this->companyId .' and t.purchase_id='.$polid;
		$pages = new CPagination(PurchaseOrderDetail::model()->count($criteria));
		$pages->applyLimit($criteria);
		$models = PurchaseOrderDetail::model()->findAll($criteria);
		$this->render('detailindex',array(
				'models'=>$models,
				'purchase'=>$purchase,
				'pages'=>$pages,
				'polid'=>$polid,
				'status'=>$status,
		));
	}
	public function actionCkdetailIndex(){
		$polid = Yii::app()->request->getParam('lid');
		$status = Yii::app()->request->getParam('status');
		$purchase = PurchaseOrder::model()->find('lid=:id and dpid=:dpid and delete_flag=0',array(':id'=>$polid,':dpid'=>$this->companyId));
		$criteria = new CDbCriteria;
		$criteria->condition =  't.delete_flag = 0 and t.dpid='.$this->companyId .' and t.purchase_id='.$polid;
		$pages = new CPagination(PurchaseOrderDetail::model()->count($criteria));
		$pages->applyLimit($criteria);
		$models = PurchaseOrderDetail::model()->findAll($criteria);
		$this->render('ckdetailindex',array(
				'models'=>$models,
				'purchase'=>$purchase,
				'pages'=>$pages,
				'polid'=>$polid,
				'status'=>$status,
		));
	}
	public function actionDetailCreate(){
		$model = new PurchaseOrderDetail();
		$model->dpid = $this->companyId ;
        $polid = Yii::app()->request->getParam('lid');
        $model->purchase_id=$polid;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('PurchaseOrderDetail');
			
			$db = Yii::app()->db;
			$sql = 'select t.* from nb_product_material t where t.delete_flag = 0 and t.lid = '.$model->material_id;
			$command2 = $db->createCommand($sql)->queryRow();
			$stockUnitId = $command2['mphs_code'];
			
			if($stockUnitId){
				$se=new Sequence("purchase_order_detail");
				$model->lid = $se->nextval();
				$model->create_at = date('Y-m-d H:i:s',time());
				$model->update_at = date('Y-m-d H:i:s',time());
				$model->mphs_code = $stockUnitId;
				//  $model->delete_flag = '0';
				if($model->save()){
					Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
					$this->redirect(array('purchaseOrder/detailindex' , 'companyId' => $this->companyId,'lid'=>$model->purchase_id, ));
				}
			}else{
				Yii::app()->user->setFlash('error',yii::t('app','添加失败'));
				$this->redirect(array('purchaseOrder/detailindex' , 'companyId' => $this->companyId, 'lid'=>$model->purchase_id ));
			}
		}
		$categories = $this->getCategories();
		$categoryId=0;
		$materials = $this->getMaterials($categoryId);
		$materialslist=CHtml::listData($materials, 'lid', 'material_name');
		$this->render('detailcreate' , array(
				'model' => $model ,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
				'materials'=>$materialslist
		));
	}
	public function actionCkdetailCreate(){
		$model = new PurchaseOrderDetail();
		$model->dpid = $this->companyId ;
		$polid = Yii::app()->request->getParam('lid');
		$model->purchase_id=$polid;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('PurchaseOrderDetail');
				
			$db = Yii::app()->db;
			$sql = 'select t.* from nb_goods t where t.delete_flag = 0 and t.lid = '.$model->material_id;
			$command2 = $db->createCommand($sql)->queryRow();
			$stockUnitId = $command2['goods_code'];
				
			if($stockUnitId){
				$se=new Sequence("purchase_order_detail");
				$model->lid = $se->nextval();
				$model->create_at = date('Y-m-d H:i:s',time());
				$model->update_at = date('Y-m-d H:i:s',time());
				$model->mphs_code = $stockUnitId;
				if($model->save()){
					Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
					$this->redirect(array('purchaseOrder/ckdetailindex' , 'companyId' => $this->companyId,'lid'=>$model->purchase_id, ));
				}
			}else{
				Yii::app()->user->setFlash('error',yii::t('app','添加失败'));
				$this->redirect(array('purchaseOrder/ckdetailindex' , 'companyId' => $this->companyId, 'lid'=>$model->purchase_id ));
			}
		}
		$categories = $this->getGoodsCategories();
		$categoryId=0;
		$goods = $this->getGoods($categoryId);
		$materialslist=CHtml::listData($goods, 'lid', 'goods_name');
		$this->render('ckdetailcreate' , array(
				'model' => $model ,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
				'materials'=>$materialslist
		));
	}
	public function actionDetailUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$model = PurchaseOrderDetail::model()->find('lid=:materialId and dpid=:dpid' , array(':materialId' => $lid,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		//Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('PurchaseOrderDetail');
			$model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				$this->redirect(array('purchaseOrder/detailindex' , 'companyId' => $this->companyId,'lid'=>$model->purchase_id, ));
			}
		}
		$categories = $this->getCategories();
        $categoryId=  $this->getCategoryId($lid);
		$materials = $this->getMaterials($categoryId);
		$materialslist=CHtml::listData($materials, 'lid', 'material_name');
		$this->render('detailupdate' , array(
				'model' => $model ,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
				'materials'=>$materialslist
		));
	}
	public function actionCkdetailUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$model = PurchaseOrderDetail::model()->find('lid=:materialId and dpid=:dpid' , array(':materialId' => $lid,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		//Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('PurchaseOrderDetail');
			$model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				$this->redirect(array('purchaseOrder/ckdetailindex' , 'companyId' => $this->companyId,'lid'=>$model->purchase_id, ));
			}
		}
		$categories = $this->getGoodsCategories();
		$categoryId=  $this->getCategoryId($lid,1);
		$materials = $this->getGoods($categoryId);
		$materialslist=CHtml::listData($materials, 'lid', 'goods_name');
		$this->render('ckdetailupdate' , array(
				'model' => $model ,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
				'materials'=>$materialslist
		));
	}
	public function actionDetailDelete(){
		$polid = Yii::app()->request->getParam('polid');
		$status = Yii::app()->request->getParam('status');
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
		//Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_purchase_order_detail set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('purchaseOrder/detailindex' , 'companyId' => $companyId,'lid'=>$polid,'status'=>$status, )) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('purchaseOrder/detailindex' , 'companyId' => $companyId,'lid'=>$polid,'status'=>$status, )) ;
		}
	}
	public function actionCkdetailDelete(){
		$polid = Yii::app()->request->getParam('polid');
		$status = Yii::app()->request->getParam('status');
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_purchase_order_detail set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('purchaseOrder/ckdetailindex' , 'companyId' => $companyId,'lid'=>$polid,'status'=>$status, )) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('purchaseOrder/ckdetailindex' , 'companyId' => $companyId,'lid'=>$polid,'status'=>$status, )) ;
		}
	}
	public function actionPurchaseVerify(){
		$pid = Yii::app()->request->getParam('pid');
		$type = Yii::app()->request->getParam('type');
		$purchase = PurchaseOrder::model()->find('lid=:id and dpid=:dpid and delete_flag=0',array(':id'=>$pid,':dpid'=>$this->companyId));
		$purchase->status = $type;
		if($purchase->update()){
			echo 'true';
		}else{
			echo 'false';
		}
		exit;
	}
	public function actionPurchaseVerifyDpid(){
		$pid = Yii::app()->request->getParam('pid');
		$type = Yii::app()->request->getParam('type');
		$purchase = PurchaseOrder::model()->find('lid=:id and dpid=:dpid and delete_flag=0',array(':id'=>$pid,':dpid'=>$this->companyId));
		$purchase->status_dpid = $this->companyId;
		$purchase->status = $type;
		if($purchase->update()){
			echo 'true';
		}else{
			echo 'false';
		}
		exit;
	}
	public function actionStorageOrder(){
		$pid = Yii::app()->request->getParam('pid');
		$purchase = PurchaseOrder::model()->find('lid=:id and dpid=:dpid and delete_flag=0',array(':id'=>$pid,':dpid'=>$this->companyId));
		if($purchase){
			$transaction = Yii::app()->db->beginTransaction();
			try{
				$purchase->status = 2;
				$purchase->save();
				
				$model = new StorageOrder();
				$model->dpid = $this->companyId ;
					
				$se=new Sequence("storage_order");
				$model->lid = $se->nextval();
				$model->create_at = date('Y-m-d H:i:s',time());
				$model->update_at = date('Y-m-d H:i:s',time());
				$model->manufacturer_id = $purchase->manufacturer_id;
				$model->organization_id = $purchase->organization_id;
				$model->admin_id = $purchase->admin_id;
				$model->storage_account_no = date('YmdHis',time()).substr($model->lid,-4);
				$model->purchase_account_no = $purchase->purchase_account_no;
				$model->storage_date = $purchase->delivery_date;
				$model->remark = $purchase->remark;
				$model->status = 0;
				// 入库订单
				$model->save();
					
				$purchaseDetails = PurchaseOrderDetail::model()->findAll('dpid=:dpid and purchase_id=:pid',array(':dpid'=>$this->companyId,':pid'=>$pid));
				
				foreach ($purchaseDetails as $detail){
					$modeldetail = new StorageOrderDetail();
					$modeldetail->dpid = $this->companyId ;
					$se=new Sequence("storage_order_detail");
					$modeldetail->lid = $se->nextval();
					$modeldetail->create_at = date('Y-m-d H:i:s',time());
					$modeldetail->update_at = date('Y-m-d H:i:s',time());
					$modeldetail->storage_id = $model->lid;
					$modeldetail->material_id = $detail->material_id;
					$modeldetail->mphs_code = $detail->mphs_code;
					$modeldetail->price = $detail->price;
					$modeldetail->stock = $detail->stock;
					$modeldetail->stock_day = $detail->stock_day;
					$modeldetail->free_stock = $detail->free_stock;
					$modeldetail->save();
				}
				$transaction->commit();
				echo 'true';
			}catch (Exception $e){
				$transaction->rollback();
				echo 'false';
			}
		}
		exit;
	}
	public function actionGetChildren(){
		$categoryId = Yii::app()->request->getParam('pid',0);
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
	public function actionGetGoodsChildren(){
		$categoryId = Yii::app()->request->getParam('pid',0);
		if(!$categoryId){
			Yii::app()->end(json_encode(array('data'=>array(),'delay'=>400)));
		}
	
		$treeDataSource = array('data'=>array(),'delay'=>400);
		$produts=  $this->getGoods($categoryId);
		
		foreach($produts as $c){
			$tmp['name'] = $c['goods_name'];
			$tmp['id'] = $c['lid'];
			$treeDataSource['data'][] = $tmp;
		}
		Yii::app()->end(json_encode($treeDataSource));
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
		}
		foreach ($options as $k=>$v) {
			$model = MaterialCategory::model()->find('t.lid = :lid and dpid=:dpid',array(':lid'=>$k,':dpid'=>  $this->companyId));
			$optionsReturn[$model->category_name] = $v;
		}
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
	private function getGoodsCategories()
	{
	
		$comps = Yii::app()->db->createCommand('select comp_dpid from nb_company where delete_flag = 0 and dpid ='.$this->companyId)->queryRow();
		if(!empty($comps)){
			$compid = $comps['comp_dpid'];
		}else{
			Yii::app()->user->setFlash('error' , yii::t('app','读取总部信息失败！'));
			$this->redirect(array('goods/index' , 'companyId' => $this->companyId)) ;
		}
	
		$criteria = new CDbCriteria;
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$compid ;
		$criteria->order = ' tree,t.lid asc ';
	
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
		}
		foreach ($options as $k=>$v) {
			$model = MaterialCategory::model()->find('t.lid = :lid and dpid=:dpid',array(':lid'=>$k,':dpid'=> $compid));
			$optionsReturn[$model->category_name] = $v;
		}
		return $optionsReturn;
	}
	private function getGoods($categoryId){
		if($categoryId==0)
		{
			$materials = Goods::model()->findAll('dpid=:companyId and delete_flag=0' , array(':companyId' => $this->companyId));
		}else{
			$materials = Goods::model()->findAll('dpid=:companyId and category_id=:categoryId and delete_flag=0' , array(':companyId' => $this->companyId,':categoryId'=>$categoryId)) ;
		}
		$materials = $materials ? $materials : array();
		return $materials;
	}
	// $type 0 门店 1 仓库
    private function getCategoryId($lid,$type = 0){
        $db = Yii::app()->db;
        if($type){
        	$sql = "SELECT category_id from nb_purchase_order_detail po,nb_goods pm where po.dpid=pm.dpid and po.material_id=pm.lid and po.lid=:lid";
        	$command=$db->createCommand($sql);
        	$command->bindValue(":lid" , $lid);
        }else{
        	$sql = "SELECT category_id from nb_purchase_order_detail po,nb_product_material pm where po.dpid=pm.dpid and po.material_id=pm.lid and po.lid=:lid";
        	$command=$db->createCommand($sql);
        	$command->bindValue(":lid" , $lid);
        }
        return $command->queryScalar();
    }
}