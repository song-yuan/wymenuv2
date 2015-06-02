<?php
class OrderController extends BackendController
{
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' ,yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex(){
		$criteria = new CDbCriteria;
		$criteria->with = array('site' , 'site.isfree') ;
		$criteria->condition =  't.company_id='.$this->companyId.' and t.delete_flag=0' ;
		$models = SiteType::model()->findAll($criteria);
		$this->render('index',array(
				'models'=>$models,
		));	
	}
	public function actionUpdate(){
		$id = Yii::app()->request->getParam('id');
		$order = Order::model()->with('company')->find('order_id=:id' , array(':id'=>$id));
		$siteNo = SiteNo::model()->findByPk($order->site_no_id);
		$orderProducts = OrderProduct::getOrderProducts($order->order_id);
		$productTotal = OrderProduct::getTotal($order->order_id);
		$total = Helper::calOrderConsume($order, $productTotal);
		
		if(Yii::app()->request->isPostRequest){
			$order->attributes = Yii::app()->request->getPost('Order');
			if($order->order_status){
				$siteNo->delete_flag = 1;
				$order->pay_time = time();
			}
			$transaction = Yii::app()->db->beginTransaction();
			try{
				if($order->save()) {
					if($order->order_status){
						$siteNo->save();
						Yii::app()->db->createCommand('delete from nb_cart where company_id=:companyId and code=:code')
						->bindValue(':companyId',$this->companyId)
						->bindValue(':code',$siteNo->code)
						->execute();
						$status = Helper::printList($order);
					}
					if(!$status['status']) {
						Yii::app()->user->setFlash('error',$status['msg']);
						throw new CException(yii::t('app','请选择打印机'));
					} else {
						Yii::app()->user->setFlash('success',yii::t('app','结单成功'));
					}
				}
				$transaction->commit();
				$this->redirect(array('order/index' , 'companyId' => $this->companyId));
			} catch(Exception $e){
				$transaction->rollback();
			}
		}
		$paymentMethods = $this->getPaymentMethodList();
		$this->render('update' , array(
				'model'=>$order,
				'orderProducts' => $orderProducts,
				'productTotal' => $productTotal ,
				'total' => $total,
				'paymentMethods'=>$paymentMethods
		));
	}
	public function actionHistoryList(){
		$criteria = new CDbCriteria;
		$criteria->with = array('siteNo','siteNo.site') ;
		$criteria->condition =  't.company_id='.$this->companyId.' and order_status=1' ;
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
	public function actionView(){
		$id = Yii::app()->request->getParam('id');
		$order = Order::model()->with('company')->find('order_id=:id' , array(':id'=>$id));
		$siteNo = SiteNo::model()->findByPk($order->site_no_id);
		$orderProducts = OrderProduct::getOrderProducts($order->order_id);
		$productTotal = OrderProduct::getTotal($order->order_id);
		$total = Helper::calOrderConsume($order, $productTotal);
		$paymentMethod = PaymentMethod::model()->findByPk($order->payment_method_id);
		$this->render('view' , array(
				'model'=>$order,
				'orderProducts' => $orderProducts,
				'productTotal' => $productTotal ,
				'total' => $total,
				'paymentMethod'=>$paymentMethod->name
		));
	}
	public function actionGetOrderId(){
		$id = Yii::app()->request->getParam('id');
		$site = Site::model()->with('isfree')->find('t.site_id=:id' , array(':id' => $id));
		
		if($site->isfree) {
			$order = Order::model()->find('site_no_id=:id' , array(':id' => $site->isfree->id));
			$productTotal = OrderProduct::getTotal($order->order_id);
			$total = Helper::calOrderConsume($order, $productTotal);
			
			echo json_encode(array('status'=>true , 'serial'=>$site->serial , 'order_id'=>$order->order_id , 'total'=>$total['total']));
		} else {
			echo json_encode(array('status'=>false));
		}
		exit;
	}
	public function actionPay(){
		$id = Yii::app()->request->getParam('id');
		$order = Order::model()->find('order_id=:id' , array(':id' => $id));
		$siteNo = SiteNo::model()->find('id=:id' , array(':id'=>$order->site_no_id));
		$order->order_status = 1;
		$order->pay_time = time();
		$siteNo->delete_flag = 1;
		
		if($order->save() && $siteNo->save()) {
			echo json_encode(array('status'=>true , 'siteId'=>$siteNo->site_id));
		} else {
			echo json_encode(array('status'=>false));
		}
		exit;
	}
	public function actionDeleteProduct(){
		$id = Yii::app()->request->getParam('id');
		$orderProduct = OrderProduct::model()->findByPk($id);
		
		if($orderProduct) {
			if($orderProduct->amount >1){
				$restNum = $orderProduct->amount-1;
				$orderProduct->saveAttributes(array('amount'=>$restNum));
				$data = array('status'=>true,'amount'=>$restNum,'price'=>$orderProduct->price);
			} else {
				$orderProduct->delete();
				$data = array('status'=>true,'amount'=>0,'price'=>0);
			}
			$data['total'] = OrderProduct::getTotal($orderProduct->order_id);
			echo json_encode($data);
			exit;
		}
		echo json_encode(array('status'=>false));
		exit;
	}
	private function getPaymentMethodList(){
		$paymentMethods = PaymentMethod::model()->findAll() ;
		return CHtml::listData($paymentMethods, 'payment_method_id', 'name');
	}
	public function actionPrintList(){
		$orderId = Yii::app()->request->getParam('id');
		$reprint = Yii::app()->request->getParam('reprint');
		
		$order = Order::model()->with('company')->find('order_id=:id' , array(':id'=>$orderId));
		
		//var_dump($order);exit;
		Yii::app()->end(json_encode(Helper::printList($order , $reprint)));
	}
	public function actionPrintProducts(){
		$orderId = Yii::app()->request->getParam('id');
		$reprint = Yii::app()->request->getParam('reprint');
		$order = Order::model()->with('company')->find('order_id=:id' , array(':id'=>$orderId));
		
		//var_dump($order);exit;
		Yii::app()->end(json_encode(Helper::printList($order , $reprint)));
	}
}