<?php 
$dpid = 461;
$type = 1;// 1 美团  2 饿了么 3 微信
$accountNo = '';
$data = 'developerId=100746&ePoiId=0000000461&sign=1b5f3d95d447da27e3ee0802a3b91bfecc1aa9a1&order=%7B%22avgSendTime%22%3A1878.0%2C%22caution%22%3A%22%22%2C%22cityId%22%3A450100%2C%22ctime%22%3A1527001306%2C%22daySeq%22%3A%2211%22%2C%22deliveryTime%22%3A0%2C%22detail%22%3A%22%5B%7B%5C%22app_food_code%5C%22%3A%5C%22%E6%8E%8C%E6%9F%9C%EF%BC%81%E7%BB%99%E6%88%91%E6%9D%A51%E5%8C%85%E7%95%AA%E8%8C%84%E6%B2%99%E5%8F%B8%E9%85%B1%5C%22%2C%5C%22box_num%5C%22%3A1%2C%5C%22box_price%5C%22%3A0%2C%5C%22cart_id%5C%22%3A0%2C%5C%22food_discount%5C%22%3A1%2C%5C%22food_name%5C%22%3A%5C%22%E6%8E%8C%E6%9F%9C%EF%BC%81%E7%BB%99%E6%88%91%E6%9D%A51%E5%8C%85%E7%95%AA%E8%8C%84%E6%B2%99%E5%8F%B8%E9%85%B1%5C%22%2C%5C%22food_property%5C%22%3A%5C%22%5C%22%2C%5C%22price%5C%22%3A0%2C%5C%22quantity%5C%22%3A1%2C%5C%22sku_id%5C%22%3A%5C%22%5C%22%2C%5C%22spec%5C%22%3A%5C%22%5C%22%2C%5C%22unit%5C%22%3A%5C%22%E4%BB%BD%5C%22%7D%2C%7B%5C%22app_food_code%5C%22%3A%5C%22%E6%8E%8C%E6%9F%9C%EF%BC%81%E7%BB%99%E6%88%91%E6%9D%A51%E5%8C%85%E7%B3%96%E9%86%8B%E9%85%B1%5C%22%2C%5C%22box_num%5C%22%3A1%2C%5C%22box_price%5C%22%3A0%2C%5C%22cart_id%5C%22%3A0%2C%5C%22food_discount%5C%22%3A1%2C%5C%22food_name%5C%22%3A%5C%22%E6%8E%8C%E6%9F%9C%EF%BC%81%E7%BB%99%E6%88%91%E6%9D%A51%E5%8C%85%E7%B3%96%E9%86%8B%E9%85%B1%5C%22%2C%5C%22food_property%5C%22%3A%5C%22%5C%22%2C%5C%22price%5C%22%3A0%2C%5C%22quantity%5C%22%3A1%2C%5C%22sku_id%5C%22%3A%5C%22%5C%22%2C%5C%22spec%5C%22%3A%5C%22%5C%22%2C%5C%22unit%5C%22%3A%5C%22%E4%BB%BD%5C%22%7D%2C%7B%5C%22app_food_code%5C%22%3A%5C%22%E6%8E%8C%E6%9F%9C%EF%BC%81%E7%BB%99%E6%88%91%E6%9D%A51%E5%8C%85%E9%BB%91%E8%83%A1%E6%A4%92%E9%A6%99%E8%BE%A3%E7%B2%89%5C%22%2C%5C%22box_num%5C%22%3A1%2C%5C%22box_price%5C%22%3A0%2C%5C%22cart_id%5C%22%3A0%2C%5C%22food_discount%5C%22%3A1%2C%5C%22food_name%5C%22%3A%5C%22%E6%8E%8C%E6%9F%9C%EF%BC%81%E7%BB%99%E6%88%91%E6%9D%A51%E5%8C%85%E9%BB%91%E8%83%A1%E6%A4%92%E9%A6%99%E8%BE%A3%E7%B2%89%5C%22%2C%5C%22food_property%5C%22%3A%5C%22%5C%22%2C%5C%22price%5C%22%3A0%2C%5C%22quantity%5C%22%3A1%2C%5C%22sku_id%5C%22%3A%5C%22%5C%22%2C%5C%22spec%5C%22%3A%5C%22%5C%22%2C%5C%22unit%5C%22%3A%5C%22%E4%BB%BD%5C%22%7D%2C%7B%5C%22app_food_code%5C%22%3A%5C%22%E6%8B%9B%E7%89%8C%E5%A5%A5%E5%B0%94%E8%89%AF%E7%83%A4%E9%B8%A1%EF%BC%88%E5%80%BC%E5%BE%97%E5%B0%9D%E8%AF%95%EF%BC%89%5C%22%2C%5C%22box_num%5C%22%3A1%2C%5C%22box_price%5C%22%3A0%2C%5C%22cart_id%5C%22%3A0%2C%5C%22food_discount%5C%22%3A1%2C%5C%22food_name%5C%22%3A%5C%22%E6%8B%9B%E7%89%8C%E5%A5%A5%E5%B0%94%E8%89%AF%E7%83%A4%E9%B8%A1%EF%BC%88%E5%80%BC%E5%BE%97%E5%B0%9D%E8%AF%95%EF%BC%89%5C%22%2C%5C%22food_property%5C%22%3A%5C%22%5C%22%2C%5C%22price%5C%22%3A25%2C%5C%22quantity%5C%22%3A1%2C%5C%22sku_id%5C%22%3A%5C%22%5C%22%2C%5C%22spec%5C%22%3A%5C%22%5C%22%2C%5C%22unit%5C%22%3A%5C%22%E4%BB%BD%5C%22%7D%5D%22%2C%22dinnersNumber%22%3A0%2C%22ePoiId%22%3A%220000000461%22%2C%22extras%22%3A%22%5B%7B%5C%22act_detail_id%5C%22%3A1984856183%2C%5C%22mt_charge%5C%22%3A0%2C%5C%22poi_charge%5C%22%3A4.2%2C%5C%22reduce_fee%5C%22%3A4.2%2C%5C%22remark%5C%22%3A%5C%22%E8%B4%AD%E4%B9%B0%E6%8B%9B%E7%89%8C%E5%A5%A5%E5%B0%94%E8%89%AF%E7%83%A4%E9%B8%A1%EF%BC%88%E5%80%BC%E5%BE%97%E5%B0%9D%E8%AF%95%EF%BC%89%E5%8E%9F%E4%BB%B725.0%E5%85%83%E7%8E%B0%E4%BB%B720.8%E5%85%83%5C%22%2C%5C%22type%5C%22%3A17%7D%2C%7B%7D%5D%22%2C%22hasInvoiced%22%3A0%2C%22invoiceTitle%22%3A%22%22%2C%22isFavorites%22%3Afalse%2C%22isPoiFirstOrder%22%3Atrue%2C%22isThirdShipping%22%3A0%2C%22latitude%22%3A22.770992%2C%22logisticsCode%22%3A%222002%22%2C%22longitude%22%3A108.29145%2C%22orderId%22%3A39791162951971772%2C%22orderIdView%22%3A39791162951971772%2C%22originalPrice%22%3A28.0%2C%22payType%22%3A2%2C%22poiAddress%22%3A%22%E5%8D%97%E5%AE%81%E5%B8%82%E6%96%B0%E5%B1%AF%E8%B7%AF18%E5%8F%B7%E4%B8%AD%E6%97%AD%E4%B8%AD%E5%A4%AE%E5%9F%8E%E4%B8%80%E5%B1%8212-117%E5%8F%B7%E9%93%BA%22%2C%22poiFirstOrder%22%3Atrue%2C%22poiId%22%3A3979116%2C%22poiName%22%3A%22%E5%BF%AB%E4%B9%90%E6%98%9F%E6%B1%89%E5%A0%A1%EF%BC%88%E6%B1%9F%E5%8D%97%E6%99%AE%E7%BD%97%E6%97%BA%E6%96%AF%E5%BA%97%EF%BC%89%22%2C%22poiPhone%22%3A%2215240650532%22%2C%22poiReceiveDetail%22%3A%22%7B%5C%22actOrderChargeByMt%5C%22%3A%5B%7B%5C%22comment%5C%22%3A%5C%22%E6%B4%BB%E5%8A%A8%E6%AC%BE%5C%22%2C%5C%22feeTypeDesc%5C%22%3A%5C%22%E6%B4%BB%E5%8A%A8%E6%AC%BE%5C%22%2C%5C%22feeTypeId%5C%22%3A10019%2C%5C%22moneyCent%5C%22%3A0%7D%5D%2C%5C%22actOrderChargeByPoi%5C%22%3A%5B%7B%5C%22comment%5C%22%3A%5C%22%E8%B4%AD%E4%B9%B0%E6%8B%9B%E7%89%8C%E5%A5%A5%E5%B0%94%E8%89%AF%E7%83%A4%E9%B8%A1%EF%BC%88%E5%80%BC%E5%BE%97%E5%B0%9D%E8%AF%95%EF%BC%89%E5%8E%9F%E4%BB%B725.0%E5%85%83%E7%8E%B0%E4%BB%B720.8%E5%85%83%5C%22%2C%5C%22feeTypeDesc%5C%22%3A%5C%22%E6%B4%BB%E5%8A%A8%E6%AC%BE%5C%22%2C%5C%22feeTypeId%5C%22%3A10019%2C%5C%22moneyCent%5C%22%3A420%7D%5D%2C%5C%22foodShareFeeChargeByPoi%5C%22%3A416%2C%5C%22logisticsFee%5C%22%3A300%2C%5C%22onlinePayment%5C%22%3A2380%2C%5C%22wmPoiReceiveCent%5C%22%3A1664%7D%22%2C%22recipientAddress%22%3A%22%E5%A4%96%E8%BF%90%C2%B7%E4%B8%BD%E6%B1%87%E5%98%89%E5%9B%AD+%286%E6%A0%8B1%E5%8D%95%E5%85%83301%29%40%23%E5%B9%BF%E8%A5%BF%E5%A3%AE%E6%97%8F%E8%87%AA%E6%B2%BB%E5%8C%BA%E5%8D%97%E5%AE%81%E5%B8%82%E6%B1%9F%E5%8D%97%E5%8C%BA%E5%8F%8B%E8%B0%8A%E8%B7%AF%E8%A5%BF%E4%B8%80%E9%87%8C%E5%8F%8B%E8%B0%8A%E8%B7%AF%E8%A5%BF%E4%B8%80%E9%87%8C%E5%A4%96%E8%BF%90%C2%B7%E4%B8%BD%E6%B1%87%E5%98%89%E5%9B%AD-%E5%8C%97%E5%8C%BA%22%2C%22recipientName%22%3A%22%E6%9E%97%E5%AD%90%28%E5%85%88%E7%94%9F%29%22%2C%22recipientPhone%22%3A%2215717717776%22%2C%22shipperPhone%22%3A%22%22%2C%22shippingFee%22%3A3.0%2C%22status%22%3A2%2C%22taxpayerId%22%3A%22%22%2C%22total%22%3A23.8%2C%22utime%22%3A1527001306%7D';


if($type==1){
	$resArr = MtUnit::dealData($data);
	$dpid = $resArr['ePoiId'];
	$order = $resArr['order'];
	$data = urldecode($order);
	// 生成订单数据数组
	$orderArr = array();
	// 收银机云端同步订单数据
	$orderCloudArr = array();
	$data = Helper::dealString($data);
	$obj = json_decode($data);
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
	
	$orderArr['order_info'] = array('creat_at'=>$orderTime,'account_no'=>$obj->orderId,'classes'=>0,'username'=>'','site_id'=>0,'is_temp'=>1,'number'=>1,'order_status'=>$obj->status,'order_type'=>7,'should_total'=>$poiReceiveDetail->wmPoiReceiveCent/100,'reality_total'=>$obj->originalPrice,'takeout_typeid'=>0,'callno'=>$obj->daySeq,'paytype'=>$payType,'appointment_time'=>$deliveryTime,'remark'=>$obj->caution,'taste_memo'=>'');
	$orderArr['order_platform'] = array('original_total'=>$obj->originalPrice,'logistics_total'=>$poiReceiveDetail->logisticsFee/100,'platform_total'=>$poiReceiveDetail->foodShareFeeChargeByPoi/100,'pay_total'=>$poiReceiveDetail->onlinePayment/100,'receive_total'=>$poiReceiveDetail->wmPoiReceiveCent/100);
	$orderArr['order_product'] = array();
	
	$orderCloudArr ['nb_site_no'] = array();
	$orderCloudArr['nb_order'] = array('dpid'=>$dpid,'create_at'=>$orderTime,'account_no'=>$obj->orderId,'classes'=>0,'username'=>'','site_id'=>0,'is_temp'=>1,'number'=>1,'order_status'=>$obj->status,'order_type'=>7,'should_total'=>$poiReceiveDetail->wmPoiReceiveCent/100,'reality_total'=>$obj->originalPrice,'takeout_typeid'=>0,'callno'=>$obj->daySeq,'paytype'=>$payType,'appointment_time'=>$deliveryTime,'remark'=>$obj->caution,'taste_memo'=>'');
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
			if( $res['is_set']==0){
				// 单品
				$foodProperty = $value['food_property'];
				$tasteArr = array();
				if($foodProperty!=''){
					$spes = explode(',', $foodProperty);
					foreach ($spes as $k => $val) {
						array_push($tasteArr, array('dpid'=>$dpid,'create_at'=>$orderTime,'taste_id'=>'0','is_order'=>'0','taste_name'=>$val,'name'=>$val));
					}
				}
					
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
				$orderProduct = array('is_set'=>1,'set_name'=>$res['name'],'set_price'=>$price,'amount'=>$amount,'set_detail'=>$pdetail,'product_taste'=>array(),'product_promotion'=>array());
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
	$orderCloudArr['nb_order_pay'] = array(array('dpid'=>$dpid,'create_at'=>$orderTime,'account_no'=>$obj->orderId,'pay_amount'=>$poiReceiveDetail->wmPoiReceiveCent/100,'paytype'=>$orderPayPaytype,'payment_method_id'=>0,'paytype_id'=>0,'remark'=>''));
	
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
	
	$orderStr = json_encode($orderArr);
	$orderCloudStr = json_encode($orderCloudArr);
	// type 同步类型  2订单
	$orderData = array('dpid'=>$dpid,'type'=>2,'data'=>$orderStr);
}elseif($type==2){
	$orderStatus = 4;
	$data = urldecode($data);
	$obj = json_decode($data);
	$type = $obj->type;
	$shopId = $obj->shopId;
	$message = $obj->message;
	// 生成订单数据数组
	$orderArr = array();
	// 收银机云端同步订单数据
	$orderCloudArr = array();
	$order = json_decode($message);
	$me = $order;
	$orderId = $me->id;
	$createdAt = $me->createdAt;
	$price = $me->totalPrice;
	$originalPrice = $me->originalPrice;
	$book = $me->book; // 是否是预订单
	$income = $me->income;//店铺实收
	$daySn = $me->daySn;
	$groups = $me->groups;
	$deliverFee = $me->deliverFee;// 配送费
	$serviceFee = $me->serviceFee;//饿了么服务费
	$vipDeliveryFeeDiscount = $me->vipDeliveryFeeDiscount;// 会员配送费
	$orderActivities = $me->orderActivities;// 订单活动
	$createdAt = date('Y-m-d H:i:s',strtotime($createdAt));
	if($book){
		$deliveryTime = $me->deliverTime;
		$deliveryTime = date('Y-m-d H:i:s',strtotime($deliveryTime));
	}else{
		$deliveryTime = $createdAt;
	}
	if($me->onlinePaid){
		$payType = 2;
		$orderPayPaytype = 15;
	}else{
		$payType = 1;
		$orderPayPaytype = 0;
	}
	
	$orderArr = array();
	$orderArr['order_info'] = array('creat_at'=>$createdAt,'account_no'=>$orderId,'classes'=>0,'username'=>'','site_id'=>0,'is_temp'=>1,'number'=>1,'order_status'=>$orderStatus,'order_type'=>8,'should_total'=>$income,'reality_total'=>$originalPrice,'takeout_typeid'=>0,'callno'=>$daySn,'paytype'=>$payType,'appointment_time'=>$deliveryTime,'remark'=>$me->description);
	$orderArr['order_platform'] = array('original_total'=>$originalPrice,'logistics_total'=>$deliverFee,'platform_total'=>$serviceFee,'pay_total'=>$price,'receive_total'=>$income);
	
	$orderCloudArr['nb_site_no'] = array();
	$orderCloudArr['nb_order'] = array('dpid'=>$dpid,'create_at'=>$createdAt,'account_no'=>$orderId,'classes'=>0,'username'=>'','site_id'=>0,'is_temp'=>1,'number'=>1,'order_status'=>$orderStatus,'order_type'=>8,'should_total'=>$income,'reality_total'=>$originalPrice,'takeout_typeid'=>0,'callno'=>$daySn,'paytype'=>$payType,'appointment_time'=>$deliveryTime,'remark'=>$me->description,'taste_memo'=>'');
	$orderCloudArr['nb_order_platform'] = array('dpid'=>$dpid,'original_total'=>$originalPrice,'logistics_total'=>$deliverFee,'platform_total'=>$serviceFee,'pay_total'=>$price,'receive_total'=>$income);
	
	$orderArr['order_product'] = array();
	$orderCloudArr['nb_order_product'] = array();
	foreach ($groups as $group){
		$groupType = $group->type;
		$items = $group->items;
		if($groupType=='extra'){
			foreach ($items as $item){
				$amount = $item->quantity;
				$itemprice = $item->price;
				$foodName = $item->name;
					
				$orderProduct = array('is_set'=>0,'set_id'=>0,'product_id'=>0,'product_name'=>'餐盒费','original_price'=>$itemprice,'price'=>$itemprice,'amount'=>$amount,'zhiamount'=>$amount,'product_type'=>2,'product_taste'=>array(),'product_promotion'=>array());
				array_push($orderArr['order_product'], $orderProduct);
					
				$orderProduct = array('is_set'=>0,'set_name'=>'','set_price'=>0,'dpid'=>$dpid,'create_at'=>$createdAt,'set_id'=>0,'main_id'=>0,'product_id'=>0,'product_name'=>'餐盒费','product_pic'=>'','original_price'=>$itemprice,'price'=>$itemprice,'amount'=>$amount,'zhiamount'=>1,'product_type'=>2,'product_order_status'=>2,'taste_memo'=>'','product_taste'=>array(),'product_promotion'=>array());
				array_push($orderCloudArr['nb_order_product'], $orderProduct);
			}
		}else{
			foreach ($items as $item){
				$elemeId = $item->id;
				$amount = $item->quantity;
				$itemprice = $item->price;
				$foodName = $item->name;
				$newSpecs = $item->newSpecs;
				$attributes = $item->attributes;
				$extendCode = $item->extendCode;
				$tasteArr = array();
				if(!empty($newSpecs)){
					foreach ($newSpecs as $newSpec){
						if(strpos($foodName,$newSpec->value)===false){
							array_push($tasteArr, array('dpid'=>$dpid,'create_at'=>$createdAt,'taste_id'=>'0','is_order'=>'0','taste_name'=>$newSpec->value,'name'=>$newSpec->value));
						}
					}
				}
				if(!empty($attributes)){
					foreach ($attributes as $attribute){
						array_push($tasteArr, array('dpid'=>$dpid,'taste_id'=>'0','is_order'=>'0','taste_name'=>$attribute->value,'name'=>$attribute->value));
					}
				}
					
				$sql = 'select 0 as is_set,lid,product_name as name,original_price from nb_product where dpid='.$dpid.' and phs_code="'.$extendCode.'" and delete_flag=0 union select 1 as is_set,lid,set_name as name,set_price as original_price  from nb_product_set where dpid='.$dpid.' and pshs_code="'.$extendCode.'" and delete_flag=0';
				$res = Yii::app()->db->createCommand($sql)->queryRow();
				if(!$res){
					$orderProduct = array('is_set'=>0,'set_id'=>0,'product_id'=>0,'product_name'=>$foodName.'(未)','original_price'=>$itemprice,'price'=>$itemprice,'amount'=>$amount,'zhiamount'=>1,'product_taste'=>array(),'product_promotion'=>array());
					array_push($orderArr['order_product'], $orderProduct);
	
					$orderProduct = array('is_set'=>0,'set_name'=>'','set_price'=>0,'dpid'=>$dpid,'create_at'=>$createdAt,'set_id'=>0,'main_id'=>0,'product_id'=>0,'product_name'=>$foodName.'(未)','product_pic'=>'','original_price'=>$itemprice,'price'=>$itemprice,'amount'=>$amount,'zhiamount'=>1,'product_type'=>0,'product_order_status'=>2,'taste_memo'=>'','product_taste'=>array(),'product_promotion'=>array());
					array_push($orderCloudArr['nb_order_product'], $orderProduct);
				}else{
					if( $res['is_set']==0){
						$orderProduct = array('is_set'=>$res['is_set'],'set_id'=>0,'product_id'=>$res['lid'],'product_name'=>$res['name'],'original_price'=>$res['original_price'],'price'=>$itemprice,'amount'=>$amount,'zhiamount'=>1,'product_taste'=>$tasteArr,'product_promotion'=>array());
						array_push($orderArr['order_product'], $orderProduct);
							
						$orderProduct = array('is_set'=>0,'set_name'=>'','set_price'=>0,'dpid'=>$dpid,'create_at'=>$createdAt,'set_id'=>0,'main_id'=>0,'product_id'=>$res['lid'],'product_name'=>$res['name'],'product_pic'=>'','original_price'=>$res['original_price'],'price'=>$itemprice,'amount'=>$amount,'zhiamount'=>1,'product_type'=>0,'product_order_status'=>2,'taste_memo'=>'','product_taste'=>$tasteArr,'product_promotion'=>array());
						array_push($orderCloudArr['nb_order_product'], $orderProduct);
					}else{
						$sql = 'select sum(t.number*t1.original_price) from nb_product_set_detail t left join nb_product t1 on t.product_id=t1.lid and t.dpid=t1.dpid where t.set_id='.$res['lid'].' and t.dpid='.$dpid.' and t.is_select=1 and t.delete_flag=0 and t1.delete_flag=0';
						$totalProductPrice = Yii::app()->db->createCommand($sql)->queryScalar();
							
						$sql = 'select t.*,t1.product_name,t1.original_price from nb_product_set_detail t left join nb_product t1 on t.product_id=t1.lid and t.dpid=t1.dpid where t.set_id='.$res['lid'].' and t.dpid='.$dpid.' and t.is_select=1 and t.delete_flag=0 and t1.delete_flag=0';
						$productDetails = Yii::app()->db->createCommand($sql)->queryAll();
	
						$pdetail = array();
						foreach ($productDetails as $i=>$detail){
							$itemPrice = Helper::dealProductPrice($detail['original_price'], $totalProductPrice, $itemprice);
							array_push($pdetail,array('dpid'=>$dpid,'create_at'=>$createdAt,'set_id'=>$res['lid'],'main_id'=>0,'product_id'=>$detail['product_id'],'product_name'=>$detail['product_name'],'product_pic'=>'','original_price'=>$detail['original_price'],'price'=>$itemPrice,'amount'=>$detail['number']*$amount,'zhiamount'=>$amount,'product_type'=>0,'product_order_status'=>2,'taste_memo'=>''));
	
							$orderProduct = array('is_set'=>1,'set_id'=>$res['lid'],'product_id'=>$detail['product_id'],'product_name'=>$detail['product_name'],'original_price'=>$detail['original_price'],'price'=>$itemPrice,'amount'=>$detail['number']*$amount,'zhiamount'=>$amount,'product_taste'=>array(),'product_promotion'=>array());
							array_push($orderArr['order_product'], $orderProduct);
						}
						$orderProduct = array('is_set'=>1,'set_name'=>$res['name'],'set_price'=>$itemprice,'amount'=>$amount,'set_detail'=>$pdetail,'product_taste'=>array(),'product_promotion'=>array());
						array_push($orderCloudArr['nb_order_product'], $orderProduct);
					}
				}
			}
		}
	}
	
	// 配送费
	if($deliverFee!=$vipDeliveryFeeDiscount){
		$orderProduct = array('is_set'=>0,'set_id'=>0,'product_id'=>0,'product_name'=>'配送费','original_price'=>$deliverFee,'price'=>$deliverFee-$vipDeliveryFeeDiscount,'amount'=>1,'zhiamount'=>1,'product_type'=>3,'product_taste'=>array(),'product_promotion'=>array());
		array_push($orderArr['order_product'], $orderProduct);
		$orderProduct = array('is_set'=>0,'set_name'=>'','set_price'=>0,'dpid'=>$dpid,'create_at'=>$createdAt,'set_id'=>0,'main_id'=>0,'product_id'=>0,'product_name'=>'配送费','product_pic'=>'','original_price'=>$deliverFee,'price'=>$deliverFee,'amount'=>1,'zhiamount'=>1,'product_type'=>3,'product_order_status'=>2,'taste_memo'=>'','product_taste'=>array(),'product_promotion'=>array());
		array_push($orderCloudArr['nb_order_product'], $orderProduct);
	}
	
	$me->deliveryPoiAddress = $me->deliveryPoiAddress;
	$orderArr['order_address'] = array(array('consignee'=>$me->consignee,'street'=>$me->deliveryPoiAddress,'mobile'=>$me->phoneList[0],'tel'=>$me->phoneList[0]));
	$orderArr['order_pay'] = array(array('pay_amount'=>$income,'paytype'=>$orderPayPaytype,'payment_method_id'=>0,'paytype_id'=>0,'remark'=>''));
	
	
	$orderCloudArr['nb_order_address'] = array(array('dpid'=>$dpid,'consignee'=>$me->consignee,'privince'=>'','city'=>'','area'=>'','street'=>$me->deliveryPoiAddress,'mobile'=>$me->phoneList[0],'tel'=>$me->phoneList[0]));
	$orderCloudArr['nb_order_pay'] = array(array('dpid'=>$dpid,'create_at'=>$createdAt,'account_no'=>$orderId,'pay_amount'=>$income,'paytype'=>$orderPayPaytype,'payment_method_id'=>0,'paytype_id'=>0,'remark'=>''));
	
	// 整单口味
	$orderCloudArr['nb_order_taste'] = array();
	// 整单优惠
	$orderArr['order_discount'] = array();
	$orderCloudArr['nb_order_account_discount'] = array();
	if(!empty($orderActivities)){
		foreach ($orderActivities as $orderActivitive){
			array_push($orderArr['order_discount'],array('discount_title'=>$orderActivitive->name,'discount_type'=>'5','discount_id'=>'0','discount_money'=>abs($orderActivitive->amount)));
			array_push($orderCloudArr['nb_order_account_discount'],array('discount_title'=>$orderActivitive->name,'discount_type'=>'5','discount_id'=>'0','discount_money'=>abs($orderActivitive->amount)));
		}
	}
		
	$orderStr = json_encode($orderArr);
	$orderCloudStr = json_encode($orderCloudArr);
	// type 同步类型  2订单
	$orderData = array('dpid'=>$dpid,'type'=>2,'data'=>$orderStr);
}elseif ($type==3){
	$sql = 'select * from nb_order where dpid='.$dpid.' and account_no="'.$accountNo.'"';
	$result = Yii::app()->db->createCommand($sql)->queryRow();;
	$order = array ();
	$order ['nb_order'] = $result;
	$order ['nb_site_no'] = array();
	if($result['order_type']=='1'){
		// 桌台模式
		$sql = 'select t.*,t1.serial from nb_site_no t,nb_site t1 where t.site_id=t1.lid and t.dpid=t1.dpid and t.lid=' . $result ['site_id'] . ' and t.dpid='.$dpid;
		$siteNo = Yii::app ()->db->createCommand ( $sql )->queryRow ();
		$order ['nb_site_no'] = $siteNo;
	}
	$sql = 'select * from nb_order_platform where order_id=' . $result ['lid'] . ' and dpid='.$dpid;
	$orderPlatform = Yii::app ()->db->createCommand ( $sql )->queryRow ();
	$order ['nb_order_platform'] = $orderPlatform;
	$sql = 'select *,"" as set_name,sum(price*amount/zhiamount) as set_price from nb_order_product where order_id=' . $result ['lid'] . ' and dpid='.$dpid.' and set_id > 0 and delete_flag=0 group by set_id ,main_id'.
			' union select *,"" as set_name,"0.00" as set_price from nb_order_product where order_id=' . $result ['lid'] . ' and dpid='.$dpid.' and set_id = 0 and delete_flag=0';
	$orderProduct = Yii::app ()->db->createCommand ( $sql )->queryAll ();
	foreach ( $orderProduct as $k => $product ) {
		$sql = 'select create_at,taste_id,order_id,is_order,taste_name from nb_order_taste where order_id=' . $product ['lid'] . ' and dpid='.$dpid.' and is_order=0 and delete_flag=0';
		$orderProductTaste = Yii::app ()->db->createCommand ( $sql )->queryAll ();
		$orderProduct [$k] ['product_taste'] = $orderProductTaste;
		$sql = 'select promotion_title,promotion_type,promotion_id,promotion_money,can_cupon from nb_order_product_promotion where order_id=' . $product ['lid'] . ' and dpid='.$dpid.' and delete_flag=0';
		$orderProductPromotion = Yii::app ()->db->createCommand ( $sql )->queryAll ();
		$orderProduct [$k] ['product_promotion'] = $orderProductPromotion;
		if($product['set_id'] > 0){
			$sql = 'select t.*,t1.set_name,t1.set_price from nb_order_product t,nb_product_set t1 where t.set_id=t1.lid and t.dpid=t1.dpid and t.dpid='.$dpid.' and t.order_id=' . $product ['order_id'] . ' and t.set_id='.$product['set_id'];
			$productSet = Yii::app ()->db->createCommand ( $sql )->queryAll ();
			if(!empty($productSet)){
				$orderProduct[$k]['amount'] = $product['zhiamount'];
				$orderProduct[$k]['set_name'] = $productSet[0]['set_name'];
				$orderProduct[$k]['set_price'] = $product['set_price'];
				$orderProduct[$k]['set_detail'] = $productSet;
			}
		}
		$orderProduct[$k]['product_name'] = $product['product_name'];
	}
	$order ['nb_order_product'] = $orderProduct;
	$sql = 'select * from nb_order_pay where order_id=' . $result ['lid'];
	$orderPay = Yii::app ()->db->createCommand ( $sql )->queryAll ();
	$order ['nb_order_pay'] = $orderPay;
	$sql = 'select create_at,taste_id,order_id,is_order,taste_name from nb_order_taste where order_id=' . $result ['lid'] . ' and dpid='.$dpid.' and is_order=1 and delete_flag=0';
	$orderTaste = Yii::app ()->db->createCommand ( $sql )->queryAll ();
	$order ['nb_order_taste'] = $orderTaste;
	$sql = 'select * from nb_order_address where dpid='.$dpid.' and order_lid=' . $result ['lid'].' and delete_flag=0';
	$orderAddress = Yii::app ()->db->createCommand ( $sql )->queryAll ();
	$order ['nb_order_address'] = $orderAddress;
	$sql = 'select * from nb_order_account_discount where dpid='.$dpid.' and order_id='.$result ['lid'].' and delete_flag=0';
	$orderDiscount = Yii::app ()->db->createCommand ( $sql )->queryAll ();
	$order ['nb_order_account_discount'] = $orderDiscount;
	$orderCloudStr = json_encode($order);
}
echo $orderCloudStr;exit;
$result = WxRedis::pushPlatform($dpid, $orderCloudStr);		
var_dump($result);
exit;
?>