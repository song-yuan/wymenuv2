<?php
class StatementsController extends BackendController
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


	public function actionProductsalesReport(){
		//$uid = Yii::app()->user->id;
		$str = Yii::app()->request->getParam('str');
		//var_dump($str);exit();
		$text = Yii::app()->request->getParam('text');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		//$catId = Yii::app()->request->getParam('cid',0);
		//var_dump($catId);exit;
		$criteria = new CDbCriteria;
		//$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.update_at,t.lid,t.dpid,t1.dpid,t.product_id,t1.lid,t1.product_name,t.price,t.amount,t.is_retreat,sum(t.price) as all_money,sum(t.amount) as all_total from nb_order_product t left join nb_product t1 on(t1.lid = t.product_id and t.dpid = t1.dpid ) where t.delete_flag=0 and t1.delete_flag = 0 and t.product_order_status=1 group by t.product_id,t.amount,is_retreat,month(t.create_at)';
		//var_dump($sql);exit;
		$criteria->select ='year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.update_at,t.lid,t.dpid,t.product_id,t.price,t.amount,t.is_retreat,sum(t.price) as all_money,sum(t.amount) as all_total, sum(t.price*t.amount) as all_price';
		$criteria->with = array('company','product');

		$criteria->condition = 't.is_retreat=0 and t.product_order_status=1 and t.delete_flag=0 and t.dpid='.$this->companyId;
		if($str){
			$criteria->condition = 't.is_retreat=0 and t.product_order_status=1 and t.delete_flag=0 and t.dpid in('.$str.')';
		}
		$criteria->addCondition("t.update_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.update_at <='$end_time 23:59:59'");
		
		if($text==1){
		$criteria->group ='t.product_id,year(t.update_at)';
		}elseif($text==2){
			$criteria->group ='t.product_id,month(t.update_at)';
		}else{
			$criteria->group ='t.product_id,day(t.update_at)';
		}
		
		$criteria->order = 't.update_at asc';

		$pages = new CPagination(OrderProduct::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = OrderProduct::model()->findAll($criteria);
		//var_dump($models);exit();
		$comName = $this->getComName();
		//$a=array_keys($comName);
		//var_dump($a);exit;
        // var_dump($comName);exit;       


		$this->render('productsalesReport',array(
				'models'=>$models,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				
				'comName'=>$comName,
				//'catId'=>$catId
		));
	}
	
	public function actionSalesReport(){
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$criteria = new CDbCriteria;
		$criteria->select = 'year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.dpid,t.update_at,sum(t.reality_total) as all_reality,t.paytype,t.payment_method_id,t.order_status';
		$criteria->with = array('company','paymentMethod');
		$criteria->condition = 't.order_status in(3,4,8) and t.dpid='.$this->companyId ;
		if($str){
			$criteria->condition = ' t.order_status in(3,4,8) and t.dpid in('.$str.')';
		}
		$criteria->addCondition("t.update_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.update_at <='$end_time 23:59:59'");
		if($text==1){
			$criteria->group ='t.paytype,t.dpid,t.payment_method_id,year(t.update_at)';
		}elseif($text==2){
			$criteria->group ='t.paytype,t.dpid,t.payment_method_id,month(t.update_at)';
		}else{
			$criteria->group ='t.paytype,t.dpid,t.payment_method_id,day(t.update_at)';
		}
		$criteria->order = 't.update_at asc';
		//$criteria->group = 't.paytype,t.payment_method_id';
		
		$pages = new CPagination(Order::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		//var_dump($criteria);exit;
                $model = Order::model()->findAll($criteria);
                $comName = $this->getComName();
		$this->render('salesReport',array(
				'models'=>$model,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'comName'=>$comName,
				//'categories'=>$categories,
				//'categoryId'=>$categoryId
		));
	}
	public function actionOrderReport(){
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$criteria = new CDbCriteria;
		$criteria->select = 'year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.dpid,t.update_at,count(t.order_status) as all_status,t.paytype,t.payment_method_id,t.order_status';
		$criteria->with = array('company','paymentMethod');
		$criteria->condition = ' t.dpid='.$this->companyId ;
		if($str){
			$criteria->condition = ' t.dpid in('.$str.')';
		}
		$criteria->addCondition("t.update_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.update_at <='$end_time 23:59:59'");
		if($text==1){
			$criteria->group ='t.dpid,t.order_status,year(t.update_at)';
		}elseif($text==2){
			$criteria->group ='t.dpid,t.order_status,month(t.update_at)';
		}else{
			$criteria->group ='t.dpid,t.order_status,day(t.update_at)';
		}
		$criteria->order = 't.update_at asc';
		//$criteria->group = 't.paytype,t.payment_method_id';
	
		$pages = new CPagination(Order::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		//var_dump($criteria);exit;
		$model = Order::model()->findAll($criteria);
		$comName = $this->getComName();
		$this->render('orderReport',array(
				'models'=>$model,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'comName'=>$comName,
				//'categories'=>$categories,
				//'categoryId'=>$categoryId
		));
	}
	private function exportOrder($models,$type=0,$orderStatus = 0,$params=array(),$export = 'xml'){
		$attributes = array(
				'order_id'=>'订单编号',
				'create_time'=>'下单时间',
				'card_id'=>'会员号',
				'goods_name'=>'所购商品',
				'order_goods_number'=>'商品总数量',
				'cost'=>'商品总价(元)',
				'total'=>'订单总价(元)',
				'shop_name'=>'门店名'
		);
		$data[1] = array_values($attributes);
		$fields = array_keys($attributes);
			
		foreach($models as $model){
			$arr = array();
			foreach($fields as $f){
				if($f == 'create_time'){
					$arr[] = date('Y-m-d H:i:s',$model[$f]);
				}elseif(in_array($f,array('cost','total'))){
					$arr[] = $model[$f]/100;
				}elseif(in_array($f,array('card_id'))){
					$arr[] = substr($model[$f],5);
				}elseif(in_array($f,array('order_goods_number'))){
					$arr[] = $model[$f];
				}elseif(in_array($f,array('goods_name'))){
					$goodsName='';
					if($model['offGoods']){
						foreach($model['offGoods'] as $goods){
							$goodsName.=$goods['goods_num'].'×'.$goods['goods_name'].';';
						}
					}
					$arr[] =rtrim($goodsName,';');
				}else{
					$arr[] = $model[$f];
				}
			}
			$data[] = $arr;
		}
		Until::exportFile($data,$export,$fileName=date('Y_m_d_H_i_s'));
	}

/*	private function getCategoryList(){
		$categories = user::model()->findAll('delete_flag=0 ' , array(':companyId' => $this->companyId)) ;
		//var_dump($categories);exit;
		return CHtml::listData($categories, 'lid', 'username');
	}
*/
	public function getComName(){
		$uid = Yii::app()->user->id;
		$sql = 'select t.lid,t.dpid,t1.company_id,t2.company_name from nb_user t left join nb_user_company t1 on(t.dpid = t1.dpid and t.lid = t1.user_id and t1.delete_flag = 0) left join nb_company t2 on(t1.company_id = t2.dpid ) where t.delete_flag = 0 and t.username = "'.$uid.'"';
		//var_dump($sql);exit;
		$connect = Yii::app()->db->createCommand($sql);
		//var_dump($connect);exit;
		$models = $connect->queryAll();
		//var_dump($model);exit; 
		//$options = array();
		$optionsReturn = array();
		//var_dump($optionsReturn);exit;
		if($models) {
			foreach ($models as $model) {
				//var_dump($model);exit;
				$optionsReturn[$model['company_id']] = $model['company_name'];
				//var_dump($optionsReturn);exit;
			}
			//var_dump($optionsReturn);exit;
		}
		
		//var_dump($optionsReturn);exit;
		return $optionsReturn;

		

	}
// 	private function getDepartments(){
// 		$departments = Department::model()->findAll('company_id=:companyId',array(':companyId'=>$this->companyId)) ;
// 		return CHtml::listData($departments, 'department_id', 'name');
// 	}
	

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


// 		public function actionSalesReport(){
// 			$str = Yii::app()->request->getParam('str');
// 			$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
// 			$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
// 			$criteria = new CDbCriteria;
// 			$criteria->select = 't.*';
// 			$criteria->with = array('company','paymentMethod');
// 			$criteria->condition = ' t.dpid='.$this->companyId ;
// 			if($str){
// 				$criteria->condition = ' t.dpid in('.$str.')';
// 			}
// 			$criteria->addCondition("t.update_at >='$begin_time 00:00:00'");
// 			$criteria->addCondition("t.update_at <='$end_time 23:59:59'");
// 			$criteria->order = 't.update_at asc';
		
// 			$pages = new CPagination(CloseAccountDetail::model()->count($criteria));
// 			//	    $pages->setPageSize(1);
// 			$pages->applyLimit($criteria);
// 			//var_dump($criteria);exit;
// 			$model = CloseAccountDetail::model()->findAll($criteria);
// 			$comName = $this->getComName();
// 			$this->render('salesReport',array(
// 					'models'=>$model,
// 					'pages'=>$pages,
// 					'begin_time'=>$begin_time,
// 					'end_time'=>$end_time,
// 					'comName'=>$comName,
// 					//'categories'=>$categories,
// 					//'categoryId'=>$categoryId
// 			));
// 		}
}