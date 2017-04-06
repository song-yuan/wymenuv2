<?php
class CompanyWxController extends BackendController
{
    
        public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId && $this->getAction()->getId() != 'upload') {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
    
	public function actionIndex(){
		$provinces = Yii::app()->request->getParam('province',0);
		$citys = Yii::app()->request->getParam('city',0);
		$areas = Yii::app()->request->getParam('area',0);
		
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
	
		$criteria = new CDbCriteria;
		$criteria->with = 'property';
		if(Yii::app()->user->role <= User::POWER_ADMIN_VICE)
		{
			$criteria->condition =' t.delete_flag=0 ';
		}else if(Yii::app()->user->role >= '5' && Yii::app()->user->role <= '9')
		{
			$criteria->condition =' t.delete_flag=0 and t.dpid in (select tt.dpid from nb_company tt where tt.comp_dpid='.Yii::app()->user->companyId.' and tt.delete_flag=0 ) or t.dpid='.Yii::app()->user->companyId;
		}else{
			$criteria->condition = ' t.delete_flag=0 and t.dpid='.Yii::app()->user->companyId ;
		}
		$province = $provinces;
		$city = $citys;
		$area = $areas;
		//var_dump($criteria);exit;
		if($citys == '市辖区'|| $citys == '省直辖县级行政区划' || $citys == '市辖县'){
			$city = '0';
		}
		if($areas == '市辖区'){
			$area = '0';
		}
		if($province){
			$criteria->addCondition('t.province like "'.$province.'"');
		}
		if($city){
			$criteria->addCondition('t.city like "'.$city.'"');
		}
		if($area){
			$criteria->addCondition('t.county_area like "'.$area.'"');
		}
		$criteria->order = 't.dpid asc';
		$pages = new CPagination(Company::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = Company::model()->findAll($criteria);
		$this->render('index',array(
				'models'=> $models,
				'pages'=>$pages,
				'province'=>$provinces,
				'city'=>$citys,
				'area'=>$areas,
		));
	}
	public function actionStore(){
		$dpid = Yii::app()->request->getParam('companyId');
		$rest = Yii::app()->request->getParam('rest');
		//var_dump($dpid,$appid);exit;
	
		//****查询公司的产品分类。。。****
		$db = Yii::app()->db;
		$compros = CompanyProperty::model()->find('dpid=:companyId and delete_flag=0' , array(':companyId'=>$dpid));
		//var_dump($compros);
		if(!empty($compros)){
			$sql = 'update nb_company_property set update_at ="'.date('Y-m-d H:i:s',time()).'",is_rest ="'.$rest.'" where dpid ='.$dpid;
			//var_dump($sql);exit;
			$command = $db->createCommand($sql);
			$command->execute();
		}else{
			$se = new Sequence("company_property");
			$id = $se->nextval();
			$data = array(
					'lid'=>$id,
					'dpid'=>$dpid,
					'create_at'=>date('Y-m-d H:i:s',time()),
					'update_at'=>date('Y-m-d H:i:s',time()),
					'pay_type'=>'1',
					'pay_channel'=>'2',
					'appId'=>'',
					'code'=>'',
					'is_rest'=>$rest,
					'delete_flag'=>'0',
			);
			//var_dump($data);exit;
			$command = $db->createCommand()->insert('nb_company_property',$data);
		}
		Yii::app()->end(json_encode(array("status"=>"success",'msg'=>'成功')));
	}
	
}