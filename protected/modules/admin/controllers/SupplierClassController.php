<?php
class SupplierClassController extends BackendController{
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
    public function beforeAction($action) {
        parent::beforeAction($action);
        if(!$this->companyId && $this->getAction()->getId() != 'upload') {
            Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
            $this->redirect(array('company/index'));
        }
        return true;
    }


    //供应商分类
    public function actionIndex(){
        $categoryId = Yii::app()->request->getParam('cid',0);
        $criteria = new CDbCriteria;
        $criteria->with = 'company';
        $criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId;
        $criteria->order = ' t.lid desc ';
        $pages = new CPagination(ManufacturerClassification::model()->count($criteria));
        //$pages->setPageSize(1);
        $pages->applyLimit($criteria);
        $models = ManufacturerClassification::model()->findAll($criteria);
        $this->render('index',array(
            'models'=>$models,
            'pages'=>$pages,
            'categoryId'=>$categoryId
        ));
    }

    //添加供应商分类
    public function actionCreate(){
        $model = new ManufacturerClassification();
        $model->dpid = $this->companyId ;

        if(Yii::app()->request->isPostRequest) {
            $model->attributes = Yii::app()->request->getPost('ManufacturerClassification');
            $se=new Sequence("manufacturer_classification");
            $model->lid = $se->nextval();
            $model->create_at = date('Y-m-d H:i:s',time());
            $model->update_at = date('Y-m-d H:i:s',time());
            $model->delete_flag = '0';
            //var_dump($model);exit;
            if($model->save()){
                Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
                $this->redirect(array('supplierClass/index' , 'companyId' => $this->companyId ));
            }
        }
        $categories = ManufacturerClassification::model()->findAll('delete_flag=0 and dpid=:companyId' , array(':companyId' => $this->companyId)) ;
        //var_dump($categories);exit;
        $this->render('create' , array(
            'model' => $model ,
            'categories' => $categories
        ));
    }

    //编辑供应商分类
    public function actionUpdate(){
        $id = Yii::app()->request->getParam('id');
        $model = ManufacturerClassification::model()->find('lid=:materialId and dpid=:dpid' , array(':materialId' => $id,':dpid'=>  $this->companyId));
        $model->dpid = $this->companyId;

        if(Yii::app()->request->isPostRequest) {
            $model->attributes = Yii::app()->request->getPost('ManufacturerClassification');
            $model->update_at=date('Y-m-d H:i:s',time());
            if($model->save()){
                Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
                $this->redirect(array('supplierClass/index' , 'companyId' => $this->companyId ));
            }
        }

        $this->render('update' , array(
            'model' => $model ,
        ));
    }

    //删除供应商分类
    public function actionDelete(){
        $companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
        $ids = Yii::app()->request->getPost('ids');

        if(!empty($ids)) {
            Yii::app()->db->createCommand('update nb_manufacturer_classification set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
                ->execute(array( ':companyId' => $this->companyId));
            $this->redirect(array('supplierClass/index' , 'companyId' => $companyId)) ;
        } else {
            Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
            $this->redirect(array('supplierClass/index' , 'companyId' => $companyId)) ;
        }
    }

}