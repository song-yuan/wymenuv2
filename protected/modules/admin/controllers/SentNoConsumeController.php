<?php
class SentNoConsumeController extends BackendController {
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
        $companyId = Yii::app()->request->getParam('companyId');
        $db=Yii::app()->db;
        $order_sql = 'select ifnull(k.user_id,0000000000) as user_id from nb_order k where k.order_type in (1,2,3,6) and k.order_status in(3,4,8) and k.dpid = '.$companyId.' group by k.user_id';
        $orders = $db->createCommand($order_sql)->queryAll();
        $user_id ='0000000000';
        foreach ($orders as $order){
                $user_id = $user_id .','.$order['user_id'];
        }
        $sql="select t.lid,t.dpid,t.card_id,t.user_name,t.nickname,t.sex,t.user_birthday,tl.level_name,t.weixin_group,t.country "
            .",t.province,t.city,t.mobile_num,com.dpid,com.company_name"				
            . " from nb_brand_user t "
            . " LEFT JOIN  nb_company com on com.dpid = t.weixin_group "  
            . " LEFT JOIN nb_brand_user_level tl on tl.dpid = t.dpid and tl.lid = t.user_level_lid and tl.delete_flag = 0 and tl.level_type = 1 "            
            . " where t.lid not in(".$user_id.") and (t.dpid=".$companyId." or t.weixin_group =".$companyId.")";
        $models = $db->createCommand($sql)->queryAll();
        
        $this->render('index',array('models'=>$models,
                                 )); 
    }
    public function actionAddprod() {
        $this->layout = '/layouts/main_picture';
        $users = Yii::app()->request->getParam('users',0);

        $criteria = new CDbCriteria;
        $criteria->condition =  't.is_available = 0 and t.delete_flag=0 and t.dpid='.$this->companyId.' and t.end_time >="'.date('Y-m-d H:i:s',time()).'"';
        $criteria->order = ' t.lid asc ';
        $models = Cupon::model()->findAll($criteria);
        
        $this->render('addprod' , array(
                        'models' => $models,
                        'users' => $users,
                        'action' => $this->createUrl('SentNoConsume/addprod' , array('companyId'=>$this->companyId))
        ));
    }
    
    public function actionStorsentwxcard(){
        $is_sync = DataSync::getInitSync();
        $plids = Yii::app()->request->getParam('plids');
        $users = Yii::app()->request->getParam('users');
        $dpid = $this->companyId;
        $materialnums = array();
        $materialnums = explode(';',$plids);

        $userarrays = array();
        $userarrays = explode(',',$users);
        $msg = '';
        $db = Yii::app()->db;
        //var_dump($userarrays);exit;
        $transaction = $db->beginTransaction();
        try{
            //var_dump($materialnums);exit;
            foreach ($userarrays as $userarray){
                //var_dump($userarray);exit;
                foreach ($materialnums as $materialnum){
                    $materials = array();
                    $materials = explode(',',$materialnum);
                    $plid = $materials[0];
                    $pcode = $materials[1];
                    //var_dump($plid.'@'.$pcode);exit;
                    $cupons = Cupon::model()->find('lid=:lid and dpid=:companyId and delete_flag=0' , array(':lid'=>$plid,':companyId'=>$this->companyId));
                    
                    if(!empty($cupons)&&!empty($plid)){
                        $se = new Sequence("cupon_branduser");
                        $id = $se->nextval();
                         $data = array(
                                        'lid'=>$id,
                                        'dpid'=>$dpid,
                                        'create_at'=>date('Y-m-d H:i:s',time()),
                                        'update_at'=>date('Y-m-d H:i:s',time()),
                                        'cupon_id'=>$plid,
                                        'cupon_source'=>'2',
                                        'source_id'=>'0000000000',
                                        'to_group'=>'3',
                                        'brand_user_lid'=>$userarray,
                                        'is_used'=>'1',
                                        'used_time'=>'0000-00-00 00:00:00',
                                        'delete_flag'=>'0',
                                        'is_sync'=>$is_sync,
                                        );
                        //$msg = $prodid.'@@'.$mateid.'@@'.$prodmaterials['product_name'].'@@'.$prodmaterials['phs_code'].'@@'.$prodcode;
                        //var_dump($data);exit;
                        $command = $db->createCommand()->insert('nb_cupon_branduser',$data);
                        //exit;	
                    }
                }
            }
            //Yii::app()->end(json_encode(array('status'=>true,'msg'=>$msg)));
            $transaction->commit(); //提交事务会真正的执行数据库操作
            Yii::app()->end(json_encode(array('status'=>true,'msg'=>$msg)));
        } catch (Exception $e) {
            $transaction->rollback(); //如果操作失败, 数据回滚
            Yii::app()->end(json_encode(array('status'=>false,'msg'=>'保存失败',)));
        }
    }
}