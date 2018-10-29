<?php
class FullSentPromotionController extends BackendController
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
    	$criteria->addCondition("t.full_type = 0 and t.dpid= ".$this->companyId);
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
		
			$code=new Sequence("promotion_code");
			$codeid = $code->nextval();
			
			$se=new Sequence("full_sent");
			$lid = $se->nextval();
			$model->lid = $lid;
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			$model->sole_code = Common::getCode($this->companyId,$lid,$codeid);
			$model->delete_flag = '0';
			$model->is_sync = $is_sync;
			$model->full_type = '0';
			$s = $model->is_available;
			if(!empty($s)){
				$st = implode(",",$s);
			}else{
				$st = 0;
			}
			$model->is_available = $st;
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('fullSentPromotion/detailindex','lid' => $model->lid , 'companyId' => $model->dpid ,'typeId'=>'product'));
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
		$is_sync = DataSync::getInitSync();
		$model = FullSent::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));

		$model->is_available =explode(',',$model->is_available);
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('FullSent');
			$model->update_at=date('Y-m-d H:i:s',time());
			$model->is_sync = $is_sync;
			$s = $model->is_available;
			if(!empty($s)){
				$st = implode(",",$s);
			}else{
				$st = 0;
			}
			$model->is_available = $st;
			//var_dump($model);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
				$this->redirect(array('FullSentPromotion/index' , 'companyId' => $this->companyId));
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
        //        Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_full_sent set delete_flag="1",is_sync ='.$is_sync.' where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('fullSentPromotion/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('fullSentPromotion/index' , 'companyId' => $companyId)) ;
		}
	}

	
	public function actionDetailindex(){
		//$sc = Yii::app()->request->getPost('csinquery');
		$promotionID = Yii::app()->request->getParam('lid');
		$typeId = Yii::app()->request->getParam('typeId');
		$categoryId = Yii::app()->request->getParam('cid',"");
		$fromId = Yii::app()->request->getParam('from','sidebar');
		$csinquery=Yii::app()->request->getPost('csinquery',"");
		//var_dump($typeId);exit;
		$db = Yii::app()->db;
		if($typeId=='product')
		{
			
			if(empty($promotionID)){
				$promotionID = Yii::app()->request->getParam('promotionID');
			}
			if(empty($promotionID)){
				echo "操作有误！请点击右上角的返回继续编辑";
				exit;
			}

			if(!empty($categoryId)){
				$sql = 'select k.* from(select t1.promotion_money,t1.promotion_discount,t1.number,t1.product_id,t1.full_sent_id,t.* from nb_product t left join nb_full_sent_detail t1 on(t.dpid = t1.dpid and t.lid = t1.product_id and t1.delete_flag = 0 and t1.full_sent_id = '.$promotionID.') where t.delete_flag = 0 and t.dpid='.$this->companyId.' and t.category_id = '.$categoryId.' ) k';
			}
	
			elseif(!empty($csinquery)){
				$sql = 'select k.* from(select t1.promotion_money,t1.promotion_discount,t1.number,t1.product_id,t1.full_sent_id,t.* from nb_product t left join nb_full_sent_detail t1 on(t.dpid = t1.dpid and t.lid = t1.product_id and t1.delete_flag = 0 and t1.full_sent_id = '.$promotionID.') where t.delete_flag = 0 and t.dpid='.$this->companyId.' and t.simple_code like "%'.strtoupper($csinquery).'%" ) k';
					
			}else{
				$sql = 'select k.* from(select t1.promotion_money,t1.promotion_discount,t1.number,t1.product_id,t1.full_sent_id,t.* from nb_product t left join nb_full_sent_detail t1 on(t.dpid = t1.dpid and t.lid = t1.product_id and t1.delete_flag = 0 and t1.full_sent_id = '.$promotionID.') where t.delete_flag = 0 and t.dpid='.$this->companyId.') k' ;
					
			}
			$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
			$pages = new CPagination($count);
			$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
			$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
			$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
			$models = $pdata->queryAll();
			$categories = $this->getCategories();

			$this->render('detailindex',array(
					'models'=>$models,
					'pages'=>$pages,
					'categories'=>$categories,
					'categoryId'=>$categoryId,
					'typeId' => $typeId,
					'promotionID'=>$promotionID
			));
		}else{
			if(empty($promotionID)){
				$promotionID = Yii::app()->request->getParam('promotionID');
			}
			$sql = 'select k.* from(select t1.promotion_money,t1.promotion_discount,t1.order_num,t1.is_set,t1.product_id,t1.private_promotion_id,t.* from nb_product_set t left join nb_private_promotion_detail t1 on(t.dpid = t1.dpid and t.lid = t1.product_id and t1.is_set = 1 and t1.delete_flag = 0 and t1.private_promotion_id = '.$promotionID.') where t.delete_flag = 0 and t.dpid='.$this->companyId.') k';
			$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
			//var_dump($count);exit;
			$pages = new CPagination($count);
			$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
			$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
			$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
			$models = $pdata->queryAll();
			$this->render('detailindex',array(
					'models'=>$models,
					'pages'=>$pages,
					'typeId' => $typeId,
					'promotionID'=>$promotionID
			));
		}
		 
	}
	
	public function actionStatus(){
		$id = Yii::app()->request->getParam('id');
		$typeId = Yii::app()->request->getParam('typeId');
		$db = Yii::app()->db;
		$sql='';
		if($typeId=='product')
		{
			$sql='update nb_product set status = not status where lid='.$id.' and dpid='.$this->companyId;
		}else{
			$sql='update nb_product_set set status = not status where lid='.$id.' and dpid='.$this->companyId;
		}
		//var_dump($sql);exit;
		$command=$db->createCommand($sql);
		$command->execute();
		//save to product_out
		exit;
	}
	
	public function actionStore(){
		$id = Yii::app()->request->getParam('id');
		$pcode = Yii::app()->request->getParam('pcode');
		$promotionID = Yii::app()->request->getParam('promotionID');
		$typeId = Yii::app()->request->getParam('typeId');
		$proID = Yii::app()->request->getParam('proID');
		$proNum = Yii::app()->request->getParam('proNum');
		$dpid = $this->companyId;
		$order_num = Yii::app()->request->getParam('order_num');
		$is_set = Yii::app()->request->getParam('is_set');
		$ceshi=$id."+".$promotionID."+".$proID."+".$proNum."+".$dpid."+".$typeId;
		$db = Yii::app()->db;
		$transaction = $db->beginTransaction();
		try 
		{
			$is_sync = DataSync::getInitSync();
			$se=new Sequence("full_sent_detail");
			$lid = $se->nextval();

			if($typeId=='product')
			{
				$sql = 'update nb_full_sent_detail set delete_flag = "1",is_sync ='.$is_sync.' where dpid='.$dpid.' and full_sent_id='.$promotionID.' and product_id='.$id;
					
				$command=$db->createCommand($sql);
				$command->execute();
				
				if($proID=='0'){
					$data = array(
							'lid'=>$lid,
							'dpid'=>$dpid,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'full_sent_id'=>$promotionID,
							'product_id'=>$id,
							'phs_code'=>$pcode,
							//'is_set'=>0,
							'is_discount'=>0,
							'promotion_money'=>$proNum,
							'promotion_discount'=>'1.00',
							'number'=>$order_num,
							'delete_flag'=>'0',
							'is_sync'=>$is_sync
					);
				}elseif($proID=="1"){
				
					$data = array(
							'lid'=>$lid,
							'dpid'=>$dpid,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'full_sent_id'=>$promotionID,
							'product_id'=>$id,
							'phs_code'=>$pcode,
							//'is_set'=>0,
							'is_discount'=>1,
							'promotion_money'=>'0.00',
							'promotion_discount'=>$proNum,
							'number'=>$order_num,
							'delete_flag'=>'0',
							'is_sync'=>$is_sync
					);
				
				
				}
			}else{
				$sql = 'update nb_full_sent_detail set delete_flag ="1",is_sync ='.$is_sync.' where dpid='.$dpid.' and full_sent_id='.$promotionID.' and product_id='.$id;
					
				$command=$db->createCommand($sql);
				$command->execute();
				
				if($proID=='0')
				{
						
					$data = array(
							'lid'=>$lid,
							'dpid'=>$dpid,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'full_sent_id'=>$promotionID,
							'product_id'=>$id,
							'phs_code'=>$pcode,
							//'is_set'=>1,
							'is_discount'=>0,
							'promotion_money'=>$proNum,
							'promotion_discount'=>'1.00',
							'number'=>$order_num,
							'delete_flag'=>'0',
							'is_sync'=>$is_sync
					);
				
				}elseif($proID=='1'){
			
					$data = array(
							'lid'=>$lid,
							'dpid'=>$dpid,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'full_sent_id'=>$promotionID,
							'product_id'=>$id,
							'phs_code'=>$pcode,
							//'is_set'=>1,
							'is_discount'=>1,
							'promotion_money'=>'0.00',
							'promotion_discount'=>$proNum,
							'number'=>$order_num,
							'delete_flag'=>'0',
							'is_sync'=>$is_sync
					);
				}
			}
			$command = $db->createCommand()->insert('nb_full_sent_detail',$data);
			
			$transaction->commit(); //提交事务会真正的执行数据库操作
			Yii::app()->end(json_encode(array("status"=>"success","promotion"=>$promotionID,'msg'=>$ceshi)));
	
			return true;
		}catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			Yii::app()->end(json_encode(array("status"=>"fail",'msg'=>$ceshi)));
			return false;
		}
	}

	
	public function actionResetall(){
		$typeId = Yii::app()->request->getParam('typeId');
		$db = Yii::app()->db;
	
		$sql='';
		if($typeId=='product')
		{
			$sql='update nb_product set store_number = -1 where dpid='.$this->companyId;
		}else{
			$sql='update nb_product_set set store_number = -1 where dpid='.$this->companyId;
		}
		//var_dump($sql);exit;
		 
	
		$command=$db->createCommand($sql);
		if($command->execute())
		{
			Yii::app()->end(json_encode(array("status"=>"success")));
		}else{
			Yii::app()->end(json_encode(array("status"=>"fail")));
		}
	}
	
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
	
	
	public function actionDetaildelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getParam('id');
		$is_sync = DataSync::getInitSync();
		//        Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_full_sent_detail set delete_flag="1", is_sync ='.$is_sync.' where product_id in('.$ids.') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			Yii::app()->end(json_encode(array("status"=>"success")));
			//$this->redirect(array('privatepromotion/detailindex' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要移除的项目'));
			$this->redirect(array('fullSentPromotion/detailindex' , 'companyId' => $companyId)) ;
		}
	}
	
	
	
	public function actionPromotiondetail(){
		$sc = Yii::app()->request->getPost('csinquery');
		$promotionID = Yii::app()->request->getParam('lid');
		$typeId = Yii::app()->request->getParam('typeId');
		$categoryId = Yii::app()->request->getParam('cid',"");
		$fromId = Yii::app()->request->getParam('from','sidebar');
		$csinquery=Yii::app()->request->getPost('csinquery',"");
		//var_dump($typeId);exit;
		$db = Yii::app()->db;
			
			
		if(empty($promotionID)){
			$promotionID = Yii::app()->request->getParam('promotionID');
		}
		if(empty($promotionID)){
			echo "操作有误！请点击右上角的返回继续编辑";
			exit;
		}
	
		if(!empty($categoryId)){
			//$criteria->condition.=' and t.category_id = '.$categoryId;
			$sql = 'select k.* from(select t.promotion_money,t.promotion_discount,t.number,t.product_id,t.full_sent_id,t1.*
							from nb_full_sent_detail t left join nb_product t1 on(t.dpid = t1.dpid and t1.lid = t.product_id  and t1.delete_flag = 0 )
							where t.delete_flag = 0 and t.dpid='.$this->companyId.' and  t.full_sent_id = '.$promotionID.' and t1.category_id = '.$categoryId.' order by t.lid asc) k';
	
		}
	
		elseif(!empty($csinquery)){
			//$criteria->condition.=' and t.simple_code like "%'.strtoupper($csinquery).'%"';
			$sql = 'select k.* from(select t.promotion_money,t.promotion_discount,t.number,t.product_id,t.full_sent_id,t1.*
							from nb_full_sent_detail t left join nb_product t1 on(t.dpid = t1.dpid and t1.lid = t.product_id and t1.delete_flag = 0 )
									where t.delete_flag = 0 and t.dpid='.$this->companyId.' and t.full_sent_id = '.$promotionID.' and t1.simple_code like "%'.strtoupper($csinquery).'%" order by t.lidcreate_at asc ) k';
	
		}else{
			$sql = 'select k.* from(select t.promotion_money,t.promotion_discount,t.number,t.product_id,t.full_sent_id,t1.*
							from nb_full_sent_detail t left join nb_product t1 on(t.dpid = t1.dpid and t1.lid = t.product_id and t1.delete_flag = 0 )
									where t.delete_flag = 0 and t.dpid='.$this->companyId.' and t.full_sent_id = '.$promotionID.' order by t.lid asc) k' ;
	
		}
		//var_dump($sql);exit;
		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
		$categories = $this->getCategories();
		$this->render('promotiondetail',array(
				'models'=>$models,
				'pages'=>$pages,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
				'typeId' => $typeId,
				'promotionID'=>$promotionID
		));
			
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
	}
	public function getProductSetPrice($productSetId,$dpid){
		$proSetPrice = '';
		$sql = 'select sum(t.price*t.number) as all_setprice,t.set_id from nb_product_set_detail t where t.set_id ='.$productSetId.' and t.dpid ='.$dpid.' and t.delete_flag = 0 and is_select = 1 ';
		$connect = Yii::app()->db->createCommand($sql);
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
