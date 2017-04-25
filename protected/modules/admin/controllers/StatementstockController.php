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
		$criteria = new CDbCriteria;
		$criteria->select ='year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,t.material_id,t.stock_num,sum(t.stock_num) as all_num';
		$criteria->condition = 't.dpid='.$this->companyId;
		$criteria->condition = 't.type=1';
		
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
				.'sum(pms.stock) as all_storagestock,sum(t.number) as all_sunyi_num,'
				//.'sum(sh.number) as all_pansun_num,'
				.'k.material_name,k.material_identifier,k.sales_unit_id,k.delete_flag as md,j.unit_name,j.delete_flag as mud,t.* '
				.'from nb_stock_taking_detail t '
				//.'left join nb_stock_taking_detail sh on(sh.material_id = t.material_id and t.status = 0 and sh.logid in(select sts.lid from nb_stock_taking sts where sts.status =1 and t.dpid ='.$this->companyId.' and t.create_at >=t.last_stock_time and t.create_at <="'.$end_time.' 23:59:59" ))'
				.'left join nb_product_material_stock pms on(pms.material_id = t.material_id and pms.create_at>=t.last_stock_time and pms.create_at<=t.create_at and t.dpid=pms.dpid and pms.delete_flag =0)'
				.'left join nb_product_material k on(t.material_id = k.lid and t.dpid = k.dpid) '
				.'left join nb_material_unit j on(j.lid = k.sales_unit_id and k.dpid=j.dpid) '
				.' where t.logid in(select st.lid from nb_stock_taking st where st.status =0 and t.dpid ='.$this->companyId.' and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" )'
				//.' and sh.logid in(select sts.lid from nb_stock_taking sts where sts.status =1 and t.dpid ='.$this->companyId.' and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" )'
				.' and t.material_id in(select pm.lid from nb_product_material pm where t.dpid ='.$this->companyId.') and t.dpid='.$this->companyId.' and t.status = 0 and t.delete_flag=0'
				.' and k.category_id'.$cateId
				.' and k.material_identifier '.$codenames.''
				.' and k.material_name '.$matenames.''
				.' group by t.lid order by year(t.create_at) asc';
		//echo $sql;exit;
		$sqlmodels = Yii::app()->db->createCommand($sql)->queryAll();
		if($str){
			$criteria->condition = 't.dpid in('.$str.')';
		}
		$criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.create_at <='$end_time 23:59:59'");
		if($text==1){
			$criteria->group ='year(t.create_at),t.material_id';
			$criteria->order = 'year(t.create_at) asc,t.dpid asc';
		}elseif($text==2){
			$criteria->group ='month(t.create_at),t.material_id';
			$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,t.dpid asc';
		}else{
			$criteria->group ='day(t.create_at),t.material_id';
			$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,day(t.create_at) asc,t.dpid asc';
		}
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