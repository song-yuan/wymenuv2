<?php
/**
* token 店铺的ePoiId与店铺的appAuthToken
* order 订单推送接口
*orderconfirm 推送订单到erp厂商接口
*/
class MtOrder
{
	public static function order($data){
		$resArr = MtUnit::dealData($data);
		$ePoiId = $resArr['ePoiId'];
		$order = $resArr['order'];
		$obj = json_decode($order);
		$orderArr = array();
		$orderArr['order_info'] = array('creat_at'=>date('Y-m-d H:i:s'),'account_no'=>$obj->orderId,'classes'=>0,'username'=>'','site_id'=>0,'is_temp'=>1,'number'=>0,'order_status'=>$obj->status,'order_type'=>7,'should_total'=>$obj->total,'reality_total'=>$obj->originalPrice,'takeout_typeid'=>0,'callno'=>'');
		$orderArr['order_product'] = array();
		$array_detail=json_decode($obj->detail,true);
		foreach ($array_detail as $key => $value) {
			$phsCode =  $array_detail[$key]['app_food_code'];
			$price = $array_detail[$key]['price'];
			$amount = $array_detail[$key]['quantity'];
			$sql = 'select 0 as is_set,lid,product_name as name from nb_product where dpid='.$ePoiId.' and phs_code='.$phsCode.
			' and delete_flag=0 union select 1 as is_set,lid,set_name as name from nb_product_set where dpid='.$ePoiId.' and pshs_code='.$phsCode.' and delete_flag=0 ';
			$res = Yii::app()->db->createCommand($sql)->queryRow();
			if( $res->is_set==0){
			    	$orderProduct = array('is_set'=>$res->is_set,'set_id'=>0,'product_id'=>$res->lid,'product_name'=>$res->name,'original_price'=>$price,'price'=>$price,'amount'=>$amount,'zhiamount'=>$amount,'product_taste'=>array(),'product_promotion'=>array());
					array_push($orderArr['order_product'], $orderProduct);
			    }else{
			    	$orderProduct = array('is_set'=>$res->is_set,'set_id'=>$res->lid,'product_id'=>$res->lid,'product_name'=>$res->name,'original_price'=>$price,'price'=>$price,'amount'=>$amount,'zhiamount'=>$amount,'product_taste'=>array(),'product_promotion'=>array());
					array_push($orderArr['order_product'], $orderProduct);
			    }  
		}
		$orderArr['order_address'] = array(array('consignee'=>$obj->recipientName,'street'=>$obj->recipientAddress,'mobile'=>$obj->recipientPhone,'tel'=>$obj->recipientPhone));
		$orderArr['order_pay'] = array(array('pay_amount'=>$obj->total,'paytype'=>14,'payment_method_id'=>0,'paytype_id'=>0,'remark'=>''));
		$orderStr = json_encode($orderArr, JSON_UNESCAPED_UNICODE);
		$url = 'http://menu.wymenu.com/wymenuv2/admin/dataAppSync/createOrder';
		$data = array('dpid'=>$dpid,'data'=>$orderStr);
		$result = MtUnit::postHttps($url, $data);
		$reobj = json_decode($result);
		if($reobj->status){
			$sql1 = "select * from token where ePoiId=".$ePoiId;
			$res1 = Yii::app()->db->createCommand($sql1)->queryRow();
			$url1 = 'http://api.open.cater.meituan.com/waimai/order/confirm';
			$array= array('appAuthToken'=>"$res1->appAuthToken",'charset'=>'utf-8','timestamp'=>124,'orderId'=>$obj->orderId );
			$sign=MtUnit::sign($array);
			$data1 = "appAuthToken=$res1->appAuthToken&charset=utf-8&timestamp=124&sign=$sign&orderId=$obj->orderId";
			$result1 = MtUnit::postHttps($url1, $data1);
			return '{ "data": "OK"}';
		}
		return '{ "data": "ERROR"}';
	}
	public static function token($data){
		$resArr = MtUnit::dealData($data);
		$ePoiId = $resArr['ePoiId'];
		$appAuthToken = $resArr['appAuthToken'];
		$se=new Sequence("meituan_token");
		$lid = $se->nextval();
		$creat_at = date("Y-m-d H:i:s");
		$update_at = date("Y-m-d H:i:s");
		$dpid = $ePoiId;
		$sql = "insert into nb_meituan_token values($lid,$creat_at,$update_at,$dpid,$ePoiId,'$appAuthToken')";
		$res = Yii::app()->db->createCommand($sql);
		if($res){
			return '{ "data": "success"}';
		}
		return '{ "data": "ERROR"}';
	}
	public static function orderconfirm($data){
		$resArr = MtUnit::dealData($data);
		$order = $resArr['order'];
		$obj = json_decode($order);
		$sql = "update nb_order set order_status=".$obj->status." where account_no=".$obj->orderId." and order_type=7";
		$res = Yii::app()->db->createCommand($sql);
		if($res){
			return '{ "data": "OK"}';
		}
		return '{ "data": "ERROR"}';
	}
	public function orderCancel($data){
		$resArr = MtUnit::dealData($data);
		$order = $resArr['orderCancel'];
		$obj = json_decode($order);
		$sql = "update nb_order set order_status=7 where account_no=".$obj->orderId." and order_type=7";
		$res = Yii::app()->db->createCommand($sql);
		if($res){
			return '{ "data": "OK"}';
		}
		return '{ "data": "ERROR"}';
	}
	public function UnboundShop($data){
		$order = json_decode($data);
		$sql = "update nb_meituan_token set delete_flag=1 where ePoiId=".$order->epoiId;
		$res = Yii::app()->db->createCommand($sql);
	}
}
