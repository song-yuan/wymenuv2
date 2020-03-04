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
		echo json_encode(array('order'=>$order,'orderProduct'=>$orderProduct));exit;
	}
	public function actionGetJsApiParam(){
		$userId = Yii::app()->request->getParam('userId');
		$orderId = Yii::app()->request->getParam('orderId');
		$payPrice = Yii::app()->request->getParam('price');
		$notifyUrl = 'http://'.$_SERVER['HTTP_HOST'].$this->createUrl('/weixin/notify');
		
		$tools = new JsApiPay();
		$openId = WxBrandUser::openId($userId,$this->companyId);
		$account = WxAccount::get($this->companyId);
		//②、统一下单
		$input = new WxPayUnifiedOrder();
		$input->SetBody($this->companyId."微信点餐订单");
		$input->SetAttach("0");
		$input->SetOut_trade_no($orderId);
		$input->SetTotal_fee($payPrice*100);
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag($this->companyId."微信点餐订单");
		$input->SetNotify_url($notifyUrl);
		$input->SetTrade_type("JSAPI");
		if($account['multi_customer_service_status'] == 1){
			$input->SetSubOpenid($openId);
		}else{
			$input->SetOpenid($openId);
		}
		$orderInfo = WxPayApi::unifiedOrder($input);
		
		$jsApiParameters = $tools->GetJsApiParameters($orderInfo);
		echo $jsApiParameters;exit;
	}
}