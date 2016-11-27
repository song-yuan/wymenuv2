<?php
class ProductMaterialController extends BackendController
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
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex(){
		$categoryId = Yii::app()->request->getParam('cid',0);
		$criteria = new CDbCriteria;
		$criteria->with = array('category');
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId;
		if($categoryId){
			$criteria->condition.=' and t.category_id = '.$categoryId;
		}
	//	$criteria->condition.=' and t.lid = '.$categoryId;
		$pages = new CPagination(ProductMaterial::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = ProductMaterial::model()->findAll($criteria);
		$categories = $this->getCategories();
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
				'categories'=>$categories,
				'categoryId'=>$categoryId

		));
	}
	public function actionCreate(){
		$model = new ProductMaterial();
		$modelStock = new ProductMaterialStock();
		$model->dpid = $this->companyId ;
		$modelStock->dpid = $this->companyId ;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductMaterial');
			
			$productMaterial = ProductMaterial::model()->find('dpid=:dpid and material_name=:name and delete_flag=0' , array(':dpid'=>  $this->companyId,':name'=>$model->material_name));
			//var_dump($category);var_dump('####');
			if($productMaterial){
				//var_dump($productMaterial);exit;
				Yii::app()->user->setFlash('error' ,yii::t('app', '该原料已添加'));
			}else{
				if($model->category_id&&$model->stock_unit_id&&$model->sales_unit_id&&$model->material_identifier){
			
					$db = Yii::app()->db;
					$sql = 'select t.* from nb_material_category t where t.delete_flag = 0 and t.lid = '.$model->category_id;
					$command1 = $db->createCommand($sql);
					$categoryCode = $command1->queryRow()['mchs_code'];
					
					$sql = 'select t.* from nb_material_unit t where t.delete_flag = 0 and t.lid = '.$model->stock_unit_id;
					$command2 = $db->createCommand($sql);
					$stockUnitId = $command2->queryRow()['muhs_code'];
					
					$sql = 'select t.* from nb_material_unit t where t.delete_flag = 0 and t.lid = '.$model->sales_unit_id;
					$command3 = $db->createCommand($sql);
					$salesUnitId = $command3->queryRow()['muhs_code'];
					//var_dump($categoryCode,$stockUnitId,$salesUnitId);exit;
					if($categoryCode&&$stockUnitId&&$salesUnitId){
			            $se=new Sequence("product_material");
			            $lid = $se->nextval();
			            $model->lid = $lid;
			            
			            $code = new Sequence('mphs_code');
			            $mphs_code = $code->nextval();
			            $model->create_at = date('Y-m-d H:i:s',time());
			            $model->update_at = date('Y-m-d H:i:s',time());
			            $model->mphs_code = ProductCategory::getChscode($this->companyId, $lid, $mphs_code);
			            $model->mchs_code = $categoryCode;
			            $model->mulhs_code = $stockUnitId;
			            $model->mushs_code = $salesUnitId;
			            $model->delete_flag = '0';
			            
			            $se=new Sequence("product_material_stock");
			            $modelStock->lid = $se->nextval();
			            $modelStock->create_at = date('Y-m-d H:i:s',time());
			            $modelStock->update_at = date('Y-m-d H:i:s',time());
			            $modelStock->material_id = $model->lid;
			            $modelStock->mphs_code = $model->mphs_code;
			            //var_dump($model);exit;
						if($model->save()&&$modelStock->save()){
							Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
							$this->redirect(array('productMaterial/index' , 'companyId' => $this->companyId ));
						}
					}
					else{
						$msg = '';
						if(empty($categoryCode)){
							$msg = $msg.'该类别编码信息有误，请删除该类别再重新添加！';
						}
						if(empty($stockUnitId)){
							$msg = $msg.';'.'该库存单位信息有误，请删除该库存单位再重新添加！';
						}
						if(empty($salesUnitId)){
							$msg = $msg.';'.'该零售单位信息有误，请删除该零售单位再重新添加！';
						}
						Yii::app()->user->setFlash('error',yii::t('app',$msg));
						
					}
				}
				else{
					Yii::app()->user->setFlash('error',yii::t('app','请完善信息！'));
				}
			}
		}
		$categories = $this->getCategoryList();
 		$this->render('create' , array(
			'model' => $model ,
			'categories' => $categories
		));
	}
	
	public function actionUpdate(){
		$id = Yii::app()->request->getParam('id');
		$model = ProductMaterial::model()->find('lid=:materialId and dpid=:dpid' , array(':materialId' => $id,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		//Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductMaterial');
			if($model->category_id){
				$categoryId = MaterialCategory::model()->find('lid=:lid and dpid=:companyId and delete_flag=0' , array(':lid'=>$model->category_id,':companyId'=>$this->companyId));
				$model->mchs_code = $categoryId['mchs_code'];
			}
			if($model->stock_unit_id){
				$unitId = MaterialUnit::model()->find('lid=:lid and dpid=:companyId and delete_flag=0' , array(':lid'=>$model->stock_unit_id,':companyId'=>$this->companyId));
				$model->mulhs_code = $unitId['muhs_code'];
			}
			if($model->sales_unit_id){
				$unitId = MaterialUnit::model()->find('lid=:lid and dpid=:companyId and delete_flag=0' , array(':lid'=>$model->sales_unit_id,':companyId'=>$this->companyId));
				$model->mushs_code = $unitId['muhs_code'];
			}
         	$model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				$this->redirect(array('productMaterial/index' , 'companyId' => $this->companyId ));
			}
		}
		$categories = $this->getCategoryList();//var_dump($categories);exit;
		$this->render('update' , array(
				'model' => $model ,
				'categories' => $categories
		));
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
                Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_product_material set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('productMaterial/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('productMaterial/index' , 'companyId' => $companyId)) ;
		}
	}
	
	public function actionDetailindex(){
		$materialId = Yii::app()->request->getParam('id',0);
		$criteria = new CDbCriteria;
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId.' and t.material_id ='.$materialId;
		//	$criteria->condition.=' and t.lid = '.$categoryId;
		$pages = new CPagination(ProductMaterialStock::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = ProductMaterialStock::model()->findAll($criteria);
		$this->render('detailindex',array(
				'models'=>$models,
				'pages'=>$pages,
	
		));
	}
	
	
	
	private function getCategoryList(){
		$categories = MaterialCategory::model()->findAll('delete_flag=0 and dpid=:companyId' , array(':companyId' => $this->companyId)) ;
		//var_dump($categories);exit;
		return CHtml::listData($categories, 'lid', 'category_name');
	}
	public function actionGetChildren(){
		$pid = Yii::app()->request->getParam('pid',0);
		if(!$pid){
			Yii::app()->end(json_encode(array('data'=>array(),'delay'=>400)));
		}
		$treeDataSource = array('data'=>array(),'delay'=>400);
		$categories = Helper::getCategory($this->companyId,$pid);

		foreach($categories as $c){
			$tmp['name'] = $c['category_name'];
			$tmp['id'] = $c['lid'];
			$treeDataSource['data'][] = $tmp;
		}
		Yii::app()->end(json_encode($treeDataSource));
	}
	private function getCategories(){
		$criteria = new CDbCriteria;
		$criteria->with = 'company';
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId ;
		$criteria->order = ' t.lid asc ';

		$models = MaterialCategory::model()->findAll($criteria);

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
			$model = MaterialCategory::model()->find('t.lid = :lid and dpid=:dpid',array(':lid'=>$k,':dpid'=>  $this->companyId));
			$optionsReturn[$model->category_name] = $v;
		}
		return $optionsReturn;
	}

}









