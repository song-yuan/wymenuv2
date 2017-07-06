<?php

class ProductLabelController extends BackendController
{
    public function actionIndex()
    {
        $sql = 'select t.*,p.lid as plid from nb_product t left join nb_product_label p on( t.lid=p.product_id and p.delete_flag=0 and p.dpid='.$this->companyId.') where t.delete_flag=0 and t.dpid='.$this->companyId.' order by plid desc';
        $models = Yii::app()->db->createCommand($sql)->queryAll();
        $this->render('index',array(
            'models'=>$models,
        ));
    }
    public function actionLabeldetail()
    {
        $plid = Yii::app()->request->getParam('product_id');
        $pname = Yii::app()->request->getParam('product_name');
        $is_sync = DataSync::getInitSync();
        $db = Yii::app()->db;
        // p($pname);
        $sql = 'select l.*,d.font_size,d.content,d.lid as dlid from nb_product_label l left join nb_product_label_detail d on(l.lid = d.product_label_id and d.delete_flag=0 and l.dpid=d.dpid) where l.delete_flag=0 and l.dpid='.$this->companyId.' and l.product_id='.$plid;
        $models = Yii::app()->db->createCommand($sql)->queryAll();
        if (Yii::app()->request->isPostRequest) {
        // p($_POST);
            if (!empty($_POST)) {
                $transaction = $db->beginTransaction();
                try{
                    if ($_POST['llid']) {
                        $info = ProductLabel::model()->find('lid=:lid and delete_flag=0 and dpid=:dpid',array(':lid'=>$_POST['llid'],':dpid'=>$this->companyId));
                        $info->update_at=date('Y-m-d H:i:s');
                        $info->is_print_date=$_POST['print_date'];
                        $info->is_print_bar=$_POST['print_bar'];
                        $info->delete_flag=0;
                        if($info->save()){
                            foreach ($_POST['lid'] as $key => $lid) {
                                if ($lid) {
                                    $infod = ProductLabelDetail::model()->find('lid=:lid and delete_flag=0 and dpid=:dpid',array(':lid'=>$lid,':dpid'=>$this->companyId));
                                    $infod->update_at=date('Y-m-d H:i:s');
                                    $infod->product_label_id=$_POST['llid'];
                                    $infod->font_size=$_POST['font_size'][$key];
                                    $infod->content=$_POST['content'][$key];
                                    $infod->delete_flag=0;
                                    $commd = $infod->save();
                                }else{
                                        $see = new Sequence("product_label_detail");
                                        $idd = $see->nextval();
                                        $data = array(
                                                'lid'=>$idd,
                                                'dpid'=>$this->companyId,
                                                'create_at'=>date('Y-m-d H:i:s',time()),
                                                'update_at'=>date('Y-m-d H:i:s',time()),
                                                'product_label_id'=>$_POST['llid'],
                                                'font_size'=>$_POST['font_size'][$key],
                                                'content'=> $_POST['content'][$key],
                                                'delete_flag'=>'0',
                                                'is_sync'=>$is_sync,
                                        );
                                        $commands = Yii::app()->db->createCommand()->insert('nb_product_label_detail',$data);
                                }
                            }
                        }
                    }else{
                        $se = new Sequence("product_label");
                        $id = $se->nextval();
                        $data = array(
                                'lid'=>$id,
                                'dpid'=>$this->companyId,
                                'create_at'=>date('Y-m-d H:i:s',time()),
                                'update_at'=>date('Y-m-d H:i:s',time()),
                                'product_id'=>$plid,
                                'is_print_date'=>$_POST['print_date'],
                                'is_print_bar'=> $_POST['print_bar'],
                                'delete_flag'=>'0',
                                'is_sync'=>$is_sync,
                        );
                        $command = $db->createCommand()->insert('nb_product_label',$data);
                        if($command){
                                foreach ($_POST['lid'] as $key => $lid) {
                                    if ($lid) {
                                        $infod = ProductLabelDetail::model()->find('lid=:lid and delete_flag=0 and dpid=:dpid',array(':lid'=>$lid,':dpid'=>$this->companyId));
                                        $infod->update_at=date('Y-m-d H:i:s');
                                        $infod->product_label_id=$id;
                                        $infod->font_size=$_POST['font_size'][$key];
                                        $infod->content=$_POST['content'][$key];
                                        $infod->delete_flag=0;
                                        $infod->save();
                                    }else{
                                        $see = new Sequence("product_label_detail");
                                        $idd = $see->nextval();
                                        $datas = array(
                                                'lid'=>$idd,
                                                'dpid'=>$this->companyId,
                                                'create_at'=>date('Y-m-d H:i:s',time()),
                                                'update_at'=>date('Y-m-d H:i:s',time()),
                                                'product_label_id'=>$id,
                                                'font_size'=>$_POST['font_size'][$key],
                                                'content'=> $_POST['content'][$key],
                                                'delete_flag'=>'0',
                                                'is_sync'=>$is_sync,
                                        );
                                        // p($datas);
                                        $commands = $db->createCommand()->insert('nb_product_label_detail',$datas);
                                        // p($command);
                                    }
                                }
                        }
                    }

                    $transaction->commit();
                    Yii::app()->user->setFlash('success' , yii::t('app','保存成功！！！'));
                    $this->redirect(array('productLabel/index' , 'companyId' => $this->companyId,)) ;
                }catch (Exception $e){
                    $transaction->rollback();
                    Yii::app()->user->setFlash('error' , yii::t('app','保存失败！！！'));
                    $this->redirect(array('productLabel/index' , 'companyId' => $this->companyId,)) ;
                }


            }
        }
        $this->render('labeldetail',array(
            'models'=>$models,
            'plid'=>$plid,
            'pname'=>$pname,
        ));
    }

        public function actiondelete()
    {
        $plid = Yii::app()->request->getParam('product_id');
        $pname = Yii::app()->request->getParam('product_name');
        $lid = Yii::app()->request->getParam('lid');
        $dpid = Yii::app()->request->getParam('companyId');
        // p($dpid);
        $sql = 'update nb_product_label_detail set update_at ="'.date('Y-m-d H:i:s',time()).'",delete_flag =1 where dpid ='.$dpid.' and lid='.$lid;
        $info = Yii::app()->db->createCommand($sql)->execute();
        // p($info);
        if ($info) {
                Yii::app()->user->setFlash('success' , yii::t('app','删除成功'));
                $this->redirect(array('productLabel/labeldetail' , 'companyId' => $this->companyId,'product_id'=>$plid,'product_name'=>$pname));
        }


    }
}