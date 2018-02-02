<?php

class DepotGroupController extends BackendController
{
    public function actionIndex()
    {
        $provinces = Yii::app()->request->getParam('province',0);
        $citys = Yii::app()->request->getParam('city',0);
        $areas = Yii::app()->request->getParam('area',0);
        $cname = Yii::app()->request->getParam('cname','');
        $dpid = Yii::app()->request->getParam('companyId');
        $price_group_ids = $_POST;
        //var_dump($price_group_id);exit;
        $db = Yii::app()->db;
        $provinced = '';
        $cityd = '';
        $aread = '';
        if($citys == '请选择..'){
            $citys = '';
        }else{
            $cityd =' and city like "'.$citys.'"';
        }
        if($areas == '请选择..'){
            $areas = '';
        }else{
            $aread = ' and county_area like "'.$areas.'"';
        }
        if ($provinces == '请选择..') {
            $provinces = '';
        }else{
            $provinced = ' and province like "'.$provinces.'"';
        }
        if ($cname) {
            if (is_numeric($cname)) {
                $cnamed = ' and mobile like "%'.$cname.'%"';
            }else{
                $cnamed = 'and (contact_name like "%'.$cname.'%" or company_name like "%'.$cname.'%")';
            }
        }else{
            $cnamed = '';
        }

        $sql = 'select t.*,t2.material_price_group_id,t2.lid from nb_company t'
            .' left join nb_company_property t2 on(t.dpid=t2.dpid)'
            .' where t.dpid in(select dpid from nb_company where delete_flag=0 and comp_dpid='.$dpid .' and type=1 '.$provinced.$cityd.$aread.$cnamed.') and t.delete_flag=0';
        $models = Yii::app()->db->createCommand($sql)->queryALL();
        // p($models);

        $sql2 = 'select * from nb_material_price_group where dpid = '.$dpid.' and delete_flag=0';
        $groups = Yii::app()->db->createCommand($sql2)->queryALL();

        if(Yii::app()->request->isPostRequest) {
            foreach($price_group_ids as $did =>$price_group_id){
                $model = CompanyProperty::model()->find('dpid=:dpid and delete_flag=0',array(':dpid'=>$did));
                // p($model);
                if ($model) {
                    $model->saveAttributes(array('material_price_group_id'=>$price_group_id,'update_at'=>date('Y-m-d H:i:s',time())));
                }else{
                    $se=new Sequence("company_property");
                    $lid = $se->nextval();
                    // p($lid);
                    $data = array(
                            'lid'=>$lid,
                            'dpid'=>$did,
                            'update_at'=>date('Y-m-d H:i:s',time()),
                            'material_price_group_id'=>$price_group_id,
                            'delete_flag'=>'0',
                    );
                    $command = $db->createCommand()->insert('nb_company_property',$data);
                }
            }
            Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
            $this->redirect(array('depotGroup/index' , 'companyId' => $dpid));
        }
        // p($models);
        $this->render('index',array(
            'models'=>$models,
            'groups'=>$groups,
            'province'=>$provinces,
            'city'=>$citys,
            'area'=>$areas,
            'cname'=>$cname,
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
            $model->saveAttributes(array('material_price_group_id'=>$arr[1],'update_at'=>date('Y-m-d H:i:s',time())));
        }else{
            $se=new Sequence("company_property");
            $lid = $se->nextval();
            $data = array(
                    'lid'=>$lid,
                    'dpid'=>$arr[0],
                    'update_at'=>date('Y-m-d H:i:s',time()),
                    'material_price_group_id'=>$arr[1],
                    'delete_flag'=>'0',
            );
            $command = $db->createCommand()->insert('nb_company_property',$data);
        }
            Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
            $this->redirect(array('companyGroup/index' , 'companyId' => $dpid));
    }

}