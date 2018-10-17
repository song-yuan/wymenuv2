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
		$companyId = $this->companyId;
		$opids = Yii::app()->request->getParam('pids');// 产品Id
		$oprintids = Yii::app()->request->getParam('printids');
		
		$pids = explode(',',$opids);
		$printids = explode(',',$oprintids);// 打印方案
		
		$nowDate = date('Y-m-d H:i:s',time());
		//****查询公司的产品分类。。。****
		$db = Yii::app()->db;
		
        if((!empty($pids))&&(!empty($printids))&&(Yii::app()->user->role <= User::SHOPKEEPER)){
        	foreach ($printids as $printid){
        		$sql = 'select product_id from nb_product_printerway where product_id in('.$opids.') and printer_way_id='.$printid.' and dpid='.$this->companyId.' and delete_flag=0';
        		$propriIds = $db->createCommand($sql)->queryColumn();
        		$diffProArr = array_diff($pids, $propriIds);
        		
        		if(!empty($diffProArr)){
        			$sql = 'insert into nb_product_printerway (lid,dpid,create_at,update_at,printer_way_id,product_id) VALUES ';
        			foreach ($diffProArr as $pid){
        				$se=new Sequence("product_printerway");
        				$lid = $se->nextval();
        				$sql .= '('.$lid.','.$companyId.',"'.$nowDate.'","'.$nowDate.'",'.$printid.','.$pid.'),';
        			}
        			$sql = rtrim($sql,',');
        			$db->createCommand($sql)->execute();
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