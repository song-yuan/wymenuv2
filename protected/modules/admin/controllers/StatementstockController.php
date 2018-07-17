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
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text',1);
		$codename = Yii::app()->request->getParam('codename','');
		$matename = Yii::app()->request->getParam('matename','');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		
		$sql = 'select DATE_FORMAT(sts.create_at,"%Y-%m-%d") as create_at,sts.type,sts.material_id,sts.sales_name,sts.prestock_taking_num,sts.stockin_num,sts.stockin_price,sts.damage_num,sts.damage_price,sts.salse_num,sts.salse_price,sts.total_num,sts.system_num,sts.stock_taking_num,sts.stock_taking_difnum,sts.stock_taking_difprice,pm.material_name,pm.material_identifier from nb_stock_taking_statistics sts left join nb_product_material pm on sts.material_id=pm.lid and sts.dpid=pm.dpid';
		$sql .= ' where sts.type=1 and sts.dpid='.$this->companyId.' and sts.create_at like "'.$begin_time.'%"';
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
				'str'=>$str,
				'codename'=>$codename,
				'matename'=>$matename,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
		));
	}
	public function actionStockweekReport(){
		$categoryId = Yii::app()->request->getParam('cid',0);
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text',1);
		$codename = Yii::app()->request->getParam('codename','');
		$matename = Yii::app()->request->getParam('matename','');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
	
		$sql = 'select DATE_FORMAT(sts.create_at,"%Y-%m-%d") as create_at,sts.type,sts.material_id,sts.sales_name,sts.prestock_taking_num,sts.stockin_num,sts.stockin_price,sts.damage_num,sts.damage_price,sts.salse_num,sts.salse_price,sts.total_num,sts.system_num,sts.stock_taking_num,sts.stock_taking_difnum,sts.stock_taking_difprice,pm.material_name,pm.material_identifier from nb_stock_taking_statistics sts left join nb_product_material pm on sts.material_id=pm.lid and sts.dpid=pm.dpid';
		$sql .= ' where sts.type=2 and sts.dpid='.$this->companyId.' and sts.create_at like "'.$begin_time.'%"';
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
		$this->render('stockweekReport',array(
				'sqlmodels'=>$sqlmodels,
				//'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'str'=>$str,
				'codename'=>$codename,
				'matename'=>$matename,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
		));
	}
	public function actionStockmonthReport(){
		$categoryId = Yii::app()->request->getParam('cid',0);
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$codename = Yii::app()->request->getParam('codename','');
		$matename = Yii::app()->request->getParam('matename','');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m',time()));
		
		$sql = 'select DATE_FORMAT(sts.create_at,"%Y-%m") as create_at,sts.type,sts.material_id,sts.sales_name,sts.prestock_taking_num,sts.stockin_num,sts.stockin_price,sts.damage_num,sts.damage_price,sts.salse_num,sts.salse_price,sts.total_num,sts.system_num,sts.stock_taking_num,sts.stock_taking_difnum,sts.stock_taking_difprice,pm.material_name,pm.material_identifier from nb_stock_taking_statistics sts left join nb_product_material pm on sts.material_id=pm.lid and sts.dpid=pm.dpid';
		$sql .= ' where sts.type=3 and sts.dpid='.$this->companyId.' and sts.create_at like "'.$begin_time.'%"';
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
				'str'=>$str,
				'codename'=>$codename,
				'matename'=>$matename,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
		));
	}	
	
	public function actionStockallReport(){
		$categoryId = Yii::app()->request->getParam('cid',0);
		$str = Yii::app()->request->getParam('str');
		$codename = Yii::app()->request->getParam('codename','');
		$matename = Yii::app()->request->getParam('matename','');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		
		
		$sql = 'select DATE_FORMAT(sts.create_at,"%Y-%m-%d") as create_at,sts.type,sts.material_id,sts.sales_name,sts.prestock_taking_num,sts.stockin_num,sts.stockin_price,sts.damage_num,sts.damage_price,sts.salse_num,sts.salse_price,sts.total_num,sts.system_num,sts.stock_taking_num,sts.stock_taking_difnum,sts.stock_taking_difprice,pm.material_name,pm.material_identifier from nb_stock_taking_statistics sts left join nb_product_material pm on sts.material_id=pm.lid and sts.dpid=pm.dpid';
		$sql .= ' where sts.dpid='.$this->companyId.' and sts.create_at >= "'.$begin_time.'" and sts.create_at <= "'.$end_time.'"';
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
		$this->render('stockallReport',array(
				'sqlmodels'=>$sqlmodels,
				//'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'str'=>$str,
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
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$codename = Yii::app()->request->getParam('codename','');
		$matename = Yii::app()->request->getParam('matename','');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
	
		$sql = 'select DATE_FORMAT(sts.create_at,"%Y-%m-%d") as create_at,sts.type,sts.material_id,sts.sales_name,sts.salse_num,sts.salse_price,sts.stock_taking_difnum,sts.stock_taking_difprice,pm.material_name,pm.material_identifier from nb_stock_taking_statistics sts left join nb_product_material pm on sts.material_id=pm.lid and sts.dpid=pm.dpid';
		$sql .= ' where sts.dpid='.$this->companyId.' and sts.create_at >= "'.$begin_time.' 00:00:00" and sts.create_at <= "'.$end_time.' 23:59:59"';
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
		$this->render('stockdifferReport',array(
				'sqlmodels'=>$sqlmodels,
				//'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'str'=>$str,
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
		
		$sql = 'select DATE_FORMAT(sts.create_at,"%Y-%m-%d") as create_at,sts.type,sts.material_id,sts.sales_name,sts.salse_num,sts.salse_price,pm.material_name,pm.material_identifier from nb_stock_taking_statistics sts left join nb_product_material pm on sts.material_id=pm.lid and sts.dpid=pm.dpid';
		$sql .= ' where sts.dpid='.$this->companyId.' and sts.create_at >= "'.$begin_time.' 00:00:00" and sts.create_at <= "'.$end_time.' 23:59:59"';
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
		$result = Yii::app ()->db->createCommand ( $sql )->queryAll ();
	
		$categories = $this->getCategories();
		$this->render('stocksalesReport',array(
				'sqlmodels'=>$result,
				//'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'codename'=>$codename,
				'matename'=>$matename,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
		));
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