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
				//var_dump($maname);exit;
				if($manames){
					$malides = '';
					foreach ($manames as $maname){
						$malid = $maname->lid;
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
			$purchase = Yii::app()->request->getPost('purchase',0);
			if($purchase){
				//echo($oid);
				//$ogname = Company::model()->find('company_name like "%'.$oid.'%" and delete_flag = 0');
				//var_dump($ogname);exit;
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
		//	    $pages->setPageSize(1);
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
	public function actionSetMealList() {
		
	}
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
		$categories = PurchaseOrder::model()->findAll('delete_flag=0 and dpid=:companyId' , array(':companyId' => $this->companyId)) ;
		//var_dump($categories);exit;
		$this->render('create' , array(
			'model' => $model ,
			'categories' => $categories
		));
	}
	
	public function actionUpdate(){
		$id = Yii::app()->request->getParam('lid');
		$model = PurchaseOrder::model()->find('lid=:materialId and dpid=:dpid' , array(':materialId' => $id,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
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
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
                Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_purchase_order set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('purchaseOrder/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('purchaseOrder/index' , 'companyId' => $companyId)) ;
		}
	}
	public function actionDetailIndex(){
		$polid = Yii::app()->request->getParam('lid');
		$status = Yii::app()->request->getParam('status');
		$purchase = PurchaseOrder::model()->find('lid=:id and dpid=:dpid and delete_flag=0',array(':id'=>$polid,':dpid'=>$this->companyId));
		$criteria = new CDbCriteria;
        $criteria->condition =  't.dpid='.$this->companyId .' and t.purchase_id='.$polid;
		$pages = new CPagination(PurchaseOrderDetail::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = PurchaseOrderDetail::model()->findAll($criteria);
		//var_dump($categoryId);exit;
		$this->render('detailindex',array(
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
			$se=new Sequence("purchase_order_detail");
			$model->lid = $se->nextval();
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			//  $model->delete_flag = '0';
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('purchaseOrder/detailindex' , 'companyId' => $this->companyId,'lid'=>$model->purchase_id, ));
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
		$model = PurchaseOrderDetail::model()->find('lid=:materialId and dpid=:dpid' , array(':materialId' => $lid,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
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
	public function actionStorageOrder(){
		$pid = Yii::app()->request->getParam('pid');
		$purchase = PurchaseOrder::model()->find('lid=:id and dpid=:dpid and delete_flag=0',array(':id'=>$pid,':dpid'=>$this->companyId));
		if($purchase){
			$transaction = Yii::app()->db->beginTransaction();
			try{
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
				$model->status = 1;
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
					$modeldetail->price = $detail->price;
					$modeldetail->stock = $detail->stock;
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
        $sql = "SELECT category_id from nb_purchase_order_detail po,nb_product_material pm where po.dpid=pm.dpid and po.material_id=pm.lid and po.lid=:lid";
        $command=$db->createCommand($sql);
        $command->bindValue(":lid" , $lid);
        return $command->queryScalar();
    }
}