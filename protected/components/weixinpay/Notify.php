<?php
/**
 *
 * 支付通知回调基础类
 * @author widyhu
 *
 */
class Notify extends WxPayNotify
{
	//查询订单
	public function Queryorder($out_trade_no)
	{
		$input = new WxPayOrderQuery();
		$input->SetOut_trade_no($out_trade_no);
		$result = WxPayApi::orderQuery($input);
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}

	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		$notfiyOutput = array();
		if(!array_key_exists("out_trade_no", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["out_trade_no"])){
			$msg = "订单查询失败";
			return false;
		}

		//记录通知 并更改订单状态
		$this->checkNotify($data);

		return true;
	}

	public function checkNotify($data){
		$sql = 'SELECT (SELECT count(*) FROM nb_notify WHERE transaction_id = "' .$data['transaction_id']. '") + (SELECT count(*) FROM nb_notify WHERE out_trade_no= "' .$data['out_trade_no']. '") as count';
		$count = Yii::app()->db->createCommand($sql)->queryRow();
		if(!$count['count']){
			$this->insertNotify($data);
		}
	}
	public function insertNotify($data){
		$orderIdArr = explode('-',$data["out_trade_no"]);
		$openId = isset($data['sub_openid'])?$data['sub_openid']:$data['openid'];

		$brandUser = WxBrandUser::getFromOpenId($openId);

		$se = new Sequence("notify");
        $lid = $se->nextval();
		$notifyData = array(
			'lid'=>$lid,
        	'dpid'=>$orderIdArr[1],
        	'create_at'=>date('Y-m-d H:i:s',time()),
        	'update_at'=>date('Y-m-d H:i:s',time()),
        	'user_id'=>$brandUser['lid'],
        	'out_trade_no'=>$data['out_trade_no'],
        	'transaction_id'=>$data['transaction_id'],
        	'total_fee'=>$data['total_fee'],
        	'time_end'=>$data['time_end'],
        	'attach'=>isset($data['attach'])?$data['attach']:'',
			);
		Yii::app()->db->createCommand()->insert('nb_notify', $notifyData);
		if($data['attach']==1){
			//充值
			$recharge = new WxRecharge($orderIdArr[0],$orderIdArr[1],$brandUser['lid']);
			exit;

		}else if($data['attach']==3){
			//商铺原料支付
			$Yorder = GoodsOrder::model()->find('account_no=:account_no and dpid=:dpid',array(':account_no'=>$orderIdArr[0],':dpid'=>$orderIdArr[1]));
			$Yorder->order_status= 1;
			$Yorder->paytype = 1;
			$Yorder->pay_status = 1;
			$Yorder->pay_time = date('Y-m-d H:i:s',time());
			$Yorder->update();
			$se = new Sequence("goods_order_pay");
			$lid = $se->nextval();
			$Data = array(
				'lid'=>$lid,
				'dpid'=>$orderIdArr[1],
				'create_at'=>date('Y-m-d H:i:s',time()),
				'update_at'=>date('Y-m-d H:i:s',time()),
				'account_no'=>$orderIdArr[0],
				'order_id'=>$Yorder->lid,
				'pay_amount'=>$Yorder->reality_total,
				'paytype'=>1,
				'paytype_id'=>$data['transaction_id'],
				'remark'=>'商铺原材料微信支付',
				'delete_flag'=>0,
				'is_sync'=>DataSync::getInitSync(),
				);
			Yii::app()->db->createCommand()->insert('nb_goods_order_pay', $Data);
			exit;

		}
		//orderpay表插入数据
		$order = WxOrder::getOrder($orderIdArr[0],$orderIdArr[1]);
		WxOrder::insertOrderPay($order,1,$data['total_fee']/100,$data["out_trade_no"]);
		WxOrder::dealOrder($brandUser, $order);
		WxOrder::pushOrderToRedis($order);
	}
}