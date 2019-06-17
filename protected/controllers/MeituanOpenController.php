<?php 
class MeituanOpenController extends Controller
{
	public function actionReceiveOrder(){
		//推送订单
       
	}
	public function actionConfirmOrder(){
		//订单确认
		$notify = new MtNotify();
        $notify->Handle('confirm');
	}
	public function actionCancelOrder(){
		//订单取消信息推送
		$data = file_get_contents('php://input');
		echo '{ "data": "OK"}';
		exit();
	}
	public function actionOrderRefund(){
		//订单取消信息推送
		$data = file_get_contents('php://input');
		echo '{ "data": "OK"}';
		exit();
	}
	public function actionToken(){
		//绑定门店获取appAuthToken
		$data = file_get_contents('php://input');
		$remt = MtOrder::token($data);
		echo $remt;
		exit();
	}
	public function actionXintiao(){
		//心跳 URL
		echo '{"data":"OK"}';
		exit();
	}
	public function actionPrivacyNumber(){
		// 隐私号降级 URL
		$data = file_get_contents('php://input');
		if(empty($data)){
			echo '{"data":"OK"}';
		}else{
			echo '{"data":"OK"}';
		}
		exit();
	}
	public function actionUnbind(){
		$data = file_get_contents('php://input');
		$remt = MtOrder::Jcbd($data);
		echo $remt;
		exit();
	}
	public function actionDistribution(){
		//配送员上传
		$dpid = $_POST['companyId'];
		$orderId = $_POST['orderid'];
		$courierName = $_POST['name'];
		$courierPhone = $_POST['phone'];
		$result = MtOrder::orderDistr($dpid,$orderId,$courierName,$courierPhone); 
		echo $result;

	}
	public function actionCompleteOrder(){
		$data = file_get_contents('php://input');
		echo '{ "data": "OK"}';
		exit();
	}
}
?>