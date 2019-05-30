<?php
class BuysentpromotionController extends BackendController
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
    
    	$pages = new CPagination(BuysentPromotion::model()->count($criteria));
    	$pages->applyLimit($criteria);
    	$models = BuysentPromotion::model()->findAll($criteria);
    	 
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
		$model = new BuysentPromotion();
		$model->dpid = $this->companyId ;
		$brdulvs = $this->getBrdulv();
		//$model->create_time = time();
		//var_dump($model);exit;
		$is_sync = DataSync::getInitSync();
		if(Yii::app()->request->isPostRequest) {
			if(Yii::app()->user->role > User::SHOPKEEPER) {
				Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
				$this->redirect(array('privatepromotion/index' , 'companyId' => $this->companyId)) ;
			}
			$db = Yii::app()->db;
			//$transaction = $db->beginTransaction();
		//try{
			$model->attributes = Yii::app()->request->getPost('BuysentPromotion');
			$groupID = Yii::app()->request->getParam('hidden1');
			$weekdayID = Yii::app()->request->getParam('weekday');
			$gropids = array();
			$gropids = explode(',',$groupID);
			//$db = Yii::app()->db;
		
			$code=new Sequence("sole_code");
			$sole_code = $code->nextval();
			
			$se=new Sequence("buysent_promotion");
			$lid = $se->nextval();
			$model->lid = $lid;
			if(!empty($groupID)){
				foreach ($gropids as $gropid){
					$userid = new Sequence("buysent_branduser");
					$id = $userid->nextval();
					$data = array(
							'lid'=>$id,
							'dpid'=>$this->companyId,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'private_promotion_id'=>$model->lid,
							'to_group'=>"2",
							'is_used'=>"1",
							'brand_user_lid'=>$gropid,
							'cupon_source'=>'0',
							'delete_flag'=>'0',
							'is_sync'=>$is_sync,
					);
					$command = $db->createCommand()->insert('nb_private_branduser',$data);
					//var_dump($gropid);exit;
				}
			}
			$model->sole_code = ProductCategory::getChscode($this->companyId, $lid, $sole_code);
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			$model->weekday = $weekdayID;
			$model->delete_flag = '0';
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
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('buysentpromotion/index' , 'companyId' => $this->companyId ));
			}
		}
		
		$this->render('create' , array(
				'model' => $model ,
				'brdulvs'=>$brdulvs,
				//'categories' => $categories
		));
	}
	
	/**
	 * 编辑活动
	 */
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		
		$brdulvs = $this->getBrdulv();
		$is_sync = DataSync::getInitSync();
		$model = BuysentPromotion::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
		//var_dump($model);exit;
		$db = Yii::app()->db;
		$sql = 'select t1.brand_user_lid from nb_private_promotion t left join nb_private_branduser t1 on(t.dpid = t1.dpid and t1.to_group = 2 and t1.private_promotion_id = t.lid and t1.delete_flag = 0) where t.delete_flag = 0 and t.lid = '.$lid.' and t.dpid = '.$this->companyId;
		$command = $db->createCommand($sql);
		$userlvs = $command->queryAll();

		$model->is_available =explode(',',$model->is_available);
		if(Yii::app()->request->isPostRequest) {
			if(Yii::app()->user->role > User::SHOPKEEPER) {
				Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
				$this->redirect(array('privatepromotion/index' , 'companyId' => $this->companyId)) ;
			}
			$model->attributes = Yii::app()->request->getPost('BuysentPromotion');
			$groupID = Yii::app()->request->getParam('hidden1');
			$weekdayID = Yii::app()->request->getParam('weekday');
			$gropids = array();
			$gropids = explode(',',$groupID);
			$db = Yii::app()->db;
			if(!empty($groupID)){
				//$sql = 'delete from nb_private_branduser where private_promotion_id='.$lid.' and dpid='.$this->companyId;
				$sql = 'update nb_private_branduser set delete_flag = "1", is_sync ='.$is_sync.' where delete_flag = 0 and private_promotion_id='.$lid.' and dpid='.$this->companyId.' and to_group =2';
				
				$command=$db->createCommand($sql);
				$command->execute();
				foreach ($gropids as $gropid){
					$se = new Sequence("private_branduser");
					$id = $se->nextval();
					$data = array(
							'lid'=>$id,
							'dpid'=>$this->companyId,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'private_promotion_id'=>$lid,
							'to_group'=>"2",
							'is_used'=>"1",
							'brand_user_lid'=>$gropid,
							'cupon_source'=>'0',
							'delete_flag'=>'0',
							'is_sync'=>$is_sync
							);
					$command = $db->createCommand()->insert('nb_private_branduser',$data);
					//var_dump($gropid);exit;
				}
			}else{
				$sql = 'update nb_private_branduser set delete_flag = "1", is_sync ='.$is_sync.' where delete_flag = 0 and private_promotion_id='.$lid.' and dpid='.$this->companyId;
				$command=$db->createCommand($sql);
				$command->execute();
			}
			//print_r(explode(',',$groupID));
			//var_dump($gropid);exit;
			$model->update_at=date('Y-m-d H:i:s',time());
			$model->weekday=$weekdayID;
			$model->is_sync = $is_sync;
			
			$s = $model->is_available;
			if(!empty($s)){
				$st = implode(",",$s);
			}else{
				$st = 0;
			}
			$model->is_available = $st;
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
				$this->redirect(array('buysentpromotion/index' , 'companyId' => $this->companyId));
			}
		}
		$this->render('update' , array(
				'model'=>$model,
				'brdulvs'=>$brdulvs,
				'userlvs'=>$userlvs,
		));
	}


	
	/**
	 * 删除现金券
	 */
	public function actionDelete(){
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('buysentpromotion/index' , 'companyId' => $this->companyId)) ;
		}
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
		$is_sync = DataSync::getInitSync();
        //        Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_buysent_promotion set delete_flag="1",is_sync ='.$is_sync.' where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			Yii::app()->db->createCommand('update nb_buysent_promotion_detail set delete_flag="1",is_sync ='.$is_sync.' where buysent_pro_id in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('buysentpromotion/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('buysentpromotion/index' , 'companyId' => $companyId)) ;
		}
	}

	public function actionAddprod() {
		$this->layout = '/layouts/main_picture';
		$pid = Yii::app()->request->getParam('pid',0);
		$phscode = Yii::app()->request->getParam('phscode',0);
		$prodname = Yii::app()->request->getParam('prodname',0);
	
		$criteria = new CDbCriteria;
		$criteria->condition =  't.pid != 0 and t.delete_flag=0 and t.dpid='.$this->companyId ;
		$criteria->order = ' t.lid asc ';
		$models = ProductCategory::model()->findAll($criteria);
		//查询分类
	
		$criteria = new CDbCriteria;
		$criteria->condition =  ' t.delete_flag=0 and t.dpid='.$this->companyId ;
		$criteria->order = ' t.lid asc ';
		$products = Product::model()->findAll($criteria);
		
		$criteria = new CDbCriteria;
		$criteria->condition =  ' t.delete_flag=0 and t.dpid='.$this->companyId ;
		$criteria->order = ' t.lid asc ';
		$productSets = ProductSet::model()->findAll($criteria);
		
	
		$db = Yii::app()->db;
		$sql = 'select t.* from nb_taste t where t.taste_group_id in(select t1.taste_group_id from nb_product_taste t1 where t1.delete_flag = 0 and t1.product_id='.$pid.' and t1.dpid='.$this->companyId.') and t.delete_flag = 0 and t.dpid ='.$this->companyId ;
		$command1 = $db->createCommand($sql);
		$prodTastes = $command1->queryAll();
		//查询产品口味
	
		$this->render('addprod' , array(
				'models' => $models,
				'prodname' => $prodname,
				'pid' => $pid,
				'phscode' => $phscode,
				'products' => $products,
				'productSets' => $productSets,
				'prodTastes' => $prodTastes,
				'action' => $this->createUrl('buysentpromotion/addprod' , array('companyId'=>$this->companyId))
		));
	}
	
	public function actionDetailindex(){
		$promotionID = Yii::app()->request->getParam('lid');
		$typeId = Yii::app()->request->getParam('typeId');
		$categoryId = Yii::app()->request->getParam('cid',"");
		$csinquery=Yii::app()->request->getPost('csinquery',"");
		$prodname = Yii::app()->request->getParam('prodname');
		
		$db = Yii::app()->db;
		if($typeId=='product')
		{
			$products = Product::model()->findAll('dpid='.$this->companyId.' and delete_flag=0');
			$productSets = ProductSet::model()->findAll('dpid='.$this->companyId.' and delete_flag=0');
			
			$sql = 'select * from nb_buysent_promotion_detail where dpid='.$this->companyId.' and buysent_pro_id='.$promotionID.' and delete_flag=0';
			$count = $db->createCommand(str_replace('*','count(*)',$sql))->queryScalar();
			$pages = new CPagination($count);
			$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
			$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
			$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
			$models = $pdata->queryAll();
			$categories = $this->getCategories();
			$this->render('detailindex',array(
					'models'=>$models,
					'pages'=>$pages,
					'products'=>$products,
					'productSets'=>$productSets,
					'typeId' => $typeId,
					'promotionID'=>$promotionID,
					'prodname'=>$prodname
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
	
	public function actionStorbuysent(){
		$ids = Yii::app()->request->getPost('ids');
		$matids = Yii::app()->request->getParam('matids');
		$prodid = Yii::app()->request->getParam('prodid');
		$prodcode = Yii::app()->request->getParam('prodcode');
		$tasteid = Yii::app()->request->getParam('tasteid');
		$dpid = $this->companyId;
		$materialnums = array();
		$materialnums = explode(';',$matids);
		$msg = '';
		$db = Yii::app()->db;
		$transaction = $db->beginTransaction();
		try{
			foreach ($materialnums as $materialnum){
				$materials = array();
				$materials = explode(',',$materialnum);
				$mateid = $materials[0];
				$matecode = $materials[1];
				$bisset = $materials[2];
				$matenum = $materials[3];
				$sentid = $materials[4];
				$sentcode = $materials[5];
				$sisset = $materials[6];
				$sentnum = $materials[7];
				$buysentprodetail = BuysentPromotionDetail::model()->find('buysent_pro_id =:bpid and product_id =:prodid and dpid=:companyId and delete_flag=0', array(':bpid'=>$prodid, ':prodid'=>$mateid, ':companyId'=>$this->companyId));
				if(!empty($mateid)&&empty($buysentprodetail)){
					$se = new Sequence("buysent_promotion_detail");
					$id = $se->nextval();
					$code=new Sequence("sole_code");
					$sole_code = $code->nextval();
					$dataprodbom = array(
							'lid'=>$id,
							'dpid'=>$dpid,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'sole_code'=>ProductCategory::getChscode($this->companyId, $id, $sole_code),
							'buysent_pro_id'=>$prodid,
							'is_set'=>$bisset,
							'fa_sole_code'=>$prodcode,
							'product_id'=>$mateid,
							'phs_code'=>$matecode,
							'buy_num'=>$matenum,
							's_product_id'=>$sentid,
							's_phs_code'=>$sentcode,
							'sent_num'=>$sentnum,
							'limit_num'=>'0',
							'group_no'=>'1',
							'is_available'=>'1',
							'source'=>'0',
							'delete_flag'=>'0',
					);
					$command = $db->createCommand()->insert('nb_buysent_promotion_detail',$dataprodbom);
				}
			}
			$transaction->commit(); //提交事务会真正的执行数据库操作
			Yii::app()->end(json_encode(array('status'=>true,'msg'=>$msg)));
			
		} catch (Exception $e) {
				$transaction->rollback(); //如果操作失败, 数据回滚
				Yii::app()->end(json_encode(array('status'=>false,'msg'=>'保存失败',)));
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
			Yii::app()->db->createCommand('update nb_buysent_promotion_detail set delete_flag="1", is_sync ='.$is_sync.' where lid in('.$ids.') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			Yii::app()->end(json_encode(array("status"=>"success")));
			//$this->redirect(array('privatepromotion/detailindex' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要移除的项目'));
			$this->redirect(array('buysentpromotion/detailindex' , 'companyId' => $companyId)) ;
		}
	}
	public function actionStordetail(){
	
		$is_sync = DataSync::getInitSync();
		//var_dump($companyId);exit;
		$matids = Yii::app()->request->getParam('matids');
		$buynum = Yii::app()->request->getParam('buynum');
		$sentnum = Yii::app()->request->getParam('sentnum');
		$tasteid = Yii::app()->request->getParam('tasteid');
		$dpid = $this->companyId;
		$msg = '';
		$db = Yii::app()->db;
		//var_dump($matids);exit;
		$transaction = $db->beginTransaction();
		try{
			Yii::app()->db->createCommand('update nb_buysent_promotion_detail set buy_num= '.$buynum.', sent_num = '.$sentnum.', is_sync ='.$is_sync.' where lid in('.$matids.') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			//Yii::app()->end(json_encode(array('status'=>true,'msg'=>$msg)));
			$transaction->commit(); //提交事务会真正的执行数据库操作
			Yii::app()->end(json_encode(array('status'=>true,'msg'=>$msg)));
				
		} catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			Yii::app()->end(json_encode(array('status'=>false,'msg'=>'保存失败',)));
		}
	}	
	
	
	public function actionPromotiondetail(){
		$sc = Yii::app()->request->getPost('csinquery');
		$promotionID = Yii::app()->request->getParam('lid');
		//$typeId = Yii::app()->request->getParam('typeId');
		$categoryId = Yii::app()->request->getParam('cid',"");
		$fromId = Yii::app()->request->getParam('from','sidebar');
		$csinquery=Yii::app()->request->getPost('csinquery',"");
		//var_dump($csinquery);exit;
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
			$sql = 'select k.* from(select t.promotion_money,t.promotion_discount,t.order_num,t.is_set,t.product_id,t.private_promotion_id,t1.*
							from nb_private_promotion_detail t left join nb_product t1 on(t.dpid = t1.dpid and t1.lid = t.product_id  and t1.delete_flag = 0 )
							where t.delete_flag = 0 and t.dpid='.$this->companyId.' and t.is_set = 0 and t.private_promotion_id = '.$promotionID.' and t1.category_id = '.$categoryId.' ) k';
	
		}
	
		elseif(!empty($csinquery)){
			//$criteria->condition.=' and t.simple_code like "%'.strtoupper($csinquery).'%"';
			$sql = 'select k.* from(select t.promotion_money,t.promotion_discount,t.order_num,t.is_set,t.product_id,t.private_promotion_id,t1.*
							from nb_private_promotion_detail t left join nb_product t1 on(t.dpid = t1.dpid and t1.lid = t.product_id and t1.delete_flag = 0 )
									where t.delete_flag = 0 and t.dpid='.$this->companyId.' and t.is_set = 0 and t.private_promotion_id = '.$promotionID.' and t1.simple_code like "%'.strtoupper($csinquery).'%" ) k';
	
		}else{
			$sql = 'select k.* from(select t.promotion_money,t.promotion_discount,t.order_num,t.is_set,t.product_id,t.private_promotion_id,t1.*
							from nb_private_promotion_detail t left join nb_product t1 on(t.dpid = t1.dpid and t1.lid = t.product_id and t1.delete_flag = 0 )
									where t.delete_flag = 0 and t.dpid='.$this->companyId.' and t.is_set = 0 and t.private_promotion_id = '.$promotionID.') k' ;
	
		}
		//$sql = 'select k.* from(select t1.promotion_money,t1.promotion_discount,t1.order_num,t1.is_set,t1.product_id,t1.private_promotion_id,t.* from nb_private_promotion_detail t1  where t1.is_set = 0 and t1.private_promotion_id ='.$promotionID.' t1.delete_flag = 0 and t1.dpid='.$this->companyId.') k' ;
	
	
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
				//'typeId' => $typeId,
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
