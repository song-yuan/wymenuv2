<?php
class StaffRechargeController extends BackendController {
    public function actionIndex() {
        $companyId = Yii::app()->request->getParam('companyId');
        $level_id = Yii::app()->request->getParam('level_id');
        $sql = "select * from nb_brand_user_level where  delete_flag = 0 and level_type=0 and dpid = ".$companyId;
        $levels = Yii::app()->db->createCommand($sql)->queryAll();
        $criteria = new CDbCriteria;
        $criteria->condition = 't.delete_flag=0 and t.dpid='.$companyId;
        $criteria->with = 'brandUserLevel';
        if($level_id != 0){
            $criteria->addCondition("t.level_id = ".$level_id);
        }
        $models = MemberCard::model()->findAll($criteria);
        $this->render('index',array(
				'models'=>$models,
                                'levels'=>$levels,
                                'level_id'=>$level_id,
		));
    }
    public function actionRecharge() {           
            $companyId = Yii::app()->request->getParam('companyId');          
            $users = Yii::app()->request->getParam('users');        
            $rmoney = Yii::app()->request->getParam('rmoney');
            $gmoney = Yii::app()->request->getParam('gmoney');

            $criteria = new CDbCriteria;
            $criteria->select = '*';
            $criteria->condition = 'delete_flag=0 and lid in ('.$users.') and dpid='.$companyId;
            $models = MemberCard::model()->findAll($criteria);
            $transaction=Yii::app()->db->beginTransaction();
            try{
                foreach ($models as $model){
                    $model->update_at = date('Y-m-d H:i:s',time());
                    $model->all_money = $rmoney + $gmoney;
                    $model->update();
                    $recharge = new MemberRecharge;
                    $se = new Sequence("member_recharge");           
                    $recharge->lid = $se->nextval();             
                    $recharge->dpid = $companyId;
                    $recharge->update_at = date('Y-m-d H:i:s',time());
                    $recharge->create_at = date('Y-m-d H:i:s',time());
                    $recharge->member_card_id = $model['rfid'];
                    $recharge->reality_money = $rmoney;
                    $recharge->give_money = $gmoney;
                    $recharge->delete_flag = '0';
                    $recharge->save();
                }               
                $transaction->commit();
                $status = true;       
               
            }catch(Exception $e){               
                $transaction->rollback();
                $status = false;      
            }            
            echo $status;
            exit;  
    }
}
