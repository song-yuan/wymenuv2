<?php
class StatementstockController extends BackendController
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
			Yii::app()->user->setFlash('error' , '请选择公司˾');
			$this->redirect(array('company/index'));
		}
		return true;
	}

	public function actionList() {
		$type = Yii::app()->request->getParam('type');
		$this->render('list',array(
				'companyId' => $this->companyId,
				'type'=>$type,
		));
	}
	
	public function actionStockReport(){
		$categoryId = Yii::app()->request->getParam('cid',0);
		$text = Yii::app()->request->getParam('text',1);
		$codename = Yii::app()->request->getParam('codename','');
		$matename = Yii::app()->request->getParam('matename','');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$selectDpid = Yii::app()->request->getParam('selectDpid','');
		if($selectDpid==''){
			$selectDpid = $this->companyId;
		}
		$download = Yii::app()->request->getParam('d',0);
		
		$sql = 'select DATE_FORMAT(sts.create_at,"%Y-%m-%d") as create_at,sts.type,sts.material_id,sts.sales_name,sts.prestock_taking_num,sts.stockin_num,sts.stockin_price,sts.damage_num,sts.damage_price,sts.salse_num,sts.salse_price,sts.total_num,sts.system_num,sts.stock_taking_num,sts.stock_taking_difnum,sts.stock_taking_difprice,pm.material_name,pm.material_identifier from nb_stock_taking_statistics sts left join nb_product_material pm on sts.material_id=pm.lid and sts.dpid=pm.dpid';
		$sql .= ' where sts.type=1 and sts.dpid='.$selectDpid.' and sts.create_at like "'.$begin_time.'%"';
		if($categoryId){
			$sql .= ' and pm.category_id='.$categoryId;
		}
		if($codename!=''){
			$sql .= ' and pm.material_identifier like "%'.$codename.'%"';
		}
		if($matename!=''){
			$sql .= ' and pm.material_name like "%'.$matename.'%"';
		}
		$sql .= ' order by pm.material_identifier asc';
		$sqlmodels = Yii::app()->db->createCommand($sql)->queryAll();
		if($download){
			$exportData = array();
			$tableHeader = array('时间','原料编码','名称','销售单位','昨日库存','入库总量','进货总量','进货成本','配送量','调拨量','损耗总量','损耗成本','销售出库','销售成本','总消耗量','系统库存','盘点库存','损溢总量','损溢成本');
			foreach ($sqlmodels as $m){
				$tempArr = array(
								$m['create_at'],
								$m['material_identifier'].' ',
								$m['material_name'],
								$m['sales_name'],
								$m['prestock_taking_num'],
								$m['stockin_num'],
								$m['stockin_num'],
								$m['stockin_price'],
								'','',
								$m['damage_num'],
								$m['damage_price'],
								$m['salse_num'],
								$m['salse_price'],
								$m['total_num'],
								$m['system_num'],
								$m['stock_taking_num'],
								$m['stock_taking_difnum'],
								$m['stock_taking_difprice']
							);
				array_push($exportData, $tempArr);
			}
			Helper::exportExcel($tableHeader,$exportData,'进销存日报报表','进销存日报');
			exit;
		}
		$categories = $this->getCategories();
		$this->render('stockReport',array(
				'sqlmodels'=>$sqlmodels,
				//'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'selectDpid'=>$selectDpid,
				'codename'=>$codename,
				'matename'=>$matename,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
		));
	}
	public function actionStockweekReport(){
		$categoryId = Yii::app()->request->getParam('cid',0);
		$text = Yii::app()->request->getParam('text',1);
		$codename = Yii::app()->request->getParam('codename','');
		$matename = Yii::app()->request->getParam('matename','');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$selectDpid = Yii::app()->request->getParam('selectDpid','');
		if($selectDpid==''){
			$selectDpid = $this->companyId;
		}
		$download = Yii::app()->request->getParam('d',0);
		
		$sql = 'select DATE_FORMAT(sts.create_at,"%Y-%m-%d") as create_at,sts.type,sts.material_id,sts.sales_name,sts.prestock_taking_num,sts.stockin_num,sts.stockin_price,sts.damage_num,sts.damage_price,sts.salse_num,sts.salse_price,sts.total_num,sts.system_num,sts.stock_taking_num,sts.stock_taking_difnum,sts.stock_taking_difprice,pm.material_name,pm.material_identifier from nb_stock_taking_statistics sts left join nb_product_material pm on sts.material_id=pm.lid and sts.dpid=pm.dpid';
		$sql .= ' where sts.type=2 and sts.dpid='.$selectDpid.' and sts.create_at like "'.$begin_time.'%"';
		if($categoryId){
			$sql .= ' and pm.category_id='.$categoryId;
		}
		if($codename!=''){
			$sql .= ' and pm.material_identifier like "%'.$codename.'%"';
		}
		if($matename!=''){
			$sql .= ' and pm.material_name like "%'.$matename.'%"';
		}
		$sql .= ' order by pm.material_identifier asc';
		$sqlmodels = Yii::app()->db->createCommand($sql)->queryAll();
		if($download){
			$exportData = array();
			$tableHeader = array('时间','原料编码','名称','销售单位','上周库存','入库总量','进货总量','进货成本','配送量','调拨量','损耗总量','损耗成本','销售出库','销售成本','总消耗量','系统库存','盘点库存','损溢总量','损溢成本');
			foreach ($sqlmodels as $m){
				$tempArr = array(
						$m['create_at'],
						$m['material_identifier'].' ',
						$m['material_name'],
						$m['sales_name'],
						$m['prestock_taking_num'],
						$m['stockin_num'],
						$m['stockin_num'],
						$m['stockin_price'],
						'','',
						$m['damage_num'],
						$m['damage_price'],
						$m['salse_num'],
						$m['salse_price'],
						$m['total_num'],
						$m['system_num'],
						$m['stock_taking_num'],
						$m['stock_taking_difnum'],
						$m['stock_taking_difprice']
				);
				array_push($exportData, $tempArr);
			}
			Helper::exportExcel($tableHeader,$exportData,'进销存周报报表','进销存周报');
			exit;
		}
		
		$categories = $this->getCategories();
		$this->render('stockweekReport',array(
				'sqlmodels'=>$sqlmodels,
				//'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'selectDpid'=>$selectDpid,
				'codename'=>$codename,
				'matename'=>$matename,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
		));
	}
	public function actionStockmonthReport(){
		$categoryId = Yii::app()->request->getParam('cid',0);
		$text = Yii::app()->request->getParam('text');
		$codename = Yii::app()->request->getParam('codename','');
		$matename = Yii::app()->request->getParam('matename','');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$begin_time = date('Y-m',strtotime($begin_time));
		$selectDpid = Yii::app()->request->getParam('selectDpid','');
		if($selectDpid==''){
			$selectDpid = $this->companyId;
		}
		$download = Yii::app()->request->getParam('d',0);
		
		$sql = 'select DATE_FORMAT(sts.create_at,"%Y-%m") as create_at,sts.type,sts.material_id,sts.sales_name,sts.prestock_taking_num,sts.stockin_num,sts.stockin_price,sts.damage_num,sts.damage_price,sts.salse_num,sts.salse_price,sts.total_num,sts.system_num,sts.stock_taking_num,sts.stock_taking_difnum,sts.stock_taking_difprice,pm.material_name,pm.material_identifier from nb_stock_taking_statistics sts left join nb_product_material pm on sts.material_id=pm.lid and sts.dpid=pm.dpid';
		$sql .= ' where sts.type=3 and sts.dpid='.$selectDpid.' and sts.create_at like "'.$begin_time.'%"';
		if($categoryId){
			$sql .= ' and pm.category_id='.$categoryId;
		}
		if($codename!=''){
			$sql .= ' and pm.material_identifier like "%'.$codename.'%"';
		}
		if($matename!=''){
			$sql .= ' and pm.material_name like "%'.$matename.'%"';
		}
		$sql .= ' order by pm.material_identifier asc';
		$sqlmodels = Yii::app()->db->createCommand($sql)->queryAll();
		if($download){
			$exportData = array();
			$tableHeader = array('时间','原料编码','名称','销售单位','上月库存','入库总量','进货总量','进货成本','配送量','调拨量','损耗总量','损耗成本','销售出库','销售成本','总消耗量','系统库存','盘点库存','损溢总量','损溢成本');
			foreach ($sqlmodels as $m){
				$tempArr = array(
						$m['create_at'],
						$m['material_identifier'].' ',
						$m['material_name'],
						$m['sales_name'],
						$m['prestock_taking_num'],
						$m['stockin_num'],
						$m['stockin_num'],
						$m['stockin_price'],
						'','',
						$m['damage_num'],
						$m['damage_price'],
						$m['salse_num'],
						$m['salse_price'],
						$m['total_num'],
						$m['system_num'],
						$m['stock_taking_num'],
						$m['stock_taking_difnum'],
						$m['stock_taking_difprice']
				);
				array_push($exportData, $tempArr);
			}
			Helper::exportExcel($tableHeader,$exportData,'进销存月报报表','进销存月报');
			exit;
		}
		
		$categories = $this->getCategories();
		$this->render('stockmonthReport',array(
				'sqlmodels'=>$sqlmodels,
				//'pages'=>$pages,
				'begin_time'=>$begin_time,
				'text'=>$text,
				'selectDpid'=>$selectDpid,
				'codename'=>$codename,
				'matename'=>$matename,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
		));
	}	
	/**
	 * 进销存汇总
	 */
	public function actionStockallReport(){
		$categoryId = Yii::app()->request->getParam('cid',0);
		$codename = Yii::app()->request->getParam('codename','');
		$matename = Yii::app()->request->getParam('matename','');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$selectDpid = Yii::app()->request->getParam('selectDpid','');
		if($selectDpid==''){
			$selectDpid = $this->companyId;
		}
		$download = Yii::app()->request->getParam('d',0);
		
		$sql = 'select DATE_FORMAT(sts.create_at,"%Y-%m-%d") as create_at,sts.type,sts.material_id,sts.sales_name,sts.prestock_taking_num,sts.stockin_num,sts.stockin_price,sts.damage_num,sts.damage_price,sts.salse_num,sts.salse_price,sts.total_num,sts.system_num,sts.stock_taking_num,sts.stock_taking_difnum,sts.stock_taking_difprice,pm.material_name,pm.material_identifier from nb_stock_taking_statistics sts left join nb_product_material pm on sts.material_id=pm.lid and sts.dpid=pm.dpid';
		$sql .= ' where sts.dpid='.$selectDpid.' and sts.create_at >= "'.$begin_time.' 00:00:00" and sts.create_at <= "'.$end_time.' 23:59:59"';
		if($categoryId){
			$sql .= ' and pm.category_id='.$categoryId;
		}
		if($codename!=''){
			$sql .= ' and pm.material_identifier like "%'.$codename.'%"';
		}
		if($matename!=''){
			$sql .= ' and pm.material_name like "%'.$matename.'%"';
		}
		$sql .= ' order by pm.material_identifier asc';
		$sqlmodels = Yii::app()->db->createCommand($sql)->queryAll();
		if($download){
			$exportData = array();
			$tableHeader = array('时间','类型','原料编码','名称','销售单位','上期库存','入库总量','进货总量','进货成本','配送量','调拨量','损耗总量','损耗成本','销售出库','销售成本','总消耗量','系统库存','盘点库存','损溢总量','损溢成本');
			foreach ($sqlmodels as $m){
				$typeStr = '';
				if($m['type']==1){
					$typeStr = '日报';
				}elseif($m['type']==2){
					$typeStr = '周报';
				}else{
					$typeStr = '月报';
				}
				$tempArr = array(
						$m['create_at'],
						$typeStr,
						$m['material_identifier'].' ',
						$m['material_name'],
						$m['sales_name'],
						$m['prestock_taking_num'],
						$m['stockin_num'],
						$m['stockin_num'],
						$m['stockin_price'],
						'','',
						$m['damage_num'],
						$m['damage_price'],
						$m['salse_num'],
						$m['salse_price'],
						$m['total_num'],
						$m['system_num'],
						$m['stock_taking_num'],
						$m['stock_taking_difnum'],
						$m['stock_taking_difprice']
				);
				array_push($exportData, $tempArr);
			}
			Helper::exportExcel($tableHeader,$exportData,'进销存综合报表','进销存综合报表');
			exit;
		}
		
		$categories = $this->getCategories();
		$this->render('stockallReport',array(
				'sqlmodels'=>$sqlmodels,
				//'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'selectDpid'=>$selectDpid,
				'codename'=>$codename,
				'matename'=>$matename,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
		));
	}
	/**
	 * 库存差异报表
	 */
	public function actionStockdifferReport(){
		$categoryId = Yii::app()->request->getParam('cid',0);
		$text = Yii::app()->request->getParam('text');
		$codename = Yii::app()->request->getParam('codename','');
		$matename = Yii::app()->request->getParam('matename','');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$selectDpid = Yii::app()->request->getParam('selectDpid','');
		if($selectDpid==''){
			$selectDpid = $this->companyId;
		}
		$download = Yii::app()->request->getParam('d',0);
		
		$sql = 'select DATE_FORMAT(sts.create_at,"%Y-%m-%d") as create_at,sts.type,sts.material_id,sts.sales_name,sts.system_num,sts.stock_taking_num,sts.stock_taking_difnum,sts.stock_taking_difprice,pm.material_name,pm.material_identifier from nb_stock_taking_statistics sts left join nb_product_material pm on sts.material_id=pm.lid and sts.dpid=pm.dpid';
		$sql .= ' where sts.dpid='.$selectDpid.' and sts.create_at >= "'.$begin_time.' 00:00:00" and sts.create_at <= "'.$end_time.' 23:59:59"';
		if($categoryId){
			$sql .= ' and pm.category_id='.$categoryId;
		}
		if($codename!=''){
			$sql .= ' and pm.material_identifier like "%'.$codename.'%"';
		}
		if($matename!=''){
			$sql .= ' and pm.material_name like "%'.$matename.'%"';
		}
		$sql .= ' order by pm.material_identifier asc';
		$sqlmodels = Yii::app()->db->createCommand($sql)->queryAll();
		if($download){
			$exportData = array();
			$tableHeader = array('时间','类型','原料编码','名称','销售单位','系统库存','盘点库存','损溢总量','损溢成本');
			foreach ($sqlmodels as $m){
				$typeStr = '';
				if($m['type']==1){
					$typeStr = '日报';
				}elseif($m['type']==2){
					$typeStr = '周报';
				}else{
					$typeStr = '月报';
				}
				$tempArr = array(
						$m['create_at'],
						$typeStr,
						$m['material_identifier'].' ',
						$m['material_name'],
						$m['sales_name'],
						$m['system_num'],
						$m['stock_taking_num'],
						$m['stock_taking_difnum'],
						$m['stock_taking_difprice']
				);
				array_push($exportData, $tempArr);
			}
			Helper::exportExcel($tableHeader,$exportData,'进销存损益报表','进销存损益报表');
			exit;
		}
		
		$categories = $this->getCategories();
		$this->render('stockdifferReport',array(
				'sqlmodels'=>$sqlmodels,
				//'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'selectDpid'=>$selectDpid,
				'codename'=>$codename,
				'matename'=>$matename,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
		));
	}	
	/**
	 * 库存销售报表
	 */
	public function actionStocksalesReport(){
		$dpid = $this->companyId;
		$categoryId = Yii::app()->request->getParam('cid',0);
		$download = Yii::app()->request->getParam('d',0);
		$codename = Yii::app()->request->getParam('codename','');
		$matename = Yii::app()->request->getParam('matename','');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$selectDpid = Yii::app()->request->getParam('selectDpid','');
		if($selectDpid==''){
			$selectDpid = $this->companyId;
		}
		
		$sql = 'select msl.material_id,msl.type,sum(msl.stock_num) as stock_num,sum(msl.stock_num*msl.unit_price) as price from nb_material_stock_log msl,nb_product_material pm where msl.material_id=pm.lid and msl.dpid=pm.dpid and msl.dpid='.$selectDpid.' and msl.create_at >= "'.$begin_time.' 00:00:00" and msl.create_at <= "'.$end_time.' 23:59:59" and msl.delete_flag=0';
		if($categoryId){
			$sql .= ' and pm.mchs_code='.$categoryId;
		}
		if($codename!=''){
			$sql .= ' and pm.material_identifier like "%'.$codename.'%"';
		}
		if($matename!=''){
			$sql .= ' and pm.material_name like "%'.$matename.'%"';
		}
		$sql .= ' group by msl.type,msl.material_id';
		$models = Yii::app ()->db->createCommand ( $sql )->queryAll();
		$results = array();
		foreach ($models as $model){
			$materialId = $model['material_id'];
			$materialType = $model['type'];
			if(isset($results[$materialId])){
				if($materialType==1){
					$results[$materialId]['tangshi_stock'] = $model['stock_num'];
					$results[$materialId]['tangshi_price'] = number_format($model['price'],2);
				}elseif ($materialType==2){
					$results[$materialId]['waimai_stock'] = $model['stock_num'];
					$results[$materialId]['waimai_price'] = number_format($model['price'],2);
				}elseif ($materialType==4){
					$results[$materialId]['pansun_stock'] = $model['stock_num'];
					$results[$materialId]['pansun_price'] = number_format($model['price'],2);
				}else{
					$results[$materialId]['pandian_stock'] = $model['stock_num'];
					$results[$materialId]['pandian_price'] = number_format($model['price'],2);
				}
			}else{
				$model['tangshi_stock'] = 0;
				$model['tangshi_price'] = 0;
				$model['waimai_stock'] = 0;
				$model['waimai_price'] = 0;
				$model['pansun_stock'] = 0;
				$model['pansun_price'] = 0;
				$model['pandian_stock'] = 0;
				$model['pandian_price'] = 0;
				if($materialType==1){
					$model['tangshi_stock'] = $model['stock_num'];
					$model['tangshi_price'] = number_format($model['price'],2);
				}elseif ($materialType==2){
					$model['waimai_stock'] = $model['stock_num'];
					$model['waimai_price'] = number_format($model['price'],2);
				}elseif ($materialType==4){
					$model['pansun_stock'] = $model['stock_num'];
					$model['pansun_price'] = number_format($model['price'],2);
				}else{
					$model['pandian_stock'] = $model['stock_num'];
					$model['pandian_price'] = number_format($model['price'],2);
				}
				$materUnit = Common::getmaterialUnit($materialId, $selectDpid, 1);
				$model['material_name'] = $materUnit['material_name'];
				$model['material_identifier'] = $materUnit['material_identifier'];
				$model['unit_name'] = $materUnit['unit_name'];
				$model['unit_specifications'] = $materUnit['unit_specifications'];
				$results[$materialId] = $model;
			}
		}
		if($download){
			$exportData = array();
			$tableHeader = array('原料编码','原料名称','原料单位','原料规格','堂食用量','堂食成本','外卖用量','外卖成本','盘损用量','盘损成本','用量汇总','汇总成本');
			foreach ($results as $m){
				$tsStock = $m['tangshi_stock'];
				$tsPrice = $m['tangshi_price'];
				$wmStock = $m['waimai_stock'];
				$wmPrice = $m['waimai_price'];
				$psStock = $m['pansun_stock'];
				$psPrice = $m['pansun_price'];
				$pdStock = $m['pandian_stock'];
				$pdPrice = $m['pandian_price'];
				$tempArr = array(
						$m['material_identifier'].' ',
						$m['material_name'],
						$m['unit_name'],
						$m['unit_specifications'],
						$tsStock,
						$tsPrice,
						$wmStock,
						$wmPrice,
						$psStock,
						$psPrice,
						$tsStock+$wmStock+$psStock,
						$tsPrice+$wmPrice+$psPrice
				);
				array_push($exportData, $tempArr);
			}
			Helper::exportExcel($tableHeader,$exportData,'原料消耗报表','原料消耗报表');
			exit;
		}
		
		$categories = $this->getCategories();
		$this->render('stocksalesReport',array(
				'models'=>$results,
				//'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'codename'=>$codename,
				'matename'=>$matename,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
				'selectDpid'=>$selectDpid
		));
	}
	/**
	 * 盘损报表
	 */
	public function actionInventoryReport(){
		$begintime = Yii::app()->request->getPost('begintime',date('Y-m-d',time()));
		$endtime = Yii::app()->request->getPost('endtime',date('Y-m-d',time()));
		$reasonid = Yii::app()->request->getPost('reasonid',0);
		$selectDpid = Yii::app()->request->getParam('selectDpid','');
		if($selectDpid==''){
			$selectDpid = $this->companyId;
		}
		
		$beginTime = $begintime.' 00:00:00';
		$endTime = $endtime.' 23:59:59';
		
		$sql = 'select t.*,t1.opretion_id,t1.reason_id from nb_inventory_detail t,nb_inventory t1 where t.inventory_id=t1.lid and t.dpid=t1.dpid and t.dpid='.$selectDpid.' and t1.create_at>="'.$beginTime.'" and t1.create_at<="'.$endTime.'" and t1.status=1';
		if($reasonid){
			$sql .= ' and t1.reason_id='.$reasonid;
		}
		$sql = 'select lid,dpid,opretion_id,type,material_id,reason_id,sum(inventory_stock) as inventory_stock from ('.$sql.')m group by type,material_id';
		$models = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($models as $key=>$model){
			$materialId = $model['material_id'];
			$reasonId = $model['reason_id'];
			$mtype = $model['type'];
			if($mtype==1){
				$material = Common::getmaterialUnit($materialId, $selectDpid, 0);
				$models[$key]['material_name'] = $material['material_name'];
				$models[$key]['material_identifier'] = $material['material_identifier'];
				$models[$key]['unit_name'] = $material['unit_name'];
				$models[$key]['unit_specifications'] = $material['unit_specifications'];
			}else{
				$productName = Common::getproductName($materialId);
				$models[$key]['material_name'] = $productName;
				$models[$key]['unit_name'] = '个';
				$models[$key]['unit_specifications'] = '个';
			}
		}
		
		$retreats = $this->getRetreats($selectDpid);
		$this->render('inventoryreport',array(
				'models'=>$models,
				'begintime'=>$begintime,
				'endtime'=>$endtime,
				'reasonid'=>$reasonid,
				'selectDpid'=>$selectDpid,
				'retreats'=>$retreats
		));
	}
	public function actionAjaxGetRetreat(){
		$sdpid = Yii::app()->request->getParam('sdpid',$this->companyId);
		$restreat = $this->getRetreats($sdpid);
		echo json_encode($restreat);exit;
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
					$options[$model->pid][$model->mchs_code] = $model->category_name;
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
	private function getRetreats($dpid){
		$sql = 'select lid,name from nb_retreat where dpid='.$dpid.' and type=2 and delete_flag=0';
		$retreats = Yii::app()->db->createCommand($sql)->queryAll();
		return $retreats;
	}
}