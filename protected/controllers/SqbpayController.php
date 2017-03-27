<?php

class SqbpayController extends Controller
{
	public function actionWappayresultceshi(){
		$dpid = '0000000027';
		
		$now = time();
		$rand = rand(100,999);
		$orderId = $now.'-'.$dpid.'-'.$rand;
		
		$company = WxCompany::get($dpid);
		$data = array(
				'dpid' => $dpid,
				'pay_type' => 0,
				'out_trade_no' => $orderId,
				'total_fee' => '0.01',
		);
		$result = MicroPayModel::insert($data);
		
		if($result['status']){
			$result = SqbPay::preOrder(array(
					'dpid'=>$dpid,
					'client_sn'=>$orderId,
					'total_amount'=>'0.01',
					'payway'=>'3',
					'subject'=>'wymenu',
					'operator'=>'admin',
					'notify_url'=>'http://menu.wymenu.com/wymenuv2/sqbpay/wappayresult',
					'return_url'=>'http://menu.wymenu.com/wymenuv2/sqbpay/wappayresult',
			));
		}else{
			echo 'error';
		}
	}
	public function actionWappayresult(){
		$is_success = Yii::app()->request->getParam('is_success');
		if($is_success == 'F'){
			$error_code = Yii::app()->request->getParam('error_code');
			$error_message = Yii::app()->request->getParam('error_message');
			
			
			var_dump($is_success);
			echo '^^^';
			var_dump($error_code);
			echo '###';
			var_dump($error_message);
		}else{
			$terminal_sn = Yii::app()->request->getParam('terminal_sn');
			$sn = Yii::app()->request->getParam('sn');
			$trade_no = Yii::app()->request->getParam('trade_no');
			$client_sn = Yii::app()->request->getParam('client_sn');
			$status = Yii::app()->request->getParam('status');
			$reflect = Yii::app()->request->getParam('reflect');
			$sign = Yii::app()->request->getParam('sign');
			echo $trade_no;
		}
		
		echo '&&&';
		
	}
}