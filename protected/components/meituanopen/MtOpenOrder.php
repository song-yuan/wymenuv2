<?php
/**
* order 订单推送接口
* orderconfirm 推送订单到erp厂商接口
*/
class MtOpenOrder
{
	public static function confirm($appid,$apppoicode,$orderId){
		$timestamp = time();
		$appSerect = MtOpenUnit::getMtappsecret($appid);
		$url = MtOpenUnit::MTURL.'order/confirm';
		$data = array(
				'app_id'=>$appid,
				'timestamp'=>$timestamp,
				'app_poi_codes'=>$apppoicode,
		);
		$url = MtOpenUnit::getUrlStr($url, $data, $appSerect);
		$result = Curl::https($url);
		$obj = json_decode($result,true);
		$data = $obj['data'];
		if($data=='ok'){
			return true;
		}
		return false;
	}
	public static function cancel($appid,$apppoicode,$orderId,$reason,$reasonCode){
		$timestamp = time();
		$appSerect = MtOpenUnit::getMtappsecret($appid);
		$url = MtOpenUnit::MTURL.'order/cancel';
		$data = array(
				'app_id'=>$appid,
				'timestamp'=>$timestamp,
				'app_poi_codes'=>$apppoicode,
		);
		$url = MtOpenUnit::getUrlStr($url, $data, $appSerect);
		$result = Curl::https($url);
		$obj = json_decode($result,true);
		$data = $obj['data'];
		if($data=='ok'){
			return true;
		}
		return false;
	}
	public static function order($data){
		$appid = $data['app_id'];
		$appPoiCode = $data['app_poi_code'];
		$orderId = $data['order_id'];
		$res = MtOpenUnit::getMtappsecret($appid,$appPoiCode,$orderId);
		return $res;
		
	}
	public static function orderconfirm($data){
		$result = self::dealOrder($data);
		return true;
	}
	public static function orderCancel($data){
		return true;
	}
	public static function orderReminder($data){
		return true;
	}
	public static function orderRefund($data){
		return true;
	}
	public static function privacyNumber($dpid){
		return true;
	}
	/**
	 * 
	 * @param $data 订单数据
	 * @param $type 类型 1 推送 2 确认
	 * @return string
	 * 
	 */
	public static function dealOrder($data){
		// 生成订单数据数组
		$orderArr = array();
		// 收银机云端同步订单数据
		$orderCloudArr = array();
		$dpid = $data['app_poi_code'];
		$dpid = '0000000027';
		$orderId = $data['order_id'];
		$orderTime = $data['ctime'];
		$payType = $data['pay_type'];
		$deliveryTime = $data['delivery_time'];
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
		$poiReceiveDetail = json_decode($data['detail']);
		
		$ocaution = $data['caution'];
		$caution = strstr($ocaution, '收餐人隐私号', TRUE);
		if($caution===false){
			$caution = $ocaution;
		}
		$orderStatus = $data['status'];
		$dayseq = $data['daySeq'];
		$originPrice = $data['original_price'];
		
		$poirede = $data['poi_receive_detail'];
		$poiredeArr = json_decode($poirede,true);
		$platformTotal = $poiredeArr['foodShareFeeChargeByPoi'];
		$logisticsTotal = $poiredeArr['logisticsFee'];
		$shouldTotal = $poiredeArr['wmPoiReceiveCent'];
		$payTotal = $poiredeArr['onlinePayment'];
		
		$orderArr['order_info'] = array('creat_at'=>$orderTime,'account_no'=>$orderId,'classes'=>0,'username'=>'','site_id'=>0,'is_temp'=>1,'number'=>1,'order_status'=>$orderStatus,'order_type'=>7,'should_total'=>$shouldTotal/100,'reality_total'=>$originPrice,'takeout_typeid'=>0,'callno'=>$dayseq,'paytype'=>$payType,'appointment_time'=>$deliveryTime,'remark'=>$caution,'taste_memo'=>'');
		$orderArr['order_platform'] = array('original_total'=>$originPrice,'logistics_total'=>$logisticsTotal/100,'platform_total'=>$platformTotal/100,'pay_total'=>$payTotal/100,'receive_total'=>$shouldTotal/100);
		$orderArr['order_product'] = array();
		
		$orderCloudArr ['nb_site_no'] = array();
		$orderCloudArr['nb_order'] = array('dpid'=>$dpid,'create_at'=>$orderTime,'account_no'=>$orderId,'classes'=>0,'username'=>'','site_id'=>0,'is_temp'=>1,'number'=>1,'order_status'=>$orderStatus,'order_type'=>7,'should_total'=>$shouldTotal/100,'reality_total'=>$originPrice,'takeout_typeid'=>0,'callno'=>$dayseq,'paytype'=>$payType,'appointment_time'=>$deliveryTime,'remark'=>$caution,'taste_memo'=>'');
		$orderCloudArr['nb_order_platform'] = array('dpid'=>$dpid,'original_total'=>$originPrice,'logistics_total'=>$logisticsTotal/100,'platform_total'=>$platformTotal/100,'pay_total'=>$payTotal/100,'receive_total'=>$shouldTotal/100);
		$orderCloudArr['nb_order_product'] = array();
		
		$pdetail = $data['detail'];
		$proDetail=json_decode($pdetail,true);
		foreach ($proDetail as $key => $value) {
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
				
				$boxPrice = $value['box_price'];
				$boxNum = $value['box_num'];
				if(!empty($boxPrice)){
					$orderProduct = array('is_set'=>0,'set_name'=>'','set_price'=>0,'dpid'=>$dpid,'create_at'=>$orderTime,'set_id'=>0,'main_id'=>0,'product_id'=>0,'product_name'=>'餐盒费','product_pic'=>'','original_price'=>$boxPrice,'price'=>$boxPrice,'amount'=>$boxNum,'zhiamount'=>1,'product_type'=>2,'product_order_status'=>2,'taste_memo'=>'','product_taste'=>array(),'product_promotion'=>array());
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
				$boxPrice = $value['box_price'];
				$boxNum = $value['box_num'];
				if(!empty($boxPrice)){
					$orderProduct = array('is_set'=>'0','set_id'=>'0','product_id'=>'0','product_name'=>'餐盒费','original_price'=>$boxPrice,'price'=>$boxPrice,'amount'=>$boxNum,'zhiamount'=>$value['box_num'],'product_type'=>2,'product_taste'=>array(),'product_promotion'=>array());
					array_push($orderArr['order_product'], $orderProduct);
					
					$orderProduct = array('is_set'=>0,'set_id'=>0,'set_name'=>'','set_price'=>0,'dpid'=>$dpid,'create_at'=>$orderTime,'set_id'=>0,'main_id'=>0,'product_id'=>0,'product_name'=>'餐盒费','product_pic'=>'','original_price'=>$boxPrice,'price'=>$boxPrice,'amount'=>$boxNum,'zhiamount'=>1,'product_type'=>2,'product_order_status'=>2,'taste_memo'=>'','product_taste'=>array(),'product_promotion'=>array());
					array_push($orderCloudArr['nb_order_product'], $orderProduct);
				}
			}
		}
		// 配送费
		$shippingFee = $data['shipping_fee'];
		if($shippingFee > 0){
			$orderProduct = array('is_set'=>0,'set_id'=>0,'product_id'=>0,'product_name'=>'配送费','original_price'=>$shippingFee,'price'=>$shippingFee,'amount'=>1,'zhiamount'=>1,'product_type'=>3,'product_taste'=>array(),'product_promotion'=>array());
			array_push($orderArr['order_product'], $orderProduct);
			
			$orderProduct = array('is_set'=>0,'set_name'=>'','set_price'=>0,'dpid'=>$dpid,'create_at'=>$orderTime,'set_id'=>0,'main_id'=>0,'product_id'=>0,'product_name'=>'配送费','product_pic'=>'','original_price'=>$shippingFee,'price'=>$shippingFee,'amount'=>1,'zhiamount'=>1,'product_type'=>3,'product_order_status'=>2,'taste_memo'=>'','product_taste'=>array(),'product_promotion'=>array());
			array_push($orderCloudArr['nb_order_product'], $orderProduct);
		}
		$receiveAddress = $data['recipient_address'];
		$recipientPhone = $data['recipient_phone'];
		$backupRecipientPhone = $data['backup_recipient_phone'];
		$backupRecipientPhone = json_decode($backupRecipientPhone,true);
		if(!empty($backupRecipientPhone)){
			$backupRecipientPhone = join(',', $backupRecipientPhone);
		}
		$consignee = $data['recipient_name'];
		$orderArr['order_address'] = array(array('consignee'=>$consignee,'street'=>$receiveAddress,'mobile'=>$recipientPhone,'tel'=>$backupRecipientPhone));
		$orderArr['order_pay'] = array(array('pay_amount'=>$shouldTotal/100,'paytype'=>$orderPayPaytype,'payment_method_id'=>0,'paytype_id'=>0,'remark'=>''));
		
		$receiveArr = explode('@#', $receiveAddress);
		
		$orderCloudArr['nb_order_address'] = array(array('dpid'=>$dpid,'consignee'=>$consignee,'privince'=>'','city'=>'','area'=>'','street'=>$receiveArr[0],'mobile'=>$recipientPhone,'tel'=>$backupRecipientPhone));
		$orderCloudArr['nb_order_pay'] = array(array('dpid'=>$dpid,'create_at'=>$orderTime,'account_no'=>$orderId,'pay_amount'=>$shouldTotal/100,'paytype'=>$orderPayPaytype,'payment_method_id'=>0,'paytype_id'=>0,'remark'=>''));
		
		// 整单口味
		$orderCloudArr['nb_order_taste'] = array();
		// 整单优惠
		$orderArr['order_discount'] = array();
		$orderCloudArr['nb_order_account_discount'] = array();
		
		$extras = json_decode($data['extras'],true);
		if(!empty($extras)){
			foreach ($extras as  $extra) {
				if(!isset($extra['rider_fee'])){
					array_push($orderArr['order_discount'],array('discount_title'=>$extra['remark'],'discount_type'=>'5','discount_id'=>'0','discount_money'=>$extra['reduce_fee']));
					array_push($orderCloudArr['nb_order_account_discount'],array('account_no'=>$orderId,'discount_title'=>$extra['remark'],'discount_type'=>'5','discount_id'=>'0','discount_money'=>$extra['reduce_fee']));
				}
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
