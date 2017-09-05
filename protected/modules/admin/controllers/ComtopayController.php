<?php
class ComtopayController extends BackendController
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


	public function actionIndex(){
		$provinces = Yii::app()->request->getParam('province',0);
		$citys = Yii::app()->request->getParam('city',0);
		$areas = Yii::app()->request->getParam('area',0);
		$content = Yii::app()->request->getParam('content','');

		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));

		$criteria = new CDbCriteria;
		$criteria->with = 'property';
		if(Yii::app()->user->role < '5')
		{
			if ($content=='') {
				$criteria->condition =' t.delete_flag=0 and t.type=0';
			}else{
				$criteria->condition =' t.delete_flag=0';
			}
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
		//var_dump($criteria);exit;
		$models = Company::model()->findAll($criteria);
		$this->render('index',array(
				'models'=> $models,
				'pages'=>$pages,
				'province'=>$provinces,
				'city'=>$citys,
				'area'=>$areas,
		));
	}
    public function actionListchidren(){
        $provinces = Yii::app()->request->getParam('province',0);
		$citys = Yii::app()->request->getParam('city',0);
		$areas = Yii::app()->request->getParam('area',0);
		$content = Yii::app()->request->getParam('content','');

		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));

		$criteria = new CDbCriteria;
		$criteria->with = 'property';

                $criteria->condition = ' t.delete_flag=0 and t.comp_dpid='.$companyId;
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
				$criteria->addCondition('t.mobile like "'.$content.'"');
			}else{
				$criteria->addCondition('t.contact_name like "%'.$content.'%" or t.company_name like "%'.$content.'%"');
			}
		}
		$criteria->order = 't.dpid asc';
		$pages = new CPagination(Company::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);

		$models = Company::model()->findAll($criteria);
//                var_dump($models);exit;
//               print_r($criteria);exit;
		$this->render('listchidren',array(
				'models'=> $models,
				'pages'=>$pages,
				'province'=>$provinces,
				'city'=>$citys,
				'area'=>$areas,

		));
        }


	/**
	 * 生成店铺二维码
	 */
	public function actionGenWxQrcode(){
		$dpid = Yii::app()->request->getParam('dpid',0);
		$account = WeixinServiceAccount::model()->find('dpid=:dpid',array(':dpid'=>$dpid));
		if($account&&$account['appid']&&$account['appsecret']){
			$companyDpid = $dpid;
		}else{
			$company = Company::model()->find('dpid=:dpid',array(':dpid'=>$dpid));
			$companyDpid = $company['comp_dpid'];
		}
		$model = CompanyProperty::model()->find('dpid=:dpid and delete_flag=0',array(':dpid'=>$dpid));
		if(!$model){
			$model = new CompanyProperty;
			$se = new Sequence("company_property");
			$lid = $se->nextval();
			$data = array(
					'lid'=>$lid,
					'dpid'=>$dpid,
					'create_at'=>date('Y-m-d H:i:s',time()),
					'update_at'=>date('Y-m-d H:i:s',time()),
					'pay_channel'=>''
			);
			$model->attributes = $data;
		}
		$data = array('msg'=>'请求失败！','status'=>false,'qrcode'=>'');

		$wxQrcode = new WxQrcode($companyDpid);
		$qrcode = $wxQrcode->getQrcode(WxQrcode::COMPANY_QRCODE,$model->dpid,strtotime('2050-01-01 00:00:00'));

		if($qrcode){
			$model->qr_code = $qrcode;
			$model->save();
			$data['msg'] = '生成二维码成功！';
			$data['status'] = true;
			$data['qrcode'] = $qrcode;
		}
		Yii::app()->end(json_encode($data));
	}
	private function getPrinterList(){
		$printers = Printer::model()->findAll('dpid=:dpid',array(':dpid'=>$this->companyId)) ;
		return CHtml::listData($printers, 'printer_id', 'name');
	}
	private function getCompanyId($username){
		$companyId = User::model()->find('username=:username',array(':username'=>$username)) ;
		return $companyId['dpid'];
	}
	public function actionStore(){
		$dpid = Yii::app()->request->getParam('companyId');
		$appid = Yii::app()->request->getParam('appid');
		$code = Yii::app()->request->getParam('code');
		$paytype = Yii::app()->request->getParam('paytype');
		$paychannel = Yii::app()->request->getParam('paychannel');
		//var_dump($dpid,$appid);exit;

		//****查询公司的产品分类。。。****
		$db = Yii::app()->db;
		$compros = CompanyProperty::model()->find('dpid=:companyId and delete_flag=0' , array(':companyId'=>$dpid));
		if(!empty($compros)){
			$sql = 'update nb_company_property set update_at ="'.date('Y-m-d H:i:s',time()).'",pay_type ="2",pay_channel ="2",appId ="'.$appid.'",code ="'.$code.'" where dpid ='.$dpid;
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
					'pay_type'=>'2',
					'pay_channel'=>'2',
					'appId'=>$appid,
					'code'=>$code,
					'delete_flag'=>'0',
			);
			//var_dump($dataprod);exit;
			$command = $db->createCommand()->insert('nb_company_property',$data);
		}
		Yii::app()->end(json_encode(array("status"=>"success",'msg'=>'成功')));
	}







	    public function actionIndexpos(){
            $criteria = new CDbCriteria;
            $criteria->with = 'detail';
            $criteria->condition = 't.dpid='.$this->companyId.' and t.delete_flag=0';
            $criteria->group = 't.lid';
            $pages = new CPagination(PadSetting::model()->count($criteria));
            $pages->applyLimit($criteria);
            $models = PadSetting::model()->findAll($criteria);

            $this->render('indexpos',array(
                    'models'=>$models,
                    'pages'=>$pages,
            ));
    }






    public function actionReset(){

        $companyId = Yii::app()->request->getParam('companyId');
        $reset = Yii::app()->request->getParam('reset');

        $sql = 'delete from nb_pad_setting_detail where pad_setting_id='.$reset;
        $result = Yii::app()->db->createCommand($sql)->execute();

        if ($result){

            $status = true;

        }else{

            $status = false;
        }
        echo $status;
        exit;

    }

    public function actionSqbactivate(){
            $compros = CompanyProperty::model()->find('dpid=:companyId and delete_flag=0' , array(':companyId'=>$this->companyId));
            if(!empty($compros)){
                    $appId = $compros['appId'];
                    $code = $compros['code'];
            }else{
                    Yii::app()->end(json_encode(array("status"=>"ERROR",'msg'=>'尚未开通')));
                    exit;
            }
            //var_dump($_POST);exit;
            //$result = SqbPay::activate($_POST);
            $device_id = $_POST['device_id'];
            $result = SqbPay::activate(array('device_id'=>$device_id,'appId'=>$appId,'code'=>$code));
            $obj = json_decode($result,true);
            $devicemodel = SqbPossetting::model()->find('device_id=:deviceid and dpid=:dpid',array(':dpid'=>$this->companyId,':deviceid'=>$device_id));
            //var_dump($obj);exit;
            if($obj['result_code']=='400'){
                    Yii::app()->end(json_encode(array("status"=>"error",'msg'=>'激活失败！！！')));
            }else{
                    if(!empty($devicemodel)){
                            Yii::app()->db->createCommand('update nb_sqb_posseting set terminal_key='.$obj['biz_response']['terminal_key'].' where device_id ='.$device_id.' and dpid ='.$this->companyId)
                            ->execute();
                    }else{

                            //$obj = json_decode($result,true);
                            $comset=new SqbPossetting();
                            $se=new Sequence("sqb_possetting");
                            $comset->lid = $se->nextval();
                            $comset->dpid=$this->companyId;
                            $comset->create_at = date('Y-m-d H:i:s',time());
                            $comset->update_at = date('Y-m-d H:i:s',time());
                            $comset->device_id = $device_id;
                            $comset->terminal_sn = $obj['biz_response']['terminal_sn'];
                            $comset->terminal_key = $obj['biz_response']['terminal_key'];
                            $comset->key_validtime = date('Ymd',time());
                            $comset->save();

                            Yii::app()->db->createCommand('update nb_pad_setting set pay_activate=2 where pad_code ='.$device_id.' and dpid ='.$this->companyId)
                            ->execute();
                    }
                    Yii::app()->end(json_encode(array("status"=>"success",'msg'=>'成功')));
            }
    }
    public function actionSqbstartonline(){
            $device_id = $_POST['device_id'];
            //var_dump($device_id);exit;
            $compros = CompanyProperty::model()->find('dpid=:companyId and delete_flag=0' , array(':companyId'=>$this->companyId));
            if(!empty($compros)){
                    $appId = $compros['appId'];
                    $code = $compros['code'];
            }else{
                    Yii::app()->end(json_encode(array("status"=>"ERROR",'msg'=>'尚未开通')));
                    exit;
            }

            Yii::app()->db->createCommand('update nb_pad_setting set pay_activate = 1 where pad_code ='.$device_id.' and dpid ='.$this->companyId)
            ->execute();

            Yii::app()->end(json_encode(array("status"=>"success",'msg'=>'成功')));

    }

}