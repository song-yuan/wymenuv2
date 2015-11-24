<?php
class NormalpromotionController extends BackendController
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
    	$criteria = new CDbCriteria;
    	$criteria->select = 't.*';
    	$criteria->order = ' update_at desc';
    	$criteria->addCondition("t.dpid= ".$this->companyId);
    	$criteria->addCondition('delete_flag=0');
    	//$criteria->params[':brandId'] = $brand->brand_id;
    
    	$pages = new CPagination(NormalPromotion::model()->count($criteria));
    	$pages->applyLimit($criteria);
    	$models = NormalPromotion::model()->findAll($criteria);
    	 
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
	 * 创建活动，并发送系统消息
	 */
	public function actionCreate(){
		$model = new NormalPromotion();
		$model->dpid = $this->companyId ;
		//$model->create_time = time();
		//var_dump($model);exit;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('NormalPromotion');
			$se=new Sequence("normal_promotion");
			$model->lid = $se->nextval();
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			$model->delete_flag = '0';
			//$py=new Pinyin();
			//$model->simple_code = $py->py($model->product_name);
			//var_dump($model);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('normalpromotion/index' , 'companyId' => $this->companyId ));
			}
		}
		//$categories = $this->getCategoryList();
		//$departments = $this->getDepartments();
		//echo 'ss';exit;
		$this->render('create' , array(
				'model' => $model ,
				//'categories' => $categories
		));
	}
	
	/**
	 * 编辑活动
	 */
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		//echo 'ddd';
		$model = NormalPromotion::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
		Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('NormalPromotion');
			$model->update_at=date('Y-m-d H:i:s',time());
			//($model->attributes);var_dump(Yii::app()->request->getPost('Printer'));exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
				$this->redirect(array('normalpromotion/index' , 'companyId' => $this->companyId));
			}
		}
		$this->render('update' , array(
				'model'=>$model,
		));
	}
// 	public function actionCre()
// 	{
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
// 	}

	public function actionUpdate1($id)
	{
		$request = Yii::app()->request;
		$model=$this->loadModel($id);
		$objects = Yii::app()->admin->getRegions($this->companyId);
		$cashcardManage = new CashcardManage($model);
		$selectedShopIds = $cashcardManage->getSelectedShopIds();
		
		if(isset($model->cash)){
			$model->cash /= 100;
		}
		if(isset($model->order_consume)){
			$model->order_consume /= 100;
		}
		if(!$model->isAdmin()){
			Yii::app()->admin->setFlash('error','你没有权限修改');
			$this->redirect(array('index','cid'=>$this->companyId));
		}
		if($request->isPostRequest)
		{
			$postData = $request->getPost('Cashcard');
			if($postData['is_exclusive'] == 0) $postData['order_consume'] = 0;
			if($postData['exchangeable'] == 0) $postData['consume_point'] = $postData['activity_point'] = 0;
			
			
			$shopIds = $request->getPost('shopId');
			
			$model->attributes=$postData;
			
			$allShopids = Yii::app()->admin->getShopIds($this->companyId);//判断是否全部选择 如果全部选择 shop_flag = 0
			$transaction = Yii::app()->db->beginTransaction();
			if($model->valid($shopIds)){
				$diffShopIds = array_diff($allShopids,$shopIds);
				try{
					if(empty($diffShopIds)){
						$model->shop_flag = 0;
						$model->save(false);
						$cashcardManage->delete($id);
					}else{
						$model->shop_flag = 1;
						$model->save(false);
						//save gift shop
						$cashcardManage->saveCashcardShop($shopIds);
						
						$regionAdminIds = array();
						$shopAdminIds = Yii::app()->admin->getShopOwnerIds($shopIds);
						if(Yii::app()->admin->role_type < AdminWebUser::REGION_ADMIN){
							$regionAdminIds = Yii::app()->admin->getRegionOwnerIds($shopIds);
						}
						$systemMessage = new SystemMessageManage();
						$title = Yii::app()->admin->admin_user_name.' 修改了现金券['.$model->title.']';
						$systemMessage->sendMessage(array_merge($regionAdminIds,$shopAdminIds),$title,'');
					}
					$transaction->commit();
					Yii::app()->admin->setFlash('success','编辑成功！');
					$this->redirect(array('index','cid'=>$this->companyId));
				} catch(Exception $e){
					$transaction->rollback();
				}
			}else{
				$model->start_time = strtotime($model->start_time);
				$model->end_time = strtotime($model->end_time)+3600*24;
			}
		}

		$model->start_time = $model->start_time ?date('Y-m-d',$model->start_time):'';
		$model->end_time = $model->end_time ?date('Y-m-d',$model->end_time-3600*24):'';
		$this->render('update',array(
			'model'=>$model,
			'objects'=>$objects,
			'selectedShopIds'=>$selectedShopIds
		));
	}

	/**
	 * 删除现金券
	 */
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
        //        Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_normal_promotion set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('normalpromotion/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('normalpromotion/index' , 'companyId' => $companyId)) ;
		}
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
	
	private function getCategories(){
		$criteria = new CDbCriteria;
		$criteria->with = 'company';
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId ;
		$criteria->order = ' tree,t.lid asc ';
	
		$models = ProductCategory::model()->findAll($criteria);
	
		//return CHtml::listData($models, 'lid', 'category_name','pid');
		$options = array();
		$optionsReturn = array(yii::t('app','--请选择分类--'));
		if($models) {
			foreach ($models as $model) {
				if($model->pid == '0') {
					$options[$model->lid] = array();
				} else {
					$options[$model->pid][$model->lid] = $model->category_name;
				}
			}
			//var_dump($options);exit;
		}
		foreach ($options as $k=>$v) {
			//var_dump($k,$v);exit;
			$model = ProductCategory::model()->find('t.lid = :lid and dpid=:dpid',array(':lid'=>$k,':dpid'=>  $this->companyId));
			$optionsReturn[$model->category_name] = $v;
		}
		return $optionsReturn;
	}
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
