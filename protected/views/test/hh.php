<?php 
$dpid = 42;
$type = 2;// 1 美团  2 饿了么 3 微信
$accountNo = '';
$data = '';


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
$result = WxRedis::pushPlatform($dpid, $orderCloudStr);		
var_dump($result);
exit;
?>