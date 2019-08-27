<?php
class CompanyController extends BackendController
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
	public function actionList(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$role = Yii::app()->user->role;
		
		$this->render('list',array('role'=>$role));
	}



	public function actionIndex(){
		$provinces = Yii::app()->request->getParam('province',0);
		$citys = Yii::app()->request->getParam('city',0);
		$areas = Yii::app()->request->getParam('area',0);
		$content = Yii::app()->request->getParam('content','');
		$role = Yii::app()->user->role;
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
		}else if(Yii::app()->user->role >= '5' && Yii::app()->user->role <= '10')
		{
			//var_dump(Yii::app()->user->role);exit;
			$criteria->condition =' t.delete_flag=0 and t.dpid in (select tt.dpid from nb_company tt where tt.comp_dpid='.Yii::app()->user->companyId.' and tt.type=1 and tt.delete_flag=0 ) or t.dpid='.Yii::app()->user->companyId;
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
		$this->render('index',array(
				'models'=> $models,
				'pages'=>$pages,
				'province'=>$provinces,
				'city'=>$citys,
				'area'=>$areas,
				'role'=>$role
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
		$pages->applyLimit($criteria);
             
		$models = Company::model()->findAll($criteria);
		$this->render('listchidren',array(
				'models'=> $models,
				'pages'=>$pages,
				'province'=>$provinces,
				'city'=>$citys,
				'area'=>$areas,
                     
		));
    }
	protected function afterSave()
	{
		if(parent::afterSave()) {
			if($this->isPostRequest) {
				$this->comp_dpid = Yii::app()->db->getLastInsertID();
			} else {
				//$this->update_time = date("Y-m-d H:i:s");
			}
			return true;
		} else {
			return false;
		}
	}
	public function actionCreate(){
        $type = '-1';
        $type2 = 'create';
        if(Yii::app()->request->isAjaxRequest){
        	$path = Yii::app()->basePath.'/../uploads/company_'.$this->companyId;
        	$up = new CFileUpload();
        	//设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
        	$up -> set("path", $path);
        	$up -> set("maxsize", 20*1024);
        	$up -> set("allowtype", array("png", "jpg","jpeg"));
        
        	if($up -> upload("file")) {
        		$msg = '/wymenuv2/./uploads/company_'.$this->companyId.'/'.$up->getFileName();
        	}else{
        		$msg = $up->getErrorMsg();
        	}
        	echo $msg;exit;
        }
        if(Yii::app()->user->role <= User::ADMIN_AREA) {

        $model = new Company();
        $model->create_at = date('Y-m-d H:i:s');
        //var_dump($model);exit;
        $db = Yii::app()->db;
        if(Yii::app()->user->role <= User::POWER_ADMIN_VICE){
            if(Yii::app()->request->isPostRequest) {
                $model->attributes = Yii::app()->request->getPost('Company');

                $pay_online = Yii::app()->request->getParam('pay_online');

                $province = Yii::app()->request->getParam('province1');
                $city = Yii::app()->request->getParam('city1');
                $area = Yii::app()->request->getParam('area1');

                $model->country = 'china';
                $model->province = $province;
                $model->city = $city;
                $model->county_area = $area;
                $model->create_at = date('Y-m-d H:i:s',time());
                $model->update_at = date('Y-m-d H:i:s',time());
                //$model->comp_dpid=mysql_insert_id();
                $model->type="0";
                if($model->save()){
                    $comp_dpid = Yii::app()->db->getLastInsertID();
                    $userid = new Sequence("company_property");
                    $id = $userid->nextval();
                    $data = array(
                                    'lid'=>$id,
                                    'dpid'=>$comp_dpid,
                                    'create_at'=>date('Y-m-d H:i:s',time()),
                                    'update_at'=>date('Y-m-d H:i:s',time()),
                                    'pay_type'=>$pay_online,
                                    'pay_channel'=>$pay_online,
                                    'delete_flag'=>'0',
                    );
                    $command = $db->createCommand()->insert('nb_company_property',$data);
                    $sql = 'update nb_company set comp_dpid = '.$comp_dpid.' where delete_flag = 0 and dpid = '.$comp_dpid;
                    $command=Yii::app()->db->createCommand($sql);
                    $command->execute();
                    //$model->comp_dpid = $post->attributes['dpid'];
                    //var_dump($id);exit;
                    Yii::app()->user->setFlash('success',yii::t('app','创建成功'));
                    $this->redirect(array('company/index','companyId'=> $this->companyId));
                } else {
                    Yii::app()->user->setFlash('error',yii::t('app','创建失败'));
                }
            }
        }else if(Yii::app()->user->role > 3 && Yii::app()->user->role <= 9){
            if(Yii::app()->request->isPostRequest) {
                    $model->attributes = Yii::app()->request->getPost('Company');
                    $coms = Yii::app()->request->getPost('com');
                    // var_dump($coms);exit();
                    $pay_online = Yii::app()->request->getParam('pay_online');

                    $province = Yii::app()->request->getParam('province1');
                    $city = Yii::app()->request->getParam('city1');
                    $area = Yii::app()->request->getParam('area1');

                    $model->country = 'china';
                    $model->province = $province;
                    $model->city = $city;
                    $model->county_area = $area;
                    $model->create_at=date('Y-m-d H:i:s',time());
                    $model->update_at=date('Y-m-d H:i:s',time());
                    $model->comp_dpid = $this->getCompanyId(Yii::app()->user->username);   
                    //var_dump($model);exit;
                    //$model->type="0";
                    if($model->save()){
                    		if(isset($coms['price'])){
                    			$price = $coms['price'];
                    		}else{
                    			$price = "";
                    		}
                            $comp_dpid = Yii::app()->db->getLastInsertID();
                            $userid = new Sequence("company_property");
                            $id = $userid->nextval();
                            $data = array(
                                            'lid'=>$id,
                                            'dpid'=>$comp_dpid,
                                            'create_at'=>date('Y-m-d H:i:s',time()),
                                            'update_at'=>date('Y-m-d H:i:s',time()),
                                            'pay_type'=>$pay_online,
                                            'pay_channel'=>$pay_online,
                                            'price_group_id'=>$price,
                                            'delete_flag'=>'0',
                            );
                            $command = $db->createCommand()->insert('nb_company_property',$data);
                           if(!empty($coms['group']) && $coms['group']!=0){
                           		$areaid = new Sequence("area_group_company");
	                            $area = $areaid->nextval();
	                            $date_area = array(
	                            	'lid'=>$area,
	                            	'dpid'=>$this->companyId,
	                            	'create_at'=>date('Y-m-d H:i:s',time()),
	                                'update_at'=>date('Y-m-d H:i:s',time()),
	                                'area_group_id'=>$coms['group'],
	                                'company_id'=>$comp_dpid
	                            ); 
	                            $areamodel = $db->createCommand()->insert('nb_area_group_company',$date_area);
                           }
                            // var_dump($areamodel);exit();
                            //厂商分类
                            $manu = new Sequence("manufacturer_classification");
                            $manu_lid = $manu->nextval();
                            $manu_data = array(
                                            'lid'=>$manu_lid,
                                            'dpid'=>$comp_dpid,
                                            'create_at'=>date('Y-m-d H:i:s',time()),
                                            'update_at'=>date('Y-m-d H:i:s',time()),
                                            'classification_name'=>'总部',                                          
                            );
                            $manu_command =$db->createCommand()->insert('nb_manufacturer_classification',$manu_data);
                            
                            //厂商信息
                            //1.查找总部信息。
                            $hq_sql = 'SELECT * FROM nb_company WHERE dpid = :dpid and delete_flag=0';
                            $hq =  Yii::app()->db->createCommand($hq_sql)->bindValue(':dpid',$model->comp_dpid)->queryRow();
                            //2.把总部信息插入厂商信息表。
                            $info = new Sequence("manufacturer_information");
                            $info_lid = $manu->nextval();
                            $info_data = array(
                                            'lid'=>$info_lid,
                                            'dpid'=>$comp_dpid,
                                            'create_at'=>date('Y-m-d H:i:s',time()),
                                            'update_at'=>date('Y-m-d H:i:s',time()),
                                            'classification_id'=>$manu_lid,
                                            'manufacturer_name'=>'总部', 
                                            'address' => $hq['province'].$hq['city'].$hq['county_area'],
                                            'contact_name'=>$hq['contact_name'],
                                            'contact_tel'=>$hq['mobile']
                            );
                            $info_command =$db->createCommand()->insert('nb_manufacturer_information',$info_data);
                                          
                                                                         
                            Yii::app()->user->setFlash('success',yii::t('app','创建成功'));
                            $this->redirect(array('company/index','companyId'=> $this->companyId)); 
                        } else {
                                Yii::app()->user->setFlash('error',yii::t('app','创建失败'));
                        }
                }

        }
        $role = Yii::app()->user->role;
        $printers = $this->getPrinterList();
        $sql = "select lid,group_name from nb_area_group where dpid=".$this->companyId." and type=2 and delete_flag=0";
        $groups = Yii::app()->db->createCommand($sql)->queryAll();
        $sqls = "select lid,group_name from nb_price_group where dpid=".$this->companyId." and delete_flag=0";
        $prices = Yii::app()->db->createCommand($sqls)->queryAll();
        // var_dump($prices);exit;
        $company = "";
        $property = "";
        return $this->render('create',array(
                        'model' => $model,
                        'printers'=>$printers,
                        'role'=>$role,
        				'companyId'=>$this->companyId,
                        'type'=> $type,
                        'type2'=> $type2,
                        'groups'=>$groups,
                        'prices'=>$prices,
		                'company'=>$company,
		                'property'=>$property
        ));
}else{
        $this->redirect(array('company/index','companyId'=>  $this->companyId));
}
}
	public function actionUpdate(){
		if(Yii::app()->request->isAjaxRequest){
			$path = Yii::app()->basePath.'/../uploads/company_'.$this->companyId;
			$up = new CFileUpload();
			//设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
			$up -> set("path", $path);
			$up -> set("maxsize", 20*1024);
			$up -> set("allowtype", array("png", "jpg","jpeg"));
		
			if($up -> upload("file")) {
				$msg = '/wymenuv2/./uploads/company_'.$this->companyId.'/'.$up->getFileName();
			}else{
				$msg = $up->getErrorMsg();
			}
			echo $msg;exit;
		}
		$role = Yii::app()->user->role;
		$db = Yii::app()->db;
		$dpid = Helper::getCompanyId(Yii::app()->request->getParam('dpid'));
		$type = Yii::app()->request->getParam('type');
		$type2 = 'update';
		$model = Company::model()->find('dpid=:companyId' , array(':companyId' => $dpid)) ;
		$sql = "select a.area_group_id from nb_area_group_company a,nb_area_group g where a.company_id=".$dpid." and a.area_group_id=g.lid and g.type=2 and a.delete_flag=g.delete_flag and a.delete_flag=0";
		$company = Yii::app()->db->createCommand($sql)->queryRow();
		// var_dump($company);exit;
		$sqls = "select price_group_id from nb_company_property where dpid=".$dpid." and delete_flag=0";
		$property = Yii::app()->db->createCommand($sqls)->queryRow();
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('company/index' , 'companyId' => $this->companyId)) ;
		}
		if(Yii::app()->request->isPostRequest) {
            //Until::isUpdateValid(array(0),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
			$model->attributes = Yii::app()->request->getPost('Company');
			$coms = Yii::app()->request->getPost('com');
			// var_dump($coms);exit();
			$province = Yii::app()->request->getParam('province1');
			$city = Yii::app()->request->getParam('city1');
			$area = Yii::app()->request->getParam('area1');
			
			$model->country = 'china';
			$model->province = $province;
			$model->city = $city;
			$model->county_area = $area;
            $model->update_at=date('Y-m-d H:i:s',time());
			
			//var_dump($model);exit;
			if($model->save()){
				if(!empty($coms)){
					if(isset($coms['group'])){
						if(!empty($company)){
						// echo "cccc";exit;
							$area = $db->createCommand('update nb_area_group_company set area_group_id="'.$coms['group'].'",update_at="'.date('Y-m-d H:i:s',time()).'" where company_id ='.$dpid.' and delete_flag=0')->execute();
						}else{
							$areaid = new Sequence("area_group_company");
		                    $area = $areaid->nextval();
		                    $date_area = array(
		                    	'lid'=>$area,
		                    	'dpid'=>$this->companyId,
		                    	'create_at'=>date('Y-m-d H:i:s',time()),
		                        'update_at'=>date('Y-m-d H:i:s',time()),
		                        'area_group_id'=>$coms['group'],
		                        'company_id'=>$dpid
		                    ); 
		                    $areamodel = $db->createCommand()->insert('nb_area_group_company',$date_area);
						}
					}
					if(isset($coms['price'])){
						$price = $db->createCommand('update nb_company_property set price_group_id="'.$coms['price'].'",update_at="'.date('Y-m-d H:i:s',time()).'" where dpid ='.$dpid.' and delete_flag=0')->execute();
					}
				}
				Yii::app()->user->setFlash('success',yii::t('app','修改成功'));
				$this->redirect(array('company/index','companyId'=>$this->companyId));
			} else {
				Yii::app()->user->setFlash('error',yii::t('app','修改失败'));
			}
		}
		$printers = $this->getPrinterList();
		$sql = "select lid,group_name from nb_area_group where dpid=".$this->companyId." and type=2 and delete_flag=0";
        $groups = Yii::app()->db->createCommand($sql)->queryAll();
        $sqls = "select lid,group_name from nb_price_group where dpid=".$this->companyId." and delete_flag=0";
        $prices = Yii::app()->db->createCommand($sqls)->queryAll();
		return $this->render('update',array(
				'model'=>$model,
				'printers'=>$printers,
				'role'=>$role,
                'companyId'=>$this->companyId,
				'type'=>$type,
				'type2'=>$type2,
				'groups'=>$groups,
                'prices'=>$prices,
                'company'=>$company,
                'property'=>$property
		));
	}




	public function actionDelete(){
		$ids = Yii::app()->request->getPost('companyIds');
        //Until::isUpdateValid(array(0),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
        $db = Yii::app()->db;
		if(!empty($ids)) {
			$transaction = $db->beginTransaction();
        	try{
				foreach ($ids as $id) {
					$info = $db->createCommand('update nb_company set delete_flag=1,update_at="'.date('Y-m-d H:i:s',time()).'" where dpid ='.$id)->execute();
					$infos = $db->createCommand('update nb_user set delete_flag=1,update_at="'.date('Y-m-d H:i:s',time()).'" where dpid ='.$id.' and delete_flag=0')->execute();
				}
				$transaction->commit();
				Yii::app()->user->setFlash('success' , yii::t('app','删除成功！！！'));
				$this->redirect(array('company/index','companyId'=>$this->companyId));
        	}catch (Exception $e){
				$transaction->rollback();
        		Yii::app()->user->setFlash('error' , yii::t('app','删除失败！！！'));
				$this->redirect(array('company/index','companyId'=>$this->companyId));
        	}
		}
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
		$qrcode = $wxQrcode->getQrcode(WxQrcode::COMPANY_QRCODE,$model->lid,$model->dpid,strtotime('2050-01-01 00:00:00'));
	
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
	public function actionPaystore(){
		$dpid = Yii::app()->request->getParam('companyId');
		//var_dump($dpid);exit;
		//****查询公司的产品分类。。。****
		$db = Yii::app()->db;
		$compros = CompanyProperty::model()->find('dpid=:companyId and delete_flag=0' , array(':companyId'=>$dpid));
		if(!empty($compros)){
			$sql = 'update nb_company_property set update_at ="'.date('Y-m-d H:i:s',time()).'",pay_type ="2",pay_channel ="3" where dpid ='.$dpid;
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
					'pay_channel'=>'3',
					'appId'=>'',
					'code'=>'',
					'delete_flag'=>'0',
			);
			//var_dump($dataprod);exit;
			$command = $db->createCommand()->insert('nb_company_property',$data);
		}
		Yii::app()->end(json_encode(array("status"=>"success",'msg'=>'成功')));
	}
}