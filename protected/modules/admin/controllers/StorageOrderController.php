<?php
class StorageOrderController extends BackendController
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
				$criteria->addCondition('storage_date >= "'.$begintime.'" ');
			}
			$endtime = Yii::app()->request->getPost('endtime',0);
			if($endtime){
				$criteria->addCondition('storage_date <= "'.$endtime.'" ');
			}
		}
		$criteria->order = ' lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		$pages = new CPagination(StorageOrder::model()->count($criteria));
		$pages->applyLimit($criteria);
		$models = StorageOrder::model()->findAll($criteria);
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
				'mid'=>$mid,
				'begintime'=>$begintime,
				'endtime'=>$endtime,
				'storage'=>$storage,
				'purchase'=>$purchase,
		));
	}
	public function actionCkindex(){
		$criteria = new CDbCriteria;
		$criteria->with = 'company';
		$mid=0;
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
				$criteria->addCondition('storage_date >= "'.$begintime.'" ');
			}
			$endtime = Yii::app()->request->getPost('endtime',0);
			if($endtime){
				$criteria->addCondition('storage_date <= "'.$endtime.'" ');
			}
		}
		$criteria->order = ' lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		$pages = new CPagination(StorageOrder::model()->count($criteria));
		$pages->applyLimit($criteria);
		$models = StorageOrder::model()->findAll($criteria);
		$this->render('ckindex',array(
				'models'=>$models,
				'pages'=>$pages,
				'mid'=>$mid,
				'begintime'=>$begintime,
				'endtime'=>$endtime,
				'storage'=>$storage,
				'purchase'=>$purchase,
		));
	}
	public function actionCreate(){
		$model = new StorageOrder();
		$model->dpid = $this->companyId ;
		$model->storage_date = date('Y-m-d H:i:s',time());
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('StorageOrder');
			$se=new Sequence("storage_order");
			$model->lid = $se->nextval();
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			$model->storage_account_no = date('YmdHis',time()).substr($model->lid,-4);
			$model->status = 0;

			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('storageOrder/detailindex','lid' => $model->lid , 'companyId' => $model->dpid));
			}
		}
		$this->render('create' , array(
			'model' => $model ,
		));
	}
	public function actionCkcreate(){
		$model = new StorageOrder();
		$model->dpid = $this->companyId ;
		$model->storage_date = date('Y-m-d H:i:s',time());
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('StorageOrder');
			$se=new Sequence("storage_order");
			$model->lid = $se->nextval();
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			$model->storage_account_no = date('YmdHis',time()).substr($model->lid,-4);
			$model->status = 0;
	
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('storageOrder/ckdetailindex','lid' => $model->lid , 'companyId' => $model->dpid));
			}
		}
		$this->render('ckcreate' , array(
				'model' => $model ,
		));
	}
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$model = StorageOrder::model()->find('lid=:storageId and dpid=:dpid' , array(':storageId' => $lid,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('StorageOrder');
			$model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				$this->redirect(array('storageOrder/index' , 'companyId' => $this->companyId ));
			}
		}

		$this->render('update' , array(
				'model' => $model ,
		));
	}
	public function actionCkupdate(){
		$lid = Yii::app()->request->getParam('lid');
		$model = StorageOrder::model()->find('lid=:storageId and dpid=:dpid' , array(':storageId' => $lid,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('StorageOrder');
			$model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				$this->redirect(array('storageOrder/ckindex' , 'companyId' => $this->companyId ));
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
			Yii::app()->db->createCommand('update nb_storage_order set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('storageOrder/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('storageOrder/index' , 'companyId' => $companyId)) ;
		}
	}
	public function actionDetailIndex(){
		$criteria = new CDbCriteria;
		$slid = Yii::app()->request->getParam('lid');   //此处lid为StorageOrder表的lid
		$status = Yii::app()->request->getParam('status');
		$storage = StorageOrder::model()->find('lid=:id and dpid=:dpid',array(':id'=>$slid,':dpid'=>$this->companyId));
		$criteria->condition =  't.delete_flag = 0 and t.dpid='.$this->companyId .' and t.storage_id='.$slid;
        $pages = new CPagination(StorageOrderDetail::model()->count($criteria));
		
        $pages->applyLimit($criteria);
		$models = StorageOrderDetail::model()->findAll($criteria);
		$this->render('detailindex',array(
				'storage'=>$storage,
				'models'=>$models,
				'pages'=>$pages,
				'slid'=>$slid,
				'dpid'=>$this->companyId,
				'status'=>$status,
		));
	}
	public function actionCkdetailIndex(){
		$criteria = new CDbCriteria;
		$slid = Yii::app()->request->getParam('lid');   //此处lid为StorageOrder表的lid
		$status = Yii::app()->request->getParam('status');
		$storage = StorageOrder::model()->find('lid=:id and dpid=:dpid',array(':id'=>$slid,':dpid'=>$this->companyId));
		$criteria->condition =  't.delete_flag = 0 and t.dpid='.$this->companyId .' and t.storage_id='.$slid;
		$pages = new CPagination(StorageOrderDetail::model()->count($criteria));
	
		$pages->applyLimit($criteria);
		$models = StorageOrderDetail::model()->findAll($criteria);
		$this->render('ckdetailindex',array(
				'storage'=>$storage,
				'models'=>$models,
				'pages'=>$pages,
				'slid'=>$slid,
				'dpid'=>$this->companyId,
				'status'=>$status,
		));
	}
	public function actionDetailCreate(){
		$model = new StorageOrderDetail();
		$model->dpid = $this->companyId ;
		$rlid = Yii::app()->request->getParam('lid');//var_dump($polid);exit;
		$model->storage_id=$rlid;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('StorageOrderDetail');
			
			$db = Yii::app()->db;
			$sql = 'select t.* from nb_product_material t where t.delete_flag = 0 and t.lid = '.$model->material_id;
			$command2 = $db->createCommand($sql);
			$stockUnitId = $command2->queryRow();
                        $stockUnitId = $stockUnitId['mphs_code'];
			if($stockUnitId){
				$se=new Sequence("storage_order_detail");
				$model->lid = $se->nextval();
				$model->create_at = date('Y-m-d H:i:s',time());
				$model->update_at = date('Y-m-d H:i:s',time());
				$model->mphs_code = $stockUnitId;
				if($model->save()){
					Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
					$this->redirect(array('StorageOrder/detailindex' , 'companyId' => $this->companyId, 'lid'=>$model->storage_id ));
				}
			}else{
				Yii::app()->user->setFlash('error',yii::t('app','添加失败--'));
				$this->redirect(array('StorageOrder/detailindex' , 'companyId' => $this->companyId, 'lid'=>$model->storage_id ));
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
		$model = new StorageOrderDetail();
// 		var_dump($model);exit;
		$model->dpid = $this->companyId ;
		$rlid = Yii::app()->request->getParam('lid');
		$model->storage_id=$rlid;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('StorageOrderDetail');
				
			$db = Yii::app()->db;
			$sql = 'select t.* from nb_goods t where t.delete_flag = 0 and t.lid = '.$model->material_id;
			$command2 = $db->createCommand($sql);
			$stockUnitId = $command2->queryRow();
			$stockUnitId = $stockUnitId['goods_code'];
			if($stockUnitId){
				$se=new Sequence("storage_order_detail");
				$model->lid = $se->nextval();
				$model->create_at = date('Y-m-d H:i:s',time());
				$model->update_at = date('Y-m-d H:i:s',time());
				$model->mphs_code = $stockUnitId;
				if($model->save()){
					Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
					$this->redirect(array('StorageOrder/ckdetailindex' , 'companyId' => $this->companyId, 'lid'=>$model->storage_id ));
				}
			}else{
				Yii::app()->user->setFlash('error',yii::t('app','添加失败--'));
				$this->redirect(array('StorageOrder/ckdetailindex' , 'companyId' => $this->companyId, 'lid'=>$model->storage_id ));
			}
		}
		$categories = $this->getGoodsCategories();
		$categoryId=0;
		$materials = $this->getGoods($categoryId);
		$this->render('ckdetailcreate' , array(
				'model' => $model ,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
				'materials'=>$materials
		));
	}
	   
    public function actionBatchSave(){
        $is_sync = DataSync::getInitSync();
		
		$matids = Yii::app()->request->getParam('matids');
		$storage_id = Yii::app()->request->getParam('lid');     // storage_id
		$dpid = Yii::app()->request->getParam('companyId'); // dpid
		$materialnums = array();
		$materialnums = explode(';',$matids);
		
		$db = Yii::app()->db;
		//var_dump($dpids,$phscodes);exit;
		$transaction = $db->beginTransaction();
		try{
			//var_dump($materialnums);exit;
			foreach ($materialnums as $materialnum){
				$materials = array();
				$materials = explode(',',$materialnum);
				$mateid = $materials[0];   // material_id
				$matenum = $materials[1];  // stock
				$price = $materials[2];  // price
				$prodmaterials = ProductMaterial::model()->find('lid=:lid and dpid=:companyId and delete_flag=0' , array(':lid'=>$mateid,':companyId'=>$this->companyId));
				
				if(!empty($prodmaterials)&&!empty($mateid)){
					$se = new Sequence("storage_order_detail");
					$id = $se->nextval();
					//Yii::app()->end(json_encode(array('status'=>true,'msg'=>'成功','matids'=>$prodmaterials['material_name'],'prodid'=>$matenum,'tasteid'=>$tasteid)));
					$dataprodbom = array(
							'lid'=>$id,
							'dpid'=>$dpid,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
                            'storage_id'=>$storage_id,
							'material_id'=>$mateid,
							'stock'=>$matenum,
							'price'=>$price,
                            'mphs_code'=>$prodmaterials['mphs_code'],
                            'delete_flag'=>'0',
							'is_sync'=>$is_sync,
					);
                                
					$command = $db->createCommand()->insert('nb_storage_order_detail',$dataprodbom);	
				}
				
			}
			$transaction->commit(); //提交事务会真正的执行数据库操作
			Yii::app()->end(json_encode(array('status'=>true)));
			
		} catch (Exception $e) {
				$transaction->rollback(); //如果操作失败, 数据回滚
				Yii::app()->end(json_encode(array('status'=>false)));
		}  
    }
   /*
    * 入库单批量添加
    */
    public function actionBatchCreate(){
            	$this->layout = '/layouts/main_picture';
		$pid = Yii::app()->request->getParam('pid',0);
		$phscode = Yii::app()->request->getParam('phscode',0);
		$prodname = Yii::app()->request->getParam('prodname',0);
		
		$criteria = new CDbCriteria;
		$criteria->condition =  't.pid != 0 and t.delete_flag=0 and t.dpid='.$this->companyId ;
		$criteria->order = ' t.lid asc ';
		$models = MaterialCategory::model()->findAll($criteria);
		//查询原料分类
		
		$criteria = new CDbCriteria;
            
		$criteria->condition =  ' t.delete_flag=0 and t.dpid='.$this->companyId ;
		$criteria->order = ' t.lid asc ';
		$materials = ProductMaterial::model()->findAll($criteria);
               
		//查询原料信息

		
		$this->render('batchcreate' , array(
				'models' => $models,
				'prodname' => $prodname,
				'pid' => $pid,
				'phscode' => $phscode,
				'materials' => $materials,
		));
            
    }
	// 仓库批量添加商品
    public function actionCkbatchCreate(){
    	$this->layout = '/layouts/main_picture';
    	$pid = Yii::app()->request->getParam('pid',0);
    	$phscode = Yii::app()->request->getParam('phscode',0);
    	$prodname = Yii::app()->request->getParam('prodname',0);
    	
    	$compid = WxCompany::getCompanyDpid($this->companyId);
    	
    	$sql = 'select * from nb_material_category where pid != 0 and delete_flag=0 and dpid='.$compid.' order by lid asc';
    	$models = Yii::app()->db->createCommand($sql)->queryAll();
    	//查询原料分类
    
    	$sql = 'select g.*,pm.stock_unit_id from nb_goods g left join nb_goods_material gm on g.lid=gm.goods_id and g.dpid=gm.dpid left join nb_product_material pm on gm.material_code=pm.mphs_code  where pm.dpid='.$compid.' and g.dpid='.$this->companyId.'  and g.delete_flag=0 order by lid asc';
    	$materials = Yii::app()->db->createCommand($sql)->queryAll();
    	//查询原料信息
    
    
    	$this->render('ckbatchcreate' , array(
    			'models' => $models,
    			'prodname' => $prodname,
    			'pid' => $pid,
    			'phscode' => $phscode,
    			'materials' => $materials,
    	));
    
    }
    public function actionCkbatchSave(){
    	$is_sync = DataSync::getInitSync();
    
    	$matids = Yii::app()->request->getParam('matids');
    	$storage_id = Yii::app()->request->getParam('lid');     // storage_id
    	$dpid = Yii::app()->request->getParam('companyId'); // dpid
    	$materialnums = array();
    	$materialnums = explode(';',$matids);
    
    	$db = Yii::app()->db;
    	$transaction = $db->beginTransaction();
    	try{
    		foreach ($materialnums as $materialnum){
    			$materials = array();
    			$materials = explode(',',$materialnum);
    			$mateid = $materials[0];   // material_id
    			$matenum = $materials[1];  // stock
    			$price = $materials[2];  // price
    			
    			$sql = 'select * from nb_goods where lid='.$mateid.' and dpid='.$this->companyId.' and delete_flag=0';;
    			$prodmaterials = $db->createCommand($sql)->queryRow();
    			if(!empty($prodmaterials)&&!empty($mateid)){
    				$se = new Sequence("storage_order_detail");
    				$id = $se->nextval();
    				//Yii::app()->end(json_encode(array('status'=>true,'msg'=>'成功','matids'=>$prodmaterials['material_name'],'prodid'=>$matenum,'tasteid'=>$tasteid)));
    				$dataprodbom = array(
    						'lid'=>$id,
    						'dpid'=>$dpid,
    						'create_at'=>date('Y-m-d H:i:s',time()),
    						'update_at'=>date('Y-m-d H:i:s',time()),
    						'storage_id'=>$storage_id,
    						'material_id'=>$mateid,
    						'stock'=>$matenum,
    						'price'=>$price,
    						'mphs_code'=>$prodmaterials['goods_code'],
    						'delete_flag'=>'0',
    						'is_sync'=>$is_sync,
    				);
    
    				$command = $db->createCommand()->insert('nb_storage_order_detail',$dataprodbom);
    			}
    
    		}
    		$transaction->commit(); //提交事务会真正的执行数据库操作
    		Yii::app()->end(json_encode(array('status'=>true)));
    			
    	} catch (Exception $e) {
    		$transaction->rollback(); //如果操作失败, 数据回滚
    		Yii::app()->end(json_encode(array('status'=>false)));
    	}
    }	
	public function actionDetailUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$model = StorageOrderDetail::model()->find('lid=:storagedetailId and dpid=:dpid' , array(':storagedetailId' => $lid,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('StorageOrderDetail');
			$model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				$this->redirect(array('storageOrder/detailindex' , 'companyId' => $this->companyId, 'lid'=>$model->storage_id));
			}
		}
		$categories = $this->getCategories();
		$categoryId=  $this->getCategoryId($lid);
		$materials = $this->getMaterials($categoryId);
		$materialslist = CHtml::listData($materials, 'lid', 'material_name');
		$this->render('detailupdate' , array(
				'model' => $model ,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
				'materials'=>$materialslist
		));
	}
	public function actionCkdetailUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$model = StorageOrderDetail::model()->find('lid=:storagedetailId and dpid=:dpid' , array(':storagedetailId' => $lid,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('StorageOrderDetail');
			$model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				$this->redirect(array('storageOrder/ckdetailindex' , 'companyId' => $this->companyId, 'lid'=>$model->storage_id));
			}
		}
		$categories = $this->getGoodsCategories();
		$categoryId=  $this->getCategoryId($lid,1);
		$materials = $this->getGoods($categoryId);
		$this->render('ckdetailupdate' , array(
				'model' => $model ,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
				'materials'=>$materials
		));
	}
	public function actionDetailDelete(){
		$slid = Yii::app()->request->getParam('slid');
		$status = Yii::app()->request->getParam('status');
		
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
		
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_storage_order_detail set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('storageOrder/detailindex' , 'companyId' => $companyId,'lid'=>$slid,'status'=>$status, )) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('storageOrder/detailindex' , 'companyId' => $companyId,'lid'=>$slid,'status'=>$status, )) ;
		}
	}
	public function actionCkdetailDelete(){
		$slid = Yii::app()->request->getParam('slid');
		$status = Yii::app()->request->getParam('status');
	
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
	
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_storage_order_detail set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('storageOrder/ckdetailindex' , 'companyId' => $companyId,'lid'=>$slid,'status'=>$status, )) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('storageOrder/ckdetailindex' , 'companyId' => $companyId,'lid'=>$slid,'status'=>$status, )) ;
		}
	}
	public function actionStorageIn(){
		$sid = Yii::app()->request->getParam('sid');
		$sql = 'select * from nb_storage_order where lid='.$sid.' and dpid='.$this->companyId.' and delete_flag=0';
		$storage = Yii::app()->db->createCommand($sql)->queryRow();
		if($storage['status']){
			$sql = 'select * from nb_storage_order_detail where storage_id='.$sid.' and dpid='.$this->companyId.' and delete_flag=0';
			$storageDetails = Yii::app()->db->createCommand($sql)->queryAll();
			$transaction = Yii::app()->db->beginTransaction();
			try{
				
				foreach ($storageDetails as $detail){
					$sql = 'select * from nb_product_material where lid='.$detail['material_id'].' and dpid='.$this->companyId.' and delete_flag=0';
					$prodmaterial = Yii::app()->db->createCommand($sql)->queryRow();
					
					$sql = 'select * from nb_material_unit_ratio where stock_unit_id='.$prodmaterial['stock_unit_id'].' and sales_unit_id='.$prodmaterial['sales_unit_id'].' and dpid='.$this->companyId.' and delete_flag=0';
					$unitratio = Yii::app()->db->createCommand($sql)->queryRow();
					if(!empty($unitratio)){
						$sql = 'update nb_product_material_stock set stock=0 where stock<0 and delete_flag=0 and material_id='.$detail['material_id'].' and dpid='.$this->companyId;
						Yii::app()->db->createCommand($sql)->execute();
						
						$num = $detail['stock'] * $unitratio['unit_ratio'];
						$pms = new Sequence("product_material_stock");
						$lid = $pms->nextval(); 
						$gmdata = array(
								'lid'=>$lid,
								'dpid'=>$this->companyId,
								'create_at'=>date('Y-m-d H:i:s',time()),
								'update_at'=>date('Y-m-d H:i:s',time()),
								'material_id'=>$detail['material_id'],
								'mphs_code'=>$detail['mphs_code'],
								'stock_day'=>$detail['stock_day'],
								'batch_stock'=>$num,
								'stock'=>$num,
								'free_stock'=>$detail['free_stock'],
								'stock_cost'=>$detail['price'],
						);
						Yii::app()->db->createCommand()->insert('nb_product_material_stock',$gmdata);
						//入库日志
						$se=new Sequence("material_stock_log");
						$lid = $se->nextval();
						$gmlogdata = array(
								'lid'=>$lid,
								'dpid'=>$this->companyId,
								'create_at'=>date('Y-m-d H:i:s',time()),
								'update_at'=>date('Y-m-d H:i:s',time()),
								'material_id'=>$detail['material_id'],
								'type'=>0,
								'stock_num'=>$num,
								'resean'=>'入库单入库',
						);
						
						Yii::app()->db->createCommand()->insert('nb_material_stock_log',$gmlogdata);
					}
					//如果没有入库单位和零售单位比的话，要提示没有入库成功。。。
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
	public function actionCkstorageIn(){
		$sid = Yii::app()->request->getParam('sid');
		$sql = 'select * from nb_storage_order where lid='.$sid.' and dpid='.$this->companyId.' and delete_flag=0';
		$storage = Yii::app()->db->createCommand($sql)->queryRow();
		
		if($storage){
			$sql = 'select * from nb_storage_order_detail where storage_id='.$sid.' and dpid='.$this->companyId.' and delete_flag=0';
			$storageDetails = Yii::app()->db->createCommand($sql)->queryAll();
			$transaction = Yii::app()->db->beginTransaction();
			try{
				$sql = 'update nb_storage_order set status=3 where lid='.$sid.' and dpid='.$this->companyId.' and delete_flag=0 ';
				Yii::app()->db->createCommand($sql)->execute();
				foreach ($storageDetails as $detail){
					$sql = 'select * from nb_goods where lid='.$detail['material_id'].' and dpid='.$this->companyId.' and delete_flag=0';
					$prodmaterial = Yii::app()->db->createCommand($sql)->queryRow();
					
					if(!empty($prodmaterial)){
						$sql = 'update nb_goods_material_stock set stock=0 where stock<0 and goods_id='.$detail['material_id'].' and dpid='.$this->companyId.' and delete_flag=0 ';
						$result = Yii::app()->db->createCommand($sql)->execute();
						$num = $detail['stock'];
						$pms = new Sequence("goods_material_stock");
						$lid = $pms->nextval();
						
						$gmdata = array(
								'lid'=>$lid,
								'dpid'=>$this->companyId,
								'create_at'=>date('Y-m-d H:i:s',time()),
								'update_at'=>date('Y-m-d H:i:s',time()),
								'goods_id'=>$detail['material_id'],
								'goods_code'=>$detail['mphs_code'],
								'stock_day'=>$detail['stock_day'],
								'batch_code'=>$detail['batch_code']?$detail['batch_code']:0,
								'batch_stock'=>$num,
								'stock'=>$num,
								'free_stock'=>$detail['free_stock'],
								'stock_cost'=>$detail['price'],
						);
						Yii::app()->db->createCommand()->insert('nb_goods_material_stock',$gmdata);
						
						//入库日志
						$se=new Sequence("material_stock_log");
						$lid = $se->nextval();
						$gmlogdata = array(
								'lid'=>$lid,
								'dpid'=>$this->companyId,
								'create_at'=>date('Y-m-d H:i:s',time()),
								'update_at'=>date('Y-m-d H:i:s',time()),
								'material_id'=>$detail['material_id'],
								'type'=>0,
								'stock_num'=>$num,
								'resean'=>'商品入库单入库',
						);
						Yii::app()->db->createCommand()->insert('nb_material_stock_log',$gmlogdata);
					}
					//如果没有入库单位和零售单位比的话，要提示没有入库成功。。。
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
		$compid = WxCompany::getCompanyDpid($this->companyId);
		
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
			$sql = 'select * from nb_goods where dpid='.$this->companyId.' and delete_flag=0';
		}else{
			$sql = 'select * from nb_goods where dpid='.$this->companyId.' and category_id='.$categoryId.' and delete_flag=0';
		}
		$materials = Yii::app()->db->createCommand($sql)->queryAll();
		return $materials;
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
			$tmp['id'] = $c['lid'];
			$tmp['name'] = $c['goods_name'];
			$tmp['is_batch'] = $c['is_batch'];
			$treeDataSource['data'][] = $tmp;
		}
		Yii::app()->end(json_encode($treeDataSource));
	}
	// $type 0 门店 1 仓库
	private function getCategoryId($lid,$type = 0){
		$db = Yii::app()->db;
		if($type){
			$sql = "SELECT category_id from nb_storage_order_detail po,nb_goods pm where po.dpid=pm.dpid and po.material_id=pm.lid and po.lid=:lid";
			$command=$db->createCommand($sql);
			$command->bindValue(":lid" , $lid);
		}else{
			$sql = "SELECT category_id from nb_storage_order_detail po,nb_product_material pm where po.dpid=pm.dpid and po.material_id=pm.lid and po.lid=:lid";
			$command=$db->createCommand($sql);
			$command->bindValue(":lid" , $lid);
		}
		return $command->queryScalar();
	}
	public function actionStorageVerify(){
		$pid = Yii::app()->request->getParam('pid');
		$type = Yii::app()->request->getParam('type');
		$storage = StorageOrder::model()->find('lid=:id and dpid=:dpid and delete_flag=0',array(':id'=>$pid,':dpid'=>$this->companyId));
		$storage->status = $type;
		//var_dump($storage);
		if($storage->update()){
			echo 'true';
		}else{
			echo 'false';
		}
		exit;
	}
	
	
	
	
}