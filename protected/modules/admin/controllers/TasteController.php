<?php
class TasteController extends BackendController
{
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex() {
		$type = Yii::app()->request->getParam('type',0);
		$criteria = new CDbCriteria;
		$criteria->addCondition('dpid=:dpid and allflae=:type and delete_flag=0');
		$criteria->order = ' lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		$criteria->params[':type']=$type; 
		
		$pages = new CPagination(TasteGroup::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = TasteGroup::model()->findAll($criteria);
		
		$this->render('index',array(
				'models'=>$models,
				'pages' => $pages,
				'type'=>$type
		));
	}
	public function actionCreate() {
		$type = Yii::app()->request->getParam('type',0);
		$model = new TasteGroup ;
		$model->dpid = $this->companyId ;
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('TasteGroup');
                        $se=new Sequence("taste_group");
                        $lid = $se->nextval();

                        $code=new Sequence("phs_code");
                        $tghs_code = $code->nextval();
                        
                        $model->lid = $lid;
                        $model->allflae = $type;
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->update_at = date('Y-m-d H:i:s',time());
                        $model->delete_flag = '0';

                        $model->tghs_code = ProductCategory::getChscode($this->companyId, $lid, $tghs_code);
//                        var_dump($model);exit;
			if($model->save()) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('taste/index' , 'companyId' => $this->companyId,'type'=>$type));
			}
		}
		$this->render('create' , array(
				'model' => $model , 
				'type' => $type
		));
	}
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$type = Yii::app()->request->getParam('type');
        //Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		$model = TasteGroup::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=>  $this->companyId));
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('TasteGroup');
                        $model->update_at = date('Y-m-d H:i:s',time());
                        
			if($model->save()){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('taste/index' , 'type'=>$type, 'companyId' => $this->companyId));
			}
		}
		$this->render('update' , array(
			'model'=>$model,
			'type' => $type
		));
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('lid');
		$type = Yii::app()->request->getParam('type',0);
        //Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			foreach ($ids as $id) {
				$model = TasteGroup::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $companyId)) ;
				if($model) {
					$model->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
				}
			}
			$this->redirect(array('taste/index' , 'companyId' => $companyId,'type'=>$type)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('taste/index' , 'companyId' => $companyId,'type'=>$type)) ;
		}
	}
        public function actionDetailIndex() {
		$groupid = Yii::app()->request->getParam('groupid',0);
                $groupname = Yii::app()->request->getParam('groupname',0);
                $type = Yii::app()->request->getParam('type',0);
		$criteria = new CDbCriteria;
		$criteria->addCondition('dpid=:dpid and taste_group_id=:groupid and delete_flag=0');
		$criteria->order = ' lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		$criteria->params[':groupid']=$groupid; 
		
		$pages = new CPagination(Taste::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = Taste::model()->findAll($criteria);
		
		$this->render('detailIndex',array(
				'models'=>$models,
				'pages' => $pages,
                                'groupid'=>$groupid,
                                'groupname'=>$groupname,
				'type'=>$type
		));
	}
	public function actionDetailCreate() {
		$groupid = Yii::app()->request->getParam('groupid',0);
                $groupname = Yii::app()->request->getParam('groupname',0);
                $type = Yii::app()->request->getParam('type','0');
		$model = new Taste();
		$model->dpid = $this->companyId ;		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Taste');
			
			if($model->is_selected){
				$sql ='update nb_taste set is_selected =0 where dpid='.$this->companyId.' and taste_group_id ='.$groupid.' and delete_flag=0';
				Yii::app()->db->createCommand($sql)->execute();
			}
                        $se=new Sequence("taste");
                        $model->taste_group_id = $groupid ;
                        $model->allflae = $type;
                        //$model->lid = $se->nextval();                        
                        //$model->create_at = date('Y-m-d H:i:s',time());
                        $model->delete_flag = '0';
                        $data = array(
					 				'lid'=>substr("0000000000".$se->nextval(),-10),//$model->lid,
					 				'dpid'=>$model->dpid,
					 				'create_at'=>date('Y-m-d H:i:s',time()),
                                    'update_at'=>date('Y-m-d H:i:s',time()),
					 				'taste_group_id'=>$groupid,
					 				'allflae'=>$type,
                        			//'other_price'=>$model->other_price,
                        			'price'=>$model->price,
                                    'name'=>$model->name,
                        			'is_selected'=>$model->is_selected,
					 				'delete_flag'=>'0'
					);
                        //var_dump($data);exit;
                        if(Yii::app()->db->createCommand()->insert('nb_taste',$data))
                        {
//                        var_dump($model);exit;
//			if($model->save()) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('taste/detailIndex' , 'companyId' => $this->companyId,'groupname'=>$groupname,'groupid'=>$groupid,'type'=>$type));
			}
		}
		$this->render('detailCreate' , array(
				'model' => $model , 
                                'groupid'=>$groupid,
                                'groupname'=>$groupname,
				'type' => $type
		));
	}
	public function actionDetailUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$type = Yii::app()->request->getParam('type');
                $groupname = Yii::app()->request->getParam('groupname',0);
        //Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		$model = Taste::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=>  $this->companyId));
		
		if(Yii::app()->request->isPostRequest) {
			//$otherprice = $_POST['other_price'];
			$model->attributes = Yii::app()->request->getPost('Taste');
			if($model->is_selected){
				$sql ='update nb_taste set is_selected =0 where dpid='.$this->companyId.' and taste_group_id ='.$model->taste_group_id.' and delete_flag=0';
				Yii::app()->db->createCommand($sql)->execute();
			}
			//$model->other_price = $otherprice;
                        $model->update_at=date('Y-m-d H:i:s',time());
                        //var_dump($model);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('taste/detailIndex' , 'type'=>$type,'groupname'=>$groupname,'groupid'=>$model->taste_group_id, 'companyId' => $this->companyId));
			}
		}
		$this->render('detailUpdate' , array(
			'model'=>$model,
                        'groupid'=>$model->taste_group_id,
                        'groupname'=>$groupname,
			'type' => $type
		));
	}
	public function actionDetailDelete(){
		//$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
                $groupid = Yii::app()->request->getParam('groupid',0);
                $groupname = Yii::app()->request->getParam('groupname',0);
		$ids = Yii::app()->request->getPost('lid');
		$type = Yii::app()->request->getParam('type',0);
        //Until::isUpdateValid($ids,$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			foreach ($ids as $id) {
				$model = Taste::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $this->companyId)) ;
				if($model) {
					$model->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
				}
			}
			$this->redirect(array('taste/detailIndex' , 'companyId' => $this->companyId,'groupname'=>$groupname,'groupid'=>$groupid,'type'=>$type)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('taste/detailIndex' , 'companyId' => $this->companyId,'groupname'=>$groupname,'groupid'=>$groupid,'type'=>$type)) ;
		}
	}
	public function actionProductTaste(){
        $categoryId = Yii::app()->request->getParam('cid',0);
        $type = Yii::app()->request->getParam('type',2);
		$criteria = new CDbCriteria;
		$criteria->with = 'productTaste';
                if($categoryId!=0)
                {
                    $criteria->addCondition('t.dpid=:dpid and t.delete_flag=0 and t.category_id ='.$categoryId);
                }else{
                    $criteria->addCondition('t.dpid=:dpid and t.delete_flag=0');
                }
		$criteria->order = ' lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		$categories = $this->getCategories();
		$pages = new CPagination(Product::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = Product::model()->findAll($criteria);
//		var_dump($models[0]);exit;
		$this->render('productTaste',array(
				'models'=>$models,
                'categories'=>$categories,
                'categoryId'=>$categoryId,
				'pages' => $pages,
				'type' => $type
		));
	}
	public function actionUpdateProductTaste(){
		$tasteArr = array();
		$lid = Yii::app()->request->getParam('lid');
        //Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		$model = Product::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=>  $this->companyId));
		
		if(Yii::app()->request->isPostRequest) {
			$postData = Yii::app()->request->getPost('Taste');
			if(TasteClass::saveProductTaste($this->companyId,$lid,$postData)){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('taste/productTaste' , 'companyId' => $this->companyId));
			}
		}
		$tastes = TasteClass::getAllOrderTasteGroup($this->companyId,0);
		$productTastes = TasteClass::getProductTasteGroup($lid,  $this->companyId);
		
		foreach($productTastes as $taste){
			array_push($tasteArr,$taste['lid']);
		}
		$this->render('updateProductTaste' , array(
			'model'=>$model,
			'tastes'=>$tastes,
			'productTastes'=>$tasteArr,
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
}