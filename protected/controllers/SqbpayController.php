<?php

class SqbpayController extends Controller
{
	public function actionWappayresultceshi(){
		$dpid = '0000000027';
		$site_id = '0000';
		$is_temp = 1;
		$orderid = '0000026834';
		
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
		echo '收钱吧WAP支付';
		echo $_POST;
	}
}