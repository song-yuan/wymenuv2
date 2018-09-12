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
				if($maname){
					$malides = '';
					foreach ($maname as $manames){
						$malid = $manames->lid;
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
			$refund = Yii::app()->request->getPost('refund',0);
			if($refund){
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
	public function actionCreate(){
		$model = new RefundOrder();
		$model->dpid = $this->companyId ;
		$model->refund_date = date('Y-m-d H:i:s',time());
		if(Yii::app()->request->isPostRequest) {
			$transaction=Yii::app()->db->beginTransaction();
			try{
				$model->attributes = Yii::app()->request->getPost('RefundOrder');
				$se = new Sequence("refund_order");
				$model->lid = $se->nextval();
				$model->create_at = date('Y-m-d H:i:s',time());
				$model->update_at = date('Y-m-d H:i:s',time());
				$model->refund_account_no = date('YmdHis',time()).substr($model->lid,-4);
				$model->save();
				// 按采购单退单
				if($model->storage_account_no!=0){
					$sql = 'select t.*,t1.storage_account_no from nb_storage_order_detail t,nb_storage_order t1 where t.storage_id=t1.lid and t.dpid=t1.dpid and t.dpid='.$this->companyId.' and t1.storage_account_no="'.$model->storage_account_no.'" and t.delete_flag=0 and t1.delete_flag=0';
					$storageDetails = Yii::app()->db->createCommand($sql)->queryAll(); 
					foreach ($storageDetails as $detail){
						// 查询已退单的数量
						$leaveStock = $detail['stock'] - $detail['refund_stock'];
						if($leaveStock > 0){
							$price = 0;
							if($detail['stock']!=0){
								$price = $leaveStock*$detail['price']/$detail['stock'];
							}
							$sql = 'update nb_storage_order_detail set refund_stock=efund_stock+'.$leaveStock.',refund_status=1 where lid='.$detail['lid'].' and dpid='.$detail['dpid'];
							Yii::app()->db->createCommand($sql)->execute();
							
							$se = new Sequence("refund_order_detail");
							$lid = $se->nextval();
							$refundDetailData = array(
									'lid'=>$lid,
									'dpid'=>$this->companyId,
									'create_at'=>date('Y-m-d H:i:s',time()),
									'update_at'=>date('Y-m-d H:i:s',time()),
									'refund_id'=>$model->lid,
									'material_id'=>$detail['material_id'],
									'price'=>$price,
									'stock'=>$leaveStock,
									'reason'=>'入库单退货',
							);
							Yii::app()->db->createCommand()->insert('nb_refund_order_detail',$refundDetailData);
						}
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
		if(Yii::app()->request->isPostRequest) {
			$transaction=Yii::app()->db->beginTransaction();
			try{
				$model->attributes = Yii::app()->request->getPost('RefundOrder');
				$model->update_at=date('Y-m-d H:i:s',time());
				$model->save();
				if($model->storage_account_no!=$storageNo){
					$sql = 'update from nb_refund_order_detail set delete_flag=1 where dpid='.$model->dpid.' and refund_id='.$model->lid;
					Yii::app()->db->createCommand($sql)->execute();
					
					$sql = 'select t.*,t1.storage_account_no from nb_storage_order_detail t,nb_storage_order t1 where t.storage_id=t1.lid and t.dpid=t1.dpid and t.dpid='.$this->companyId.' and t1.storage_account_no="'.$model->storage_account_no.'" and t.delete_flag=0 and t1.delete_flag=0';
					$storageDetails = Yii::app()->db->createCommand($sql)->queryAll();
					foreach ($storageDetails as $detail){
						// 查询已退单的数量
						$leaveStock = $detail['stock'] -  $detail['refund_stock'];
						if($leaveStock > 0){
							$price = 0;
							if($detail['stock']!=0){
								$price = $leaveStock*$detail['price']/$detail['stock'];
							}
							$sql = 'update nb_storage_order_detail set refund_stock=efund_stock+'.$leaveStock.',refund_status=1 where lid='.$detail['lid'].' and dpid='.$detail['dpid'];
							Yii::app()->db->createCommand($sql)->execute();
							
							$se = new Sequence("refund_order_detail");
							$lid = $se->nextval();
							$refundDetailData = array(
									'lid'=>$lid,
									'dpid'=>$this->companyId,
									'create_at'=>date('Y-m-d H:i:s',time()),
									'update_at'=>date('Y-m-d H:i:s',time()),
									'refund_id'=>$model->lid,
									'material_id'=>$detail['material_id'],
									'price'=>$price,
									'stock'=>$leaveStock,
									'reason'=>'入库单退货',
							);
							Yii::app()->db->createCommand()->insert('nb_refund_order_detail',$refundDetailData);
						}
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
		if(!empty($ids)) {
			$idstr = join(',', $ids);
			$transaction = Yii::app()->db->beginTransaction();
			try{
				$sql = 'select * from nb_refund_order where lid in('.$idstr.') and dpid='.$this->companyId;
				$refundOrders = Yii::app()->db->createCommand($sql)->queryAll();
				foreach ($refundOrders as $refund){
					$sql = 'update nb_refund_order set delete_flag=1 where lid='.$refund['lid'].' and dpid='.$this->companyId;
					Yii::app()->db->createCommand($sql)->execute();
					$sql = 'update nb_refund_order_detail set delete_flag=1 where refund_id in='.$refund['lid'].' and dpid='.$this->companyId;
					Yii::app()->db->createCommand($sql)->execute();
					if(!empty($refund['storage_account_no'])){
						$sql = 'select * from nb_storage_order where dpid='.$this->companyId.' and storage_account_no="'.$refund['storage_account_no'].'" and delete_flag=0';
						$storageOrder = Yii::app()->db->createCommand($sql)->queryRow();
						if(!empty($storageOrder)){
							$sql = 'select * from nb_refund_order_detail where refund_id in='.$refund['lid'].' and dpid='.$this->companyId;
							$refundDetails = Yii::app()->db->createCommand($sql)->queryAll();
							foreach ($refundDetails as $detail){
								$sql = 'update nb_storage_order_detail set refund_stock=0 and refund_status=0 where storage_id='.$storageOrder['lid'].' and dpid='.$this->companyId.' and material_id='.$detail['material_id'].' and delete_flag=0';
								Yii::app()->db->createCommand($sql)->execute();
							}
						}
					}
				}
				$transaction->commit();
				Yii::app()->user->setFlash('success',yii::t('app','删除成功！'));
				$this->redirect(array('refundOrder/index' , 'companyId' => $this->companyId ));
			}catch (Exception $e){
				$transaction->rollback();
				Yii::app()->user->setFlash('error',yii::t('app','删除失败！'));
				$this->redirect(array('refundOrder/index' , 'companyId' => $this->companyId ));
			}
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
		$rlid = Yii::app()->request->getParam('lid');
		$model->refund_id = $rlid;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('RefundOrderDetail');
			$sql = 'select * from nb_refund_order where lid='.$rlid.' and dpid='.$this->companyId;
			$refundOrder = Yii::app()->db->createCommand($sql)->queryRow();
			$hasError = false;
			if(!empty($refundOrder['storage_account_no'])){
				$sql = 'select t.* from nb_storage_order_detail t,nb_storage_order t1 where t.storage_id=t1.lid and t.dpid=t1.dpid and t.dpid='.$this->companyId.' and t.material_id='.$model->material_id.' and t1.storage_account_no="'.$refundOrder['storage_account_no'].'"';
				$detail = Yii::app()->db->createCommand($sql)->queryRow();
				if(!empty($detail)){
					$leaveStock = $detail['stock'] -  $detail['refund_stock'];
					if($model->stock > $leaveStock){
						$hasError = true;
						$model->addError('stock','退单库存不能超过'.$leaveStock);
					}else{
						$sql = 'update nb_storage_order_detail set refund_stock=efund_stock+'.$leaveStock.',refund_status=1 where lid='.$detail['lid'].' and dpid='.$detail['dpid'];
						Yii::app()->db->createCommand($sql)->execute();
						
						$se = new Sequence("refund_order_detail");
						$model->lid = $se->nextval();
						$model->create_at = date('Y-m-d H:i:s',time());
						$model->update_at = date('Y-m-d H:i:s',time());
						if($model->save()){
							Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
							$this->redirect(array('refundOrder/detailindex' , 'companyId' => $this->companyId, 'lid'=>$model->refund_id));
						}
					}
				}
			}
			if(!$hasError){
				$se = new Sequence("refund_order_detail");
				$model->lid = $se->nextval();
				$model->create_at = date('Y-m-d H:i:s',time());
				$model->update_at = date('Y-m-d H:i:s',time());
				if($model->save()){
					Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
					$this->redirect(array('refundOrder/detailindex' , 'companyId' => $this->companyId, 'lid'=>$model->refund_id));
				}
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
		if(Yii::app()->request->isPostRequest) {
			$hasError = false;
			$model->attributes = Yii::app()->request->getPost('RefundOrderDetail');
			$sql = 'select * from nb_refund_order where lid='.$rlid.' and dpid='.$this->companyId;
			$refundOrder = Yii::app()->db->createCommand($sql)->queryRow();
			if(!empty($refundOrder['storage_account_no'])){
				$sql = 'select t.* from nb_storage_order_detail t,nb_storage_order t1 where t.storage_id=t1.lid and t.dpid=t1.dpid and t.dpid='.$this->companyId.' and t.material_id='.$model->material_id.' and t1.storage_account_no="'.$refundOrder['storage_account_no'].'"';
				$detail = Yii::app()->db->createCommand($sql)->queryRow();
				if(!empty($detail)){
					$leaveStock = $detail['stock'] -  $detail['refund_stock'];
					if($model->stock > $leaveStock){
						$hasError = true;
						$model->addError('stock','退单库存不能超过'.$leaveStock);
					}else{
						$sql = 'update nb_storage_order_detail set refund_stock=efund_stock+'.$leaveStock.',refund_status=1 where lid='.$detail['lid'].' and dpid='.$detail['dpid'];
						Yii::app()->db->createCommand($sql)->execute();
				
						$se = new Sequence("refund_order_detail");
						$model->lid = $se->nextval();
						$model->create_at = date('Y-m-d H:i:s',time());
						$model->update_at = date('Y-m-d H:i:s',time());
						if($model->save()){
							Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
							$this->redirect(array('refundOrder/detailindex' , 'companyId' => $this->companyId, 'lid'=>$model->refund_id));
						}
					}
				}
			}
			if(!$hasError){
				$model->update_at=date('Y-m-d H:i:s',time());
				if($model->save()){
					Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
					$this->redirect(array('refundOrder/detailindex' , 'companyId' => $this->companyId, 'lid'=>$model->refund_id ));
				}
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
	
		$companyId = $this->companyId;
		$ids = Yii::app()->request->getPost('ids');
	
		if(!empty($ids)) {
			$idStr = join(',', $ids);
			$sql = 'select t.*,t1.storage_account_no from nb_refund_order_detail t,nb_refund_order t1 where t.refund_id=t1.lid and t.dpid=t1.dpid and lid in('.$idStr.') and dpid='.$this->companyId;
			$refundDetails = Yii::app()->db->createCommand($sql)->queryAll();
			foreach ($refundDetails as $detail){
				if(empty($detail['storage_account_no'])){
					$sql = 'select t.* from nb_storage_order_detail t,nb_storage_order t1 where t.storage_id=t1.lid and t.dpid=t1.dpid and t.dpid='.$this->companyId.' and t1.storage_account_no="'.$detail['storage_account_no'].'" and t.material_id='.$detail['material_id'].' and t.delete_flag=0 and t1.delete_flag=0';
					$storageDetail = Yii::app()->db->createCommand($sql)->queryRow();
					if(!empty($storageDetail)){
						$sql = 'update nb_storage_order_detail set refund_stock=0 and refund_status=0 where lid='.$storageDetail['lid'].' and dpid='.$this->companyId;
						Yii::app()->db->createCommand($sql)->execute();
					}
				}
			}
			
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