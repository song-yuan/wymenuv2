<?php
class PromotionActivityController extends BackendController
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
    
    	$pages = new CPagination(PromotionActivity::model()->count($criteria));
    	$pages->applyLimit($criteria);
    	$models = PromotionActivity::model()->findAll($criteria);
    	 
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
		$model = new PromotionActivity();
		$model->dpid = $this->companyId ;
		$is_sync = DataSync::getInitSync();
		//$model->create_time = time();
		//var_dump($model);exit;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('PromotionActivity');
			$se=new Sequence("promotion_activity");
			$model->lid = $se->nextval();
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			$model->delete_flag = '0';
			$model->is_sync = $is_sync;

			//var_dump($model);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('promotionActivity/index' , 'companyId' => $this->companyId ));
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
		$is_sync = DataSync::getInitSync();
		//echo 'ddd';
		$model = PromotionActivity::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
		//Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('PromotionActivity');
			$model->update_at=date('Y-m-d H:i:s',time());
			$model->is_sync = $is_sync;
			//($model->attributes);var_dump(Yii::app()->request->getPost('Printer'));exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
				$this->redirect(array('promotionActivity/index' , 'companyId' => $this->companyId));
			}
		}
		$this->render('update' , array(
				'model'=>$model,
		));
	}



	public function actionDetailindex(){
			$typeID = Yii::app()->request->getParam('typeID');
			$data = date('Y-m-d H:i:s',time());
			//var_dump($typeID);exit;
			$activityID = Yii::app()->request->getParam('lid');
			if (empty($activityID)){
				$activityID = Yii::app()->request->getParam('activityID');
 			}
//  			else{
// 				$activityID = Yii::app()->request->getParam('lid');
// 			}
			$db = Yii::app()->db;
			if($typeID=="normal"){
				$sql = 'select k.* from(select t1.promotion_lid, t.* from nb_normal_promotion t left join nb_promotion_activity_detail t1 on(t.dpid = t1.dpid and t1.delete_flag = 0 and t1.promotion_lid = t.lid and t1.activity_lid ='.$activityID.') where t.dpid='.$this->companyId.' and t.end_time >="'.$data.'" and t.delete_flag = 0 and is_available = 0) k';
				$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
				//var_dump($sql);exit;
				$pages = new CPagination($count);
				$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
				$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
				$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
				$models = $pdata->queryAll();
			}elseif($typeID=="private"){
				$sql = 'select l.* from(select t1.promotion_lid,t.* from nb_private_promotion t left join nb_promotion_activity_detail t1 on(t.dpid = t1.dpid and t1.delete_flag = 0 and t1.promotion_lid = t.lid and t1.activity_lid ='.$activityID.') where t.dpid='.$this->companyId.' and t.end_time >="'.$data.'" and t.delete_flag = 0 and is_available = 0) l';
				$count = $db->createCommand(str_replace('l.*','count(*)',$sql))->queryScalar();
				//var_dump($sql);exit;
				$pages = new CPagination($count);
				$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
				$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
				$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
				$models = $pdata->queryAll();
			}elseif($typeID=="cupon"){
				$sql = 'select j.* from(select t1.promotion_lid,t.* from nb_cupon t left join nb_promotion_activity_detail t1 on(t.dpid = t1.dpid and t1.delete_flag = 0 and t1.promotion_lid = t.lid and t1.activity_lid ='.$activityID.') where t.dpid='.$this->companyId.' and t.end_time >="'.$data.'" and t.delete_flag = 0 and is_available = 0) j';
				$count = $db->createCommand(str_replace('j.*','count(*)',$sql))->queryScalar();
				//var_dump($sql);exit;
				$pages = new CPagination($count);
				$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
				$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
				$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
				$models = $pdata->queryAll();
			}elseif($typeID=="gift"){
				$sql = 'select m.* from(select t1.promotion_lid,t.* from nb_gift t left join nb_promotion_activity_detail t1 on(t.dpid = t1.dpid and t1.delete_flag = 0 and t1.promotion_lid = t.lid and t1.activity_lid ='.$activityID.') where t.dpid='.$this->companyId.' and t.end_time >="'.$data.'" and t.delete_flag = 0 ) m';
				$count = $db->createCommand(str_replace('m.*','count(*)',$sql))->queryScalar();
				//var_dump($sql);exit;
				$pages = new CPagination($count);
				$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
				$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
				$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
				$models = $pdata->queryAll();
			}
			$this->render('detailindex',array(
					'models'=>$models,
					'pages'=>$pages,
					'activityID'=>$activityID,
					'typeID'=>$typeID,
			));
		
			
			
	
	}
	


	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
		$is_sync = DataSync::getInitSync();
        //        Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_promotion_activity set delete_flag="1", is_sync ='.$is_sync.' where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('promotionActivity/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('promotionActivity/index' , 'companyId' => $companyId)) ;
		}
	}

	public function actionStore(){
		$typeID = Yii::app()->request->getParam('typeID');
		$activityID = Yii::app()->request->getParam('activityID');
		$chk = Yii::app()->request->getParam('chk');
		$id = Yii::app()->request->getParam('id');
		$dpid = $this->companyId;
		$is_sync = DataSync::getInitSync();
		
		$db = Yii::app()->db;
		$transaction = $db->beginTransaction();
		try{
			$se=new Sequence("promotion_activity_detail");
			$lid = $se->nextval();
			//$create_at = date('Y-m-d H:i:s',time());
			//$update_at = date('Y-m-d H:i:s',time());
	
			//$sql = 'delete from nb_promotion_activity_detail where promotion_lid = '.$id.' and dpid='.$dpid.' and activity_lid='.$activityID;
			//var_dump($sql);exit;
			$sql = 'update nb_promotion_activity_detail set delete_flag = "1", is_sync ='.$is_sync.' where promotion_lid = '.$id.' and dpid='.$dpid.' and activity_lid='.$activityID;
				
			$command=$db->createCommand($sql);
			$command->execute();
			if($typeID=="normal"){
			if(!empty($chk)){
				$data = array(
						'lid'=>$lid,
						'dpid'=>$dpid,
						'create_at'=>date('Y-m-d H:i:s',time()),
						'update_at'=>date('Y-m-d H:i:s',time()),
						'activity_lid'=>$activityID,
						'promotion_type'=>0,
						'promotion_lid'=>$id,
	
						'delete_flag'=>'0',
						'is_sync'=>$is_sync
				);//Yii::app()->end(json_encode(array("status"=>"success")));
				$command = $db->createCommand()->insert('nb_promotion_activity_detail',$data);
			}
			}elseif ($typeID=="private"){
				if(!empty($chk)){
					$data = array(
							'lid'=>$lid,
							'dpid'=>$dpid,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'activity_lid'=>$activityID,
							'promotion_type'=>1,
							'promotion_lid'=>$id,
				
							'delete_flag'=>'0',
							'is_sync'=>$is_sync
					);//Yii::app()->end(json_encode(array("status"=>"success")));
					$command = $db->createCommand()->insert('nb_promotion_activity_detail',$data);
				}
			}elseif ($typeID=="cupon"){
				if(!empty($chk)){
					$data = array(
							'lid'=>$lid,
							'dpid'=>$dpid,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'activity_lid'=>$activityID,
							'promotion_type'=>2,
							'promotion_lid'=>$id,
				
							'delete_flag'=>'0',
							'is_sync'=>$is_sync
					);//Yii::app()->end(json_encode(array("status"=>"success")));
					$command = $db->createCommand()->insert('nb_promotion_activity_detail',$data);
				}
			}elseif ($typeID=="gift"){
				if(!empty($chk)){
					$data = array(
							'lid'=>$lid,
							'dpid'=>$dpid,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'activity_lid'=>$activityID,
							'promotion_type'=>3,
							'promotion_lid'=>$id,
				
							'delete_flag'=>'0',
							'is_sync'=>$is_sync
					);//Yii::app()->end(json_encode(array("status"=>"success")));
					$command = $db->createCommand()->insert('nb_promotion_activity_detail',$data);
				}
			}
			$transaction->commit(); //提交事务会真正的执行数据库操作
			Yii::app()->end(json_encode(array("status"=>"success")));
			return true;
		}catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			Yii::app()->end(json_encode(array("status"=>"fail")));
			return false;
		}
	
	
			
	}

	
	public function actionStoreprivate(){
		$activityID = Yii::app()->request->getParam('activityID');
		$chk = Yii::app()->request->getParam('chk');
		$id = Yii::app()->request->getParam('id');
		$dpid = $this->companyId;
		$is_sync = DataSync::getInitSync();
	
		$db = Yii::app()->db;
		$transaction = $db->beginTransaction();
		try{
			$se=new Sequence("promotion_activity_detail");
			$lid = $se->nextval();
			//$create_at = date('Y-m-d H:i:s',time());
			//$update_at = date('Y-m-d H:i:s',time());
	
			//$sql = 'delete from nb_promotion_activity_detail where promotion_lid = '.$id.' and dpid='.$dpid.' and activity_lid='.$activityID;
			//var_dump($sql);exit;
			$sql = 'update nb_promotion_activity_detail set delete_flag = "1", is_sync ='.$is_sync.' where promotion_lid = '.$id.' and dpid='.$dpid.' and activity_lid='.$activityID;
				
			$command=$db->createCommand($sql);
			$command->execute();
				
			if(!empty($chk)){
				$data = array(
						'lid'=>$lid,
						'dpid'=>$dpid,
						'create_at'=>date('Y-m-d H:i:s',time()),
						'update_at'=>date('Y-m-d H:i:s',time()),
						'activity_lid'=>$activityID,
						'promotion_type'=>1,
						'promotion_lid'=>$id,
	
						'delete_flag'=>'0',
						'is_sync'=>$is_sync
				);//Yii::app()->end(json_encode(array("status"=>"success")));
				$command = $db->createCommand()->insert('nb_promotion_activity_detail',$data);
			}
			$transaction->commit(); //提交事务会真正的执行数据库操作
			Yii::app()->end(json_encode(array("status"=>"success")));
			return true;
		}catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			Yii::app()->end(json_encode(array("status"=>"fail")));
			return false;
		}		
	}
	
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
	
	public function getLvName($proID,$dpid,$type){
		$LvNames = "";
		if($type=="cupon"){
			//	echo 'ABC';
			$sql = 'select t1.level_name from nb_cupon_branduser t left join nb_brand_user_level t1 on(t.brand_user_lid = t1.lid and t.dpid = t1.dpid and t1.delete_flag = 0) where t.delete_flag  = 0 and  t.to_group = 2 and t.cupon_id ='.$proID.' and t.dpid ='.$dpid;
			$connect = Yii::app()->db->createCommand($sql);
			//	$connect->bindValue(':site_id',$siteId);
			//	$connect->bindValue(':dpid',$dpid);
			$LvNames = $connect->queryAll();
			
		}elseif ($type=="private"){
			$sql = 'select t1.level_name from nb_private_branduser t left join nb_brand_user_level t1 on(t.brand_user_lid = t1.lid and t.dpid = t1.dpid and t1.delete_flag = 0) where t.delete_flag = 0 and  t.to_group = 2 and t.private_promotion_id ='.$proID.' and t.dpid ='.$dpid;
			$connect = Yii::app()->db->createCommand($sql);
			//	$connect->bindValue(':site_id',$siteId);
			//	$connect->bindValue(':dpid',$dpid);
			$LvNames = $connect->queryAll();
				
		}
		//if($siteId && $dpid){
		//$sql = 'select order.site_id, order.dpid,site.type_id, site.serial, site_type.name from nb_order, nb_site, nb_site_type where order.site_id = site.lid and order.dpid = site.dpid';
		//$conn = Yii::app()->db->createCommand($sql);
	
		//}
		return $LvNames;
	}
	
	
}
