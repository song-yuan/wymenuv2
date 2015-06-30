<?php
class OrderManagementController extends BackendController
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
		$criteria = new CDbCriteria;
		$sql = 'select t1.name, t.* from nb_order t left join  nb_payment_method t1 on( t.payment_method_id = t1.lid and t.dpid = t1.dpid ) where t.update_at >=0 and t.dpid= '.$this->companyId;
		$connect = Yii::app()->db->createCommand($sql);
		$model = $connect->queryAll();
		$categoryId = Yii::app()->request->getParam('cid',0);
		
		//$criteria = new CDbCriteria;
		//$criteria->condition = 't.dpid='.$this->companyId ;
		$pages = new CPagination(count($model));
                $pages->PageSize = 10;
		$pages->applyLimit($criteria);
// 		$model = Yii::app()->db->creatCommand($sql."LIMIT:offset,:limit");
// 		$model->bindValue(':offset',$pages->currentPage*$pages->pageSize);
// 		$model->bindValue(':limit',$pages->pageSize);
// 		$model = $ret->queryAll();
		
		//$pages = new CPagination(Order::model()->count($criteria));
		//	    $pages->seetPageSize(1);
		//$pages->applyLimit($criteria);
		//$models = Order::model()->findAll($criteria);
		//$nicais = OrderManagementController::getProductname()->findAll($criteria);
		//$categories = $this->getCategories();
		//var_dump($models);exit;
        echo $this->getSiteName();
       
		 //      exit;
		$this->render('index',array(
				'models'=>$model,
				'pages'=>$pages,
				//'categories'=>$categories,
				//'categoryId'=>$categoryId
		));
	}
	public function actionOrderDaliyCollect(){

		$begin_time = Yii::app()->request->getParam('begin_time',1314-05-17);
		$end_time = Yii::app()->request->getParam('end_time',5200-08-09);
		$criteria = new CDbCriteria;
		$sql = "select t2.company_name, t1.name, t.lid, t.dpid, t.payment_method_id, t.update_at, sum(t.should_total) as should_all from nb_order t left join  nb_payment_method t1 on( t.payment_method_id = t1.lid and t.dpid = t1.dpid ) left join nb_company t2 on t.dpid = t2.dpid where t.order_status in(3,4) and  t.update_at >= '$begin_time 00:00:00' and t.update_at <= '$end_time 60:60:60' and t.dpid= ".$this->companyId ." group by t.payment_method_id " ;
		//var_dump($sql);exit;
		$connect = Yii::app()->db->createCommand($sql);
		$model = $connect->queryAll();
		$categoryId = Yii::app()->request->getParam('cid',0);
	
		
		$pages = new CPagination(count($model));
		$pages->PageSize = 10;
		$pages->applyLimit($criteria);
		
		echo $this->getSiteName();
		 
		//      exit;
		$this->render('orderDaliyCollect',array(
				'models'=>$model,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				//'categories'=>$categories,
				//'categoryId'=>$categoryId
		));
	}
        public function actionPaymentRecord(){
	
                $begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
                $end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		//var_dump($begin_time);exit;
                $criteria->select = 't.'; //代表了要查询的字段，默认select='*';    
                $criteria->join = 'nb_payment_method t1 on ...'; //连接表
                $criteria->join = 'nb_company t2 on ...'; //连接表
                $criteria->order = 't.lid ASC' ;//排序条件    
                //$criteria->group = 'group 条件';    
                //$criteria->having = 'having 条件 ';    
                $criteria->distinct = TRUE; //是否唯一查询 
                
		$criteria = new CDbCriteria;
		//$sql = "select t1.company_name, t2.name, t.* from nb_order t left join  nb_payment_method t2 on( t.payment_method_id = t2.lid and t.dpid = t2.dpid )  left join  nb_company t1 on t.dpid = t1.dpid where t.order_status in(3,4,8) and  t.update_at >= '$begin_time 00:00:00' and t.update_at <= '$end_time 23:59:59' and t.dpid= ".$this->companyId;
		//var_dump($sql);exit;
		//$connect = Yii::app()->db->createCommand($sql);
		//$model = $connect->queryAll();
                $model=  OrderPay::model()->findAll($criteria);
		//$categoryId = Yii::app()->request->getParam('cid',0);
	
		//exit;
		$pages = new CPagination(count($model));
		$pages->PageSize = 10;
		$pages->applyLimit($criteria);
		
		//echo $this->getSiteName();
		 
		//      exit;
		$this->render('paymentRecord',array(
				'models'=>$model,
				'pages'=>$pages,
                                'page'=>1,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				//'categories'=>$categories,
				//'categoryId'=>$categoryId
		));
	}
	public function actionNotPay(){
		
		$begin_time = Yii::app()->request->getParam('begin_time',1314-05-17);
		$end_time = Yii::app()->request->getParam('end_time',5200-08-09);
		//var_dump($begin_time);
		//var_dump($end_time);exit;
		//城里人就是这么会玩(o.o)!
		$criteria = new CDbCriteria();
		$sql = "select t2.company_name, t1.name, t.* from nb_order t left join  nb_payment_method t1 on( t.payment_method_id = t1.lid and t.dpid = t1.dpid ) left join nb_company t2 on t.dpid = t2.dpid where t.order_status in(3,4) and  t.update_at >= '$begin_time 00:00:00' and t.update_at <= '$end_time 60:60:60' and t.dpid= ".$this->companyId;
		//var_dump($sql);exit;
		$connect = Yii::app()->db->createCommand($sql);
		$ret = $connect->queryAll();
		//var_dump($ret);exit;
	  // $Begin_time = Yii::app()->request->getParam('begin_time');
	  // Convert(date,[datetime2.value]);
	  // var_dump($Begin_time);exit;
	   // $End_time = Yii::app()->request->getParam('end_time',9999);
		
		$criteria->condition =  ' t.dpid='.$this->companyId ;
		//if($categoryId){
		//	$criteria->condition.=' and t.category_id = '.$categoryId;
		//}
	
		$pages = new CPagination(count($ret));
	   // $pages->PageSize = 10;
		$pages->applyLimit($criteria);
		//$pages = new CPagination();
		//	    $pages->setPageSize(1);z
		//$pages->applyLimit($criteria);
// 		$models = Order::model()->findAll($criteria);
// 		var_dump($models);exit;
// 		echo $this->getSiteName();
		
		
	/*	$postData = Yii::app()->request->getPost('NotPay');
		$model->attributes = $postData;
		$se=new Sequence("retreat");
		$model->lid = $se->nextval();
		$model->create_at = date('Y-m-d H:i:s',time());
	*/	
		//$categories = $this->getCategories();
               
		$this->render('notPay',array(
				'models'=>$ret,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				//'ret'=>$ret,
				//'categories'=>$categories,
				//'categoryId'=>$categoryId
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
                        $model->delete_flag = '0';
                        $py=new Pinyin();
                        $model->simple_code = $py->py($model->product_name);
                        //var_dump($model);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success','添加成功！');
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
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Product');
                        $py=new Pinyin();
                        $model->simple_code = $py->py($model->product_name);
			//var_dump($model->attributes);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success','修改成功！');
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
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_product set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('product/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , '请选择要删除的项目');
			$this->redirect(array('product/index' , 'companyId' => $companyId)) ;
		}
	}
	public function actionStatus(){
		$id = Yii::app()->request->getParam('id');
		$product = Product::model()->find('lid=:id and dpid=:companyId' , array(':id'=>$id,':companyId'=>$this->companyId));
		var_dump($product->status);
		if($product){
			$product->saveAttributes(array('status'=>$product->status?0:1));
		}
		exit;
	}
	public function actionRecommend(){
		$id = Yii::app()->request->getParam('id');
		$product = Product::model()->find('lid=:id and dpid=:companyId' , array(':id'=>$id,':companyId'=>$this->companyId));
		
		if($product){
			$product->saveAttributes(array('recommend'=>$product->recommend==0?1:0));
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
		$optionsReturn = array('--请选择分类--');
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
	
	public function getSiteName(){
		$sitename="";
		$sitetype="";

		$sql = 'select t.site_id, t.dpid, t1.site_level, t1.type_id, t1.serial, t2.name from nb_order t, nb_site t1, nb_site_type t2 where t.site_id = t1.lid and t.dpid = t1.dpid and t1.type_id = t2.lid';
		//$conn = Yii::app()->db->createCommand($sql);
		//$result = $conn->queryRow();
		//$siteId = $result['lid'];
 		$connect = Yii::app()->db->createCommand($sql);
 	//	$connect->bindValue(':site_id',$siteId);
 	//	$connect->bindValue(':dpid',$dpid);
 		$site = $connect->queryRow();
 		if($site['site_id'] && $site['dpid'] ){
		//	echo 'ABC';
		$sitelevel = $site['site_level'];
		$sitename = $site['name'];
		$sitetype = $site['serial'];
		}
		//if($siteId && $dpid){
			//$sql = 'select order.site_id, order.dpid,site.type_id, site.serial, site_type.name from nb_order, nb_site, nb_site_type where order.site_id = site.lid and order.dpid = site.dpid';
			//$conn = Yii::app()->db->createCommand($sql);

	      //}
		return $sitelevel.":".$sitename.":".$sitetype;
	}
	
	public function getOrderDetails($orderId){
	    //$sql = 'select t1.product_id from nb_order t, nb_order_product t1, nb_product t2 where t.lid = t1.order_id and t.dpid = t1.dpid ';
		$sql = 'select t2.product_name  from nb_order_product t1, nb_product t2 where t1.dpid = t2.dpid and t1.product_id = t2.lid and t1.order_id='.$orderId;
		//$sql = 'select t1.product_id, t2.product_name from nb_order t, nb_order_product t1, nb_product t2 where t.lid = t1.order_id and t.dpid = t1.dpid = t2.dpid and t1.product_id = t2.lid';
		
 		$connect = Yii::app()->db->createCommand($sql);
// 		//	$connect->bindValue(':site_id',$siteId);
// 		//	$connect->bindValue(':dpid',$dpid);
 		$name = $connect->queryAll();
 		//var_dump($name);exit;
 		$ret="";
 		foreach($name as $key=>$val){
 			$ret.=$val['product_name']."/";			
 		}				
		echo $ret;
	}
  /*  public function getPayment($lid){
    	$sql = 'select t2.name from nb_order t1, nb_payment_method t2 where t1.payment_method_id = t2.lid and t1.dpid = t2.dpid';
        $connect = Yii::app()->db->createCommand($sql);
        $name = $connect->queryAll();
        $ret="";
        //var_dump($name);exit;
        $ret.=$result['name']."/";
        echo $ret;
    }
   /* public function getPayname($lid){
    	$sql = 'select name from nb_payment_method ';
    	$connect = Yii::app()->db->createCommand($sql);
    	$name = $connect->queryRow();
    	//var_dump($name);exit;
    	echo 'adc';
    
    }
/*  public static function getProductname(){
		$categoryId = Yii::app()->request->getParam('cid',0);
		$criteria = new CDbCriteria;
		$criteria->condition =  't.dpid='.$this->companyId ;
		//if($categoryId){
		//	$criteria->condition.=' and t.category_id = '.$categoryId;
		//}
		
		$pages = new CPagination(Order::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = Order_product::model()->findAll($criteria);
		
		//$categories = $this->getCategories();
                
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
				//'categories'=>$categories,
				//'categoryId'=>$categoryId
		));
		$sql = 'select product_id from nb_order_product where lid=:lid';
		$conn = $command->createcommand($sql);
		$aonn->bindvalue(':productID',$siteNo['product_id']);
		$result = $conn->queryRow();
		return $result['product_id'];
		
		
	}
*/	
}
