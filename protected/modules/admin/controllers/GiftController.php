<?php
class GiftController extends BackendController
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
    	//$brand = Yii::app()->admin->getBrand($this->companyId);
    	$criteria = new CDbCriteria;
    	$criteria->select = 't.*';
    	$criteria->order = ' update_at desc';
    	$criteria->addCondition("t.dpid= ".$this->companyId);
    	$criteria->addCondition('delete_flag=0');
    	//$criteria->params[':brandId'] = $brand->brand_id;
    
    	$pages = new CPagination(Gift::model()->count($criteria));
    	$pages->applyLimit($criteria);
    	$models = Gift::model()->findAll($criteria);
    	 
    	$this->render('index',array(
    			'models'=>$models,
    			'pages'=>$pages,
    	));
    }
    
    
    
	public function saveFile($event){
		$fullName = $event->sender['name'];
		$extensionName = $event->sender['uploadedFile']->getExtensionName();
		$path = $event->sender['path'];
		
		$fileName = substr($fullName,0,strpos($fullName,'.'));
		$image = Yii::app()->image->load($path.'/'.$fullName);
		$image->resize(160,160)->quality(100)->sharpen(20);
		$image->save($path.'/'.$fileName.'_thumb.'.$extensionName); // or $image->save('images/small.jpg');
		return true;
	}
	/**
	 * 创建活动
	 */
	public function actionCreate(){
		$model = new Gift();
		$model->dpid = $this->companyId ;

		if(Yii::app()->request->isPostRequest) {
			$is_sync = DataSync::getInitSync();
			$model->attributes = Yii::app()->request->getPost('Gift');
		
			$se=new Sequence("gift");
			$model->lid = $se->nextval();
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			$model->is_sync = $is_sync;
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('gift/index' , 'companyId' => $this->companyId ));
			}
		}
		
		$this->render('create' , array(
				'model' => $model ,
		));
		}		

	
	/**
	 * 编辑活动
	 */
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		
		$model = Gift::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
		//Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		
		if(Yii::app()->request->isPostRequest) {
			$is_sync = DataSync::getInitSync();
			$model->attributes = Yii::app()->request->getPost('Gift');
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
				$this->redirect(array('gift/index' , 'companyId' => $this->companyId));
			}
		}
		$this->render('update' , array(
				'model'=>$model,
		));

	}


	/**
	 * 删除现金券
	 */
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
		$is_sync = DataSync::getInitSync();
		if(!empty($ids)) {
			$sql = 'update nb_gift set delete_flag=1, is_sync ='.$is_sync.' where lid in ('.implode(',' , $ids).') and dpid = :companyId';
			Yii::app()->db->createCommand($sql)
			->bindValue(':companyId',$this->companyId)
			->execute();
			$this->redirect(array('gift/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('gift/index' , 'companyId' => $companyId)) ;
		}
	}
	/**
	 * 
	 * 
	 * 领取统计
	 * 
	 */
	public function actionStatic(){
		$lid = Yii::app()->request->getParam('lid');
		$code = Yii::app()->request->getParam('code',null);
		
		$criteria = new CDbCriteria;
    	$criteria->with = array('gift','branduser');
    	$criteria->order = 't.update_at desc';
    	if($code){
    		$criteria->addSearchCondition('t.code',$code);
    	}
    	$criteria->addCondition('gift.lid=:lid');
    	$criteria->addCondition('t.dpid=:dpid');
    	$criteria->addCondition('t.delete_flag=0');
    	$criteria->params[':lid'] = $lid;
    	$criteria->params[':dpid'] = $this->companyId;
    
    	$pages = new CPagination(BranduserGift::model()->count($criteria));
    	$pages->applyLimit($criteria);
    	$models = BranduserGift::model()->findAll($criteria);
    	$this->render('static',array(
    			'models'=>$models,
    			'code'=>$code,
    			'pages'=>$pages,
    	));

	}
	public function actionExchange(){
		$lid = Yii::app()->request->getParam('lid');
		
		$criteria = new CDbCriteria;
		$criteria->addCondition('t.lid=:lid');
    	$criteria->addCondition('t.dpid=:dpid');
    	$criteria->addCondition('t.delete_flag=0');
    	$criteria->params[':lid'] = $lid;
    	$criteria->params[':dpid'] = $this->companyId;
    
        $model = BranduserGift::model()->find($criteria);
        if($model){
        	$model->is_used = 1;
        	$model->used_at = date('Y-m-d H:i:s',time());
        	if($model->update()){
        		Yii::app()->user->setFlash('success' , yii::t('app','核销成功'));
				$this->redirect(Yii::app()->request->urlReferrer) ;
        	}else{
        		Yii::app()->user->setFlash('error' , yii::t('app','核销失败'));
				$this->redirect(Yii::app()->request->urlReferrer) ;
        	}
        }else{
        	Yii::app()->user->setFlash('error' , yii::t('app','核销失败'));
			$this->redirect(Yii::app()->request->urlReferrer) ;
        }
    	

	}
	public function actionCode(){
		$code = Yii::app()->request->getParam('code',null);
		
		$criteria = new CDbCriteria;
    	$criteria->with = array('gift','branduser');
    	
    	$criteria->addSearchCondition('t.code',$code);
    	$criteria->addCondition('t.dpid=:dpid');
    	$criteria->addCondition('t.delete_flag=0');
    	$criteria->params[':dpid'] = $this->companyId;
    	
    	$pages = new CPagination(BranduserGift::model()->count($criteria));
    	$pages->applyLimit($criteria);
    	$models = BranduserGift::model()->findAll($criteria);
    	$this->render('code',array(
    			'models'=>$models,
    			'code'=>$code,
    			'pages'=>$pages,
    	));

    	
	}

}
