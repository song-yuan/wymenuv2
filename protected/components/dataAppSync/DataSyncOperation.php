<?php
/**
 * 
 * 
 * 云端和本地营业数据实时同步！！！！！！！！！！
 * 本地获得云端新的订单数据，只取完成的，
 * 本地将自己的新的订单数据及会员和优惠数据发送给云端，只发送完成的
 * ///////////////////////////
 * 根据具体的需求添加
 * 目前有：
 * 1获得云端订单，2更改云端订单（状态、支付及口味等信息）
 * 2发送本地订单、支付、结单到云端、
 * 3发送本地交接班、日结、等信息到云端
 * 4发送本地活动信息到云端。
 * 5发送本地会员信息到云端。
 * 
 * 
 */
class DataSyncOperation {
	/**
	 *
	 * 获取pos设备信息
	 *
	 */
	public static function getDataSyncPosInfor($code) {
		if($code){
			$sql = 'select * from nb_pad_setting where pad_code="'.$code.'" and delete_flag=0';
			$result = Yii::app ()->db->createCommand ( $sql )->queryRow ();
			if($result){
				$msg = array('status'=>true,'msg'=>$result);
			}else{
				$msg = array('status'=>false,'msg'=>'');
			}
		}else{
			$msg = array('status'=>false,'msg'=>'');
		}
		return $msg;
	}
	/**
	 * 
	 * 
	 * 获取基础数据表
	 * 
	 * 
	 */
	public static function getDataSyncBaseTables() {
		$dataBase = new DataSyncTables ();
		$baseTables = $dataBase->getBaseTableList ();
		return $baseTables;
	}
	/**
	 * 
	 * 
	 * 获取所有的表 和 结构
	 * 
	 * 
	 */
	public static function getDataSyncAllTables() {
		$dataBase = new DataSyncTables ();
		$allTables = $dataBase->getAllTableList ();
		
		foreach ( $allTables as $k => $table ) {
			$tableStruct = $dataBase->getTableStructure ( $table ['table'] );
			$allTables [$k] ['struct'] = $tableStruct;
		}
		return array (
				'status' => true,
				'msg' => $allTables 
		);
	}
	/**
	 * 
	 * 
	 * 获取初始化数据
	 * 
	 * 
	 */
	public static function getDataSyncData($dpid, $tableName) {
		$dataBase = new DataSyncTableData ( $dpid, $tableName );
		$tableData = $dataBase->getInitData ();
		return array ('status' => true,'msg' => $tableData);
	}
	/**
	 * 
	 * 
	 * 获取需要到本地执行的sql，每次仅限1000条
	 * 
	 * 
	 */
	public static function getCloudSqlData1000($dpid) {
		$allflag = substr ( "11111111111111111111111111111111111111111111111111111", 0, Yii::app ()->params ['sync_maxlocal'] );
		return substr_replace ( $allflag, "0", Yii::app ()->params ['sync_localnum'] - 1, 1 );
	}
	public static function execLocalSql($dpid, $sqldata) {
	}
	
	/**
	 * 
	 * 
	 * 有些更新必须先同步到云端，如产品的库存数量，必须各个客户端都更新云端一个地方，然后同步下来
	 * 采用的策略是用sql语句更新云端，然后从云端同步到本地，
	 * 所以更新时限更新云端，然后更新本地，如果云端失败，保存sql，同步前先调用这个更新sql，再同步云端数据
	 * 具体功能是：
	 * 先操作云端，如果成功则返回，如果失败则存储到nb_sqlcmd_sync
	 *
	 * 这些数据，必须云端一致！！！
	 *
	 * @param type $sql  
	 * 
	 *       	
	 */
	public static function cloudFirt($dpid, $sql) {
		try {
			$dbcloud = Yii::app ()->dbcloud;
			$dbcloud->createCommand ( $sql )->execute ();
			return true;
		} catch ( Exception $e ) {
			$dblocal = Yii::app ()->dblocal;
			$se = new Sequence ( "sqlcmd_sync" );
			$lid = $se->nextval ();
			$data = array (
					'lid' => $lid,
					'dpid' => $dpid,
					'create_at' => date ( 'Y-m-d H:i:s', time () ),
					'sqlcmd' => $sql,
					'is_sync' => '10000' 
			); // 10000表示需要先在服务器端更新！

			$dblocal->createCommand ()->insert ( 'nb_sqlcmd_sync', $data );
			return false;
		}
	}
	
	/**
	 * 
	 * 
	 * 同步云端的数据之前，将本地需要先更新云端的数据查询出来，
	 * 然后更新云端，再同步，如果失败直接返回false
	 * 操作：
	 * 查出所有的sqlcmd_sync，然后执行
	 *
	 * @return boolean
	 * 
	 * 
	 */
	public static function cloudFirstSync($dpid) {
		$dbcloud = Yii::app ()->dbcloud;
		$dblocal = Yii::app ()->dblocal;
		$cloudexec = $dblocal->createCommand ( "select lid,sqlcmd from nb_sqlcmd_sync where is_sync='10000' and dpid=" . $dpid )->queryAll ();
		if (empty ( $cloudexec )) {
			return true;
		}
		$dellist = "(";
		$transactioncloud = $dbcloud->beginTransaction ();
		try {
			foreach ( $cloudexec as $row ) {
				$dbcloud->createCommand ( $row ['sqlcmd'] )->execute ();
				$dellist = $dellist . $row ['lid'] . ",";
			}
			$dellist = $dellist . "0000000000" . ")";
			$dblocal->createCommand ( "delete from nb_sqlcmd_sync where dpid=" . $dpid . " and lid in " . $dellist )->execute ();
			$transactioncloud->commit ();
			return true;
		} catch ( Exception $ex ) {
			// osy//echo $ex->getMessage();
			$transactioncloud->rollback ();
			return false;
		}
	}
	/**
	 * 
	 * 
	 * 获取同步数据
	 * 
	 * 
	 */
	public static function getSyncData($dpid) {
		$data = array ();
		$data ['order'] = array ();
		$data ['member_card'] = array ();
		$transaction = Yii::app ()->db->beginTransaction ();
		try {
			//订单数据
			$sql = 'select * from nb_order where dpid=' . $dpid . ' and order_status=3 and is_sync<>0';
			$results = Yii::app ()->db->createCommand ( $sql )->queryAll ();
			foreach ( $results as $result ) {
				$order = array ();
				$order ['nb_order'] = $result;
				$sql = 'select *,"" as set_name,sum(price) as set_price from nb_order_product where order_id=' . $result ['lid'] . ' and dpid='.$dpid.' and set_id > 0 and delete_flag=0 group by set_id'.
					   ' union select *,"" as set_name,"0.00" as set_price from nb_order_product where order_id=' . $result ['lid'] . ' and dpid='.$dpid.' and set_id = 0 and delete_flag=0';
				$orderProduct = Yii::app ()->db->createCommand ( $sql )->queryAll ();
				foreach ( $orderProduct as $k => $product ) {
					$sql = 'select t.*,t1.name from nb_order_taste t,nb_taste t1 where t.taste_id=t1.lid and t.order_id=' . $product ['lid'] . ' and t.dpid='.$dpid.' and t.is_order=0 and t.delete_flag=0';
					$orderProductTaste = Yii::app ()->db->createCommand ( $sql )->queryAll ();
					$orderProduct [$k] ['product_taste'] = $orderProductTaste;
					if($product['set_id'] > 0){
						$sql = 'select t.*,t1.set_name,t1.set_price from nb_order_product t,nb_product_set t1 where t.set_id=t1.lid and t.dpid=t1.dpid and t.dpid='.$dpid.' and t.order_id=' . $product ['lid'] . ' and t.set_id='.$product['set_id'];
						$productSet = Yii::app ()->db->createCommand ( $sql )->queryAll ();
						if(!empty($productSet)){
							$orderProduct [$k] ['set_name'] = $productSet[0]['set_name'];
							$orderProduct [$k] ['set_price'] = $product['set_price'] + $productSet[0]['set_price'];
							$orderProduct [$k] ['set_detail'] = $productSet;
						}
					}
				}
				$order ['nb_order_product'] = $orderProduct;
				$sql = 'select * from nb_order_pay where order_id=' . $result ['lid'];
				$orderPay = Yii::app ()->db->createCommand ( $sql )->queryAll ();
				$order ['nb_order_pay'] = $orderPay;
				$sql = 'select t.*,t1.name from nb_order_taste t,nb_taste t1 where t.taste_id=t1.lid and t.order_id=' . $result ['lid'] . ' and t.dpid='.$dpid.' and t.is_order=1 and t.delete_flag=0';
				$orderTaste = Yii::app ()->db->createCommand ( $sql )->queryAll ();
				$order ['nb_order_taste'] = $orderTaste;
				$sql = 'select * from nb_order_address where dpid='.$dpid.' and order_lid=' . $result ['lid'].' and delete_flag=0';
				$orderAddress = Yii::app ()->db->createCommand ( $sql )->queryAll ();
				$order ['nb_order_address'] = $orderAddress;
				$sql = 'update nb_order set is_sync=0 where dpid=' . $dpid . ' and lid=' . $result ['lid'];
				Yii::app ()->db->createCommand ( $sql )->execute ();
				array_push ( $data ['order'], $order );
			}
			//会员数据
			$sql = 'select * from nb_member_card where dpid=' . $dpid . ' and delete_flag=0 and is_sync<>0';
			$memberCard = Yii::app ()->db->createCommand ( $sql )->queryAll ();
			foreach ( $memberCard as $card ) {
				$sql = 'select * from nb_member_recharge where 	member_card_id='.$card['lid'].' and dpid=' . $dpid . ' and delete_flag=0 and is_sync<>0';
				$memberCardRecharge = Yii::app ()->db->createCommand ( $sql )->queryAll ();
				$card['member_recharge'] = $memberCardRecharge;
				array_push ( $data ['member_card'], $card );
				$sql = 'update nb_member_card set is_sync=0 where dpid=' . $dpid . ' and lid=' . $card ['lid'];
				Yii::app ()->db->createCommand ( $sql )->execute ();
			}
			$transaction->commit (); // 事物结束
		} catch ( Exception $e ) {
			$transaction->rollback (); // 回滚函数
			$data ['order'] = array ();
			$data ['member_card'] = array ();
		}
		return json_encode ( $data );
	}
	/**
	 * 
	 * 
	 * 用户名密码验证
	 * 
	 * 
	 */
	public static function validateUser($data) {
		$dpid = $data ['dpid'];
		$userName = $data ['user_name'];
		$passward = $data ['passward'];
		if($userName=='admin'){
			$sql = 'select * from nb_user where username="' . $userName . '" and password_hash="' . Helper::genPassword ( $passward ) . '" and delete_flag=0';
		}else{
			if($dpid > 0){
				$sql = 'select * from nb_user where dpid='.$dpid.' and username="' . $userName . '" and password_hash="' . Helper::genPassword ( $passward ) . '" and delete_flag=0';
			}else{
				$sql = 'select * from nb_user where username="' . $userName . '" and password_hash="' . Helper::genPassword ( $passward ) . '" and delete_flag=0';
			}
		}
		$result = Yii::app ()->db->createCommand ( $sql )->queryRow ();
		if ($result) {
			return json_encode ( array (
					'status' => true,
					'user_id' => $result ['lid'],
					'staff_no' => $result ['staff_no']
			) );
		} else {
			return json_encode ( array (
					'status' => false 
			) );
		}
	}
	/**
	 *
	 * 检验是否有新表结构数据
	 * 有新数据返回 更新详情到最大id
	 *
	 */
	public static function getNewPosTableData($data) {
		$dpid = $data['dpid'];
		$poscode = $data['poscode'];
		$sql = 'select * from nb_postable_sync_detail where dpid='.$dpid.' and poscode="'.$poscode.'" and delete_flag=0 order by postable_sync_id desc limit 1';
		$result = Yii::app()->db->createCommand($sql)->queryRow();
		if ($result){
			$maxPosTableId = $result['postable_sync_id'];
		}else{
			$maxPosTableId = 0;
		}
		
		$sql = 'select max(lid) as maxid from nb_postable_sync where lid > '.$maxPosTableId.' and delete_flag=0';
		$result = Yii::app()->db->createCommand($sql)->queryRow();
		
		if($result['maxid']!=null){
			$sql = 'select * from nb_postable_sync where lid > '.$maxPosTableId.' and delete_flag=0';
			$results = Yii::app()->db->createCommand($sql)->queryAll();
			
			$isSync = DataSync::getInitSync ();
			
			$se = new Sequence ( "postable_sync_detail" );
			$lid = $se->nextval ();
			$data = array (
					'lid' => $lid,
					'dpid' => $dpid,
					'create_at' => date ( 'Y-m-d H:i:s', time () ),
					'postable_sync_id' => $result['maxid'],
					'poscode' => $poscode,
					'is_sync' => $isSync
			);
			$res = Yii::app()->db->createCommand ()->insert ( 'nb_postable_sync_detail', $data );
			if($res){
				return json_encode($results);
			}
		}
		return json_encode(array());
	}
	/**
	 * 
	 * 检验是否有新数据
	 * 有新数据返回 表名
	 * 
	 */
	public static function getNewDataByTime($data) {
		$dpid = $data['dpid'];
		$syncTime = $data['sync_at'];
		$results = array();
		$diffTable = array('nb_pad_setting','nb_site_type','nb_product_icache','nb_order','nb_order_product','nb_order_pay','nb_order_address','nb_order_feedback','nb_order_taste','nb_order_retreat','nb_order_account_discount','nb_order_product_promotion','nb_close_account','nb_close_account_detail','nb_shift_detail','nb_sync_failure');
		$dataBase = new DataSyncTables ();
		$allTables = $dataBase->getAllTableName ();
		$allTable = array_diff($allTables, $diffTable);
		foreach ($allTable as $table){
			$tableName = $table;
			if($table=='nb_local_company'){
				$tableName = 'nb_company';
			}
			if($table=='nb_member_card'||$table=='nb_brand_user_level'){
				$dpid = WxCompany::getDpids($dpid);
			}
			$sql = 'select * from '.$tableName.' where dpid in ('.$dpid.') and (create_at >="'.$syncTime.'" or update_at >="'.$syncTime.'") and is_sync<>0';
			$result = Yii::app ()->db->createCommand ( $sql )->queryRow ();
			if($result){
				array_push($results,$table);
			}
		}
		return json_encode($results);
	}
	/**
	 * 
	 * 
	 * 订单操作
	 * 
	 * 
	 */
	public static function operateOrder($data) {
		$syncLid = 0;
		if(isset($data ['sync_lid'])){
			$syncLid = $data ['sync_lid'];
		}
		$dpid = $data ['dpid'];
		$orderData = $data ['data'];
		$obj = json_decode ( $orderData );
		if (isset ( $data ['is_pos'] ) && $data ['is_pos'] == 1) {
			$isSync = 0;
		} else {
			$isSync = DataSync::getInitSync ();
		}
		
		$orderInfo = $obj->order_info;
		$orderProduct = $obj->order_product;
		$orderPay = $obj->order_pay;
		
		if (isset ( $obj->order_taste )) {
			$orderTaste = $obj->order_taste;
		} else {
			$orderTaste = array ();
		}
		
		if (isset ( $obj->order_discount )) {
			$orderDiscount = $obj->order_discount;
		} else {
			$orderDiscount = array ();
		}
		if (isset ( $obj->order_address )) {
			$orderAddress = $obj->order_address;
		} else {
			$orderAddress = array ();
		}
		if (isset ( $obj->member_points )) {
			$memberPoints = $obj->member_points;
		} else {
			$memberPoints = array ();
		}
		
		$accountNo = $orderInfo->account_no;
		$createAt = $orderInfo->creat_at;
		
		$time = time ();
		$se = new Sequence ( "order" );
		$orderId = $se->nextval ();
		
		$transaction = Yii::app ()->db->beginTransaction ();
		try {
			$sql = 'select * from nb_order where dpid='.$dpid.' and create_at="'.$createAt.'" and account_no="'.$accountNo.'"';
			$orderModel = Yii::app ()->db->createCommand ($sql)->queryRow();
			if($orderModel){
				$msg = json_encode ( array (
						'status' => true,
						'orderId' => $orderModel['lid'],
						'syncLid' => $syncLid,
						'content' => $orderData
				) );
				return $msg;
			}
			
			$insertOrderArr = array (
					'lid' => $orderId,
					'dpid' => $dpid,
					'create_at' => $createAt,
					'update_at' => date ( 'Y-m-d H:i:s', $time ),
					'account_no' => $accountNo,
					'classes' => $orderInfo->classes,
					'username' => $orderInfo->username,
					'user_id' => '0',
					'site_id' => $orderInfo->site_id,
					'is_temp' => $orderInfo->is_temp,
					'number' => $orderInfo->number,
					'order_status' => $orderInfo->order_status,
					'takeout_typeid' => isset($orderInfo->takeout_typeid) ? $orderInfo->takeout_typeid : $orderInfo->takeout_typeid,
					'order_type' => $orderInfo->order_type,
					'should_total' => $orderInfo->should_total,
					'reality_total' => isset($orderInfo->reality_total) ? $orderInfo->reality_total : $orderInfo->should_total,
					'callno' => isset($orderInfo->callno) ? $orderInfo->callno : $orderInfo->callno,
					'taste_memo' => isset ( $orderInfo->taste_memo ) ? $orderInfo->taste_memo : '',
					'is_sync' => $isSync 
			);
			$result = Yii::app ()->db->createCommand ()->insert ( 'nb_order', $insertOrderArr );
			
			foreach ( $orderProduct as $product ) {
				$se = new Sequence ( "order_product" );
				$orderProductId = $se->nextval ();
				$orderProductData = array (
						'lid' => $orderProductId,
						'dpid' => $dpid,
						'create_at' => $createAt,
						'update_at' => date ( 'Y-m-d H:i:s', $time ),
						'order_id' => $orderId,
						'set_id' => $product->set_id,
						'product_id' => $product->product_id,
						'product_name' => $product->product_name,
						'product_pic' => '',
						'price' => $product->price,
						'original_price' => $product->original_price,
						'amount' => $product->amount,
						'zhiamount' => isset($product->zhiamount)?$product->zhiamount:1,
						'product_order_status' => 2,
						'is_sync' => $isSync 
				);
				Yii::app ()->db->createCommand ()->insert ( 'nb_order_product', $orderProductData );
				
				//产品口味
				$productTasteArr = array(); // 口味id 组成的数组
				if(isset($product->product_taste)){
					$productTastes = $product->product_taste;
					foreach ($productTastes as $taste){
						$se = new Sequence ( "order_taste" );
						$orderTasteId = $se->nextval ();
						$orderTasteData = array (
								'lid' => $orderTasteId,
								'dpid' => $dpid,
								'create_at' => $createAt,
								'update_at' => date ( 'Y-m-d H:i:s', $time ),
								'taste_id' => $taste->taste_id,
								'order_id' => $orderProductId,
								'is_order' => 0,
								'is_sync' => $isSync
						);
						Yii::app ()->db->createCommand ()->insert ( 'nb_order_taste', $orderTasteData );
						array_push($productTasteArr, $taste->taste_id);
					}
				}
				//产品普通优惠
				if(isset($product->product_promotion)){
					$productPromotions = $product->product_promotion;
					foreach ($productPromotions as $promotion){
						$se = new Sequence ( "order_product_promotion" );
						$orderPromotionId = $se->nextval ();
						$orderPromotionData = array (
								'lid' => $orderPromotionId,
								'dpid' => $dpid,
								'create_at' => $createAt,
								'update_at' => date ( 'Y-m-d H:i:s', $time ),
								'order_id' => $orderId,
								'order_product_id' => $orderProductId,
								'account_no' => $accountNo,
								'promotion_type' => $promotion->promotion_type,
								'promotion_id' => $promotion->promotion_id,
								'promotion_money' => $promotion->promotion_money,
								'is_sync' => $isSync
						);
						Yii::app ()->db->createCommand ()->insert ( 'nb_order_product_promotion', $orderPromotionData );
					}
				}
				
				//减库存
				$productItem = WxProduct::getProduct($product->product_id, $dpid);
				if($productItem['store_number']>0){
					if($productItem['store_number'] < $product->amount){
						$sql = 'update nb_product set store_number = 0,is_sync='.$isSync.' where lid='.$product->product_id.' and dpid='.$dpid.' and delete_flag=0';
					}else{
						$sql = 'update nb_product set store_number =  store_number-'.$product->amount.',is_sync='.$isSync.' where lid='.$product->product_id.' and dpid='.$dpid.' and delete_flag=0';
					}
					Yii::app()->db->createCommand($sql)->execute();
				}
				if($isSync==0){
					// 消耗原材料库存
					$productBoms = self::getBom($dpid, $product->product_id, $productTasteArr);
					if(!empty($productBoms)){
						foreach ($productBoms as $bom){
							$stock = $bom['number']*$product->amount;
							self::updateMaterialStock($dpid,$bom['material_id'],$stock);
						}
					}
				}
			}
			// 支付方式
			foreach ( $orderPay as $pay ) {
				$se = new Sequence ( "order_pay" );
				$orderPayId = $se->nextval ();
				$orderPayData = array (
						'lid' => $orderPayId,
						'dpid' => $dpid,
						'create_at' => $createAt,
						'update_at' => date ( 'Y-m-d H:i:s', $time ),
						'order_id' => $orderId,
						'account_no' => $accountNo,
						'pay_amount' => $pay->pay_amount,
						'paytype' => $pay->paytype,
						'payment_method_id' => $pay->payment_method_id,
						'paytype_id' => $pay->paytype_id,
						'remark' => isset($pay->remark)?$pay->remark:'',
						'is_sync' => $isSync
				);
				Yii::app ()->db->createCommand ()->insert ( 'nb_order_pay', $orderPayData );
			}
			// 订单口味
			if(!empty($orderTaste)){
				foreach ( $orderTaste as $taste ) {
					$se = new Sequence ( "order_taste" );
					$orderTasteId = $se->nextval ();
					$orderTasteData = array (
							'lid' => $orderTasteId,
							'dpid' => $dpid,
							'create_at' => $createAt,
							'update_at' => date ( 'Y-m-d H:i:s', $time ),
							'taste_id' => $taste->taste_id,
							'order_id' => $orderId,
							'is_order' => 1,
							'is_sync' => $isSync
					);
					Yii::app ()->db->createCommand ()->insert ( 'nb_order_taste', $orderTasteData );
				}
			}
			
			// 订单优惠
			if(!empty($orderDiscount)){
				foreach ( $orderDiscount as $discount ) {
					$se = new Sequence ( "order_account_discount" );
					$orderDiscountId = $se->nextval ();
					$orderDiscountData = array (
							'lid' => $orderDiscountId,
							'dpid' => $dpid,
							'create_at' => $createAt,
							'update_at' => date ( 'Y-m-d H:i:s', $time ),
							'order_id' => $orderId,
							'account_no' => $accountNo,
							'discount_type' => $discount->discount_type,
							'discount_id' => $discount->discount_id,
							'discount_money' => $discount->discount_money,
							'is_sync' => $isSync
					);
					Yii::app ()->db->createCommand ()->insert ( 'nb_order_account_discount', $orderDiscountData );
				}
			}
			
			//订单地址
			if(!empty($orderAddress)){
				foreach ( $orderAddress as $address ) {
					$se = new Sequence ( "order_address" );
					$orderAddressId = $se->nextval ();
					$orderAddressData = array (
							'lid' => $orderAddressId,
							'dpid' => $dpid,
							'create_at' =>$createAt,
							'update_at' => date ( 'Y-m-d H:i:s', $time ),
							'order_lid' => $orderId,
							'consignee' => $address->consignee,
							'street' => $address->street,
							'mobile' => $address->mobile,
							'tel' => $address->tel,
							'is_sync' => $isSync
					);
					Yii::app ()->db->createCommand ()->insert ( 'nb_order_address', $orderAddressData );
				}
			}
			
			//会员卡积分
			if(!empty($memberPoints)){
				$se = new Sequence ( "member_points" );
				$memberPointId = $se->nextval ();
				$memberPointData = array (
						'lid' => $memberPointId,
						'dpid' => $dpid,
						'create_at' => $createAt,
						'update_at' => date ( 'Y-m-d H:i:s', $time ),
						'member_card_rfid' => $memberPoints->member_card_rfid,
						'order_id' => $orderId,
						'points' => $memberPoints->receive_points,
						'is_sync' => $isSync
				);
				Yii::app ()->db->createCommand ()->insert ( 'nb_member_points', $memberPointData );
				$sql = 'update nb_member_card set all_points = all_points+'.$memberPoints->receive_points.' where rfid='.$memberPoints->member_card_rfid.' and dpid='.$dpid;
				Yii::app ()->db->createCommand ($sql)->execute();
			}
			$transaction->commit ();
			$msg = json_encode ( array (
					'status' => true,
					'orderId' => $orderId,
					'syncLid' => $syncLid,
					'content' => $orderData
			) );
		} catch ( Exception $e ) {
			$transaction->rollback ();
			$msg = json_encode ( array (
					'status' => false,
					'orderId' => '' 
			) );
		}
		return $msg;
	}
	/**
	 * 
	 * 退单
	 * 
	 */
	public static function retreatOrder($data) {
		$time = time();
		$syncLid = 0;
		if(isset($data ['sync_lid'])){
			$syncLid = $data ['sync_lid'];
		}
		$dpid = $data ['dpid'];
		$accountNo = $data ['account'];
		$retreatId = $data ['retreatid'];
		$retreatprice = $data ['retreatprice'];
		$adminId = $data ['admin_id'];
		$username =  $data ['username'];
		$pruductIds = split('==',$data ['pruductids']);
		$memo = $data ['memo'];
		$content = '';
		if(isset($data ['data'])){
			$content = $data ['data'];
		}
		if(isset($adminId) && $adminId != "" ){
			$admin = WxAdminUser::get($dpid, $adminId);
			if(!$admin){
				$msg = array('status'=>false,'msg'=>'不存在该服务员');
				echo json_encode($msg);
				exit;
			}
		}else{
			$msg = array('status'=>false,'msg'=>'不存在该服务员');
			echo json_encode($msg);
			exit;
		}
		$transaction = Yii::app ()->db->beginTransaction ();
		try {
			$sql = 'select * from nb_order where dpid='.$dpid.' and account_no="'.$accountNo.'" and order_status in (3,4)';
			$order =  Yii::app ()->db->createCommand ($sql)->queryRow();
			if($order){
				$orderId = $order['lid'];
				foreach ($pruductIds as $productId){
					$productArr = split(',', $productId);
				    if($productArr[0] > 0){
				    	$sql = 'select * from nb_order_product where order_id='.$orderId.' and dpid='.$dpid.' and set_id='.$productArr[0];
				    }else{
				    	$sql = 'select * from nb_order_product where order_id='.$orderId.' and dpid='.$dpid.' and set_id='.$productArr[0].' and product_id='.$productArr[1].' and price='.$productArr[3];
				    }
					$orderProducts =  Yii::app ()->db->createCommand ($sql)->queryAll();
					foreach ($orderProducts as $orderproduct){
						$orderProductDetailId = $orderproduct['lid'];
						
						$sql = 'update nb_order_product set is_retreat=1 where lid='.$orderProductDetailId.' and dpid='.$dpid;
						Yii::app ()->db->createCommand ($sql)->execute();
						
						$se = new Sequence ( "order_retreat" );
						$orderRetreatId = $se->nextval ();
						$orderRetreatData = array (
								'lid' => $orderRetreatId,
								'dpid' => $dpid,
								'create_at' => date ( 'Y-m-d H:i:s', $time ),
								'update_at' => date ( 'Y-m-d H:i:s', $time ),
								'retreat_id' => $retreatId,
								'order_detail_id' => $orderProductDetailId,
								'retreat_memo' => $memo,
								'username' => $username,
								'retreat_amount' => $productArr[2],
								'is_sync' => 0
						);
						Yii::app ()->db->createCommand ()->insert ( 'nb_order_retreat', $orderRetreatData );
					}
				}
				
				$sql = 'select sum(pay_amount) as total from nb_order_pay where order_id='.$orderId.' and dpid='.$dpid.' and pay_amount < 0 and paytype < 11';
				$orderPay =  Yii::app ()->db->createCommand ($sql)->queryRow();
				if($orderPay && empty($orderPay['total'])){
					if($order['should_total'] + $orderPay['total'] + $retreatprice < 0){
						throw new Exception('退款金额超过总金额');
					}
				}
				
				$sql = 'select * from nb_order_pay where order_id='.$orderId.' and dpid='.$dpid.' and pay_amount > 0 and paytype < 11';
				$orderPayArr =  Yii::app ()->db->createCommand ($sql)->queryAll();
				
				$allOrderRetreat = false; // 是否整单退
				if($order['should_total'] == -$retreatprice){
					// 整单全退
					$allOrderRetreat = true;
				}
				
				foreach ($orderPayArr as $pay){
					if($allOrderRetreat){
						$refund_fee = $pay['pay_amount'];
					}else{
						$refund_fee = -$retreatprice;
					}
					if($pay['paytype']==1){
						// 微信支付
						$url = Yii::app()->request->hostInfo.'/wymenuv2/weixin/refund?companyId='.$dpid.'&admin_id='.$adminId.'&out_trade_no='.$pay['remark'].'&total_fee='.$pay['pay_amount'].'&refund_fee='.$refund_fee;
						$result = Curl::httpsRequest($url);
						$resArr = json_decode($result);
						if(!$resArr['status']){
							throw new Exception('微信退款失败');
						}
					}elseif($pay['paytype']==2){
						// 支付宝支付
						$url = Yii::app()->request->hostInfo.'/wymenuv2/alipay/refund?companyId='.$dpid.'&admin_id='.$adminId.'&out_trade_no='.$pay['remark'].'&refund_fee='.$refund_fee;
						$result = Curl::httpsRequest($url);
						$resArr = json_decode($result);
						if(!$resArr['status']){
							throw new Exception('支付宝退款失败');
						}	
					}elseif($pay['paytype']==4){
						// 会员卡支付
						$url = Yii::app()->request->hostInfo.'/wymenuv2/admin/dataAppSync/refundMemberCard';
						$data = array(
								'dpid'=>$dpid,
								'rfid'=>$pay['paytype_id'],
								'admin_id'=>$adminId,
								'password'=>'',
								'refund_price'=>$refund_fee,
								);
						$result = Curl::httpsRequest($url,$data);
						$resArr = json_decode($result);
						if(!$resArr['status']){
							throw new Exception('会员卡退款失败');
						}
					}
					$se = new Sequence ( "order_pay" );
					$orderPayId = $se->nextval ();
					$orderPayData = array (
							'lid' => $orderPayId,
							'dpid' => $dpid,
							'create_at' => date ( 'Y-m-d H:i:s', $time ),
							'update_at' => date ( 'Y-m-d H:i:s', $time ),
							'order_id' => $orderId,
							'account_no' => $accountNo,
							'pay_amount' => -$refund_fee,
							'paytype' => $pay['paytype'],
							'payment_method_id' => $pay['payment_method_id'],
							'paytype_id' => $pay['paytype_id'],
							'is_sync' => 0
					);
					var_dump($orderPayData);exit;
					Yii::app ()->db->createCommand ()->insert ( 'nb_order_pay', $orderPayData );
				}
				
				$transaction->commit ();
				$msg = json_encode ( array (
						'status' => true,
						'syncLid' => $syncLid,
						'content' => $content
				) );
			}else{
				throw new Exception('订单不存在');
			}
		} catch ( Exception $e ) {
			$transaction->rollback ();
			$msg = json_encode ( array (
					'status' => false,
					'msg'=>$e->getMessage()
			) );
		}
		return $msg;
	}
	/**
	 * 
	 * 
	 * 
	 * 增加会员卡
	 * 
	 * 
	 * 
	 */
	public static function addMemberCard($data) {
		$syncLid = 0;
		if(isset($data ['sync_lid'])){
			$syncLid = $data ['sync_lid'];
		}
		$dpid = $data ['dpid'];
		$orderData = $data ['data'];
		if (isset ( $data ['is_pos'] ) && $data ['is_pos'] == 1) {
			$isSync = 0;
		} else {
			$isSync = DataSync::getInitSync ();
		}
		$obj = json_decode ( $orderData );
		
		$time = time ();
		$sql = 'select * from nb_member_card where rfid="'.$obj->rfid.'" and delete_flag=0';
		$memberCard = Yii::app ()->db->createCommand ($sql)->queryRow();
		if($memberCard){
			$msg = json_encode ( array (
					'status' => false,
			) );
			return $msg;
		}
		$se = new Sequence ( "member_card" );
		$memberCardId = $se->nextval ();
		$inserMemberCardrArr = array (
				'lid' => $memberCardId,
				'dpid' => $dpid,
				'create_at' => date ( 'Y-m-d H:i:s', $time ),
				'update_at' => date ( 'Y-m-d H:i:s', $time ),
				'selfcode' => $obj->selfcode,
				'rfid' => $obj->rfid,
				'level_id' => $obj->level_id,
				'name' => $obj->name,
				'mobile' => $obj->mobile,
				'sex' => $obj->sex,
				'ages' => $obj->ages,
				'birthday' => $obj->birthday,
				'enable_date' => $obj->enable_date,
				'is_sync' => $isSync 
		);
		$result = Yii::app ()->db->createCommand ()->insert ( 'nb_member_card', $inserMemberCardrArr );
		if ($result) {
			$msg = json_encode ( array (
						'status' => true,
						'syncLid' => $syncLid,
						'content' => $orderData
				) );
		} else {
			$msg = json_encode ( array (
					'status' => false,
				) );
		}
		return $msg;
	}
	/**
	 * 
	 * 会员卡余额
	 * 
	 */
	public static function getMemberCardYue($data) {
		$dpid = $data ['dpid'];
		$rfid = $data ['rfid'];
		
		$dpid = WxCompany::getDpids($dpid);
		$sql = 'select * from nb_member_card where dpid in (' . $dpid . ') and rfid=' . $rfid . ' and delete_flag=0';
		$reslut = Yii::app ()->db->createCommand ( $sql )->queryRow ();
		if (! $reslut) {
			return '0.00';
		}else{
			return $reslut['all_money'];
		}
	}
	/**
	 * 
	 * 
	 * 会员卡支付
	 * 
	 * 
	 */
	public static function payMemberCard($data) {
		$dpid = $data ['dpid'];
		$rfid = $data ['rfid'];
		$adminId = $data ['admin_id'];
		$password = $data ['password'];
		$payPrice = $data ['pay_price'];
		
		$sql = 'select * from nb_user where dpid=' . $dpid . ' and lid=' . $adminId . ' and delete_flag=0';
		$reslut = Yii::app ()->db->createCommand ( $sql )->queryRow ();
		if (! $reslut) {
			return json_encode ( array (
					'status' => false,
					'msg' => '不存在该管理员'
			) );
		}
		$dpid = WxCompany::getDpids($dpid);
		
		$sql = 'select * from nb_member_card where dpid in (' . $dpid . ') and rfid=' . $rfid . ' and delete_flag=0';
		$reslut = Yii::app ()->db->createCommand ( $sql )->queryRow ();
		if (! $reslut) {
			return json_encode ( array (
					'status' => false,
					'msg' => '不存在该会员信息' 
			) );
		}
		
		if ($payPrice > $reslut ['all_money']) {
			return json_encode ( array (
					'status' => false,
					'msg' => '余额不足' 
			) );
		}
		
		$sql = 'update nb_member_card set all_money=all_money-' . $payPrice . ' where dpid in (' . $dpid . ') and lid=' . $reslut ['lid'] . ' and rfid=' . $rfid;
		$reslut = Yii::app ()->db->createCommand ( $sql )->execute ();
		if ($reslut) {
			return json_encode ( array (
					'status' => true,
			) );
		} else {
			return json_encode ( array (
					'status' => false,
					'msg' => '支付失败'
			) );
		}
	}
	/**
	 * 
	 * 会员卡 退款
	 * 
	 */
	public static function refundMemberCard($data) {
		$dpid = $data ['dpid'];
		$rfid = $data ['rfid'];
		$adminId = $data ['admin_id'];
		$password = $data ['password'];
		$refundPrice = $data ['refund_price'];
		
		$sql = 'select * from nb_user where dpid=' . $dpid . ' and lid=' . $adminId . ' and delete_flag=0';
		$reslut = Yii::app ()->db->createCommand ( $sql )->queryRow ();
		if (! $reslut) {
			return json_encode ( array (
					'status' => false,
					'msg' => '不存在该管理员'
			) );
		}
		
		$dpid = WxCompany::getDpids($dpid);
		
		$sql = 'select * from nb_member_card where dpid in (' . $dpid . ') and rfid=' . $rfid . ' and delete_flag=0';
		$reslut = Yii::app ()->db->createCommand ( $sql )->queryRow ();
		if (! $reslut) {
			return json_encode ( array (
					'status' => false,
					'msg' => '不存在该会员信息'
			) );
		}
		
		$sql = 'update nb_member_card set all_money=all_money+' . $refundPrice . ' where dpid in (' . $dpid . ') and lid=' . $reslut ['lid'] . ' and rfid=' . $rfid;
		$reslut = Yii::app ()->db->createCommand ( $sql )->execute ();
		if ($reslut) {
			return json_encode ( array (
					'status' => true,
			) );
		} else {
			return json_encode ( array (
					'status' => false,
					'msg' => '退款失败'
			) );
		}
	}
	/**
	 * 
	 * 
	 * 
	 * 获取订单
	 * 
	 * 
	 * 
	 */
	public static function getOrderStaus($dpid, $orderId) {
		$order = WxOrder::getOrder ( $orderId, $dpid );
		if ($order) {
			return json_encode ( array (
					'status' => true,
					'order_status' => $order ['order_status'] 
			) );
		} else {
			return json_encode ( array (
					'status' => false,
					'order_status' => '' 
			) );
		}
	}
	/**
	 * 
	 * 
	 * 
	 * 日结订单
	 * 
	 * 
	 * 
	 */
	public static function operateCloseAccount($dpid, $userId) {
		$time = time ();
		$sql = 'select lid from nb_order where dpid=' . $dpid . ' and order_status in(3,4)';
		$lids = Yii::app ()->db->createCommand ( $sql )->queryColumn ();
		$lidStr = join ( ',', $lids );
		
		$sql = 'select sum(pay_amount) as pay_amount,paytype,payment_method_id,paytype_id from nb_order_pay where order_id in (' . $lidStr . ') group by paytype';
		$results = Yii::app ()->db->createCommand ( $sql )->queryAll ();
		
		$totalMoney = 0;
		$transaction = Yii::app ()->db->beginTransaction ();
		try {
			$se = new Sequence ( "close_account" );
			$closeAccountId = $se->nextval ();
			
			foreach ( $results as $result ) {
				$se = new Sequence ( "close_account_detail" );
				$closeAccountDetailId = $se->nextval ();
				$closeAccountDetailArr = array (
						'lid' => $closeAccountDetailId,
						'dpid' => $dpid,
						'create_at' => date ( 'Y-m-d H:i:s', $time ),
						'update_at' => date ( 'Y-m-d H:i:s', $time ),
						'close_account_id' => $closeAccountId,
						'paytype' => $result ['paytype'],
						'payment_method_id' => $result ['payment_method_id'],
						'all_money' => $result ['pay_amount'],
						'is_sync' => DataSync::getInitSync () 
				);
				Yii::app ()->db->createCommand ()->insert ( 'nb_close_account_detail', $closeAccountDetailArr );
				$totalMoney += $result ['pay_amount'];
			}
			$closeAccountArr = array (
					'lid' => $closeAccountId,
					'dpid' => $dpid,
					'create_at' => date ( 'Y-m-d H:i:s', $time ),
					'update_at' => date ( 'Y-m-d H:i:s', $time ),
					'user_id' => $userId,
					'close_day' => date ( 'Y-m-d', $time ),
					'all_money' => $totalMoney,
					'is_sync' => DataSync::getInitSync () 
			);
			Yii::app ()->db->createCommand ()->insert ( 'nb_close_account', $closeAccountArr );
			
			$sql = 'update nb_order set order_status=8 where lid in(' . $lidStr . ')';
			Yii::app ()->db->createCommand ( $sql )->execute ();
			
			$transaction->commit ();
			$msg = json_encode ( array (
					'status' => true,
					'closeAccountId' => $closeAccountId 
			) );
		} catch ( Exception $e ) {
			$transaction->rollback ();
			$msg = json_encode ( array (
					'status' => false,
					'closeAccountId' => '' 
			) );
		}
		return $msg;
	}
	/**
	 * 
	 * bom库存对应信息
	 * 
	 * 
	 */
	public static function getBom($dpid, $productId, $tasteArr) {
		if(empty($tasteArr)){
			$sql = 'select * from nb_product_bom where dpid='.$dpid.' and product_id='.$productId.' and taste_id=0 and delete_flag=0';
		}else{
			$tasteStr = join(',', $tasteArr);
			$sql = 'select * from nb_product_bom where dpid='.$dpid.' and product_id='.$productId.' and taste_id=0 and delete_flag=0'.
					' union select * from nb_product_bom where dpid='.$dpid.' and product_id='.$productId.' and taste_id in('.$tasteStr.') and delete_flag=0';
		}
		
		$results = Yii::app ()->db->createCommand ( $sql )->queryAll ();
		return $results;
	}
	/**
	 * 
	 * 
	 * 更新库存
	 * 
	 * 
	 * 
	 */
	public static function updateMaterialStock($dpid, $materialId, $stock) {
		$temStock = $stock;
		$time = time ();
		$sql = 'select * from nb_product_material_stock where dpid='.$dpid.' and  material_id='.$materialId.' and delete_flag=0 order by create_at asc';
		$materialStocks = Yii::app ()->db->createCommand ( $sql )->queryAll ();
		if(!empty($materialStocks)){
			$count = count($materialStocks);
			foreach ($materialStocks as $k=>$materialStock){
				$realityStock = $materialStock['stock'];
				if($realityStock == 0 && $k+1 != $count){
					continue;
				}
				$temStock = $temStock - $realityStock;
				if($temStock > 0){
					if($k+1 == $count){
						$sql = 'update nb_product_material_stock set stock = stock - '.($temStock + $realityStock).' where lid='.$materialStock['lid'].' and dpid='.$dpid.' and delete_flag=0';
						Yii::app ()->db->createCommand ( $sql )->execute ();
					}else{
						$sql = 'update nb_product_material_stock set stock= 0 where lid='.$materialStock['lid'].' and dpid='.$dpid.' and delete_flag=0';
						Yii::app ()->db->createCommand ( $sql )->execute ();
					}
				}else{
					$sql = 'update nb_product_material_stock set stock = stock - '.($temStock + $realityStock).' where lid='.$materialStock['lid'].' and dpid='.$dpid.' and delete_flag=0';
					Yii::app ()->db->createCommand ( $sql )->execute ();
					break;
				}
			}
			$se = new Sequence ( "material_stock_log" );
			$materialStockLogId = $se->nextval ();
			$materialStockLog = array (
					'lid' => $materialStockLogId,
					'dpid' => $dpid,
					'create_at' => date ( 'Y-m-d H:i:s', $time ),
					'update_at' => date ( 'Y-m-d H:i:s', $time ),
					'material_id' => $materialId,
					'type' => 1,
					'stock_num' => $stock,
					'resean' => '正常消耗',
					'is_sync' => DataSync::getInitSync ()
			);
			Yii::app ()->db->createCommand ()->insert ( 'nb_material_stock_log', $materialStockLog );
		}
	}
	
	/**
	 * 
	 * 获取双屏信息
	 * 
	 */
	public static function getDoubleScreen($dpid) {
		$sql = 'select t.lid,t.dpid,t.url as main_picture,if(t.type=0,3,4) as is_set from nb_double_screen_detail t,nb_double_screen t1 where t.double_screen_id=t1.lid and t.dpid=t1.dpid and t.dpid='.$dpid.' and t1.is_able=1 and t.delete_flag=0 and t1.delete_flag=0';
		$results = Yii::app ()->db->createCommand ( $sql )->queryAll ();
		return json_encode($results);
	}
}

