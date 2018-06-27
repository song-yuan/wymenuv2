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
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-01-01',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		
		$sql = 'select m.* from (select ps.*,c.province,c.city,c.county_area,c.mobile,c.company_name,c.contact_name,pf.used_at,pf.exp_time from nb_pad_setting ps left join nb_company c on ps.dpid=c.dpid left join nb_poscode_fee pf on ps.pad_code=pf.poscode and ps.dpid=pf.dpid where c.comp_dpid='.$companyId.' and c.comp_dpid!=c.dpid and c.type=1 and ps.delete_flag=0 and c.delete_flag=0';
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
			$sql .=' and c.province like "'.$province.'"'; 
		}
		if($city){
			$sql .=' and c.city like "'.$city.'"';
		}
		if($area){
			$sql .=' and c.county_area like "'.$area.'"';
		}
		if ($content) {
			if (is_numeric($content)) {
				$sql .=' and c.mobile like "%'.$content.'%"';
			}else{
				$sql .=' and (c.contact_name like "%'.$content.'%" or c.company_name like "%'.$content.'%")';
			}
		}
		$sql .=')m order by m.exp_time asc, m.dpid asc';
		
		$count = Yii::app()->db->createCommand(str_replace('m.*','count(*)',$sql))->queryScalar();
		
		$pages = new CPagination($count);
		$pdata =Yii::app()->db->createCommand($sql." LIMIT :offset,:limit");
				$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
				$pdata->bindValue(':limit', $pages->getPageSize());
				$models = $pdata->queryAll();
		
// 		var_dump($models);exit;
		
		$this->render('setindex',array(
				'models'=> $models,
				'pages'=>$pages,
				'province'=>$provinces,
				'city'=>$citys,
				'area'=>$areas,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time
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