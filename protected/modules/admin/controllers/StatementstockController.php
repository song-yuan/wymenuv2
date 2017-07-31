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
		$text = Yii::app()->request->getParam('text');
		$codename = Yii::app()->request->getParam('codename','');
		$matename = Yii::app()->request->getParam('matename','');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		
		if($categoryId){
			$cateId = '='.$categoryId;
		}else{
			$cateId ='>0';
		}
		if($codename>=0&&!empty($codename)){
			$codenames = 'like"%'.$codename.'%"';
		}else{
			$codenames ='>=0';
		}
		if($matename>=0&&!empty($matename)){
			$matenames = 'like"%'.$matename.'%"';
		}else{
			$matenames ='>=0';
		}
		
		$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.sales_stocks,t.last_stock,t.last_stock_id,t.last_stock_time,'
				.'sum(pms.batch_stock) as all_storagestock,sum(t.number) as all_sunyi_num,'
				//.'sum(sh.number) as all_num,'
				.'k.material_name,k.material_identifier,k.sales_unit_id,k.delete_flag as md,j.unit_name,j.delete_flag as mud,t.* '
				.'from nb_stock_taking_detail t '
				//.'left join nb_stock_taking_detail sh on(t.lid=sh.lid)'
				.'left join nb_product_material_stock pms on(pms.material_id = t.material_id and pms.create_at>=t.last_stock_time and pms.create_at<=t.create_at and t.dpid=pms.dpid and pms.delete_flag =0)'
				.'left join nb_product_material k on(t.material_id = k.lid and t.dpid = k.dpid) '
				.'left join nb_material_unit j on(j.lid = k.sales_unit_id and k.dpid=j.dpid) '
				.' where t.logid in(select st.lid from nb_stock_taking st where st.status =0 and t.dpid ='.$this->companyId.' and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" )'
				//.' and sh.logid in(select sts.lid from nb_stock_taking sts where sts.status =1 and t.dpid ='.$this->companyId.' and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" )'
				.' and t.material_id in(select pm.lid from nb_product_material pm where t.dpid ='.$this->companyId.') and t.dpid='.$this->companyId.' and t.status = 0 and t.delete_flag=0'
				.' and k.category_id'.$cateId
				.' and k.material_identifier '.$codenames.''
				.' and k.material_name '.$matenames.''
				.' group by t.lid order by t.material_id,t.create_at asc';
		//echo $sql;exit;
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
	public function actionStockmonthReport(){
		$categoryId = Yii::app()->request->getParam('cid',0);
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$codename = Yii::app()->request->getParam('codename','');
		$matename = Yii::app()->request->getParam('matename','');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m',time()));
		
		$timearr = array();
		$timearr = explode('-',$begin_time);
		$yeartime = $timearr[0];
		$monthtime = $timearr[1];
		
		if($monthtime == '01'){
			$lastyt = $yeartime -1;
			$lastmt = '12';
		}else{
			$lastyt = $yeartime;
			$lastmt = $monthtime-1;
		}
		if($categoryId){
			$cateId = '='.$categoryId;
		}else{
			$cateId ='>0';
		}
		if($codename>=0&&!empty($codename)){
			$codenames = 'like"%'.$codename.'%"';
		}else{
			$codenames ='>=0';
		}
		if($matename>=0&&!empty($matename)){
			$matenames = 'like"%'.$matename.'%"';
		}else{
			$matenames ='>=0';
		}
		$stackids = '0';
		$staksql = 'select t.lid from nb_stock_taking t where t.status =0 and t.dpid ='.$this->companyId.' and year(t.create_at) ='.$lastyt.' and month(t.create_at) ='.$lastmt;
		$stakstocks = Yii::app()->db->createCommand($staksql)->queryAll();
		foreach ($stakstocks as $stakstock){
			$stackids = $stackids.','.$stakstock['lid'];
		}
		
		$mstackids = '0';
		$mstaksql = 'select t.lid from nb_stock_taking t where t.status =0 and t.dpid ='.$this->companyId.' and year(t.create_at) ='.$yeartime.' and month(t.create_at) ='.$monthtime;
		$mstakstocks = Yii::app()->db->createCommand($mstaksql)->queryAll();
		foreach ($mstakstocks as $mstakstock){
			$mstackids = $mstackids.','.$mstakstock['lid'];
		}
	
		$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.sales_stocks,t.last_stock,t.last_stock_id,t.last_stock_time,'
				.'sum(pms.batch_stock) as all_storagestock,sum(pms.stock_cost) as all_storageprice,'
				.'sum(t.number) as all_sunyi_num,'
				//.'sum(t.sales_stocks) as all_salestock,sum(t.sales_price) as all_salesprice,'
				//.'sum(t.demage_stock) as all_demagestock,sum(t.demage_price) as all_demageprice,'
				.'lms.taking_stock as lms_takingstock,'
				.'mms.taking_stock as mms_takingstock,'
				.'count(pms.lid) as re_num,'
				.'count(sy.lid) as re_psnum,'
				.'ps.all_sunyinum,'
				.'sh.all_demagestock,sh.all_demageprice,sh.all_salestock,sh.all_salesprice,'
				.'sum(sy.sales_price) as all_sunyi_price,'
				.'k.material_name,k.material_identifier,k.sales_unit_id,k.delete_flag as md,j.unit_name,j.delete_flag as mud,t.* '
				.'from nb_stock_taking_detail t '
				.'left join nb_stock_taking_detail lms on(lms.material_id = t.material_id and lms.lid=(select max(lmsl.lid) from nb_stock_taking_detail lmsl where lmsl.material_id = lms.material_id and lmsl.dpid='.$this->companyId.' and year(lmsl.create_at) ='.$lastyt.' and month(lmsl.create_at) ='.$lastmt.' and lmsl.status=0 and lmsl.logid in('.$stackids.')))'
				.'left join nb_stock_taking_detail mms on(mms.material_id = t.material_id and mms.lid=(select max(mmsl.lid) from nb_stock_taking_detail mmsl where mmsl.material_id = mms.material_id and mmsl.dpid='.$this->companyId.' and year(mmsl.create_at) ='.$yeartime.' and month(mmsl.create_at) ='.$monthtime.' and mmsl.status=0 and mmsl.logid in('.$mstackids.')))'
				.'left join nb_stock_taking_detail sy on(sy.detail_id = t.lid)'
				.'left join (select sum(pss.number) as all_sunyinum,pss.material_id from nb_stock_taking_detail pss where pss.dpid ='.$this->companyId.' and year(pss.create_at) ="'.$yeartime.'" and month(pss.create_at) ="'.$monthtime.'" and pss.type=0 and pss.status=0 group by pss.material_id) ps on(ps.material_id = t.material_id )'
				.'left join (select sum(shs.demage_stock) as all_demagestock,sum(shs.demage_price) as all_demageprice,sum(shs.sales_stocks) as all_salestock,sum(shs.sales_price) as all_salesprice,shs.material_id from nb_stock_taking_detail shs where shs.dpid ='.$this->companyId.' and year(shs.create_at) ="'.$yeartime.'" and month(shs.create_at) ="'.$monthtime.'" and shs.type=0 and shs.status=0 group by shs.material_id) sh on(sh.material_id = t.material_id )'
				.'left join nb_product_material_stock pms on(pms.material_id = t.material_id and pms.create_at>=t.last_stock_time and pms.create_at<=t.create_at and t.dpid=pms.dpid and pms.delete_flag =0)'
				.'left join nb_product_material k on(t.material_id = k.lid and t.dpid = k.dpid) '
				.'left join nb_material_unit j on(j.lid = k.sales_unit_id and k.dpid=j.dpid) '
				.' where t.logid in(select st.lid from nb_stock_taking st where st.status =0 and st.dpid ='.$this->companyId.' and year(st.create_at) ="'.$yeartime.'" and month(st.create_at) ="'.$monthtime.'" )'
				.' and t.material_id in(select pm.lid from nb_product_material pm where t.dpid ='.$this->companyId.') and t.dpid='.$this->companyId.' and t.status = 0 and t.delete_flag=0'
				.' and k.category_id'.$cateId
				.' and k.material_identifier '.$codenames.''
				.' and k.material_name '.$matenames.''
				.' group by t.material_id order by t.material_id,t.create_at desc';
		//echo $sql;exit;
		$sqlmodels = Yii::app()->db->createCommand($sql)->queryAll();
		
		//var_dump($models);exit;
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
		$text = Yii::app()->request->getParam('text');
		$codename = Yii::app()->request->getParam('codename','');
		$matename = Yii::app()->request->getParam('matename','');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		
		if($categoryId){
			$cateId = '='.$categoryId;
		}else{
			$cateId ='>0';
		}
		if($codename>=0&&!empty($codename)){
			$codenames = 'like"%'.$codename.'%"';
		}else{
			$codenames ='>=0';
		}
		if($matename>=0&&!empty($matename)){
			$matenames = 'like"%'.$matename.'%"';
		}else{
			$matenames ='>=0';
		}
		$bengin_times = date('Y-m-d 00:00:00',strtotime($begin_time.' -1 month'));
		//var_dump($bengin_times);exit;
		$stackids = '0';
		$staksql = 'select t.lid from nb_stock_taking t where t.status =0 and t.dpid ='.$this->companyId.' and t.create_at >="'.$bengin_times.' 00:00:00" and t.create_at <="'.$begin_time.' 23:59:59"';
		$stakstocks = Yii::app()->db->createCommand($staksql)->queryAll();
		foreach ($stakstocks as $stakstock){
			$stackids = $stackids.','.$stakstock['lid'];
		}
	
		$mstackids = '0';
		$mstaksql = 'select t.lid from nb_stock_taking t where t.status =0 and t.dpid ='.$this->companyId.' and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59"';
		$mstakstocks = Yii::app()->db->createCommand($mstaksql)->queryAll();
		foreach ($mstakstocks as $mstakstock){
			$mstackids = $mstackids.','.$mstakstock['lid'];
		}
	
		$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.sales_stocks,t.last_stock,t.last_stock_id,t.last_stock_time,'
				.'sum(pms.batch_stock) as all_storagestock,sum(pms.stock_cost) as all_storageprice,'
				.'sum(t.number) as all_sunyi_num,'
				.'sum(t.sales_stocks) as all_salestock,sum(t.sales_price) as all_salesprice,sum(t.demage_stock) as all_demagestock,sum(t.demage_price) as all_demageprice,'
				.'lms.taking_stock as lms_takingstock,'
				.'mms.taking_stock as mms_takingstock,'
				.'count(pms.lid) as re_num,'
				.'ps.all_sunyinum,'
				.'sum(sy.sales_price) as all_sunyi_price,'
				.'sh.all_demagestock,sh.all_demageprice,sh.all_salestock,sh.all_salesprice,'
				.'k.material_name,k.material_identifier,k.sales_unit_id,k.delete_flag as md,j.unit_name,j.delete_flag as mud,t.* '
				.'from nb_stock_taking_detail t '
				.'left join nb_stock_taking_detail lms on(lms.material_id = t.material_id and lms.lid=(select max(lmsl.lid) from nb_stock_taking_detail lmsl where lmsl.material_id = lms.material_id and lmsl.dpid='.$this->companyId.' and lmsl.create_at <="'.$begin_time.' 00:00:00" and lmsl.status=0 and lmsl.logid in('.$stackids.')))'
				.'left join nb_stock_taking_detail mms on(mms.material_id = t.material_id and mms.lid=(select max(mmsl.lid) from nb_stock_taking_detail mmsl where mmsl.material_id = mms.material_id and mmsl.dpid='.$this->companyId.' and mmsl.create_at >="'.$begin_time.' 00:00:00" and mmsl.create_at <="'.$end_time.' 23:59:59" and mmsl.status=0 and mmsl.logid in('.$mstackids.')))'
				.'left join nb_stock_taking_detail sy on(sy.detail_id = t.lid )'
				.'left join (select sum(pss.number) as all_sunyinum,pss.material_id from nb_stock_taking_detail pss where pss.dpid ='.$this->companyId.' and pss.create_at >="'.$begin_time.' 00:00:00" and pss.create_at <="'.$end_time.' 23:59:59" and pss.type=0 and pss.status=0 group by pss.material_id) ps on(ps.material_id = t.material_id )'
				.'left join (select sum(shs.demage_stock) as all_demagestock,sum(shs.demage_price) as all_demageprice,sum(shs.sales_stocks) as all_salestock,sum(shs.sales_price) as all_salesprice,shs.material_id from nb_stock_taking_detail shs where shs.dpid ='.$this->companyId.' and shs.create_at >="'.$begin_time.' 00:00:00" and shs.create_at <="'.$end_time.' 23:59:59" and shs.type=0 and shs.status=0 group by shs.material_id) sh on(sh.material_id = t.material_id )'
				.'left join nb_product_material_stock pms on(pms.material_id = t.material_id and pms.create_at>=t.last_stock_time and pms.create_at<=t.create_at and t.dpid=pms.dpid and pms.delete_flag =0)'
				.'left join nb_product_material k on(t.material_id = k.lid and t.dpid = k.dpid) '
				.'left join nb_material_unit j on(j.lid = k.sales_unit_id and k.dpid=j.dpid) '
				.' where t.logid in(select st.lid from nb_stock_taking st where st.status =0 and st.dpid ='.$this->companyId.' and st.create_at >="'.$begin_time.' 00:00:00" and st.create_at <="'.$end_time.' 23:59:59" )'
				.' and t.material_id in(select pm.lid from nb_product_material pm where t.dpid ='.$this->companyId.') and t.dpid='.$this->companyId.' and t.status = 0 and t.delete_flag=0'
				.' and k.category_id'.$cateId
				.' and k.material_identifier '.$codenames.''
				.' and k.material_name '.$matenames.''
				.' group by t.material_id order by t.material_id,t.create_at desc';
		//echo $sql;exit;
		$sqlmodels = Yii::app()->db->createCommand($sql)->queryAll();
	
		//var_dump($models);exit;
		$categories = $this->getCategories();
		$this->render('stockallReport',array(
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
	

	public function actionStockdifferReport(){
		$categoryId = Yii::app()->request->getParam('cid',0);
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$codename = Yii::app()->request->getParam('codename','');
		$matename = Yii::app()->request->getParam('matename','');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
	
		if($categoryId){
			$cateId = '='.$categoryId;
		}else{
			$cateId ='>0';
		}
		if($codename>=0&&!empty($codename)){
			$codenames = 'like"%'.$codename.'%"';
		}else{
			$codenames ='>=0';
		}
		if($matename>=0&&!empty($matename)){
			$matenames = 'like"%'.$matename.'%"';
		}else{
			$matenames ='>=0';
		}
		$stackids = '0';
		$staksql = 'select t.lid from nb_stock_taking t where t.status =0 and t.dpid ='.$this->companyId.' and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59"';
		$stakstocks = Yii::app()->db->createCommand($staksql)->queryAll();
		foreach ($stakstocks as $stakstock){
			$stackids = $stackids.','.$stakstock['lid'];
		}
	
		$mstackids = '0';
		$mstaksql = 'select t.lid from nb_stock_taking t where t.status =0 and t.dpid ='.$this->companyId.' and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59"';
		$mstakstocks = Yii::app()->db->createCommand($mstaksql)->queryAll();
		foreach ($mstakstocks as $mstakstock){
			$mstackids = $mstackids.','.$mstakstock['lid'];
		}
	
		$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.sales_stocks,t.last_stock,t.last_stock_id,t.last_stock_time,'
				.'sum(t.number) as all_sunyi_num,'
				.'count(pms.lid) as re_num,'
				.'ps.all_sunyinum,'
				.'sum(sy.sales_price) as all_sunyi_price,'
				.'sh.all_demagestock,sh.all_demageprice,sh.all_salestock,sh.all_salesprice,'
				.'k.material_name,k.material_identifier,k.sales_unit_id,k.delete_flag as md,j.unit_name,j.delete_flag as mud,t.* '
				.'from nb_stock_taking_detail t '
				.'left join nb_stock_taking_detail sy on(sy.detail_id = t.lid )'
				.'left join (select sum(pss.number) as all_sunyinum,pss.material_id from nb_stock_taking_detail pss where pss.dpid ='.$this->companyId.' and pss.create_at >="'.$begin_time.' 00:00:00" and pss.create_at <="'.$end_time.' 23:59:59" and pss.type=0 and pss.status=0 group by pss.material_id) ps on(ps.material_id = t.material_id )'
				.'left join (select sum(shs.demage_stock) as all_demagestock,sum(shs.demage_price) as all_demageprice,sum(shs.sales_stocks) as all_salestock,sum(shs.sales_price) as all_salesprice,shs.material_id from nb_stock_taking_detail shs where shs.dpid ='.$this->companyId.' and shs.create_at >="'.$begin_time.' 00:00:00" and shs.create_at <="'.$end_time.' 23:59:59" and shs.type=0 and shs.status=0 group by shs.material_id) sh on(sh.material_id = t.material_id )'
				.'left join nb_product_material_stock pms on(pms.material_id = t.material_id and pms.create_at>=t.last_stock_time and pms.create_at<=t.create_at and t.dpid=pms.dpid and pms.delete_flag =0)'
				.'left join nb_product_material k on(t.material_id = k.lid and t.dpid = k.dpid) '
				.'left join nb_material_unit j on(j.lid = k.sales_unit_id and k.dpid=j.dpid) '
				.' where t.logid in(select st.lid from nb_stock_taking st where st.status =0 and st.dpid ='.$this->companyId.' and st.create_at >="'.$begin_time.' 00:00:00" and st.create_at <="'.$end_time.' 23:59:59" )'
				.' and t.material_id in(select pm.lid from nb_product_material pm where t.dpid ='.$this->companyId.') and t.dpid='.$this->companyId.' and t.status = 0 and t.delete_flag=0'
				.' and k.category_id'.$cateId
				.' and k.material_identifier '.$codenames.''
				.' and k.material_name '.$matenames.''
				.' group by t.material_id order by t.material_id,t.create_at desc';
		//echo $sql;exit;
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
	
	public function actionStocksalesReport(){
		$dpid = $this->companyId;
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$text = 1;
		
		$sql = 'select t.material_id,sum(t.stock_num) as material_num,t1.material_name,t2.unit_name from nb_material_stock_log t left join nb_product_material t1 on t.material_id=t1.lid and t.dpid=t1.dpid left join nb_material_unit t2 on t1.sales_unit_id=t2.lid and t1.dpid=t2.dpid where t.dpid='.$dpid.' and t.create_at >= "'.$begin_time.'" and "'.$end_time.'" >= t.create_at and t.type=1 and t.material_id in(select k.lid from nb_product_material k where k.delete_flag = 0 and k.dpid = '.$dpid.') group by t.material_id';
		$result = Yii::app ()->db->createCommand ( $sql )->queryAll ();
	
		
		$this->render('stocksalesReport',array(
				'sqlmodels'=>$result,
				//'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
		));
	}

}