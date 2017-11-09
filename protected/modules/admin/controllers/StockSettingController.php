<?php
class StockSettingController extends BackendController
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
	public function actionIndex(){
        $model = StockSetting::model()->find('dpid=:dpid and delete_flag=0',array(':dpid'=>$this->companyId));
        if(!$model){
            $model = new StockSetting();
            $se=new Sequence("stock_setting");
            $model->lid = $se->nextval();
            $model->create_at = date('Y-m-d H:i:s',time());
            $model->update_at = date('Y-m-d H:i:s',time());
            $model->dpid = $this->companyId ;
        }

        if(Yii::app()->request->isPostRequest){
            $model->attributes = Yii::app()->request->getPost('StockSetting');
            $a = $model->save();
			if($a){
                Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
                $this->redirect(array('StockSetting/index' , 'companyId' => $this->companyId ));
            }else{
            	Yii::app()->user->setFlash('error',yii::t('app','设置失败！'));
            	$this->redirect(array('StockSetting/index' , 'companyId' => $this->companyId ));
            }
        }
		$this->render('index',array(
				'model'=>$model,
		));
	}

}