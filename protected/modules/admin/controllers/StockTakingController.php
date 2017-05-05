<?php
class StockTakingController extends BackendController
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
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId && $this->getAction()->getId() != 'upload') {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex(){
		$categoryId = Yii::app()->request->getParam('cid',0);
		$sttype = Yii::app()->request->getParam('sttype',1);
		$criteria = new CDbCriteria;
		$criteria->with = array('category');
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId;
		if($categoryId){
			$criteria->condition.=' and t.category_id = '.$categoryId;
		}
		$criteria->order = 't.category_id asc,t.lid asc';
	//	$criteria->condition.=' and t.lid = '.$categoryId;
		//$pages = new CPagination(ProductMaterial::model()->count($criteria));
		//$pages->setPageSize(1);
		//$pages->applyLimit($criteria);
		$models = ProductMaterial::model()->findAll($criteria);
		$categories = $this->getCategories();
		$this->render('index',array(
				'models'=>$models,
				//'pages'=>$pages,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
				'sttype'=>$sttype

		));
	}
	public function actionDamageindex(){
		$categoryId = Yii::app()->request->getParam('cid',0);
		$criteria = new CDbCriteria;
		$criteria->with = array('category');
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId;
		if($categoryId){
			$criteria->condition.=' and t.category_id = '.$categoryId;
		}
		$criteria->order = 't.category_id asc,t.lid asc';
		//	$criteria->condition.=' and t.lid = '.$categoryId;
		//$pages = new CPagination(ProductMaterial::model()->count($criteria));
		//$pages->setPageSize(1);
		//$pages->applyLimit($criteria);
		$models = ProductMaterial::model()->findAll($criteria);
		$categories = $this->getCategories();
		
		$reasons = $this->getReasons();
		$this->render('damageindex',array(
				'models'=>$models,
				//'pages'=>$pages,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
				'reasons'=>$reasons,
	
		));
	}

	public function actionDamagereason() {
		$criteria = new CDbCriteria;
		$criteria->addCondition('type = 2 and dpid=:dpid and delete_flag=0');
		$criteria->order = ' lid desc ';
		$criteria->params[':dpid']=$this->companyId;
	
		$pages = new CPagination(Retreat::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = Retreat::model()->findAll($criteria);
	
		$this->render('damagereason',array(
				'models'=>$models,
				'pages' => $pages,
		));
	}
	public function actionReasoncreate() {
		$model = new Retreat ;
		$model->dpid = $this->companyId ;
	
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Retreat');
			$se=new Sequence("retreat");
			$model->lid = $se->nextval();
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			$model->delete_flag = '0';
			$model->type = '2';
			//                        var_dump($model);exit;
			if($model->save()) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('stockTaking/damagereason' , 'companyId' => $this->companyId));
			}
		}
		$this->render('reasoncreate' , array(
				'model' => $model ,
		));
	}
	public function actionReasonupdate(){
		$lid = Yii::app()->request->getParam('lid');
		//Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		$model = Retreat::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=>  $this->companyId));
	
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Retreat');
			if($model->save()){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('stockTaking/damagereason', 'companyId' => $this->companyId));
			}
		}
		$this->render('reasonupdate' , array(
				'model'=>$model,
		));
	}
	public function actionDamagedelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('lid');
		//Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			foreach ($ids as $id) {
				$model = Retreat::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $companyId)) ;
				if($model) {
					$model->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
				}
			}
		}else {
			Yii::app()->user->setFlash('error' ,yii::t('app', '请选择要删除的项目'));
		}
		$this->redirect(array('stockTaking/damagereason' , 'companyId' => $companyId)) ;
	}
	
	
	public function actionAllStore(){

		$username = Yii::app()->user->username;
		$optvals = Yii::app()->request->getParam('optval');
		$categoryId = Yii::app()->request->getParam('cid',0);
		$sttype = Yii::app()->request->getParam('sttype',1);
		$optval = array();
		$optval = explode(';',$optvals);
		//var_dump($optval);
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
			$stocktaking->title =''.date('m月d日 H时i分',time()).' 盘点操作记录';
			$stocktaking->status = 0;
			$stocktaking->is_sync = $is_sync;
			$stocktaking->save();
			
			foreach ($optval as $opts){
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
			$transaction->commit();
			Yii::app()->end(json_encode(array("status"=>"success","msg"=>$nostockmsg,"logid"=>$logid)));
				
			return true;
		}catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			exit;
			Yii::app()->end(json_encode(array("status"=>"fail")));
			return false;
		}
	}
	

	public function actionDamageStore(){
	
		$username = Yii::app()->user->username;
		$optvals = Yii::app()->request->getParam('optval');
		$categoryId = Yii::app()->request->getParam('cid',0);
		$optval = array();
		$optval = explode(';',$optvals);
		//var_dump($optval);
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
				
			foreach ($optval as $opts){
				$opt = array();
				$opt = explode(',',$opts);
				$id = $opt[0];
				$difference = $opt[1];
				$nowNum = $opt[2];
				$originalNum = $opt[3];
				$damagereason = $opt[4];
	
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
			$transaction->commit();
			Yii::app()->end(json_encode(array("status"=>"success","msg"=>$nostockmsg,"logid"=>$logid)));
	
			return true;
		}catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			exit;
			Yii::app()->end(json_encode(array("status"=>"fail")));
			return false;
		}
	}	
	
		public function actionStore(){
			//不可直接使用，需要修改....
			$id = Yii::app()->request->getParam('id');
			$categoryId = Yii::app()->request->getParam('cid',0);
			$nowNum = Yii::app()->request->getParam('nowNum');
			$originalNum = Yii::app()->request->getParam('originalNum');
			$difference = Yii::app()->request->getParam('difference');
			$dpid = $this->companyId;
			$db = Yii::app()->db;
			$transaction = $db->beginTransaction();
			try
			{	
				$is_sync = DataSync::getInitSync();
				//盘点日志
				//盘点日志
				$stocktaking = new StockTaking();
				$se=new Sequence("stock_taking");
				$logid = $stocktaking->lid = $se->nextval();
				$stocktaking->dpid = $dpid;
				$stocktaking->create_at = date('Y-m-d H:i:s',time());
				$stocktaking->update_at = date('Y-m-d H:i:s',time());
				$stocktaking->username = $username ;
				$stocktaking->title =''.date('m月d日 H时i分',time()).' 盘点操作记录';
				$stocktaking->status = 0;
				$stocktaking->is_sync = $is_sync;
				$stocktaking->save();
				
				$stocks = ProductMaterialStock::model()->find('material_id=:sid and dpid=:dpid and delete_flag=0 and t.create_at =(select max(t1.create_at) from nb_product_material_stock t1 where t1.delete_flag = 0 and t1.dpid='.$this->companyId.' and t1.material_id ='.$id.' )',array(':sid'=>$id,':dpid'=>$this->companyId,));
				
				//对该次盘点进行日志保存
				$stocktakingdetail = new StockTakingDetail();
				$se=new Sequence("stock_taking_detail");
				$detailid = $stocktakingdetail->lid = $se->nextval();
				$stocktakingdetail->dpid = $dpid;
				$stocktakingdetail->create_at = date('Y-m-d H:i:s',time());
				$stocktakingdetail->update_at = date('Y-m-d H:i:s',time());
				$stocktakingdetail->logid = $logid;
				$stocktakingdetail->material_id = $id;
				$stocktakingdetail->material_stock_id = $stocks->lid;
				$stocktakingdetail->reality_stock = $originalNum;
				$stocktakingdetail->taking_stock = $nowNum;
				$stocktakingdetail->number = $difference;
				$stocktakingdetail->reasion = '';
				$stocktakingdetail->status = 0;
				$stocktakingdetail->is_sync = $is_sync;
				$stocktakingdetail->save();
				
				if($difference > 0 ){
					//盘点操作，当盘点的库存比理论库存多时，直接在后进的库存批次上加上此次的盘点的差值。。。
					

					//下面是对该次盘点进行的操作。。。
					$stock->stock = $stocks->stock + $difference;
					$stock->update_at = date('Y-m-d H:i:s',time());
					$stock->update();
					
				}else{
					$sql = 'select t.* from nb_product_material_stock t where t.delete_flag = 0 and t.dpid ='.$dpid.' and t.material_id = '.$id.' order by t.create_at asc';
					$command = $db->createCommand($sql);
					$stock = $command->queryAll();
					$minusnum = -$difference;
					//var_dump($minusnum.'1');
					//var_dump($stock);
					foreach ($stock as $stockid){
						//var_dump($stockid);
						$stockori = $stockid['stock'];
						if($minusnum >= 0 && $stockori > 0){
							$minusnums = $minusnum - $stockori ;
							//var_dump($stockori.'@@');
							//var_dump($minusnums.'2');
							if($minusnums <= 0 ) {
								//var_dump($minusnums.'3');
								$stock = ProductMaterialStock::model()->find('material_id=:sid and dpid=:dpid and delete_flag=0 and lid=:lid',array(':sid'=>$id,':dpid'=>$this->companyId,':lid'=>$stockid['lid'],));
								$changestock = $stock->stock - $minusnum;
								$sql1 = 'update nb_product_material_stock set stock = '.$changestock. ' where delete_flag = 0 and material_id ='.$id.' and dpid ='.$this->companyId.' and lid='.$stockid['lid'];
								//var_dump($sql1);
								//Yii::app()->db->createCommand($sql)->execute();
								$command=$db->createCommand($sql1);
								$command->execute();
								//$stock->update_at = date('Y-m-d H:i:s',time());
								//$stock->update();
								
								//对该次盘点进行日志保存
								$stocktakingdetails = new StockTakingDetail();
								$se=new Sequence("stock_taking_detail");
								$stocktakingdetails = array(
										'lid'=>$se->nextval(),
										'dpid'=>$dpid,
										'create_at'=>date('Y-m-d H:i:s',time()),
										'update_at'=>date('Y-m-d H:i:s',time()),
										'logid'=>$detailid,
										'material_id'=>$id,
										'material_stock_id' => $stock->lid,
										'reality_stock' => $stock->stock,
										'taking_stock' => ''.$changestock,
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
// 								$stock = ProductMaterialStock::model()->find('material_id=:sid and dpid=:dpid and delete_flag=0 and lid=:lid',array(':sid'=>$id,':dpid'=>$this->companyId,':lid'=>$stockid['lid'],));
								
								
								//对该次盘点进行日志保存
								$materialStockLog = new MaterialStockLog();
								$se=new Sequence("material_stock_log");
								$materialStockLog = array(
										'lid'=>$se->nextval(),
										'dpid'=>$dpid,
										'create_at'=>date('Y-m-d H:i:s',time()),
										'update_at'=>date('Y-m-d H:i:s',time()),
										'logid'=>$detailid,
										'material_id'=>$id,
										'material_stock_id' => $stock->lid,
										'reality_stock' => $stock->stock,
										'taking_stock' => $stockori,
										'number'=>'-'.$stockori,
										'resean'=>'',
										'status' => 1,
										'is_sync'=>$is_sync,

								);
								$command = $db->createCommand()->insert('nb_stock_taking_detail',$materialStockLog);
								
							}
						}
					}
					//exit;
				}

				$transaction->commit();
				Yii::app()->end(json_encode(array("status"=>"success")));
				//var_dump($stock);exit;
					
				return true;
			}catch (Exception $e) {
				$transaction->rollback(); //如果操作失败, 数据回滚
				Yii::app()->end(json_encode(array("status"=>"fail")));
				return false;
			}		
		}
	
	private function getCategoryList(){
		$categories = MaterialCategory::model()->findAll('delete_flag=0 and dpid=:companyId' , array(':companyId' => $this->companyId)) ;
		//var_dump($categories);exit;
		return CHtml::listData($categories, 'lid', 'category_name');
	}
	public function actionGetChildren(){
		$pid = Yii::app()->request->getParam('pid',0);
		if(!$pid){
			Yii::app()->end(json_encode(array('data'=>array(),'delay'=>400)));
		}
		$treeDataSource = array('data'=>array(),'delay'=>400);
		$categories = Helper::getCategory($this->companyId,$pid);

		foreach($categories as $c){
			$tmp['name'] = $c['category_name'];
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
	
	private function getReasons(){
		$criteria = new CDbCriteria;
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId ;
		$criteria->order = ' t.lid asc ';
		$models = Retreat::model()->findAll($criteria);
		return $models;
	}

	//导出excel
	public function actionStockExport(){
		$categoryId = Yii::app()->request->getParam('cid',0);
		$criteria = new CDbCriteria;
		$criteria->with = array('category');
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId;
		if($categoryId){
			$criteria->condition.=' and t.category_id = '.$categoryId;
		}
		$criteria->order = 't.category_id asc,t.lid asc';
		$models = ProductMaterial::model()->findAll($criteria);
	
		$objPHPExcel = new PHPExcel();
		//设置第1行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		//设置第2行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
		$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(30);
		//设置字体
		$objPHPExcel->getDefaultStyle()->getFont()->setName('宋体');
		$objPHPExcel->getDefaultStyle()->getFont()->setSize(16);
		$styleArray1 = array(
				'font' => array(
						'bold' => true,
						'color'=>array(
								'rgb' => '000000',
						),
						'size' => '20',
				),
				'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				),
		);
		$styleArray2 = array(
				'font' => array(
						'color'=>array(
								'rgb' => 'ff0000',
						),
						'size' => '16',
				),
				'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				),
		);
		//大边框样式 边框加粗
		$lineBORDER = array(
				'borders' => array(
						'outline' => array(
								'style' => PHPExcel_Style_Border::BORDER_THICK,
								'color' => array('argb' => '000000'),
						),
				),
		);
		//$objPHPExcel->getActiveSheet()->getStyle('A1:E'.$j)->applyFromArray($lineBORDER);
		//细边框样式
		$linestyle = array(
				'borders' => array(
						'outline' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN,
								'color' => array('argb' => 'FF000000'),
						),
				),
		);

		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1','盘点库存报表')
		->setCellValue('A2',yii::t('app','盘点库存列表'))
		->setCellValue('A3','品项编号')
		->setCellValue('B3','品项名称')
		->setCellValue('C3','类型')
		->setCellValue('D3','库存单位')
		->setCellValue('E3','盘点库存')
		->setCellValue('F3','');
	
		$i=4;
		foreach($models as $v){
		
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValueExplicit('A'.$i,$v->material_identifier,PHPExcel_Cell_DataType::TYPE_STRING)
			->setCellValue('B'.$i,$v->material_name)
			->setCellValue('C'.$i,$v->category->category_name)
			->setCellValue('D'.$i,Common::getStockName($v->stock_unit_id))
			->setCellValue('E'.$i,'')
			->setCellValue('F'.$i,'');
			
			$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->applyFromArray($linestyle);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
			$objPHPExcel->getActiveSheet()->getStyle('F'.$i.':C'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$i++;
		}
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
		//单元格加粗，居中：
		$objPHPExcel->getActiveSheet()->getStyle('A1:F'.$i)->applyFromArray($lineBORDER);//大边框格式引用
		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);
	
		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//A2字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//A2字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置填充颜色
	
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFill()->getStartColor()->setARGB('FFB848');
		$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFill()->getStartColor()->setARGB('FFB848');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="盘点库存列表（".date('m-d H:i',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}

}









