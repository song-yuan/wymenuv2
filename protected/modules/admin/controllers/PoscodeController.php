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
    public function actionCreate(){
            $model = new PadSetting() ;
            $model->dpid = $this->companyId ;

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
                    $model->attributes = Yii::app()->request->getPost('PadSetting');
                    $se=new Sequence("pad_setting");
                    $model->lid = $se->nextval();
                    $model->create_at = date('Y-m-d H:i:s',time());
                    $model->update_at = date('Y-m-d H:i:s',time());
                    $model->delete_flag = '0';
                    $model->pay_activate = $pay_act;
                    $model->pad_code = PadSetting::getNo($model->lid,4).PadSetting::getNo($model->dpid,4).PadSetting::getRandomString(6,1);
                    //var_dump($model);exit;
                    if($model->save()) {
                            Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
                            $this->redirect(array('poscode/index','companyId' => $this->companyId));
                    }
            }

            $this->render('create' , array(
                            'model' => $model,
            ));
    }

    public function actionDelete(){
            $companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
            $ids = Yii::app()->request->getPost('ids');
            //Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
            //var_dump($ids);exit;
            if(!empty($ids)) {
                    foreach ($ids as $id) {
                            $model = PadSetting::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $companyId)) ;
                            if($model) {
                                    $model->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
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

}