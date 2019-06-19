<?php
/**
* order 订单推送接口
* orderconfirm 推送订单到erp厂商接口
*/
class MtOpenOrder
{
	public static function order($data){
		$resArr = MtOpenUnit::urlToArr($data);
		$ePoiId = $resArr['app_poi_code'];
		$detail = $resArr['detail'];
		$order = urldecode(urldecode($detail));
// 		$result = self::dealOrder($order,$ePoiId,2);
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
		
		$ocaution = $obj->caution;
		$caution = strstr($ocaution, '收餐人隐私号', TRUE);
		if($caution===false){
			$caution = $ocaution;
		}
		$orderArr['order_info'] = array('creat_at'=>$orderTime,'account_no'=>$orderId,'classes'=>0,'username'=>'','site_id'=>0,'is_temp'=>1,'number'=>1,'order_status'=>$obj->status,'order_type'=>7,'should_total'=>$poiReceiveDetail->wmPoiReceiveCent/100,'reality_total'=>$obj->originalPrice,'takeout_typeid'=>0,'callno'=>$obj->daySeq,'paytype'=>$payType,'appointment_time'=>$deliveryTime,'remark'=>$caution,'taste_memo'=>'');
		$orderArr['order_platform'] = array('original_total'=>$obj->originalPrice,'logistics_total'=>$poiReceiveDetail->logisticsFee/100,'platform_total'=>$poiReceiveDetail->foodShareFeeChargeByPoi/100,'pay_total'=>$poiReceiveDetail->onlinePayment/100,'receive_total'=>$poiReceiveDetail->wmPoiReceiveCent/100);
		$orderArr['order_product'] = array();
		
		$orderCloudArr ['nb_site_no'] = array();
		$orderCloudArr['nb_order'] = array('dpid'=>$dpid,'create_at'=>$orderTime,'account_no'=>$orderId,'classes'=>0,'username'=>'','site_id'=>0,'is_temp'=>1,'number'=>1,'order_status'=>$obj->status,'order_type'=>7,'should_total'=>$poiReceiveDetail->wmPoiReceiveCent/100,'reality_total'=>$obj->originalPrice,'takeout_typeid'=>0,'callno'=>$obj->daySeq,'paytype'=>$payType,'appointment_time'=>$deliveryTime,'remark'=>$caution,'taste_memo'=>'');
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
		$recipientPhone = $obj->recipientPhone;
		$backupRecipientPhone = isset($obj->backupRecipientPhone)?$obj->backupRecipientPhone:'';
		if($backupRecipientPhone!=''){
			$backupRecipientPhone = json_decode($backupRecipientPhone);
			$backupRecipientPhone = join(',', $backupRecipientPhone);
		}
		
		$orderArr['order_address'] = array(array('consignee'=>$obj->recipientName,'street'=>$receiveAddress,'mobile'=>$recipientPhone,'tel'=>$backupRecipientPhone));
		$orderArr['order_pay'] = array(array('pay_amount'=>$poiReceiveDetail->wmPoiReceiveCent/100,'paytype'=>$orderPayPaytype,'payment_method_id'=>0,'paytype_id'=>0,'remark'=>''));
		
		$receiveArr = explode('@#', $receiveAddress);
		
		$orderCloudArr['nb_order_address'] = array(array('dpid'=>$dpid,'consignee'=>$obj->recipientName,'privince'=>'','city'=>'','area'=>'','street'=>$receiveArr[0],'mobile'=>$recipientPhone,'tel'=>$backupRecipientPhone));
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
				array_push($orderCloudArr['nb_order_account_discount'],array('account_no'=>$orderId,'discount_title'=>$extra['remark'],'discount_type'=>'5','discount_id'=>'0','discount_money'=>$extra['reduce_fee']));
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
