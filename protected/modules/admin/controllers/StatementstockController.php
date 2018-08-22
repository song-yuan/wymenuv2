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
		$sql .= ' order by sts.lid desc';
		$sqlmodels = Yii::app()->db->createCommand($sql)->queryAll();
		
		//var_dump($models);exit;
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
		$sql .= ' order by sts.lid desc';
		$sqlmodels = Yii::app()->db->createCommand($sql)->queryAll();
	
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
		$sql .= ' order by sts.lid desc';
		$sqlmodels = Yii::app()->db->createCommand($sql)->queryAll();
		
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
		$sql .= ' order by sts.lid desc';
		$sqlmodels = Yii::app()->db->createCommand($sql)->queryAll();
	
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
		
		$sql = 'select DATE_FORMAT(sts.create_at,"%Y-%m-%d") as create_at,sts.type,sts.material_id,sts.sales_name,sts.salse_num,sts.salse_price,sts.stock_taking_difnum,sts.stock_taking_difprice,pm.material_name,pm.material_identifier from nb_stock_taking_statistics sts left join nb_product_material pm on sts.material_id=pm.lid and sts.dpid=pm.dpid';
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
		$sql .= ' order by sts.lid desc';
		$sqlmodels = Yii::app()->db->createCommand($sql)->queryAll();
	
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
		$codename = Yii::app()->request->getParam('codename','');
		$matename = Yii::app()->request->getParam('matename','');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$selectDpid = Yii::app()->request->getParam('selectDpid','');
		if($selectDpid==''){
			$selectDpid = $this->companyId;
		}
		
		$sql = 'select t.material_id,t.type,sum(t.stock_num) as stock_num,sum(t.stock_num*t.unit_price) as price,t1.material_name,t1.material_identifier from nb_material_stock_log t,nb_product_material t1 where t.material_id=t1.lid and t.dpid=t1.dpid and  t.dpid='.$selectDpid.' and t.create_at >= "'.$begin_time.' 00:00:00" and t.create_at <= "'.$end_time.' 23:59:59" and t.delete_flag=0';
		if($categoryId){
			$sql .= ' and t1.category_id='.$categoryId;
		}
		if($codename!=''){
			$sql .= ' and t1.material_identifier like "%'.$codename.'%"';
		}
		if($matename!=''){
			$sql .= ' and t1.material_name like "%'.$matename.'%"';
		}
		$sql .= ' group by t.type,t.material_id order by t1.material_identifier asc';
		$models = Yii::app ()->db->createCommand ( $sql )->queryAll();
		$results = array();
		foreach ($models as $model){
			$materialId = $model['material_id'];
			$materialType = $model['type'];
			if(isset($results[$materialId])){
				if($materialType==1){
					$results[$materialId]['tangshi_stock'] = $model['stock_num'];
				}elseif ($materialType==2){
					$results[$materialId]['waimai_stock'] = $model['stock_num'];
				}elseif ($materialType==4){
					$results[$materialId]['pansun_stock'] = $model['stock_num'];
				}else{
					$results[$materialId]['pandian_stock'] = $model['stock_num'];
				}
			}else{
				if($materialType==1){
					$model['tangshi_stock'] = $model['stock_num'];
				}elseif ($materialType==2){
					$model['waimai_stock'] = $model['stock_num'];
				}elseif ($materialType==4){
					$model['pansun_stock'] = $model['stock_num'];
				}else{
					$model['pandian_stock'] = $model['stock_num'];
				}
				$materUnit = Common::getmaterialUnit($materialId, $selectDpid, 1);
				if($materUnit){
					$model['unit_name'] = $materUnit['unit_name'];
					$model['unit_specifications'] = $materUnit['unit_specifications'];
				}else{
					$model['unit_name'] = '';
					$model['unit_specifications'] = '';
				}
				$results[$materialId] = $model;
			}
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
	private function getRetreats($dpid){
		$sql = 'select lid,name from nb_retreat where dpid='.$dpid.' and type=2 and delete_flag=0';
		$retreats = Yii::app()->db->createCommand($sql)->queryAll();
		return $retreats;
	}
}