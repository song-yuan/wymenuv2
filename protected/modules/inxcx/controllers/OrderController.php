<?php
/**
 * 订单接口
 */
class OrderController extends Controller
{
	public $companyId;
	public function init()
	{
		$companyId = Yii::app()->request->getParam('companyId');
		$this->companyId = $companyId;
	}
	public function actionQueryById()
	{
		$orderId = Yii::app()->request->getParam('orderId');
		$dpid = $this->companyId;
		$order = WxOrder::getOrder($orderId, $dpid);
		$orderProduct = WxOrder::getOrderProduct($orderId, $dpid);
		echo json_encode(array('order'=>$order,'orderProduct'=>$orderProduct));
	}
}