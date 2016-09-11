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
	public function actionCreate(){
		$model = new ProductMaterial();
		$modelStock = new ProductMaterialStock();
		$model->dpid = $this->companyId ;
		$modelStock->dpid = $this->companyId ;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductMaterial');
			
			$db = Yii::app()->db;
			$sql = 'select t.* from nb_material_category t where t.delete_flag = 0 and t.lid = '.$model->category_id;
			$command1 = $db->createCommand($sql);
			$categoryCode = $command1->queryRow()['mchs_code'];
			
			$sql = 'select t.* from nb_material_unit t where t.delete_flag = 0 and t.lid = '.$model->stock_unit_id;
			$command2 = $db->createCommand($sql);
			$stockUnitId = $command2->queryRow()['muhs_code'];
			
			$sql = 'select t.* from nb_material_unit t where t.delete_flag = 0 and t.lid = '.$model->sales_unit_id;
			$command3 = $db->createCommand($sql);
			$salesUnitId = $command3->queryRow()['muhs_code'];
			//var_dump($categoryId,$stockUnitId,$salesUnitId);exit;
			if($categoryCode&&$stockUnitId&&$salesUnitId){
	            $se=new Sequence("product_material");
	            $lid = $se->nextval();
	            $model->lid = $lid;
	            
	            $code = new Sequence('mphs_code');
	            $mphs_code = $code->nextval();
	            $model->create_at = date('Y-m-d H:i:s',time());
	            $model->update_at = date('Y-m-d H:i:s',time());
	            $model->mphs_code = ProductCategory::getChscode($this->companyId, $lid, $mphs_code);
	            $model->mchs_code = $categoryCode;
	            $model->mulhs_code = $stockUnitId;
	            $model->mushs_code = $salesUnitId;
	            $model->delete_flag = '0';
	            
	            $se=new Sequence("product_material_stock");
	            $modelStock->lid = $se->nextval();
	            $modelStock->create_at = date('Y-m-d H:i:s',time());
	            $modelStock->update_at = date('Y-m-d H:i:s',time());
	            $modelStock->material_id = $model->lid;
	            $modelStock->mphs_code = $model->mphs_code;
	            
				if($model->save()&&$modelStock->save()){
					Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
					$this->redirect(array('productMaterial/index' , 'companyId' => $this->companyId ));
				}
			}else{
				Yii::app()->user->setFlash('error',yii::t('app','添加失败'));
				$this->redirect(array('productMaterial/index' , 'companyId' => $this->companyId ));
			}
		}
		$categories = $this->getCategoryList();
 		$this->render('create' , array(
			'model' => $model ,
			'categories' => $categories
		));
	}
	
	public function actionUpdate(){
		$id = Yii::app()->request->getParam('id');
		$model = ProductMaterial::model()->find('lid=:materialId and dpid=:dpid' , array(':materialId' => $id,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		//Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductMaterial');
         	$model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				$this->redirect(array('productMaterial/index' , 'companyId' => $this->companyId ));
			}
		}
		$categories = $this->getCategoryList();//var_dump($categories);exit;
		$this->render('update' , array(
				'model' => $model ,
				'categories' => $categories
		));
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
                Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_product_material set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('productMaterial/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('productMaterial/index' , 'companyId' => $companyId)) ;
		}
	}
	
	public function actionDetailindex(){
		$materialId = Yii::app()->request->getParam('id',0);
		$criteria = new CDbCriteria;
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId.' and t.material_id ='.$materialId;
		//	$criteria->condition.=' and t.lid = '.$categoryId;
		$pages = new CPagination(ProductMaterialStock::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = ProductMaterialStock::model()->findAll($criteria);
		$this->render('detailindex',array(
				'models'=>$models,
				'pages'=>$pages,
	
		));
	}

	public function actionAllStore(){
		$optvals = Yii::app()->request->getParam('optval');
		$categoryId = Yii::app()->request->getParam('cid',0);
		$optval = array();
		$optval = explode(';',$optvals);
		//var_dump($optval);exit;
		$dpid = $this->companyId;
		$db = Yii::app()->db;
		$transaction = $db->beginTransaction();
		try
		{
			foreach ($optval as $opts){
				$opt = array();
				$opt = explode(',',$opts); 
				$id = $opt[0];
				$difference = $opt[1];
				$nowNum = $opt[2];
				$originalNum = $opt[3];
				$is_sync = DataSync::getInitSync();
				//盘点日志
				$materialStockLog = new MaterialStockLog();
				$se=new Sequence("material_stock_log");
				$logid = $materialStockLog->lid = $se->nextval();
				$materialStockLog->dpid = $dpid;
				$materialStockLog->create_at = date('Y-m-d H:i:s',time());
				$materialStockLog->update_at = date('Y-m-d H:i:s',time());
				$materialStockLog->material_id = $id;
				$materialStockLog->type = 3;
				$materialStockLog->stock_num = $difference;
				$materialStockLog->original_num = $originalNum;
				$materialStockLog->resean = '盘点修改总记录{和原始库存差值：'.$difference.'/原始库存：'.$originalNum.'/盘点库存：'.$nowNum.'/盘点模型：先进先出}';
				$materialStockLog->is_sync = $is_sync;
				$materialStockLog->save();
		
				if($difference > 0 ){
					//盘点操作，当盘点的库存比理论库存多时，直接在后进的库存批次上加上此次的盘点的差值。。。
					$stock = ProductMaterialStock::model()->find('material_id=:sid and dpid=:dpid and delete_flag=0 and t.create_at =(select max(t1.create_at) from nb_product_material_stock t1 where t1.delete_flag = 0 and t1.dpid='.$this->companyId.' and t1.material_id ='.$id.' )',array(':sid'=>$id,':dpid'=>$this->companyId,));
						
					//对该次盘点进行日志保存
					$materialStockLog = new MaterialStockLog();
					$se=new Sequence("material_stock_log");
					$materialStockLog->lid = $se->nextval();
					$materialStockLog->dpid = $dpid;
					$materialStockLog->create_at = date('Y-m-d H:i:s',time());
					$materialStockLog->update_at = date('Y-m-d H:i:s',time());
					$materialStockLog->logid = $logid;
					$materialStockLog->material_id = $id;
					$materialStockLog->type = 3;
					$materialStockLog->stock_num = $difference;
					$materialStockLog->original_num = $stock->stock;
					$materialStockLog->resean = '盘点修改批次{和原始库存差值：'.$difference.'/原始库存：'.$stock->stock.'/盘点模型：先进先出}';
					$materialStockLog->is_sync = $is_sync;
					$materialStockLog->save();
		
					//下面是对该次盘点进行的操作。。。
					$stock->stock = $stock->stock + $difference;
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
								$materialStockLog = new MaterialStockLog();
								$se=new Sequence("material_stock_log");
								$materialStockLog = array(
										'lid'=>$se->nextval(),
										'dpid'=>$dpid,
										'create_at'=>date('Y-m-d H:i:s',time()),
										'update_at'=>date('Y-m-d H:i:s',time()),
										'logid'=>$logid,
										'material_id'=>$id,
										'type'=>"3",
										'stock_num'=>'-'.$minusnum,
										'original_num'=>$stock->stock,
										'resean'=>'盘点修改批次{和原始库存差值：'.-$minusnum.'/原始库存：'.$stock->stock.'/盘点模型：先进先出}',
										'is_sync'=>$is_sync,
								);
								$command = $db->createCommand()->insert('nb_material_stock_log',$materialStockLog);
								
// 								$materialStockLog = new MaterialStockLog();
// 								$se=new Sequence("material_stock_log");
// 								$materialStockLog->lid = $se->nextval();
// 								$materialStockLog->dpid = $dpid;
// 								$materialStockLog->create_at = date('Y-m-d H:i:s',time());
// 								$materialStockLog->update_at = date('Y-m-d H:i:s',time());
// 								$materialStockLog->logid = $logid;
// 								$materialStockLog->material_id = $id;
// 								$materialStockLog->type = 3;
// 								$materialStockLog->stock_num = -$minusnum;
// 								$materialStockLog->original_num = $stock->stock;
// 								$materialStockLog->resean = '盘点修改{和原始库存差值：'.-$minusnum.'/原始库存：'.$stock->stock.'/盘点模型：先进先出}';
// 								$materialStockLog->is_sync = $is_sync;
// 								$materialStockLog->save();
		
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
										'logid'=>$logid,
										'material_id'=>$id,
										'type'=>"3",
										'stock_num'=>'-'.$stockori,
										'original_num'=>$stockori,
										'resean'=>'盘点修改批次{和原始库存差值：'.-$stockori.'/原始库存：'.$stockori.'/盘点模型：先进先出}',
										'is_sync'=>$is_sync,
								);
								$command = $db->createCommand()->insert('nb_material_stock_log',$materialStockLog);
								
// 								//对该次盘点进行日志保存
// 								$materialStockLog = new MaterialStockLog();
// 								$se=new Sequence("material_stock_log");
// 								$materialStockLog->lid = $se->nextval();
// 								$materialStockLog->dpid = $dpid;
// 								$materialStockLog->create_at = date('Y-m-d H:i:s',time());
// 								$materialStockLog->update_at = date('Y-m-d H:i:s',time());
// 								$materialStockLog->logid = $logid;
// 								$materialStockLog->material_id = $id;
// 								$materialStockLog->type = 3;
// 								$materialStockLog->stock_num = -$stockori;
// 								$materialStockLog->original_num = $stockori;
// 								$materialStockLog->resean = '盘点修改{和原始库存差值：'.-$stockori.'/原始库存：'.$stockori.'/盘点模型：先进先出}';
// 								$materialStockLog->is_sync = $is_sync;
// 								$materialStockLog->save();
		
							}
						}
					}
					//exit;
				}
			}
			$transaction->commit();
			Yii::app()->end(json_encode(array("status"=>"success")));
			//var_dump($stock);exit;
				
			return true;
		}catch (Exception $se) {
			$transaction->rollback(); //如果操作失败, 数据回滚
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
				$materialStockLog = new MaterialStockLog();
				$se=new Sequence("material_stock_log");
				$logid = $materialStockLog->lid = $se->nextval();
				$materialStockLog->dpid = $dpid;
				$materialStockLog->create_at = date('Y-m-d H:i:s',time());
				$materialStockLog->update_at = date('Y-m-d H:i:s',time());
				$materialStockLog->material_id = $id;
				$materialStockLog->type = 3;
				$materialStockLog->stock_num = $difference;
				$materialStockLog->original_num = $originalNum;
				$materialStockLog->resean = '盘点修改总记录{和原始库存差值：'.$difference.'/原始库存：'.$originalNum.'/盘点库存：'.$nowNum.'/盘点模型：先进先出}';
				$materialStockLog->is_sync = $is_sync;
				$materialStockLog->save();
				
				if($difference > 0 ){
					//盘点操作，当盘点的库存比理论库存多时，直接在后进的库存批次上加上此次的盘点的差值。。。
					$stock = ProductMaterialStock::model()->find('material_id=:sid and dpid=:dpid and delete_flag=0 and t.create_at =(select max(t1.create_at) from nb_product_material_stock t1 where t1.delete_flag = 0 and t1.dpid='.$this->companyId.' and t1.material_id ='.$id.' )',array(':sid'=>$id,':dpid'=>$this->companyId,));
					
					//对该次盘点进行日志保存
					$materialStockLog = new MaterialStockLog();
					$se=new Sequence("material_stock_log");
					$materialStockLog->lid = $se->nextval();
					$materialStockLog->dpid = $dpid;
					$materialStockLog->create_at = date('Y-m-d H:i:s',time());
					$materialStockLog->update_at = date('Y-m-d H:i:s',time());
					$materialStockLog->logid = $logid;
					$materialStockLog->material_id = $id;
					$materialStockLog->type = 3;
					$materialStockLog->stock_num = $difference;
					$materialStockLog->original_num = $stock->stock;
					$materialStockLog->resean = '盘点修改批次{和原始库存差值：'.$difference.'/原始库存：'.$stock->stock.'/盘点模型：先进先出}';
					$materialStockLog->is_sync = $is_sync;
					$materialStockLog->save();

					//下面是对该次盘点进行的操作。。。
					$stock->stock = $stock->stock + $difference;
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
								$materialStockLog = new MaterialStockLog();
								$se=new Sequence("material_stock_log");
								$materialStockLog->lid = $se->nextval();
								$materialStockLog->dpid = $dpid;
								$materialStockLog->create_at = date('Y-m-d H:i:s',time());
								$materialStockLog->update_at = date('Y-m-d H:i:s',time());
								$materialStockLog->logid = $logid;
								$materialStockLog->material_id = $id;
								$materialStockLog->type = 3;
								$materialStockLog->stock_num = -$minusnum;
								$materialStockLog->original_num = $stock->stock;
								$materialStockLog->resean = '盘点修改批次{和原始库存差值：'.-$minusnum.'/原始库存：'.$stock->stock.'/盘点模型：先进先出}';
								$materialStockLog->is_sync = $is_sync;
								$materialStockLog->save();

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
								$materialStockLog->lid = $se->nextval();
								$materialStockLog->dpid = $dpid;
								$materialStockLog->create_at = date('Y-m-d H:i:s',time());
								$materialStockLog->update_at = date('Y-m-d H:i:s',time());
								$materialStockLog->logid = $logid;
								$materialStockLog->material_id = $id;
								$materialStockLog->type = 3;
								$materialStockLog->stock_num = -$stockori;
								$materialStockLog->original_num = $stockori;
								$materialStockLog->resean = '盘点修改批次{和原始库存差值：'.-$stockori.'/原始库存：'.$stockori.'/盘点模型：先进先出}';
								$materialStockLog->is_sync = $is_sync;
								$materialStockLog->save();
								
							}
						}
					}
					//exit;
				}

				$transaction->commit();
				Yii::app()->end(json_encode(array("status"=>"success")));
				//var_dump($stock);exit;
					
				return true;
			}catch (Exception $se) {
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









