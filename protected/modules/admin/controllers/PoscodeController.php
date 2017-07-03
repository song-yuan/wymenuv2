<?php
class PoscodeController extends BackendController
{
    public function beforeAction($action) {
            parent::beforeAction($action);
            if(!$this->companyId) {
                    Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
                    $this->redirect(array('company/index'));
            }
            return true;
    }
    public function actionIndex(){
            $criteria = new CDbCriteria;
            $criteria->with = 'detail';
            $criteria->condition = 't.dpid='.$this->companyId.' and t.delete_flag=0';
            $criteria->group = 't.lid';
            $pages = new CPagination(PadSetting::model()->count($criteria));
            $pages->applyLimit($criteria);
            $models = PadSetting::model()->findAll($criteria);

            $this->render('index',array(
                    'models'=>$models,
                    'pages'=>$pages,
            ));
    }


    //总部pos机报表
    public function actionHqindex(){
        //查询总公司
        $cdpid = Yii::app()->request->getParam('cdpid');
        $download = Yii::app()->request->getParam('download');
        $model = Yii::app()->db->createCommand("select * from nb_company where type=0 and delete_flag =0")->queryALL();
        // p($model);
        $models=0;
        $CompanyName=null;
        if($cdpid){

            //获取ajax数据,总公司的dpid-----子公司的comp_dpid
            //$cdpid = Yii::app()->request->getPost('cdpid');//$_POST['cdpid'];

            $CompanyName = Company::model()->findByPk($cdpid)->company_name;
            // p($CompanyName);
            //查询子公司POS机数据
            
                $sql ='select pss.status,pss.use_status,c.company_name,t.* from nb_pad_setting t '
                        .' left join nb_company c on(c.dpid = t.dpid ) '
                        .' left join nb_pad_setting_status pss ON(pss.dpid = t.dpid and pss.pad_setting_id = t.lid ) '
                        .' where t.delete_flag =0 and t.dpid in( '
                                .' select dpid from nb_company where comp_dpid ='.$cdpid.' and delete_flag = 0)'
                        .' order by c.company_name asc';
                        //echo $sql;exit;
                $models = Yii::app()->db->createCommand($sql)->queryALL();

        // p($models);
                if($download){
                            // $models = $db->createCommand($sql)->queryAll();
                            //var_dump($models);exit;
                            $this->exportPosReport($models,$CompanyName);
                            exit;
                }
        }
            $this->render('hqindex',array(
                    'statu'=>'null',
                    'use_statu'=>'null',
                    'models'=>$models,
                    'hqcompany'=>$model,
                    'comp_name'=>$CompanyName,
            ));
    }

    //总部pos机报表条件查询
    public function actionHqsearch(){
        //查询总公司
        $cdpid = Yii::app()->request->getParam('cdpid');
        $download = Yii::app()->request->getParam('download');
        $statu = Yii::app()->request->getParam('statu');
        $use_statu = Yii::app()->request->getParam('use_statu');
        $model = Yii::app()->db->createCommand("select * from nb_company where type=0 and delete_flag =0")->queryALL();
        $models=0;
        $CompanyName=null;
        if($cdpid){

            //获取ajax数据,总公司的dpid-----子公司的comp_dpid
            //$cdpid = Yii::app()->request->getPost('cdpid');//$_POST['cdpid'];

            $CompanyName = Company::model()->findByPk($cdpid)->company_name;
            // gp($statu);
            // gp($use_statu);
            //查询子公司POS机数据
            if($statu==='null'){
                if ($use_statu==='null') {
                    $sql ='select pss.status,pss.use_status,c.company_name,t.* from nb_pad_setting t '
                            .' left join nb_company c on(c.dpid = t.dpid ) '
                            .' left join nb_pad_setting_status pss ON(pss.dpid = t.dpid and pss.pad_setting_id = t.lid ) '
                            .' where t.delete_flag =0 and t.dpid in( '
                                    .' select dpid from nb_company where comp_dpid ='.$cdpid.' and delete_flag = 0)';
                }else{
                    $sql ='select pss.status,pss.use_status,c.company_name,t.* from nb_pad_setting t '
                            .' left join nb_company c on(c.dpid = t.dpid ) '
                            .' left join nb_pad_setting_status pss ON(pss.dpid = t.dpid and pss.pad_setting_id = t.lid ) '
                            .' where t.delete_flag =0 and t.dpid in( '
                                    .' select dpid from nb_company where comp_dpid ='.$cdpid.' and delete_flag = 0)'
                                    .' and pss.use_status='.$use_statu;
                }
            }else{
                if ($use_statu==='null') {
                    $sql ='select pss.status,pss.use_status,c.company_name,t.* from nb_pad_setting t '
                            .' left join nb_company c on(c.dpid = t.dpid ) '
                            .' left join nb_pad_setting_status pss ON(pss.dpid = t.dpid and pss.pad_setting_id = t.lid ) '
                            .' where t.delete_flag =0 and t.dpid in( '
                                    .' select dpid from nb_company where comp_dpid ='.$cdpid.' and delete_flag = 0)'
                                    .' and pss.status='.$statu;
                }else{
                    $sql ='select pss.status,pss.use_status,c.company_name,t.* from nb_pad_setting t '
                            .' left join nb_company c on(c.dpid = t.dpid ) '
                            .' left join nb_pad_setting_status pss ON(pss.dpid = t.dpid and pss.pad_setting_id = t.lid ) '
                            .' where t.delete_flag =0 and t.dpid in( '
                                    .' select dpid from nb_company where comp_dpid ='.$cdpid.' and delete_flag = 0)'
                                    .' and pss.status='.$statu
                                    .' and pss.use_status='.$use_statu;
                }
            }
                        // echo $sql;exit;
                $models = Yii::app()->db->createCommand($sql)->queryALL();

        // p($models);
                if($download){
                            // $models = $db->createCommand($sql)->queryAll();
                            //var_dump($models);exit;
                            $this->exportPosReport($models,$CompanyName);
                            exit;
                }
        }
            $this->render('hqindex',array(
                    'statu'=>$statu,
                    'use_statu'=>$use_statu,
                    'models'=>$models,
                    'hqcompany'=>$model,
                    'comp_name'=>$CompanyName,
            ));
    }



    //pos机结算
    public function actionCounts(){
            $companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
            $ids = Yii::app()->request->getPost('ids');
            $status = Yii::app()->request->getPost('status');
            // var_dump($ids);exit;
            if(!is_array($ids)){
                $ids = array($ids);
            }
            if(!empty($ids)) {
                    foreach ($ids as $id) {
                        $models = PadSettingStatus::model()->find('pad_setting_id=:id ' , array(':id' => (int)$id ,)) ;
                        // p($model_one);
                        // 如果状态表数据存在就更新,如果不存在就创建为结算状态
                        if($models) {
                            $models->saveAttributes(array('status'=>$status,'update_at'=>date('Y-m-d H:i:s',time())));
                        }else{
                            $se1=new Sequence("pad_setting_status");
                            $lid = $se1->nextval();
                            $model = PadSetting::model()->find('lid=:id ' , array(':id' => $id ,));
                            $comp_dpid = $model->dpid;
                            $data = array(
                                    'lid' => $lid,
                                    'dpid' => $comp_dpid,
                                    'create_at'=>date('Y-m-d H:i:s',time()),
                                    'update_at'=>date('Y-m-d H:i:s',time()),
                                    'pad_setting_id'=>$id,
                                    'status' =>'1',
                                    'use_status' => '0',
                                );
                            $command = Yii::app()->db->createCommand()->insert('nb_pad_setting_status' , $data);
                            //gp($data);
                        }

                    }
                    echo 1;//返回1用于结果提示
            } else {
                    Yii::app()->user->setFlash('error' ,yii::t('app', '请选择要操作的项目'));
                    $this->redirect(array('poscode/index' , 'companyId' => $companyId)) ;
            }
    }



    private function exportPosReport($models,$hqCname,$params=array(),$export = 'xml'){

        $attributes = array(
                'lid'=>'序号',
                'pad_code'=>'POS序列号',
                'use_status'=>'使用状态(0未使用1已使用)',
                'pad_sales_type'=>'模式',
                'company_name'=>'店铺',
                'status'=>'结算状态(0未结算1已结算)',
        );
        // p($hqCname);
        $data[1] = array_values($attributes);
        $fields = array_keys($attributes);

        foreach($models as $k=>$model){
                $arr = array();
                foreach($fields as $f){
                    if($f == 'lid'){
                        $arr[] = $k+1;
                    }else{
                        $arr[] = $model[$f];
                    }
                }
                $data[] = $arr;
            }
            $name = $hqCname.date('Y-m-d H:i:s',time());//若文件名无中文店名,将compnents/config/Excel.php:155$filename注释
            //var_dump($name);exit;
        Until::exportFile($data,$export,$name);
        }



    public function actionCreate(){
            $model = new PadSetting() ;
            $model_one = new PadSettingStatus() ;

            if(Yii::app()->request->isPostRequest) {
                    $is_onlinepay = CompanyProperty::model()->find('dpid=:dpid',array(':dpid'=>$this->companyId));
                    if(!empty($is_onlinepay)){
                            if($is_onlinepay['pay_type']&&$is_onlinepay['pay_channel']=='2'){
                                    $pay_act = '1';
                            }else{
                                    $pay_act = '0';
                            }
                    }else{
                            $pay_act = '0';
                    }
                    //开启事务
                    $db = Yii::app()->db;
                    $transaction = $db->beginTransaction();
                    try{
                        $model->attributes = Yii::app()->request->getPost('PadSetting');
                        $model_one->attributes = Yii::app()->request->getPost('PadSettingStatus');
                        $se=new Sequence("pad_setting");
                        $model->lid = $se->nextval();
                        $pad_setting_id=$model->lid;
                        $model->dpid = $this->companyId ;
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->update_at = date('Y-m-d H:i:s',time());
                        $model->delete_flag = '0';
                        $model->pay_activate = $pay_act;
                        $model->pad_code = PadSetting::getNo($model->lid,4).PadSetting::getNo($model->dpid,4).PadSetting::getRandomString(6,1);
                        
                        //先验证数据,通过验证在保存数据
                        if ($model->save()) {
                            $se1=new Sequence("pad_setting_status");
                            $id = $se1->nextval();
                            $comp_dpid = $this->companyId;
                            $data = array(
                                    'lid' => $id,
                                    'dpid' => $comp_dpid,
                                    'create_at'=>date('Y-m-d H:i:s',time()),
                                    'update_at'=>date('Y-m-d H:i:s',time()),
                                    'pad_setting_id'=>$pad_setting_id,
                                    'status' =>'0',
                                    'use_status' => '0',
                                );
                            $command = $db->createCommand()->insert('nb_pad_setting_status' , $data);
                            //var_dump($command);die;
                            // if (!$command) {
                            //     $error=array_values($model->getFirstErrors())[0];
                            //     throw new Exception($error);
                            // }
                        }else{
                            $error=array_values($model->getFirstErrors())[0];
                            throw new Exception($error);
                        }
                        //执行事务
                        $transaction->commit();
                        Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
                        $this->redirect(array('poscode/index','companyId' => $this->companyId));
                        
                    }catch(Exception $e){
                        $transaction->rollBack();
                        Yii::app()->user->setFlash('error' ,yii::t('app', '添加失败'));
                        $this->redirect(array('poscode/index','companyId' => $this->companyId));
                    }
            }else{
                $this->render('create' , array(
                                'model' => $model,
                                'model_one'=>$model_one,
                ));
            }

    }




    public function actionDelete(){
            $companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
            $ids = Yii::app()->request->getPost('ids');
            //Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
            //var_dump($ids);exit;
            if(!empty($ids)) {
                    foreach ($ids as $id) {
                        $model = PadSetting::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $companyId)) ;
                        $model_one = PadSettingStatus::model()->find('pad_setting_id=:id and dpid=:companyId' , array(':id' => (int)$id , ':companyId' => $companyId)) ;
                        // p($model_one);
                        if($model) {
                                $model->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
                                if ($model_one) {
                                    $model_one->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
                                }
                        }
                    }
                    $this->redirect(array('poscode/index' , 'companyId' => $companyId)) ;
            } else {
                    Yii::app()->user->setFlash('error' ,yii::t('app', '请选择要删除的项目'));
                    $this->redirect(array('poscode/index' , 'companyId' => $companyId)) ;
            }
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
    // 统计店铺中有几台POS机
    public function actionCountNum(){
        // 总公司的dpid
        $cdpid = Yii::app()->request->getParam('cdpid');
        $CompanyName = Company::model()->findByPk($cdpid)->company_name;
        $model = Yii::app()->db->createCommand("select * from nb_company where type=0 and delete_flag =0")->queryALL();
        $sql='select c.company_name,count(pst.dpid) as cnum from nb_pad_setting pst '.'left join nb_company c on (c.dpid=pst.dpid) where pst.delete_flag=0 and pst.dpid in (select dpid from nb_company where comp_dpid='.$cdpid.' and delete_flag=0) group by pst.dpid';
        $models = Yii::app()->db->createCommand($sql)->queryALL();
        $this->render('hqcount',array(
                'statu'=>'null',
                'use_statu'=>'null',
                'models'=>$models,
                'cdpid'=>$cdpid,
                'hqcompany'=>$model,
                'comp_name'=>$CompanyName,
        ));
    }//select c.company_name,count(pst.dpid) from nb_pad_setting pst left join nb_company c on (c.dpid=pst.dpid) where pst.delete_flag=0 and pst.dpid in (select dpid from nb_company where comp_dpid=0000000006 and delete_flag=0) group by pst.dpid
}