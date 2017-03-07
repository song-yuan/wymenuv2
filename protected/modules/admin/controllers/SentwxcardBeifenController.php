<?php
class SentwxcardBeifenController extends BackendController
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
    	$criteria->order = ' t.update_at desc';
    	$criteria->addCondition("t.dpid= ".$this->companyId);
    	$criteria->addCondition('t.delete_flag=0');
    	$criteria->addCondition('t.type=0');
    	//$criteria->params[':brandId'] = $brand->brand_id;
    
    	$pages = new CPagination(SentwxcardPromotion::model()->count($criteria));
    	$pages->applyLimit($criteria);
    	$models = SentwxcardPromotion::model()->findAll($criteria);
    	 
    	$this->render('index',array(
    			'models'=>$models,
    			'pages'=>$pages,
    	));
    }

	/**
	 * 创建活动，并发送系统消息
	 */
	public function actionCreate(){
		$model = new SentwxcardPromotion();
		$model->dpid = $this->companyId ;
		$brdulvs = $this->getBrdulv();
		//$model->create_time = time();
		//var_dump($model);exit;
		$is_sync = DataSync::getInitSync();
		if(Yii::app()->request->isPostRequest) {
			if(Yii::app()->user->role > User::SHOPKEEPER) {
				Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
				$this->redirect(array('senwxcardpromotion/index' , 'companyId' => $this->companyId)) ;
			}
			$db = Yii::app()->db;
			//$transaction = $db->beginTransaction();
		//try{
			$model->attributes = Yii::app()->request->getPost('SentwxcardPromotion');
		
			$code=new Sequence("sole_code");
			$sole_code = $code->nextval();
			
			$se=new Sequence("sentwxcard_promotion");
			$lid = $se->nextval();
			$model->lid = $lid;
			$model->sole_code = ProductCategory::getChscode($this->companyId, $lid, $sole_code);
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			$model->delete_flag = '0';
			$model->type = '0';
			$model->is_sync = $is_sync;
			//var_dump($model);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('sentwxcardBeifen/index' , 'companyId' => $this->companyId ));
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
		$model = SentwxcardPromotion::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
		//var_dump($model);exit;
		$db = Yii::app()->db;
		$sql = 'select t1.brand_user_lid from nb_private_promotion t left join nb_private_branduser t1 on(t.dpid = t1.dpid and t1.to_group = 2 and t1.private_promotion_id = t.lid and t1.delete_flag = 0) where t.delete_flag = 0 and t.lid = '.$lid.' and t.dpid = '.$this->companyId;
		$command = $db->createCommand($sql);
		$userlvs = $command->queryAll();

		
		if(Yii::app()->request->isPostRequest) {
			if(Yii::app()->user->role > User::SHOPKEEPER) {
				Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
				$this->redirect(array('sentwxcardBeifen/index' , 'companyId' => $this->companyId)) ;
			}
			$model->attributes = Yii::app()->request->getPost('SentwxcardPromotion');
			$db = Yii::app()->db;
			
			$model->update_at=date('Y-m-d H:i:s',time());
			$model->is_sync = $is_sync;
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
				$this->redirect(array('sentwxcardBeifen/index' , 'companyId' => $this->companyId));
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
			$this->redirect(array('sentwxcardBeifen/index' , 'companyId' => $this->companyId)) ;
		}
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
		$is_sync = DataSync::getInitSync();
        //        Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_sentwxcard_promotion set delete_flag="1",is_sync ='.$is_sync.' where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			Yii::app()->db->createCommand('update nb_sentwxcard_promotion_detail set delete_flag="1",is_sync ='.$is_sync.' where sentwxcard_pro_id in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('sentwxcardBeifen/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('sentwxcardBeifen/index' , 'companyId' => $companyId)) ;
		}
	}

	public function actionAddprod() {
		$this->layout = '/layouts/main_picture';
		$pid = Yii::app()->request->getParam('pid',0);
		$phscode = Yii::app()->request->getParam('phscode',0);
		$prodname = Yii::app()->request->getParam('prodname',0);
	
		$criteria = new CDbCriteria;
		$criteria->condition =  't.is_available = 0 and t.delete_flag=0 and t.dpid='.$this->companyId.' and t.end_time >="'.date('Y-m-d H:i:s',time()).'"';
		$criteria->order = ' t.lid asc ';
		$models = Cupon::model()->findAll($criteria);
		//查询原料分类
	
		$criteria = new CDbCriteria;
		$criteria->condition =  ' t.delete_flag=0 and t.dpid='.$this->companyId ;
		$criteria->order = ' t.lid asc ';
		$products = Product::model()->findAll($criteria);
		//查询原料信息
	
		$db = Yii::app()->db;
		$sql = 'select t.* from nb_taste t where t.taste_group_id in(select t1.taste_group_id from nb_product_taste t1 where t1.delete_flag = 0 and t1.product_id='.$pid.' and t1.dpid='.$this->companyId.') and t.delete_flag = 0 and t.dpid ='.$this->companyId ;
		$command1 = $db->createCommand($sql);
		$prodTastes = $command1->queryAll();
		//查询产品口味
	
		//var_dump($products);exit;
		$this->render('addprod' , array(
				'models' => $models,
				'prodname' => $prodname,
				'pid' => $pid,
				'phscode' => $phscode,
				'products' => $products,
				'prodTastes' => $prodTastes,
				'action' => $this->createUrl('sentwxcardBeifen/addprod' , array('companyId'=>$this->companyId))
		));
	}
	
	public function actionDetailindex(){
		//$sc = Yii::app()->request->getPost('csinquery');
		$promotionID = Yii::app()->request->getParam('lid');
		$typeId = Yii::app()->request->getParam('typeId');
		$categoryId = Yii::app()->request->getParam('cid',"");
		$csinquery=Yii::app()->request->getPost('csinquery',"");

		$prodname = Yii::app()->request->getParam('prodname');
		//var_dump($typeId);exit;
		$db = Yii::app()->db;
		
			if(empty($promotionID)){
				$promotionID = Yii::app()->request->getParam('promotionID');
			}
			
			$sql = 'select k.* from(select t1.cupon_title,t1.cupon_money,t1.min_consumer,t.* from nb_sentwxcard_promotion_detail t left join nb_cupon t1 on(t.dpid = t1.dpid and t1.delete_flag = 0 and t1.lid = t.wxcard_id) where t.sentwxcard_pro_id = '.$promotionID.' and t.delete_flag = 0 and t.dpid = '.$this->companyId.' ) k';	
			
			//var_dump($sql);exit;
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
					'categoryId'=>$categoryId,
					'typeId' => $typeId,
					'promotionID'=>$promotionID,
					'prodname'=>$prodname
			));
	}
	

	
	public function actionStorsentwxcard(){
		
		$is_sync = DataSync::getInitSync();
		$plids = Yii::app()->request->getParam('plids');
		$falid = Yii::app()->request->getParam('falid');
		$facode = Yii::app()->request->getParam('facode');
		$dpid = $this->companyId;
		$materialnums = array();
		$materialnums = explode(';',$plids);
		$msg = '';
		$db = Yii::app()->db;
		//var_dump($plids,$materialnums);exit;
		$transaction = $db->beginTransaction();
		try{
			//var_dump($materialnums);exit;
			foreach ($materialnums as $materialnum){
				$materials = array();
				$materials = explode(',',$materialnum);
				$plid = $materials[0];
				$pcode = $materials[1];
				//var_dump($plid.'@'.$pcode);exit;
				$cupons = Cupon::model()->find('lid=:lid and dpid=:companyId and delete_flag=0' , array(':lid'=>$plid,':companyId'=>$this->companyId));
				$sentwxcardtprodetail = sentwxcardBeifenDetail::model()->find('sentwxcard_pro_id =:plid and wxcard_id =:prodid and dpid=:companyId and delete_flag=0', array(':plid'=>$falid, ':prodid'=>$plid, ':companyId'=>$this->companyId));
				//var_dump($buysentprodetail);exit;
				if(!empty($cupons)&&!empty($plid)&&empty($sentwxcardtprodetail)){
					$se = new Sequence("sentwxcard_promotion_detail");
					$id = $se->nextval();
					$code=new Sequence("sole_code");
					$sole_code = $code->nextval();
					//Yii::app()->end(json_encode(array('status'=>true,'msg'=>'成功','matids'=>$prodmaterials['material_name'],'prodid'=>$matenum,'tasteid'=>$tasteid)));
					$dataprodbom = array(
							'lid'=>$id,
							'dpid'=>$dpid,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'sole_code'=>ProductCategory::getChscode($this->companyId, $id, $sole_code),
							'sentwxcard_pro_id'=>$falid,
							'fa_sole_code'=>$facode,
							'card_type'=>'0',
							'wxcard_id'=>$plid,
							'card_code'=>$pcode,
							'sent_num'=>'1',
							'is_available'=>'0',
							'source'=>'0',
							'delete_flag'=>'0',
							'is_sync'=>$is_sync,
					);
					//$msg = $prodid.'@@'.$mateid.'@@'.$prodmaterials['product_name'].'@@'.$prodmaterials['phs_code'].'@@'.$prodcode;
					//var_dump($dataprodbom);exit;
					$command = $db->createCommand()->insert('nb_sentwxcard_promotion_detail',$dataprodbom);
					
				}
				
			}
			//Yii::app()->end(json_encode(array('status'=>true,'msg'=>$msg)));
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
			Yii::app()->db->createCommand('update nb_sentwxcard_promotion_detail set delete_flag="1", is_sync ='.$is_sync.' where lid in('.$ids.') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			Yii::app()->end(json_encode(array("status"=>"success")));
			//$this->redirect(array('privatepromotion/detailindex' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要移除的项目'));
			$this->redirect(array('sentwxcardBeifen/detailindex' , 'companyId' => $companyId)) ;
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
