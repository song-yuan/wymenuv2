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
				'categoryId'=>$categoryId

		));
	}


	public function actionAllStore(){

		$username = Yii::app()->user->username;
		$optvals = Yii::app()->request->getParam('optval');
		$categoryId = Yii::app()->request->getParam('cid',0);
		$optval = array();
		$optval = explode(';',$optvals);
		//var_dump($optval);
		$dpid = $this->companyId;
		$db = Yii::app()->db;
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
						
					//对该次盘点进行批次日志保存
// 					$stocktakingdetail = new StockTakingDetail();
// 					$se=new Sequence("stock_taking_detail");
// 					$stocktakingdetail->lid = $se->nextval();
// 					$stocktakingdetail->dpid = $dpid;
// 					$stocktakingdetail->create_at = date('Y-m-d H:i:s',time());
// 					$stocktakingdetail->update_at = date('Y-m-d H:i:s',time());
// 					$stocktakingdetail->logid = $detailid;
// 					$stocktakingdetail->material_id = $id;
// 					$stocktakingdetail->material_stock_id = $stocks->lid;
// 					$stocktakingdetail->reality_stock = $originalNum;
// 					$stocktakingdetail->taking_stock = $nowNum;
// 					$stocktakingdetail->number = $difference;
// 					$stocktakingdetail->reasion = '';
// 					$stocktakingdetail->status = 1;
// 					$stocktakingdetail->is_sync = $is_sync;
// 					$stocktakingdetail->save();
					//var_dump($stocks);exit;
					//下面是对该次盘点进行的操作。。。
					$stocks->stock = $stocks->stock + $difference;
					$stocks->update_at = date('Y-m-d H:i:s',time());
					$stocks->update();
						
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
								//$stock = ProductMaterialStock::model()->find('material_id=:sid and dpid=:dpid and delete_flag=0 and lid=:lid',array(':sid'=>$id,':dpid'=>$this->companyId,':lid'=>$stockid['lid'],));
		
								//对该次盘点进行日志保存
								$materialStockLog = new StockTakingDetail();
								$se=new Sequence("stock_taking_detail");
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
			}
			$transaction->commit();
			Yii::app()->end(json_encode(array("status"=>"success")));
				
			return true;
		}catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			exit;
			Yii::app()->end(json_encode(array("status"=>"fail")));
			return false;
		}
	}
	
	
		public function actionStore(){
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

}









