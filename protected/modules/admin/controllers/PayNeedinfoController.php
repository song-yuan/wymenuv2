<?php
class PayNeedinfoController extends BackendController
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
                 $model=new PayNeedinfo();
                
                
               $this->render('index',array(
                   'model'=>$model
               ));
            }
            public function actionCreate(){
                $msg = '';
                $model= new PayNeedinfo();
                $model->dpid=$this->companyId;
                $models = Company::model()->find('dpid=:dpid and delete_flag=0',array('dpid'=>$this->companyId));
                $model->company_name = $models->company_name;
                $model->contact_name = $models->contact_name;
                $model->mobile = $models->mobile;
                $model->company_address = $models->address;
                if(Yii::app()->request->isAjaxRequest){//处理上传图片的路径
                      $path = Yii::app()->basePath.'/../payneedinfo/ca_'.$this->companyId;
                      $up = new CFileUpload();
                      //设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
                      $up -> set("path", $path);
                      $up -> set("maxsize", 2*1024*1024);
                      $up -> set("allowtype", array("png", "jpg","jpeg"));

                      if($up -> upload("file")) {
                              $msg = '/wymenuv2/./payneedinfo/ca_'.$this->companyId.'/'.$up->getFileName();
                      }else{
                              $msg = $up->getErrorMsg();
                      }
                      echo $msg;exit;
		}
                $model->create_at = date('Y-m-d H:i:s',time());
                $model->update_at = date('Y-m-d H:i:s',time());
                $model->attributes = Yii::app()->request->getPost('PayNeedinfo');
               
                if(Yii::app()->user->role == User::ADMIN ){
                   $model->status=1;
                   $this->render('create',array(
                    'model'=>$model
                    ));
                } 
//                var_dump($model->status);exit;
                $this->render('create',array(
                    'model'=>$model
                    ));
            }
}
?>
