<?php
class SentTotalConsumeController extends BackendController {
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
    	$brdulvs = $this->getBrdulv();
    	$is_new = Yii::app()->request->getParam('is_new','0');
    		
    	$is_sync = DataSync::getInitSync();
    	$models = '';
    	$selcups = array();
    	
    	$pid = '';
    	$phscode = '';
    	
    	if($is_new == '1'){
    		$model = new SentwxcardPromotion();
    		$model->dpid = $this->companyId ;
    		
    		$code=new Sequence("sole_code");
    		$sole_code = $code->nextval();
    			
    		$se=new Sequence("sentwxcard_promotion");
    		$lid = $se->nextval();
    		$model->lid = $lid;
    		$model->sole_code = ProductCategory::getChscode($this->companyId, $lid, $sole_code);
    		$model->create_at = date('Y-m-d H:i:s',time());
    		$model->update_at = date('Y-m-d H:i:s',time());
    		$model->delete_flag = '0';
    		$model->type = '7';
    		$model->is_sync = $is_sync;
    		
    		$pid = $model->lid;
    		$phscode = $model->sole_code;
    	}elseif($is_new =='2'){
    		Yii::app()->db->createCommand('update nb_sentwxcard_promotion set is_available ="0",is_sync ='.$is_sync.' where type = "7" and dpid = :companyId')
    		->execute(array( ':companyId' => $this->companyId));
    		$this->redirect(array('sentTotalConsume/index' , 'companyId' => $this->companyId)) ;
    	}elseif ($is_new =='3'){
    		Yii::app()->db->createCommand('update nb_sentwxcard_promotion set is_available ="1",is_sync ='.$is_sync.' where type = "7" and dpid = :companyId')
    		->execute(array( ':companyId' => $this->companyId));
    		$this->redirect(array('sentTotalConsume/index' , 'companyId' => $this->companyId)) ;
    	}else{

    		$criteria = new CDbCriteria;
    		$criteria->with = 'sentwxcarDet';
    		$criteria->select = 't.*';
    		$criteria->order = ' t.update_at desc';
    		$criteria->addCondition("t.dpid= ".$this->companyId);
    		$criteria->addCondition('t.delete_flag=0');
    		$criteria->addCondition('t.type=7');
    		
    		$model = SentwxcardPromotion::model()->find($criteria);
    		
    		if($model){
	    		$cris = new CDbCriteria;
	    		$cris->select = 't.*';
	    		$cris->with = 'cupon';
	    		$cris->order = ' t.update_at desc';
	    		$cris->addCondition("t.dpid= ".$this->companyId);
	    		$cris->addCondition('t.delete_flag=0');
	    		$cris->addCondition('t.sentwxcard_pro_id='.$model->lid);
	    		$models = SentwxcardPromotionDetail::model()->findAll($cris);
	    		
	    		foreach ($models as $m){
	    			Array_push($selcups,$m->wxcard_id);
	    		}
	    		$pid = $model->lid;
	    		$phscode = $model->sole_code;
    		}
    	}
    	$cups = new CDbCriteria;
    	$cups->select = 't.*';
    	$cups->order = ' t.update_at desc';
    	$cups->addCondition("t.dpid= ".$this->companyId);
    	$cups->addCondition('t.delete_flag=0');
    	$cupons = Cupon::model()->findAll($cups);
    	 
    	if(Yii::app()->request->isPostRequest) {
    		if(Yii::app()->user->role > User::SHOPKEEPER) {
    			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
    			$this->redirect(array('sentTotalConsume/index' , 'companyId' => $this->companyId)) ;
    		}
    		$model->attributes = Yii::app()->request->getPost('SentwxcardPromotion');
    		$falid = Yii::app()->request->getPost('falid');
    		$facode = Yii::app()->request->getPost('facode');
    		$plids = Yii::app()->request->getPost('newselcups');
    		$db = Yii::app()->db;
    			
    		$model->update_at=date('Y-m-d H:i:s',time());
    		$model->is_sync = $is_sync;
    		//var_dump($falid.'@@'.$facode.'##'.$plids);exit;
    		
    		
    		$dpid = $this->companyId;
    		$materialnums = array();
    		$materialnums = explode(';',$plids);
    		//var_dump($plids,$materialnums);exit;
    		$transaction = $db->beginTransaction();
    		try{
    			Yii::app()->db->createCommand('update nb_sentwxcard_promotion_detail set delete_flag ="1",is_sync ='.$is_sync.' where sentwxcard_pro_id =:lid and dpid = :companyId')
    			->execute(array(':lid' =>$falid ,':companyId' => $this->companyId));
    			//var_dump($materialnums);
    			foreach ($materialnums as $materialnum){
    				$materials = array();
    				$materials = explode(',',$materialnum);
    				$plid = $materials[0];
    				$pcode = $materials[1];
    				//var_dump($plid.'@'.$pcode);exit;
    				$cupons = Cupon::model()->find('lid=:lid and dpid=:companyId and delete_flag=0' , array(':lid'=>$plid,':companyId'=>$this->companyId));
    				$sentwxcardtprodetail = SentwxcardPromotionDetail::model()->find('sentwxcard_pro_id =:plid and wxcard_id =:prodid and dpid=:companyId', array('plid'=>$falid,':prodid'=>$plid, ':companyId'=>$this->companyId));
    				//var_dump($sentwxcardtprodetail);
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
    						
    				}elseif(!empty($cupons)&&!empty($plid)&&!empty($sentwxcardtprodetail)){
    					Yii::app()->db->createCommand('update nb_sentwxcard_promotion_detail set delete_flag ="0",is_sync ='.$is_sync.' where lid =:lid and dpid = :companyId')
    					->execute(array(':lid' =>$sentwxcardtprodetail->lid ,':companyId' => $this->companyId));
    				}
    		
    			}
    			if($model->save()){
    				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
    				$transaction->commit(); //提交事务会真正的执行数据库操作
    				$this->redirect(array('sentTotalConsume/index' , 'companyId' => $this->companyId));
    			}else{
    				$transaction->rollback();
    			}
    				
    		} catch (Exception $e) {
    			$transaction->rollback();
    			$this->redirect(array('sentTotalConsume/index' , 'companyId' => $this->companyId));
    		}
    		
    	}
    	$this->render('index',array(
    			'model'=>$model,
    			'models'=>$models,
    			'brdulvs'=>$brdulvs,
    			'selcups'=>$selcups,
    			'cupons'=>$cupons,
    			'pid' => $pid,
    			'phscode'=>$phscode,
    			'is_new'=>$is_new,
    	));
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

	

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cashcard-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

