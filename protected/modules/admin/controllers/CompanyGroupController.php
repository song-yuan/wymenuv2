<?php

class CompanyGroupController extends BackendController
{
    public function actionIndex()
    {
        $dpid = Yii::app()->request->getParam('companyId');
        $price_group_id = $_POST;
        //var_dump($price_group_id);exit;
        $db = Yii::app()->db;
        $sql = 'SELECT t.*,t2.price_group_id,t2.lid FROM `nb_company` `t` left join nb_company_property t2 on(t.dpid=t2.dpid) WHERE t.dpid in(
        select dpid from nb_company where delete_flag=0 and comp_dpid='.$dpid .') and t.delete_flag=0';
        $models = Yii::app()->db->createCommand($sql)->queryALL();

        $sql2 = 'select * from nb_price_group where dpid = '.$dpid.' and delete_flag=0';
        $groups = Yii::app()->db->createCommand($sql2)->queryALL();


        if(Yii::app()->request->isPostRequest) {
            foreach($price_group_id as $did =>$price_group_idd){
                $model = CompanyProperty::model()->find('dpid=:dpid and delete_flag=0',array(':dpid'=>$did));
                // p($model);
                if ($model) {
                    $model->saveAttributes(array('price_group_id'=>$price_group_idd,'update_at'=>date('Y-m-d H:i:s',time())));
                }else{
                    $se=new Sequence("company_property");
                    $lid = $se->nextval();
                    // p($lid);
                    $data = array(
                            'lid'=>$lid,
                            'dpid'=>$did,
                            'update_at'=>date('Y-m-d H:i:s',time()),
                            'price_group_id'=>$price_group_idd,
                            'delete_flag'=>'0',
                    );
                    $command = $db->createCommand()->insert('nb_company_property',$data);
                }
            }
            Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
            $this->redirect(array('companyGroup/index' , 'companyId' => $dpid));
        }
        // p($models);
        $this->render('index',array(
            'models'=>$models,
            'groups'=>$groups,
        ));
    }


    public function actionStore()
    {
        $pcode = Yii::app()->request->getParam('arr');
        $arr = explode(':',$pcode);
        // var_dump($arr);exit;


        $model = CompanyProperty::model()->find('dpid=:dpid and delete_flag=0',array(':dpid'=>$arr[0]));
        // p($model);
        if ($model) {
            $model->saveAttributes(array('price_group_id'=>$arr[1],'update_at'=>date('Y-m-d H:i:s',time())));
        }else{
            $se=new Sequence("company_property");
            $lid = $se->nextval();
            // p($lid);
            $data = array(
                    'lid'=>$lid,
                    'dpid'=>$arr[0],
                    'update_at'=>date('Y-m-d H:i:s',time()),
                    'price_group_id'=>$arr[1],
                    'delete_flag'=>'0',
            );
            $command = $db->createCommand()->insert('nb_company_property',$data);
        }
            Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
            $this->redirect(array('companyGroup/index' , 'companyId' => $dpid));
    }

}