<?php

class SqbpayController extends Controller
{
	public function actionWappayresultceshi(){
		$dpid = '0000000027';
		$site_id = '0000';
		$is_temp = 1;
		$orderid = '0000016830';
		var_dump(Order::getAccountNo($dpid,$site_id,$is_temp,$orderid));exit;
		$result = SqbPay::preOrder(array(
				'dpid'=>$dpid,
				'client_sn'=>Order::getAccountNo($dpid,$site_id,$is_temp,$orderid),
				'total_amount'=>'0.01',
				'payway'=>'3',
				'subject'=>'wymenu',
				'operator'=>'admin',
				'notify_url'=>'http://menu.wymenu.com/wymenuv2/sqbpay/wappayresult',
				'return_url'=>'http://menu.wymenu.com/wymenuv2/sqbpay/wappayresult',
		));
		
	}
	public function actionWappayresult(){
		$is_success = Yii::app()->request->getParam('is_success');
		if($is_success == 'F'){
			$error_code = Yii::app()->request->getParam('error_code');
			$error_message = Yii::app()->request->getParam('error_message');
			
			
			var_dump($is_success);
			echo '---';
			var_dump($error_code);
			echo '---';
			var_dump($error_message);
		}
		
		echo '---';
		
	}
}