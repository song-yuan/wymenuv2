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
                    $criteria->condition =  ' t.status in ("1","2","3") and  t.dpid='.$companyId.' and t.site_id='.$sid.' and t.is_temp='.$istemp ;
                    $criteria->order = ' t.lid desc ';
                    $siteNo = SiteNo::model()->find($criteria);
                }
                //var_dump($order);exit;
                if(empty($order))
                {
                    $order=new Order();
                    $se=new Sequence("order");
                    $order->lid = $se->nextval();
                    $order->dpid=$companyId;
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
                
                $paymentMethods = PaymentClass::getPaymentMethodList($companyId);
                //var_dump($paymentMethods);exit;
                if(Yii::app()->request->isPostRequest){
                        //var_dump(Yii::app()->request->getPost('Order'));exit;
                        $order->attributes = Yii::app()->request->getPost('Order');
                        $order->pay_time = date('Y-m-d H:i:s',time());
                        $orderpay->attributes = Yii::app()->request->getPost('OrderPay');
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
                            $precode="1B70001EFF00";//开钱箱
                            $printserver="1";                            
                            $ret=Helper::printList($order , $pad,$precode,$printserver,$memo);
                            $this->redirect(array('default/index' , 'companyId' => $this->companyId,'typeId'=>$typeId));
                            
			} catch(Exception $e){
				$transaction->rollback();
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
                            $precode="1B70001EFF00";//开钱箱
                            $printserver="1";                            
                            $ret=Helper::printList($order , $pad,$precode,$printserver,$memo);
                            //$ret=array('status'=>false,'dpid'=>"0000000011",'jobid'=>"0",'type'=>'none','msg'=>yii::t('app','没有要打印的菜品！'));
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
        
        public function actionRetreatProduct(){
		$id = Yii::app()->request->getParam('id',0);
		$typeId = Yii::app()->request->getParam('typeId');
		$companyId = Yii::app()->request->getParam('companyId');
                if(Yii::app()->request->isPostRequest) {
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
                    $orderRetreat->attributes = Yii::app()->request->getPost('OrderRetreat');
                    $orderRetreat->create_at = date('Y-m-d H:i:s',time());
                    $se=new Sequence("order_retreat");
                    $orderRetreat->lid = $se->nextval();
                    if($orderRetreat->save()){                                
                        echo json_encode(array('msg'=>yii::t('app','成功')));
                    }else{
                        echo json_encode(array('msg'=>yii::t('app','失败')));
                    }                    
                    return;
                }                
                $this->renderPartial('addretreat' , array(
				'orderRetreat' => $orderRetreat,
				'retreats'=>$retreatslist                                
		));
	}
        
        public function actionEditRetreat() {
                $companyId=Yii::app()->request->getParam('companyId','0');
                $orderRetreatId=Yii::app()->request->getParam('orderRetreatId','0');
                $orderRetreat = OrderRetreat::model()->with('retreat')->find(' t.dpid=:dpid and t.lid=:lid and t.delete_flag=0',  array(':dpid'=>$companyId,':lid'=>$orderRetreatId));
                
		if(Yii::app()->request->isPostRequest){                        
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
                                    $db->createCommand('delete from nb_order_product where set_id=:setid and dpid=:dpid')->execute(array(':setid'=>$orderProduct->set_id,':dpid'=>$companyId));
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
                        Gateway::getOnlineStatus();
                        $store = Store::instance('wymenu');
                        $store->set("kitchenjobs_".$companyId."_".$orderId,json_encode($jobids),0,300);                        
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
                        
                        $sqlproduct="update nb_product set order_number=order_number+1 where dpid=:companyId and lid in (select distinct product_id from nb_order_product where dpid=:sdpid and order_id=:orderId)";
                        $commandproduct=Yii::app()->db->createCommand($sqlproduct);
                        $commandproduct->bindValue(":orderId" , $orderId);
                        $commandproduct->bindValue(":companyId" , $companyId);
                        $commandproduct->bindValue(":sdpid" , $companyId);
                        $commandproduct->execute();
                        
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
                $typeId =  Yii::app()->request->getParam('typeId');
                $db = Yii::app()->db;              
                
                //var_dump(Yii::app()->params->has_cache);exit;
                //$transaction = $db->beginTransaction();
                try {
                        $orderProduct = OrderProduct::model()->with('product')->find('t.lid=:id and t.dpid=:dpid and t.delete_flag=0' , array(':id'=>$orderProductId,':dpid'=>$companyId));                        
                        $order = Order::model()->with('company')->find('t.lid=:id and t.dpid=:dpid' , array(':id'=>$orderProduct->order_id,':dpid'=>$companyId));
                        $criteria = new CDbCriteria;
                        $criteria->condition =  't.status in ("1","2","3") and t.dpid='.$order->dpid.' and t.site_id='.$order->site_id.' and t.is_temp='.$order->is_temp ;
                        $criteria->order = ' t.lid desc ';
                        $siteNo = SiteNo::model()->find($criteria);
                        if($siteNo->is_temp=='0')
                        {
                            $site = Site::model()->with('siteType')->find('t.lid=:lid and t.dpid=:dpid',  array(':lid'=>$order->site_id,':dpid'=>$order->dpid));                            
                        }else{
                            $site = new Site();
                        }
                        if($orderProduct->is_print=='0')
                        {
                            $reprint=false;
                        }else{
                            $reprint=true;
                        }
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
                        //Yii::app()->end(json_encode($ret));
                }
                //var_dump($ret);exit;
                $this->renderPartial('printresultone' , array(
                                'orderId'=>$order->lid,
                                'orderProductId'=>$orderProductId,
                                'ret'=>$ret,
                                //'joblist' => $joblist, job in memcached
                                'typeId'=>$typeId                                
		));		             
        }
        /**
         * 每个菜品一张单子
         */
        public function actionPrintKitchenResult(){
                $orderId = Yii::app()->request->getParam('orderId',0);
		$companyId = Yii::app()->request->getParam('companyId');
                $timenum =  Yii::app()->request->getParam('timenum');
                $db = Yii::app()->db;
                $finished=false;
                $successnum=0;
                $errornum=0;
                $notsurenum=0;
                
                Gateway::getOnlineStatus();
                $store = Store::instance('wymenu');
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
                $db = Yii::app()->db;
                $finished=false;
                $successnum=0;
                $errornum=0;
                $notsurenum=0;
                
                Gateway::getOnlineStatus();
                $store = Store::instance('wymenu');
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
                if($timenum==0 || $notsurenum==0)
                {
                    $finished=true;
                }
                $ret=array('finished'=>$finished,'successnum'=>$successnum,'errornum'=>$errornum,'notsurenum'=>$notsurenum);
                Yii::app()->end(json_encode($ret));
                //get status from memcache
                //if error change product kitchen status in db
                //if timenum=0 return finish or all success
        }
        
        public function actionPrintKitchenResultOne(){
                $companyId = Yii::app()->request->getParam('companyId');
                $jobid =  Yii::app()->request->getParam('jobid');
                $orderProductId =  Yii::app()->request->getParam('orderProductId');
                $db = Yii::app()->db;
                
                //$jobstatus=false;
                Gateway::getOnlineStatus();
                $store = Store::instance('wymenu');
                
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
                
                Yii::app()->end(json_encode($ret));
                //get status from memcache
                //if error change product kitchen status in db
                //if timenum=0 return finish or all success
        }
        
        public function actionPrintPadList(){                
                $id = Yii::app()->request->getParam('id',0);
		$companyId = Yii::app()->request->getParam('companyId');
                $padId = Yii::app()->request->getParam('padId');
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
                $order = Order::model()->with('company')->find('t.lid=:id and t.dpid=:dpid' , array(':id'=>$orderId,':dpid'=>$this->companyId));
                $pad=Pad::model()->find(' dpid=:dpid and lid=:lid',array(':dpid'=>$order->dpid,'lid'=>$padId));
                //前面加 barcode
                $precode="";
                //$precode="1D6B450B".strtoupper(implode('',unpack('H*', 'A'.$order->lid)))."0A".strtoupper(implode('',unpack('H*', 'A'.$order->lid)))."0A";
                
		//Yii::app()->end(json_encode(Helper::printList($order , $padid)));
                $printserver="1";
                $memo="";
                $ret=Helper::printList($order , $pad,$precode,$printserver,$memo);
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
                
                Gateway::getOnlineStatus();
                $store = Store::instance('wymenu');
                
                $jobresult=$store->get('job_'.$companyId."_".$jobid.'_result');
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