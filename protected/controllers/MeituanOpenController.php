<?php 
class MeituanOpenController extends Controller
{
	public function actionChangedpinfo(){
		//门店状态变更
		echo '{ "data": "OK"}';
		exit();
	}
	public function actionReceiveOrder(){
		//推送订单*
		$notify = new MtOpenNotify();
        $notify->Handle('new');
	}
	public function actionCancelOrder(){
		//订单取消信息推送*
		$notify = new MtOpenNotify();
        $notify->Handle('cancel');
	}
	public function actionOrderRefund(){
		//美团用户或客服退款流程操作
		$notify = new MtOpenNotify();
        $notify->Handle('refund');
	}
	public function actionOrderPartRefund(){
		//美团用户或客服部分退款流程操作
		$notify = new MtOpenNotify();
        $notify->Handle('refund');
	}
	public function actionOrderShipper(){
		//订单退款信息推送
		$data = file_get_contents('php://input');
		echo '{ "data": "OK"}';
		exit();
	}
	public function actionOrderComplete(){
		//订单完成信息推送
		echo '{ "data": "OK"}';
		exit();
	}
	public function actionOrderSettlement(){
		//订单结算信息
		echo '{ "data": "OK"}';
		exit();
	}
	public function actionOrderReminder(){
		//催单推送*
		$notify = new MtOpenNotify();
        $notify->Handle('reminder');
	}
	public function actionPrivacyNumber(){
		//隐私号降级推送*
		$notify = new MtOpenNotify();
        $notify->Handle('privacynumber');
	}
	public function actionModifyOrder(){
		//修改订单信息回调
		echo '{ "data": "OK"}';
		exit();
	}
	public function actionOrderPayment(){
		//推送订单赔付消息
		echo '{ "data": "OK"}';
		exit();
	}
}
?>