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
    
//     public function actionIndex() {
//     	$model = WeixinServiceAccount::model()->find('dpid=:dpid',array(':dpid'=>$this->companyId));
//     	if(!$model){
//     		$model = new WeixinServiceAccount;
//     	}
//     	if(Yii::app()->request->isPostRequest){
//     		$postData = Yii::app()->request->getPost('WeixinServiceAccount');
//     		$se=new Sequence("weixin_service_account");
//     		$postData['lid'] = $se->nextval();
//     		$postData['dpid'] = $this->companyId;
//     		$postData['create_at'] = date('Y-m-d H:i:s',time());
//     		$postData['update_at'] = date('Y-m-d H:i:s',time());
//     		$model->attributes = $postData;
//     		if($model->save()){
//     			Yii::app()->user->setFlash('success' ,yii::t('app', '设置成功'));
//     		}else{
//     			$this->redirect(array('/admin/cashcard/index','companyId'=>$this->companyId));
//     		}
//     	}
//     	$this->render('index',array(
//     			'model'=>$model,
//     	));
//     }
    
    
    public function actionIndex(){
    	//$brand = Yii::app()->admin->getBrand($this->companyId);

    	//$criteria->params[':brandId'] = $brand->brand_id;
//     	$db = Yii::app()->db;
//     	$sql = 'select t.* from nb_total_promotion t where t.delete_flag = 0 order by t.update_at desc';
//     	$models = Yii::app()->db->createCommand($sql)->queryAll();
//     	$count = $db->createCommand(str_replace('t.*','count(*)',$sql))->queryScalar();
//     	$pages = new CPagination($count);
//     	$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
//     	$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
//     	$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
//     	$models = $pdata->queryAll();

    	
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
    	if ($models) {
    		$a="1";
    		$this->render('index',array(
    	    	'models'=>$models,
    	    	'pages'=>$pages,
    				'a'=>$a,
    	    	));
    	}else {
    		$a="2";
    		$model = new TotalPromotion();
    		$model->dpid = $this->companyId ;
    		//$models = (array)$models;
    		//$model->create_time = time();
    		//var_dump($models);exit;
//     		if(Yii::app()->request->isPostRequest) {
//     			$model->attributes = Yii::app()->request->getPost('TotalPromotion');
//     			$se=new Sequence("total_promotion");
//     			$model->lid = $se->nextval();
//     			$model->create_at = date('Y-m-d H:i:s',time());
//     			$model->update_at = date('Y-m-d H:i:s',time());
//     			$model->delete_flag = '0';
//     			//$py=new Pinyin();
//     			//$model->simple_code = $py->py($model->product_name);
//     			//var_dump($model);exit;
//     			if($model->save()){
//     				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
//     				$this->redirect(array('cashcard/index' , 'companyId' => $this->companyId ));
//     			}
//     		}
    		//var_dump($models);exit;
    		$this->render('index' , array(
    				'model' => $model,
    				'a'=>$a,
    				//'categories' => $categories
    		));
    	}
    	
    	//var_dump($models);exit;
    	}
    	  
    	//$lid = null;
//     	$lid = Yii::app()->request->getParam('lid');
//     	if($lid!= null){
//     		//echo 'ddd';
//     		$model = TotalPromotion::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
//     		//Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
//     		if(Yii::app()->request->isPostRequest) {
//     			$model->attributes = Yii::app()->request->getPost('Totalpromotion');
//     			$model->update_at=date('Y-m-d H:i:s',time());
//     			//($model->attributes);var_dump(Yii::app()->request->getPost('Printer'));exit;
//     			if($model->save()){
//     				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
//     				$this->redirect(array('cashcard/index' , 'companyId' => $this->companyId));
//     			}
//     		}
//     		$this->render('index' , array(
//     				'model'=>$model,
//     		));
    	
//     	}else {
    	
//     			$model = new TotalPromotion();
//     			$model->dpid = $this->companyId ;
//     			//$model->create_time = time();
    	 
//     			if(Yii::app()->request->isPostRequest) {
//     				$model->attributes = Yii::app()->request->getPost('TotalPromotion');
//     				$se=new Sequence("total_promotion");
//     				$model->lid = $se->nextval();
//     				$model->create_at = date('Y-m-d H:i:s',time());
//     				$model->update_at = date('Y-m-d H:i:s',time());
//     				$model->delete_flag = '0';
//     				//$py=new Pinyin();
//     				//$model->simple_code = $py->py($model->product_name);
//     				//var_dump($model);exit;
//     				if($model->save()){
//     					Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
//     					$this->redirect(array('cashcard/index' , 'companyId' => $this->companyId ));
//     				}
//     			}
//     			//$categories = $this->getCategoryList();
//     			//$departments = $this->getDepartments();
//     			//echo 'ss';exit;
//     			$this->render('index' , array(
//     					'model' => $model ,
//     					//'categories' => $categories
//     			));
//     			} 
//		}
//     	//$brand = Yii::app()->admin->getBrand($this->companyId);
//     	$criteria = new CDbCriteria;
//     	$criteria->select = 't.*';
//     	$criteria->order = ' create_at desc';
//     	//$criteria->addCondition('brand_id=:brandId');
//     	//$criteria->addCondition('delete_flag=0');
//     	//$criteria->params[':brandId'] = $brand->brand_id;
    
//     	$pages = new CPagination(TotalPromotion::model()->count($criteria));
//     	$pages->applyLimit($criteria);
//     	$models = TotalPromotion::model()->findAll($criteria);
    	 
//     	$this->render('index',array(
//     			'models'=>$models,
//     			'pages'=>$pages,
//     	));
 //   }
    
    
    
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
		//$model->create_time = time();
		//var_dump($model);exit;
		if(Yii::app()->request->isPostRequest) {
		$model->attributes = Yii::app()->request->getPost('TotalPromotion');
		$se=new Sequence("total_promotion");
		$model->lid = $se->nextval();
		$model->create_at = date('Y-m-d H:i:s',time());
		$model->update_at = date('Y-m-d H:i:s',time());
		$model->delete_flag = '0';
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
// 		$brand = Yii::app()->admin->getBrand($this->companyId);
// 		$request = Yii::app()->request;
// 		$model = new Cashcard;
// 		$model->brand_id = $brand->brand_id;
// 		$model->group_id = Yii::app()->admin->admin_user_id;
// 		$objects = Yii::app()->admin->getRegions($this->companyId);
	
// 		if($request->isPostRequest)
// 		{	
// 			$postData = $request->getPost('Cashcard');
// 			$postData['create_time'] = time();
// 			$shopIds = $request->getPost('shopId');
			
// 			$model->attributes = $postData;
// 			$allShopids = Yii::app()->admin->getShopIds($this->companyId);
			
// 			$transaction = Yii::app()->db->beginTransaction();
// 			if($model->valid($shopIds)){
// 				$diffShopIds = array_diff($allShopids,$shopIds);
// 				try{
// 					if(empty($diffShopIds)){
// 						$model->shop_flag = 0;
// 						$model->save(false);
// 					}else{
// 						$model->shop_flag = 1;
// 						$model->save(false);
// 						//save gift shop
// 						$cashcardManage = new CashcardManage($model);
// 						$cashcardManage->saveCashcardShop($shopIds);
						
// 						$regionAdminIds = array();
// 						$shopAdminIds = Yii::app()->admin->getShopOwnerIds($shopIds);
// 						if(Yii::app()->admin->role_type < AdminWebUser::REGION_ADMIN){
// 						$regionAdminIds = Yii::app()->admin->getRegionOwnerIds($shopIds);
// 					  }
// 						$systemMessage = new SystemMessageManage();
// 						$title = Yii::app()->admin->admin_user_name.' 添加了现金券['.$model->title.']';
// 						$systemMessage->sendMessage(array_merge($regionAdminIds,$shopAdminIds),$title,'');
// 					}
					
// 					$transaction->commit();
					
// 					Yii::app()->admin->setFlash('success','创建成功！');
// 					$this->redirect(array('index','cid'=>$this->companyId));
// 				} catch(Exception $e){
// 					$transaction->rollback();
// 				}
// 			}
// 		}
// 		$this->render('create',array(
// 			'model'=>$model,
// 			'objects'=>$objects,
// 		));
	}
	/**
	 * 编辑现金券
	 */
	public function actionUpdate()
	{
		$lid = Yii::app()->request->getParam('lid');
		    //var_dump($lid);exit;	
		    		//echo 'ddd';
		   $model = TotalPromotion::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
		    //Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		   if(Yii::app()->request->isPostRequest) {
		   $model->attributes = Yii::app()->request->getPost('TotalPromotion');
		   $model->update_at=date('Y-m-d H:i:s',time());
		   //($model->attributes);var_dump(Yii::app()->request->getPost('Printer'));exit;
		   if($model->save()){
		    	Yii::app()->user->setFlash('success' , yii::t('app','haha! 修改成功'));
		    	$this->redirect(array('cashcard/index' , 'companyId' => $this->companyId));
		    	}
		    }
		    $this->render('index' , array(
		    			'model'=>$model,
		    ));
// 		$request = Yii::app()->request;
// 		$model=$this->loadModel($id);
// 		$objects = Yii::app()->admin->getRegions($this->companyId);
// 		$cashcardManage = new CashcardManage($model);
// 		$selectedShopIds = $cashcardManage->getSelectedShopIds();
		
// 		if(isset($model->cash)){
// 			$model->cash /= 100;
// 		}
// 		if(isset($model->order_consume)){
// 			$model->order_consume /= 100;
// 		}
// 		if(!$model->isAdmin()){
// 			Yii::app()->admin->setFlash('error','你没有权限修改');
// 			$this->redirect(array('index','cid'=>$this->companyId));
// 		}
// 		if($request->isPostRequest)
// 		{
// 			$postData = $request->getPost('Cashcard');
// 			if($postData['is_exclusive'] == 0) $postData['order_consume'] = 0;
// 			if($postData['exchangeable'] == 0) $postData['consume_point'] = $postData['activity_point'] = 0;
			
			
// 			$shopIds = $request->getPost('shopId');
			
// 			$model->attributes=$postData;
			
// 			$allShopids = Yii::app()->admin->getShopIds($this->companyId);//判断是否全部选择 如果全部选择 shop_flag = 0
// 			$transaction = Yii::app()->db->beginTransaction();
// 			if($model->valid($shopIds)){
// 				$diffShopIds = array_diff($allShopids,$shopIds);
// 				try{
// 					if(empty($diffShopIds)){
// 						$model->shop_flag = 0;
// 						$model->save(false);
// 						$cashcardManage->delete($id);
// 					}else{
// 						$model->shop_flag = 1;
// 						$model->save(false);
// 						//save gift shop
// 						$cashcardManage->saveCashcardShop($shopIds);
						
// 						$regionAdminIds = array();
// 						$shopAdminIds = Yii::app()->admin->getShopOwnerIds($shopIds);
// 						if(Yii::app()->admin->role_type < AdminWebUser::REGION_ADMIN){
// 							$regionAdminIds = Yii::app()->admin->getRegionOwnerIds($shopIds);
// 						}
// 						$systemMessage = new SystemMessageManage();
// 						$title = Yii::app()->admin->admin_user_name.' 修改了现金券['.$model->title.']';
// 						$systemMessage->sendMessage(array_merge($regionAdminIds,$shopAdminIds),$title,'');
// 					}
// 					$transaction->commit();
// 					Yii::app()->admin->setFlash('success','编辑成功！');
// 					$this->redirect(array('index','cid'=>$this->companyId));
// 				} catch(Exception $e){
// 					$transaction->rollback();
// 				}
// 			}else{
// 				$model->start_time = strtotime($model->start_time);
// 				$model->end_time = strtotime($model->end_time)+3600*24;
// 			}
// 		}

// 		$model->start_time = $model->start_time ?date('Y-m-d',$model->start_time):'';
// 		$model->end_time = $model->end_time ?date('Y-m-d',$model->end_time-3600*24):'';
// 		$this->render('update',array(
// 			'model'=>$model,
// 			'objects'=>$objects,
// 			'selectedShopIds'=>$selectedShopIds
// 		));
	}

	/**
	 * 删除现金券
	 */
	public function actionDelete($id)
	{
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
