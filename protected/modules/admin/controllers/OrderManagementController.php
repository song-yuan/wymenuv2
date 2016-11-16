<?php
class orderManagementController extends BackendController
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
	public function actionList(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
	
		$this->render('list');
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
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		//$sql = 'select t1.name, t.* from nb_order t left join  nb_payment_method t1 on( t.payment_method_id = t1.lid and t.dpid = t1.dpid ) where t.update_at >=0 and t.dpid= '.$this->companyId;
		$criteria->select = 't.*';
		//$criteria->with = array("channel");
		$criteria->addCondition("t.dpid= ".$this->companyId);
		$criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.create_at <='$end_time 23:59:59'");
		//$criteria->addCondition("t.dpid= ".$this->companyId);
		$criteria->with = array("company","paymentMethod","channel");
		
		//$connect = Yii::app()->db->createCommand($sql);
		//$model = $connect->queryAll();
		$criteria->order = 't.create_at asc,t.lid ASC' ;
		$criteria->distinct = TRUE;
	
		//$categoryId = Yii::app()->request->getParam('cid',0);
	
	
	
		$pages = new CPagination(Order::model()->count($criteria));
		//$pages->PageSize = 10;
		$pages->applyLimit($criteria);
	
		$model=  Order::model()->findAll($criteria);
		//var_dump($model);exit;
		$this->render('index',array(
				'models'=>$model,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				
				//'categories'=>$categories,
				//'categoryId'=>$categoryId
		));
	}
	
	public function actionRefund(){
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$orderId = Yii::app()->request->getParam('orderID');
		
		$model = new OrderPay;//新建数据库表！！！
		$model->dpid = $this->companyId ;
		
		$order = Order::model()->find('lid=:lid and dpid=:dpid',array(':lid'=>$orderId,':dpid'=>$this->companyId));
		if(!$order){
			Yii::app()->user->setFlash('error' , yii::t('app','无法查询到该订单!'));
			$this->redirect(array('orderManagement/paymentRecord','companyId' => $this->companyId,'begin_time'=>$begin_time,'end_time'=>$end_time));
		}
		if(Yii::app()->request->isPostRequest) {
			$postData = Yii::app()->request->getPost('OrderPay');
			
			 $model->attributes = $postData;
			 
			 $transaction = Yii::app()->db->beginTransaction();
			try {
				$pay_amount = -abs($postData['pay_amount']);
				$order->reality_total = $order->reality_total + $pay_amount;
				if($order->reality_total < 0){
					Yii::app()->user->setFlash('success' , yii::t('app','退款失败,退款金额大于订单金额!'));
					$this->redirect(array('orderManagement/paymentRecord','companyId' => $this->companyId,'begin_time'=>$begin_time,'end_time'=>$end_time));
				}
				$order->update();
				$model->pay_amount = $pay_amount;
				$se=new Sequence("order_pay");
				$model->lid = $se->nextval();
				$model->create_at = date('Y-m-d H:i:s',time());
				$model->update_at = date('Y-m-d H:i:s',time());
				if($model->save()) {
					Yii::app()->user->setFlash('success' , yii::t('app','退款成功'));
				}
			     $transaction->commit(); //提交事务会真正的执行数据库操作
			     $this->redirect(array('orderManagement/paymentRecord','companyId' => $this->companyId,'begin_time'=>$begin_time,'end_time'=>$end_time));
			} catch (Exception $e) {
				$transaction->rollback(); //如果操作失败, 数据回滚
				 Yii::app()->user->setFlash('error' , yii::t('app','退款失败'));
			}      
		}
		$this->render('refund' , array(
				'model' => $model,
				'order'=>$order,
				'orderId'=>$orderId,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
		));
	}

	public function actionNotPay(){
		$criteria = new CDbCriteria();
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		//var_dump($begin_time);
		//var_dump($end_time);exit;
		//城里人就是这么会玩(o.o)!
		$criteria->select = 't.*'; //代表了要查询的字段，默认select='*';
		$criteria->addCondition("t.dpid= ".$this->companyId);
		$criteria->addInCondition('t.order_status', array(3,4));
		$criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.create_at <='$end_time 23:59:59'");
		$criteria->with = 'paymentMethod';//连接表
		$criteria->order = 't.lid ASC' ;//排序条件
		$criteria->distinct = TRUE; //是否唯一查询
		
		$pages = new CPagination(Order::model()->count($criteria));
		//$pages->PageSize = 10;
		$pages->applyLimit($criteria);
		
		$model=  Order::model()->findAll($criteria);
		//var_dump($model);exit;
				 
		$this->render('notPay',array(
				'models'=>$model,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				//'ret'=>$ret,
				//'categories'=>$categories,
				//'categoryId'=>$categoryId
		));
	}
	
	public function actionOrderDaliyCollect(){
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$money = "0";
		$db = Yii::app()->db;
		$sql = 'select sum(t.reality_money) as all_money,sum(t.give_money) as all_give from nb_member_recharge t where t.dpid = '.$this->companyId.' and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" ';
		$money = Yii::app()->db->createCommand($sql)->queryRow();
		//var_dump($models);exit;
		//var_dump($money);exit;
		$sql = 'select sum(t.recharge_money) as all_recharge,sum(t.cashback_num) as all_cashback from nb_recharge_record t where t.dpid = '.$this->companyId.' and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" ';
		$recharge = Yii::app()->db->createCommand($sql)->queryRow();
		$criteria = new CDbCriteria;

		//$sql = "select t2.company_name, t1.name, t.lid, t.dpid, t.payment_method_id, t.update_at, sum(t.should_total) as should_all from nb_order t left join  nb_payment_method t1 on( t.payment_method_id = t1.lid and t.dpid = t1.dpid ) left join nb_company t2 on t.dpid = t2.dpid where t.order_status in(3,4) and  t.update_at >= '$begin_time 00:00:00' and t.update_at <= '$end_time 60:60:60' and t.dpid= ".$this->companyId ." group by t.payment_method_id " ;
		//var_dump($sql);exit;
		//$connect = Yii::app()->db->createCommand($sql);
		//$model = $connect->queryAll();
		//$categoryId = Yii::app()->request->getParam('cid',0);
//		$criteria->select = 't.paytype, t.payment_method_id,t.lid,t.dpid, t.update_at,t.order_status,t.should_total,sum(t.reality_total) as should_all';
//	    //利用Yii框架CDB语句时，聚合函数要在model的类里面进行公共变量定义，如：变量should_all在order的class里面定义为public $should_all;
//		//$criteria->select = 'sum(t.should_total) as should_all'; //代表了要查询的字段，默认select='*';
//		$criteria->addCondition("t.dpid= ".$this->companyId);
//		$criteria->addInCondition('t.order_status', array(3,4));
//		$criteria->addCondition("t.update_at >='$begin_time 00:00:00'");
//		$criteria->addCondition("t.update_at <='$end_time 23:59:59'");
//		$criteria->with = array("company","paymentMethod"); //连接表
//		$criteria->order = 't.lid ASC' ;//排序条件
//		$criteria->group = 't.paytype';
//		
//		$pages = new CPagination(Order::model()->count($criteria));
//		//$pages->PageSize = 10;
//		$pages->applyLimit($criteria);
//		
//		$model=  Order::model()->findAll($criteria);
		//var_dump($model);exit;
		//$sql ='select t.paytype, t.payment_method_id,t.dpid, t.update_at,sum(t.pay_amount) as should_all,t1.name from nb_order_pay t left join nb_payment_method t1 on(t.dpid = t1.dpid and t.payment_method_id = t1.lid) where (t.dpid = t1.dpid ) group by t.paytype,t.payment_method_id';
        $criteria->select = 't.paytype, t.payment_method_id,t.dpid, t.update_at,sum(t.pay_amount) as should_all';
	    //利用Yii框架CDB语句时，聚合函数要在model的类里面进行公共变量定义，如：变量should_all在order的class里面定义为public $should_all;
		//$criteria->select = 'sum(t.should_total) as should_all'; //代表了要查询的字段，默认select='*';
		$criteria->with = array("order","paymentMethod"); //连接表
		
        $criteria->addCondition("t.dpid= ".$this->companyId);
		$criteria->addCondition("order.update_at >='$begin_time 00:00:00'");
		$criteria->addCondition("order.update_at <='$end_time 23:59:59'");
		$criteria->group = "t.paytype,t.payment_method_id";
		
		$pages = new CPagination(OrderPay::model()->count($criteria));
		$pages->PageSize = 20;
		$pages->applyLimit($criteria);
		
		$model=  OrderPay::model()->findAll($criteria);
        //var_dump($model);exit;
		$this->render('orderDaliyCollect',array(
				'models'=>$model,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'moneys'=>$money,
				'recharge'=>$recharge,
				//'categories'=>$categories,
				//'categoryId'=>$categoryId
		));
	}
        
    public function actionOrderDaliyCollectPrint(){
    	$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
    	$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
    	$padid= Yii::app()->request->getParam('padid');
    	$rl= Yii::app()->request->getParam('rl');
    	$rll=explode(",",$rl);
    	$ret=array();
    	
    	//退爱明细
    	$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t1.account_no,t2.username,t2.retreat_amount,t3.name,t2.retreat_memo,t4.product_name,t.* from nb_order_product t left join nb_order t1 on(t.dpid= t1.dpid and t1.lid = t.order_id ) left join nb_order_retreat t2 on(t.dpid = t2.dpid and t2.order_detail_id = t.lid and t2.delete_flag = 0) left join nb_retreat t3 on(t.dpid = t3.dpid and t3.lid = t2.retreat_id and t3.delete_flag = 0) left join nb_product t4 on(t.dpid = t4.dpid and t.product_id = t4.lid and t4.delete_flag = 0)  where t.delete_flag = 0 and t.is_retreat = 1 and t.product_order_status in(1,2) and t.create_at>="'.$begin_time.' 00:00:00" and t.create_at<="'.$end_time.' 23:59:59"';
    	$retreatdetails = Yii::app()->db->createCommand($sql)->queryAll();
    	
    	//台桌区域
    	//$db = Yii::app()->db;
    	$sql = 'select sum(t.number) as all_number, count(t.account_no) as all_account, sum(t2.pay_amount) as all_paymoney, t3.name, t.* from nb_order t left join nb_site t1 on(t.site_id = t1.lid and t.dpid = t1.dpid and t1.delete_flag =0) left join nb_order_pay t2 on(t.lid = t2.order_id and t.dpid = t2.dpid and t2.paytype !=11) left join nb_site_type t3 on(t1.type_id = t3.lid and t3.dpid = t.dpid ) where t.dpid ='.$this->companyId.' and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.order_status in(3,4,8) group by t1.type_id order by sum(t.number) desc';//区域名称报表
    	$tableareas = Yii::app()->db->createCommand($sql)->queryAll();
    	//var_dump($model);exit;
    	$sql = 'select sum(t.number) as all_number, count(t.account_no) as all_account, sum(t2.pay_amount) as all_money, t3.name, t.* from nb_order t left join nb_site t1 on(t.site_id = t1.lid and t.dpid = t1.dpid and t1.delete_flag =0) left join nb_order_pay t2 on(t.lid = t2.order_id and t.dpid = t2.dpid and t2.paytype !=11) left join nb_site_type t3 on(t1.type_id = t3.lid and t3.dpid = t.dpid ) where t.dpid ='.$this->companyId.' and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.order_status in(3,4,8) ';//区域名称报表
    	$allmoney = Yii::app()->db->createCommand($sql)->queryRow();
    	
    	//账单详情‘
    	$criteria = new CDbCriteria;
    	//$sql = 'select t1.name, t.* from nb_order t left join  nb_payment_method t1 on( t.payment_method_id = t1.lid and t.dpid = t1.dpid ) where t.create_at >=0 and t.dpid= '.$this->companyId;
    	$criteria->select = 'sum(t.number) as all_number,t.*';
    	$criteria->addCondition("t.dpid= ".$this->companyId);
    	$criteria->addCondition("t.order_status in(3,4,8) ");//只要付款了的账单都进行统计
    	$criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
    	$criteria->addCondition("t.create_at <='$end_time 23:59:59'");
    	//$criteria->addCondition("t.dpid= ".$this->companyId);
    	$criteria->with = array("company","paymentMethod");
    	$criteria->group = 't.account_no,t.order_status' ;
    	$criteria->order = 't.lid ASC' ;
    	$criteria->distinct = TRUE;
    	$orderdetails = Order::model()->findAll($criteria);
    	
    	//产品销售
    	$criteria = new CDbCriteria;
    	$criteria->select ='year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,t.product_id,t.price,t.amount,t.is_retreat,sum(t.price) as all_money,sum(t.amount) as all_total, sum(t.price*t.amount*(-(t.is_giving-1))) as all_price, sum(t.original_price*t.amount) as all_jiage';
    	$criteria->with = array('company','product','order');
    	$criteria->condition = 'order.order_status in(3,4,8) and t.is_retreat=0 and t.product_order_status in(1,2) and t.delete_flag=0 and t.dpid='.$this->companyId;
    	//if($str){
    	//	$criteria->condition = 'order.order_status in(3,4,8) and t.is_retreat=0 and t.product_order_status in(1,2) and t.delete_flag=0 and t.dpid in('.$str.')';
    	//}
    	$criteria->addCondition("t.set_id in(0)");
    	$criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
    	$criteria->addCondition("t.create_at <='$end_time 23:59:59'");
    	$criteria->group ='t.product_id,year(t.create_at)';
		$criteria->order = 'year(t.create_at) asc,sum(t.amount) desc,sum(t.original_price*t.amount) desc,t.dpid asc';
    	$products = OrderProduct::model()->findAll($criteria);
    	
    	//收款统计（支付方式）
		$criteria = new CDbCriteria;
		$criteria->select = 'year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.dpid,t.create_at,sum(t.pay_amount) as all_reality,t.paytype,t.payment_method_id,count(*) as all_num';//array_count_values()
		$criteria->with = array('company','order8','paymentMethod');
		$criteria->condition = ' t.dpid='.$this->companyId ;
		//	$criteria->condition = 't.dpid in('.$str.')';
		$criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.create_at <='$end_time 23:59:59'");
		$criteria->group ='t.payment_method_id,t.paytype,t.dpid,year(t.create_at)';
		$criteria->order = 'year(t.create_at) asc,sum(t.pay_amount) desc,t.dpid asc';
		$payments = OrderPay::model()->findAll($criteria);
		
		//营业收入（菜品类别）
		$db = Yii::app()->db;
		$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.number) as all_number,ifnull(count(distinct(t.account_no)),0) as all_account,t2.pay_amount,ifnull(sum(t2.pay_amount),0) as all_realprice,t.* from nb_order t left join nb_order_pay t2 on(t.dpid = t2.dpid and t2.order_id = t.lid and t2.paytype not in(9,10,11)) where t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.order_status in(3,4,8)';
		$modeldata = Yii::app()->db->createCommand($sql)->queryRow();
		$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.number) as all_number,ifnull(count(distinct(t.account_no)),0) as all_account,ifnull(sum(t1.original_price*t1.amount),0) as all_originalprice,ifnull(sum(t1.price*t1.amount*(-(t1.is_giving-1))),0) as all_price,t.* from nb_order t left join nb_order_product t1 on(t.dpid = t1.dpid and t1.delete_flag = 0 and t1.order_id = t.lid and t1.product_order_status in(1,2) and t1.is_retreat =0) where t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.order_status in(3,4,8) ';
		//统计订单原价
		//echo $sql;exit;
		$moneydata = Yii::app()->db->createCommand($sql)->queryRow();
		
		//营业收入报表
		$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.amount) as all_num,
						t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price*t.amount*(-(t.is_giving-1))) as all_price,t1.category_id,t2.category_name,t3.company_name
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status in(3,4,8)
								group by t1.category_id,t.dpid,year(t.create_at) order by year(t.create_at) asc,sum(t.price) desc,t.dpid asc';
		$incomes = Yii::app()->db->createCommand($sql)->queryAll();
		
		
//		$sql = 'select sum(t.reality_money) as all_money from nb_member_recharge t where t.dpid = '.$this->companyId.' and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" ';
//		$money = Yii::app()->db->createCommand($sql)->queryRow();
		//充值（卡）
		$money = "0";
        $sql = 'select ifnull(sum(t.reality_money),0) as all_money,ifnull(sum(t.give_money),0) as all_give from nb_member_recharge t where t.dpid = '.$this->companyId.' and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" ';
		$money = Yii::app()->db->createCommand($sql)->queryRow();
		//充值（微信）
        $sql = 'select ifnull(sum(t.recharge_money),0) as all_recharge,ifnull(sum(t.cashback_num),0) as all_cashback from nb_recharge_record t where t.dpid = '.$this->companyId.' and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" ';
		$recharge = Yii::app()->db->createCommand($sql)->queryRow();
		
		//日结数据
		$criteria = new CDbCriteria;
		//添加
		$criteria->select = 't.paytype, t.payment_method_id,t.dpid, t.update_at,sum(t.pay_amount) as should_all';
	    //利用Yii框架CDB语句时，聚合函数要在model的类里面进行公共变量定义，如：变量should_all在order的class里面定义为public $should_all;
		//$criteria->select = 'sum(t.should_total) as should_all'; //代表了要查询的字段，默认select='*';
		$criteria->with = array("order","paymentMethod"); //连接表
		$criteria->addCondition("t.dpid= ".$this->companyId);
		$criteria->addCondition("order.update_at >='$begin_time 00:00:00'");
		$criteria->addCondition("order.update_at <='$end_time 23:59:59'");
		$criteria->group = "t.paytype,t.payment_method_id";
		
		$models=  OrderPay::model()->findAll($criteria);
//                 if(count($models)==0)
//                 {
//                     $ret=array('status'=>false,'msg'=>"没有数据！");
//                     Yii::app()->end(json_encode($ret));
//                 }
		//var_dump($model[0]->order);exit;
                //Yii::app()->end(json_encode(array('status'=>false,'msg'=>"111")));
                ////////////////
                
                $transaction = Yii::app()->db->beginTransaction(); 
		try{
			$userId = Yii::app()->user->userId;// lid_dpid
			$userIdArr = explode('_',$userId);
			$se=new Sequence("close_account");
                        $clid = $se->nextval();
			$closeA = new CloseAccount;
			$closeAdata = array(
                                            'lid'=>$clid,
                                            'dpid'=>$this->companyId,
                                            'create_at'=>date('Y-m-d H:i:s',time()),
                                            'update_at'=>date('Y-m-d H:i:s',time()),
                                            'user_id'=>$userIdArr[0],
                                            'begin_time'=>$begin_time,
                                            'end_time'=>$end_time,
                                            'close_day'=>date('Y-m-d H:i:s',time()),
                                            'all_money'=>0
                                            );
			$closeA->attributes = $closeAdata;
			$closeA->save();
			
			$totalMoney = 0;
			foreach($models as $model){
				//插入明细表
				$se=new Sequence("close_account_detail");
	            $lid = $se->nextval();
				$closeADel = new CloseAccountDetail;
				$closeADeldata = array(
                                                'lid'=>$lid,
                                                'dpid'=>$this->companyId,
                                                'create_at'=>date('Y-m-d H:i:s',time()),
                                                'update_at'=>date('Y-m-d H:i:s',time()),
                                                'close_account_id'=>$clid,
                                                'paytype'=>$model->paytype,
                                                'payment_method_id'=>$model->payment_method_id,
                                                'all_money'=>$model->should_all,
                                                );
				$closeADel->attributes = $closeADeldata;
				$closeADel->save();				
				$totalMoney +=$model->should_all;
			}
			
			//更改订单表状态
			//Order::model()->updateAll(array('order_status'=>8),'update_at >=:begin_time and :end_time >=update_at and order_status in (3,4) and dpid=:dpid',array(':begin_time'=>$begin_time,':end_time'=>$end_time));
			$sqlorderup="update nb_order set order_status=8 where dpid=$this->companyId and update_at >='$begin_time 00:00:00' and update_at<='$end_time 23:59:59' and order_status in (4)";
                        //var_dump($sqlorderup);exit;
                        Yii::app()->db->createCommand($sqlorderup)->execute();
			$cmodel = CloseAccount::model()->find('lid=:lid and dpid=:dpid',array(':lid'=>$clid,'dpid'=>$this->companyId));
			$cmodel->all_money = $totalMoney;
			$cmodel->update();
                        
                        ///////////////////////
                        $pad=Pad::model()->with('printer')->find(' t.dpid=:dpid and t.lid=:lid',array(':dpid'=>$this->companyId,'lid'=>$padid));
                         //前面加 barcode
                        $precode="";
                        //$memo="日结对账单";
                        $ret = Helper::printCloseAccount($this->companyId, $retreatdetails, $tableareas,$allmoney, $orderdetails, $products, $rll, $payments, $models ,$incomes, $begin_time, $end_time, $modeldata, $money, $moneydata, $recharge, $pad,$precode,"0");//添加$money
                       // var_dump($ret);exit;
                        //var_dump($money);exit;
			$transaction->commit(); //提交事务会真正的执行数据库操作
			//echo 1;
		}catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			//echo 0;
                        $ret=array('status'=>false,'msg'=>"请重试！");
		}              
                Yii::app()->end(json_encode($ret));		
	}
	//日结 指定日期的订单
	public function actionDailyclose(){

		$criteria = new CDbCriteria;
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		
//		$criteria->select = 't.paytype, t.payment_method_id,t.lid,t.dpid, t.update_at,t.order_status,t.should_total,sum(t.reality_total) as should_all';
//	    //利用Yii框架CDB语句时，聚合函数要在model的类里面进行公共变量定义，如：变量should_all在order的class里面定义为public $should_all;
//		//$criteria->select = 'sum(t.should_total) as should_all'; //代表了要查询的字段，默认select='*';
//		$criteria->addCondition("t.dpid= ".$this->companyId);
//		$criteria->addInCondition('t.order_status', array(3,4));
//		$criteria->addCondition("t.update_at >='$begin_time 00:00:00'");
//		$criteria->addCondition("t.update_at <='$end_time 23:59:59'");
//		$criteria->with = array("company","paymentMethod"); //连接表
//		$criteria->order = 't.lid ASC' ;//排序条件
//		$criteria->group = 't.paytype';
//		
//		$models =  Order::model()->findAll($criteria);
                $criteria->select = 't.paytype, t.payment_method_id,t.dpid, t.update_at,sum(t.pay_amount) as should_all';
	    //利用Yii框架CDB语句时，聚合函数要在model的类里面进行公共变量定义，如：变量should_all在order的class里面定义为public $should_all;
		//$criteria->select = 'sum(t.should_total) as should_all'; //代表了要查询的字段，默认select='*';
		$criteria->with = array("order"); //连接表
		
                $criteria->addCondition("t.dpid= ".$this->companyId);
		$criteria->addCondition("order.update_at >='$begin_time 00:00:00'");
		$criteria->addCondition("order.update_at <='$end_time 23:59:59'");
		//$criteria->with = array("paymentMethod"); //连接表
                $criteria->group = "t.paytype";
		
		$models=  OrderPay::model()->findAll($criteria);
                
		$transaction = Yii::app()->db->beginTransaction(); 
		try{
			$userId = Yii::app()->user->userId;// lid_dpid
			$userIdArr = explode('_',$userId);
			$se=new Sequence("close_account");
            $clid = $se->nextval();
			$closeA = new CloseAccount;
			$closeAdata = array(
								'lid'=>$clid,
								'dpid'=>$this->companyId,
								'create_at'=>date('Y-m-d H:i:s',time()),
								'update_at'=>date('Y-m-d H:i:s',time()),
								'user_id'=>$userIdArr[0],
								'begin_time'=>$begin_time,
								'end_time'=>$end_time,
								'close_day'=>date('Y-m-d H:i:s',time()),
								'all_money'=>0
								);
			$closeA->attributes = $closeAdata;
			$closeA->save();
			
			$totalMoney = 0;
			foreach($models as $model){
				//插入明细表
				$se=new Sequence("close_account_detail");
	            $lid = $se->nextval();
				$closeADel = new CloseAccountDetail;
				$closeADeldata = array(
								'lid'=>$lid,
								'dpid'=>$this->companyId,
								'create_at'=>date('Y-m-d H:i:s',time()),
								'update_at'=>date('Y-m-d H:i:s',time()),
								'close_account_id'=>$clid,
								'paytype'=>$model->paytype,
								'payment_method_id'=>$model->payment_method_id,
								'all_money'=>$model->should_all,
								);
				$closeADel->attributes = $closeADeldata;
				$closeADel->save();
				
				$totalMoney +=$model->should_all;
			}
			
			//更改订单表状态
			//Order::model()->updateAll(array('order_status'=>8),'update_at >=:begin_time and :end_time >=update_at and order_status in (3,4) and dpid=:dpid',array(':begin_time'=>$begin_time,':end_time'=>$end_time));
			$sqlorderup="update nb_order set order_status=8 where dpid=$this->companyId and update_at >='$begin_time 00:00:00' and update_at<='$end_time 23:59:59' and order_status in (4)";
                        //var_dump($sqlorderup);exit;
                        Yii::app()->db->createCommand($sqlorderup)->execute();
			$cmodel = CloseAccount::model()->find('lid=:lid and dpid=:dpid',array(':lid'=>$clid,'dpid'=>$this->companyId));
			$cmodel->all_money = $totalMoney;
			$cmodel->update();
			$transaction->commit(); //提交事务会真正的执行数据库操作
			echo 1;
		}catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			echo 0;
		}
		exit();
	}
	
	public function actionAccountStatement(){
	
		$criteria = new CDbCriteria;
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));

		//$sql= "select year(create_at),month(create_at),day(create_at), t.lid, t.paytype,t.payment_method_id,t.dpid, t.create_at,t.all_money, t1.company_name, t2.name,sum(t.all_money) as should_all  from nb_close_account_detail t left join  nb_payment_method t2 on( t.payment_method_id = t2.lid and t.dpid = t2.dpid )  left join  nb_company t1 on t.dpid = t1.dpid  where t.dpid= ".$this->companyId ." group by t.payment_method_id,t.paytype,year(create_at),month(create_at),day(create_at)";
		//var_dump($sql);exit();
		//利用Yii框架CDB语句时，聚合函数要在model的类里面进行公共变量定义，如：变量should_all在order的class里面定义为public $should_all;
		$criteria->select = "t.lid, t.all_money,t.user_id,t.dpid,t.close_day ";
		//$criteria->select = "convert(nvarchar(10),CreateDate,120), t.lid, t.paytype,t.payment_method_id,t.dpid, t.create_at,sum(t.all_money) as should_all ";
		
		$criteria->addCondition("t.dpid= ".$this->companyId);
		
		$criteria->addCondition("t.close_day >='$begin_time 00:00:00'");
		$criteria->addCondition("t.close_day <='$end_time 23:59:59'");
		$criteria->with = 'user'; //连接表
		$criteria->order = 't.close_day ASC' ;//排序条件
		//$criteria->group = 't.payment_method_id,t.paytype,day(t.create_at)';
		//$criteria->group = 't.paytype';
	
		$pages = new CPagination(CloseAccount::model()->count($criteria));
		//$pages->PageSize = 10;
		$pages->applyLimit($criteria);
	
		$model=  CloseAccount::model()->findAll($criteria);
                //var_dump($model);exit;
		//var_dump($model);exit;
		$this->render('accountStatement',array(
				'models'=>$model,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				//'categories'=>$categories,
				//'categoryId'=>$categoryId
		));
	}
	public function actionDetail(){
		$id = Yii::app()->request->getParam('id');
		$criteria = new CDbCriteria;
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
	
		//利用Yii框架CDB语句时，聚合函数要在model的类里面进行公共变量定义，如：变量should_all在order的class里面定义为public $should_all;
		//$criteria->select = "year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all, t.lid, t.paytype,t.payment_method_id,t.dpid, t.create_at,sum(t.all_money) as should_all ";
		//$criteria->select = "convert(nvarchar(10),CreateDate,120), t.lid, t.paytype,t.payment_method_id,t.dpid, t.create_at,sum(t.all_money) as should_all ";
	    $criteria->select = "t.lid,t.dpid,t.paytype,t.payment_method_id,t.create_at,t.all_money";
		$criteria->addCondition("t.dpid= ".$this->companyId);
		$criteria->addCondition("t.close_account_id=".$id);
		$criteria->with = array("closeAccount","paymentMethod");
		//$criteria->group = 't.paymentMethod' ;
		$criteria->order = 't.update_at ASC' ;//排序条件

		$criteria->distinct = TRUE; //是否唯一查询
	
		$pages = new CPagination(CloseAccountDetail::model()->count($criteria));
		//$pages->PageSize = 10;
		$pages->applyLimit($criteria);
	
		$model=  CloseAccountDetail::model()->findAll($criteria);
		//var_dump($model);exit;
		$this->render('detail',array(
				'models'=>$model,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				//'categories'=>$categories,
				//'categoryId'=>$categoryId
		));
	}
        public function actionPaymentRecord(){
        	    $criteria = new CDbCriteria;
                $begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
                $end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
                $Did = Yii::app()->request->getParam('Did',0);
                //var_dump($begin_time);exit;
                $orderID = Yii::app()->request->getParam('orderID');
                //var_dump($orderID);exit;
                $criteria->select = 't.*'; //代表了要查询的字段，默认select='*'; 
                $criteria->addCondition("t.dpid= ".$this->companyId);
               // if ($Did > 0){
               // $criteria->addCondition("t.order_id = '$Did'");}
                
                $criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
                
                $criteria->addCondition("t.create_at <='$end_time 23:59:59'");
                if($orderID){
                	$criteria->addCondition("t.order_id= ".$orderID);
                }
                //$criteria->select = 't1.should_total';
                //var_dump($begin_time);exit;
				$criteria->with = array("company","order4"); //连接表
                //$criteria->join = 'left join nb_order t1 on (t.dpid = t1.dpid and t.order_id = t1.lid )'; //连接表
                //var_dump();exit;
               // $criteria->join = 'left join nb_company on t.dpid = nb_company.dpid '; //连接表
                $criteria->order = 't.create_at ASC,t.order_id ASC' ;//排序条件    
               // var_dump($begin_time);exit;
                //$criteria->group = 'group 条件';    
                //$criteria->having = 'having 条件 ';    
                $criteria->distinct = TRUE; //是否唯一查询 
                
		//$criteria = new CDbCriteria;
		//$sql = "select t1.company_name, t2.name, t.* from nb_order t left join  nb_payment_method t2 on( t.payment_method_id = t2.lid and t.dpid = t2.dpid )  left join  nb_company t1 on t.dpid = t1.dpid where t.order_status in(3,4,8) and  t.update_at >= '$begin_time 00:00:00' and t.update_at <= '$end_time 23:59:59' and t.dpid= ".$this->companyId;
		//var_dump($sql);exit;
		//$connect = Yii::app()->db->createCommand($sql);
		//$model = $connect->queryAll();
                $pages = new CPagination(OrderPay::model()->count($criteria));
                //$pages->PageSize = 10;
                $pages->applyLimit($criteria);
                
                // 注意事项：pages语句 必须放在  model 语句前面才能有效的执行。。。
                $model=  OrderPay::model()->findAll($criteria);
                //var_dump($model);exit;
		//$categoryId = Yii::app()->request->getParam('cid',0);
	
		//exit;

		 
		$this->render('paymentRecord',array(
				'models'=>$model,
				'pages'=>$pages,
                //'page'=>1,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'Did'=>$Did,
				'orderID'=>$orderID,
				//'categories'=>$categories,
				//'categoryId'=>$categoryId
		));
	}
 
/*	public function actionNotPay(){
		
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
	*/

	/*SQL语句写成的分页出现问题。。。
	 * 	public function actionIndex(){
	$criteria = new CDbCriteria;
	$sql = 'select t1.name, t.* from nb_order t left join  nb_payment_method t1 on( t.payment_method_id = t1.lid and t.dpid = t1.dpid ) where t.update_at >=0 and t.dpid= '.$this->companyId;
	$connect = Yii::app()->db->createCommand($sql);
	$model = $connect->queryAll();
	$categoryId = Yii::app()->request->getParam('cid',0);
	
	echo $this->getSiteName($orderId);
	
	$pages = new CPagination(count($model));
	$pages->PageSize = 10;
	$pages->applyLimit($criteria);
	
	$this->render('index',array(
			'models'=>$model,
			'pages'=>$pages,
			//'categories'=>$categories,
			//'categoryId'=>$categoryId
	));
	}
	*/

/*	public function actionOrderDaliyCollect(){
	
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
	
	
		$this->render('orderDaliyCollect',array(
				'models'=>$model,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				//'categories'=>$categories,
				//'categoryId'=>$categoryId
		));
	}

*/
/*	private function getCategoryList(){
		$categories = ProductCategory::model()->findAll('delete_flag=0 and dpid=:companyId' , array(':companyId' => $this->companyId)) ;
		//var_dump($categories);exit;
		return CHtml::listData($categories, 'lid', 'category_name');
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
	*/
	private function getDepartments(){
		$departments = Department::model()->findAll('company_id=:companyId',array(':companyId'=>$this->companyId)) ;
		return CHtml::listData($departments, 'department_id', 'name');
	}
	
	public function getSiteName($orderId){
		$sitename="";
		$sitetype="";

		$sql = 'select t.site_id, t.dpid, t1.site_level, t1.type_id, t1.serial, t2.name from nb_order t, nb_site t1, nb_site_type t2 where t.site_id = t1.lid and t.dpid = t1.dpid and t1.type_id = t2.lid and t.dpid = t2.dpid and t.lid ='. $orderId;
		//$conn = Yii::app()->db->createCommand($sql);
		//$result = $conn->queryRow();
		//$siteId = $result['lid'];
 		$connect = Yii::app()->db->createCommand($sql);
 	//	$connect->bindValue(':site_id',$siteId);
 	//	$connect->bindValue(':dpid',$dpid);
 		$site = $connect->queryRow();
                $retsite="";
 		if($site['site_id'] && $site['dpid'] ){
		//	echo 'ABC';
                    $sitelevel = $site['site_level'];
                    $sitename = $site['name'];
                    $sitetype = $site['serial'];
                    $retsite=$sitelevel.":".$sitename.":".$sitetype;
		}
		//if($siteId && $dpid){
			//$sql = 'select order.site_id, order.dpid,site.type_id, site.serial, site_type.name from nb_order, nb_site, nb_site_type where order.site_id = site.lid and order.dpid = site.dpid';
			//$conn = Yii::app()->db->createCommand($sql);

	      //}
		return $retsite;
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
	static public function getCompanyName($companyId) {
		if($companyId)
		{
			$models = Company::model()->find('t.dpid = '.$companyId);
			//var_dump($models);exit;
			//return Yii::app()->user->role == User::POWER_ADMIN ? $companyId : Yii::app()->user->companyId ;
			 
		}else{
			$models = Company::model()->find('t.dpid = '.Yii::app()->user->companyId);
		}
		return $models->company_name;
		//index 界面的店铺名称获取方法来自于layouts->header.php 和components->Helper.php 。
	}
 
}
