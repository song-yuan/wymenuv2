<?php
class DiscountController extends BackendController
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

    public function actionList() {
    	$type = Yii::app()->request->getParam('type');
    	$this->render('list',array(
    			'companyId' => $this->companyId,
    			'type'=>$type,
    	));
    }
    public function actionIndex(){
    	//$brand = Yii::app()->admin->getBrand($this->companyId);
    	$criteria = new CDbCriteria;
    	$criteria->select = 't.*';
    	$criteria->order = ' update_at desc';
    	$criteria->addCondition("t.dpid= ".$this->companyId);
    	$criteria->addCondition('delete_flag=0');
    	//$criteria->params[':brandId'] = $brand->brand_id;
    
    	$pages = new CPagination(Discount::model()->count($criteria));
    	$pages->applyLimit($criteria);
    	$models = Discount::model()->findAll($criteria);
    	 
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
		$model = new Discount();
		$model->dpid = $this->companyId ;
		$is_sync = DataSync::getInitSync();
		//$model->create_time = time();
		//var_dump($model);exit;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Discount');
			$se=new Sequence("discount");
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
				$this->redirect(array('discount/index' , 'companyId' => $this->companyId ));
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
		//echo 'ddd';
		$model = Discount::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
		//Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Discount');
			//$model->update_at=date('Y-m-d H:i:s',time());
			//$model->is_sync=$is_sync;
			//var_dump($model);exit;
			//($model->attributes);var_dump(Yii::app()->request->getPost('Printer'));exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
				$this->redirect(array('discount/index' , 'companyId' => $this->companyId));
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
			Yii::app()->db->createCommand('update nb_discount set delete_flag="1", is_sync ='.$is_sync.' where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('discount/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('discount/index' , 'companyId' => $companyId)) ;
		}
	}

	/**
	 * 现金券列表
	 */
	public function actionDetailindex(){
			$redpkID = Yii::app()->request->getParam('lid');
			$db = Yii::app()->db;
		
			$sql = 'select k.* from(select t1.promotion_lid, t.* from nb_cupon t left join nb_redpacket_detail t1 on(t1.dpid = t.dpid and t1.redpacket_lid ='.$redpkID.' and t.lid = t1.promotion_lid and t1.delete_flag = 0) where t.delete_flag = 0 and t.dpid='.$this->companyId.') k';
			//var_dump($sql);exit;
			$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
			//var_dump($count);exit;
			$pages = new CPagination($count);
			$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
			$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
			$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
			$models = $pdata->queryAll();
			//var_dump($models);exit;
			$this->render('detailindex',array(
					'models'=>$models,
					'pages'=>$pages,
					'redpkID'=>$redpkID,
					//'typeId' => $typeId,
					//'promotionID'=>$promotionID
			));
		}

		public function actionDetailrules(){
			$redpkID = Yii::app()->request->getParam('lid');
			$is_sync = DataSync::getInitSync();
			$model = RedpacketSendStrategy::model()->find('redpacket_lid=:redpkID and dpid=:dpid', array(':redpkID' => $redpkID,':dpid'=> $this->companyId));
			//Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
			if(empty($model)){
			$model = new RedpacketSendStrategy();
			$model->dpid = $this->companyId ;
			
			//$model->create_time = time();
			//var_dump($model);exit;
			if(Yii::app()->request->isPostRequest) {
				//var_dump(Yii::app()->request->getPost('RedpacketSendStrategy'));exit;
				$model->attributes = Yii::app()->request->getPost('RedpacketSendStrategy');
				$se=new Sequence("redpacket_send_strategy");
				$model->lid = $se->nextval();
				$model->redpacket_lid = $redpkID;
				$model->create_at = date('Y-m-d H:i:s',time());
				$model->update_at = date('Y-m-d H:i:s',time());
				$model->delete_flag = '0';
				$model->is_sync = $is_sync;
				//$py=new Pinyin();
				//$model->simple_code = $py->py($model->product_name);
				//var_dump($model);exit;
				if($model->save()){
					Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
					$this->redirect(array('wxRedpacket/index' , 'companyId' => $this->companyId));
				}
			}
			}else{
				if(Yii::app()->request->isPostRequest) {
			
			$model->attributes = Yii::app()->request->getPost('RedpacketSendStrategy');
			$model->update_at=date('Y-m-d H:i:s',time());
			$model->is_sync=$is_sync;
			//($model->attributes);var_dump(Yii::app()->request->getPost('Printer'));exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
				$this->redirect(array('wxRedpacket/index' , 'companyId' => $this->companyId));
			}
		}
		}
			$this->render('detailrules' , array(
					'model' => $model ,
						
			));
		}
		public function actionStore(){
			$redpkID = Yii::app()->request->getParam('redpkID');
			$chk = Yii::app()->request->getParam('chk');
			$id = Yii::app()->request->getParam('id');
			$dpid = $this->companyId;
			$is_sync = DataSync::getInitSync();
			$db = Yii::app()->db;
			$transaction = $db->beginTransaction();
			try{
				$se=new Sequence("redpacket_detail");
				$lid = $se->nextval();
				//$create_at = date('Y-m-d H:i:s',time());
				//$update_at = date('Y-m-d H:i:s',time());
				
				//$sql = 'delete from nb_redpacket_detail where promotion_lid = '.$id.' and dpid='.$dpid.' and redpacket_lid='.$redpkID;
				//var_dump($sql);exit;
				$sql = 'update nb_redpacket_detail set delete_flag = "1", is_sync ='.$is_sync.' where promotion_lid = '.$id.' and dpid='.$dpid.' and redpacket_lid='.$redpkID;
				
				$command=$db->createCommand($sql);
				$command->execute();
				if(!empty($chk)){
					$data = array(
						'lid'=>$lid,
						'dpid'=>$dpid,
						'create_at'=>date('Y-m-d H:i:s',time()),
						'update_at'=>date('Y-m-d H:i:s',time()),
						'redpacket_lid'=>$redpkID,
						'promotion_type'=>0,
						'promotion_lid'=>$id,

						'delete_flag'=>'0',
							'is_sync'=>$is_sync,
						);
				$command = $db->createCommand()->insert('nb_redpacket_detail',$data);
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
		
		public function getLvName($proID,$dpid){
				//$LvNames = "";
			
				//	echo 'ABC';
				$sql = 'select t1.level_name from nb_cupon_branduser t left join nb_brand_user_level t1 on(t.brand_user_lid = t1.lid and t.dpid = t1.dpid and t1.delete_flag = 0) where t.delete_flag = 0 and  t.to_group = 2 and t.cupon_id ='.$proID.' and t.dpid ='.$dpid;
				$connect = Yii::app()->db->createCommand($sql);
				//	$connect->bindValue(':site_id',$siteId);
				//	$connect->bindValue(':dpid',$dpid);
				$LvNames = $connect->queryAll();
				if(!empty($LvNames)){
					return $LvNames;
				}else {
					return $LvNames = "";
				}

			
		}

}
