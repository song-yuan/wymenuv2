<?php

class AreaGroupDetailController extends BackendController
{
    public function actionIndex()
    {
        $dpid = Yii::app()->request->getParam('companyId');
        $type = Yii::app()->request->getParam('type',1);
        $price_group_id = $_POST;
        
        $db = Yii::app()->db;
        $sql = 'select t.*,t1.area_group_id,t1.lid from nb_company t left join (select n.* from nb_area_group_company n,nb_area_group n1 where n.dpid=n1.dpid and n.area_group_id=n1.lid and n1.type='.$type.' and n.delete_flag=0 and n1.delete_flag=0) t1 on t.dpid=t1.company_id where t.comp_dpid='.$dpid.' and t.type=1 and t.delete_flag=0';
        $models = Yii::app()->db->createCommand($sql)->queryALL();

        $sql2 = 'select * from nb_area_group where dpid = '.$dpid.' and type='.$type.' and delete_flag=0';
        $groups = Yii::app()->db->createCommand($sql2)->queryALL();

        if(Yii::app()->request->isPostRequest) {
        	
            foreach($price_group_id as $did =>$price_group_idd){
            	if($price_group_idd==0){
            		continue;
            	}
            	//var_dump($price_group_id);exit;
                $model = AreaGroupCompany::model()->find('dpid=:dpid and company_id=:companyId and delete_flag=0',array(':dpid'=>$dpid,':companyId'=>$did));
                //exit;
                // p($model);
                if ($model) {
                    $model->saveAttributes(array('area_group_id'=>$price_group_idd,'update_at'=>date('Y-m-d H:i:s',time())));
                }else{
                    $se=new Sequence("area_group_company");
                    $lid = $se->nextval();
                    // p($lid);
                    $data = array(
                            'lid'=>$lid,
                            'dpid'=>$dpid,
                            'update_at'=>date('Y-m-d H:i:s',time()),
                            'area_group_id'=>$price_group_idd,
            				'company_id'=>$did,
                            'delete_flag'=>'0',
                    );
                    $command = $db->createCommand()->insert('nb_area_group_company',$data);
                }
            }
            Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
            $this->redirect(array('areaGroupDetail/index' , 'companyId' => $dpid,'type'=>$type));
        }
        // p($models);
        $this->render('index',array(
            'models'=>$models,
            'groups'=>$groups,
        	'type'=>$type
        ));
    }


    public function actionStore()
    {
    	$dpid = Yii::app()->request->getParam('companyId');
        $pcode = Yii::app()->request->getParam('arr');
        $arr = explode(':',$pcode);
        //var_dump($arr);exit;

        $db = Yii::app()->db;
        $model = AreaGroupCompany::model()->find('company_id=:dpid and delete_flag=0',array(':dpid'=>$arr[0]));
        //var_dump($model);exit;
        if ($model) {
            $command = $model->saveAttributes(array('area_group_id'=>$arr[1],'update_at'=>date('Y-m-d H:i:s',time())));
        }else{
            $se=new Sequence("area_group_company");
            $lid = $se->nextval();
            // p($lid);
            $data = array(
                    'lid'=>$lid,
                    'dpid'=>$dpid,
                    'update_at'=>date('Y-m-d H:i:s',time()),
                    'area_group_id'=>$arr[1],
            		'company_id'=>$arr[0],
                    'delete_flag'=>'0',
            );
            $command = $db->createCommand()->insert('nb_area_group_company',$data);
        }
        if($command){
        	echo true;
        }else{
        	echo false;
        }
        exit;
    }

}