<?php
class WxMemberShopController extends BackendController
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

         public function actionIndex() {
             $models = WxMemberShop::model()->findAll(array('condition'=>'delete_flag < 1'));  
		
		$this->render('index',array(
				'models'=> $models)		
		);
    }
     public function actionCreate() {
        $goods_category[0] = "点心";
        $goods_category[1] = "饮品";
        $goods_category[2] = "粤菜";
        
        $state[0]="未上架";
        $state[1]="已上架";
        $model = new WxMemberShop();
        if(Yii::app()->request->isPostRequest) {
             $shop = Yii::app()->request->getPost('WxMemberShop');
                $se=new Sequence("wx_member_shop");
                $lid = $se->nextval();
                $model->lid = $lid;
                $model->goods_img = $shop['goods_img'];
             
               $model->price = $shop['price'];
                $model->goods_name = $shop['goods_name'];
                 $model->goods_category = $shop['goods_category'];
               $model->state = $shop['state'];
                $model->create_at = date('Y-m-d H:i:s',time());
            //var_dump($model);exit;
            if($model->save()){
                    Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
                    $this->redirect(array('WxMemberShop/index' , 'companyId' => $this->companyId ));
            }         
        }
       
        
        return $this->render('create',array(
				"model" => $model,
                                "goods_category"=>$goods_category,
                                "state"=>$state,     
                            ));
    }
    public function actionDelete(){
            $ids = Yii::app()->request->getPost('shopIds');
           
            if(!empty($ids)) {
                    Yii::app()->db->createCommand('update nb_wx_member_shop set delete_flag=1,update_at="'.date('Y-m-d H:i:s',time()).'" where lid in ('.implode(',' , $ids).')')
                    ->execute();

            }
            $this->redirect(array('WxMemberShop/index','companyId'=>$this->companyId));
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
        $this->render('update' , array(
				'model'=>$model,
		));
        
     }
     public function actionVipDelete(){
            $ids = Yii::app()->request->getPost('shopIds');
           
            if(!empty($ids)) {
                    Yii::app()->db->createCommand('update nb_wx_member_shop set delete_flag=1,update_at="'.date('Y-m-d H:i:s',time()).'" where lid in ('.implode(',' , $ids).')')
                    ->execute();

            }
            $this->redirect(array('WxMemberShop/vip','companyId'=>$this->companyId));
    }
        
}