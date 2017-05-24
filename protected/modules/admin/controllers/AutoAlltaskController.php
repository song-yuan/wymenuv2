<?php
class AutoAlltaskController extends BackendController
{

	public function actionAutogenpurchase(){
		$dpid = Yii::app()->request->getParam('companyId');
		$db = Yii::app()->db;
		$stocksets = $db->createCommand('select * from nb_stock_setting where delete_flag=0 and dpid ='.$dpid)->queryRow();
		if(!empty($stocksets)){
			$salesday = $stocksets['dsales_day'];
			$safemaxd = $stocksets['dsafe_max_day'];
			$safemind = $stocksets['dsafe_min_day'];
			
			$nowday = date('Y-m-d 00:00:00',time());
			$salesday_now = date('Y-m-d 00:00:00',strtotime("".$nowday."-". $salesday." day"));
			
			$sql = 'select nms.all_stock_now,mds.all_slase,pm.* from nb_product_material pm '.
					'left join (select msn.material_id,sum(stock) as all_stock_now from nb_product_material_stock msn where msn.dpid='.$dpid.' and msn.delete_flag =0 group by msn.material_id) nms on(nms.material_id = pm.lid)'.
					'left join (select ms.material_id,sum(ms.stock_num) as all_slase,count(ms.lid) as all_num from nb_material_stock_log ms where ms.type = 1 and ms.dpid = '.$dpid.' and ms.create_at >="'.$salesday_now.'" and ms.create_at <="'.$nowday.'" group by ms.material_id) mds on(mds.material_id = pm.lid) '.
					'where dpid ='.$dpid;
			$models = $db->createCommand($sql)->queryAll();
			if($models){
				foreach ($models as $model){
					if($model['all_slase']){
						$averstocks = $model['all_slase']/$salesday;
						$maxstocks = $averstocks * $safemaxd;
						var_dump($model['all_slase']);
						var_dump($averstocks);
						var_dump($maxstocks);
					}
				}
			}
			var_dump($models);exit;
			Yii::app()->end(json_encode(array("status"=>"success","msg"=>'平均:'.$salesday.';最大：'.$safemaxd.';最小：'.$safemind.';时间：'.$nowday.';时间：'.$salesday_now)));
		}else{
			Yii::app()->end(json_encode(array("status"=>"success","msg"=>'请先设置安全库存，再进行自动生成采购单操作')));
		}
		
		
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
				$allpansun_price = '0';
				$all_price = '0';
				
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









