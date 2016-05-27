<?php
class FullMinusPromotionController extends BackendController
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
    	$criteria->order = ' t.lid desc';
    	$criteria->addCondition("t.full_type = 1 and t.dpid= ".$this->companyId);
    	$criteria->addCondition('delete_flag=0');
    	//$criteria->params[':brandId'] = $brand->brand_id;
    
    	$pages = new CPagination(FullSent::model()->count($criteria));
    	$pages->applyLimit($criteria);
    	$models = FullSent::model()->findAll($criteria);
    	 
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
		$model = new FullSent();
		$model->dpid = $this->companyId ;
		//$brdulvs = $this->getBrdulv();
		//$model->create_time = time();
		//var_dump($model);exit;
		$is_sync = DataSync::getInitSync();
		if(Yii::app()->request->isPostRequest) {
			$db = Yii::app()->db;
			//$transaction = $db->beginTransaction();
		//try{
			$model->attributes = Yii::app()->request->getPost('FullSent');
			$groupID = Yii::app()->request->getParam('hidden1');
			$gropids = array();
			$gropids = explode(',',$groupID);
			//$db = Yii::app()->db;
		
			$se=new Sequence("full_sent");
			$model->lid = $se->nextval();
// 			if(!empty($groupID)){
// 				//$sql = 'delete from nb_private_branduser where private_promotion_id='.$lid.' and dpid='.$this->companyId;
// 				//$command=$db->createCommand($sql);
// 				//$command->execute();
// 				foreach ($gropids as $gropid){
// 					$userid = new Sequence("private_branduser");
// 					$id = $userid->nextval();
// 					$data = array(
// 							'lid'=>$id,
// 							'dpid'=>$this->companyId,
// 							'create_at'=>date('Y-m-d H:i:s',time()),
// 							'update_at'=>date('Y-m-d H:i:s',time()),
// 							'private_promotion_id'=>$model->lid,
// 							'to_group'=>"2",
// 							'is_used'=>"1",
// 							'brand_user_lid'=>$gropid,
// 							'cupon_source'=>'0',
// 							'delete_flag'=>'0',
// 							'is_sync'=>$is_sync,
// 					);
// 					$command = $db->createCommand()->insert('nb_private_branduser',$data);
// 					//var_dump($gropid);exit;
// 				}
// 			}
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			$model->delete_flag = '0';
			$model->is_sync = $is_sync;
			$model->full_type = '1';
			//$transaction->commit(); //提交事务会真正的执行数据库操作
			//return true;
			//}catch (Exception $e) {
			//	$transaction->rollback(); //如果操作失败, 数据回滚
				//Yii::app()->end(json_encode(array("status"=>"fail")));
			//	return false;
			//}
			
			//$py=new Pinyin();
			//$model->simple_code = $py->py($model->product_name);
			//var_dump($model);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('fullMinusPromotion/index' , 'companyId' => $this->companyId ));
			}
		}
		
		//$categories = $this->getCategoryList();
		//$departments = $this->getDepartments();
		//echo 'ss';exit;
		$this->render('create' , array(
				'model' => $model ,
				//'brdulvs'=>$brdulvs,
				//'categories' => $categories
		));
	}
	
	/**
	 * 编辑活动
	 */
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		//echo 'ddd';
		//$groupID = Yii::app()->request->getParam('str');
		//var_dump($groupID);exit;
		//$brdulvs = $this->getBrdulv();
		$is_sync = DataSync::getInitSync();
		$model = FullSent::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));

		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('FullSent');
	
			//print_r(explode(',',$groupID));
			//var_dump($gropid);exit;
			$model->update_at=date('Y-m-d H:i:s',time());
			$model->is_sync = $is_sync;
			//$gropid = array();
			//$gropid = (dexplode(',',$groupID));
			//var_dump(dexplode(',',$groupID));exit;
			//($model->attributes);var_dump(Yii::app()->request->getPost('Printer'));exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
				$this->redirect(array('FullMinusPromotion/index' , 'companyId' => $this->companyId));
			}
		}
		$this->render('update' , array(
				'model'=>$model,
				//'brdulvs'=>$brdulvs,
				//'userlvs'=>$userlvs,
		));
	}


	
	/**
	 * 删除现金券
	 */
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
		$is_sync = DataSync::getInitSync();
        //        Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_full_sent set delete_flag="1",is_sync ='.$is_sync.' where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('fullMinusPromotion/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('fullMinusPromotion/index' , 'companyId' => $companyId)) ;
		}
	}

	
	/*
	 * 
	 * 获取会员等级。。。
	 * 
	 * */
	private function getBrdulv(){
		$criteria = new CDbCriteria;
		$criteria->with = '';
		$criteria->condition = ' t.delete_flag=0 and t.dpid='.$this->companyId ;
		$criteria->order = ' t.min_total_points asc ' ;
		$brdules = BrandUserLevel::model()->findAll($criteria);
		if(!empty($brdules)){
		return $brdules;
		}
// 		else{
// 			return flse;
// 		}				
	}
	public function getProductSetPrice($productSetId,$dpid){
		$proSetPrice = '';
		$sql = 'select sum(t.price*t.number) as all_setprice,t.set_id from nb_product_set_detail t where t.set_id ='.$productSetId.' and t.dpid ='.$dpid.' and t.delete_flag = 0 and is_select = 1 ';
		$connect = Yii::app()->db->createCommand($sql);
		//	$connect->bindValue(':site_id',$siteId);
		//	$connect->bindValue(':dpid',$dpid);
		$proSetPrice = $connect->queryRow();
		//var_dump($proSetPrice);exit;
		if(!empty($proSetPrice)){
			return $proSetPrice['all_setprice'] ;
		}
		else{
			return flse;
		}
	}
	

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cashcard-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
