<?php
class WxMessageController extends BackendController
{

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
    		Yii::app()->user->setFlash('error' , '请选择公司˾');
    		$this->redirect(array('company/index'));
    	}
    	return true;
    }
    
    public function actionIndex(){
        $criteria = new CDbCriteria;
        $criteria->condition =  't.dpid=:dpid and t.delete_flag=0';
        $criteria->order = ' t.lid desc ';
        $criteria->params[':dpid']=$this->companyId;

        $pages = new CPagination(WeixinMessagetpl::model()->count($criteria));
        //$pages->setPageSize(1);
        $pages->applyLimit($criteria);

        $models = WeixinMessagetpl::model()->findAll($criteria);
        //var_dump($models);exit;

        $this->render('index',array(
                        'models'=> $models,
                        'pages' => $pages
        ));
    } 
    
    public function actionCreate() {
        $message_type[0] = "支付成功通知";
        $message_type[1] = "代金券领取";
        
        $model = new WeixinMessagetpl();
        
        $model->dpid = $this->companyId ;
        if(Yii::app()->request->isPostRequest) {
            $message = Yii::app()->request->getPost('WeixinMessagetpl');
            $se=new Sequence("weixin_messagetpl");
            $lid = $se->nextval();
            $model->lid = $lid;            
            $model->message_type = $message['message_type'];
            $model->message_tpl_id = $message['message_tpl_id'];
            $model->create_at = date('Y-m-d H:i:s',time());
            $model->update_at = date('Y-m-d H:i:s',time());
            if($model->save()){
                        Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
                        $this->redirect(array('wxMessage/index' , 'companyId' => $this->companyId ));
                }         
            }
        
        return $this->render('create',array('model' => $model,
                              'message_type'=>$message_type));
    }
    
    public function actionUpdate() {
        $message_type[0] = "支付成功通知";
        $message_type[1] = "代金券领取";
        //通过get方法接收要展示的信息的主键。
        $lid = Yii::app()->request->getParam('lid');
        //在数据库查找该主键对应的条目。
        $model = WeixinMessagetpl::model()->find('lid=:lid and dpid=:dpid' , array(':lid' => $lid,':dpid'=> $this->companyId)) ;
        if(Yii::app()->request->isPostRequest) {
            $message = Yii::app()->request->getPost('WeixinMessagetpl');
            $model->message_type = $message['message_type'];
            $model->message_tpl_id = $message['message_tpl_id'];
            $model->update_at=date('Y-m-d H:i:s',time());

            if($model->save()){
                    Yii::app()->user->setFlash('success',yii::t('app','修改成功'));
                    $this->redirect(array('wxMessage/index','companyId'=>$this->companyId));
            } else {
                    Yii::app()->user->setFlash('error',yii::t('app','修改失败'));
            }
        }
        $this->render('update',array('model'=> $model, 'message_type'=>$message_type));
    }
    
    public function actionDelete(){
    	$ids = Yii::app()->request->getPost('Ids');
    	if(!empty($ids)) {
    		Yii::app()->db->createCommand('update nb_weixin_messagetpl set delete_flag=1 where dpid = '.$this->companyId.' and lid in ('.implode(',' , $ids).')')->execute();
    		Yii::app()->user->setFlash('success' , yii::t('app','删除成功'));
    		$this->redirect(array('wxMessage/index' , 'companyId' => $this->companyId)) ;
    	}else {
    		Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
    		$this->redirect(array('wxMessage/index' , 'companyId' => $this->companyId)) ;
    	}
    }        

    
}
