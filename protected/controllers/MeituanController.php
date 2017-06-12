<?php 
class MeituanController extends Controller
{
	public function actionReceiveOrder(){
		//推送订单
        $data = file_get_contents('php://input');
		$remt = MtOrder::order($data);
		echo $remt;
		exit();   
	}
	public function actionConfirmOrder(){
		//订单确认
		$data = file_get_contents('php://input');
		$remt=MtOrder::orderconfirm($data);
		echo $remt;
		exit();
	}
	public function actionCancelOrder(){
		//订单取消信息推送
		$data = file_get_contents('php://input');
		$remt=MtOrder::orderconfirm($data);
		echo $remt;
		exit();
	}
	public function actionToken(){
		//绑定门店获取appAuthToken
		$data = file_get_contents('php://input');
		Helper::writeLog($data);
		$remt = MtOrder::token($data);
		echo $remt;
		exit();
	}
	public function actionUnboundToken(){
		$data = file_get_contents('php://input');
		Helper::writeLog($data);
		$remt = MtOrder::UnboundShop($data);
		echo $remt;
		exit();
	}
}
?>