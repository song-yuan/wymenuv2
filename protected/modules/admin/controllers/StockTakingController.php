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
		$sttype = Yii::app()->request->getParam('sttype',0);
		$db = Yii::app()->db;
		if($categoryId){
			$cate ='='.$categoryId;
		}else{
			$cate ='>0';
		}
		
		$sql = 'select ms.stock_all,mu.unit_name,mu.lid as mu_lid,ms.lid as ms_lid,ms.unit_name as sales_name,k.category_name,inv.inventory_stock,inv.inventory_sales,inv.ratio,inv.lid as invtid,t.* from nb_product_material t '.
				'left join nb_material_category k on(t.category_id = k.lid and t.dpid = k.dpid)'.
				'left join nb_material_unit mu on(t.stock_unit_id = mu.lid and t.dpid = mu.dpid) '.
				'left join nb_material_unit ms on(t.sales_unit_id = ms.lid and t.dpid = ms.dpid) '.
				'left join (select sum(stock) as stock_all,material_id from nb_product_material_stock where dpid='.$this->companyId.' and delete_flag=0 group by material_id) ms on(t.lid = ms.material_id)'.
				'left join (select lid,material_id,inventory_stock,inventory_sales,ratio from nb_inventory_detail where inventory_id in(select max(lid) from nb_inventory where dpid ='.$this->companyId.' and type =2 and status =0) group by material_id) inv on(inv.material_id = t.lid)'.
				'where t.lid in(select tt.lid from nb_product_material tt where tt.delete_flag = 0 and tt.dpid ='.$this->companyId.' and tt.category_id '.$cate.') and mu.delete_flag =0 and ms.delete_flag =0 order by t.material_identifier asc,t.category_id asc,t.lid asc';
		$models = $db->createCommand($sql)->queryAll();
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
		$db = Yii::app()->db;
		if($categoryId){
			$cate ='='.$categoryId;
		}else{
			$cate ='>0';
		}
		
		$sql = 'select ms.stock_all,mu.unit_name,k.category_name,t.* from nb_product_material t '.
				'left join nb_material_category k on(t.category_id = k.lid and t.dpid = k.dpid)'.
				'left join nb_material_unit mu on(t.stock_unit_id = mu.lid and t.dpid = mu.dpid and mu.delete_flag =0) '.
				'left join (select sum(stock) as stock_all,material_id from nb_product_material_stock where dpid='.$this->companyId.' and delete_flag=0 group by material_id) ms on(t.lid = ms.material_id)'.
				'where t.lid in(select tt.lid from nb_product_material tt where tt.delete_flag = 0 and tt.dpid ='.$this->companyId.' and tt.category_id '.$cate.') order by t.category_id asc,t.lid asc';
		$models = $db->createCommand($sql)->queryAll();
		
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
		$dpid = $this->companyId;
		$nostockmsg = '';
		$time = time();
		
		$db = Yii::app()->db;
		$transaction = $db->beginTransaction();
		try
		{
			//盘点日志
			$se = new Sequence("stock_taking");
			$logid = $se->nextval();
			$stockArr = array(
					'lid'=>$logid,
					'dpid'=>$dpid,
					'create_at'=>date('Y-m-d H:i:s',$time),
					'update_at'=>date('Y-m-d H:i:s',$time),
					'username'=>$username,
					'type'=>$sttype,
					'title'=>date('m月d日 H时i分',$time).' 盘点操作记录',
					'status'=>0
			);
			$db->createCommand()->insert('nb_stock_taking',$stockArr);
			
			foreach ($optval as $opts){
				$opt = array();
				$opt = explode(',',$opts); 
				
				$id = $opt[0];
				$nownumd = $opt[1];
				$nownumx = $opt[2];
				$ratio = $opt[3];
				
				// 系统库存
				$originalNum = $opt[4];
				// 原料销售单位
				$salesName = $opt[5];
				
				$presystemNum = '0.00';
				$stockinNum = '0.00'; // 入库库存
				$stockinPrice = '0.00';// 入库成本
				$damageNum = '0'; //损耗数量
				$damagePrice = '0';//损耗成本
				$salseNum = '0.00';//销售数量
				$salsePrice = '0.00';//销售成本
				$totalNum = '0.00';//总消耗量
				$systemNum = $originalNum;//系统库存
				$nowNum = $nownumd*$ratio + $nownumx;// 盘点库存
				$diffPrice = '0.00';//损溢成本
				
				
				// 查询原料是否入库
				$sql = 'select * from nb_product_material_stock where material_id='.$id.' and dpid='.$dpid.' and delete_flag=0 order by create_at desc limit 1';
				$stocks = $db->createCommand($sql)->queryRow();
				// 已入库
				if(!empty($stocks)){
					// 获取上一次盘点统计信息
					$sql = 'select * from nb_stock_taking_statistics where dpid='.$dpid.' and type='.$sttype.' and material_id='.$id.' and delete_flag=0 order by lid desc limit 1';
					$statict = $db->createCommand($sql)->queryRow();
					
					if($statict){
						$presystemNum = $statict['stock_taking_num'];//上次盘点库存
						$preStockTime = $statict['create_at'];// 上次盘点时间
						
						// 获取两次盘点之间其他类型的盘点 损耗总和  其他盘点不能影响该盘点的系统库存
						$sql = 'select sum(stock_taking_difnum) as stock_taking_difnum from nb_stock_taking_statistics where dpid='.$dpid.' and create_at>="'.$preStockTime.'" and type!='.$sttype.' and material_id='.$id.' and delete_flag=0';
						$stDifnum = Yii::app()->db->createCommand($sql)->queryScalar();
						$systemNum = $systemNum - $stDifnum;
						
						// 从库存日志记录表 查询上次盘点到本次盘点的 入库库存 盘损库存 销售库存
						$sql = 'select type,sum(stock_num) as stock_num,sum(stock_num*unit_price) as stock_cost from nb_material_stock_log where dpid='.$dpid.' and material_id='.$id.' and type in(0,1,2,4) and create_at>"'.$preStockTime.'" group by type';
						$mStockLogs = $db->createCommand($sql)->queryAll();
						if($mStockLogs){
							foreach ($mStockLogs as $mStockArr){
								$mtype = $mStockArr['type'];
								if($mtype==0){
									// 采购入库
									$stockinNum = $mStockArr['stock_num'];
									$stockinPrice = $mStockArr['stock_cost'];
								}elseif($mtype==1){
									// 堂食销售出库
									$salseNum +=$mStockArr['stock_num'];
									$salsePrice +=$mStockArr['stock_cost'];
								}elseif($mtype==2){
									// 外卖销售出库
									$salseNum +=$mStockArr['stock_num'];
									$salsePrice +=$mStockArr['stock_cost'];
								}elseif($mtype==4){
									// 盘损库存
									$damageNum = $mStockArr['stock_num'];
									$damagePrice = $mStockArr['stock_cost'];
								}
							}
						}
					}
					
					// 超过原始库存
					$difference = $nowNum - $systemNum;// 损溢库存
					if($difference > 0 ){
						//盘点操作，当盘点的库存比理论库存多时，直接在后进的库存批次上加上此次的盘点的差值。。。
						if($stocks['batch_stock'] == 0){
							$unit_price = '0';
						}else{
							$unit_price = $stocks['stock_cost'] / $stocks['batch_stock'];
						}	
						$diffPrice = $unit_price*$difference;
						
						//下面是对该次盘点进行的操作。。。
						$sql = 'update nb_product_material_stock set stock=stock+'.$difference.' where lid='.$stocks['lid'].' and dpid='.$stocks['dpid'];
						$db->createCommand($sql)->execute();
						
						//盘点详情记录
						$se = new Sequence("stock_taking_detail");
						$lid = $se->nextval();
						$stocktakingdetails = array(
								'lid'=>$lid,
								'dpid'=>$dpid,
								'create_at'=>date('Y-m-d H:i:s',$time),
								'update_at'=>date('Y-m-d H:i:s',$time),
								'logid'=>$logid,
								'material_id'=>$id,
								'material_stock_id' =>$stocks['lid'],
								'reality_stock' =>$originalNum,
								'taking_stock' =>$nowNum,
								'number'=>$difference,
								'reasion'=>'',
						);
						$command = $db->createCommand()->insert('nb_stock_taking_detail',$stocktakingdetails);
					
						$se = new Sequence("material_stock_log");
						$lid = $se->nextval();
						$stocktakingdetails = array(
								'lid'=>$lid,
								'dpid'=>$dpid,
								'create_at'=>date('Y-m-d H:i:s',$time),
								'update_at'=>date('Y-m-d H:i:s',$time),
								'type'=>3,
								'logid'=>$logid,
								'material_id'=>$id,
								'stock_num' => $difference,
								'original_num' => $originalNum,
								'unit_price'=>$unit_price,
								'resean'=>'盘点溢出',
						);
						$command = $db->createCommand()->insert('nb_material_stock_log',$stocktakingdetails);
					}else{
						//盘点库存小于系统的库存  查出所有库存不为0批次
						$sql = 'select * from nb_product_material_stock where stock!=0 and dpid ='.$dpid.' and material_id = '.$id.' and delete_flag = 0 order by create_at asc';
						$stock2 = $db->createCommand($sql)->queryAll();
						
						if(empty($stock2)){
							// 如果所有批次都为0 在最后这批扣减
							if($stocks['batch_stock'] == 0){
								$unit_price = '0';
							}else{
								$unit_price = $stocks['stock_cost'] / $stocks['batch_stock'];
							}
							$diffPrice = $unit_price*$difference;
							
							//下面是对该次盘点进行的操作。。。
							$sql = 'update nb_product_material_stock set stock=stock+'.$difference.' where lid='.$stocks['lid'].' and dpid='.$stocks['dpid'];
							$db->createCommand($sql)->execute();
							
							//盘点详情记录
							$se = new Sequence("stock_taking_detail");
							$lid = $se->nextval();
							$stocktakingdetails = array(
									'lid'=>$lid,
									'dpid'=>$dpid,
									'create_at'=>date('Y-m-d H:i:s',$time),
									'update_at'=>date('Y-m-d H:i:s',$time),
									'logid'=>$logid,
									'material_id'=>$id,
									'material_stock_id' =>$stocks['lid'],
									'reality_stock' =>$originalNum,
									'taking_stock' =>$nowNum,
									'number'=>$difference,
									'reasion'=>'',
							);
							$command = $db->createCommand()->insert('nb_stock_taking_detail',$stocktakingdetails);
								
							$se = new Sequence("material_stock_log");
							$lid = $se->nextval();
							$stocktakingdetails = array(
									'lid'=>$lid,
									'dpid'=>$dpid,
									'create_at'=>date('Y-m-d H:i:s',$time),
									'update_at'=>date('Y-m-d H:i:s',$time),
									'type'=>3,
									'logid'=>$logid,
									'material_id'=>$id,
									'stock_num' => $difference,
									'original_num' => $originalNum,
									'unit_price'=>$unit_price,
									'resean'=>'盘点损失',
							);
							$command = $db->createCommand()->insert('nb_material_stock_log',$stocktakingdetails);
						}
						
						// 盘点差异 取正
						$minusnum = -$difference;
						foreach ($stock2 as $stock){
							$stockori = $stock['stock'];//该批次库存
							if($stockori < 0){
								$minusnum = -$minusnum + $stockori;
							}else{
								$minusnum = $minusnum - $stockori ;
							}
							
							if($stock['batch_stock'] == 0){
								$unit_price = '0';
							}else{
								$unit_price = $stock['stock_cost'] / $stock['batch_stock'];
							}
							// 该批库存 大于 差值的库存
							if($minusnum <= 0 ) {
								if($stockori < 0){
									$changestock = $minusnum - $stockori;
								}else{
									$changestock = $stockori + $minusnum;
								}
								
								$sql = 'update nb_product_material_stock set stock = stock-'.$changestock. ' where lid ='.$stock['lid'].' and dpid='.$stock['dpid'];
								$command = $db->createCommand($sql)->execute();
								
								$diffPrice += $unit_price*$minusnum;
								//对该次盘点进行日志保存
								$se = new Sequence("stock_taking_detail");
								$lid = $se->nextval();
								$stocktakingdetails = array(
										'lid'=>$lid,
										'dpid'=>$dpid,
										'create_at'=>date('Y-m-d H:i:s',$time),
										'update_at'=>date('Y-m-d H:i:s',$time),
										'logid'=>$logid,
										'material_id'=>$id,
										'material_stock_id' => $stock['lid'],
										'reality_stock' => $originalNum,
										'taking_stock' => $nowNum,
										'number'=>$difference,
										'reasion'=>'',
								);
								$command = $db->createCommand()->insert('nb_stock_taking_detail',$stocktakingdetails);
								
								if($minusnum!=0){
									$se = new Sequence("material_stock_log");
									$lid = $se->nextval();
									$stocktakingdetails = array(
											'lid'=>$lid,
											'dpid'=>$dpid,
											'create_at'=>date('Y-m-d H:i:s',$time),
											'update_at'=>date('Y-m-d H:i:s',$time),
											'type'=>3,
											'logid'=>$logid,
											'material_id'=>$id,
											'stock_num' => -$changestock,
											'original_num' => $stockori,
											'unit_price'=>$unit_price,
											'resean'=>'盘点损失',
									);
									$command = $db->createCommand()->insert('nb_material_stock_log',$stocktakingdetails);
								}
								break;
							}else{
								$sql = 'update nb_product_material_stock set stock=0 where lid ='.$stock['lid'].' and dpid ='.$stock['dpid'];
								$command = $db->createCommand($sql)->execute();
								$diffPrice += -$unit_price*$stockori;
								
								$se = new Sequence("material_stock_log");
								$lid = $se->nextval();
								$stocktakingdetails = array(
										'lid'=>$lid,
										'dpid'=>$dpid,
										'create_at'=>date('Y-m-d H:i:s',$time),
										'update_at'=>date('Y-m-d H:i:s',$time),
										'type'=>3,
										'logid'=>$logid,
										'material_id'=>$id,
										'stock_num' => -$stockori,
										'original_num' => $stockori,
										'unit_price'=>$unit_price,
										'resean'=>'盘点损失',
								);
								$command = $db->createCommand()->insert('nb_material_stock_log',$stocktakingdetails);
							}
						}
					}
					
					// 插入盘点统计信息
					$totalNum = $damageNum + $salseNum;
					$se = new Sequence("stock_taking_statistics");
					$lid = $se->nextval();
					$statictsArr = array(
							'lid'=>$lid,
							'dpid'=>$dpid,
							'create_at'=>date('Y-m-d H:i:s',$time),
							'update_at'=>date('Y-m-d H:i:s',$time),
							'type'=>$sttype,
							'material_id'=>$id,
							'sales_name'=>$salesName,
							'stock_taking_id'=>$logid,
							'prestock_taking_num'=>$presystemNum,
							'stockin_num'=>$stockinNum,
							'stockin_price'=>$stockinPrice,
							'damage_num'=>$damageNum,
							'damage_price'=>$damagePrice,
							'salse_num'=>$salseNum,
							'salse_price'=>$salsePrice,
							'total_num'=>$totalNum,
							'system_num'=>$systemNum,
							'stock_taking_num'=>$nowNum,
							'stock_taking_difnum'=>$difference,
							'stock_taking_difprice'=>$diffPrice,
					);
					$command = $db->createCommand()->insert('nb_stock_taking_statistics',$statictsArr);
				}else{
					$matername = Common::getmaterialName($id);
					$nostockmsg = $nostockmsg.','.$matername;
					
					//对该次盘点进行日志保存
					$se=new Sequence("stock_taking_detail");
					$detailid = $se->nextval();
					$stocktakingdetail = array(
							'lid'=>$detailid,
							'dpid'=>$dpid,
							'create_at'=>date('Y-m-d H:i:s',$time),
							'update_at'=>date('Y-m-d H:i:s',$time),
							'logid'=>$logid,
							'material_id'=>$id,
							'material_stock_id' => '0000000000',
							'reality_stock' => $originalNum,
							'taking_stock' => $nowNum,
							'number'=>'0',
							'reasion'=>'该次盘点['.$matername.']尚未入库，无法进行盘点,请先添加入库单进行入库.',
					);
					$command = $db->createCommand()->insert('nb_stock_taking_detail',$stocktakingdetail);
				}
			}
			
			$sql = 'update nb_inventory set status =2 where status=0 and dpid='.$dpid.' and delete_flag=0';
			$db->createCommand($sql)->execute();
			$transaction->commit();
			$msg = json_encode(array("status"=>"success","msg"=>$nostockmsg,"logid"=>$logid));
		}catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			$msg = json_encode(array("status"=>"fail",'msg'=>$e->getMessage()));
		}
		Yii::app()->end($msg);
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
	public function getRatio($mulid,$mslid){
		$sql = 'select unit_ratio from nb_material_unit_ratio where stock_unit_id='.$mulid.' and sales_unit_id='.$mslid;
		$models = Yii::app()->db->createCommand($sql)->queryRow();
		if(!empty($models)){
			$r = $models['unit_ratio'];
		}else{
			$r = '0';
		}
		return $r;
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









