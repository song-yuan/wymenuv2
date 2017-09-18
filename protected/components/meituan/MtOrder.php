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
	public static function order($data){
		if(empty($data)){
			return '200';
		}
		$data = urldecode($data);
		$resArr = MtUnit::dealData($data);
		$ePoiId = $resArr['ePoiId'];
		$order = $resArr['order'];
		
		$obj = json_decode($order);
		$orderId = $obj->orderId;
		
		$res = MtUnit::getWmSetting($ePoiId);
		if(!empty($res)&&$res['is_receive']==1){
			$mtToken = self::getToken($ePoiId);
			$timetamp = time();
			if($mtToken){
				$url = 'http://api.open.cater.meituan.com/waimai/order/confirm';
				$array= array('appAuthToken'=>$mtToken['appAuthToken'],'charset'=>'utf-8','timestamp'=>$timetamp,'orderId'=>$orderId);
				$sign=MtUnit::sign($array);
				$data = "appAuthToken=".$mtToken['appAuthToken']."&charset=utf-8&timestamp=".$timetamp."&sign=".$sign."&orderId=".$orderId;
				$result = MtUnit::postHttps($url, $data);
				$obj = json_decode($result);
				if($obj->data=='OK'){
					return true;
				}else{
					return false;
				}
			}else{
				return true;
			}
		}else{
			return true;
		}
	}
	public static function token($data){
		if(empty($data)){
			return '200';
		}
		Helper::writeLog('bd:'.$data);
		$resArr = MtUnit::dealData($data);
		$ePoiId = $resArr['ePoiId'];
		$appAuthToken = $resArr['appAuthToken'];
		$timestamp = isset($resArr['timestamp'])?$resArr['timestamp']:time();
		$sql = 'select * from nb_meituan_token where dpid='.$ePoiId.' and delete_flag=0';
		$result = Yii::app()->db->createCommand($sql)->queryRow();
		if($result){
			$sql = 'update nb_meituan_token set appAuthToken='.$appAuthToken.',timestamp='.$timestamp.' where lid='.$result['lid'].' and dpid='.$result['dpid'];
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
	public static function orderconfirm($data){
		if(empty($data)){
			return '200';
		}
		$data = urldecode($data);
		$resArr = MtUnit::dealData($data);
		$ePoiId = $resArr['ePoiId'];
		$order = $resArr['order'];
		Helper::writeLog($ePoiId.'--meituan message--'.$order);
		$result = self::dealOrder($order,$ePoiId,2);
		$reobj = json_decode($result);
		if($reobj->status){
			return true;
		}
		return false;
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
		if(empty($data)){
			return '200';
		}
		Helper::writeLog('jcbd:'.$data);
		$resArr = MtUnit::dealData($data);
		$ePoiId = $resArr['ePoiId'];
		$timestamp = $resArr['timestamp'];
		$sql = 'select * from nb_meituan_token where dpid='.$ePoiId.' and type=2 and ePoiId='.$ePoiId.' and timestamp="'.$timestamp.'"';
		$releaseBing = Yii::app()->db->createCommand($sql)->queryRow();
		if(!empty($releaseBing)){
			return '{"data":"OK"}';
		}
		$sql = 'select * from nb_meituan_token where dpid='.$ePoiId.' and type=1 and ePoiId='.$ePoiId;
		$mtToken = Yii::app()->db->createCommand($sql)->queryRow();
		if(empty($mtToken)){
			return '{"data":"OK"}';
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
		$sql = "update nb_meituan_token set delete_flag=1 where type=1 and dpid=".$ePoiId." and ePoiId=".$ePoiId;
		$res = Yii::app()->db->createCommand($sql)->execute();
		if($res){
			return '{"data":"OK"}';
		}
		return '{"data":"error"}';
	}
	public static function orderDistr($dpid,$orderId,$courierName,$courierPhone){
		$sql = "select appAuthToken from nb_meituan_token where dpid=$dpid and delete_flag=0";
		$res = Yii::app()->db->createCommand($sql)->queryRow();
		$url = "http://api.open.cater.meituan.com/waimai/order/delivering";
		$array= array('appAuthToken'=>$res['appAuthToken'],'charset'=>'utf-8','timestamp'=>124,'orderId'=>$orderId );
		$sign=MtUnit::sign($array);
		$data = "appAuthToken=".$res['appAuthToken']."&charset=utf-8&timestamp=124&sign=$sign&orderId=$orderId&courierName=$courierName&courierPhone=$courierPhone";
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
		$data = Helper::dealString($data);
		$obj = json_decode($data);
		$orderArr = array();
		$orderTime = $obj->ctime;
		$payType = $obj->payType;
		if($payType==2){
			$orderPayPaytype = 14;
		}else{
			$orderPayPaytype = 0;
		}
		$poiReceiveDetail = json_decode($obj->poiReceiveDetail);
		
		$orderArr['order_info'] = array('creat_at'=>date('Y-m-d H:i:s',$orderTime),'account_no'=>$obj->orderId,'classes'=>0,'username'=>'','site_id'=>0,'is_temp'=>1,'number'=>1,'order_status'=>$obj->status,'order_type'=>7,'should_total'=>$poiReceiveDetail->wmPoiReceiveCent/100,'reality_total'=>$obj->originalPrice,'takeout_typeid'=>0,'callno'=>$obj->daySeq,'paytype'=>$payType,'remark'=>$obj->caution);
		$orderArr['order_platform'] = array('original_total'=>$obj->originalPrice,'logistics_total'=>$poiReceiveDetail->logisticsFee/100,'platform_total'=>$poiReceiveDetail->foodShareFeeChargeByPoi/100,'pay_total'=>$poiReceiveDetail->onlinePayment/100,'receive_total'=>$poiReceiveDetail->wmPoiReceiveCent/100);
		$orderArr['order_product'] = array();
		$array_detail=json_decode($obj->detail,true);
		foreach ($array_detail as $key => $value) {
			$phsCode =  $value['sku_id'];
			$price = $value['price'];
			$amount = $value['quantity'];
			$sql = 'select 0 as is_set,lid,product_name as name from nb_product where dpid='.$dpid.' and phs_code="'.$phsCode.'" and delete_flag=0 union select 1 as is_set,lid,set_name as name from nb_product_set where dpid='.$dpid.' and pshs_code="'.$phsCode.'" and delete_flag=0 ';
			$res = Yii::app()->db->createCommand($sql)->queryRow();
			
			if(!$res){
				$foodName = $value['food_name'];
				$orderProduct = array('is_set'=>0,'set_id'=>0,'product_id'=>0,'product_name'=>$foodName.'(未对应菜品)','original_price'=>$price,'price'=>$price,'amount'=>$amount,'zhiamount'=>$amount,'product_taste'=>array(),'product_promotion'=>array());
				array_push($orderArr['order_product'], $orderProduct);
				
				if(!empty($value['box_price'])){
					$orderProduct = array('is_set'=>0,'set_id'=>0,'product_id'=>0,'product_name'=>'餐盒费','original_price'=>$value['box_price'],'price'=>$value['box_price'],'amount'=>$value['box_num'],'zhiamount'=>$value['box_num'],'product_type'=>2,'product_taste'=>array(),'product_promotion'=>array());
					array_push($orderArr['order_product'], $orderProduct);
				}
			}else{
				if( $res['is_set']==0){
					$foodProperty = $value['food_property'];
					$tasteArr = array();
					if($foodProperty!=''){
						$spes = split(',', $foodProperty);
						foreach ($spes as $k => $val) {
							array_push($tasteArr, array("taste_id"=>"0","is_order"=>"0","taste_name"=>$val));
						}
					}
					$orderProduct = array('is_set'=>$res['is_set'],'set_id'=>0,'product_id'=>$res['lid'],'product_name'=>$res['name'],'original_price'=>$price,'price'=>$price,'amount'=>$amount,'zhiamount'=>$amount,'product_taste'=>$tasteArr,'product_promotion'=>array());
					array_push($orderArr['order_product'], $orderProduct);
				}else{
					$sql = 'select sum(t.number*t1.original_price) from nb_product_set_detail t left join nb_product t1 on t.product_id=t1.lid and t.dpid=t1.dpid where t.set_id='.$res['lid'].' and t.dpid='.$dpid.' and t.is_select=1 and t.delete_flag=0 and t1.delete_flag=0';
					$totalProductPrice = Yii::app()->db->createCommand($sql)->queryScalar();
					$sql = 'select t.*,t1.product_name,t1.original_price from nb_product_set_detail t left join nb_product t1 on t.product_id=t1.lid and t.dpid=t1.dpid where t.set_id='.$res['lid'].' and t.dpid='.$dpid.' and t.is_select=1 and t.delete_flag=0 and t1.delete_flag=0';
					$productDetails = Yii::app()->db->createCommand($sql)->queryAll();
					$hasPrice = 0;
					foreach ($productDetails as $i=>$detail){
						if($totalProductPrice > 0){
							$eachPrice = $detail['original_price']*$detail['number']/$totalProductPrice*$price;
						}else{
							$eachPrice = 0;
						}
						$hasPrice += $eachPrice;
						if($i+1 == count($detail)){
							$leavePrice = $hasPrice - $price;
							if($leavePrice > 0){
								$itemPrice =  $eachPrice - $leavePrice;
							}else{
								$itemPrice =  $eachPrice - $leavePrice;
							}
						}else{
							$itemPrice = $eachPrice;
						}
						$itemPrice = number_format($itemPrice,4);
						$orderProduct = array('is_set'=>$res['is_set'],'set_id'=>$res['lid'],'product_id'=>$detail['product_id'],'product_name'=>$detail['product_name'],'original_price'=>$itemPrice,'price'=>$itemPrice,'amount'=>$amount*$detail['number'],'zhiamount'=>$amount,'product_taste'=>array(),'product_promotion'=>array());
						array_push($orderArr['order_product'], $orderProduct);
					}
				}
				if(!empty($value['box_price'])){
					$orderProduct = array('is_set'=>'0','set_id'=>'0','product_id'=>'0','product_name'=>'餐盒费','original_price'=>$value['box_price'],'price'=>$value['box_price'],'amount'=>$value['box_num'],'zhiamount'=>$value['box_num'],'product_type'=>2,'product_taste'=>array(),'product_promotion'=>array());
					array_push($orderArr['order_product'], $orderProduct);
				}
			}
		}
		// 配送费
		if($obj->shippingFee > 0){
			$orderProduct = array('is_set'=>0,'set_id'=>0,'product_id'=>0,'product_name'=>'配送费','original_price'=>$obj->shippingFee,'price'=>$obj->shippingFee,'amount'=>1,'zhiamount'=>1,'product_type'=>3,'product_taste'=>array(),'product_promotion'=>array());
			array_push($orderArr['order_product'], $orderProduct);
		}
		if(empty($orderArr['order_product'])){
			return '{ "data": "OK"}';
		}
		$extras = json_decode($obj->extras,true);
		// 整单优惠
		$orderArr['order_discount'] = array();
		
		foreach ($extras as  $extra) {
			if(!empty($extra)){
				array_push($orderArr['order_discount'],array('discount_title'=>$extra['remark'],'discount_type'=>'5','discount_id'=>'0','discount_money'=>$extra['reduce_fee']));
			}
		}
		
		$orderArr['order_address'] = array(array('consignee'=>$obj->recipientName,'street'=>$obj->recipientAddress,'mobile'=>$obj->recipientPhone,'tel'=>$obj->recipientPhone));
		$orderArr['order_pay'] = array(array('pay_amount'=>$poiReceiveDetail->wmPoiReceiveCent/100,'paytype'=>$orderPayPaytype,'payment_method_id'=>0,'paytype_id'=>0,'remark'=>''));
		$orderStr = json_encode($orderArr);
		$data = array('dpid'=>$dpid,'data'=>$orderStr);
		$result = DataSyncOperation::operateOrder($data);
		return $result;
	}
}
