<?php
class SupplierController extends BackendController{
    //展示页面和查询
    public function actionIndex(){
        $criteria = new CDbCriteria;
        $criteria->with = array('company','mfrclass');
        $criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId;
        $pages = new CPagination(ManufacturerInformation::model()->count($criteria));

        $pages->applyLimit($criteria);
        $models = ManufacturerInformation::model()->findAll($criteria);
        $this->render('index',array(
            'models'=>$models,
            'pages'=>$pages
        ));
    }

    //添加
    public function actionCreate(){
        $model = new ManufacturerInformation();
        $model->dpid = $this->companyId ;
        if(Yii::app()->request->isPostRequest) {
            $model->attributes = Yii::app()->request->getPost('ManufacturerInformation');
            $seq=new Sequence("manufacturer_information");
            $model->lid = $seq->nextval();
            $model->create_at = date('Y-m-d H:i:s',time());
            $model->update_at = date('Y-m-d H:i:s',time());
            $model->delete_flag = '0';

            /*$sup_name=$model->manufacturer_name;
            $ordusername = ManufacturerInformation::model()->find('manufacturer_name=:name' , array(':name'=>$sup_name));
            if($ordusername){
                Yii::app()->user->setFlash('error' ,yii::t('app', '该供货商已存在，请重新添加！！！'));
                $this->redirect(array('supplier/create' , 'companyId' => $this->companyId));
            }*/
            // var_dump($model);exit;
            if($model->save()){
                Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
                $this->redirect(array('supplier/index' , 'companyId' => $this->companyId ));
            }
        }
        $this->render('create' , array(
            'model' => $model ,
        ));

    }

    //编辑更新
    public function actionUpdate(){
        $id = Yii::app()->request->getParam('id');
        $model = ManufacturerInformation::model()->find('lid=:manufacturerId and dpid=:dpid' , array(':manufacturerId' => $id,':dpid'=>  $this->companyId));
        $model->dpid = $this->companyId;
        if(Yii::app()->request->isPostRequest) {
            $model->attributes = Yii::app()->request->getPost('ManufacturerInformation');
            $model->update_at=date('Y-m-d H:i:s',time());
            //var_dump($model->attributes);exit;
            if ($model->save()) {
                Yii::app()->user->setFlash('success',yii::t('app','修改成功'));
                $this->redirect(array('supplier/index','companyId' => $this->companyId));
            }else{
                Yii::app()->user->setFlash('error' ,yii::t('app', '修改失败,请重试'));
                $this->redirect(array('supplier/index','companyId' => $this->companyId));
            }
        }
        $this->render('update' , array('model' => $model)) ;
    }

    //删除 改变delete_flag的值，不是彻底删除，这样有助于在数据库找回被删除的数据
    public function actionDelete(){
        $companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
        $ids = Yii::app()->request->getPost('ids');

        if(!empty($ids)) {
            Yii::app()->db->createCommand('update nb_manufacturer_information set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
                ->execute(array( ':companyId' => $this->companyId));
            $this->redirect(array('supplier/index' , 'companyId' => $companyId)) ;
        } else {
            Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
            $this->redirect(array('supplier/index' , 'companyId' => $companyId)) ;
        }
    }

    public function getClassName($mfrId){
        //var_dump($stockId);
        $mfrname = "";
        $sql="select t.classification_name from nb_manufacturer_classification t where  t.lid='".$mfrId ."' order by lid desc";
        $connect = Yii::app()->db->createCommand($sql);
        $mfr = $connect->queryRow();
        //var_dump($stock);exit;
        $mfrname = $mfr['classification_name'];
        return $mfrname;
    }


}