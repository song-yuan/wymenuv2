<?php
class PosfeeController extends BackendController
{
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex() {
		$ty=1;
        $model = PoscodeFeeset::model()->find('dpid=:dpid',array(':dpid'=>$this->companyId));
        if(empty($model)){
        	$model = new PoscodeFeeset;
        	$se=new Sequence("poscode_feeset");
        	$model->lid = $se->nextval();
        	$model->create_at = date('Y-m-d H:i:s',time());
            $model->update_at = date('Y-m-d H:i:s',time());
            $ty = 0;
        }
        if(Yii::app()->request->isPostRequest){
        	$postData = Yii::app()->request->getPost('PoscodeFeeset');
        	//var_dump($postData);exit;
        	$model->dpid = $this->companyId;
            $postData['update_at'] = date('Y-m-d H:i:s',time());
        	$model->attributes = $postData;
        	//var_dump($model);exit;
        	if($model->save()){
        		Yii::app()->user->setFlash('success' ,yii::t('app', '设置成功'));
        	}else{
        		Yii::app()->user->setFlash('error' ,yii::t('app', '失败'));
        	}
        }
		$this->render('index',array(
				'model'=>$model,
				'ty'=>$ty,
		));
	}
	public function actionSetindex(){
		$provinces = Yii::app()->request->getParam('province',0);
		$citys = Yii::app()->request->getParam('city',0);
		$areas = Yii::app()->request->getParam('area',0);
		$content = Yii::app()->request->getParam('content','');
		$role = Yii::app()->user->role;
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
	
		$criteria = new CDbCriteria;
		$criteria->with = 'posfee';
		if(Yii::app()->user->role < '5')
		{
			//if($this->companyId=='0000000001'){
				if ($content!='') {
					$criteria->condition =' t.delete_flag=0 and t.type=0';
				}else{
					//$criteria->condition =' t.delete_flag=0';
					$criteria->condition =' t.delete_flag=0 and t.dpid in (select tt.dpid from nb_company tt where tt.comp_dpid='.$this->companyId.' and tt.delete_flag=0 ) or t.dpid='.$this->companyId;
				}
// 			}else{
// 				$criteria->condition =' t.delete_flag=0 and t.dpid in (select tt.dpid from nb_company tt where tt.comp_dpid='.$this->companyId.' and tt.delete_flag=0 ) or t.dpid='.Yii::app()->user->companyId;
					
// 			}
			
		}else if(Yii::app()->user->role >= '5' && Yii::app()->user->role <= '9')
		{
			//var_dump(Yii::app()->user->role);exit;
			$criteria->condition =' t.delete_flag=0 and t.dpid in (select tt.dpid from nb_company tt where tt.comp_dpid='.Yii::app()->user->companyId.' and tt.delete_flag=0 ) or t.dpid='.Yii::app()->user->companyId;
		}else{
			$criteria->condition = ' t.delete_flag=0 and t.dpid='.Yii::app()->user->companyId ;
		}
		$province = $provinces;
		$city = $citys;
		$area = $areas;
		if($citys == '市辖区'|| $citys == '省直辖县级行政区划' || $citys == '市辖县'){
			$city = '0';
		}
		if($areas == '市辖区'){
			$area = '0';
		}
		if ($provinces == '请选择..') {
			$province = '';
		}
		if ($citys == '请选择..') {
			$city = '';
		}
		if ($areas == '请选择..') {
			$area = '';
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
		if ($content) {
			if (is_numeric($content)) {
				$criteria->addCondition('t.mobile like "%'.$content.'%"');
			}else{
				$criteria->addCondition('t.contact_name like "%'.$content.'%" or t.company_name like "%'.$content.'%"');
			}
		}
		$criteria->order = 't.dpid asc';
		$pages = new CPagination(Company::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = Company::model()->findAll($criteria);
		
		//$sql = 'select * from nb_company c left join nb_poscode_fee pf on(pf.dpid = c.dpid and pf.delete_flag=0) where ';
		
		$this->render('setindex',array(
				'models'=> $models,
				'pages'=>$pages,
				'province'=>$provinces,
				'city'=>$citys,
				'area'=>$areas,
				'role'=>$role
		));
	}
	public function actionStore(){
		//Yii::app()->end(json_encode(array("status"=>true,'msg'=>'成功')));
		$db = Yii::app()->db;
		$sql = 'select ps.dpid,ps.pad_code,pss.used_at,pss.use_status,pss.pad_no from nb_pad_setting ps left join nb_pad_setting_status pss on(pss.pad_setting_id = ps.lid and pss.dpid = ps.dpid and pss.delete_flag=0) where pss.use_status=1 and pss.delete_flag=0 and ps.delete_flag=0';
		$models = $db->createCommand($sql)->queryAll();
		
		//var_dump($models);exit;
		if(!empty($models)){
			foreach ($models as $model){
				$sql ='select * from nb_poscode_fee where dpid='.$model['dpid'].' and poscode='.$model['pad_code'];
				$re = $db->createCommand($sql)->queryRow();
				if(empty($re)){
					$se = new Sequence("poscode_fee");
					$id = $se->nextval();
					$data = array(
							'lid'=>$id,
							'dpid'=>$model['dpid'],
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'poscode'=>$model['pad_code'],
							'exp_time'=>$model['used_at'],
							'num'=>$model['pad_no'],
							'status'=>'0',
					);
					//var_dump($data);exit;
					$command = $db->createCommand()->insert('nb_poscode_fee',$data);
					//var_dump($command);exit;
				}
			}
		}
		Yii::app()->end(json_encode(array("status"=>true,'msg'=>'成功')));
	}
	public function actionPostore(){
		$dpid = Yii::app()->request->getParam('companyId',0);
		$poscode = Yii::app()->request->getParam('poscode',0);
		$years = Yii::app()->request->getParam('years',0);
		$month = Yii::app()->request->getParam('month',0);
		$status = Yii::app()->request->getParam('status',0);
		$time = Yii::app()->request->getParam('expt',date('Y-m-d H:i:s',time()));
		//var_dump($time);exit;
		if($years){
			$time = date('Y-m-d H:i:s',strtotime('+'.$years.' year '.$time));
		}
		if($month){
			$time = date('Y-m-d H:i:s',strtotime('+'.$month.' month '.$time));
		}
		//var_dump($time);exit;
		$db = Yii::app()->db;
		$sql = 'update nb_poscode_fee set exp_time="'.$time.'",status='.$status.' where dpid='.$dpid.' and poscode='.$poscode;
		$models = $db->createCommand($sql)->execute();
	
		Yii::app()->end(json_encode(array("status"=>true,'msg'=>'成功')));
	}
}