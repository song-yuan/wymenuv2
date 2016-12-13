<?php
class CashcardController extends BackendController
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
    	$criteria->select = 't.*';
    	$criteria->order = ' create_at desc';
    	//$criteria->addCondition('brand_id=:brandId');
    	$criteria->addCondition('t.dpid = '.$this->companyId);
    	$criteria->addCondition('delete_flag=0');
    	 
    	$pages = new CPagination(TotalPromotion::model()->count($criteria));
    	$pages->applyLimit($criteria);
    	$models = TotalPromotion::model()->findALL($criteria);
    	//var_dump($models);exit;
    	if (!empty($models)) {
    		$a="1";
    		$this->render('index',array(
    	    	'models'=>$models,
    	    	'pages'=>$pages,
    				'a'=>$a,
    	    	));
    	}else {
    		$a="2";
    		$model = new TotalPromotion();
    		$model->dpid = $this->companyId;
		//var_dump($models);exit;
    		$this->render('index' , array(
    				'model' => $model,
    				'a'=>$a,
    				//'categories' => $categories
    		));
    	}
    	
    	//var_dump($models);exit;
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
	 * 创建现金券，并发送系统消息
	 */
	public function actionCreate()
	{
		
		$model = new TotalPromotion();
		$model->dpid = $this->companyId ;
		$is_sync = DataSync::getInitSync();
		//$model->create_time = time();
		//var_dump($model);exit;
		if(Yii::app()->request->isPostRequest) {
			if(Yii::app()->user->role > User::SHOPKEEPER) {
				Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
				$this->redirect(array('cashcard/index' , 'companyId' => $this->companyId)) ;
			}
		$model->attributes = Yii::app()->request->getPost('TotalPromotion');
		$se=new Sequence("total_promotion");
		$model->lid = $se->nextval();
		$model->create_at = date('Y-m-d H:i:s',time());
		$model->update_at = date('Y-m-d H:i:s',time());
		$model->delete_flag = '0';
		$model->is_sync = $is_sync;
		//$py=new Pinyin();
		//$model->simple_code = $py->py($model->product_name);
		//var_dump($model);exit;
		if($model->save()){
		Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
		$this->redirect(array('cashcard/index' , 'companyId' => $this->companyId ));
			}
		}
		//$categories = $this->getCategoryList();
		//$departments = $this->getDepartments();
		//echo 'ss';exit;
		$this->render('index' , array(
		    		'model' => $model ,
		    		//'categories' => $categories
		    		));

	}
	/**
	 * 编辑现金券
	 */
	public function actionUpdate()
	{
		$lid = Yii::app()->request->getParam('lid');
		$is_sync = DataSync::getInitSync();
		    //var_dump($lid);exit;	
		    		//echo 'ddd';
		   $model = TotalPromotion::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
		    //Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		   if(Yii::app()->request->isPostRequest) {
		   	if(Yii::app()->user->role > User::SHOPKEEPER) {
		   		Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
		   		$this->redirect(array('cashcard/index' , 'companyId' => $this->companyId)) ;
		   	}
		   $model->attributes = Yii::app()->request->getPost('TotalPromotion');
		   $model->update_at=date('Y-m-d H:i:s',time());
		   $model->is_sync = $is_sync;
		   //($model->attributes);var_dump(Yii::app()->request->getPost('Printer'));exit;
		   if($model->save()){
		    	Yii::app()->user->setFlash('success' , yii::t('app','haha! 修改成功'));
		    	$this->redirect(array('cashcard/index' , 'companyId' => $this->companyId));
		    	}
		    }
		    $this->render('index' , array(
		    			'model'=>$model,
		    ));

	}

	/**
	 * 删除现金券
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('cashcard/index' , 'companyId' => $this->companyId)) ;
		}
		$model = $this->loadModel($id);
		if($model->isAdmin()){
			$model->delete();
			Yii::app()->admin->setFlash('success','删除成功！');		
		}else{
			Yii::app()->admin->setFlash('error','你没有权限');
		}
		$this->redirect(Yii::app()->request->getUrlReferrer());
	}

	/**
	 * 现金券列表
	 */

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Cashcard the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Cashcard::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Cashcard $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cashcard-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
