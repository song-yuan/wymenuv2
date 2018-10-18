<?php
/**
* token 店铺的ePoiId与店铺的appAuthToken
* order 订单推送接口
*orderconfirm 推送订单到erp厂商接口
*/
class MtOrder
{
	public static function getToken($dpid){
		$sql = "select * from nb_meituan_token where type=1 and dpid=".$dpid." and ePoiId=".$dpid." and delete_flag=0";
		$res = Yii::app()->db->createCommand($sql)->queryRow();
		return $res;
	}
	public static function token($data){
		Helper::writeLog('bd:'.$data);
		if(empty($data)){
			return '200';
		}
		$resArr = MtUnit::dealData($data);
		$ePoiId = $resArr['ePoiId'];
		$appAuthToken = $resArr['appAuthToken'];
		$timestamp = isset($resArr['timestamp'])?$resArr['timestamp']:time();
		$sql = 'select * from nb_meituan_token where dpid='.$ePoiId.' and type=1 and delete_flag=0';
		$result = Yii::app()->db->createCommand($sql)->queryRow();
		if($result){
			$sql = 'update nb_meituan_token set appAuthToken="'.$appAuthToken.'",timestamp="'.$timestamp.'" where lid='.$result['lid'].' and dpid='.$result['dpid'];
			$res = Yii::app()->db->createCommand($sql)->execute();
			if($res){
				return '{ "data": "success"}';
			}
			return '{ "data": "ERROR"}';
		}
		$se = new Sequence("meituan_token");
		$lid = $se->nextval();
		$creat_at = date("Y-m-d H:i:s");
		$update_at = date("Y-m-d H:i:s");
		$dpid = $ePoiId;
		$inserData = array(
				'lid'=>	$lid,
				'dpid'=> $dpid,
				'create_at'=>$creat_at,
				'update_at'=>$update_at,
				'type'=>'1',
				'ePoiId'=>	$ePoiId,
				'appAuthToken'=>$appAuthToken,
				'timestamp'=>$timestamp,
		);
		$res = Yii::app()->db->createCommand()->insert('nb_meituan_token',$inserData);
		if($res){
			return '{ "data": "success"}';
		}
		return '{ "data": "ERROR"}';
	}
	public static function order($data){
		if(empty($data)){
			return '200';
		}
		
		$resArr = MtUnit::dealData($data);
		$ePoiId = $resArr['ePoiId'];
		$order = $resArr['order'];
		$order = urldecode($order);
		
		$obj = json_decode($order);
		$orderId = $obj->orderId;
		$res = MtUnit::getWmSetting($ePoiId);
		if(!empty($res)&&$res['is_receive']==1){
			$mtToken = self::getToken($ePoiId);
			$timetamp = time();
			if($mtToken){
				$url = MtUnit::MTHOST.'/waimai/order/confirm';
				$array = array('appAuthToken'=>$mtToken['appAuthToken'],'charset'=>'utf-8','timestamp'=>$timetamp,'orderId'=>$orderId);
				$sign = MtUnit::sign($array);
				$data = "appAuthToken=".$mtToken['appAuthToken']."&charset=utf-8&timestamp=".$timetamp."&sign=".$sign."&orderId=".$orderId;
				$result = MtUnit::postHttps($url, $data);
				Helper::writeLog('confirm-metian:'.$orderId.'-'.$result);
				$obj = json_decode($result);
				if(isset($obj->data) && $obj->data=='ok'){
					return true;
				}else{
					$errmessage = $obj->error->message;
					if(strpos($errmessage,'订单已经确认')===false){
						return false;
					}else {
						return true;
					}
				}
			}else{
				return true;
			}
		}else{
			return true;
		}
	}
	public static function orderconfirm($data){
		if(empty($data)){
			return '200';
		}
		$resArr = MtUnit::dealData($data);
		$ePoiId = $resArr['ePoiId'];
		$order = $resArr['order'];
		$order = urldecode($order);
		$result = self::dealOrder($order,$ePoiId,2);
		return $result;
	}
	public static function orderCancel($data){
		if(empty($data)){
			return '200';
		}
		$resArr = MtUnit::dealData($data);
		$order = $resArr['orderCancel'];
		$ePoiId = $resArr['ePoiId'];
		$obj = json_decode($order);
		$sql = "update nb_order set order_status=7 where account_no=".$obj->orderId." and order_type=7";
		$res = Yii::app()->db->createCommand($sql)->execute();
		if($res){
			return '{ "data": "OK"}';
		}
		return '{ "data": "ERROR"}';
	}
	public static function Jcbd($data){
		Helper::writeLog('jcbd:'.$data);
		if(empty($data)){
			return '{"data":"success"}';
		}
		$resArr = MtUnit::dealData($data);
		$ePoiId = $resArr['ePoiId'];
		$timestamp = $resArr['timestamp'];
		$sql = 'select * from nb_meituan_token where dpid='.$ePoiId.' and type=2 and ePoiId='.$ePoiId.' and timestamp="'.$timestamp.'"';
		$releaseBing = Yii::app()->db->createCommand($sql)->queryRow();
		if(!empty($releaseBing)){
			return '{"data":"success"}';
		}
		$mtToken = self::getToken($ePoiId);
		if(empty($mtToken)){
			return '{"data":"success"}';
		}
		$se = new Sequence("meituan_token");
		$lid = $se->nextval();
		$creat_at = date("Y-m-d H:i:s");
		$update_at = date("Y-m-d H:i:s");
		$inserData = array(
				'lid'=>	$lid,
				'dpid'=> $ePoiId,
				'create_at'=>$creat_at,
				'update_at'=>$update_at,
				'type'=>'2',
				'ePoiId'=>	$ePoiId,
				'appAuthToken'=>'',
				'timestamp'=>$timestamp,
		);
		$resInser = Yii::app()->db->createCommand()->insert('nb_meituan_token',$inserData);
		$sql = "update nb_meituan_token set delete_flag=1 where type=1 and dpid=".$ePoiId." and ePoiId=".$ePoiId.' and timestamp<"'.$timestamp.'"';
		$res = Yii::app()->db->createCommand($sql)->execute();
		if($resInser){
			return '{"data":"success"}';
		}
		return '{"data":"error"}';
	}
	// 通过订单号查询订单信息
	public static function getOrderById($dpid,$orderId){
		$timestamp = time();
		$res = self::getToken($dpid);
		
		$url = MtUnit::MTHOST."/waimai/order/queryById";
		$array = array('appAuthToken'=>$res['appAuthToken'],'charset'=>'utf-8','timestamp'=>$timestamp,'orderId'=>$orderId );
		$sign = MtUnit::sign($array);
		$url .= "?appAuthToken=".$res['appAuthToken']."&charset=utf-8&timestamp=".$timestamp."&sign=".$sign."&orderId=".$orderId;
		$result = MtUnit::postHttps($url);
		return $result;
	}
	public static function orderDistr($dpid,$orderId,$courierName,$courierPhone){
		$res = self::getToken($dpid);
		$url = MtUnit::MTHOST."/waimai/order/delivering";
		$array= array('appAuthToken'=>$res['appAuthToken'],'charset'=>'utf-8','timestamp'=>124,'orderId'=>$orderId );
		$sign=MtUnit::sign($array);
		$data = "appAuthToken=".$res['appAuthToken']."&charset=utf-8&timestamp=124&sign=$sign&orderId=$orderId&courierName=$courierName&courierPhone=$courierPhone";
		$result = MtUnit::postHttps($url, $data);
		return $result;
	
	}
	public static function privacyNumber($dpid){
		$timestamp = time();
		$degradOffset = 0;
		$degradLimit = 1000;
		$developerId = MtUnit::developerId;
		$res = self::getToken($dpid);
		$url = MtUnit::MTHOST."/waimai/order/batchPullPhoneNumber";
		$array = array('appAuthToken'=>$res['appAuthToken'],'charset'=>'utf-8','timestamp'=>$timestamp,"degradOffset"=>$degradOffset,'degradLimit'=>$degradLimit,'developerId'=>$developerId);
		$sign = MtUnit::sign($array);
		$data = "appAuthToken=".$res['appAuthToken']."&charset=utf-8&timestamp=".$timestamp."&sign=".$sign."&degradOffset=".$degradOffset."&degradLimit=".$degradLimit."&developerId=".$developerId;
		$result = MtUnit::postHttps($url, $data);
		return $result;
	}
	/**
	 * 
	 * @param unknown $callback
	 * @param unknown $data
	 * @return mixed
	 * 通过回调函数 先返回结果
	 * 
	 */
	public static function callUserFunc($callback){
		$data = file_get_contents('php://input');
		Helper::writeLog('meituan message--'.$data);
		return call_user_func($callback,$data);
	}
	/**
	 * 
	 * @param $data 订单数据
	 * @param $type 类型 1 推送 2 确认
	 * @return string
	 * 
	 */
	public static function dealOrder($data,$dpid,$type){
		// 生成订单数据数组
		$orderArr = array();
		// 收银机云端同步订单数据
		$orderCloudArr = array();
		$data = Helper::dealString($data);
		$obj = json_decode($data);
		$orderId = (string)$obj->orderId;
		$orderTime = $obj->ctime;
		$payType = $obj->payType;
		$deliveryTime = $obj->deliveryTime;
		if($deliveryTime==0){
			$deliveryTime = $orderTime;
		}
		$orderTime = date('Y-m-d H:i:s',$orderTime);
		$deliveryTime = date('Y-m-d H:i:s',$deliveryTime);
		if($payType==2){
			$orderPayPaytype = 14;
		}else{
			$orderPayPaytype = 0;
		}
		$poiReceiveDetail = json_decode($obj->poiReceiveDetail);
		
		$orderArr['order_info'] = array('creat_at'=>$orderTime,'account_no'=>$orderId,'classes'=>0,'username'=>'','site_id'=>0,'is_temp'=>1,'number'=>1,'order_status'=>$obj->status,'order_type'=>7,'should_total'=>$poiReceiveDetail->wmPoiReceiveCent/100,'reality_total'=>$obj->originalPrice,'takeout_typeid'=>0,'callno'=>$obj->daySeq,'paytype'=>$payType,'appointment_time'=>$deliveryTime,'remark'=>$obj->caution,'taste_memo'=>'');
		$orderArr['order_platform'] = array('original_total'=>$obj->originalPrice,'logistics_total'=>$poiReceiveDetail->logisticsFee/100,'platform_total'=>$poiReceiveDetail->foodShareFeeChargeByPoi/100,'pay_total'=>$poiReceiveDetail->onlinePayment/100,'receive_total'=>$poiReceiveDetail->wmPoiReceiveCent/100);
		$orderArr['order_product'] = array();
		
		$orderCloudArr ['nb_site_no'] = array();
		$orderCloudArr['nb_order'] = array('dpid'=>$dpid,'create_at'=>$orderTime,'account_no'=>$orderId,'classes'=>0,'username'=>'','site_id'=>0,'is_temp'=>1,'number'=>1,'order_status'=>$obj->status,'order_type'=>7,'should_total'=>$poiReceiveDetail->wmPoiReceiveCent/100,'reality_total'=>$obj->originalPrice,'takeout_typeid'=>0,'callno'=>$obj->daySeq,'paytype'=>$payType,'appointment_time'=>$deliveryTime,'remark'=>$obj->caution,'taste_memo'=>'');
		$orderCloudArr['nb_order_platform'] = array('dpid'=>$dpid,'original_total'=>$obj->originalPrice,'logistics_total'=>$poiReceiveDetail->logisticsFee/100,'platform_total'=>$poiReceiveDetail->foodShareFeeChargeByPoi/100,'pay_total'=>$poiReceiveDetail->onlinePayment/100,'receive_total'=>$poiReceiveDetail->wmPoiReceiveCent/100);
		$orderCloudArr['nb_order_product'] = array();
		$array_detail=json_decode($obj->detail,true);
		foreach ($array_detail as $key => $value) {
			$phsCode =  $value['sku_id'];
			$price = $value['price'];
			$amount = $value['quantity'];
			$sql = 'select 0 as is_set,lid,product_name as name,original_price from nb_product where dpid='.$dpid.' and phs_code="'.$phsCode.'" and delete_flag=0 union select 1 as is_set,lid,set_name as name,set_price as original_price from nb_product_set where dpid='.$dpid.' and pshs_code="'.$phsCode.'" and delete_flag=0 ';
			$res = Yii::app()->db->createCommand($sql)->queryRow();
			
			if(!$res){
				$foodName = $value['food_name'];
				
				$orderProduct = array('is_set'=>0,'set_id'=>0,'product_id'=>0,'product_name'=>$foodName.'(未)','original_price'=>$price,'price'=>$price,'amount'=>$amount,'zhiamount'=>1,'product_taste'=>array(),'product_promotion'=>array());
				array_push($orderArr['order_product'], $orderProduct);
				if(!empty($value['box_price'])){
					$orderProduct = array('is_set'=>0,'set_id'=>0,'product_id'=>0,'product_name'=>'餐盒费','original_price'=>$value['box_price'],'price'=>$value['box_price'],'amount'=>$value['box_num'],'zhiamount'=>1,'product_type'=>2,'product_taste'=>array(),'product_promotion'=>array());
					array_push($orderArr['order_product'], $orderProduct);
				}
				
				$orderProduct = array('is_set'=>0,'set_name'=>'','set_price'=>0,'dpid'=>$dpid,'create_at'=>$orderTime,'set_id'=>0,'main_id'=>0,'product_id'=>0,'product_name'=>$foodName.'(未)','product_pic'=>'','original_price'=>$price,'price'=>$price,'amount'=>$amount,'zhiamount'=>1,'product_type'=>0,'product_order_status'=>2,'taste_memo'=>'','product_taste'=>array(),'product_promotion'=>array());
				array_push($orderCloudArr['nb_order_product'], $orderProduct);
				
				if(!empty($value['box_price'])){
					$orderProduct = array('is_set'=>0,'set_name'=>'','set_price'=>0,'dpid'=>$dpid,'create_at'=>$orderTime,'set_id'=>0,'main_id'=>0,'product_id'=>0,'product_name'=>'餐盒费','product_pic'=>'','original_price'=>$value['box_price'],'price'=>$value['box_price'],'amount'=>$value['box_num'],'zhiamount'=>1,'product_type'=>2,'product_order_status'=>2,'taste_memo'=>'','product_taste'=>array(),'product_promotion'=>array());
					array_push($orderCloudArr['nb_order_product'], $orderProduct);
				}
			}else{
				$foodProperty = $value['food_property'];
				$tasteArr = array();
				if($foodProperty!=''){
					$spes = explode(',', $foodProperty);
					foreach ($spes as $k => $val) {
						array_push($tasteArr, array('dpid'=>$dpid,'create_at'=>$orderTime,'taste_id'=>'0','is_order'=>'0','taste_name'=>$val,'name'=>$val));
					}
				}
				if( $res['is_set']==0){
					// 单品 
					$orderProduct = array('is_set'=>0,'set_id'=>0,'product_id'=>$res['lid'],'product_name'=>$res['name'],'original_price'=>$res['original_price'],'price'=>$price,'amount'=>$amount,'zhiamount'=>$amount,'product_taste'=>$tasteArr,'product_promotion'=>array());
					array_push($orderArr['order_product'], $orderProduct);
					
					$orderProduct = array('is_set'=>0,'set_name'=>'','set_price'=>0,'dpid'=>$dpid,'create_at'=>$orderTime,'set_id'=>0,'main_id'=>0,'product_id'=>$res['lid'],'product_name'=>$res['name'],'product_pic'=>'','original_price'=>$res['original_price'],'price'=>$price,'amount'=>$amount,'zhiamount'=>1,'product_type'=>0,'product_order_status'=>2,'taste_memo'=>'','product_taste'=>$tasteArr,'product_promotion'=>array());
					array_push($orderCloudArr['nb_order_product'], $orderProduct);
				}else{
					// 套餐
					$sql = 'select sum(t.number*t1.original_price) from nb_product_set_detail t left join nb_product t1 on t.product_id=t1.lid and t.dpid=t1.dpid where t.set_id='.$res['lid'].' and t.dpid='.$dpid.' and t.is_select=1 and t.delete_flag=0 and t1.delete_flag=0';
					$totalProductPrice = Yii::app()->db->createCommand($sql)->queryScalar();
					
					$sql = 'select t.*,t1.product_name,t1.original_price from nb_product_set_detail t left join nb_product t1 on t.product_id=t1.lid and t.dpid=t1.dpid where t.set_id='.$res['lid'].' and t.dpid='.$dpid.' and t.is_select=1 and t.delete_flag=0 and t1.delete_flag=0';
					$productDetails = Yii::app()->db->createCommand($sql)->queryAll();
					
					$pdetail = array();
					foreach ($productDetails as $detail){
						$itemPrice = Helper::dealProductPrice($detail['original_price'], $totalProductPrice, $price);
						
						$orderProduct = array('is_set'=>1,'set_id'=>$res['lid'],'product_id'=>$detail['product_id'],'product_name'=>$detail['product_name'],'original_price'=>$detail['original_price'],'price'=>$itemPrice,'amount'=>$detail['number']*$amount,'zhiamount'=>$amount,'product_taste'=>array(),'product_promotion'=>array());
						array_push($orderArr['order_product'], $orderProduct);
						
						array_push($pdetail,array('dpid'=>$dpid,'create_at'=>$orderTime,'set_id'=>$res['lid'],'main_id'=>0,'product_id'=>$detail['product_id'],'product_name'=>$detail['product_name'],'product_pic'=>'','original_price'=>$detail['original_price'],'price'=>$itemPrice,'amount'=>$detail['number']*$amount,'zhiamount'=>$amount,'product_type'=>0,'product_order_status'=>2,'taste_memo'=>''));
					}
					$orderProduct = array('is_set'=>1,'set_name'=>$res['name'],'set_price'=>$price,'amount'=>$amount,'set_detail'=>$pdetail,'product_taste'=>$tasteArr,'product_promotion'=>array());
					array_push($orderCloudArr['nb_order_product'], $orderProduct);
				}
				if(!empty($value['box_price'])){
					$orderProduct = array('is_set'=>'0','set_id'=>'0','product_id'=>'0','product_name'=>'餐盒费','original_price'=>$value['box_price'],'price'=>$value['box_price'],'amount'=>$value['box_num'],'zhiamount'=>$value['box_num'],'product_type'=>2,'product_taste'=>array(),'product_promotion'=>array());
					array_push($orderArr['order_product'], $orderProduct);
					
					$orderProduct = array('is_set'=>0,'set_id'=>0,'set_name'=>'','set_price'=>0,'dpid'=>$dpid,'create_at'=>$orderTime,'set_id'=>0,'main_id'=>0,'product_id'=>0,'product_name'=>'餐盒费','product_pic'=>'','original_price'=>$value['box_price'],'price'=>$value['box_price'],'amount'=>$value['box_num'],'zhiamount'=>1,'product_type'=>2,'product_order_status'=>2,'taste_memo'=>'','product_taste'=>array(),'product_promotion'=>array());
					array_push($orderCloudArr['nb_order_product'], $orderProduct);
				}
			}
		}
		// 配送费
		if($obj->shippingFee > 0){
			$orderProduct = array('is_set'=>0,'set_id'=>0,'product_id'=>0,'product_name'=>'配送费','original_price'=>$obj->shippingFee,'price'=>$obj->shippingFee,'amount'=>1,'zhiamount'=>1,'product_type'=>3,'product_taste'=>array(),'product_promotion'=>array());
			array_push($orderArr['order_product'], $orderProduct);
			
			$orderProduct = array('is_set'=>0,'set_name'=>'','set_price'=>0,'dpid'=>$dpid,'create_at'=>$orderTime,'set_id'=>0,'main_id'=>0,'product_id'=>0,'product_name'=>'配送费','product_pic'=>'','original_price'=>$obj->shippingFee,'price'=>$obj->shippingFee,'amount'=>1,'zhiamount'=>1,'product_type'=>3,'product_order_status'=>2,'taste_memo'=>'','product_taste'=>array(),'product_promotion'=>array());
			array_push($orderCloudArr['nb_order_product'], $orderProduct);
		}
		$receiveAddress = $obj->recipientAddress;
		$orderArr['order_address'] = array(array('consignee'=>$obj->recipientName,'street'=>$receiveAddress,'mobile'=>$obj->recipientPhone,'tel'=>$obj->recipientPhone));
		$orderArr['order_pay'] = array(array('pay_amount'=>$poiReceiveDetail->wmPoiReceiveCent/100,'paytype'=>$orderPayPaytype,'payment_method_id'=>0,'paytype_id'=>0,'remark'=>''));
		
		$receiveArr = explode('@#', $receiveAddress);
		$orderCloudArr['nb_order_address'] = array(array('dpid'=>$dpid,'consignee'=>$obj->recipientName,'privince'=>'','city'=>'','area'=>'','street'=>$receiveArr[0],'mobile'=>$obj->recipientPhone,'tel'=>$obj->recipientPhone));
		$orderCloudArr['nb_order_pay'] = array(array('dpid'=>$dpid,'create_at'=>$orderTime,'account_no'=>$orderId,'pay_amount'=>$poiReceiveDetail->wmPoiReceiveCent/100,'paytype'=>$orderPayPaytype,'payment_method_id'=>0,'paytype_id'=>0,'remark'=>''));
		
		// 整单口味
		$orderCloudArr['nb_order_taste'] = array();
		// 整单优惠
		$orderArr['order_discount'] = array();
		$orderCloudArr['nb_order_account_discount'] = array();
		
		$extras = json_decode($obj->extras,true);
		foreach ($extras as  $extra) {
			if(!empty($extra)){
				array_push($orderArr['order_discount'],array('discount_title'=>$extra['remark'],'discount_type'=>'5','discount_id'=>'0','discount_money'=>$extra['reduce_fee']));
				array_push($orderCloudArr['nb_order_account_discount'],array('discount_title'=>$extra['remark'],'discount_type'=>'5','discount_id'=>'0','discount_money'=>$extra['reduce_fee']));
			}
		}
		
		
		// type 同步类型  2订单
		$orderData = array('sync_lid'=>0,'dpid'=>$dpid,'type'=>2,'is_pos'=>0,'posLid'=>0,'data'=>json_encode($orderArr));
		
		$orderStr = json_encode($orderData);
		$orderCloudStr = json_encode($orderCloudArr);
		
		// 放入redis中
		$result = WxRedis::pushOrder($dpid, $orderStr);
		if(!$result){
			Helper::writeLog('redis缓存失败 :类型:美团-订单pushOrder;dpid:'.$dpid.';data:'.$orderStr);
		}
		$result = WxRedis::pushPlatform($dpid, $orderCloudStr);
		if(!$result){
			Helper::writeLog('redis缓存失败 :类型:美团-接单pushPlatform;dpid:'.$dpid.';data:'.$orderCloudStr);
		}
		return true;
	}
}
