<?php
class RefundOrderController extends BackendController
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
		$categoryId = Yii::app()->request->getParam('cid',0);
		$mid=0;
		$oid=0;
		$begintime=0;
		$endtime=0;
		$storage=0;
		$refund=0;
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
			$oid = Yii::app()->request->getPost('oid',0);
			if($oid){
				$criteria->addSearchCondition('organization_id',$oid);
			}
			$storage = Yii::app()->request->getPost('storage',0);
			if($storage){
				//echo($oid);
				//$ogname = Company::model()->find('company_name like "%'.$oid.'%" and delete_flag = 0');
				//var_dump($ogname);exit;
				$criteria->addSearchCondition('storage_account_no',$storage);
			}
			$refund = Yii::app()->request->getPost('refund',0);
			if($refund){
				//echo($oid);
				//$ogname = Company::model()->find('company_name like "%'.$oid.'%" and delete_flag = 0');
				//var_dump($ogname);exit;
				$criteria->addSearchCondition('refund_account_no',$refund);
			}
			$begintime = Yii::app()->request->getPost('begintime',0);
			if($begintime){
				$criteria->addCondition('refund_date >= "'.$begintime.'" ');
			}
			$endtime = Yii::app()->request->getPost('endtime',0);
			if($endtime){
				$criteria->addCondition('refund_date <= "'.$endtime.'" ');
			}
		}
		$criteria->order = ' lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		$pages = new CPagination(RefundOrder::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = RefundOrder::model()->findAll($criteria);
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
				'categoryId'=>$categoryId,
				'mid'=>$mid,
				'oid'=>$oid,
				'begintime'=>$begintime,
				'endtime'=>$endtime,
				'storage'=>$storage,
				'refund'=>$refund,
		));
	}
	public function actionSetMealList() {
		
	}
	public function actionCreate(){
		$model = new RefundOrder();
		$model->dpid = $this->companyId ;
		
		if(Yii::app()->request->isPostRequest) {
			$transaction=Yii::app()->db->beginTransaction();
			try{
				$model->attributes = Yii::app()->request->getPost('RefundOrder');
				$se=new Sequence("refund_order");
				$model->lid = $se->nextval();
				$model->create_at = date('Y-m-d H:i:s',time());
				$model->update_at = date('Y-m-d H:i:s',time());
				$model->refund_account_no = date('YmdHis',time()).substr($model->lid,-4);
				$model->save();
				if($model->storage_account_no!=0){
					$storage = StorageOrder::model()->find('storage_account_no=:storageNo and delete_flag=0',array(':storageNo'=>$model->storage_account_no));
					$storageDetails = StorageOrderDetail::model()->findAll('storage_id=:storageId and dpid=:dpid and delete_flag=0',array(':storageId'=>$storage['lid'],':dpid'=>$storage['dpid']));
					foreach ($storageDetails as $detail){
						$refundDetail = new RefundOrderDetail();
						$se=new Sequence("refund_order_detail");
						$refundDetail->lid = $se->nextval();
						$refundDetail->dpid = $this->companyId ;
						$refundDetail->create_at = date('Y-m-d H:i:s',time());
						$refundDetail->update_at = date('Y-m-d H:i:s',time());
						$refundDetail->refund_id = $model->lid;
						$refundDetail->material_id = $detail['material_id'];
						$refundDetail->price = $detail['price'];
						$refundDetail->stock = $detail['stock'];
						$refundDetail->reason = '入库单退货';
						$refundDetail->save();
					}
				}
				$transaction->commit();
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('refundOrder/index' , 'companyId' => $this->companyId ));
			}catch (Exception $e){
				$transaction->rollback();
				Yii::app()->user->setFlash('error',yii::t('app','添加失败！'));
				$this->redirect(array('refundOrder/index' , 'companyId' => $this->companyId ));
			}
		}
		$categories = $categories = RefundOrder::model()->findAll('delete_flag=0 and dpid=:companyId' , array(':companyId' => $this->companyId)) ;
		$this->render('create' , array(
			'model' => $model ,
			'categories' => $categories
		));
	}
	
	public function actionUpdate(){
		$id = Yii::app()->request->getParam('lid');
		$model = RefundOrder::model()->find('lid=:refundId and dpid=:dpid' , array(':refundId' => $id,':dpid'=>  $this->companyId));
		$storageNo = $model->storage_account_no;
		//Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$transaction=Yii::app()->db->beginTransaction();
			try{
				$model->attributes = Yii::app()->request->getPost('RefundOrder');
				$model->update_at=date('Y-m-d H:i:s',time());
				$model->save();
				if($model->storage_account_no!=$storageNo){
					StorageOrderDetail::model()->update(array('delete_flag'=>1),'refund_id=:refundId and dpid=:dpid',array(':refundId'=>$model->lid,':dpid'=>$model->dpid));
					$storage = StorageOrder::model()->find('storage_account_no=:storageNo and delete_flag=0',array(':storageNo'=>$model->storage_account_no));
					$storageDetails = StorageOrderDetail::model()->findAll('storage_id=:storageId and dpid=:dpid and delete_flag=0',array(':storageId'=>$storage['lid'],':dpid'=>$storage['dpid']));
					foreach ($storageDetails as $detail){
						$refundDetail = new RefundOrderDetail();
						$se=new Sequence("refund_order_detail");
						$refundDetail->lid = $se->nextval();
						$refundDetail->dpid = $this->companyId ;
						$refundDetail->create_at = date('Y-m-d H:i:s',time());
						$refundDetail->update_at = date('Y-m-d H:i:s',time());
						$refundDetail->refund_id = $model->lid;
						$refundDetail->material_id = $detail['material_id'];
						$refundDetail->price = $detail['price'];
						$refundDetail->stock = $detail['stock'];
						$refundDetail->reason = '入库单退货';
						$refundDetail->save();
					}
				}
				$transaction->commit();
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('refundOrder/index' , 'companyId' => $this->companyId ));
			}catch (Exception $e){
				$transaction->rollback();
				Yii::app()->user->setFlash('error',yii::t('app','添加失败！'));
				$this->redirect(array('refundOrder/index' , 'companyId' => $this->companyId ));
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
			Yii::app()->db->createCommand('update nb_refund_order set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('refundOrder/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('refundOrder/index' , 'companyId' => $companyId)) ;
		}
	}
	public function actionDetailIndex(){
		$criteria = new CDbCriteria;
		$rlid = Yii::app()->request->getParam('lid');
		$status = Yii::app()->request->getParam('status');
		$refund = RefundOrder::model()->find('lid=:lid and dpid=:dpid and delete_flag=0',array(':lid'=>$rlid,':dpid'=>$this->companyId));
        $criteria->condition =  't.delete_flag = 0 and t.dpid='.$this->companyId .' and t.refund_id='.$rlid;
		$pages = new CPagination(RefundOrderDetail::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = RefundOrderDetail::model()->findAll($criteria);
		$this->render('detailindex',array(
				'models'=>$models,
				'refund'=>$refund,
				'pages'=>$pages,
				'rlid'=>$rlid,
				'status'=>$status,
		));
	}
	public function actionDetailCreate(){
		$model = new RefundOrderDetail();
		$model->dpid = $this->companyId ;
		$rlid = Yii::app()->request->getParam('lid');//var_dump($rlid);exit;
		$model->refund_id=$rlid;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('RefundOrderDetail');
			$se=new Sequence("refund_order_detail");
			$model->lid = $se->nextval();
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('refundOrder/detailindex' , 'companyId' => $this->companyId, 'lid'=>$model->refund_id));
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

	public function actionDetailUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$model = RefundOrderDetail::model()->find('lid=:refunddetailId and dpid=:dpid' , array(':refunddetailId' => $lid,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		//Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('RefundOrderDetail');
			$model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				$this->redirect(array('refundOrder/detailindex' , 'companyId' => $this->companyId, 'lid'=>$model->refund_id ));
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
	public function actionDetailDelete(){
		$rlid = Yii::app()->request->getParam('rlid');
		$status = Yii::app()->request->getParam('status');
	
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
	
		//Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_refund_order_detail set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('refundOrder/detailindex' , 'companyId' => $companyId,'lid'=>$rlid,'status'=>$status, )) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('refundOrder/detailindex' , 'companyId' => $companyId,'lid'=>$rlid,'status'=>$status, )) ;
		}
	}
	/**
	 * 
	 * 获取已入库入库订单
	 */
	public function actionGetStorageOrder(){
		$dpid = Yii::app()->request->getParam('dpid');
		$storageOrder = Common::getStorageOrder($dpid);
		Yii::app()->end(json_encode($storageOrder));
	}
	/**
	 * 
	 * 审核退货订单
	 * 
	 */
	public function actionRefundVerify(){
		$pid = Yii::app()->request->getParam('pid');
		$type = Yii::app()->request->getParam('type');
		$refund = RefundOrder::model()->find('lid=:id and dpid=:dpid and delete_flag=0',array(':id'=>$pid,':dpid'=>$this->companyId));
		$refund->status = $type;
		if($refund->update()){
			echo 'true';
		}else{
			echo 'false';
		}
		exit;
	}
	/**
	 * 
	 *确认退货 
	 * 
	 *
	 */
	public function actionRefundOrder(){
		$pid = Yii::app()->request->getParam('pid');
		
		$refund = RefundOrder::model()->find('lid=:id and dpid=:dpid and delete_flag=0',array(':id'=>$pid,':dpid'=>$this->companyId));
		if($refund){
			$refundDetails = RefundOrderDetail::model()->findAll('dpid=:dpid and refund_id=:refundId and delete_flag=0',array(':dpid'=>$this->companyId,':refundId'=>$refund->lid));
			$organizeId = $refund->organization_id;

// 			$refund->status = 4;
// 			if($refund->update()){
// 				return;
// 			}else{
// 				echo 'false';
// 				exit;
// 			}
			$transaction=Yii::app()->db->beginTransaction();
			try{
				foreach ($refundDetails as $detail){
					$sql = 'update nb_product_material_stock set stock = stock - '.$detail['stock'].' where material_id='.$detail['material_id'].' and dpid='.$organizeId.' and delete_flag=0';
					Yii::app()->db->createCommand($sql)->execute();
					
					//出库日志
					$materialStockLog = new MaterialStockLog();
					$se=new Sequence("material_stock_log");
					$materialStockLog->lid = $se->nextval();
					$materialStockLog->dpid = $organizeId;
					$materialStockLog->create_at = date('Y-m-d H:i:s',time());
					$materialStockLog->update_at = date('Y-m-d H:i:s',time());
					$materialStockLog->material_id = $detail['material_id'];
					$materialStockLog->type = 1;
					$materialStockLog->stock_num = $detail['stock'];
					$materialStockLog->resean = '退货出库';
					$materialStockLog->save();
				}
				$transaction->commit();
				//var_dump($pid);exit;
				$refund->status = 4;
				$refund->update();
				echo 'true';
			}catch(Exception $e){
				$transaction->rollback();
				$refund->status = 2;
				$refund->update();
				echo 'false';
				
				exit;
			}
			
		}else{
			echo 'false';
		}
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
	private function getMaterials($categoryId){
		if($categoryId==0)
		{
			//var_dump ('2',$categoryId);exit;
			$materials = ProductMaterial::model()->findAll('dpid=:companyId and delete_flag=0' , array(':companyId' => $this->companyId));
		}else{
			//var_dump ('3',$categoryId);exit;
			$materials = ProductMaterial::model()->findAll('dpid=:companyId and category_id=:categoryId and delete_flag=0' , array(':companyId' => $this->companyId,':categoryId'=>$categoryId)) ;
		}
		$materials = $materials ? $materials : array();
		//var_dump($products);exit;
		return $materials;
		//return CHtml::listData($products, 'lid', 'product_name');
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
        $sql = "SELECT category_id from nb_refund_order_detail ro,nb_product_material pm where ro.dpid=pm.dpid and ro.material_id=pm.lid and ro.lid=:lid";
        $command=$db->createCommand($sql);
        $command->bindValue(":lid" , $lid);
        return $command->queryScalar();
    }
}