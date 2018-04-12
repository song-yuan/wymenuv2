<?php

class EntityCardController extends BackendController {
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
    public function actionList() {
       
		$this->render('list');
    }
     public function actionCardSearch() {         
        $card_model=''; 
        $orderPay='';
        
        $num = Yii::app()->request->getPost('num');
        if(Yii::app()->request->isPostRequest){
            if($num !=''){
                 $card_model = MemberCard::model()->with(array('brandUserLevel','point','recharge'))->find(" t.dpid='".$this->companyId ."'and ( t.selfcode like '%".$num."%' or t.rfid='".$num."' or t.mobile='".$num."')");           
                 $rfid = 0;
                if($card_model){
                  $rfid = $card_model->rfid;
                }

                $orderPay = OrderPay::model()->with('order4')->findAll("t.paytype=4 and t.paytype_id='".$rfid."' and t.dpid='".$this->companyId."'");


                if(!$card_model){
                 Yii::app()->user->setFlash('error' ,yii::t('app', '没有查询到该会员'));
                }
            }
        }    
       	$this->render('cardsearch',array( "card_model"=> $card_model,
                                            "num"=>$num,
                                             'orderPay'=>$orderPay
                                        )
                    );
}
 public function actionRecharge() {
        $model = new MemberRecharge;
        $model->dpid = $this->companyId;
        //Until::validOperate($model->dpid, $this);
        if(Yii::app()->request->isPostRequest) {
            
            $model->attributes = Yii::app()->request->getPost('MemberRecharge');
           // var_dump(Yii::app()->request->getPost('MemberRecharge'));

            $rfid = Yii::app()->request->getPost('rfid');

            //var_dump($rfid);
            // exit();
            $transaction=Yii::app()->db->beginTransaction();
            try{
                $member = MemberCard::model()->find('rfid=:rfid and selfcode=:selfcode and dpid=:dpid',array(':rfid'=>$rfid,':selfcode'=>$model->member_card_id,':dpid'=>$this->companyId));
                //Until::validOperate($member->lid, $this);
                //var_dump($member);exit;
                $member->all_money = $member->all_money + $model->reality_money + $model->give_money;

                $se = new Sequence("member_recharge");
           
                $model->lid = $se->nextval();
                $model->member_card_id = $rfid;
                $model->update_at = date('Y-m-d H:i:s',time());
                $model->create_at = date('Y-m-d H:i:s',time());
                $model->delete_flag = '0';
                if($model->save()&&$member->update()) {
                        $transaction->commit();
                                Yii::app()->user->setFlash('success',yii::t('app', '充值成功'));
                        }else{
                                $transaction->rollback();
                                Yii::app()->user->setFlash('error',yii::t('app', '充值失败'));
                        }
                }catch(Exception $e){
                        Yii::app()->user->setFlash('error' ,yii::t('app', '充值失败'));
                        $transaction->rollback();
                }
                $this->redirect(array('entityCard/recharge','companyId'=>$this->companyId));
        }
        $this->render('recharge' , array(
                        'model' => $model , 
        ));
 }
    public function actionGetMember() {


            $card = Yii::app()->request->getParam('card',0);
            $companid = Yii::app()->request->getParam('companyId');
            $criteria = new CDbCriteria;

            if($card){
                    $criteria->addCondition('rfid=:card');
                    $criteria->addCondition('selfcode=:card',"OR");
                    $criteria->addCondition('name=:card','OR');
                    $criteria->addCondition('mobile=:card','OR');
                    $criteria->params[':card']=$card;

            }
            $criteria->addCondition('delete_flag=0 and dpid = '.$companid );
            $criteria->order = ' lid desc ';


            $model = MemberCard::model()->find($criteria);
            if($model){
                    $res = array('rfid'=>$model->rfid,'selfcode'=>$model->selfcode,'all_money'=>$model->all_money,'name'=>$model->name,'mobile'=>$model->mobile,'email'=>$model->email);
                    Yii::app()->end(json_encode(array('status'=>true,'msg'=>$res)));
            }else{
                    Yii::app()->end(json_encode(array('status'=>false,'msg'=>'没有查询到该会员信息')));
            }

    }
    public function actionZero() {
            $model = new MemberRecharge;
            $model->dpid = $this->companyId;
            //Until::validOperate($model->dpid, $this);
            if(Yii::app()->request->isPostRequest) {

                $model->attributes = Yii::app()->request->getPost('MemberRecharge');
               // var_dump(Yii::app()->request->getPost('MemberRecharge'));

                $rfid = Yii::app()->request->getPost('rfid');

                //var_dump($rfid);
                // exit();
                $transaction=Yii::app()->db->beginTransaction();
                try{
                    $member = MemberCard::model()->find('rfid=:rfid and selfcode=:selfcode and dpid=:dpid',array(':rfid'=>$rfid,':selfcode'=>$model->member_card_id,':dpid'=>$this->companyId));
                    //Until::validOperate($member->lid, $this);
                    //var_dump($member);exit;
                    $member->all_money = "0";

                $se = new Sequence("member_recharge");

                $model->lid = $se->nextval();
                $model->member_card_id = $rfid;
                $model->update_at = date('Y-m-d H:i:s',time());
                $model->create_at = date('Y-m-d H:i:s',time());
                $model->delete_flag = '0';
                //var_dump($model);exit;
               if($model->save()&&$member->update()) {
                            $transaction->commit();
                                    Yii::app()->user->setFlash('success',yii::t('app', '清零成功'));
                            }else{
                                    $transaction->rollback();
                                    Yii::app()->user->setFlash('error',yii::t('app', '清零失败'));
                            }
                    }catch(Exception $e){
                            Yii::app()->user->setFlash('error' ,yii::t('app', '清零失败'));
                            $transaction->rollback();
                    }
                    $this->redirect(array('entityCard/zero','companyId'=>$this->companyId));
            }
            $this->render('zero' , array(
                            'model' => $model , 
            ));
     }
    public function actionAccountDetail(){

            $type = Yii::app()->request->getParam('type',"0");

            $orderid = Yii::app()->request->getParam('orderid',"0");
            $db = Yii::app()->db;
            if($type == 0){
                    $sql = 'select sum(t.zhiamount*t.amount) as all_amount,t1.set_name,t.* from nb_order_product t left join nb_product_set t1 on(t.dpid = t1.dpid and t.set_id = t1.lid) where t.dpid='.$this->companyId.' and t.order_id='.$orderid.' group by t.lid';
            }else{
                    $sql = 'select sum(t.zhiamount*t.amount) as all_amount,count(t.zhiamount) as all_zhiamount,sum(t2.retreat_amount) as retreat_num,t1.set_name,t.* from nb_order_product t left join nb_product_set t1 on(t.dpid = t1.dpid and t.set_id = t1.lid) left join nb_order_retreat t2 on(t.dpid = t2.dpid and t.lid = t2.order_detail_id) where t.dpid='.$this->companyId.' and t.order_id='.$orderid.' group by t.lid';
            }//var_dump($sql);exit;
            $allmoney = Yii::app()->db->createCommand($sql)->queryAll();
            $sql1 = 'select t.pay_amount from nb_order_pay t where t.paytype =11 and t.dpid ='.$this->companyId.' and t.order_id ='.$orderid;
            $model = Yii::app()->db->createCommand($sql1)->queryRow();
            $change = $model['pay_amount']?$model['pay_amount']:0;
            //var_dump($models);exit; 
            $sql2 = 'select sum(t.pay_amount) as all_money from nb_order_pay t where t.paytype in(0,11) and t.pay_amount >0 and t.dpid ='.$this->companyId.' and t.order_id ='.$orderid;
            $models = Yii::app()->db->createCommand($sql2)->queryRow();
            $money = $models['all_money']?$models['all_money']:0;

            $sql4 = 'select sum(t.pay_amount) as all_money from nb_order_pay t where t.pay_amount <0 and t.dpid ='.$this->companyId.' and t.order_id ='.$orderid;
            $models = Yii::app()->db->createCommand($sql4)->queryRow();
            $retreat = $models['all_money']?$models['all_money']:0;

            $sql3 = 'select t1.name,t.* from nb_order_pay t left join nb_payment_method t1 on(t.dpid = t1.dpid and t.payment_method_id = t1.lid) where t.paytype not in (0,11) and t.dpid='.$this->companyId.' and t.order_id='.$orderid.' group by t.payment_method_id,t.paytype';
            $allpayment = Yii::app()->db->createCommand($sql3)->queryAll();
            if(empty($allpayment)){
                    $allpayment = false;
            }
            Yii::app()->end(json_encode(array('status'=>true,'msg'=>$allmoney,'change'=>$change,'money'=>$money,'allpayment'=>$allpayment,'retreat'=>$retreat)));

    }
    public function actionActive() {       
        $companyId = Yii::app()->request->getParam('companyId');
        $begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
        $end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time())); 
       
        $number = Yii::app()->request->getParam('number','');  
        $models = array();
        $arr = array();
        if(Yii::app()->request->isPostRequest) {
            $sql = "select order_id,paytype_id from nb_order_pay where create_at >='".$begin_time." 00:00:00' and create_at <='".$end_time." 23:59:59' and paytype = 4 and  dpid = ".$companyId."  union select order_id,discount_id from nb_order_account_discount where create_at >='".$begin_time." 00:00:00' and create_at <='".$end_time." 23:59:59' and delete_flag = 0 and discount_type = 4 and dpid = ".$companyId;
            $order_rfid = Yii::app()->db->createCommand($sql)->queryAll();
              
            $arr_temp = array();            
            foreach($order_rfid as $val){
                $arr_temp[$val['paytype_id']][]=$val;  
            } 

                         
            $arr_tmp = array();
            foreach($arr_temp as $key => $val){
                $arr_tmp[$key]=count($val);  
            } 

                       
           
            $rfid = '';
            foreach($arr_tmp as $key => $val){
                if($val>$number){
                   $arr[$key] =  $val;
                   if($rfid == ''){
                    $rfid  = $key; 
                    }else{
                         $rfid .= ",".$key;
                    }   
                }      
            } 
          
          
            if($rfid!=''){
                $sql_models = "select * from nb_member_card where delete_flag = 0 and rfid in (".$rfid.") and  dpid = ".$companyId;           
                $models = Yii::app()->db->createCommand($sql_models)->queryAll();         
            } 
 
            
        }         
        $this->render('active' , array(
                    'models' => $models ,
                    'arr'=>$arr,
                    'begin_time'=>$begin_time,
                    'end_time'=>$end_time,
                    'number'=>$number,
        )); 
    }
    public function actionUnActive() {       
        $companyId = Yii::app()->request->getParam('companyId');
        $begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
        $end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time())); 
       
        $number = Yii::app()->request->getParam('number','');  
        $models = array();
        $arr = array();
        if(Yii::app()->request->isPostRequest) {
            $sql = "select order_id,paytype_id from nb_order_pay where create_at >='".$begin_time." 00:00:00' and create_at <='".$end_time." 23:59:59' and paytype = 4 and  dpid = ".$companyId."  union select order_id,discount_id from nb_order_account_discount where create_at >='".$begin_time." 00:00:00' and create_at <='".$end_time." 23:59:59' and delete_flag = 0 and discount_type = 4 and dpid = ".$companyId;
            $order_rfid = Yii::app()->db->createCommand($sql)->queryAll();
              
            $arr_temp = array();            
            foreach($order_rfid as $val){
                $arr_temp[$val['paytype_id']][]=$val;  
            } 

                         
            $arr_tmp = array();
            foreach($arr_temp as $key => $val){
                $arr_tmp[$key]=count($val);  
            } 

                       
           
            $rfid = '';
            foreach($arr_tmp as $key => $val){
                if($val<$number){
                   $arr[$key] =  $val;
                   if($rfid == ''){
                    $rfid  = $key; 
                    }else{
                         $rfid .= ",".$key;
                    }   
                }      
            } 
          
          
            if($rfid!=''){
                $sql_models = "select * from nb_member_card where delete_flag = 0 and rfid in (".$rfid.") and  dpid = ".$companyId;           
                $models = Yii::app()->db->createCommand($sql_models)->queryAll();         
            } 
 
            
        }         
        $this->render('unactive' , array(
                    'models' => $models ,
                    'arr'=>$arr,
                    'begin_time'=>$begin_time,
                    'end_time'=>$end_time,
                    'number'=>$number,
        )); 
    }
    public function actionDetail() {
        $companyId = Yii::app()->request->getParam('companyId');

        $criteria = new CDbCriteria;
        $criteria->addCondition(' t.delete_flag=0 and t.dpid = '.$companyId);
        $criteria->with = 'brandUserLevel';
        $models = MemberCard::model()->findAll($criteria);
        
        $this->render('detail', array(
                    'models' => $models ,
            ));
    }
    public function actionConsumeDetail() {
        $companyId = Yii::app()->request->getParam('companyId');
        $lid = Yii::app()->request->getParam('lid');
        $criteria = new CDbCriteria;
        $criteria->addCondition(' t.delete_flag=0 and t.lid = '.$lid.' and t.dpid = '.$companyId);
        $criteria->with = 'brandUserLevel';
        $model = MemberCard::model()->find($criteria);
        
        $this->render('consumedetail', array(
                    'model' => $model ,
            ));
    }
        
        
}
