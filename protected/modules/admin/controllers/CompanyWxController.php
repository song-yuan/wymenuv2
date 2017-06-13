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
	public function actionStoretime(){
		$dpid = Yii::app()->request->getParam('companyId');
		$shop_time = Yii::app()->request->getParam('shop_time');
		$closing_time = Yii::app()->request->getParam('closing_time');
		//var_dump($dpid,$appid);exit;
	
		//****查询公司的产品分类。。。****
		$db = Yii::app()->db;
		$compros = CompanyProperty::model()->find('dpid=:companyId and delete_flag=0' , array(':companyId'=>$dpid));
		//var_dump($compros);
		if(!empty($compros)){
			$sql = 'update nb_company_property set update_at ="'.date('Y-m-d H:i:s',time()).'",shop_time ="'.$shop_time.'",closing_time ="'.$closing_time.'" where dpid ='.$dpid;
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
					'is_rest'=>'0',
					'shop_time'=>$shop_time,
					'closing_time'=>$closing_time,
					'delete_flag'=>'0',
			);
			//var_dump($data);exit;
			$command = $db->createCommand()->insert('nb_company_property',$data);
		}
		Yii::app()->end(json_encode(array("status"=>"success",'msg'=>'成功')));
	}
	
	public function actionCopyprice(){
		$dpid = Yii::app()->request->getParam('companyId');
		//var_dump($dpid,$appid);exit;
	
		//****查询公司的产品分类。。。****
		$db = Yii::app()->db;
		$compros = CompanyProperty::model()->find('dpid=:companyId and delete_flag=0' , array(':companyId'=>$dpid));
		
		$sql = 'select * from nb_company where dpid ='.$dpid;
		$company = $db->createCommand($sql)->queryRow();
		
		$sqlprod = 'select * from nb_product where dpid ='.$dpid.' and delete_flag =0 and is_temp_price =1';
		$prods = $db->createCommand($sqlprod)->queryAll();
		//var_dump($prods);exit;
		if(!empty($prods)){
			$sql = 'update nb_company_property set update_at ="'.date('Y-m-d H:i:s',time()).'",is_copyprice = 1 where dpid ='.$dpid;
			//var_dump($sql);exit;
			$command = $db->createCommand($sql);
			$command->execute();
			
			foreach ($prods as $prod){
				$sqlprodzb = 'select * from nb_product where dpid ='.$company['comp_dpid'].' and delete_flag =0 and phs_code ='.$prod['phs_code'];
				$prodzb = $db->createCommand($sqlprodzb)->queryRow();
				//var_dump($prodzb);exit; 
				if(!empty($prodzb)){
					if($prod['is_lock'] == '0' || $prodzb['original_price'] != $prod['original_price'] || $prodzb['member_price'] != $prod['member_price'] || $prodzb['is_show_wx'] != $prod['is_show_wx'] ){
						$sqlcopy = 'update nb_product set original_price ='.$prodzb['original_price'].',member_price ='.$prodzb['member_price'].',is_show_wx ='.$prodzb['is_show_wx'].',is_lock =1 where lid ='.$prod['lid'];
						$result = $db->createCommand($sqlcopy)->execute();
					}
				}
			}
		}
		
		Yii::app()->end(json_encode(array("status"=>true,'msg'=>'成功')));
	}
	
}