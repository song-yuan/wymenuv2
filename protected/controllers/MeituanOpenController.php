<?php 
class MeituanOpenController extends Controller
{
	public function actionChangedpinfo(){
		//门店状态变更
		$data = file_get_contents('php://input');
		Helper::writeLog('meituan-open-Changedpinfo:'.$data);
		echo '{ "data": "OK"}';
		exit();
	}
	public function actionReceiveOrder(){
		//推送订单
		$data = file_get_contents('php://input');
		Helper::writeLog('meituan-open-ReceiveOrder:'.$data);
		echo '{ "data": "OK"}';
		exit();
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
		//美团用户或客服退款流程操作
		$data = file_get_contents('php://input');
		echo '{ "data": "OK"}';
		exit();
	}
	public function actionOrderPartRefund(){
		//美团用户或客服部分退款流程操作
		$data = file_get_contents('php://input');
		echo '{ "data": "OK"}';
		exit();
	}
	public function actionOrderShipper(){
		//订单退款信息推送
		$data = file_get_contents('php://input');
		echo '{ "data": "OK"}';
		exit();
	}
	public function actionOrderComplete(){
		//订单取消信息推送
		$data = file_get_contents('php://input');
		echo '{ "data": "OK"}';
		exit();
	}
	public function actionOrderSettlement(){
		//订单结算信息
		$data = file_get_contents('php://input');
		echo '{ "data": "OK"}';
		exit();
	}
	public function actionOrderReminder(){
		//催单推送
		$data = file_get_contents('php://input');
		echo '{ "data": "OK"}';
		exit();
	}
	public function actionPrivacyNumber(){
		//隐私号降级推送
		$data = file_get_contents('php://input');
		echo '{ "data": "OK"}';
		exit();
	}
	public function actionModifyOrder(){
		//修改订单信息回调
		$data = file_get_contents('php://input');
		echo '{ "data": "OK"}';
		exit();
	}
	public function actionPrivacyNumber(){
		//推送订单赔付消息
		$data = file_get_contents('php://input');
		echo '{ "data": "OK"}';
		exit();
	}
}
?>