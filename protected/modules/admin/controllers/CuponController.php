<?php
class CuponController extends BackendController
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
    	// echo "xxxx";exit();
    	//$brand = Yii::app()->admin->getBrand($this->companyId);
    	$criteria = new CDbCriteria;
    	$criteria->select = 't.*';
    	$criteria->order = ' lid desc';
    	$criteria->addCondition("t.dpid= ".$this->companyId);
    	$criteria->addCondition('delete_flag=0');
    	//$criteria->params[':brandId'] = $brand->brand_id;

    	$pages = new CPagination(Cupon::model()->count($criteria));
        $pages->pageSize = 12;
    	$pages->applyLimit($criteria);
    	$models = Cupon::model()->findAll($criteria);

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
		$model = new Cupon();
		$model->dpid = $this->companyId ;
		$brdulvs = $this->getBrdulv();
		//$model->create_time = time();
		//var_dump($model);exit;
		$is_sync = DataSync::getInitSync();
		if(Yii::app()->request->isPostRequest) {
            $model->attributes = Yii::app()->request->getPost('Cupon');
            $cupon = Yii::app()->request->getPost('Cupon');
            $model->type = implode(",",$cupon['type']);
            // p($model);
			if(Yii::app()->user->role > User::SHOPKEEPER) {
				Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
				$this->redirect(array('cupon/index' , 'companyId' => $this->companyId)) ;
			}
			$db = Yii::app()->db;
			$groupID = Yii::app()->request->getParam('hidden1');
			$gropids = array();
			$gropids = explode(',',$groupID);
			//$db = Yii::app()->db;
			$beginday = Yii::app()->request->getParam('cupon_begin_day');
			$day = Yii::app()->request->getParam('cupon_day');

			$se=new Sequence("cupon");
            $lid= $se->nextval();
			$model->lid =$lid;

			$model->day_begin = $beginday;
			$model->day = $day;

            $code=new Sequence("cupon_code");
            $codeid = $code->nextval();
            $model->sole_code = Common::getCode($this->companyId,$lid,$codeid);
			$model->source='0';
            if(Yii::app()->user->role < 11){
            	$model->type_dpid = '0';
            }else{
            	$model->type_dpid = '1';
            }
            if(!empty($groupID)){
				foreach ($gropids as $gropid){
					$userid = new Sequence("cupon_branduser");
					$id = $userid->nextval();

					$data = array(
							'lid'=>$id,
							'dpid'=>$this->companyId,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'cupon_id'=>$model->lid,
							'cupon_source'=>'0',
							'source_id'=>'0',
							'to_group'=>"2",
							'brand_user_lid'=>$gropid,
							'delete_flag'=>'0',
							'is_sync'=>$is_sync,
					);
					$command = $db->createCommand()->insert('nb_cupon_branduser',$data);
					//var_dump($gropid);
				}
			}
			//$sync = DataSync::getInitSync();
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			$model->delete_flag = '0';
			$model->is_sync = $is_sync;
			//var_dump($model);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('cupon/index' , 'companyId' => $this->companyId ));
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
		//echo 'ddd';
		//$groupID = Yii::app()->request->getParam('str');
		//var_dump($groupID);exit;
		$brdulvs = $this->getBrdulv();
		$model = Cupon::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
		//Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		$model->type =explode(',',$model->type);
		// p($model);
		$db = Yii::app()->db;
		$sql = 'select t1.brand_user_lid from nb_cupon t left join nb_cupon_branduser t1 on(t.dpid = t1.dpid and t1.to_group = 2 and t1.cupon_id = t.lid and t1.delete_flag = 0)where t.delete_flag = 0 and t.lid = '.$lid.' and t.dpid = '.$this->companyId;
		$command = $db->createCommand($sql);
		$userlvs = $command->queryAll();
		//var_dump($userlvs);exit;
		$is_sync = DataSync::getInitSync();
		if(Yii::app()->request->isPostRequest) {
			if(Yii::app()->user->role > User::SHOPKEEPER) {
				Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
				$this->redirect(array('cupon/index' , 'companyId' => $this->companyId)) ;
			}
            $cupon = Yii::app()->request->getPost('Cupon');
            $model->attributes = $cupon;
            $model->type = implode(",",$cupon['type']);
			$groupID = Yii::app()->request->getParam('hidden1');
			$beginday = Yii::app()->request->getParam('cupon_begin_day');
			$day = Yii::app()->request->getParam('cupon_day');
			$gropids = array();
			$gropids = explode(',',$groupID);
			$db = Yii::app()->db;
			if(!empty($groupID)){
				//$sql = 'delete from nb_cupon_branduser where cupon_id='.$lid.' and dpid='.$this->companyId;
                $sql = 'update nb_cupon_branduser set delete_flag="1",is_sync ='.$is_sync.' where cupon_id='.$lid.' and dpid='.$this->companyId.' and to_group=2';
				$command=$db->createCommand($sql);
				$command->execute();
				foreach ($gropids as $gropid){
					$se = new Sequence("cupon_branduser");
					$id = $se->nextval();

					$data = array(
							'lid'=>$id,
							'dpid'=>$this->companyId,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'cupon_id'=>$model->lid,
							'cupon_source'=>'0',
							//'source_id'=>$model->lid,
							'to_group'=>"2",
							'brand_user_lid'=>$gropid,
							'delete_flag'=>'0',
							'is_used'=>1,
							'is_sync'=>$is_sync,
					);
					$command = $db->createCommand()->insert('nb_cupon_branduser',$data);
					//var_dump($gropid);exit;
				}
			}else{
				$sql = 'update nb_cupon_branduser set delete_flag = "1", is_sync ='.$is_sync.' where source_id='.$lid.' and dpid='.$this->companyId;
				$command=$db->createCommand($sql);
				$command->execute();
			}
			//print_r(explode(',',$groupID));
			$model->day_begin = $beginday;
			$model->day = $day;
			$model->update_at=date('Y-m-d H:i:s',time());
			$model->is_sync=$is_sync;
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
				$this->redirect(array('cupon/index' , 'companyId' => $this->companyId));
			}
		}
		$this->render('update' , array(
				'model'=>$model,
				'brdulvs'=>$brdulvs,
				'userlvs'=>$userlvs,
		));

	}

	public function actionDetailinfo(){
		$lid = Yii::app()->request->getParam('lid');
		$cuponcode = Yii::app()->request->getParam('code');
		//var_dump($lid.'@@@'.$cuponcode);exit;
		$db = Yii::app()->db;
		$criteria = new CDbCriteria;
		$criteria->select = 't.*';
		$criteria->order = ' lid desc';
		$criteria->addCondition("t.dpid= ".$this->companyId);
		$criteria->addCondition('delete_flag=0');

		$sqldpid = 'select k.company_name,t.* from nb_cupon_dpid t left join nb_company k on(t.cupon_dpid = k.dpid and k.delete_flag =0) where t.dpid ='.$this->companyId.' and t.delete_flag =0 and t.cupon_code ="'.$cuponcode.'"';
		$command=$db->createCommand($sqldpid);
		$cupondpids = $command->queryAll();

		$sqlprod = 'select k.product_name,t.* from nb_cupon_product t left join nb_product k on(t.prod_code = k.phs_code and k.delete_flag =0 and k.dpid=t.dpid) where t.dpid ='.$this->companyId.' and t.delete_flag =0 and t.cupon_code ="'.$cuponcode.'"';
		$command=$db->createCommand($sqlprod);
		$cuponprods = $command->queryAll();

		$sqlcomps = 'select t.dpid,t.company_name from nb_company t where t.delete_flag = 0 and t.comp_dpid = '.$this->companyId;
		$command = $db->createCommand($sqlcomps);
		$dpids = $command->queryAll();
		//var_dump($dpids);exit;
		$pages = new CPagination(Cupon::model()->count($criteria));
		$pages->pageSize = 12;
		$pages->applyLimit($criteria);
		$models = Cupon::model()->findAll($criteria);


		$categories = $this->getCategories();
		// var_dump($categories); exit();
		$categoryId=0;
        $products = $this->getProducts($categoryId);
        $productslist=CHtml::listData($products, 'phs_code', 'product_name','category_id');
       // var_dump($productslist); exit();
		$this->render('detailinfo',array(
				'cuponprods'=>$cuponprods,
				'cupondpids'=>$cupondpids,
				'pages'=>$pages,
				'dpids'=>$dpids,
				'categories' => $categories,
				'categoryId' => $categoryId,
                'products' => $productslist,
				'cuponid' => $lid,
				'cuponcode' => $cuponcode,
		));
	}


	/**
	 * 添加对应单品
	 */
	public function actionAddprod(){
		$db = Yii::app()->db;
		$prodcodes = Yii::app()->request->getPost('product_id');
		// var_dump($prodcode);exit();
		$cuid = Yii::app()->request->getParam('cuid');
		$cucode = Yii::app()->request->getParam('cucode');
		foreach ($prodcodes as $prodcode) {
			$sql = 'select * from nb_product where dpid ='.$this->companyId.' and phs_code ="'.$prodcode.'" and delete_flag=0';
			// var_dump($sql);exit;
			$command = $db->createCommand($sql);
			$prod = $command->queryRow();

			$sqls = 'select * from nb_cupon_product where dpid ='.$this->companyId.' and prod_code ="'.$prodcode.'" and delete_flag =0 and cupon_id='.$cuid;
			$command = $db->createCommand($sqls);
			$cuprod = $command->queryRow();
			//var_dump($prod.'##'.$cuprod);exit;
				if(!empty($prod)&&empty($cuprod)){
					$se = new Sequence("cupon_product");
					$id = $se->nextval();

					$data = array(
							'lid'=>$id,
							'dpid'=>$this->companyId,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'cupon_id'=>$cuid,
							'cupon_code'=>$cucode,
							'prod_code'=>$prodcode,
							'delete_flag'=>'0',
							'is_sync'=>'11111',
					);
					//var_dump($data);exit;
					$command = $db->createCommand()->insert('nb_cupon_product',$data);

					if($command){
						$sql = 'update nb_cupon set type_prod = 1 where dpid ='.$this->companyId.' and lid ='.$cuid;
						$result = $db->createCommand($sql)->execute();
						Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
						$this->redirect(array('cupon/detailinfo' , 'lid'=>$cuid,'code'=>$cucode,'companyId' => $this->companyId));
					}
				}else{
					Yii::app()->user->setFlash('error' ,yii::t('app', '该菜品已有代金券，请重新选择！'));
					$this->redirect(array('cupon/detailinfo' , 'lid'=>$cuid,'code'=>$cucode,'companyId' => $this->companyId));
				}
			}
			
			

				
			

			

	}


	/**
	 * 添加限制店铺
	 */
	public function actionAdddpid(){
		$db = Yii::app()->db;
		$cupondpids = Yii::app()->request->getParam('dpids');
		$cuid = Yii::app()->request->getParam('cuid');
		$cucode = Yii::app()->request->getParam('cucode');
		//var_dump($dpids.'@@'.$cuid.'$$'.$cucode);exit;

		$dpids = array();
		$dpids = explode(',',$cupondpids);
		$transaction = $db->beginTransaction();
		try{
			foreach ($dpids as $dpid){
				$sqls = 'select * from nb_cupon_dpid where dpid ='.$this->companyId.' and cupon_dpid ="'.$dpid.'" and delete_flag =0 and cupon_id='.$cuid;
				$command = $db->createCommand($sqls);
				$cudpid = $command->queryRow();
				if(empty($cudpid)){
					$se = new Sequence("cupon_dpid");
					$id = $se->nextval();
					$data = array(
							'lid'=>$id,
							'dpid'=>$this->companyId,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'cupon_id'=>$cuid,
							'cupon_code'=>$cucode,
							'cupon_dpid'=>$dpid,
							'delete_flag'=>'0',
							'is_sync'=>'11111',
					);
					//var_dump($data);exit;
					$command = $db->createCommand()->insert('nb_cupon_dpid',$data);
				}
			}
			$transaction->commit();
			$sql = 'update nb_cupon set type_dpid = 2 where dpid ='.$this->companyId.' and lid ='.$cuid;
			$result = $db->createCommand($sql)->execute();
			Yii::app()->end(json_encode(array("status"=>true)));
		}catch (Exception $e){
        		$transaction->rollback();
        		Yii::app()->end(json_encode(array("status"=>false)));
        }

	}

	/**
	 * 移除对应单品
	 */
	public function actionDelprod(){
		$db = Yii::app()->db;
		$prodcode = Yii::app()->request->getParam('prodcode');
		$cuid = Yii::app()->request->getParam('cuid');
		$cucode = Yii::app()->request->getParam('cucode');

		$sqls = 'delete from nb_cupon_product where dpid ='.$this->companyId.' and prod_code ="'.$prodcode.'" and delete_flag =0 and cupon_id='.$cuid;
		$command = $db->createCommand($sqls);
		$cuprod = $command->execute();

		if($cuprod){
			$sqls = 'select * from nb_cupon_product where dpid ='.$this->companyId.' and delete_flag =0 and cupon_id='.$cuid;
			$cuponprods = $db->createCommand($sqls)->queryAll();
			if(!empty($cuponprods)){
				Yii::app()->end(json_encode(array("status"=>true)));
			}else{
				$sql = 'update nb_cupon set type_prod = 0 where dpid ='.$this->companyId.' and lid ='.$cuid;
				$result = $db->createCommand($sql)->execute();
				if($result){
					Yii::app()->end(json_encode(array("status"=>true)));
				}else{
					Yii::app()->end(json_encode(array("status"=>false)));
				}
			}

		}else{
			Yii::app()->end(json_encode(array("status"=>false)));
		}
	}


	/**
	 * 移除限制店铺
	 */
	public function actionDeldpid(){
		$db = Yii::app()->db;
		$cudpid = Yii::app()->request->getParam('cudpid');
		$cuid = Yii::app()->request->getParam('cuid');
		$cucode = Yii::app()->request->getParam('cucode');

		$sqls = 'delete from nb_cupon_dpid where dpid ='.$this->companyId.' and cupon_dpid ="'.$cudpid.'" and delete_flag =0 and cupon_id='.$cuid;
		$command = $db->createCommand($sqls);
		$cudpid = $command->execute();

		if($cudpid){
			$sqls = 'select * from nb_cupon_dpid where dpid ='.$this->companyId.' and delete_flag =0 and cupon_id='.$cuid;
			$cupondpid = $db->createCommand($sqls)->queryAll();
			if(!empty($cupondpid)){
				Yii::app()->end(json_encode(array("status"=>true)));
			}else{
				$sql = 'update nb_cupon set type_dpid = 0 where dpid ='.$this->companyId.' and lid ='.$cuid;
				$result = $db->createCommand($sql)->execute();
				if($result){
					Yii::app()->end(json_encode(array("status"=>true)));
				}else{
					Yii::app()->end(json_encode(array("status"=>false)));
				}
			}

		}else{
			Yii::app()->end(json_encode(array("status"=>false)));
		}
	}

	private function getBrdulv(){
		$criteria = new CDbCriteria;
		$criteria->with = '';
		$criteria->condition = ' t.delete_flag=0 and t.level_type=1 and t.dpid='.$this->companyId ;
		$criteria->order = ' t.min_total_points asc ' ;
		$brdules = BrandUserLevel::model()->findAll($criteria);
		if(!empty($brdules)){
			return $brdules;
		}
	}


	/**
	 * 删除现金券
	 */
	public function actionDelete(){
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('cupon/index' , 'companyId' => $this->companyId)) ;
		}
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));

		$is_sync = DataSync::getInitSync();
        //        Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		$lid = Yii::app()->request->getParam('lid');
                if($lid){
                   Yii::app()->db->createCommand('update nb_cupon set delete_flag=1,update_at="'.date('Y-m-d H:i:s',time()).'" where lid = "'.$lid.'"')->execute();
                }
                $this->redirect(array('cupon/index','companyId'=>$this->companyId));
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
	private function getProducts($categoryId){
		if($categoryId==0)
		{
			$products = Product::model()->findAll('dpid=:companyId and delete_flag=0' , array(':companyId' => $this->companyId));
		}else{
			$products = Product::model()->findAll('dpid=:companyId and category_id=:categoryId and delete_flag=0' , array(':companyId' => $this->companyId,':categoryId'=>$categoryId)) ;
		}
		$products = $products ? $products : array();
		return $products;
		//return CHtml::listData($products, 'lid', 'product_name');
	}
	public function actionGetChildren(){
		$categoryId = Yii::app()->request->getParam('pid',0);
		// var_dump($productSetId);exit;
		if(!$categoryId){
			Yii::app()->end(json_encode(array('data'=>array(),'delay'=>400)));
		}
		$treeDataSource = array('data'=>array(),'delay'=>400);
		$produts=  $this->getProducts($categoryId);
		//var_dump($produts);exit;
		foreach($produts as $c){
			$tmp['name'] = $c['product_name'];
			$tmp['id'] = $c['phs_code'];
			$treeDataSource['data'][] = $tmp;
		}
		// var_dump($treeDataSource);exit();
		Yii::app()->end(json_encode($treeDataSource));
	}
}
