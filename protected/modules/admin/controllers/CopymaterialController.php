<?php
class CopymaterialController extends BackendController
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
		$criteria->with = array('company','category');
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId;
		if($categoryId){
			$criteria->condition.=' and t.category_id = '.$categoryId;
		}
		
		//$pages = new CPagination(Product::model()->count($criteria));
		//	    $pages->setPageSize(1);
		//$pages->applyLimit($criteria);
		$models = ProductMaterial::model()->findAll($criteria);
		
		$db = Yii::app()->db;
		$sql = 'select t.dpid,t.company_name from nb_company t where t.delete_flag = 0 and t.comp_dpid = '.$this->companyId;
		$command = $db->createCommand($sql);
		$dpids = $command->queryAll();
		//var_dump($dpids);exit;
		$categories = $this->getCategories();
//                var_dump($categories);exit;
		$this->render('index',array(
				'models'=>$models,
				'dpids'=>$dpids,
				'categories'=>$categories,
				'categoryId'=>$categoryId
		));
	}
	public function actionSetMealList() {
		
	}
	public function actionCreate(){
		$model = new Product();
		$model->dpid = $this->companyId ;
		//$model->create_time = time();
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Product');
                        $se=new Sequence("product");
                        $model->lid = $se->nextval();
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->update_at = date('Y-m-d H:i:s',time());
                        $model->delete_flag = '0';
                        $py=new Pinyin();
                        $model->simple_code = $py->py($model->product_name);
                        //var_dump($model);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('product/index' , 'companyId' => $this->companyId ));
			}
		}
		$categories = $this->getCategoryList();
		//$departments = $this->getDepartments();
                //echo 'ss';exit;
		$this->render('create' , array(
			'model' => $model ,
			'categories' => $categories
		));
	}
	
	public function actionUpdate(){
		$id = Yii::app()->request->getParam('id');
		$model = Product::model()->find('lid=:productId and dpid=:dpid' , array(':productId' => $id,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		//Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Product');
                        $py=new Pinyin();
                        $model->simple_code = $py->py($model->product_name);
			$model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				$this->redirect(array('product/index' , 'companyId' => $this->companyId ));
			}
		}
		$categories = $this->getCategoryList();
		//$departments = $this->getDepartments();
		$this->render('update' , array(
				'model' => $model ,
				'categories' => $categories
		));
	}
	public function actionStorMaterial(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$is_sync = DataSync::getInitSync();
		//var_dump($companyId);exit;
		$db = Yii::app()->db;
		$ids = Yii::app()->request->getPost('ids');
		$mchscode = Yii::app()->request->getParam('mchscode');
		$mphscode = Yii::app()->request->getParam('mphscode');
		$mulhscode = Yii::app()->request->getParam('mulhscode');
		$mushscode = Yii::app()->request->getParam('mushscode');
		$dpid = Yii::app()->request->getParam('dpids');
		$mchscodes = array();
		$mchscodes = explode(',',$mchscode);
		$mphscodes = array();
		$mphscodes = explode(',',$mphscode);
		$mulhscodes = array();
		$mulhscodes = explode(',',$mulhscode);
		$mushscodes = array();
		$mushscodes = explode(',',$mushscode);
		$dpids = array();
		$dpids = explode(',',$dpid);
		//var_dump($ids,$mchscodes,$dpids,$mphscodes,$mulhscodes,$mushscodes);exit;
		
		//****查询公司的产品分类。。。****
		
		$sql = 'select t.* from nb_material_category t where t.delete_flag = 0 and t.pid = 0 and t.dpid = '.$this->companyId;
		$command = $db->createCommand($sql);
		$catep1 = $command->queryAll();
		
		$sql = 'select t.* from nb_material_category t where t.delete_flag = 0 and t.pid != 0 and t.dpid = '.$this->companyId;
		$command = $db->createCommand($sql);
		$catep2 = $command->queryAll();
		
		$sql = 'select t.* from nb_material_unit t where t.delete_flag = 0 and t.dpid = '.$this->companyId;
		$command = $db->createCommand($sql);
		$materialunit = $command->queryAll();
		
		$sql = 'select t.* from nb_material_unit_ratio t where t.delete_flag = 0 and t.dpid = '.$this->companyId;
		$command = $db->createCommand($sql);
		$materialunitratio = $command->queryAll();
		
		$sql = 'select t.* from nb_product_material t where t.delete_flag = 0 and t.dpid = '.$this->companyId;
		$command = $db->createCommand($sql);
		$materials = $command->queryAll();
		
		//var_dump($materialunitratio);exit;
        //Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
        if((!empty($dpids))&&(Yii::app()->user->role < User::SHOPKEEPER)){
        	foreach ($dpids as $dpid){
        		if(!empty($catep1)){
        			foreach($catep1 as $category){
        				
        				if($category['mchs_code']){
        					//var_dump($category);
	        				$catep = MaterialCategory::model()->find('mchs_code=:mccode and dpid=:companyId and delete_flag =0' , array(':mccode'=>$category['mchs_code'],':companyId'=>$dpid));
	        				if($catep){
	//         					Yii::app()->user->setFlash('success' ,yii::t('app', '菜单下发成功'));
	// 	                        $this->redirect(array('copyproduct/index' , 'companyId' => $this->companyId));
	        				}else{//var_dump($catep);exit;
	        					
		                        $semc = new Sequence("material_category");
		                        $id = $semc->nextval();
		                        $data = array(
		                        		'lid'=>$id,
		                        		'dpid'=>$dpid,
		                        		'create_at'=>date('Y-m-d H:i:s',time()),
		                        		'update_at'=>date('Y-m-d H:i:s',time()),
		                        		'category_name'=>$category['category_name'],
		                        		'pid'=>"0",
		                        		//'type'=>$category['type'],
		                        		'mchs_code'=> $category['mchs_code'],
		                        		'main_picture'=>$category['main_picture'],
		                        		'order_num'=>$category['order_num'],
		                        		'delete_flag'=>'0',
		                        		'is_sync'=>$is_sync,
		                        );
		                        //var_dump($data,'fenlei');
		                        $command = $db->createCommand()->insert('nb_material_category',$data);
		                      
		                        	//var_dump(mysql_query($command));exit;
		                        	//var_dump($model);exit;
	 	                        	$self = MaterialCategory::model()->find('lid=:pid and dpid=:dpid and delete_flag=0' , array(':pid'=>$id,':dpid'=>$dpid ));
	 	                        	//var_dump($self);exit;
		                        	if($self->pid!='0'){
		                        		$parent = MaterialCategory::model()->find('lid=:pid and dpid=:dpid and delete_flag=0' , array(':pid'=>$model->pid,':dpid'=> $dpid));
		                        		$self->tree = $parent->tree.','.$self->lid;
		                        	} else {
		                        		$self->tree = '0,'.$self->lid;
		                        	}
		                        	$self->update();
		                        	//var_dump($self);
	        				}
        				}
        			}
        			if($catep2){
        				foreach ($catep2 as $category){
        					if($category['mchs_code']){
	        					$sql = 'select t.mchs_code,t.tree from nb_material_category t where t.delete_flag =0 and t.lid='.$category['pid'].' and t.dpid='.$this->companyId;
	        					$command = $db->createCommand($sql);
	        					$sqltree = $command->queryRow();
	        					$mchscode = $sqltree['mchs_code'];
	        					//$chscodetree = $sqltree['tree'];
	        					$catep = MaterialCategory::model()->find('mchs_code=:ccode and dpid=:companyId and delete_flag=0' , array(':ccode'=>$category['mchs_code'],':companyId'=>$dpid));
	        					$cateptree = MaterialCategory::model()->find('mchs_code=:ccode and dpid=:companyId and delete_flag=0' , array(':ccode'=>$mchscode,':companyId'=>$dpid));
	        					
	        					
	        					//var_dump($cateptree,$sqltree);exit;
	        					if($catep){
	        						//         					Yii::app()->user->setFlash('success' ,yii::t('app', '菜单下发成功'));
	        						// 	                        $this->redirect(array('copyproduct/index' , 'companyId' => $this->companyId));
	        					}else{//var_dump($catep);exit;
	        				
	        						$se = new Sequence("material_category");
	        						$id = $se->nextval();
	        						$datacate = array(
	        								'lid'=>$id,
	        								'dpid'=>$dpid,
	        								'create_at'=>date('Y-m-d H:i:s',time()),
	        								'update_at'=>date('Y-m-d H:i:s',time()),
	        								'category_name'=>$category['category_name'],
	        								'pid'=>$cateptree['lid'],
	        								//'type'=>$category['type'],
	        								'mchs_code'=> $category['mchs_code'],
	        								'main_picture'=>$category['main_picture'],
	        								'order_num'=>$category['order_num'],
	        								'delete_flag'=>'0',
	        								'is_sync'=>$is_sync,
	        						);
	        						$command = $db->createCommand()->insert('nb_material_category',$datacate);
	        				
	        						//var_dump(mysql_query($command));exit;
	        						//var_dump($datacate);
	        						$self = MaterialCategory::model()->find('lid=:pid and dpid=:dpid and delete_flag=0' , array(':pid'=>$id,':dpid'=>$dpid ));
	        						//var_dump($self);exit;
	        						if($self->pid!='0'){
	        							//$parent = ProductCategory::model()->find('lid=:pid and dpid=:dpid' , array(':pid'=>$model->pid,':dpid'=> $dpid));
	        							$self->tree = $cateptree['tree'].','.$self->lid;
	        						} else {
	        							$self->tree = '0,'.$self->lid;
	        						}
	        						$self->update();
	        						
	        				
	        					}
        					}
        				}
        			}
        			
        		}
        		
        		if(!empty($materialunit)){
        			foreach($materialunit as $materialunits){
        				if($materialunits['muhs_code']){
	        				$unit = MaterialUnit::model()->find('muhs_code=:mucode and dpid=:companyId and delete_flag =0' , array(':mucode'=>$materialunits['muhs_code'],':companyId'=>$dpid));
	        				if($unit){
	        					//         					Yii::app()->user->setFlash('success' ,yii::t('app', '菜单下发成功'));
	        					// 	                        $this->redirect(array('copyproduct/index' , 'companyId' => $this->companyId));
	        				}else{//var_dump($catep);exit;
	        					 
	        					$se = new Sequence("material_unit");
	        					$id = $se->nextval();
	        					$data = array(
	        							'lid'=>$id,
	        							'dpid'=>$dpid,
	        							'create_at'=>date('Y-m-d H:i:s',time()),
	        							'update_at'=>date('Y-m-d H:i:s',time()),
	        							'unit_name'=>$materialunits['unit_name'],
	        							'unit_type'=>$materialunits['unit_type'],
	        							//'type'=>$category['type'],
	        							'sort_code'=> $materialunits['sort_code'],
	        							'muhs_code'=> $materialunits['muhs_code'],
	        							'unit_specifications'=>$materialunits['unit_specifications'],
	        							'delete_flag'=>'0',
	        							'is_sync'=>$is_sync,
	        					);
	        					$command = $db->createCommand()->insert('nb_material_unit',$data);
	        					//var_dump($data,'danwei');
	        				}
	        			}
        			}
        			
        		}

        		if(!empty($materialunitratio)){
        			foreach($materialunitratio as $materialunitratios){
        				if($materialunitratios['mulhs_code']&&$materialunitratios['mushs_code']){
	        				$unit = MaterialUnitRatio::model()->find('mulhs_code=:mulcode and mushs_code=:muscode and dpid=:companyId and delete_flag =0' , array(':mulcode'=>$materialunitratios['mulhs_code'],':muscode'=>$materialunitratios['mushs_code'],':companyId'=>$dpid));
	        				if($unit){
	        					$unit->update_at = date('Y-m-d H:i:s',time());
	        					$unit->unit_ratio = $materialunitratios['unit_ratio'];
	        					$unit->mulhs_code = $materialunitratios['mulhs_code'];
	        					$unit->mushs_code = $materialunitratios['mushs_code'];
	        					$unit->unit_code = $materialunitratios['unit_code'];
	        					$unit->stock_unit_id = MaterialUnit::getMaterialUnitLid($dpid,$materialunitratios['mulhs_code']);
	        					$unit->sales_unit_id = MaterialUnit::getMaterialUnitLid($dpid,$materialunitratios['mushs_code']);
	        					$res = $unit->update();
	        				}else{//var_dump($catep);exit;
	        					$se = new Sequence("material_unit_ratio");
	        					$id = $se->nextval();
	        					$data = array(
	        							'lid'=>$id,
	        							'dpid'=>$dpid,
	        							'create_at'=>date('Y-m-d H:i:s',time()),
	        							'update_at'=>date('Y-m-d H:i:s',time()),
	        							'stock_unit_id'=>MaterialUnit::getMaterialUnitLid($dpid,$materialunitratios['mulhs_code']),
	        							'sales_unit_id'=>MaterialUnit::getMaterialUnitLid($dpid,$materialunitratios['mushs_code']),
	        							'unit_ratio'=>$materialunitratios['unit_ratio'],
	        							//'type'=>$category['type'],
	        							'mulhs_code'=> $materialunitratios['mulhs_code'],
	        							'mushs_code'=> $materialunitratios['mushs_code'],
	        							'unit_code'=>$materialunitratios['unit_code'],
	        							'delete_flag'=>'0',
	        							'is_sync'=>$is_sync,
	        					);
	        					$command = $db->createCommand()->insert('nb_material_unit_ratio',$data);
	        					// var_dump($data,'bili');
	        				}
	        			}
        			}
        			 
        		}
        		if($materials){
        			foreach ($mphscodes as $prodhscode){
        				$materialo = ProductMaterial::model()->find('mphs_code=:pcode and dpid=:companyId and delete_flag=0' , array(':pcode'=>$prodhscode,':companyId'=>$dpid));
        				$material =  ProductMaterial::model()->find('mphs_code=:pcode and dpid=:companyId and delete_flag=0' , array(':pcode'=>$prodhscode,':companyId'=>$this->companyId));
        				$categoryId = MaterialCategory::model()->find('mchs_code=:ccode and dpid=:companyId and delete_flag=0' , array(':ccode'=>$material['mchs_code'],':companyId'=>$dpid));
        				//var_dump($product,$producto,$categoryId);exit;
        				//var_dump(!empty($producto));exit;
        				if((!empty($material))&&(empty($materialo))&&(!empty($categoryId))){
        					//var_dump($catep);exit;
        						
        					$sem = new Sequence("product_material");
        					$id = $sem->nextval();
        					$datamaterial = array(
        							'lid'=>$id,
        							'dpid'=>$dpid,
        							'create_at'=>date('Y-m-d H:i:s',time()),
        							'update_at'=>date('Y-m-d H:i:s',time()),
        							'category_id'=>$categoryId['lid'],
        							'material_name'=>$material['material_name'],
        							'material_identifier'=>$material['material_identifier'],
        							'material_private_identifier'=>$material['material_private_identifier'],
        							'stock_unit_id'=>MaterialUnit::getMaterialUnitLid($dpid,$material['mulhs_code']),
        							'sales_unit_id'=>MaterialUnit::getMaterialUnitLid($dpid,$material['mushs_code']),
        							'mchs_code'=>$material['mchs_code'],
        							'mphs_code'=>$material['mphs_code'],
        							'mulhs_code'=>$material['mulhs_code'],
        							'mushs_code'=>$material['mushs_code'],
        							'delete_flag'=>'0',
        							'is_sync'=>$is_sync,
        					);
        					
        					$sepms = new Sequence("product_material_stock");
        					$pmsid = $sepms->nextval();
        					$pmsdata = array(
        							'lid' => $pmsid,
        							'create_at' => date('Y-m-d H:i:s',time()),
        							'update_at' => date('Y-m-d H:i:s',time()),
        							'material_id' => $id,
        							'mphs_code' => $material['mphs_code'],
        							'delete_flag'=>'0',
        							'is_sync'=>$is_sync,
        					);
        					$command = $db->createCommand()->insert('nb_product_material',$datamaterial);
        					$command = $db->createCommand()->insert('nb_product_material_stock',$pmsdata);
        					//var_dump($dataprod);exit;
        					
        					
        				}elseif((!empty($material))&&(!empty($materialo))&&(!empty($categoryId))){
        					$materialo->update_at = date('Y-m-d H:i:s',time());
        					$materialo->category_id = $categoryId['lid'];
        					$materialo->material_name = $material['material_name'];
        					$materialo->material_identifier = $material['material_identifier'];
        					$materialo->material_private_identifier = $material['material_private_identifier'];
        					$materialo->stock_unit_id = MaterialUnit::getMaterialUnitLid($dpid,$material['mulhs_code']);
        					$materialo->sales_unit_id = MaterialUnit::getMaterialUnitLid($dpid,$material['mushs_code']);
        					$materialo->mchs_code = $material['mchs_code'];
        					$materialo->mphs_code = $material['mphs_code'];
        					$materialo->mulhs_code = $material['mulhs_code'];
        					$materialo->mushs_code = $material['mushs_code'];
        					$materialo->save();
        				}
        			}
        		}
        	}
        	Yii::app()->user->setFlash('success' , yii::t('app','菜品下发成功！！！'));
        	$this->redirect(array('copymaterial/index' , 'companyId' => $companyId)) ;
        	
        }else{
        	Yii::app()->user->setFlash('error' , yii::t('app','无权限进行此项操作！！！'));
        	$this->redirect(array('copymaterial/index' , 'companyId' => $companyId)) ;
        }        
        
	}
	public function actionStatus(){
		$id = Yii::app()->request->getParam('id');
		$product = Product::model()->find('lid=:id and dpid=:companyId' , array(':id'=>$id,':companyId'=>$this->companyId));
		//var_dump($product->status);
		if($product){
			$product->saveAttributes(array('status'=>$product->status?0:1,'update_at'=>date('Y-m-d H:i:s',time())));
		}
		exit;
	}
	public function actionRecommend(){
		$id = Yii::app()->request->getParam('id');
		$product = Product::model()->find('lid=:id and dpid=:companyId' , array(':id'=>$id,':companyId'=>$this->companyId));
		
		if($product){
			$product->saveAttributes(array('recommend'=>$product->recommend==0?1:0,'update_at'=>date('Y-m-d H:i:s',time())));
		}
		exit;
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
		$categories = Helper::getCategories($this->companyId,$pid);
	
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
		$criteria->order = ' tree,t.lid asc ';
		
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
	private function getDepartments(){
		$departments = Department::model()->findAll('company_id=:companyId',array(':companyId'=>$this->companyId)) ;
		return CHtml::listData($departments, 'department_id', 'name');
	}
	
}