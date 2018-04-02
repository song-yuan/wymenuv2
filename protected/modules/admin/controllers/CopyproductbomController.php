<?php
class CopyproductbomController extends BackendController
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
		$models = Product::model()->findAll($criteria);
		
		$db = Yii::app()->db;
		$sql = 'select t.dpid,t.type,t.company_name,t1.is_rest from nb_company t left join nb_company_property t1 on(t1.dpid = t.dpid) where t.delete_flag = 0 and t.type = 1 and t.comp_dpid = '.$this->companyId.' group by t.dpid';
		$command = $db->createCommand($sql);
		$dpids = $command->queryAll();
		$categories = $this->getCategories();
		$this->render('index',array(
				'models'=>$models,
				'dpids'=>$dpids,
				'categories'=>$categories,
				'categoryId'=>$categoryId
		));
	}

	public function actionStorProduct(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$is_sync = DataSync::getInitSync();
		//var_dump($companyId);exit;
		$ids = Yii::app()->request->getPost('ids');
		$chscode = Yii::app()->request->getParam('chscode');
		$phscode = Yii::app()->request->getParam('phscode');
		$dpid = Yii::app()->request->getParam('dpids');
		$ctp = Yii::app()->request->getParam('ctp');
		$chscodes = array();
		$chscodes = explode(',',$chscode);
		$phscodes = array();
		$phscodes = explode(',',$phscode);
		$dpids = array();
		$dpids = explode(',',$dpid);
		$msgnull = '下列产品暂无配方，请添加后再进行下发操作：';
		$msgprod = '下列产品尚未下发至选择店铺，请先下发产品再下发配方：';
		$msgmate = '下列原料尚未下发至选择店铺，请先下发原料再下发配方：';
		//****查询公司的产品分类。。。****
		
		$db = Yii::app()->db;
		$sql = 'select t.* from nb_product t where t.delete_flag = 0 and t.dpid = '.$this->companyId;
		$command = $db->createCommand($sql);
		$products = $command->queryAll();
		//var_dump($catep1,$catep2,$products);exit;
        //Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
        if((!empty($dpids))&&(Yii::app()->user->role < User::SHOPKEEPER)){
        	foreach ($dpids as $dpid){
        			foreach ($phscodes as $prodhscode){
        				$prods = Product::model()->find('phs_code=:pcode and dpid=:companyId and delete_flag=0' , array(':pcode'=>$prodhscode,':companyId'=>$this->companyId));
        				$producto = ProductBom::model()->find('phs_code=:pcode and dpid=:companyId and delete_flag=0' , array(':pcode'=>$prodhscode,':companyId'=>$dpid));
        				$product =  ProductBom::model()->findAll('phs_code=:pcode and dpid=:companyId and delete_flag=0' , array(':pcode'=>$prodhscode,':companyId'=>$this->companyId));
        				if(!empty($product)){
	        				if((!empty($producto))&& ($ctp ==1)){
	        					$sql = 'delete from nb_product_bom where phs_code ='.$prodhscode.' and dpid ='.$dpid;
	        					$command=$db->createCommand($sql);
								$command->execute();
	        				}else{
	        					$sql = 'delete from nb_product_bom where source=1 and dpid ='.$dpid;
	        					$command=$db->createCommand($sql);
	        					$command->execute();
	        				}
	        				foreach ($product as $prod){
	        					$prodid = Product::model()->find('phs_code=:pcode and dpid=:companyId and delete_flag=0' , array(':pcode'=>$prodhscode,':companyId'=>$dpid));
	        					$mateid = ProductMaterial::model()->find('mphs_code=:mpcode and mushs_code =:muscode and dpid=:companyId and delete_flag=0' , array(':mpcode'=>$prod['mphs_code'],':muscode'=>$prod['mushs_code'],':companyId'=>$dpid));
	        					if(!empty($prodid)&&!empty($mateid)){
		        					$se = new Sequence("product_bom");
		        					$id = $se->nextval();
		        					$dataprodbom = array(
		        							'lid'=>$id,
		        							'dpid'=>$dpid,
		        							'create_at'=>date('Y-m-d H:i:s',time()),
		        							'update_at'=>date('Y-m-d H:i:s',time()),
		        							'product_id'=>$prodid['lid'],
		        							'material_id'=>$mateid['lid'],
		        							'number'=>$prod['number'],
		        							'sales_unit_id'=>$mateid['sales_unit_id'],
		        							'mphs_code'=>$mateid['mphs_code'],
		        							'phs_code'=>$prodid['phs_code'],
		        							'mushs_code'=>$mateid['mushs_code'],
		        							'source'=>1,
		        							'delete_flag'=>'0',
		        							'is_sync'=>$is_sync,
		        					);
		        					//var_dump($dataprod);exit;
		        					$command = $db->createCommand()->insert('nb_product_bom',$dataprodbom);
		        				
        						}else{
        							if(empty($prodid)){
        								$msgprod = $msgprod + $prodid['product_name']+';';
        							}
        							if(empty($mateid)){
        								$msgmate = $msgmate + $prodid['product_name']+';';
        							}
        						}
        				
        					}
        				}else{
        					$msgnull = $msgnull +$prods['product_name']+';';
        				}
        					
        			}
        	}
        	//Yii::app()->user->setFlash('success' , $msgmate);
        	Yii::app()->user->setFlash('success' , yii::t('app','配方下发成功！！！'));
        	$this->redirect(array('copyproductbom/index' , 'companyId' => $companyId)) ;
        	
        }else{
        	Yii::app()->user->setFlash('error' , yii::t('app','无权限进行此项操作！！！'));
        	$this->redirect(array('copyproductbom/index' , 'companyId' => $companyId)) ;
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
		$categories = ProductCategory::model()->findAll('delete_flag=0 and dpid=:companyId' , array(':companyId' => $this->companyId)) ;
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
	private function getDepartments(){
		$departments = Department::model()->findAll('company_id=:companyId',array(':companyId'=>$this->companyId)) ;
		return CHtml::listData($departments, 'department_id', 'name');
	}
	
}