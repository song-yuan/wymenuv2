<?php

class TestController extends Controller
{
	public $layout = '/layouts/productmain';
	public function actionIndex()
	{
		$this->render('index');
	}
	public function actionReadLog()
	{
		echo '<meta charset="utf-8">';
		$log = file_get_contents( Yii::app()->basePath."/data/log.txt");
		echo $log;
		exit;
	}
	public function actionQrcode(){
		$url = 'https://m.wosai.cn/qr/gateway?client_sn=0000033658-0000000027-087&notify_url=http://menu.wymenu.com/wymenuv2/sqbpay/wappayresult&operator=微信会员-0000002200&payway=3&reflect={"companyId":"0000000027","dpid":"0000000027"}&return_url=http://menu.wymenu.com/wymenuv2/sqbpay/wappayreturn&subject=壹点吃演示店-微信点餐订单&terminal_sn=100000930002128676&total_amount=700&sign=39601E5E603C0927890424DFF8254717';
		$code=new QRCode($url);
		$code->create();exit;
	}
	public function actionMicroPay(){
		$companyId = Yii::app()->request->getParam('companyId');
		$orderId = Yii::app()->request->getParam('orderId');
		$this->render('micropay',array('companyId'=>$companyId,'orderId'=>$orderId));
	}
	public function actionMemercache(){
		$dpid = 30;
		$sql = 'select count(*) from nb_pad_setting_status where dpid='.$dpid.' and delete_flag=0';
		$padNums = Yii::app ()->db->createCommand ( $sql )->queryScalar();
		$padNo = $padNums + 1;
		var_dump($padNo);exit;
	}
	public function actionEleme(){
		$data = Yii::app()->request->getParam('eleme');
		if(!empty($data)){
			$obj = json_decode($data);
			$type = $obj->type;
			$shopId = $obj->shopId;
			$message = $obj->message;
			$elemeDy = Elm::getErpDpid($shopId);
			if($elemeDy){
				$dpid = $elemeDy['dpid'];
				if($type==10){
					$result = Elm::order($message,$dpid);
				}elseif($type==12){
					$result = Elm::orderStatus($message,$dpid);
				}elseif($type==20){
					$result = Elm::orderCancel($message,$dpid);
				}elseif($type==30){
					$result = Elm::refundOrder($message,$dpid);
				}else {
					$result = true;
				}
				if($result){
					echo '{"message":"ok"}';
				}else{
					echo '{"message":"error"}';
				}
			}else{
				echo '{"message":"ok"}';
			}
		}
		exit;
	}
}