<?php

class DefaultOrderController extends BackendController
{
        public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' ,yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
    
        public function actionOrder(){
		$sid = Yii::app()->request->getParam('sid',0);
		$istemp = Yii::app()->request->getParam('istemp',0);
		$companyId = Yii::app()->request->getParam('companyId',0);
                $typeId = Yii::app()->request->getParam('typeId',0);
                $orderId = Yii::app()->request->getParam('orderId',0);
                $syscallId = Yii::app()->request->getParam('syscallId',0);
                $autoaccount = Yii::app()->request->getParam('autoaccount',0);
                $order=array();
                $siteNo=array();
                ///***********insert to order feedback
                ///*************print
                if($orderId !='0')
                {
                    $order = Order::model()->find('lid=:lid and dpid=:dpid and order_status in("1","2","3")' , array(':lid'=>$orderId,':dpid'=>$companyId));
                    if(empty($order))
                    {
                        $title=yii::t('app',"该订单不存在，请输入合法订单！");
                        $backurl=$this->createUrl('default/index',array('companyId'=>$this->companyId));
                        $this->render('error' , 
                                array('backurl'=>$backurl,
                                    'title'=>$title));
                        exit;
                    }
                    $criteria = new CDbCriteria;
                    $criteria->condition =  't.dpid='.$companyId.' and t.site_id='.$order->site_id.' and t.is_temp='.$order->is_temp ;
                    $criteria->order = ' t.lid desc ';                    
                    $siteNo = SiteNo::model()->find($criteria);
                }else{
                    $criteria = new CDbCriteria;
                    $criteria->condition =  ' t.order_status in ("1","2","3") and  t.dpid='.$companyId.' and t.site_id='.$sid.' and t.is_temp='.$istemp ;
                    $criteria->order = ' t.lid desc ';
                    $order = Order::model()->find($criteria);
                    //var_dump($order);exit;
                    $criteria->condition =  ' t.status in ("1","2","3") and  t.dpid='.$companyId.' and t.site_id='.$sid.' and t.is_temp='.$istemp ;
                    $criteria->order = ' t.lid desc ';
                    $siteNo = SiteNo::model()->find($criteria);
                }
                //var_dump($order);exit;
                if(empty($order))
                {
                    Until::validOperate($companyId,$this);
                    
                    $order=new Order();
                    $se=new Sequence("order");
                    $order->lid = $se->nextval();
                    $order->dpid=$companyId;
                    $order->usernmae=Yii::app()->user->name;
                    $order->create_at = date('Y-m-d H:i:s',time());
                    $order->lock_status = '0';
                    $order->order_status = '1';
                    $order->site_id = $siteNo->site_id;
                    $order->number = $siteNo->number;
                    $order->is_temp = $siteNo->is_temp;
                    //var_dump($order);exit;
                    $order->save();
                }
                $allOrderTastes=  TasteClass::getOrderTasteKV($order->lid,'1',$companyId);
		//$orderProducts = OrderProduct::model()->findAll('dpid=:dpid and order_id=:orderid',array(':dpid'=>$companyId,':orderid'=>$order->order_id));
		$orderProducts = OrderProduct::getOrderProducts($order->lid,$order->dpid);
                $allOrderProductTastes=  TasteClass::getOrderTasteKV($order->lid,'2',$companyId);
                //var_dump($allOrderProductTastes);exit;
                
                $productTotal = OrderProduct::getTotal($order->lid,$order->dpid);
                //var_dump($productTotal);exit;
                if($siteNo->is_temp=='1')
                {
                    $total = array('total'=>$productTotal,'remark'=>yii::t('app','临时座：').$siteNo->site_id%1000);                    
                }else{
                    $total = Helper::calOrderConsume($order,$siteNo, $productTotal);
                }
                $order->should_total=$total['total'];
		//var_dump($order);exit;
		//var_dump($total);exit;
		//$paymentMethods = $this->getPaymentMethodList();
		$this->render('order' , array(
				'model'=>$order,
				'orderProducts' => $orderProducts,
                                'allOrderTastes'=>$allOrderTastes,
                                'allOrderProductTastes'=>$allOrderProductTastes,
                                //'orderProduct' => $orderProduct,
				'productTotal' => $productTotal ,
				'total' => $total,
				//'paymentMethods'=>$paymentMethods,
                                'typeId' => $typeId,
                                'syscallId'=>$syscallId,
                                'autoaccount'=>$autoaccount
                                //'categories' => $categories
                                //'products' => $productslist,
                                //'setlist' => $setlist
		));
	}
        
        public function actionOrderPartial(){
		$sid = Yii::app()->request->getParam('sid',0);
		$istemp = Yii::app()->request->getParam('istemp',0);
		$companyId = Yii::app()->request->getParam('companyId',0);
                $typeId = Yii::app()->request->getParam('typeId',0);
                $orderId = Yii::app()->request->getParam('orderId',0);
                $syscallId = Yii::app()->request->getParam('syscallId',0);
                $autoaccount = Yii::app()->request->getParam('autoaccount',0);
                $padId = Yii::app()->request->getParam('padId',"0000000000");
                $order=new Order();
                $siteNo=new SiteNo();
                $site=new Site();
                   
                $criteria = new CDbCriteria;
                $criteria->condition =  't.delete_flag=0 and t.dpid='.$companyId ;
                $criteria->addCondition('t.fee_type in(1,2,3)');
                $criteria->order = ' t.fee_type asc,t.lid asc';
                $feeTypes = CompanyBasicFee::model()->findAll($criteria);
                
                $db = Yii::app()->db;
                $sql = 'select t.fee_price from nb_company_basic_fee t where t.fee_type = "1" and t.dpid = '.$this->companyId;
                $ordersitefee = Yii::app()->db->createCommand($sql)->queryRow();
                ///***********insert to order feedback
                ///*************print
                if($orderId !='0')
                {
                    $order = Order::model()->find('lid=:lid and dpid=:dpid and order_status in("1","2","3")' , array(':lid'=>$orderId,':dpid'=>$companyId));
                    if(empty($order))
                    {
                        $title=yii::t('app',"该订单不存在，请输入合法订单！");
                        $backurl=$this->createUrl('default/index',array('companyId'=>$this->companyId));
                        $this->render('error' , 
                                array('backurl'=>$backurl,
                                    'title'=>$title));
                        exit;
                    }
                    $criteria = new CDbCriteria;
                    $criteria->condition =  't.dpid='.$companyId.' and t.site_id='.$order->site_id.' and t.is_temp='.$order->is_temp ;
                    $criteria->order = ' t.lid desc ';                    
                    $siteNo = SiteNo::model()->find($criteria);
                    //如果不是临时台，需要讲固定台的多个订单合并，所以要取出
                }else{
                    $criteria = new CDbCriteria;
                    $criteria->condition =  ' t.order_status in ("1","2","3") and  t.dpid='.$companyId.' and t.site_id='.$sid.' and t.is_temp='.$istemp ;
                    $criteria->order = ' t.lid desc ';
                    $order = Order::model()->find($criteria);
                    //var_dump($order);exit;
                    $criteria->condition =  ' t.status in ("1","2","3") and  t.dpid='.$companyId.' and t.site_id='.$sid.' and t.is_temp='.$istemp ;
                    $criteria->order = ' t.lid desc ';
                    $siteNo = SiteNo::model()->find($criteria);
                    //var_dump($siteNo);exit;
                }
                //var_dump($order);exit;                
                if(empty($order))
                {
                    Until::validOperate($companyId,$this);                    
                    $order=new Order();
                    $se=new Sequence("order");
                    $templid=$se->nextval();
                    $order->lid = $templid;
                    $order->account_no=Order::getAccountNo($companyId, $siteNo->site_id, $siteNo->is_temp, $templid );
                    $order->dpid=$companyId;
                    $order->username=Yii::app()->user->name;
                    $order->create_at = date('Y-m-d H:i:s',time());
                    $order->lock_status = '0';
                    $order->order_status = '1';
                    $order->site_id = $siteNo->site_id;
                    $order->number = $siteNo->number;
                    $order->is_temp = $siteNo->is_temp;
                    //var_dump($order);exit;
                    $order->save();
                }
                //检查是否有自动打印的东西，order_product product_order_status，然后有定时任务打印出来
                ////////////OrderProduct::setPauseJobs($companyId,$padId);
                //检查语音播报,然后传递到partial界面，调用语音播报
                //更新所有状态是9的为0（微信下单）,8的为3（微信支付）,并自动呼叫
                $ret9arr=OrderProduct::setOrderCall($companyId,$siteNo->site_id,$siteNo->is_temp);
                //var_dump($ret9arr);exit;
                $ret8arr=OrderProduct::setPayCall($companyId,$siteNo->site_id,$siteNo->is_temp);
                //var_dump($order); exit;
                //固定台的最大的status
                //$maxstatus=  OrderProduct::getMaxStatus($siteNo->site_id, $companyId);
                ////////取得该座位对应的所有的订单ID列表////////
                $orderlist=Order::getOrderList($companyId,$siteNo->site_id,$siteNo->is_temp);
                //var_dump($siteNo); exit;
		//var_dump($maxstatus); exit;
                $productPauseTotalarray = OrderProduct::getPauseTotalAll($orderlist,$companyId);
                //var_dump($productTotalarray);exit;
//                $productTotal=$productTotalarray["total"];
//                $originaltotal=$productTotalarray["originaltotal"];
                //////////////
                //订单已经选择的口味
                $allOrderTastes=  TasteClass::getOrderTasteKV($order->lid,$orderlist,'1',$companyId);
                //所有订单明细
                $orderProducts = OrderProduct::getOrderProducts($orderlist,$companyId);
                
//                 $orderSeatingProducts = OrderProduct::getOrderProductsByType($orderlist,$companyId,1);
//                 $orderPackingProducts = OrderProduct::getOrderProductsByType($orderlist,$companyId,2);
//                 $orderFreightProducts = OrderProduct::getOrderProductsByType($orderlist,$companyId,3);
                //var_dump($orderProducts);exit;
                //单品口味
                $allOrderProductTastes=  TasteClass::getOrderTasteKV($order->lid,$orderlist,'2',$companyId);
                $tasteidsOrderProducts=array();
                foreach($allOrderProductTastes as $orderProductTaste)
                {
                    //var_dump($orderProductTaste);
                    if(empty($tasteidsOrderProducts[$orderProductTaste['id']]))
                    {
                        $tasteidsOrderProducts[$orderProductTaste['id']]=$orderProductTaste['tasteid']."|";
                    }else{
                        $tasteidsOrderProducts[$orderProductTaste['id']]=$tasteidsOrderProducts[$orderProductTaste['id']].$orderProductTaste['tasteid']."|";
                    }
                }
                //var_dump($tasteidsOrderProducts);exit;
                $productTotalarray = OrderProduct::getOriginalTotal($orderlist,$companyId);
                //var_dump($productTotalarray);exit;
                //现价
                $nowTotal=$productTotalarray["total"];
                //原价
                $originaltotal=$productTotalarray["originaltotal"];
                //已支付
                $paytotal=OrderProduct::getPayTotalAll($orderlist,$companyId);
//                $productTotal = OrderProduct::getTotal($orderlist,$order->dpid);
                //参与折扣的总额
                $productDisTotal = OrderProduct::getDisTotal($orderlist,$order->dpid);
                //var_dump($productTotal);exit;
                if($siteNo->is_temp=='1')
                {
                    $total = array('total'=>$nowTotal,'remark'=>yii::t('app','临时座：').$siteNo->site_id%1000);                    
                }else{
                    $total = Helper::calOrderConsume($order,$siteNo, $nowTotal);
                }
                $order->should_total=$originaltotal;
		$order->reality_total=$total['total'];
                $order->pay_total=$paytotal;
                //$paymentMethods = $this->getPaymentMethodList();
                $tastegroups= TasteClass::getAllOrderTasteGroup($companyId, '1');
                //所有可选择的全单口味
                $orderTastes=  TasteClass::getOrderTaste($orderlist, '1', $companyId);
                $tasteMemo = TasteClass::getOrderTasteMemo($orderlist, '1', $companyId);
                //var_dump(array_column($allOrderProductTastes, "lid"));exit;
                //var_dump($tasteMemo);exit;
		$this->renderPartial('orderPartial' , array(
				'model'=>$order,
				'orderProducts' => $orderProducts,
// 				'orderSeatingProducts' =>$orderSeatingProducts,
// 				'orderPackingProducts' =>$orderPackingProducts,
// 				'orderFreightProducts' =>$orderFreightProducts,
                                'allOrderTastes'=>$allOrderTastes,
                                'allOrderProductTastes'=>$allOrderProductTastes,
                                //'orderProduct' => $orderProduct,
				//'productTotal' => $productTotal ,
                                'productDisTotal'=>$productDisTotal,
				'total' => $total,
                                'orderlist'=>$orderlist,
                                //'maxstatus'=>$maxstatus,
				//'paymentMethods'=>$paymentMethods,
                                'typeId' => $typeId,
                                'syscallId'=>$syscallId,
                                'autoaccount'=>$autoaccount,
                                'tastegroups'=>$tastegroups,
                                'orderTastes'=>$orderTastes,
                                'tasteMemo'=>$tasteMemo,
                                'tasteidsOrderProducts'=>$tasteidsOrderProducts,
                                'productPauseTotalarray'=>$productPauseTotalarray,
                                'ordersitefee'=>$ordersitefee,
                                'feeTypes'=> $feeTypes,
                                //'categories' => $categories
                                //'products' => $productslist,
                                //'setlist' => $setlist
		));
	}
//
// 修改人数。。。（未修改） 
// 	public function actionStore(){
// 		$sid = Yii::app()->request->getParam('sid',0);
// 		$istemp = Yii::app()->request->getParam('istemp',0);
// 		$companyId = Yii::app()->request->getParam('companyId',0);
//                 $typeId = Yii::app()->request->getParam('typeId',0);
//                 $orderId = Yii::app()->request->getParam('orderId',0);
//                 $syscallId = Yii::app()->request->getParam('syscallId',0);
//                 $autoaccount = Yii::app()->request->getParam('autoaccount',0);
//                 $padId = Yii::app()->request->getParam('padId',"0000000000");
//                 $siteNumber = Yii::app()->request->getParam('siteNumber',"0000000000");
// 		$dpid = $this->companyId;
// 		$is_sync = DataSync::getInitSync();
	
// 		$db = Yii::app()->db;
// 		$transaction = $db->beginTransaction();
// 		try{
// 			$se=new Sequence("promotion_activity_detail");
// 			$lid = $se->nextval();
// 			//$create_at = date('Y-m-d H:i:s',time());
// 			//$update_at = date('Y-m-d H:i:s',time());
	
// 			//$sql = 'delete from nb_promotion_activity_detail where promotion_lid = '.$id.' and dpid='.$dpid.' and activity_lid='.$activityID;
// 			//var_dump($sql);exit;
// 			$sql = 'update nb_order set number ='.$siteNumber.', is_sync ='.$is_sync.' where promotion_lid = '.$id.' and dpid='.$dpid.' and activity_lid='.$activityID;
	
// 			$command=$db->createCommand($sql);
// 			$command->execute();
			
// 			$transaction->commit(); //提交事务会真正的执行数据库操作
// 			Yii::app()->end(json_encode(array("status"=>"success")));
// 			return true;
// 		}catch (Exception $e) {
// 			$transaction->rollback(); //如果操作失败, 数据回滚
// 			Yii::app()->end(json_encode(array("status"=>"fail")));
// 			return false;
// 		}
	
	
			
// 	}
	
        public function actionGetFailPrintjobs(){
		$companyId = Yii::app()->request->getParam('companyId',"0");
                $orderId = Yii::app()->request->getParam('orderId',"0");
                $jobId=Yii::app()->request->getParam('jobId',"0");
                
                if($jobId!="0")
                {
                    $printjobsql="update nb_order_printjobs set finish_flag=1".
                                " where dpid=".$companyId." and orderid=".$orderId.
                                " and jobid in(".$jobId.")";
                    Yii::app()->db->createCommand($printjobsql)->execute();
                }
                
                $order=Order::model()->find(" dpid=".$companyId." and lid=".$orderId);
                //var_dump($tasteidsOrderProducts);exit;
                $orderlist=Order::getOrderList($companyId,$order->site_id,$order->is_temp);
                $productTotalarray = OrderProduct::getOriginalTotal($orderlist,$companyId);
                //var_dump($productTotalarray);exit;
                //现价
                $nowTotal=$productTotalarray["total"];
                //原价
                $originaltotal=$productTotalarray["originaltotal"];
                //已支付
                $paytotal=OrderProduct::getPayTotalAll($orderlist,$companyId);
//                $productTotal = OrderProduct::getTotal($orderlist,$order->dpid);
                //参与折扣的总额
                $productDisTotal = OrderProduct::getDisTotal($orderlist,$order->dpid);
                //var_dump($productTotal);exit;
                $criteria = new CDbCriteria;
                $criteria->condition =  't.dpid='.$companyId.' and t.orderid='.$orderId.' and t.finish_flag=0';
                $criteria->order = ' t.lid desc ';                    
                //$siteNo = SiteNo::model()->find($criteria);
                $orderprintjobs=  OrderPrintjobs::model()->with("printer")->findAll($criteria);
                //var_dump($orderprintjobs);exit;
                $order_status=Yii::app()->db->createCommand("select order_status from nb_order where dpid=".$companyId." and lid=".$orderId)->queryScalar();
                $this->renderPartial('orderPrintjobs' , array(
				'orderPrintjobs'=>$orderprintjobs,
				'dpid' => $companyId,
                                'orderid'=>$orderId,
                                'order_status'=>$order_status,
                                'nowTotal'=>$nowTotal,
                                'originaltotal'=>$originaltotal,
                                'paytotal'=>$paytotal,
                                'productDisTotal'=>$productDisTotal
		));
	}

        public function actionSaveFailPrintjobs()
	{
		$jobId = Yii::app()->request->getParam('jobId',"0");
                $companyId = Yii::app()->request->getParam('companyId',"0000000000");
                //$address = Yii::app()->request->getParam('address',"0");
                $orderId = Yii::app()->request->getParam('orderId',"0");
                $db=Yii::app()->db;
                if($jobId!="0")
                {
                    $printjobsql="update nb_order_printjobs set finish_flag=1".
                                " where dpid=".$companyId." and orderid=".$orderId.
                                " and jobid in(".$jobId.")";
                    $db->createCommand($printjobsql)->execute();                        
                }
                Yii::app()->end(json_encode(array("status"=>true)));
	}
        
        /*
         * 挂单
         * 传递数据保存，
         * 保存前检查库存
         * 更新库存
         * 
         */
        public function actionOrderPause(){
		$companyId = Yii::app()->request->getParam('companyId',0);
                $orderId = Yii::app()->request->getParam('orderid',"0");
                $orderStatus = Yii::app()->request->getParam('orderstatus',"0");
                $productList = Yii::app()->request->getPost('productlist',"0");
                $orderTasteIds=Yii::app()->request->getPost('ordertasteids',"0");//只传递新追加的
                $orderTasteMemo=Yii::app()->request->getPost('ordertastememo',"0");
                //$orderList=Yii::app()->request->getPost('orderlist',"0");
                $callId=Yii::app()->request->getParam('callId',"0");
                
                //返回json挂单成功或失败
                //如果orderId是0，表示是临时台，
                //要开台、生成新的订单//暂时不处理
                //Yii::app()->end(json_encode(array('status'=>false,'msg'=>"test1")));
                if(!Until::validOperateJson($companyId, $this))
                {
                    Yii::app()->end(json_encode(array('status'=>false,'msg'=>"云端不能操作本地数据")));                    
                }
                if($orderId =="0")
                {
                    //临时台，没有开过台的，
                    //要开台，
                    //生成新的订单，
                    //然后才有后面的插入！！
                    //var_dump($order);exit;
    //                    if(empty($order))
    //                    {
    //                        Until::validOperate($companyId,$this);
    //
    //                        $order=new Order();
    //                        $se=new Sequence("order");
    //                        $order->lid = $se->nextval();
    //                        $order->dpid=$companyId;
    //                        $order->create_at = date('Y-m-d H:i:s',time());
    //                        $order->lock_status = '0';
    //                        $order->order_status = '1';
    //                        $order->site_id = $siteNo->site_id;
    //                        $order->number = $siteNo->number;
    //                        $order->is_temp = $siteNo->is_temp;
    //                        //var_dump($order);exit;
    //                        $order->save();
    //                    }
                }
                //$syscallId = Yii::app()->request->getParam('syscallId',0);
                //$autoaccount = Yii::app()->request->getParam('autoaccount',0);
                $order=new Order();
                $siteNo=new SiteNo();
                $site=new Site();
                ///***********insert to order feedback
                ///*************print
                if($orderId !='0')
                {
                    $order = Order::model()->with("company")->find('t.lid=:lid and t.dpid=:dpid and t.order_status in("1","2","3")' , array(':lid'=>$orderId,':dpid'=>$companyId));
                    if(empty($order))
                    {
                        return json_encode(array('status'=>false,'msg'=>"该订单不存在"));
                    }
                    $criteria = new CDbCriteria;
                    $criteria->condition =  't.dpid='.$companyId.' and t.site_id='.$order->site_id.' and t.is_temp='.$order->is_temp ;
                    $criteria->order = ' t.lid desc ';                    
                    $siteNo = SiteNo::model()->find($criteria);
                    //order site 和 siteno都需要更新状态 所以要取出来
                    if($order->is_temp=="0")
                    {
                        $criteria2 = new CDbCriteria;
                        $criteria2->condition =  't.dpid='.$companyId.' and t.lid='.$order->site_id ;
                        $criteria2->order = ' t.lid desc ';                    
                        $site = Site::model()->with("siteType")->find($criteria2);
                    }
                }
                $orderList=Order::getOrderList($companyId,$siteNo->site_id,$siteNo->is_temp);
                //返回json挂单成功或失败
                Yii::app()->end(json_encode(OrderList::createOrder($companyId,$orderId,$orderList,$orderStatus,$productList,$orderTasteIds,$orderTasteMemo,$callId,$order,$site,$siteNo)));
	}
        
        /*
         * 挂单
         * 传递数据保存，
         * 保存前检查库存
         * 更新库存
         * 
         */
        public function actionOrderKitchen(){
		$companyId = Yii::app()->request->getParam('companyId',0);
                $orderId = Yii::app()->request->getParam('orderid',"0");
                $orderStatus = Yii::app()->request->getParam('orderstatus',"0");
                $productList = Yii::app()->request->getPost('productlist',"0");
                $orderTasteIds=Yii::app()->request->getPost('ordertasteids',"0");//只传递新追加的
                $orderTasteMemo=Yii::app()->request->getPost('ordertastememo',"0");
                //$orderList=Yii::app()->request->getPost('orderlist',"0");
                $callId=Yii::app()->request->getParam('callId',"0");
                //返回json挂单成功或失败gi
                //如果orderId是0，表示是临时台，
                //要开台、生成新的订单//暂时不处理
                ///Yii::app()->end(json_encode(array('status'=>false,'msg'=>$productList)));
                if(!Until::validOperateJson($companyId, $this))
                {
                    Yii::app()->end(json_encode(array('status'=>false,'msg'=>"云端不能操作本地数据")));                    
                }
                if($orderId =="0")
                {
                    //临时台，没有开过台的，
                    //要开台，
                    //生成新的订单，
                    //然后才有后面的插入！！
                    //var_dump($order);exit;
    //                    if(empty($order))
    //                    {
    //                        Until::validOperate($companyId,$this);
    //
    //                        $order=new Order();
    //                        $se=new Sequence("order");
    //                        $order->lid = $se->nextval();
    //                        $order->dpid=$companyId;
    //                        $order->create_at = date('Y-m-d H:i:s',time());
    //                        $order->lock_status = '0';
    //                        $order->order_status = '1';
    //                        $order->site_id = $siteNo->site_id;
    //                        $order->number = $siteNo->number;
    //                        $order->is_temp = $siteNo->is_temp;
    //                        //var_dump($order);exit;
    //                        $order->save();
    //                    }
                }
                //$syscallId = Yii::app()->request->getParam('syscallId',0);
                //$autoaccount = Yii::app()->request->getParam('autoaccount',0);
                $order=new Order();
                $siteNo=new SiteNo();
                $site=new Site();
                ///***********insert to order feedback
                ///*************print
                if($orderId !='0')
                {
                    $order = Order::model()->with('company')->find(' t.lid=:lid and t.dpid=:dpid and t.order_status in(1,2,3)' , array(':lid'=>$orderId,':dpid'=>$companyId));
                    //Yii::app()->end(json_encode(array('status'=>false,'msg'=>"234")));                    
                    if(empty($order))
                    {
                        Yii::app()->end(json_encode(array('status'=>false,'msg'=>"该订单不存在")));
                    }
                    $criteria = new CDbCriteria;
                    $criteria->condition =  't.dpid='.$companyId.' and t.site_id='.$order->site_id.' and t.is_temp='.$order->is_temp ;
                    $criteria->order = ' t.lid desc ';                    
                    $siteNo = SiteNo::model()->find($criteria);
                    //order site 和 siteno都需要更新状态 所以要取出来
                    if($order->is_temp=="0")
                    {
                        $criteria2 = new CDbCriteria;
                        $criteria2->condition =  't.dpid='.$companyId.' and t.lid='.$order->site_id ;
                        $criteria2->order = ' t.lid desc ';                    
                        $site = Site::model()->with("siteType")->find($criteria2);
                    }
                }
                $orderList=Order::getOrderList($companyId,$siteNo->site_id,$siteNo->is_temp);
                //Yii::app()->end(json_encode(array('status'=>false,'msg'=>$orderList)));
                $savejson=OrderList::createOrder($companyId,$orderId,$orderList,$orderStatus,$productList,$orderTasteIds,$orderTasteMemo,$callId,$order,$site,$siteNo);
                //$jobids=array();
                //Yii::app()->end(json_encode($savejson));
//                if(!$savejson["status"])
//                {
//                    $ret=json_encode($savejson);
//                }else{
//                    $ret=  json_encode(Helper::printKitchenAll2($order,$site,$siteNo,false));
//                }
                Yii::app()->end(json_encode($savejson));
	}
        
        public function actionOrderAccountSure(){
		$companyId = Yii::app()->request->getParam('companyId',0);
                $orderId = Yii::app()->request->getParam('orderId',"0");
                $padId = Yii::app()->request->getParam('padId',"0");
                //$payShouldAccount=Yii::app()->request->getPost('payShouldAccount',"0");
                $paycashaccount = floatval(str_replace(",","",Yii::app()->request->getPost('paycashaccount',"0")));
                
                $payothers = floatval(str_replace(",","",Yii::app()->request->getPost('payothers',"0")));
                $payotherdetail=Yii::app()->request->getPost('payotherdetail',"");
                
                $paymemberaccount = floatval(str_replace(",","",Yii::app()->request->getPost('paymemberaccount',"0")));
                $cardno = Yii::app()->request->getParam('cardno',"0000000000");
                $cardtotal=Yii::app()->request->getPost('cardtotal',0);
                
                $payunionaccount = floatval(str_replace(",","",Yii::app()->request->getPost('payunionaccount',"0")));
                
                $payshouldaccount = floatval(str_replace(",","",Yii::app()->request->getPost('payshouldaccount',"0")));
                $payoriginaccount = floatval(str_replace(",","",Yii::app()->request->getPost('payoriginaccount',"0")));
                $ordermemo = Yii::app()->request->getPost('ordermemo',"0");
                ///////////////////////
                $notpaydetail = Yii::app()->request->getPost('notpaydetail',"0");
                $order=new Order();
                $printList=array();
                //var_dump($notpaydetail);exit;
                //Yii::app()->end(json_encode(array('status'=>false,'msg'=>"111")));
                //echo $orderId;exit;
                if($orderId !='0')
                {
                    $order = Order::model()->with('company')->find(' t.lid=:lid and t.dpid=:dpid and t.order_status in(1,2,3)' , array(':lid'=>$orderId,':dpid'=>$companyId));
                    //Yii::app()->end(json_encode(array('status'=>false,'msg'=>"234")));                    
                    if(empty($order))
                    {
                        Yii::app()->end(json_encode(array('status'=>false,'msg'=>"1111该订单不存在")));
                    }
                    //$productTotal = OrderProduct::getTotal($order->lid,$order->dpid);
                    $criteria = new CDbCriteria;
                    $criteria->condition =  't.dpid='.$companyId.' and t.site_id='.$order->site_id.' and t.is_temp='.$order->is_temp ;
                    $criteria->order = ' t.lid desc ';                    
                    $siteNo = SiteNo::model()->find($criteria);
                    $orderList=Order::getOrderList($companyId, $order->site_id, $order->is_temp);
                    //var_dump($siteNo);exit;
                    $productTotalarray = OrderProduct::getOriginalTotal($orderList,$companyId);
                    //var_dump($productTotalarray);exit;
                    //现价
                    $nowTotal=$productTotalarray["total"];
//                    //原价
                    $originaltotal=$productTotalarray["originaltotal"];
                    //已支付
                    $paytotal=OrderProduct::getPayTotalAll($orderList,$companyId);
//                    $productTotal = OrderProduct::getTotal($orderlist,$order->dpid);
                    //参与折扣的总额
                    $productDisTotal = OrderProduct::getDisTotal($orderList,$order->dpid);
                    //var_dump($productTotal);exit;
//                    if($siteNo->is_temp=='1')
//                    {
//                        $total = array('total'=>$nowTotal,'remark'=>yii::t('app','临时座：').$siteNo->site_id%1000);                    
//                    }else{
//                        $total = Helper::calOrderConsume($order,$siteNo, $nowTotal);
//                    }
                    $order->should_total=$originaltotal;
                    $order->reality_total=$payshouldaccount;//$total['total'];实际应该支付的
                    $order->pay_total=$paytotal;
                    $order->pay_discount_total=$productDisTotal;
                    $order->account_cash=$paycashaccount;
                    $order->account_membercard=$cardno."|".$paymemberaccount."|".$cardtotal;
                    $order->account_union=$payunionaccount;
                    $order->account_otherdetail=$payotherdetail;
                    $order->notpaydetail=$notpaydetail;
                }
                //Yii::app()->end(json_encode(array('status'=>false,'msg'=>"111")));
                $pad=Pad::model()->with('printer')->find(' t.dpid=:dpid and t.lid=:lid',array(':dpid'=>$order->dpid,'lid'=>$padId));
            	 //前面加 barcode
                $precode="";//"1D6B450B".strtoupper(implode('',unpack('H*', 'A'.$order->lid)))."0A".strtoupper(implode('',unpack('H*', 'A'.$order->lid)))."0A";
                $orderProducts = OrderProduct::getHasOrderProductsAll($orderList,$order->dpid);
                $memo="结账单";
                $printList = Helper::printList($order,$orderProducts , $pad,$precode,"0",$memo,$cardtotal);
                //var_dump($printList);exit;
                //Yii::app()->end(json_encode($printList));
                $this->renderPartial('orderaccountsure' , array(
				'printList'=>$printList
		));
        }
        
        public function actionOrderPrintlist(){
		$companyId = Yii::app()->request->getParam('companyId',0);
                $orderId = Yii::app()->request->getParam('orderId',"0");
                $padId = Yii::app()->request->getParam('padId',"0");
                $payShouldAccount=Yii::app()->request->getParam('payShouldAccount',"0");
                $cardtotal=Yii::app()->request->getParam('cardtotal',0);
                $order=new Order();
                //Yii::app()->end(json_encode(array('status'=>false,'msg'=>"111")));
                if($orderId !='0')
                {
                    $order = Order::model()->with('company')->find(' t.lid=:lid and t.dpid=:dpid and t.order_status in(1,2,3)' , array(':lid'=>$orderId,':dpid'=>$companyId));
                    //Yii::app()->end(json_encode(array('status'=>false,'msg'=>"234")));                    
                    if(empty($order))
                    {
                        Yii::app()->end(json_encode(array('status'=>false,'msg'=>"该订单不存在")));
                    }
                    //$productTotal = OrderProduct::getTotal($order->lid,$order->dpid);
                    $criteria = new CDbCriteria;
                    $criteria->condition =  't.dpid='.$companyId.' and t.site_id='.$order->site_id.' and t.is_temp='.$order->is_temp ;
                    $criteria->order = ' t.lid desc ';                    
                    $siteNo = SiteNo::model()->find($criteria);
                    $orderList=Order::getOrderList($companyId, $order->site_id, $order->is_temp);
                
                    $productTotalarray = OrderProduct::getOriginalTotal($orderList,$companyId);
                    //var_dump($productTotalarray);exit;
                    //现价
                    $nowTotal=$productTotalarray["total"];
                    //原价
                    $originaltotal=$productTotalarray["originaltotal"];
                    //已支付
                    $paytotal=OrderProduct::getPayTotalAll($orderList,$companyId);
    //                $productTotal = OrderProduct::getTotal($orderlist,$order->dpid);
                    //参与折扣的总额
                    $productDisTotal = OrderProduct::getDisTotal($orderList,$order->dpid);
                    //var_dump($productTotal);exit;
                    if($siteNo->is_temp=='1')
                    {
                        $total = array('total'=>$nowTotal,'remark'=>yii::t('app','临时座：').$siteNo->site_id%1000);                    
                    }else{
                        $total = Helper::calOrderConsume($order,$siteNo, $nowTotal);
                    }
                    $order->should_total=$originaltotal;
                    $order->reality_total=$payShouldAccount;//$total['total'];
                    $order->pay_total=$paytotal;
                    $order->pay_discount_total=$productDisTotal;
                    
                }
                //Yii::app()->end(json_encode(array('status'=>false,'msg'=>"111")));
                $pad=Pad::model()->with('printer')->find(' t.dpid=:dpid and t.lid=:lid',array(':dpid'=>$order->dpid,'lid'=>$padId));
            	 //前面加 barcode
                $precode="";//"1D6B450B".strtoupper(implode('',unpack('H*', 'A'.$order->lid)))."0A".strtoupper(implode('',unpack('H*', 'A'.$order->lid)))."0A";
                $orderProducts = OrderProduct::getHasOrderProductsAll($orderList,$order->dpid);
                $memo="预结单";
                $printList = Helper::printList($order,$orderProducts , $pad,$precode,"0",$memo,$cardtotal);
                Yii::app()->end(json_encode($printList));
        }
        
        public function actionPausePrintlist(){
		$companyId = Yii::app()->request->getParam('companyId',0);
                $orderId = Yii::app()->request->getParam('orderId',"0");
                $padId = Yii::app()->request->getParam('padId',"0");
                //$orderList=Yii::app()->request->getParam('orderList',"0");
                $order=new Order();
                if($orderId !='0')
                {
                    $order = Order::model()->with('company')->find(' t.lid=:lid and t.dpid=:dpid and t.order_status in(1,2,3)' , array(':lid'=>$orderId,':dpid'=>$companyId));
                    if(empty($order))
                    {
                        Yii::app()->end(json_encode(array('status'=>false,'msg'=>"该订单不存在")));
                    }
                    
                    
                    //$productTotal = OrderProduct::getTotal($order->lid,$order->dpid);
                    $criteria = new CDbCriteria;
                    $criteria->condition =  't.dpid='.$companyId.' and t.site_id='.$order->site_id.' and t.is_temp='.$order->is_temp ;
                    $criteria->order = ' t.lid desc ';                    
                    $siteNo = SiteNo::model()->find($criteria);
                    $orderList=Order::getOrderList($companyId,$siteNo->site_id,$siteNo->is_temp);
                    $productTotalarray = OrderProduct::getPauseTotalAll($orderList,$companyId);
                    //var_dump($productTotalarray);exit;
                    $productTotal=$productTotalarray["total"];
                    $originaltotal=$productTotalarray["originaltotal"]; 
                    if($order->is_temp=='1')
                    {
                        $total = array('total'=>$productTotal,'remark'=>yii::t('app','临时座：').$siteNo->site_id%1000);                    
                    }else{
                        $total = Helper::calOrderConsume($order,$siteNo, $productTotal);
                    }
                    $order->should_total=$originaltotal;
                    $order->reality_total=$total["total"];
                }
                
                //Yii::app()->end(json_encode(array('status'=>false,'msg'=>"111")));
                $pad=Pad::model()->with('printer')->find(' t.dpid=:dpid and t.lid=:lid',array(':dpid'=>$order->dpid,'lid'=>$padId));
            	 //前面加 barcode
                $precode="";//"1D6B450B".strtoupper(implode('',unpack('H*', 'A'.$order->lid)))."0A".strtoupper(implode('',unpack('H*', 'A'.$order->lid)))."0A";
                $orderProducts = OrderProduct::getHasPauseProductsAll($orderList,$order->dpid);
                $cardtotal=0;
                $memo="挂单清单";
                 Yii::app()->end(json_encode(array('status'=>false,'msg'=>  count($orderProducts))));
                $printList = Helper::printList($order,$orderProducts ,$pad,$precode,"0",$memo,$cardtotal);
                Yii::app()->end(json_encode($printList));
        }
        
        public function actionMemberCardPassword(){
		$companyId = Yii::app()->request->getParam('companyId',"0");
                $password = Yii::app()->request->getParam('passWord',"0");
                $cardno = Yii::app()->request->getParam('cardno',"0");
                $db = Yii::app()->db;
                $sql;
                if(empty($password))
                {
                    $sql = "SELECT all_money from nb_member_card where dpid=".$companyId." and haspassword=0 and (rfid='".$cardno."' or selfcode='".$cardno."') and delete_flag=0 limit 0,1";
                }else{
                    $sql = "SELECT all_money from nb_member_card where dpid=".$companyId." and password_hash='".MD5($password)."' and (rfid='".$cardno."' or selfcode='".$cardno."') and delete_flag=0 limit 0,1";
                }
                $command=$db->createCommand($sql);
                $nowval= $command->queryScalar();
                //var_dump($nowval);exit;
                $ret;
                if($nowval===false)
                {
                    $ret=json_encode(array('status'=>false,'msg'=>$nowval));                    
                }else{
                    $ret=json_encode(array('status'=>true,'msg'=>$nowval));
                }
                Yii::app()->end($ret);                
	}
        
        public function actionOrderAccount(){
		$companyId = Yii::app()->request->getParam('companyId',"0");
                $orderid = Yii::app()->request->getParam('orderid',"0");
                $orderstatus = Yii::app()->request->getParam('orderstatus',"0");
                $paycashaccount = floatval(str_replace(",","",Yii::app()->request->getPost('paycashaccount',"0")));
                $payothers = floatval(str_replace(",","",Yii::app()->request->getPost('payothers',"0")));
                $paymemberaccount = floatval(str_replace(",","",Yii::app()->request->getPost('paymemberaccount',"0")));
                $payunionaccount = floatval(str_replace(",","",Yii::app()->request->getPost('payunionaccount',"0")));
                $payshouldaccount = floatval(str_replace(",","",Yii::app()->request->getPost('payshouldaccount',"0")));
                $payoriginaccount = floatval(str_replace(",","",Yii::app()->request->getPost('payoriginaccount',"0")));
                $notpaydetail = Yii::app()->request->getPost('notpaydetail',"0");
                $cardno = Yii::app()->request->getParam('cardno',"0000000000");
                $ordermemo = Yii::app()->request->getPost('ordermemo',"0");
                $payotherdetail=Yii::app()->request->getPost('payotherdetail',"");
                //存数order order_pay 0现金，4会员卡，5银联                         
                //写入会员卡消费记录，会员卡总额减少
                $ret;
                $time=date('Y-m-d H:i:s',time());
                $db = Yii::app()->db;
                if(!Until::validOperateJson($companyId, $this))
                {
                    Yii::app()->end(json_encode(array('status'=>false,'msg'=>"云端不能操作本地数据")));                    
                }
                if(Yii::app()->user->role > '2')
                {
                    Yii::app()->end(json_encode(array('status'=>false,'msg'=>"您没有权限操作此功能")));  
                }
                $transaction = $db->beginTransaction();
                try{
                    $order=Order::model()->with("company")->find(" t.lid=:lid and t.dpid=:dpid",array(":lid"=>$orderid,":dpid"=>$companyId));
//                    if($order->order_status > "3")
//                    {
//                        $transaction->rollback();
//                        $ret=json_encode(array('status'=>false,'msg'=>"已经结单"));
//                        Yii::app()->end($ret);
//                    }
                    $orderList=Order::getOrderList($companyId, $order->site_id, $order->is_temp);
                    $accountNo=Order::getAccountNo($companyId, $order->site_id, $order->is_temp,$order->lid);
                    //var_dump($accountNo);exit;
//                    $order->should_total=$payoriginaccount;
//                    $order->reality_total=$payshouldaccount;
//                    $order->update_at=$time;
//                    $order->order_status=$orderstatus;
//                    $order->remark=$order->remark+$ordermemo;
//                    $order->save();
                    $ordersql="update nb_order set order_status=".$orderstatus.",remark='".$ordermemo
                            ."' where dpid=".$companyId." and site_id=".$order->site_id
                            ." and is_temp=".$order->is_temp." and order_status in ('1','2','3')";
                    $db->createCommand($ordersql)->execute();
                    
                    $ordersql="update nb_order_product set product_order_status=2" 
                            ." where dpid=".$companyId." and order_id in ( ".$orderList
                            .") and product_order_status=1 and delete_flag=0";
                    $db->createCommand($ordersql)->execute();
//                    $criteria = new CDbCriteria;
//                    $criteria->condition =  't.dpid='.$companyId.' and t.site_id='.$order->site_id.' and t.is_temp='.$order->is_temp ;
//                    $criteria->order = ' t.lid desc ';                    
//                    $siteNo = SiteNo::model()->find($criteria);
//                    $siteNo->status=$orderstatus;
//                    $siteNo->save();
                    //为了删除脏数据，这里用全部的update
                    $sitenosql="update nb_site_no set status=".$orderstatus.
                            " where dpid=".$companyId." and site_id=".$order->site_id.
                            " and is_temp=".$order->is_temp." and status in ('1','2','3')";
                    $db->createCommand($sitenosql)->execute();
                                        
                    //order site 和 siteno都需要更新状态 所以要取出来
                    if($order->is_temp=="0")
                    {
                        $criteria2 = new CDbCriteria;
                        $criteria2->condition =  't.dpid='.$companyId.' and t.lid='.$order->site_id ;
                        $criteria2->order = ' t.lid desc ';                    
                        $site = Site::model()->with("siteType")->find($criteria2);
                        $site->status=$orderstatus;
                        $site->save();
                    }
                    $se=new Sequence("order_pay");
                    //Yii::app()->end(json_encode(array('status'=>false,'msg'=>"测试啊")));
                    if($paycashaccount>0)
                    {
                        $orderPayId = $se->nextval();
                        //插入一条
                        $orderPayData = array(
                                            'lid'=>$orderPayId,
                                            'dpid'=>$companyId,
                                            'create_at'=>$time,
                                            'order_id'=>$orderid,
                                            'update_at'=>$time,
                                            'account_no'=>$accountNo,
                                            'pay_amount'=>$paycashaccount,
                                            'paytype'=>"0",
                                            'payment_method_id'=>"0000000000",
                                            'remark'=>'现金付款',//'product_order_status'=>$orderProductStatus,
                                            );
                        $db->createCommand()->insert('nb_order_pay',$orderPayData);
                    }
                    
                    if($paymemberaccount>0)
                    {
                        $membercard= MemberCard::model()->find(' dpid=:dpid and (rfid =:rfid or selfcode =:selfcode) and delete_flag =0',
                                array(":dpid"=>$companyId,":rfid"=>$cardno,":selfcode"=>$cardno));
//                        $ret=json_encode(array('status'=>false,'msg'=>$paymemberaccount ));
//                            Yii::app()->end($ret);
                
                        if($membercard->all_money >= $paymemberaccount)
                        {
                            $membercard->all_money=$membercard->all_money-$paymemberaccount;
                            $membercard->save();
                        }else{
                            $transaction->rollback();
                            $ret=json_encode(array('status'=>false,'msg'=>"会员卡余额不足"));
                            Yii::app()->end($ret);
                        }
                        $orderPayId = $se->nextval();
                        //插入一条
                        $orderPayData = array(
                                            'lid'=>$orderPayId,
                                            'dpid'=>$companyId,
                                            'create_at'=>$time,
                                            'order_id'=>$orderid,
                                            'update_at'=>$time,
                                            'account_no'=>$accountNo,
                                            'pay_amount'=>$paymemberaccount,
                                            'paytype'=>"4",
                                            'payment_method_id'=>$cardno,
                                            'remark'=>'会员卡付款',//'product_order_status'=>$orderProductStatus,
                                            );
                        $db->createCommand()->insert('nb_order_pay',$orderPayData);                        
                        
                    }
                    if($payunionaccount>0)
                    {
                        $orderPayId = $se->nextval();
                        //插入一条
                        $orderPayData = array(
                                            'lid'=>$orderPayId,
                                            'dpid'=>$companyId,
                                            'create_at'=>$time,
                                            'order_id'=>$orderid,
                                            'update_at'=>$time,
                                            'account_no'=>$accountNo,
                                            'pay_amount'=>$payunionaccount,
                                            'paytype'=>"5",
                                            'payment_method_id'=>"0000000000",
                                            'remark'=>'银联卡付款',//'product_order_status'=>$orderProductStatus,
                                            );
                        $db->createCommand()->insert('nb_order_pay',$orderPayData);
                    }
//                    if($payothers>0)
//                    {
//                        $orderPayId = $se->nextval();
//                        //插入一条
//                        $orderPayData = array(
//                                            'lid'=>$orderPayId,
//                                            'dpid'=>$companyId,
//                                            'create_at'=>$time,
//                                            'order_id'=>$orderid,
//                                            'update_at'=>$time,
//                                            'pay_amount'=>$payothers,
//                                            'paytype'=>"3",
//                                            'payment_method_id'=>"3990000000",
//                                            'remark'=>'大众点评临时',//'product_order_status'=>$orderProductStatus,
//                                            );
//                        $db->createCommand()->insert('nb_order_pay',$orderPayData);
//                    }
                    if(strlen($payotherdetail)>0)
                    {
                        $detailarr=explode("|",$payotherdetail);
                        foreach ($detailarr as $da)
                        {
                            $daarr=explode(",",$da);
                            if($daarr[0]!="0000000000" && floatval($daarr[1])>0)
                            {
                                $orderPayId = $se->nextval();
                                //插入一条
                                $orderPayData = array(
                                                    'lid'=>$orderPayId,
                                                    'dpid'=>$companyId,
                                                    'create_at'=>$time,
                                                    'order_id'=>$orderid,
                                                    'update_at'=>$time,
                                                    'account_no'=>$accountNo,
                                                    'pay_amount'=>$daarr[1],
                                                    'paytype'=>"3",
                                                    'payment_method_id'=>$daarr[0],
                                                    'remark'=>'其他支付',//'product_order_status'=>$orderProductStatus,
                                                    );
                                $db->createCommand()->insert('nb_order_pay',$orderPayData);                                
                                //
                            }                            
                        }
                    }
                    //插入优惠的各项数据
                    $sedis=new Sequence("order_account_discount");
                    //Yii::app()->end(json_encode(array('status'=>false,'msg'=>"accountNo:".$accountNo)));
                    if(strlen($notpaydetail)>0)
                    {
                        $daarr=explode("|",$notpaydetail);
                        //折扣
                        if($daarr[0]!="0000000000")
                        {
                            $orderAccountDisId = $sedis->nextval();
                            //插入一条
                            $orderAccountDis = array(
                                                'lid'=>$orderAccountDisId,
                                                'dpid'=>$companyId,
                                                'create_at'=>$time,
                                                'update_at'=>$time,
                                                'order_id'=>$orderid,
                                                'account_no'=>$accountNo,
                                                'discount_type'=>"1",
                                                'discount_id'=>$daarr[0],
                                                'discount_money'=>$daarr[2],
                                                'delete_flag'=>'0',
                                                'is_sync'=>  DataSync::getInitSync(),                                                
                                                );
                            $db->createCommand()->insert('nb_order_account_discount',$orderAccountDis);                                
                            //
                        }
                        //减价
                        if(floatval($daarr[3])>0)
                        {
                            $orderAccountDisId = $sedis->nextval();
                            //插入一条
                            $orderAccountDis = array(
                                                'lid'=>$orderAccountDisId,
                                                'dpid'=>$companyId,
                                                'create_at'=>$time,
                                                'update_at'=>$time,
                                                'order_id'=>$orderid,
                                                'account_no'=>$accountNo,
                                                'discount_type'=>"2",
                                                'discount_id'=>"0000000000",
                                                'discount_money'=>$daarr[3],
                                                'delete_flag'=>'0',
                                                'is_sync'=>  DataSync::getInitSync(),                                                
                                                );
                            $db->createCommand()->insert('nb_order_account_discount',$orderAccountDis);                                 
                            //
                        }
                        //抹零
                        if(floatval($daarr[4])>0)
                        {
                            $orderAccountDisId = $sedis->nextval();
                            //插入一条
                            $orderAccountDis = array(
                                                'lid'=>$orderAccountDisId,
                                                'dpid'=>$companyId,
                                                'create_at'=>$time,
                                                'update_at'=>$time,
                                                'order_id'=>$orderid,
                                                'account_no'=>$accountNo,
                                                'discount_type'=>"0",
                                                'discount_id'=>"0000000000",
                                                'discount_money'=>$daarr[4],
                                                'delete_flag'=>'0',
                                                'is_sync'=>  DataSync::getInitSync(),                                                
                                                );
                            $db->createCommand()->insert('nb_order_account_discount',$orderAccountDis);                                 
                            //
                        }
                    }
                    WxScanLog::invalidScene($companyId,$order->site_id);
                    $transaction->commit();
                    
                    $ret=json_encode(array('status'=>true,'msg'=>"结单成功"));
                } catch (Exception $ex) {
                    $transaction->rollback();
                    $ret=json_encode(array('status'=>false,'msg'=>$ex->getMessage()));
                    
                }                
                Yii::app()->end($ret);                
	}
        
        public function actionHistoryList(){
		$criteria = new CDbCriteria;
		$criteria->with = array('siteNo','siteNo.site') ;
		$criteria->condition =  't.company_id='.$this->companyId.' and order_status=4' ;
		$criteria->order = 'pay_time desc';
		$pages = new CPagination(Order::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		
		$models = Order::model()->findAll($criteria);
		
		$this->render('historyList',array(
				'models'=>$models,
				'pages'=>$pages
		));	
	}
        
        /*退款*/
        public function actionPayback() {
		
	}
        
        public function actionAccount() {
		$orderId = Yii::app()->request->getParam('orderId','0');
                $companyId = Yii::app()->request->getParam('companyId','0');
                $typeId=Yii::app()->request->getParam('typeId','0');
                $callId=Yii::app()->request->getParam('callId','0');
                $padId=Yii::app()->request->getParam('padId','0');
                $ispayback=Yii::app()->request->getParam('payback','0');
                //$op=
                $totaldata=Yii::app()->request->getParam('total','0');
                 ///*************print

                $criteria = new CDbCriteria;
                $criteria->condition =  't.dpid='.$companyId.' and t.lid='.$orderId ;
                $criteria->order = ' t.lid desc ';
                $order = Order::model()->find($criteria);

                $order->should_total=$totaldata;
                $orderpay=new OrderPay();
                $orderpay->dpid=$order->dpid;
                $orderpay->order_id=$order->lid;
                $orderpay->paytype="3";
                $orderpay->create_at=date('Y-m-d H:i:s',time());
                $memo="";
                $paymentMethods = PaymentClass::getPaymentMethodList($companyId);
                //var_dump($paymentMethods);exit;
                if(Yii::app()->request->isPostRequest){
                        //var_dump(Yii::app()->request->getPost('Order'));exit;
                        //$order->attributes = Yii::app()->request->getPost('Order');
                        Until::validOperate($companyId,$this);
                        $orderpay->pay_amount = Yii::app()->request->getPost('OrderPay_pay_amount');
                        $orderpay->payment_method_id = Yii::app()->request->getPost('OrderPay_payment_method_id');
                        $orderpay->remark = Yii::app()->request->getPost('OrderPay_remark');
                        $order->order_status = Yii::app()->request->getPost('order_status');
                        $order->should_total = Yii::app()->request->getPost('order_should_total');
                        
                        $order->pay_time = date('Y-m-d H:i:s',time());
                       // $orderpay->attributes = Yii::app()->request->getPost('OrderPay');
                        $order->paytype=$orderpay->paytype;
                        $order->payment_method_id=$orderpay->payment_method_id;
                        $order->reality_total+=$orderpay->pay_amount;
                        $order->remark.=$orderpay->remark;
                        $se=new Sequence("order_pay");
                        $orderpay->lid = $se->nextval();
                        //var_dump($order);exit;
                        
			$transaction = Yii::app()->db->beginTransaction();
			try{
                            
                            $criteria2 = new CDbCriteria;
                            $criteria2->condition =  't.status in ("1","2","3") and t.dpid='.$order->dpid.' and t.site_id='.$order->site_id.' and t.is_temp='.$order->is_temp ;
                            $criteria2->order = ' t.lid desc ';
                            $siteNo = SiteNo::model()->find($criteria2);
                            if($siteNo->is_temp=='0')
                            {
                                $site = Site::model()->with('siteType')->find('t.lid=:lid and t.dpid=:dpid',  array(':lid'=>$order->site_id,':dpid'=>$order->dpid));
                                $site->status = $order->order_status;
                                $site->save();
                            }else{
                                $site = array();
                            }
                            //$order->order_status=$tempstatus;
                            $order->save();
                            $orderpay->save();
                            $siteNo->status=$order->order_status;
                            $siteNo->save();
                            
                            if($order->order_status=="3")
                            {
                                $memo=yii::t('app','收款').":".$orderpay->pay_amount;
                                if($orderpay->pay_amount<0)
                                {
                                    $memo=yii::t('app','退款').":".$orderpay->pay_amount;
                                }
                            }else if($order->order_status=="4"){
                                $memo=yii::t('app','结单').":".$orderpay->pay_amount;
                            }
                            
                            if($order->order_status=='4')
                            {
                                SiteClass::deleteCode($this->companyId,$siteNo->code);                     
                            
                                //FeedBackClass::cancelAllOrderMsg("0000000000","0",$order->lid,$companyId);
                                $sqlfeedback = "update nb_order_feedback set is_deal='1' where dpid=:companyId and site_id=:siteId and is_temp=:istemp";
                                $commandfeedback = Yii::app()->db->createCommand($sqlfeedback);
                                $commandfeedback->bindValue(":companyId",$companyId);
                                $commandfeedback->bindValue(":siteId",$order->site_id);
                                $commandfeedback->bindValue(":istemp",$order->is_temp);
                                //var_dump($sqlsite);exit;
                                $commandfeedback->execute();

                                $sqlall = "update nb_order_feedback set is_deal='1'where dpid=:dpid and order_id=:orderId and is_order='1'";
                                $connorder = Yii::app()->db->createCommand($sqlall);
                                $connorder->bindValue(':dpid',$companyId);
                                $connorder->bindValue(':orderId',$orderId);

                                $connorder->execute();

                                $sql = 'update nb_order_feedback set is_deal=1 where dpid=:dpid and order_id in (select lid from nb_order_product where dpid=:sdpid and order_id=:sorderId) and is_order=0';
                                $connorderproduct = Yii::app()->db->createCommand($sql);
                                $connorderproduct->bindValue(':dpid',$companyId);
                                $connorderproduct->bindValue(':sdpid',$companyId);
                                $connorderproduct->bindValue(':sorderId',$orderId);
                                $connorderproduct->execute();
                                //var_dump($connorderproduct);exit;
                            }
                            $transaction->commit();
                            $pad=Pad::model()->find(' dpid=:dpid and lid=:lid',array(':dpid'=>$order->dpid,'lid'=>$padId));
                            if(!empty($pad))
                            {
                                $precode="1B70001EFF00";//开钱箱
                                $printserver="1";
                                //$orderProducts= //传递要打印的菜品，这里是已经下单的
                                //$ret=Helper::printList($order , $pad,$precode,$printserver,$memo);
                                $orderProducts = OrderProduct::getHasOrderProducts($order->lid,$order->dpid);
                                $cardtotal=0;
                                $ret=Helper::printList($order,$orderProducts , $pad,$precode,$printserver,$memo,$cardtotal);
                            }
                            Yii::app()->end(json_encode(array("status"=>"success")));
                            //$this->redirect(array('default/index' , 'companyId' => $this->companyId,'typeId'=>$typeId));
                            
			} catch(Exception $e){
				$transaction->rollback();
                                Yii::app()->end(json_encode(array("status"=>"fail")));
			}
//                        $this->renderPartial('printlist' , array(
//                                'orderId'=>$orderId,
//                                //'companyId'=>$companyId,
//                                'ret'=>$ret,
//                                'typeId'=>$typeId                                
//                        ));
		}
		$this->renderPartial('account' , array(
				'order' => $order,
                                'orderpay' => $orderpay,
                                'payback'=>$ispayback,
                                'typeId'=>$typeId,
                                'callid'=>$callId,
                                'padId'=>$padId,
                                'paymentMethods'=>$paymentMethods
		));
	}
        
        public function actionAccountAuto() {
		$orderId = Yii::app()->request->getParam('orderId','0');
                $companyId = Yii::app()->request->getParam('companyId','0');
                $typeId=Yii::app()->request->getParam('typeId','0');
                $callId=Yii::app()->request->getParam('callId','0');
                
                $totaldata=Yii::app()->request->getParam('total','0');
                 ///*************print

                $criteria = new CDbCriteria;
                $criteria->condition =  't.dpid='.$companyId.' and t.lid='.$orderId ;
                $criteria->order = ' t.lid desc ';
                $order = Order::model()->find($criteria);

                $order->should_total=$totaldata;
                $orderpay=new OrderPay();
                $orderpay->dpid=$order->dpid;
                $orderpay->order_id=$order->lid;
                $orderpay->create_at=date('Y-m-d H:i:s',time());
                
                $paymentMethods = PaymentClass::getPaymentMethodList($companyId);
                //var_dump($paymentMethods);exit;
                if(Yii::app()->request->isPostRequest){
                        Until::validOperate($companyId,$this);
                        //var_dump(Yii::app()->request->getPost('Order'));exit;
                        $order->attributes = Yii::app()->request->getPost('Order');
                        $order->pay_time = date('Y-m-d H:i:s',time());
                        $orderpay->attributes = Yii::app()->request->getPost('OrderPay');
                        $order->payment_method_id=$orderpay->payment_method_id;
                        $order->reality_total+=$orderpay->pay_amount;
                        $order->remark.=$orderpay->remark;
                        $se=new Sequence("order_pay");
                        $orderpay->lid = $se->nextval();
                        //var_dump($order);exit;
                        
			$transaction = Yii::app()->db->beginTransaction();
			try{
                            
                            $criteria2 = new CDbCriteria;
                            $criteria2->condition =  't.status in ("1","2","3") and t.dpid='.$order->dpid.' and t.site_id='.$order->site_id.' and t.is_temp='.$order->is_temp ;
                            $criteria2->order = ' t.lid desc ';
                            $siteNo = SiteNo::model()->find($criteria2);
                            if($siteNo->is_temp=='0')
                            {
                                $site = Site::model()->with('siteType')->find('t.lid=:lid and t.dpid=:dpid',  array(':lid'=>$order->site_id,':dpid'=>$order->dpid));
                                $site->status = $order->order_status;
                                $site->save();
                            }else{
                                $site = array();
                            }
                            //$order->order_status=$tempstatus;
                            $order->save();
                            $orderpay->save();
                            $siteNo->status=$order->order_status;
                            $siteNo->save();                               
                            
                            $transaction->commit();
                            $this->redirect(array('default/index' , 'companyId' => $this->companyId,'typeId'=>$typeId));
			} catch(Exception $e){
				$transaction->rollback();
			}
		}
		$this->renderPartial('accountauto' , array(
				'order' => $order,
                                'orderpay' => $orderpay,
                                'typeId'=>$typeId,
                                'callid'=>$callId,
                                'paymentMethods'=>$paymentMethods
		));
	}
        
        public function actionAccountManul() {
		$orderId = Yii::app()->request->getParam('orderId','0');
                $companyId = Yii::app()->request->getParam('companyId','0');
                $typeId=Yii::app()->request->getParam('typeId','0');
                $callId=Yii::app()->request->getParam('callId','0');
                $padId=Yii::app()->request->getParam('padId','0000000000');
                $totaldata=Yii::app()->request->getParam('total','0');
                 ///*************print

                $criteria = new CDbCriteria;
                $criteria->condition =  't.dpid='.$companyId.' and t.lid='.$orderId ;
                $criteria->order = ' t.lid desc ';
                $order = Order::model()->find($criteria);

                $order->should_total=$totaldata;
                $orderpay=new OrderPay();
                $orderpay->dpid=$order->dpid;
                $orderpay->order_id=$order->lid;
                $orderpay->paytype="0";
                $orderpay->remark="现金支付";
                $orderpay->create_at=date('Y-m-d H:i:s',time());
                
                $paymentMethods = PaymentClass::getPaymentMethodList($companyId);
                //var_dump($paymentMethods);exit;
                if(Yii::app()->request->isPostRequest){
                        Until::validOperate($companyId,$this);
                        //var_dump(Yii::app()->request->getPost('Order'));exit;
                        $order->attributes = Yii::app()->request->getPost('Order');
                        $order->pay_time = date('Y-m-d H:i:s',time());
                        $orderpay->attributes = Yii::app()->request->getPost('OrderPay');
                        $order->paytype=$orderpay->paytype;
                        //$order->payment_method_id=$orderpay->payment_method_id;
                        $order->reality_total+=$orderpay->pay_amount;
                        $order->remark.=$orderpay->remark;
                        $se=new Sequence("order_pay");
                        $orderpay->lid = $se->nextval();
                        //var_dump($order);exit;
                        $memo="";
                        if($order->order_status=="3")
                        {
                            $memo=yii::t('app','收款').":".$orderpay->pay_amount;
                        }else if($order->order_status=="4"){
                            $memo=yii::t('app','结单').":".$orderpay->pay_amount;
                        }
			$transaction = Yii::app()->db->beginTransaction();
			try{
                            
                            $criteria2 = new CDbCriteria;
                            $criteria2->condition =  't.status in ("1","2","3") and t.dpid='.$order->dpid.' and t.site_id='.$order->site_id.' and t.is_temp='.$order->is_temp ;
                            $criteria2->order = ' t.lid desc ';
                            $siteNo = SiteNo::model()->find($criteria2);
                            if($siteNo->is_temp=='0')
                            {
                                $site = Site::model()->with('siteType')->find('t.lid=:lid and t.dpid=:dpid',  array(':lid'=>$order->site_id,':dpid'=>$order->dpid));
                                $site->status = $order->order_status;
                                $site->save();
                            }else{
                                $site = array();
                            }
                            //$order->order_status=$tempstatus;
                            $order->save();
                            $orderpay->save();
                            $siteNo->status=$order->order_status;
                            $siteNo->save();                               
                            
                            if($order->order_status=='4')
                            {
                                SiteClass::deleteCode($this->companyId,$siteNo->code);  
                                //FeedBackClass::cancelAllOrderMsg("0000000000","0",$order->lid,$companyId);
                                $sqlfeedback = "update nb_order_feedback set is_deal='1' where dpid=:companyId and site_id=:siteId and is_temp=:istemp";
                                $commandfeedback = Yii::app()->db->createCommand($sqlfeedback);
                                $commandfeedback->bindValue(":companyId",$companyId);
                                $commandfeedback->bindValue(":siteId",$order->site_id);
                                $commandfeedback->bindValue(":istemp",$order->is_temp);
                                //var_dump($sqlsite);exit;
                                $commandfeedback->execute();

                                $sqlall = "update nb_order_feedback set is_deal='1'where dpid=:dpid and order_id=:orderId and is_order='1'";
                                $connorder = Yii::app()->db->createCommand($sqlall);
                                $connorder->bindValue(':dpid',$companyId);
                                $connorder->bindValue(':orderId',$orderId);

                                $connorder->execute();

                                $sql = 'update nb_order_feedback set is_deal=1 where dpid=:dpid and order_id in (select lid from nb_order_product where dpid=:sdpid and order_id=:sorderId) and is_order=0';
                                $connorderproduct = Yii::app()->db->createCommand($sql);
                                $connorderproduct->bindValue(':dpid',$companyId);
                                $connorderproduct->bindValue(':sdpid',$companyId);
                                $connorderproduct->bindValue(':sorderId',$orderId);
                                $connorderproduct->execute();
                            }                                                
                            
                            $transaction->commit();
                            $pad=Pad::model()->find(' dpid=:dpid and lid=:lid',array(':dpid'=>$order->dpid,'lid'=>$padId));
                            if(!empty($pad))
                            {
                                $precode="1B70001EFF00";//开钱箱
                                $printserver="1"; 
                                //$orderProducts= //传递要打印的菜品，这里是已经下单的
                                $orderProducts = OrderProduct::getHasOrderProducts($order->lid,$order->dpid);
                                $cardtotal=0;
                                $ret=Helper::printList($order,$orderProducts , $pad,$precode,$printserver,$memo,$cardtotal);
                            }//$ret=array('status'=>false,'dpid'=>"0000000011",'jobid'=>"0",'type'=>'none','msg'=>yii::t('app','没有要打印的菜品！'));
                            $this->redirect(array('default/index' , 'companyId' => $this->companyId,'typeId'=>$typeId));
                            
			} catch(Exception $e){
				$transaction->rollback();
			}
//                        $this->renderPartial('printlist' , array(
//                                'orderId'=>$orderId,
//                                //'companyId'=>$companyId,
//                                'ret'=>$ret,
//                                
//                                'typeId'=>$typeId                                
//                        ));
//                        exit;
		}
                //var_dump($order);exit;
		$this->renderPartial('accountmanul' , array(
				'order' => $order,
                                'orderpay' => $orderpay,
                                'typeId'=>$typeId,
                                'callid'=>$callId,
                                'padId'=>$padId,
                                'paymentMethods'=>$paymentMethods
		));
	}
        
        public function actionAddProduct() {
                $companyId=Yii::app()->request->getParam('companyId','0');
                $typeId=Yii::app()->request->getParam('typeId','0');
                $orderId=Yii::app()->request->getParam('orderId','0');
                $orderProduct = new OrderProduct();
                $orderProduct->order_id = $orderId;
                $categories = ProductClass::getCategories($companyId);
                $isset=Yii::app()->request->getParam('isset','0');
                //var_dump($categories);exit;
                $setlist = ProductSetClass::getSetlist($companyId);
                $categoryId=0;
                $products = ProductClass::getProducts($categoryId,$companyId);
                $productslist=CHtml::listData($products, 'lid', 'product_name');
                
                
		if(Yii::app()->request->isPostRequest){
                        Until::validOperate($companyId,$this);
                        //$isset = Yii::app()->request->getPost('isset',0);
                        //$setid = Yii::app()->request->getParam('setid',0);
                        $selsetlist = Yii::app()->request->getPost('selsetlist',0);
                        $db = Yii::app()->db;
                        $transaction = $db->beginTransaction();
                        try {
                            //var_dump($isset);exit;
                            if($isset==0)
                            {   
                                $orderProduct = new OrderProduct();
                                $orderProduct->dpid = $companyId;
                                $orderProduct->delete_flag = '0';
                                $orderProduct->taste_memo = "";
                                $orderProduct->product_order_status = '0';
                                $orderProduct->attributes = Yii::app()->request->getPost('OrderProduct');
                                $orderProduct->create_at = date('Y-m-d H:i:s',time());
                                $orderProduct->set_id = '0000000000';                                
                                $se=new Sequence("order_product");
                                $orderProduct->lid = $se->nextval();
                                //var_dump($orderProduct);exit;
                                $orderProduct->save();
                            }else{
                                //var_dump($selsetlist);exit;
                                if(strlen($selsetlist)>10)
                                {   
                                    
                                    $productIdlist=explode(',',$selsetlist);
                                    $setid=Yii::app()->request->getPost('OrderProduct');
                                    //var_dump($setid['set_id']);exit;
                                    foreach ($productIdlist as $productId){
                                        //var_dump($productId);
                                        $sorderProduct = new OrderProduct();
                                        $sorderProduct->dpid = $companyId;
                                        $sorderProduct->delete_flag = '0';
                                        $sorderProduct->product_order_status = '0';
                                        $sorderProduct->set_id=$setid['set_id'];
                                        $sorderProduct->main_id='0000000000';
                                        $sorderProduct->order_id=$setid['order_id'];
                                        //$orderProduct->attributes = Yii::app()->request->getPost('OrderProduct');
                                        $sorderProduct->create_at = date('Y-m-d H:i:s',time());
                                        $productUnit=explode('|',$productId);
                                        $sorderProduct->product_id = $productUnit[0];
                                        $sorderProduct->amount = $productUnit[1];
                                        $sorderProduct->price = $productUnit[2];
                                        $sorderProduct->is_giving = '0';
                                        $sorderProduct->product_status = '0';//添加cf
                                        $sorderProduct->zhiamount = 0;                                    
                                        $se=new Sequence("order_product");
                                        $sorderProduct->lid = $se->nextval();
                                        //var_dump($orderProduct);exit;
                                        $sorderProduct->save();                                    
                                    }
                                }
                            }
                            $transaction->commit();
                            Yii::app()->user->setFlash('success' , yii::t('app','添加单品成功'));
                            $this->redirect(array('defaultOrder/order' , 'companyId' => $this->companyId,'orderId' => $orderId,'typeId'=>$typeId));
                        } catch (Exception $e) {
                            $transaction->rollback(); //如果操作失败, 数据回滚
                            //echo json_encode(array('status'=>0,'message'=>'换台失败'));
                            Yii::app()->user->setFlash('success' , yii::t('app','添加失败'));
                            return false;
                    }
                        //var_dump($orderProduct);exit;                   
                        //第一个菜需要更新订单状态。。。。
                        //添加产品时，还可以添加套餐。。。                     
                }
                
                $paymentMethods = PaymentClass::getPaymentMethodList($companyId);
                //var_dump($paymentMethods);exit;
                $this->renderPartial('addproduct' , array(
                                'orderId'=>$orderId,
				'orderProduct' => $orderProduct,
				'paymentMethods'=>$paymentMethods,
                                'categories' => $categories,
                                'products' => $productslist,
                                'typeId'=>$typeId,
                                'setlist' => $setlist,
                                'isset' => $isset
		));
	}
        
        
        public function actionAddProductAll() {
                $companyId=Yii::app()->request->getParam('companyId','0');
                $typeId=Yii::app()->request->getParam('typeId','0');
                $orderId=Yii::app()->request->getParam('orderId','0');
                
                $criteria = new CDbCriteria;
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$companyId ;
		$criteria->order = ' order_num asc ';		
		$categories = ProductCategory::model()->findAll($criteria);
                
                $criteriaps = new CDbCriteria;
		$criteriaps->condition =  't.delete_flag=0 and t.dpid='.$companyId ;
                $criteriaps->with="productsetdetail";
		$criteriaps->order = ' t.lid asc ';		
		$productSets = ProductSet::model()->findAll($criteriaps);
                                
                $criteriap = new CDbCriteria;
		$criteriap->condition =  'delete_flag=0 and t.dpid='.$companyId ;// and is_show=1
		$criteriap->order = ' t.category_id asc,t.lid asc ';
                $products =  Product::model()->findAll($criteriap);
                
                $productidnameArr=array();
                foreach($products as $product)
                {
                    $productidnameArr[$product->lid]=$product->product_name;
                }
                //var_dump($productidnameArr);exit;
                        
		if(Yii::app()->request->isPostRequest){
                    Until::validOperate($companyId,$this);
                        //$isset = Yii::app()->request->getPost('isset',0);
                        //$setid = Yii::app()->request->getParam('setid',0);
                        $selectlist=Yii::app()->request->getPost('selectproductlist','0');
                        $db = Yii::app()->db;
                        $transaction = $db->beginTransaction();
                        try {
                            if(strlen($selectlist)>10)
                            {                              
                                $productlist=explode(';',$selectlist);                                
                                foreach ($productlist as $product){
                                    //var_dump($productId);
                                    $productUnit=explode(',',$product);
                                    $sorderProduct = new OrderProduct();
                                    $sorderProduct->dpid = $companyId;
                                    $sorderProduct->delete_flag = '0';
                                    $sorderProduct->product_order_status = '0';
                                    $sorderProduct->set_id=$productUnit[0];
                                    $sorderProduct->main_id='0000000000';
                                    $sorderProduct->order_id=$orderId;
                                    $sorderProduct->create_at = date('Y-m-d H:i:s',time());
                                    $sorderProduct->product_id = $productUnit[1];
                                    $sorderProduct->amount = $productUnit[2];
                                    $sorderProduct->zhiamount = $productUnit[3];                                    
                                    $sorderProduct->price = $productUnit[4];
                                    $sorderProduct->is_giving = $productUnit[5];
                                    $sorderProduct->product_status = "0";//添加cf
                                    $se=new Sequence("order_product");
                                    $sorderProduct->lid = $se->nextval();
                                    //var_dump($orderProduct);exit;
                                    $sorderProduct->save();                                    
                                }
                            }
                            $transaction->commit();
                            Yii::app()->user->setFlash('success' , yii::t('app','添加单品成功'));
                            $this->redirect(array('defaultOrder/order' , 'companyId' => $this->companyId,'orderId' => $orderId,'typeId'=>$typeId));
                        } catch (Exception $e) {
                            $transaction->rollback(); //如果操作失败, 数据回滚
                            //echo json_encode(array('status'=>0,'message'=>'换台失败'));
                            Yii::app()->user->setFlash('success' , yii::t('app','添加失败'));
                            return false;
                    }
                        //var_dump($orderProduct);exit;                   
                        //第一个菜需要更新订单状态。。。。
                        //添加产品时，还可以添加套餐。。。                     
                }
//                var_dump($productidnameArr);exit;
                $this->renderPartial('addproductall' , array(
                                'orderId'=>$orderId,
				'categories' => $categories,
                                'products' => $products,
                                'productSets' => $productSets,
                                'typeId'=>$typeId,
                                //'setlist' => $setlist,
                                //'isset' => $isset
                                "pn"=>$productidnameArr
		));
	}
        
        public function actionAddAddition() {
                $companyId=Yii::app()->request->getParam('companyId','0');
                $typeId=Yii::app()->request->getParam('typeId','0');
                $orderId=Yii::app()->request->getParam('orderId','0');
                $productId=Yii::app()->request->getParam('productId','0');
                $products = ProductClass::getAdditionProducts($productId,$companyId);
                
		if(Yii::app()->request->isPostRequest){
                    Until::validOperate($companyId,$this);
                        $additionnames = Yii::app()->request->getPost('additionnames','');
                        $db = Yii::app()->db;
                        $transaction = $db->beginTransaction();
                        try {          
                               if(strlen($additionnames)>10)
                                {                                   
                                    $productIdlist=explode(',',$additionnames);
                                    //update parent product
                                    $db->createCommand('update nb_order_product set main_id=product_id where product_id=:productid and dpid=:dpid')->execute(array(':productid'=>$productId,':dpid'=>$companyId));
                                    foreach ($productIdlist as $product){
                                        //var_dump($productId);
                                        $sorderProduct = new OrderProduct();
                                        $sorderProduct->dpid = $companyId;
                                        $sorderProduct->delete_flag = '0';
                                        $sorderProduct->product_order_status = '0';
                                        $sorderProduct->order_id=$orderId;
                                        $sorderProduct->main_id=$productId;
                                        $sorderProduct->set_id='0000000000';
                                        //$orderProduct->attributes = Yii::app()->request->getPost('OrderProduct');
                                        $sorderProduct->create_at = date('Y-m-d H:i:s',time());
                                        $productUnit=explode('|',$product);
                                        $sorderProduct->product_id = $productUnit[0];
                                        $sorderProduct->price = $productUnit[1];
                                        $sorderProduct->amount = $productUnit[2];
                                        $sorderProduct->is_giving = '0';
                                        $sorderProduct->product_status = '0';//添加cf
                                        $sorderProduct->zhiamount = 0;                                    
                                        $se=new Sequence("order_product");
                                        $sorderProduct->lid = $se->nextval();
                                        //var_dump($orderProduct);exit;
                                        $sorderProduct->save();                                    
                                    }
                                }                            
                            $transaction->commit();
                            Yii::app()->user->setFlash('success' , yii::t('app','添加成功'));
                            $this->redirect(array('defaultOrder/order' , 'companyId' => $this->companyId,'orderId' => $orderId,'typeId'=>$typeId));
                        } catch (Exception $e) {
                            $transaction->rollback(); //如果操作失败, 数据回滚
                            //echo json_encode(array('status'=>0,'message'=>'换台失败'));
                            Yii::app()->user->setFlash('success' , yii::t('app','添加失败'));
                            return false;
                    }
                        //var_dump($orderProduct);exit;                   
                        //第一个菜需要更新订单状态。。。。
                        //添加产品时，还可以添加套餐。。。                     
                }
                
                $paymentMethods = PaymentClass::getPaymentMethodList($companyId);
                //var_dump($paymentMethods);exit;
                $this->renderPartial('additiondetail' , array(
                                'orderId'=>$orderId,
				'models' => $products,
                                'productId'=>$productId,
				'typeId'=>$typeId
		));
	}
        
        public function actionSetdetail() {
		$id = Yii::app()->request->getParam('id',0);
                $criteria = new CDbCriteria;
                $criteria->with = array('product');
                $criteria->condition =  't.dpid='.$this->companyId .' and t.set_id='.$id.' and t.delete_flag=0 and product.delete_flag=0';
                $criteria->order = ' t.group_no ASC,t.is_select DESC';
		$models = ProductSetDetail::model()->findAll($criteria);
                $modelsp= Yii::app()->db->createCommand('select product_id from nb_order_product t where t.dpid='.$this->companyId.' and t.set_id='.$id.' and t.delete_flag=0')->queryAll();
                $modelspt=array_column($modelsp, 'product_id');
                //var_dump($modelspt);exit;
                foreach ($models as $m){
                    //$m->is_select='0';
                    if(in_array($m->product_id, $modelspt))
                    {
                        $m->is_select='1';
                    }
                }
                //set select
		$this->renderPartial('setdetail' , array(
				'models' => $models
		));
	}
        
        public function actionOver() {
		$lid = Yii::app()->request->getParam('lid',0);
                $companyId = Yii::app()->request->getParam('companyId',0);
                $orderId = Yii::app()->request->getParam('orderId',0);
                $typeId = Yii::app()->request->getParam('typeId',0);
                Until::isUpdateValid(array($lid), $companyId, $this);
                $sql='update nb_order_product set is_waiting=2 where dpid='.$companyId.' and lid='.$lid;
                //var_dump($sql);exit;
                Yii::app()->db->createCommand($sql)->execute();
                $this->redirect(array('defaultOrder/order' , 'companyId' => $companyId,'orderId' => $orderId,'typeId'=>$typeId));
	}
        
        public function actionDelproduct(){
		$id = Yii::app()->request->getParam('id',0);
		$setid = Yii::app()->request->getParam('setid',0);
		$orderId = Yii::app()->request->getParam('orderId');
		$typeId = Yii::app()->request->getParam('typeId');
		$companyId = Yii::app()->request->getParam('companyId');
                //Until::isUpdateValid($lid, $companyId, $this);
                Until::validOperate($companyId, $this);
                if($setid=='0000000000')
                {
                    $sql='update nb_order_product set delete_flag = 1 where lid='.$id.' and dpid='.$companyId;
                }else{
                    $sql='update nb_order_product set delete_flag = 1 where order_id='.$orderId.' and set_id='.$setid.' and dpid='.$companyId;
                }
                Yii::app()->db->createCommand($sql)->execute();
                $this->redirect(array('defaultOrder/order' , 'companyId' => $companyId,'orderId' => $orderId,'typeId'=>$typeId));
	}
        
        public function actionProductTaste(){
		$lid = Yii::app()->request->getParam('lid',0);
		$typeId = Yii::app()->request->getParam('typeId');
		$companyId = Yii::app()->request->getParam('companyId');
                $isall = Yii::app()->request->getParam('isall','0');
                if($isall=='1')
                {
                    $tastegroups= TasteClass::getAllOrderTasteGroup($companyId, '1');
                    $orderTastes=  TasteClass::getOrderTaste($lid, '1', $companyId);
                    $tasteMemo = TasteClass::getOrderTasteMemo($lid, '1', $companyId);
                    $orderId=$lid;
                    //var_dump($tastegroups,$orderTastes,$tasteMemo);exit;                   
                    
                }else{
                    $orderProduct=  OrderProduct::model()->find(' lid=:lid and dpid=:dpid',array(':lid'=>$lid,':dpid'=>$companyId));
                    $tastegroups=  TasteClass::getProductTasteGroup($orderProduct->product_id,$companyId);
                    $orderTastes=  TasteClass::getOrderTaste($lid, '2', $companyId);
                    $tasteMemo = TasteClass::getOrderTasteMemo($lid, '2', $companyId);
                    $orderId=$orderProduct->order_id;
                    //var_dump($tastegroups,$orderTastes,$tasteMemo);exit;       
                                       
                }
                if(Yii::app()->request->isPostRequest) {
                        Until::validOperate($companyId, $this);
                        $taste_memo = Yii::app()->request->getPost('taste_memo');
                        $selectTasteList = Yii::app()->request->getPost('selectTasteList');
                        $selectTastes=explode(',',$selectTasteList);
                        if(TasteClass::save($companyId, $isall,$lid,$selectTastes,$taste_memo))
                        {
                            $this->redirect(array('defaultOrder/order' , 'companyId' => $companyId,'orderId' => $orderId,'typeId'=>$typeId));
                        }                        
                } 
                $this->renderPartial('tastedetail' , array(
                                'tastegroups' => $tastegroups,
                                'orderTastes'=>$orderTastes,
                                'tasteMemo' => $tasteMemo,
                                'isall'=>$isall,
                                'lid'=>$lid,
                                'typeId'=>$typeId
                ));
	}
        
        public function actionProductTasteAll(){
		$lid = Yii::app()->request->getParam('lid',0);
		$companyId = Yii::app()->request->getParam('companyId');
                $isall = Yii::app()->request->getParam('isall','0');
                if($isall=='1')
                {
                    $tastegroups= TasteClass::getAllOrderTasteGroup($companyId, '1');
                    $orderTastes=  TasteClass::getOrderTaste($lid, '1', $companyId);
                    $tasteMemo = TasteClass::getOrderTasteMemo($lid, '1', $companyId);
                    $orderId=$lid;
                }else{
                    $tastegroups=  TasteClass::getProductTasteGroup($lid,$companyId);
                    $orderTastes=  TasteClass::getOrderTaste($lid, '2', $companyId);
                    $tasteMemo = TasteClass::getOrderTasteMemo($lid, '2', $companyId);
                }
                 
                $this->renderPartial('tastedetailall' , array(
                                'tastegroups' => $tastegroups,
                                'orderTastes'=>$orderTastes,
                                'tasteMemo' => $tasteMemo
                ));
	}
        
        public function actionRetreatProduct(){
		$id = Yii::app()->request->getParam('id',0);
		$typeId = Yii::app()->request->getParam('typeId',0);
		$companyId = Yii::app()->request->getParam('companyId');
                if(Yii::app()->request->isPostRequest) {
                    Until::validOperate($companyId, $this);
                    $orderProduct = OrderProduct::model()->find(' dpid=:dpid and lid=:lid', array(':dpid'=>$companyId,':lid'=>$id));
                    $orderProduct->is_retreat ='1';
                    if($orderProduct->save()){                                
				//Yii::app()->user->setFlash('success' , '添加成功');
				$this->redirect(array('defaultOrder/order' , 'companyId' => $companyId,'orderId' => $orderProduct->order_id,'typeId'=>$typeId));
			}
                }                
                $models = OrderRetreat::model()->with('retreat')->findAll('t.order_detail_id=:id and t.dpid=:dpid',array(':id'=>$id,':dpid'=>$companyId));
                $this->renderPartial('retreat' , array(
				'models' => $models,
                                'orderDetailId'=>$id,
                                'typeId'=>$typeId
		));
	}
        
        public function actionAddRetreat() {
                $companyId=Yii::app()->request->getParam('companyId','0');
                $orderDetailId=Yii::app()->request->getParam('orderDetailId','0');
                
               
                $orderRetreat = new OrderRetreat();
                $orderRetreat->order_detail_id = $orderDetailId;
                $orderRetreat->dpid = $companyId;
                $retreats = Retreat::model()->findAll(' dpid=:dpid and delete_flag = 0',array(':dpid'=>$companyId));                
                $retreatslist=CHtml::listData($retreats, 'lid', 'name');                
                
		if(Yii::app()->request->isPostRequest){
                    Until::validOperate($companyId, $this);
                    $orderDetail = OrderProduct::model()->find('dpid=:dpid and lid=:lid',array(':dpid'=>$companyId,':lid'=>$orderDetailId));
                    $transaction = Yii::app()->db->beginTransaction();
					try {
						$orderDetail->is_retreat = 1;
						$orderDetail->update();
	                    $orderRetreat->attributes = Yii::app()->request->getPost('OrderRetreat');
	                    $orderRetreat->create_at = date('Y-m-d H:i:s',time());
	                    $se=new Sequence("order_retreat");
	                    $orderRetreat->lid = $se->nextval();
	                    if($orderRetreat->save()){                                
	                        echo json_encode(array('msg'=>yii::t('app','成功')));
	                    }else{
	                        echo json_encode(array('msg'=>yii::t('app','失败')));
	                    }
	                    $transaction->commit(); //提交事务会真正的执行数据库操作
					} catch (Exception $e) {
						$transaction->rollback(); //如果操作失败, 数据回滚
						 echo json_encode(array('msg'=>yii::t('app','失败')));
					}                    
                    return;
                }                
                $this->renderPartial('addretreat' , array(
				'orderRetreat' => $orderRetreat,
				'retreats'=>$retreatslist                                
		));
	}
        
        public function actionAddRetreatOne() {
                $companyId=Yii::app()->request->getParam('companyId','0');
                $orderDetailId=Yii::app()->request->getParam('orderDetailId','0');
                $producttype=Yii::app()->request->getParam('productType','0');
                //Yii::app()->end(array('status'=>false,'msg'=>yii::t('app','失败1')));
                $db=Yii::app()->db;
                $orderRetreat = new OrderRetreat();
                $orderRetreat->order_detail_id = $orderDetailId;
                $orderRetreat->dpid = $companyId;
                $retreats = Retreat::model()->findAll(' dpid=:dpid and delete_flag = 0',array(':dpid'=>$companyId));                
                $retreatslist=CHtml::listData($retreats, 'lid', 'name');
                //var_dump($retreatslist);exit;
                $orderDetail = OrderProduct::model()->with("product")->findAll('t.dpid=:dpid and t.lid=:lid',array(':dpid'=>$companyId,':lid'=>$orderDetailId));
                //Yii::app()->end(array('status'=>false,'msg'=>yii::t('app','失败1')));
                $orderId=$orderDetail[0]->order_id;
                //var_dump($orderDetail);exit;
                if($producttype=="0"){
                	$productdata=Product::model()->find('lid=:lid and dpid=:dpid' , array(':lid'=>$orderDetail[0]->product_id,':dpid'=>$companyId));
                	$productname=$productdata->product_name;
                }elseif($producttype=="1"){
                	$productname="餐位费";
                }elseif($producttype=="2"){
                	$productname="打包费";
                }elseif($producttype=="3"){
                	$productname="送餐费";
                }elseif($producttype=="4"){
                	$productname="外卖起步价";
                }else{
                	$productname="其他";
                }
                $ret=array();
                if($producttype=="0"){
				if(Yii::app()->request->isPostRequest){
                    Until::validOperate($companyId, $this);
                    $retreatnum=Yii::app()->request->getPost('retreatnum',0);
                    $othermemo=Yii::app()->request->getPost('othermemo','');
                    $retreatid=Yii::app()->request->getPost('retreatid','');
                    $isall=Yii::app()->request->getPost('isall','1');
                    $padid=Yii::app()->request->getPost('padid','1');
                    $time=date('Y-m-d H:i:s',time());
                    //Yii::app()->end(json_encode(array('status'=>false,'msg'=>$retreatnum.$othermemo.$isall.$padid)));
                    $transaction = Yii::app()->db->beginTransaction();
                    try {
                        $sqlorderproduct="";
                        $memo="";
                        if($isall=="0")
                        {
                            $sqlorderproduct="update nb_order_product set amount=amount-".$retreatnum." where dpid=".$companyId." and lid = ".$orderDetailId;
                            $memo="退".$retreatnum."份".$othermemo;
                        }else{
                            $sqlorderproduct="update nb_order_product set is_retreat = 1 where dpid=".$companyId." and lid = ".$orderDetailId;
                            $memo="全退".$othermemo;
                        }
                            $db->createCommand($sqlorderproduct)->execute();
	                    //Yii::app()->end(json_encode(array('status'=>"0",'msg'=>$retreatnum.$othermemo)));
                            $se=new Sequence("order_retreat");
	                    $orderRetreatlid = $se->nextval();
                            $orderRetreat = array(
                                            'lid'=>$orderRetreatlid,
                                            'dpid'=>$companyId,
                                            'create_at'=>$time,
                                            'order_detail_id'=>$orderDetailId,
                                            'update_at'=>$time,
                                            'retreat_memo'=>$memo,
                                            'retreat_id'=>$retreatid,
                                            'username'=>Yii::app()->user->name,
                                            'retreat_amount'=>$retreatnum,
                                            'delete_flag'=>'0',//'product_order_status'=>$orderProductStatus,
                                            );
                            $db->createCommand()->insert('nb_order_retreat',$orderRetreat);
//                            Yii::app()->end(json_encode(array('status'=>false,'msg'=>"23424332")));
                            if($productdata->store_number>=0)
                            {
                                $productdata->store_number=$retreatnum+$productdata->store_number;
                                $productdata->update_at=$time;
                                $productdata->save();
                            }
                            //Yii::app()->end(json_encode(array('status'=>false,'msg'=>"23424332")));
                                ////////////////退菜打印
                            $order=new Order();
                            $siteNo=new SiteNo();
                            $site=new Site();
                            ///***********insert to order feedback
                            ///*************print
                            if(!empty($orderId))
                            {
                                $order = Order::model()->with('company')->find(' t.lid=:lid and t.dpid=:dpid and t.order_status in(1,2,3)' , array(':lid'=>$orderId,':dpid'=>$companyId));
                                //Yii::app()->end(json_encode(array('status'=>false,'msg'=>"234")));                    
                                if(empty($order))
                                {
                                    Yii::app()->end(json_encode(array('status'=>false,'msg'=>"该订单不存在")));
                                }
                                $criteria = new CDbCriteria;
                                $criteria->condition =  't.dpid='.$companyId.' and t.site_id='.$order->site_id.' and t.is_temp='.$order->is_temp ;
                                $criteria->order = ' t.lid desc ';                    
                                $siteNo = SiteNo::model()->find($criteria);
                                //order site 和 siteno都需要更新状态 所以要取出来
                                if($order->is_temp=="0")
                                {
                                    $criteria2 = new CDbCriteria;
                                    $criteria2->condition =  't.dpid='.$companyId.' and t.lid='.$order->site_id ;
                                    $criteria2->order = ' t.lid desc ';                    
                                    $site = Site::model()->with("siteType")->find($criteria2);
                                }
                            }
                            //$memo="退菜单";
                            $orderDetail[0]->amount=$retreatnum;
                            //Yii::app()->end(json_encode(array('status'=>false,'msg'=>$order->dpid)));                           
                            $ret=  Helper::printKitchenOther($order,$orderDetail,$site,$siteNo,false,$othermemo);                    
                            if(!$ret['status'])
                            {
                                $transaction->rollback();
                            }else{
                                $transaction->commit();
                            }
                            //$ret= json_encode(array('status'=>"1",'msg'=>yii::t('app','退菜成功')));
                        } catch (Exception $e) {
                                $transaction->rollback(); //如果操作失败, 数据回滚
                                $ret= array('status'=>false,'msg'=>yii::t('app','失败1'));
                        } 
                        Yii::app()->end(json_encode($ret));                    
                }  
                }else{
                		if(Yii::app()->request->isPostRequest){
                			$ret= array('status'=>false,'msg'=>yii::t('app','失败1'));
                			Yii::app()->end(json_encode($ret));
                			Until::validOperate($companyId, $this);
                			$retreatnum=Yii::app()->request->getPost('retreatnum',0);
                			$othermemo=Yii::app()->request->getPost('othermemo','');
                			$retreatid=Yii::app()->request->getPost('retreatid','');
                			$isall=Yii::app()->request->getPost('isall','1');
                			$padid=Yii::app()->request->getPost('padid','1');
                			$time=date('Y-m-d H:i:s',time());exit;
                			//Yii::app()->end(json_encode(array('status'=>false,'msg'=>$retreatnum.$othermemo.$isall.$padid)));
                			$transaction = Yii::app()->db->beginTransaction();
                			try {
                				$sqlorderproduct="";
                				$memo="";
                				if($isall=="0")
                				{
                					$sqlorderproduct="update nb_order_product set amount=amount-".$retreatnum." where dpid=".$companyId." and lid = ".$orderDetailId;
                					$memo="退".$retreatnum."份".$othermemo;
                				}else{
                					$sqlorderproduct="update nb_order_product set is_retreat = 1 where dpid=".$companyId." and lid = ".$orderDetailId;
                					$memo="全退".$othermemo;
                				}
                				$db->createCommand($sqlorderproduct)->execute();
                				//Yii::app()->end(json_encode(array('status'=>"0",'msg'=>$retreatnum.$othermemo)));
                				$se=new Sequence("order_retreat");
                				$orderRetreatlid = $se->nextval();
                				$orderRetreat = array(
                						'lid'=>$orderRetreatlid,
                						'dpid'=>$companyId,
                						'create_at'=>$time,
                						'order_detail_id'=>$orderDetailId,
                						'update_at'=>$time,
                						'retreat_memo'=>$memo,
                						'retreat_id'=>$retreatid,
                						'username'=>Yii::app()->user->name,
                						'retreat_amount'=>$retreatnum,
                						'delete_flag'=>'0',//'product_order_status'=>$orderProductStatus,
                				);
                				$db->createCommand()->insert('nb_order_retreat',$orderRetreat);
                				//                            Yii::app()->end(json_encode(array('status'=>false,'msg'=>"23424332")));
                				if($productdata->store_number>=0)
                				{
                					$productdata->store_number=$retreatnum+$productdata->store_number;
                					$productdata->update_at=$time;
                					$productdata->save();
                				}
                				//Yii::app()->end(json_encode(array('status'=>false,'msg'=>"23424332")));
                				////////////////退菜打印
                				$order=new Order();
                				$siteNo=new SiteNo();
                				$site=new Site();
                				///***********insert to order feedback
                				///*************print
                				if(!empty($orderId))
                				{
                					$order = Order::model()->with('company')->find(' t.lid=:lid and t.dpid=:dpid and t.order_status in(1,2,3)' , array(':lid'=>$orderId,':dpid'=>$companyId));
                					//Yii::app()->end(json_encode(array('status'=>false,'msg'=>"234")));
                					if(empty($order))
                					{
                						Yii::app()->end(json_encode(array('status'=>false,'msg'=>"该订单不存在")));
                					}
                					$criteria = new CDbCriteria;
                					$criteria->condition =  't.dpid='.$companyId.' and t.site_id='.$order->site_id.' and t.is_temp='.$order->is_temp ;
                					$criteria->order = ' t.lid desc ';
                					$siteNo = SiteNo::model()->find($criteria);
                					//order site 和 siteno都需要更新状态 所以要取出来
                					if($order->is_temp=="0")
                					{
                						$criteria2 = new CDbCriteria;
                						$criteria2->condition =  't.dpid='.$companyId.' and t.lid='.$order->site_id ;
                						$criteria2->order = ' t.lid desc ';
                						$site = Site::model()->with("siteType")->find($criteria2);
                					}
                				}
                				//$memo="退菜单";
                				$orderDetail[0]->amount=$retreatnum;
                				//Yii::app()->end(json_encode(array('status'=>false,'msg'=>$order->dpid)));
                				$ret=  $ret=array('status'=>true,'orderid'=>$order->lid,'dpid'=>$order->dpid,'allnum'=>count($jobids2),'msg'=>'打印任务正常发布',"jobs"=>$jobids2);
                				if(!$ret['status'])
                				{
                					$transaction->rollback();
                				}else{
                					$transaction->commit();
                				}
                				//$ret= json_encode(array('status'=>"1",'msg'=>yii::t('app','退菜成功')));
                			} catch (Exception $e) {
                				$transaction->rollback(); //如果操作失败, 数据回滚
                				$ret= array('status'=>false,'msg'=>yii::t('app','失败1'));
                			}
                			Yii::app()->end(json_encode($ret));
                		}
                }              
                $this->renderPartial('addretreatone' , array(
				'orderRetreat' => $orderRetreat,
				'retreats'=>$retreatslist,
                                'productname'=>$productname,
                		'producttype'=>$producttype,
		));
	}
	public function actionAddHurryOne() {
		$companyId=Yii::app()->request->getParam('companyId','0');
                $orderDetailId=Yii::app()->request->getParam('orderDetailId','0');
                //Yii::app()->end(array('status'=>false,'msg'=>yii::t('app','失败1')));
                $db=Yii::app()->db;
                $orderRetreat = new OrderRetreat();
                $orderRetreat->order_detail_id = $orderDetailId;
                $orderRetreat->dpid = $companyId;
                $retreats = Retreat::model()->findAll(' dpid=:dpid and delete_flag = 0',array(':dpid'=>$companyId));                
                $retreatslist=CHtml::listData($retreats, 'lid', 'name');
                //var_dump($retreatslist);exit;
                $orderDetail = OrderProduct::model()->with("product")->findAll('t.dpid=:dpid and t.lid=:lid',array(':dpid'=>$companyId,':lid'=>$orderDetailId));
                //Yii::app()->end(array('status'=>false,'msg'=>yii::t('app','失败1')));
                $orderId=$orderDetail[0]->order_id;
                //var_dump($orderDetail);exit;
                $productdata=Product::model()->find('lid=:lid and dpid=:dpid' , array(':lid'=>$orderDetail[0]->product_id,':dpid'=>$companyId));
                $productname=$productdata->product_name;
                $ret=array();
		if(Yii::app()->request->isPostRequest){
                    Until::validOperate($companyId, $this);
                    $retreatnum=Yii::app()->request->getPost('retreatnum',0);
                    $othermemo=Yii::app()->request->getPost('othermemo','');
                    $retreatid=Yii::app()->request->getPost('retreatid','');
                    $isall=Yii::app()->request->getPost('isall','1');
                    $padid=Yii::app()->request->getPost('padid','1');
                    $time=date('Y-m-d H:i:s',time());
                    //Yii::app()->end(json_encode(array('status'=>false,'msg'=>$retreatnum.$othermemo.$isall.$padid)));
                    $transaction = Yii::app()->db->beginTransaction();
                    try {
                        $sqlorderproduct="";
                        $memo="催菜单！";

                            //Yii::app()->end(json_encode(array('status'=>false,'msg'=>"23424332")));
                                ////////////////退菜打印
                            $order=new Order();
                            $siteNo=new SiteNo();
                            $site=new Site();
                            ///***********insert to order feedback
                            ///*************print
                            if(!empty($orderId))
                            {
                                $order = Order::model()->with('company')->find(' t.lid=:lid and t.dpid=:dpid and t.order_status in(1,2,3)' , array(':lid'=>$orderId,':dpid'=>$companyId));
                                //Yii::app()->end(json_encode(array('status'=>false,'msg'=>"234")));                    
                                if(empty($order))
                                {
                                    Yii::app()->end(json_encode(array('status'=>false,'msg'=>"该订单不存在")));
                                }
                                $criteria = new CDbCriteria;
                                $criteria->condition =  't.dpid='.$companyId.' and t.site_id='.$order->site_id.' and t.is_temp='.$order->is_temp ;
                                $criteria->order = ' t.lid desc ';                    
                                $siteNo = SiteNo::model()->find($criteria);
                                //order site 和 siteno都需要更新状态 所以要取出来
                                if($order->is_temp=="0")
                                {
                                    $criteria2 = new CDbCriteria;
                                    $criteria2->condition =  't.dpid='.$companyId.' and t.lid='.$order->site_id ;
                                    $criteria2->order = ' t.lid desc ';                    
                                    $site = Site::model()->with("siteType")->find($criteria2);
                                }
                            }
                            //$memo="退菜单";
                            //$orderDetail[0]->amount=$retreatnum;
                            //Yii::app()->end(json_encode(array('status'=>false,'msg'=>$order->dpid)));                           
                            $ret=  Helper::printKitchenHurry($order,$orderDetail,$site,$siteNo,false,$othermemo);                    
                            if(!$ret['status'])
                            {
                                $transaction->rollback();
                            }else{
                                $transaction->commit();
                            }
                            //$ret= json_encode(array('status'=>"1",'msg'=>yii::t('app','退菜成功')));
                        } catch (Exception $e) {
                                $transaction->rollback(); //如果操作失败, 数据回滚
                                $ret= array('status'=>false,'msg'=>yii::t('app','失败1'));
                        } 
                        Yii::app()->end(json_encode($ret));                    
                }                
                $this->renderPartial('addhurryone' , array(
				'orderRetreat' => $orderRetreat,
				'retreats'=>$retreatslist,
                                'productname'=>$productname
		));
	}    
	
	
        public function actionSelectAllDiscount() {
                $companyId=Yii::app()->request->getParam('companyId','0');
                //Yii::app()->end(array('status'=>false,'msg'=>yii::t('app','失败1')));
                //$db=Yii::app()->db;
                $alldiscounts = Discount::model()->findAll(' dpid=:dpid and delete_flag = 0',array(':dpid'=>$companyId)); 
                
                //$alldiscountslist=CHtml::listData($alldiscounts, 'lid', 'discount_name','discount_num');
                //var_dump($alldiscountslist);exit;
                $this->renderPartial('selectalldiscount' , array(
				'alldiscounts'=>$alldiscounts
		));
	}
        
        public function actionEditRetreat() {
                $companyId=Yii::app()->request->getParam('companyId','0');
                $orderRetreatId=Yii::app()->request->getParam('orderRetreatId','0');
                $orderRetreat = OrderRetreat::model()->with('retreat')->find(' t.dpid=:dpid and t.lid=:lid and t.delete_flag=0',  array(':dpid'=>$companyId,':lid'=>$orderRetreatId));
                
		if(Yii::app()->request->isPostRequest){
                    Until::validOperate($companyId, $this);
                    $orderRetreat->attributes = Yii::app()->request->getPost('OrderRetreat');
                    
                    if($orderRetreat->save()){                                
                        echo json_encode(array('msg'=>yii::t('app','成功')));
                    }else{
                        echo json_encode(array('msg'=>yii::t('app','失败')));
                    }                    
                    return;
                }                
                $this->renderPartial('editRetreat' , array(
				'orderRetreat' => $orderRetreat                                
		));
	}
        
        
        public function actionWeightProduct(){
		$id = Yii::app()->request->getParam('id',0);
		$typeId = Yii::app()->request->getParam('typeId');
		$companyId = Yii::app()->request->getParam('companyId');
                $orderProduct = OrderProduct::model()->with(array('product'))->find('t.lid=:id and t.dpid=:dpid',array(':id'=>$id,':dpid'=>$companyId));
                //}
                if(Yii::app()->request->isPostRequest) {
                        Until::validOperate($companyId, $this);
                        $orderProduct->attributes = Yii::app()->request->getPost('OrderProduct');
                        if($orderProduct->save())
                        {
                            Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
                            //echo '333';exit;
                            $this->redirect(array('defaultOrder/order' , 'companyId' => $this->companyId,'orderId' => $orderProduct->order_id,'typeId'=>$typeId));
                        } else {
                            Yii::app()->user->setFlash('success' , yii::t('app','添加失败'));
                            return false;
                    }
		}
		$this->renderPartial('weightdetail' , array(
                                //'setid'=>$setid,
                                //'orderid'=>$orderId,
				'orderProduct' => $orderProduct,
                                'typeId'=>$typeId
                                //'models' => $models,
                                //'modelsp' => $modelsp
		));
	}
        
        public function actionEditProduct(){
		$id = Yii::app()->request->getParam('id',0);
		$setid = Yii::app()->request->getParam('setid',0);
		$orderId = Yii::app()->request->getParam('orderId');
		$typeId = Yii::app()->request->getParam('typeId');
		$companyId = Yii::app()->request->getParam('companyId');
                //$orderProduct=null;
                //$models=null;
                //$modelsp=null;
                //if($setid=='0000000000')
                //{
                $orderProduct = OrderProduct::model()->with(array('product','productSet'))->find('t.lid=:id and t.dpid=:dpid',array(':id'=>$id,':dpid'=>$companyId));
                //}
                if(Yii::app()->request->isPostRequest) {
                    Until::validOperate($companyId, $this);
                        $isset = Yii::app()->request->getPost('isset',0);
                        //$setid = Yii::app()->request->getParam('setid',0);
                        $selsetlist = Yii::app()->request->getPost('selsetlist',0);
                        //var_dump($orderProduct);exit;
                        $orderProduct->attributes = Yii::app()->request->getPost('OrderProduct');
                        //var_dump(Yii::app()->request->getPost('OrderProduct'));exit;
                        //var_dump($selsetlist,$isset);exit;
                        $db = Yii::app()->db;
                        $transaction = $db->beginTransaction();
                        try {                           
                            if($isset==0)
                            {                        
                                
                                $orderProduct->save();
                            }else{
                                if(strlen($selsetlist)>10)
                                {                                   
                                    $productIdlist=explode(',',$selsetlist);
                                    //$setid=Yii::app()->request->getPost('OrderProduct');
                                    //var_dump($setid['set_id']);exit;
                                    //$db->createCommand('delete from nb_order_product where set_id=:setid and dpid=:dpid')->execute(array(':setid'=>$orderProduct->set_id,':dpid'=>$companyId));
                                    $db->createCommand('update nb_order_product set delete_flag="1" where set_id=:setid and dpid=:dpid')->execute(array(':setid'=>$orderProduct->set_id,':dpid'=>$companyId));
                                    foreach ($productIdlist as $productId){
                                        //var_dump($productId);exit;
                                        $sorderProduct = new OrderProduct();
                                        $sorderProduct->dpid = $companyId;
                                        $sorderProduct->delete_flag = '0';
                                        $sorderProduct->product_order_status = '0';
                                        $sorderProduct->set_id=$orderProduct->set_id;
                                        $sorderProduct->order_id=$orderProduct->order_id;
                                        $sorderProduct->create_at = date('Y-m-d H:i:s',time());
                                        $productUnit=explode('|',$productId);
                                        $sorderProduct->product_id = $productUnit[0];
                                        $sorderProduct->amount = $productUnit[1];
                                        $sorderProduct->price = $productUnit[2];
                                        $sorderProduct->is_giving = '0';
                                        $sorderProduct->product_status = '0';//添加cf
                                        $sorderProduct->zhiamount = 0;                                    
                                        $se=new Sequence("order_product");
                                        $sorderProduct->lid = $se->nextval();
                                        //var_dump($sorderProduct);exit;
                                        $sorderProduct->save();                                    
                                    }
                                }
                            }
                            $transaction->commit();
                            Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
                            //echo '333';exit;
                            $this->redirect(array('defaultOrder/order' , 'companyId' => $this->companyId,'orderId' => $orderProduct->order_id,'typeId'=>$typeId));
                        } catch (Exception $e) {
                            $transaction->rollback(); //如果操作失败, 数据回滚
                            //var_dump($e);
                            //echo json_encode(array('status'=>0,'message'=>'换台失败'));
                            Yii::app()->user->setFlash('success' , yii::t('app','添加失败'));
                            return false;
                    }
		}
		$this->renderPartial('editproduct' , array(
                                //'setid'=>$setid,
                                'orderid'=>$orderId,
				'orderProduct' => $orderProduct,
                                'typeId'=>$typeId
                                //'models' => $models,
                                //'modelsp' => $modelsp
		));
	}
        
        public function actionCurrentprice(){
                $id = Yii::app()->request->getParam('id',0);
		$companyId = Yii::app()->request->getParam('companyId');
                $currentPrice=CreateOrder::getProductPrice($companyId,$id,0);
                echo json_encode(array('cp'=>$currentPrice));
        }
        
        public function actionRetreatTip(){
                $id = Yii::app()->request->getParam('id',0);
		$companyId = Yii::app()->request->getParam('companyId');
                $retreat=  Retreat::model()->find(' lid=:id and dpid=:dpid',  array(':id'=>$id,':dpid'=>$companyId));
                echo json_encode(array('cp'=>$retreat->tip));
        }    
        
        public function actionPrintKitchen(){
                $orderId = Yii::app()->request->getParam('orderId',0);
		$companyId = Yii::app()->request->getParam('companyId');
                $typeId =  Yii::app()->request->getParam('typeId');
                $callId =  Yii::app()->request->getParam('callId');
                Until::validOperate($companyId, $this);
                $db = Yii::app()->db;              
                $ret=array();
                //var_dump(Yii::app()->params->has_cache);exit;
                $transaction = $db->beginTransaction();
                try {
                        $order = Order::model()->with('company')->find('t.lid=:id and t.dpid=:dpid' , array(':id'=>$orderId,':dpid'=>$companyId));
                        //var_dump($order);exit;
                        $criteria = new CDbCriteria;
                        $criteria->condition =  't.status in ("1","2","3") and t.dpid='.$order->dpid.' and t.site_id='.$order->site_id.' and t.is_temp='.$order->is_temp ;
                        $criteria->order = ' t.lid desc ';
                        $siteNo = SiteNo::model()->find($criteria);
                        //var_dump($siteNo);exit;
                        if($siteNo->is_temp=='0')
                        {
                            $site = Site::model()->with('siteType')->find('t.lid=:lid and t.dpid=:dpid',  array(':lid'=>$order->site_id,':dpid'=>$order->dpid));
                            $site->status = '2';
                            $site->save();
                        }else{
                            $site = new Site();
                        }
                        
                        $orderProducts = OrderProduct::model()->with('product')->findAll('t.order_id=:id and t.dpid=:dpid and t.delete_flag=0' , array(':id'=>$orderId,':dpid'=>$companyId));
                        $order->order_status='2';
                        $order->callno=$callId;
                        $order->save();
                        $siteNo->status='2';
                        $siteNo->save();
                        $jobids=array();
                        //var_dump($orderProducts);exit;
                        foreach($orderProducts as $orderProduct)
                        {
                            $reprint = false;
                            //var_dump($orderProduct);exit;
                            if($orderProduct->is_print=='0')
                            {
                                //echo $orderProduct->is_print; exit;
                                $tempprintret=Helper::printKitchen($order,$orderProduct,$site,$siteNo ,$reprint);
                                sleep(4);
                                //echo $tempprintret;exit;
                                //if($tempprintret['status'])
                                //{
                                    array_push($jobids,$tempprintret['jobid']."_".$orderProduct->lid);//如果失败jobid==0，检测时判断就行
                                //}
                                //$orderProduct->is_print='1';
                                $orderProduct->product_order_status='1';
                                $orderProduct->save();
                                
                                if($orderProduct->set_id!="0000000000")
                                {
                                    $productset=  ProductSet::model()->find('t.lid=:id and t.dpid=:dpid' , array(':id'=>$orderProduct->set_id,':dpid'=>$companyId));
                                    if(!empty($productset))
                                    {
                                        $productset->order_number++;
                                        $productset->save();
                                    }
                                }
                                $product=  Product::model()->find('t.lid=:id and t.dpid=:dpid' , array(':id'=>$orderProduct->product_id,':dpid'=>$companyId));
                                if(!empty($product))
                                {
                                    $product->order_number++;
                                    $product->save();
                                }
                            
//                                $product=  Product::model()->find('t.lid=:id and t.dpid=:dpid' , array(':id'=>$orderProduct->product_id,':dpid'=>$companyId));
//                                $product->order_number++;
//                                $product->save();
                            }                            
                        }
                        $transaction->commit();
                        //var_dump(json_encode($jobids));exit;
//                        Gateway::getOnlineStatus();
//                        $store = Store::instance('wymenu');
                        $store=new Memcache;
                        $store->connect(Yii::app()->params['memcache']['server'],Yii::app()->params['memcache']['port']);
                
                        $store->set("kitchenjobs_".$companyId."_".$orderId,json_encode($jobids),0,300);    
                        $store->close();
                        $ret=array('status'=>true,'allnum'=>count($jobids),'msg'=>yii::t('app','打印任务正常发布'));
                } catch (Exception $e) {
                        $transaction->rollback(); //如果操作失败, 数据回滚
                        $ret=array('status'=>false,'allnum'=>count($jobids),'msg'=>yii::t('app','打印任务发布异常'));
                        Yii::app()->end(json_encode($ret));
                }
                $this->renderPartial('printresultlist' , array(
                                'orderId'=>$orderId,
				'ret' => $ret,// job in memcached
                                'typeId'=>$typeId,
                                'callId'=>$callId
		));
		/*/////////////test
                Gateway::getOnlineStatus();
                $se=new Sequence("printer_job_id");
                $jobid = $se->nextval();
                $test_print_data=array(
                    "company_id"=>  $this->companyId,
                    "job_id"=>$jobid,
                    "printer"=>"192.168.63.100",
                    "content"=>"BBB6D3ADCAB9D3C30A0A0A0A0A0A1D5601"
                );
                $store = Store::instance('wymenu');
                $clientId=$store->get("client_".$companyId);
                var_dump($clientId, json_encode($test_print_data));
                if(!empty($clientId))
                {
                    Gateway::sendToClient($clientId,json_encode($test_print_data));
                }
                exit;*/
                ///////////test                
        }
        
        public function actionPrintKitchenAll(){
                $orderId = Yii::app()->request->getParam('orderId',0);
		$companyId = Yii::app()->request->getParam('companyId');
                $typeId =  Yii::app()->request->getParam('typeId');
                $callId =  Yii::app()->request->getParam('callId');
                Until::validOperate($companyId, $this);
                $db = Yii::app()->db;              
                $ret=array();
                //var_dump(Yii::app()->params->has_cache);exit;
                $transaction = $db->beginTransaction();
                try {
                        $order = Order::model()->with('company')->find('t.lid=:id and t.dpid=:dpid' , array(':id'=>$orderId,':dpid'=>$companyId));
                        //var_dump($order);exit;
                        $criteria = new CDbCriteria;
                        $criteria->condition =  't.status in ("1","2","3") and t.dpid='.$order->dpid.' and t.site_id='.$order->site_id.' and t.is_temp='.$order->is_temp ;
                        $criteria->order = ' t.lid desc ';
                        $siteNo = SiteNo::model()->find($criteria);
                        //var_dump($siteNo);exit;
                        if($siteNo->is_temp=='0')
                        {
                            $site = Site::model()->with('siteType')->find('t.lid=:lid and t.dpid=:dpid',  array(':lid'=>$order->site_id,':dpid'=>$order->dpid));
                            if($site->status<'2')
                            {
                                $site->status = '2';
                                $site->save();
                            }
                        }else{
                            $site = new Site();
                        }
                        if($order->order_status<'2')
                        {
                            $order->order_status='2';
                        }
                        $order->callno=$callId;
                        $order->save();
                        if($siteNo->status<'2')
                        {
                            $siteNo->status='2';
                        }
                        //$siteNo->status='2';
                        $siteNo->save();
                        
                        $sqlorderproduct="update nb_order_product set product_order_status='1' where dpid=:companyId and order_id=:orderId";
                        $commandorderproduct=Yii::app()->db->createCommand($sqlorderproduct);
                        $commandorderproduct->bindValue(":orderId" , $orderId);
                        $commandorderproduct->bindValue(":companyId" , $companyId);
                        $commandorderproduct->execute();
//                        var_dump($sqlorderproduct);exit;
                        $sqlproduct='update nb_product set order_number=order_number+1 where dpid='.$companyId.' and lid in (select distinct product_id from nb_order_product where dpid='.$companyId.' and order_id='.$orderId.')';
                        $commandproduct=Yii::app()->db->createCommand($sqlproduct);//->execute();
//                        $commandproduct->bindValue(":orderId" , $orderId);
//                        $commandproduct->bindValue(":companyId" , $companyId);
//                        $commandproduct->bindValue(":sdpid" , $companyId);
                        $commandproduct->execute();
                        //var_dump($commandproduct);exit;
                        $sqlproductset="update nb_product_set set order_number=order_number+1 where dpid=:companyId and lid in (select distinct set_id from nb_order_product where dpid=:sdpid and order_id=:orderId)";
                        $commandproductset=Yii::app()->db->createCommand($sqlproductset);
                        $commandproductset->bindValue(":orderId" , $orderId);
                        $commandproductset->bindValue(":companyId" , $companyId);
                        $commandproductset->bindValue(":sdpid" , $companyId);
                        $commandproductset->execute();
                        
                        
                        $transaction->commit();
                        $jobids=array();
                        //var_dump($order);exit;
                        //printKitchenAll所有的打印在一张单子上，口味等不打印
                        //printKitchenAll2在同一个打印机输出的就打印在一张单子上，口味等也打印
                       /*$tempret=Helper::printKitchenAll($order , $site,$siteNo,false); 
                       //var_dump($tempret);exit;
                        if($tempret['status'])
                        {
                            array_push($jobids,$tempret['jobid']."_".$order->lid);
                            //var_dump(json_encode($jobids));exit;
                            Gateway::getOnlineStatus();
                            $store = Store::instance('wymenu');
                            $store->set("kitchenjobs_".$companyId."_".$orderId,json_encode($jobids),0,300);                        
                            $ret=array('status'=>true,'allnum'=>count($jobids),'msg'=>yii::t('app','打印任务正常发布'));
                        }  else {
                            
                            $ret=array('status'=>false,'allnum'=>count($jobids),'msg'=>$tempret['msg']);
                             
                            //Yii::app()->end(json_encode($ret));
                        }*/
//                        $deeeeeee="3r42rfwrewr324r";
//                     var_dump($deeeeeee);exit;
                         $ret=Helper::printKitchenAll2($order , $site,$siteNo,false); 
                        
                       
                } catch (Exception $e) {
                        $transaction->rollback(); //如果操作失败, 数据回滚
                        $ret=array('status'=>false,'allnum'=>count($jobids),'msg'=>yii::t('app','打印任务发布异常'));
                        //Yii::app()->end(json_encode($ret));
                }
                $this->renderPartial('printresultlistall' , array(
                                'orderId'=>$orderId,
				'ret' => $ret,// job in memcached
                                'typeId'=>$typeId,
                                'callId'=>$callId
		));                
        }
        /*
         * 该函数暂时不用
         */
        public function actionPrintKitchenAll2(){
                $orderId = Yii::app()->request->getParam('orderId',0);
		$companyId = Yii::app()->request->getParam('companyId');
                $typeId =  Yii::app()->request->getParam('typeId');
                $callId =  Yii::app()->request->getParam('callId');
                Until::validOperate($companyId, $this);
                $db = Yii::app()->db;              
                $ret=array();
                //var_dump(Yii::app()->params->has_cache);exit;
                $transaction = $db->beginTransaction();
                try {
                        $order = Order::model()->with('company')->find('t.lid=:id and t.dpid=:dpid' , array(':id'=>$orderId,':dpid'=>$companyId));
                        //var_dump($order);exit;
                        $criteria = new CDbCriteria;
                        $criteria->condition =  't.status in ("1","2","3") and t.dpid='.$order->dpid.' and t.site_id='.$order->site_id.' and t.is_temp='.$order->is_temp ;
                        $criteria->order = ' t.lid desc ';
                        $siteNo = SiteNo::model()->find($criteria);
                        //var_dump($siteNo);exit;
                        if($siteNo->is_temp=='0')
                        {
                            $site = Site::model()->with('siteType')->find('t.lid=:lid and t.dpid=:dpid',  array(':lid'=>$order->site_id,':dpid'=>$order->dpid));
                            $site->status = '2';
                            $site->save();
                        }else{
                            $site = new Site();
                        }                        
                        $order->order_status='2';
                        $order->callno=$callId;
                        $order->save();
                        $siteNo->status='2';
                        $siteNo->save();
                        $transaction->commit();
                        //$jobids=array();
                        $ret=Helper::printKitchenAll2($order , $site,$siteNo,false); 
                        //array_push($jobids,$tempret['jobid']."_".$order->lid);
                        //var_dump(json_encode($jobids));exit;
                        //Gateway::getOnlineStatus();
                        //$store = Store::instance('wymenu');
                        //$store->set("kitchenjobs_".$companyId."_".$orderId,json_encode($jobids),0,300);                        
                        //$ret=array('status'=>true,'allnum'=>count($jobids),'msg'=>'打印任务正常发布');
                } catch (Exception $e) {
                        $transaction->rollback(); //如果操作失败, 数据回滚
                        $ret=array('status'=>false,'allnum'=>count($jobids),'msg'=>yii::t('app','打印任务发布异常'));
                        Yii::app()->end(json_encode($ret));
                }
                $this->renderPartial('printresultlistall' , array(
                                'orderId'=>$orderId,
				'ret' => $ret,// job in memcached
                                'typeId'=>$typeId,
                                'callId'=>$callId
		));                
        }
        
        public function actionPrintOneKitchen(){
                $orderProductId = Yii::app()->request->getParam('orderProductId',0);
				$companyId = Yii::app()->request->getParam('companyId');
                $typeId =  Yii::app()->request->getParam('typeId',0);
                Until::validOperate($companyId, $this);
                $db = Yii::app()->db;              
//                 $ret=array('status'=>false,'jobid'=>"0",'type'=>'none','msg'=>yii::t('app','发生异常'));
//                 Yii::app()->end(json_encode($ret));
                //var_dump(Yii::app()->params->has_cache);exit;
                //$transaction = $db->beginTransaction();
                try {
                        $orderProduct = OrderProduct::model()->with('product')->find('t.lid=:id and t.dpid=:dpid and t.delete_flag=0' , array(':id'=>$orderProductId,':dpid'=>$companyId));                        
                        $order = Order::model()->with('company')->find('t.lid=:id and t.dpid=:dpid' , array(':id'=>$orderProduct->order_id,':dpid'=>$companyId));
                        $criteria = new CDbCriteria;
                        $criteria->condition =  't.dpid='.$order->dpid.' and t.site_id='.$order->site_id.' and t.is_temp='.$order->is_temp ;
                        
                        //$criteria->condition =  't.status in ("1","2","3") and t.dpid='.$order->dpid.' and t.site_id='.$order->site_id.' and t.is_temp='.$order->is_temp ;
                        $criteria->order = ' t.lid desc ';
                        $siteNo = SiteNo::model()->find($criteria);
                        if($siteNo->is_temp=='0')
                        {
                            $site = Site::model()->with('siteType')->find('t.lid=:lid and t.dpid=:dpid',  array(':lid'=>$order->site_id,':dpid'=>$order->dpid));                            
                        }else{
                            $site = new Site();
                        }
//                         if($orderProduct->is_print=='0')
//                         {
                             $reprint=false;
//                         }else{
//                             $reprint=true;
//                         }
                        $ret=Helper::printKitchen($order,$orderProduct,$site,$siteNo ,$reprint);
//                        if($orderProduct->is_print=='0')
//                        {
//                            $orderProduct->is_print='1';
//                            $orderProduct->save();
//                        }
                        //$transaction->commit();
                        //$ret=array('status'=>true,'jobid'=>"",'type'=>'none','msg'=>'发生异常');
                } catch (Exception $e) {
                        //$transaction->rollback(); //如果操作失败, 数据回滚
                        //var_dump($e);exit;
                        $ret=array('status'=>false,'jobid'=>"0",'type'=>'none','msg'=>yii::t('app','发生异常'));
                        Yii::app()->end(json_encode($ret));
                }
                //var_dump($ret);exit;
//                 $this->renderPartial('printresultone' , array(
//                                 'orderId'=>$order->lid,
//                                 'orderProductId'=>$orderProductId,
//                                 'ret'=>$ret,
//                                 //'joblist' => $joblist, job in memcached
//                                 'typeId'=>$typeId                                
// 		));		             
        }
        /**
         * 每个菜品一张单子
         */
        public function actionPrintKitchenResult(){
                $orderId = Yii::app()->request->getParam('orderId',0);
		$companyId = Yii::app()->request->getParam('companyId');
                $timenum =  Yii::app()->request->getParam('timenum');
                Until::validOperate($companyId, $this);
                $db = Yii::app()->db;
                $finished=false;
                $successnum=0;
                $errornum=0;
                $notsurenum=0;
                
//                Gateway::getOnlineStatus();
//                $store = Store::instance('wymenu');
                $store=new Memcache;
                $store->connect(Yii::app()->params['memcache']['server'],Yii::app()->params['memcache']['port']);
                
                $joblist=json_decode($store->get("kitchenjobs_".$companyId."_".$orderId),true);
                foreach ($joblist as $job_orderproduct_id)
                {
                    $ids=explode('_',$job_orderproduct_id);
                    $jobid=$ids[0];
                    if($jobid=='0')
                    {
                        $errornum++;
                        continue;
                    }
                    $jobresult=$store->get('job_'.$companyId."_".$jobid.'_result');
                    if(empty($jobresult))
                    {
                        $notsurenum++;
                    }else{
                        if($jobresult=="success")
                        {
                            //update status//
                            $orderProduct=  OrderProduct::model()->find(' dpid=:dpid and lid=:lid', array(':dpid'=>$companyId,':lid'=>$ids[1]));
                            if($orderProduct->is_print=='0')
                            {
                                $orderProduct->is_print='1';
                                $orderProduct->save();          
                                
                            }
                            $successnum++;
                        }else{
                            $errornum++;
                        }
                    }
                }
                if($timenum==0 || $notsurenum==0)
                {
                    $finished=true;
                }
                $ret=array('finished'=>$finished,'successnum'=>$successnum,'errornum'=>$errornum,'notsurenum'=>$notsurenum);
                $store->close();
                Yii::app()->end(json_encode($ret));
                //get status from memcache
                //if error change product kitchen status in db
                //if timenum=0 return finish or all success
        }
        
        //同一个厨打菜品在同一个单子上打印时的结果查询
        //目前没有完善的很仔细，都在一张默认的单子上打印的
        //15/06/23已经完善了printKitchenAll2，这里进一步完善
        
        public function actionPrintKitchenResultAll(){
                $orderId = Yii::app()->request->getParam('orderId',0);
		$companyId = Yii::app()->request->getParam('companyId');
                $timenum =  Yii::app()->request->getParam('timenum');
                Until::validOperate($companyId, $this);
                $db = Yii::app()->db;
                $finished=false;
                $successnum=0;
                $errornum=0;
                $notsurenum=0;
                
//                Gateway::getOnlineStatus();
//                $store = Store::instance('wymenu');
                $store=new Memcache;
                $store->connect(Yii::app()->params['memcache']['server'],Yii::app()->params['memcache']['port']);
                
                $joblist=json_decode($store->get("kitchenjobs_".$companyId."_".$orderId),true);
                if(!empty($joblist))
                {
                    foreach ($joblist as $job_orderproduct_id)
                    {
                        $ids=explode('_',$job_orderproduct_id);
                        $jobid=$ids[0];
                        if($jobid=='0')
                        {
                            $errornum++;
                            continue;
                        }
                        $jobresult=$store->get('job_'.$companyId."_".$jobid.'_result');
                        if(empty($jobresult))
                        {
                            $notsurenum++;
                        }else{
    //                        $sqlorderproduct="update nb_order_product set is_print='1' where dpid=".$companyId." and lid in (".$ids[1].")";
    //                        var_dump($sqlorderproduct);exit;
                            if($jobresult=="success")
                            {
                                //$sqlorderproduct="update nb_order_product set is_print='1',product_order_status='1' where dpid=:companyId and order_id=:orderId";
                                $sqlorderproduct="update nb_order_product set is_print='1' where dpid=".$companyId." and lid in (".$ids[1].")";
                                $commandorderproduct=Yii::app()->db->createCommand($sqlorderproduct);
                                //$commandorderproduct->bindValue(":companyId" , $companyId);
                                $commandorderproduct->execute();
                                //update status//
                                //$orderProduct=  OrderProduct::model()->find(' dpid=:dpid and lid=:lid', array(':dpid'=>$companyId,':lid'=>$ids[1]));
                                //if($orderProduct->is_print=='0')
                                //{
                                //    $orderProduct->is_print='1';
                                //    $orderProduct->save();
                                //}
                                $successnum++;
                            }else{
                                $errornum++;
                            }
                        }
                    }
                }
                if($timenum==0 || $notsurenum==0)
                {
                    $finished=true;
                }
                $ret=array('finished'=>$finished,'successnum'=>$successnum,'errornum'=>$errornum,'notsurenum'=>$notsurenum);
                $store->close();
                Yii::app()->end(json_encode($ret));
                //get status from memcache
                //if error change product kitchen status in db
                //if timenum=0 return finish or all success
        }
        
        public function actionPrintKitchenResultOne(){
                $companyId = Yii::app()->request->getParam('companyId');
                $jobid =  Yii::app()->request->getParam('jobid');
                $orderProductId =  Yii::app()->request->getParam('orderProductId');
                Until::validOperate($companyId, $this);
                $db = Yii::app()->db;
                
                //$jobstatus=false;
//                Gateway::getOnlineStatus();
//                $store = Store::instance('wymenu');
                $store=new Memcache;
                $store->connect(Yii::app()->params['memcache']['server'],Yii::app()->params['memcache']['port']);
                                
                $jobresult=$store->get('job_'.$companyId."_".$jobid.'_result');
                //var_dump($jobresult);exit;
                if(empty($jobresult))
                {
                    $ret=array('status'=>false,'msg'=>yii::t('app','任务未返回'));
                }else{
                    if($jobresult=="success")
                    {
                        //var_dump($companyId,$orderProductId);exit;
                        $orderProduct=  OrderProduct::model()->find(' dpid=:dpid and lid=:lid', array(':dpid'=>$companyId,':lid'=>$orderProductId));
//                        if($orderProduct->is_print=='0')
//                        {
//                            $orderProduct->is_print='1';
//                            $orderProduct->save();
//                        }
                        if($orderProduct->is_print=='0')
                        {
                            $orderProduct->is_print='1';
                            $orderProduct->save();                            
                        }
                        $ret=array('status'=>true,'msg'=>yii::t('app','打印成功'));
                    }else{
                        $ret=array('status'=>false,'msg'=>yii::t('app','打印机执行任务失败'));
                    }
                }     
                $store->close();
                Yii::app()->end(json_encode($ret));
                //get status from memcache
                //if error change product kitchen status in db
                //if timenum=0 return finish or all success
        }
        
        public function actionPrintPadList(){                
                $id = Yii::app()->request->getParam('id',0);
		$companyId = Yii::app()->request->getParam('companyId');
                $padId = Yii::app()->request->getParam('padId');
                Until::validOperate($companyId, $this);
                $order = Order::model()->with('company')->find('t.lid=:id and t.dpid=:dpid' , array(':id'=>$id,':dpid'=>$companyId));
                $pad=Pad::model()->find(' dpid=:dpid and lid=:lid',array(':dpid'=>$order->dpid,'lid'=>$padId));
                //要判断打印机类型错误，必须是local。
                if($pad->printer_type!='1')
                {
                    Yii::app()->end(json_encode(array('status'=>false,'jobid'=>"0",'type'=>'local','msg'=>yii::t('app','必须是本地打印机！'))));
                }else{
                    //前面加 barcode
                    $precode="1D6B450B".strtoupper(implode('',unpack('H*', 'A'.$order->lid)))."0A".strtoupper(implode('',unpack('H*', 'A'.$order->lid)))."0A";
                    Yii::app()->end(json_encode(Helper::printList($order , $pad,$precode)));
                }
                /*//////////////////////test
                Gateway::getOnlineStatus();
                $store = Store::instance('wymenu');
                $se=new Sequence("printer_job_id");
                $jobid = $se->nextval();
                $ret = $store->set($companyId."_".$jobid,'1C43011C2688A488A482AE82AF82B182F182C982BF82CD0A0A0A0A0A0A1D5601',0,60);
                echo Yii::app()->end(json_encode(array('status'=>true,'jobid'=>$jobid)));
                exit;*/
                /*
                 $('#print-btn').click(function(){
                            if (typeof Androidwymenuprinter == "undefined") {
                                alert("无法获取PAD设备信息，请在PAD中运行该程序！");
                                return false;
                            }
                            var company_id="<?php echo $this->companyId ?>"                            
                            $.get('<?php echo $this->createUrl('defaultOrder/printPadList',array('companyId'=>$this->companyId,'id'=>$model->lid));?>',function(data){
                                    if(data.status) {
                                        if(Androidwymenuprinter.printJob(company_id,data.jobid))
                                        {
                                            alert("打印成功");
                                        }
                                        else
                                        {
                                            alert("PAD打印失败！，请确认打印机连接好后再试！");                                                                        
                                        }                                                
                                    } else {
                                            alert(data.msg);
                                    }
                            },'json');
                        }); 
                 
                 */
                ////////////////////////test
        }
        
        public function actionPrintList(){                
                $orderId = Yii::app()->request->getParam('orderId',0);
		//$companyId = Yii::app()->request->getParam('companyId');
                $padId = Yii::app()->request->getParam('padId');
                $typeId = Yii::app()->request->getParam('typeId');
                Until::validOperate($companyId, $this);
                $order = Order::model()->with('company')->find('t.lid=:id and t.dpid=:dpid' , array(':id'=>$orderId,':dpid'=>$this->companyId));
                $pad=Pad::model()->find(' dpid=:dpid and lid=:lid',array(':dpid'=>$order->dpid,'lid'=>$padId));
                //前面加 barcode
                if(!empty($pad))
                {
                    $precode="";
                    //$precode="1D6B450B".strtoupper(implode('',unpack('H*', 'A'.$order->lid)))."0A".strtoupper(implode('',unpack('H*', 'A'.$order->lid)))."0A";

                    //Yii::app()->end(json_encode(Helper::printList($order , $padid)));
                    $printserver="1";
                    $memo="";
                    $cardtotal=0;
                    $ret=Helper::printList($order , $pad,$precode,$printserver,$memo,$cardtotal);
                }else{
                    $ret=array('status'=>false,'dpid'=>$order->dpid,'jobid'=>"0",'type'=>'none','msg'=>yii::t('app','PAD不存在！'));
                }
                //exit;
                $this->renderPartial('printlist' , array(
                                'orderId'=>$orderId,
                                //'companyId'=>$companyId,
                                'ret'=>$ret,
                                'typeId'=>$typeId                                
		));
        }
        
        public function actionPrintListNetResult(){
                $companyId = Yii::app()->request->getParam('companyId');
                $jobid =  Yii::app()->request->getParam('jobid');
                
//                Gateway::getOnlineStatus();
//                $store = Store::instance('wymenu');
                $store=new Memcache;
                $store->connect(Yii::app()->params['memcache']['server'],Yii::app()->params['memcache']['port']);
                
                $jobresult=$store->get('job_'.$companyId."_".$jobid.'_result');
                $store->close();
                //var_dump($jobresult);exit;
                if(empty($jobresult))
                {
                    $ret=array('status'=>false,'msg'=>yii::t('app','任务未返回'));
                }else{
                    if($jobresult=="success")
                    {                        
                        $ret=array('status'=>true,'msg'=>yii::t('app','打印成功'));
                    }else{
                        $ret=array('status'=>false,'msg'=>yii::t('app','打印机执行任务失败'));
                    }
                }               
                Yii::app()->end(json_encode($ret));                
        }
}