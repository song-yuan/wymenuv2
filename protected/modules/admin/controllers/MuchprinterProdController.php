<?php
class MuchprinterProdController extends BackendController
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
		$criteria->with = array('company','category','productPrinterway');
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId;
		if($categoryId){
			$criteria->condition.=' and t.category_id = '.$categoryId;
		}
		$printerWays = PrinterWay::getPrinterWay($this->companyId);
		$models = Product::model()->findAll($criteria);
		
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
				'categoryId'=>$categoryId,
				'printerWays'=>$printerWays,
		));
	}

	public function actionStorProduct(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$is_sync = DataSync::getInitSync();
		//var_dump($companyId);exit;
		$opids = Yii::app()->request->getParam('pids');
		$oprintids = Yii::app()->request->getParam('printids');
		//$dpid = Yii::app()->request->getParam('dpids');
		//var_dump($pids);var_dump($prodids);var_dump($nums);exit;
		$pids = array();
		$pids = explode(',',$opids);
		$printids = array();
		$printids = explode(',',$oprintids);
		//var_dump($pids,$printids);exit;
		
		//****查询公司的产品分类。。。****
		$db = Yii::app()->db;
		
		//var_dump($catep1,$catep2,$products);exit;
         //       Until::isUpdateValid($pids,$companyId,$this);//0,表示企业任何时候都在云端更新。
        if((!empty($pids))&&(!empty($printids))&&(Yii::app()->user->role <= User::SHOPKEEPER)){
        	
	        	foreach ($pids as $pid){
	        		$product =  Product::model()->find('lid=:lid and dpid=:companyId and delete_flag=0' , array(':lid'=>$pid,':companyId'=>$this->companyId));
	        		//var_dump($pid);//exit;	
	        		if($product){
	        			$sql = 'update nb_product_printerway set delete_flag=1,update_at="'.date('Y-m-d H:i:s',time()).'" where product_id ='.$pid.' and dpid ='.$this->companyId;
	        			$result = $db->createCommand($sql)->execute();
	        			foreach ($printids as $printid){
	        				
	        				//$prodprinter = ProductPrinterway::model()->find(' dpid=:companyId and delete_flag=0 and product_id=:proid and printer_way_id=:printerId' , array(':proid'=>$pid,':printerId'=>$printid,':companyId'=>$this->companyId));
	        				//var_dump($prodprinter);
        				
        					$se=new Sequence("product_printerway");
        					$lid = $se->nextval();
        					$data = array(
        							'lid'=>$lid,
        							'dpid'=>$this->companyId,
        							'create_at'=>date('Y-m-d H:i:s',time()),
        							'update_at'=>date('Y-m-d H:i:s',time()),
        							'printer_way_id'=>$printid,
        							'product_id'=>$pid,
        							'delete_flag'=>"0",
        					);
        					Yii::app()->db->createCommand()->insert('nb_product_printerway',$data);
	        				
	        			}
	        		}
	        	}
        	
        	Yii::app()->user->setFlash('success' , yii::t('app','菜品批量修改成功！！！'));
        	$this->redirect(array('muchprinterProd/index' , 'companyId' => $companyId)) ;
        	
        }else{
        	Yii::app()->user->setFlash('error' , yii::t('app','无权限进行此项操作！！！'));
        	$this->redirect(array('muchprinterProd/index' , 'companyId' => $companyId)) ;
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