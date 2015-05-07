<?php

class DefaultOrderController extends BackendController
{
        public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , '请选择公司');
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
                $order=array();
                $siteNo=array();
                ///***********insert to order feedback
                ///*************print
                if($orderId !='0')
                {
                    $order = Order::model()->find('lid=:lid and dpid=:dpid' , array(':lid'=>$orderId,':dpid'=>$companyId));
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
                if($siteNo->is_temp=='1')
                {
                    $total = array('total'=>$productTotal,'remark'=>'临时座位:'.$siteNo->site_id%1000);                    
                }else{
                    $total = Helper::calOrderConsume($order,$siteNo, $productTotal);
                }
                
		//var_dump($productTotal);exit;
		
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
                                'typeId' => $typeId
                                //'categories' => $categories
                                //'products' => $productslist,
                                //'setlist' => $setlist
		));
	}
        
        public function actionAccount() {
		$orderId = Yii::app()->request->getParam('orderId','0');
                $companyId = Yii::app()->request->getParam('companyId','0');
                $typeId=Yii::app()->request->getParam('typeId','0');
                //$op=
                $totaldata=Yii::app()->request->getParam('total','0');
                $sid=Yii::app()->request->getParam('sid',0);
                $istemp=Yii::app()->request->getParam('istemp',0);
                ///***********insert to order feedback
                            ///*************print
                if($orderId==0)
                {
                    $criteria = new CDbCriteria;
                    $criteria->condition =  ' t.order_status in ("1","2","3") and  t.dpid='.$companyId.' and t.site_id='.$sid.' and t.is_temp='.$istemp ;
                    $criteria->order = ' t.lid desc ';
                    $order = Order::model()->find($criteria);
                    $productTotal = OrderProduct::getTotal($order->lid,$order->dpid);
                    if($istemp=='1')
                    {
                        $total = array('total'=>$productTotal,'remark'=>'临时座位:'.$sid%1000);                    
                    }else{
                        $criteria->condition =  ' t.status in ("1","2","3") and  t.dpid='.$companyId.' and t.site_id='.$sid.' and t.is_temp='.$istemp ;
                        $criteria->order = ' t.lid desc ';
                        $siteNo = SiteNo::model()->find($criteria);
                        $total = Helper::calOrderConsume($order,$siteNo, $productTotal);                        
                    }
                    $totaldata=$total['total'];
                }else{
                    $criteria = new CDbCriteria;
                    $criteria->condition =  't.dpid='.$companyId.' and t.lid='.$orderId ;
                    $criteria->order = ' t.lid desc ';
                    $order = Order::model()->find($criteria);
                }
                $paymentMethods = PaymentClass::getPaymentMethodList($companyId);
                //var_dump($paymentMethods);exit;
                if(Yii::app()->request->isPostRequest){
                        //var_dump(Yii::app()->request->getPost('Order'));exit;
                        $order->attributes = Yii::app()->request->getPost('Order');
                        $order->pay_time = date('Y-m-d H:i:s',time());
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
                            $siteNo->status=$order->order_status;
                            $siteNo->save();
                            /*if($order->order_status=='3')
                            {
                                ///***********insert to order feedback
                                $sef=new Sequence("order_feedback");
                                $lidf = $sef->nextval();
                                $dataf = array(
                                    'lid'=>$lidf,
                                    'dpid'=>$companyId,
                                    'create_at'=>date('Y-m-d H:i:s',time()),
                                    'is_temp'=>$istemp,
                                    'site_id'=>$site_id,
                                    'is_deal'=>'0',
                                    'feedback_id'=>0,
                                    'order_id'=>0,
                                    'is_order'=>'1',
                                    'feedback_memo'=>'已付款',
                                    'delete_flag'=>'0'
                                );
                                $db->createCommand()->insert('nb_order_feedback',$dataf);
                            }*/
                            if($order->order_status=='4')
                            {
                                SiteClass::deleteCode($this->companyId,$siteNo->code);
                            }
                            $transaction->commit();
                            $this->redirect(array('default/index' , 'companyId' => $this->companyId,'typeId'=>$typeId));
			} catch(Exception $e){
				$transaction->rollback();
			}
		}
		$this->renderPartial('account' , array(
				'model' => $order,
                                'total' => $totaldata,
                                'typeId'=>$typeId,
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
                                $orderProduct->taste_memo = '无';
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
                            Yii::app()->user->setFlash('success' , '添加单品成功');
                            $this->redirect(array('defaultOrder/order' , 'companyId' => $this->companyId,'orderId' => $orderId,'typeId'=>$typeId));
                        } catch (Exception $e) {
                            $transaction->rollback(); //如果操作失败, 数据回滚
                            //echo json_encode(array('status'=>0,'message'=>'换台失败'));
                            Yii::app()->user->setFlash('success' , '添加失败');
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
                            Yii::app()->user->setFlash('success' , '添加成功');
                            $this->redirect(array('defaultOrder/order' , 'companyId' => $this->companyId,'orderId' => $orderId,'typeId'=>$typeId));
                        } catch (Exception $e) {
                            $transaction->rollback(); //如果操作失败, 数据回滚
                            //echo json_encode(array('status'=>0,'message'=>'换台失败'));
                            Yii::app()->user->setFlash('success' , '添加失败');
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
                    $tastes= TasteClass::getAllOrderTaste($companyId, '1');
                    $orderTastes=  TasteClass::getOrderTaste($lid, '1', $companyId);
                    $tasteMemo = TasteClass::getOrderTasteMemo($lid, '1', $companyId);
                    $orderId=$lid;
                    //var_dump($tastes,$orderTastes,$tasteMemo);exit;                   
                    
                }else{
                    $orderProduct=  OrderProduct::model()->find(' lid=:lid and dpid=:dpid',array(':lid'=>$lid,':dpid'=>$companyId));
                    $tastes=  TasteClass::getProductTaste($orderProduct->product_id,$companyId);
                    $orderTastes=  TasteClass::getOrderTaste($lid, '2', $companyId);
                    $tasteMemo = TasteClass::getOrderTasteMemo($lid, '2', $companyId);
                    $orderId=$orderProduct->order_id;
                    //var_dump($tastes,$orderTastes,$tasteMemo);exit;       
                                       
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
                                'tastes' => $tastes,
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
                        echo json_encode(array('msg'=>'成功'));
                    }else{
                        echo json_encode(array('msg'=>'失败'));
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
                        echo json_encode(array('msg'=>'成功'));
                    }else{
                        echo json_encode(array('msg'=>'失败'));
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
                            Yii::app()->user->setFlash('success' , '修改成功');
                            //echo '333';exit;
                            $this->redirect(array('defaultOrder/order' , 'companyId' => $this->companyId,'orderId' => $orderProduct->order_id,'typeId'=>$typeId));
                        } else {
                            Yii::app()->user->setFlash('success' , '添加失败');
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
                            Yii::app()->user->setFlash('success' , '修改成功');
                            //echo '333';exit;
                            $this->redirect(array('defaultOrder/order' , 'companyId' => $this->companyId,'orderId' => $orderProduct->order_id,'typeId'=>$typeId));
                        } catch (Exception $e) {
                            $transaction->rollback(); //如果操作失败, 数据回滚
                            //var_dump($e);
                            //echo json_encode(array('status'=>0,'message'=>'换台失败'));
                            Yii::app()->user->setFlash('success' , '添加失败');
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
        
        public function actionPrintList(){
                
                $id = Yii::app()->request->getParam('id',0);
		$companyId = Yii::app()->request->getParam('companyId');
                $sid=Yii::app()->request->getParam('sid',0);
                $istemp=Yii::app()->request->getParam('istemp',0);
                ///////////////////////test
                Gateway::getOnlineStatus();
                $store = Store::instance('wymenu');
                $se=new Sequence("printer_job_id");
                $jobid = $se->nextval();
                $ret = $store->set($companyId."_".$jobid,'1C43011C2688A488A482AE82AF82B182F182C982BF82CD0A0A0A0A0A0A1D5601',0,60);
                echo Yii::app()->end(json_encode(array('status'=>true,'msg'=>'')));
                exit;
                ////////////////////////test
                if($id==0)
                {
                    $criteria = new CDbCriteria;
                    $criteria->condition =  ' t.order_status in ("1","2","3") and  t.dpid='.$companyId.' and t.site_id='.$sid.' and t.is_temp='.$istemp ;
                    $criteria->order = ' t.lid desc ';
                    $order = Order::model()->find($criteria);
                }else{                
                    $order = Order::model()->with('company')->find('t.lid=:id and t.dpid=:dpid' , array(':id'=>$id,':dpid'=>$companyId));
                }
		//var_dump($order);exit;
                                
                $reprint = false;
		Yii::app()->end(json_encode(Helper::printList($order , $reprint)));
        }
        
        public function actionPrintKitchen(){
                $orderId = Yii::app()->request->getParam('orderId',0);
		$companyId = Yii::app()->request->getParam('companyId');
                $typeId =  Yii::app()->request->getParam('typeId');
                $db = Yii::app()->db;
                
                //////////////test
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
                echo json_encode($clientId,$test_print_data);
                if(!empty($clientId))
                {
                    Gateway::sendToClient($clientId,json_encode($test_print_data));
                }
                exit;
                ///////////test
                //var_dump(Yii::app()->params->has_cache);exit;
                $transaction = $db->beginTransaction();
                try {
                        $order = Order::model()->with('company')->find('t.lid=:id and t.dpid=:dpid' , array(':id'=>$orderId,':dpid'=>$companyId));
                        $criteria = new CDbCriteria;
                        $criteria->condition =  't.status in ("1","2","3") and t.dpid='.$order->dpid.' and t.site_id='.$order->site_id.' and t.is_temp='.$order->is_temp ;
                        $criteria->order = ' t.lid desc ';
                        $siteNo = SiteNo::model()->find($criteria);
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
                        $order->save();
                        $siteNo->status='2';
                        $siteNo->save();
                        foreach($orderProducts as $orderProduct)
                        {
                            $reprint = false;
                            //var_dump($orderProduct);exit;
                            if($orderProduct->is_print=='0')
                            {
                                Helper::printKitchen($order,$orderProduct,$site,$siteNo ,$reprint);
                                $orderProduct->is_print='1';
                                $orderProduct->product_order_status='1';
                                $orderProduct->save();
                            }                            
                        }
                        $transaction->commit();
                        Yii::app()->user->setFlash('success' , '修改成功');
                        $this->redirect(array('defaultOrder/order' , 'companyId' => $companyId,'orderId' => $orderId,'typeId'=>$typeId));
                        
                } catch (Exception $e) {
                        $transaction->rollback(); //如果操作失败, 数据回滚
                        var_dump($e);
                        
                        return false;
                }
		//var_dump($order);exit;
                //if((Yii::app()->request->isAjaxRequest)) {
		//	echo Yii::app()->end(json_encode(array('status'=>true,'msg'=>'打印结束')));
		//} else {
		//	return array('status'=>true,'msg'=>'打印结束');
		//}                
        }
}