<?php
class WxMemberSourceController extends BackendController
{       public function actions() {
		return array(
				'upload'=>array(
						'class'=>'application.extensions.swfupload.SWFUploadAction',
						//注意这里是绝对路径,.EXT是文件后缀名替代符号
						'filepath'=>Helper::genFileName().'.EXT',
						//'onAfterUpload'=>array($this,'saveFile'),
				)
		);
	}

         public function actionIndex() {
             $models = WxMemberSource::model()->findAll(array('condition'=>'delete_flag < 1'));  
		
		$this->render('index',array(
				'models'=> $models,
				
		));
    }
     public function actionCreate() {
        $model = new WxMemberSource();
        if(Yii::app()->request->isPostRequest) {
             $source = Yii::app()->request->getPost('WxMemberSource');
                $se=new Sequence("wx_member_source");
                $lid = $se->nextval();
                $model->lid = $lid;
              
               $model->channel_name = $source['channel_name'];
                $model->channel_comment = $source['channel_comment'];
              
            //var_dump($model);exit;
            if($model->save()){
                    Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
                    $this->redirect(array('WxMemberSource/index' , 'companyId' => $this->companyId ));
            }         
        }
       
        
        return $this->render('create',array(
				'model' => $model,
                               
                                 
                            ));
    }
    public function actionDelete(){
            $ids = Yii::app()->request->getPost('sourceIds');
           
            if(!empty($ids)) {
                    Yii::app()->db->createCommand('update nb_wx_member_source set delete_flag=1,update_at="'.date('Y-m-d H:i:s',time()).'" where lid in ('.implode(',' , $ids).')')
                    ->execute();

            }
            $this->redirect(array('WxMemberSource/index','companyId'=>$this->companyId));
    }
     public function actionUpdate() {
          //通过get方法接收要展示的信息的主键。
        $lid = Yii::app()->request->getParam('lid');
       
        //在数据库查找该主键对应的条目。
        $model =  WxMemberSource::model()->find('lid=:lid' , array(':lid' => $lid)) ;
        if(Yii::app()->request->isPostRequest) {
            $model->attributes = Yii::app()->request->getPost('WxMemberSource');
            
            if($model->save()){
                Yii::app()->user->setFlash('success',yii::t('app','更新成功！'));
                $this->redirect(array('WxMemberSource/index' , 'companyId' => $this->companyId ));
            }  
        }
        return $this->render('update' , array(
				'model'=>$model,
		));
        
     }
        
}