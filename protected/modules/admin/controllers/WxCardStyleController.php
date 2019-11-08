<?php
class WxCardStyleController extends BackendController
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
 
        $models = MemberWxcardStyle::model()->findAll("delete_flag < 1 and dpid=".$this->companyId);
		
		$this->render('index',array(
				'models'=> $models,
				
		));
    }
    public function actionCreate() {
    //    $style_cardnum_style[1] = "卡号在左边";
    //    $style_cardnum_style[2] = "卡号在右边";
        $model = new MemberWxcardStyle();
        $model->dpid = $this->companyId ;
        if(Yii::app()->request->isAjaxRequest){
        	$path = Yii::app()->basePath.'/../uploads/company_'.$this->companyId;
        	$up = new CFileUpload();
        	//设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
        	$up -> set("path", $path);
        	$up -> set("maxsize", 20*1024);
        	$up -> set("allowtype", array("png", "jpg","jpeg"));
        
        	if($up -> upload("file")) {
        		$msg = '/wymenuv2/./uploads/company_'.$this->companyId.'/'.$up->getFileName();
        	}else{
        		$msg = $up->getErrorMsg();
        	}
        	echo $msg;exit;
        }
        if(Yii::app()->request->isPostRequest) {
             $cardImg = Yii::app()->request->getPost('MemberWxcardStyle');
                $se=new Sequence("member_wxcard_style");
                $lid = $se->nextval();
                $model->lid = $lid;
             
               $model->bg_img = $cardImg['bg_img'];
           //     $model->style_cardnum_style = $cardImg['style_cardnum_style'];
               
             
                $model->create_at = date('Y-m-d H:i:s',time());
                $model->update_at = date('Y-m-d H:i:s',time());
            //var_dump($model);exit;
            if($model->save()){
                    Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
                    $this->redirect(array('wxCardStyle/index' , 'companyId' => $this->companyId ));
            }         
        }
        
        return $this->render('create',array(
				'model' => $model,
                         //       'style_cardnum_style'=>$style_cardnum_style
                               
                                 
                            ));
    }
    public function actionUpdate() {
       // $style_cardnum_style[1] = "卡号在左边";
       // $style_cardnum_style[2] = "卡号在右边";
        //通过get方法接收要展示的信息的主键。
    	if(Yii::app()->request->isAjaxRequest){
    		$path = Yii::app()->basePath.'/../uploads/company_'.$this->companyId;
    		$up = new CFileUpload();
    		//设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
    		$up -> set("path", $path);
    		$up -> set("maxsize", 20*1024);
    		$up -> set("allowtype", array("png", "jpg","jpeg"));
    	
    		if($up -> upload("file")) {
    			$msg = '/wymenuv2/./uploads/company_'.$this->companyId.'/'.$up->getFileName();
    		}else{
    			$msg = $up->getErrorMsg();
    		}
    		echo $msg;exit;
    	}
        $lid = Yii::app()->request->getParam('lid');
        //在数据库查找该主键对应的条目。
        $model = MemberWxcardStyle::model()->find('lid=:lid and dpid=:dpid' , array(':lid' => $lid,':dpid'=> $this->companyId)) ;
        if(Yii::app()->request->isPostRequest) {
           
                $cardImg = Yii::app()->request->getPost('MemberWxcardStyle');
                $model->bg_img = $cardImg['bg_img'];
              //  $model->style_cardnum_style = $cardImg['style_cardnum_style'];
                $model->update_at=date('Y-m-d H:i:s',time());

            //var_dump($model->attributes);exit;
            if($model->save()){
                    Yii::app()->user->setFlash('success',yii::t('app','修改成功'));
                    $this->redirect(array('wxCardStyle/index','companyId'=>$this->companyId));
            } else {
                    Yii::app()->user->setFlash('error',yii::t('app','修改失败'));
            }
        }
        $this->render('update',array(
				'model'=> $model,
				// 'style_cardnum_style'=>$style_cardnum_style
		));
    }
    public function actionDelete() {
        $lid = Yii::app()->request->getParam('lid');
        if($lid){
           Yii::app()->db->createCommand('update nb_member_wxcard_style set delete_flag=1,update_at="'.date('Y-m-d H:i:s',time()).'" where lid = "'.$lid.'"')->execute();
        }
        $this->redirect(array('wxCardStyle/index','companyId'=>$this->companyId)); 
    }
}

