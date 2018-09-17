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
		
		$db = Yii::app()->db;
		$sql = 'select t.*,t1.category_name from nb_product t,nb_product_category t1 where t.category_id=t1.lid and t.dpid=t1.dpid and t.dpid='.$this->companyId.' and t.delete_flag=0';
		if($categoryId){
			$sql .= ' and t.category_id = '.$categoryId;
		}
		$models = $db->createCommand($sql)->queryAll();
		
		$sql = 'select t.lid,t.product_id,t1.name from nb_product_printerway t,nb_printer_way t1 where t.printer_way_id=t1.lid and t.dpid=t1.dpid and t.delete_flag=0';
		$proprintways = $db->createCommand($sql)->queryAll();
		foreach ($models as $key=>$model){
			if(!isset($models[$key]['printerway'])){
				$models[$key]['printerway'] = array();
			}
			foreach ($proprintways as $proprintway){
				if($model['lid']==$proprintway['product_id']){
					array_push($models[$key]['printerway'],$proprintway);
				}
			}
		}
		$printerWays = PrinterWay::getPrinterWay($this->companyId);
		$categories = $this->getCategories();
		
		$this->render('index',array(
				'models'=>$models,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
				'printerWays'=>$printerWays,
		));
	}

	public function actionStorProduct(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$is_sync = DataSync::getInitSync();
		$opids = Yii::app()->request->getParam('pids');
		$oprintids = Yii::app()->request->getParam('printids');
		$pids = array();
		$pids = explode(',',$opids);
		$printids = array();
		$printids = explode(',',$oprintids);
		
		//****查询公司的产品分类。。。****
		$db = Yii::app()->db;
		
        if((!empty($pids))&&(!empty($printids))&&(Yii::app()->user->role <= User::SHOPKEEPER)){
        	
	        	foreach ($pids as $pid){
	        		$sql = 'select lid from nb_product where lid='.$pid.' and dpid='.$this->companyId.' and delete_flag=0';
	        		$product = $db->createCommand($sql)->queryRow();
	        		if($product){
	        			$sql = 'update nb_product_printerway set delete_flag=1 where product_id ='.$pid.' and dpid ='.$this->companyId;
	        			$result = $db->createCommand($sql)->execute();
	        			foreach ($printids as $printid){
	        				
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