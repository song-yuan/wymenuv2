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
	public static function getDataSyncPosInfor($code,$mac) {
		if($code){
			$sql = 'select * from nb_pad_setting where pad_code="'.$code.'" and delete_flag=0';
			$result = Yii::app ()->db->createCommand ( $sql )->queryRow ();
			if($result){
				$padSettingId = $result['lid'];
				$dpid = $result['dpid'];
				$sql = 'select t.* from nb_pad_setting_detail t,nb_pad_setting t1 where t.pad_setting_id=t1.lid and t.dpid='.$dpid.' and t.pad_setting_id='.$padSettingId.' and t.delete_flag=0 and t1.delete_flag=0';
				$resDetail = Yii::app ()->db->createCommand ( $sql )->queryRow ();
				if($resDetail && $resDetail['content']!=$mac){
					$msg = array('status'=>false,'msg'=>'该序列号已被使用');
				}else{
					if(!$resDetail){
						$sql = 'select * from nb_pad_setting_detail t,nb_pad_setting t1 where t.pad_setting_id=t1.lid and t.dpid='.$dpid.' and t.content="'.$mac.'" and t1.delete_flag=0';
						$resDetail = Yii::app ()->db->createCommand ( $sql )->queryRow ();
						if($resDetail){
							$msg = array('status'=>false,'msg'=>'该收银机已绑定其他序列号');
							return $msg;
						}
					}
					$sql = 'select * from nb_pad_setting_status where dpid='.$dpid.' and delete_flag=0';
					$padSettingStatus = Yii::app ()->db->createCommand ( $sql )->queryRow ();
					if($padSettingStatus){
						if($padSettingStatus['use_status']==0){
							$sql = 'update nb_pad_setting_status set update_at="'.date ( 'Y-m-d H:i:s', time () ).'",use_status=1,pad_no=pad_no+1 where lid='.$padSettingStatus['lid'].' and dpid='.$dpid;
							$result = Yii::app ()->db->createCommand ( $sql )->execute ();
							if(!$result){
								$msg = array('status'=>false,'msg'=>'使用失败,请重新使用');
								return $msg;
							}
						}
					}else{
						$se = new Sequence ( "pad_setting_status" );
						$lid = $se->nextval ();
						$data = array (
								'lid' => $lid,
								'dpid' => $dpid,
								'create_at' => date ( 'Y-m-d H:i:s', time () ),
								'update_at' => date ( 'Y-m-d H:i:s', time () ),
								'pad_setting_id' => $padSettingId,
								'status' => '0',
								'use_status'=>1,
								'is_sync' => $isSync
						);
						$res = Yii::app()->db->createCommand ()->insert ( 'nb_pad_setting_status', $data );
					}
					
					$isSync = DataSync::getInitSync ();
					$se = new Sequence ( "pad_setting_detail" );
					$lid = $se->nextval ();
					$data = array (
							'lid' => $lid,
							'dpid' => $dpid,
							'create_at' => date ( 'Y-m-d H:i:s', time () ),
							'update_at' => date ( 'Y-m-d H:i:s', time () ),
							'pad_setting_id' => $padSettingId,
							'content' => $mac,
							'is_sync' => $isSync
					);
					$res = Yii::app()->db->createCommand ()->insert ( 'nb_pad_setting_detail', $data );
					if($res){
						$msg = array('status'=>true,'msg'=>$result);
					}else{
						$msg = array('status'=>false,'msg'=>'请重新操作');
					}
				}
			}else{
				$msg = array('status'=>false,'msg'=>'序列号不存在');
			}
		}else{
			$msg = array('status'=>false,'msg'=>'请输入序列号');
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
	public static function getDataSyncData($data) {
		$dataBase = new DataSyncTableData ($data);
		$tableData = $dataBase->getInitData ();
		return array_merge(array('status' => true),$tableData);
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
			$sql = 'select * from nb_order where dpid=' . $dpid . ' and (order_status=3 or order_status=4) and is_sync<>0';
			$results = Yii::app ()->db->createCommand ( $sql )->queryAll ();
			foreach ( $results as $result ) {
				$order = array ();
				$order ['nb_order'] = $result;
				$sql = 'select *,"" as set_name,sum(price) as set_price from nb_order_product where order_id=' . $result ['lid'] . ' and dpid='.$dpid.' and set_id > 0 and delete_flag=0 group by set_id ,main_id'.
					   ' union select *,"" as set_name,"0.00" as set_price from nb_order_product where order_id=' . $result ['lid'] . ' and dpid='.$dpid.' and set_id = 0 and delete_flag=0';
				$orderProduct = Yii::app ()->db->createCommand ( $sql )->queryAll ();
				foreach ( $orderProduct as $k => $product ) {
					$sql = 'select create_at,taste_id,order_id,is_order,taste_name as name from nb_order_taste where order_id=' . $product ['lid'] . ' and dpid='.$dpid.' and is_order=0 and delete_flag=0';
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
				}
				$order ['nb_order_product'] = $orderProduct;
				$sql = 'select * from nb_order_pay where order_id=' . $result ['lid'];
				$orderPay = Yii::app ()->db->createCommand ( $sql )->queryAll ();
				$order ['nb_order_pay'] = $orderPay;
				$sql = 'select create_at,taste_id,order_id,is_order,taste_name as name from nb_order_taste where order_id=' . $result ['lid'] . ' and dpid='.$dpid.' and is_order=1 and delete_flag=0';
				$orderTaste = Yii::app ()->db->createCommand ( $sql )->queryAll ();
				$order ['nb_order_taste'] = $orderTaste;
				$sql = 'select * from nb_order_address where dpid='.$dpid.' and order_lid=' . $result ['lid'].' and delete_flag=0';
				$orderAddress = Yii::app ()->db->createCommand ( $sql )->queryAll ();
				$order ['nb_order_address'] = $orderAddress;
				$sql = 'select * from nb_order_account_discount where dpid='.$dpid.' and order_id='.$result ['lid'].' and delete_flag=0';
				$orderDiscount = Yii::app ()->db->createCommand ( $sql )->queryAll ();
				$order ['nb_order_account_discount'] = $orderDiscount;
				$sql = 'update nb_order set is_sync=0 where dpid=' . $dpid . ' and lid=' . $result ['lid'];
				$res = Yii::app ()->db->createCommand ( $sql )->execute ();
				if($res){
					array_push ( $data ['order'], $order );
				}
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
			echo $e->getMessage();exit;
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
		$code = isset($data['code'])?$data['code']:'';
		$syncTime = $data['sync_at'];
		$results = array();
		$diffTable = array('nb_product_icache','nb_order','nb_order_product','nb_order_pay','nb_order_address','nb_order_feedback','nb_order_taste','nb_order_retreat','nb_order_account_discount','nb_order_product_promotion','nb_close_account','nb_close_account_detail','nb_shift_detail','nb_sync_failure');
		$dataBase = new DataSyncTables ();
		$allTables = $dataBase->getAllTableName ();
		$allTable = array_diff($allTables, $diffTable);
		foreach ($allTable as $table){
			$tableName = $table;
			if($table=='nb_local_company'){
				$tableName = 'nb_company';
			}
			$dpid = $data['dpid'];
			if($tableName=='nb_company_setting'){
				$dpid = WxCompany::getCompanyDpid($dpid);
			}
			$sql = 'select * from '.$tableName.' where dpid in ('.$dpid.') and (create_at >="'.$syncTime.'" or update_at >="'.$syncTime.'") and is_sync<>0';
			if($tableName=='nb_pad_setting'){
				$sql .= ' and pad_code="'.$code.'"';
			}
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
		if(isset($data['sync_lid'])){
			$syncLid = $data['sync_lid'];
		}
		$dpid = $data['dpid'];
		$padSetLid = isset($data['posLid'])?$data['posLid']:0; // pad序列号对于的lid
		$orderData = $data['data'];
		$obj = json_decode( $orderData );
		if (isset ( $data['is_pos'] ) && $data['is_pos'] > 0) {
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
		
		$sql = 'select * from nb_order where dpid='.$dpid.' and create_at="'.$createAt.'" and user_id='.$padSetLid.' and account_no="'.$accountNo.'"';
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
		
		$transaction = Yii::app ()->db->beginTransaction ();
		try {
			$se = new Sequence ( "order" );
			$orderId = $se->nextval ();
			
			$insertOrderArr = array (
					'lid' => $orderId,
					'dpid' => $dpid,
					'create_at' => $createAt,
					'update_at' => date ( 'Y-m-d H:i:s', $time ),
					'account_no' => $accountNo,
					'classes' => $orderInfo->classes,
					'username' => $orderInfo->username,
					'user_id' => $padSetLid,
					'site_id' => $orderInfo->site_id,
					'is_temp' => $orderInfo->is_temp,
					'number' => $orderInfo->number,
					'order_status' => $orderInfo->order_status,
					'takeout_typeid' => isset($orderInfo->takeout_typeid) ? $orderInfo->takeout_typeid : $orderInfo->takeout_typeid,
					'order_type' => $orderInfo->order_type,
					'should_total' => $orderInfo->should_total,
					'reality_total' => isset($orderInfo->reality_total) ? $orderInfo->reality_total : $orderInfo->should_total,
					'callno' => isset($orderInfo->callno) ? $orderInfo->callno : $orderInfo->callno,
					'remark' => isset ( $orderInfo->remark ) ? $orderInfo->remark : '',
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
						'product_type'=>isset($product->product_type)?$product->product_type:'0',
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
								'taste_name' => isset($taste->taste_name)?$taste->taste_name:'',
								'is_sync' => $isSync
						);
						Yii::app ()->db->createCommand ()->insert ( 'nb_order_taste', $orderTasteData );
						if((int)$taste->taste_id > 0){
							array_push($productTasteArr, $taste->taste_id);
						}
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
								'promotion_title' => isset($promotion->promotion_title)?$promotion->promotion_title:'',
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
				// 消耗原材料库存
				$productBoms = self::getBom($dpid, $product->product_id, $productTasteArr);
				if(!empty($productBoms)){
					foreach ($productBoms as $bom){
						$stock = $bom['number']*$product->amount;
						self::updateMaterialStock($dpid,$createAt,$bom['material_id'],$stock,$orderProductId);
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
					if((int)$taste->taste_id > 0){
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
								'taste_name' => isset($taste->taste_name)?$taste->taste_name:'',
								'is_sync' => $isSync
						);
						Yii::app ()->db->createCommand ()->insert ( 'nb_order_taste', $orderTasteData );
					}
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
							'discount_title' => isset($discount->discount_title)?$discount->discount_title:'',
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
				$cardType = $memberPoints->card_type;
				$cardId = $memberPoints->member_card_rfid;
				$receivePoint = $memberPoints->receive_points;
				$endDate = date('Y',$time)+1 . '-' . date('m-d 23:59:59',$time);
				if($cardType == 0&&$cardId!=0){
					// 实体会员卡
					$se = new Sequence ( "member_points" );
					$memberPointId = $se->nextval ();
					$memberPointData = array (
							'lid' => $memberPointId,
							'dpid' => $dpid,
							'create_at' => $createAt,
							'update_at' => date ( 'Y-m-d H:i:s', $time ),
							'card_type' => $cardType,
							'card_id' => $cardId,
							'point_resource' => 0,
							'resource_id' => $orderId,
							'points' => $receivePoint,
							'remain_points' => $receivePoint,
							'end_time' => $endDate,
							'is_sync' => $isSync
					);
					Yii::app ()->db->createCommand ()->insert ( 'nb_member_points', $memberPointData );
					$sql = 'update nb_member_card set all_points = all_points+'.$memberPoints->receive_points.' where rfid="'.$memberPoints->member_card_rfid.'"';
					Yii::app ()->db->createCommand ($sql)->execute();
				}else{
					// 微信会员卡
					$user = WxBrandUser::getFromCardId($dpid, $cardId);
					if($user){
						$se = new Sequence ( "member_points" );
						$memberPointId = $se->nextval ();
						$memberPointData = array (
								'lid' => $memberPointId,
								'dpid' => $dpid,
								'create_at' => $createAt,
								'update_at' => date ( 'Y-m-d H:i:s', $time ),
								'card_type' => $cardType,
								'card_id' => $user['lid'],
								'point_resource' => 0,
								'resource_id' => $orderId,
								'points' => $receivePoint,
								'remain_points' => $receivePoint,
								'end_time' => $endDate,
								'is_sync' => $isSync
						);
						Yii::app ()->db->createCommand ()->insert ( 'nb_member_points', $memberPointData );
						$sql = 'update nb_brand_user set consume_point_history = consume_point_history+'.$receivePoint.',consume_total_money =consume_total_money+'.$orderInfo->should_total.' where lid='.$user['lid'].' and dpid='.$user['dpid'];
						Yii::app ()->db->createCommand ($sql)->execute();
					}
				}
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
			Helper::writeLog($dpid.'---'.$orderData.'---'.$e->getMessage());
			$msg = json_encode ( array (
					'status' => false,
					'msg' => $e->getMessage(),
					'orderId' => '' 
			) );
		}
		return $msg;
	}
	/**
	 * 
	 * @param unknown $data
	 * 
	 * 微信支付退款
	 * 
	 */
	public static function refundWxPay($data) {
		$now = time();
		$poscode = $data['poscode'];
		$dpid = $data['dpid'];
		$rand = rand(100,999);
		$out_refund_no = $now.'-'.$dpid.'-'.$rand;
		$paytype = isset($data['paytype'])?$data['paytype']:1;
		if(isset($data['admin_id']) && $data['admin_id'] != "" ){
			$admin_id = $data['admin_id'];
			$admin = WxAdminUser::get($dpid, $admin_id);
			if(!$admin){
				$msg = array('status'=>false);
				return json_encode($msg);
			}
		}else{
			$msg = array('status'=>false);
			return json_encode($msg);
		}
		
		if(isset($data['out_trade_no']) && $data['out_trade_no']!="" && $data['out_trade_no']!=0){
			$out_trade_no = $data['out_trade_no'];
			$total_fee = $data['total_fee'];
			$refund_fee = $data['refund_fee'];
			
			if($paytype==1){
				$microPay = MicroPayModel::get($dpid, $out_trade_no, 0);
				if(empty($microPay)||empty($microPay['transaction_id'])){
					// 不存在支付记录 则直接退款成功
					$msg = array('status'=>true, 'trade_no'=>$out_refund_no);
					return json_encode($msg);
				}
			}
			$compaychannel = WxCompany::getpaychannel($dpid);
			if($compaychannel['pay_channel']=='2'){
				$result = SqbPay::refund(array(
						'device_id'=>$poscode,
						'refund_amount'=>''.$refund_fee*100,
						'clientSn'=>$out_trade_no,
						'dpid'=>$dpid,
						'operator'=>$admin_id,
				));
			}else{
				$input = new WxPayRefund();
				$input->SetOut_trade_no($out_trade_no);
				$input->SetTotal_fee($total_fee*100);
				$input->SetRefund_fee($refund_fee*100);
				$input->SetOut_refund_no($out_refund_no);
				 
				$result = WxPayApi::refund($input);
			}
			if($result['return_code']=='SUCCESS'&&$result['result_code']=='SUCCESS'){
				$msg = array('status'=>true, 'trade_no'=>$out_refund_no);
			}else{
				$msg = array('status'=>false);
			}
		}else{
			$msg = array('status'=>false);
		}
		return  json_encode($msg);
	}
	/**
	 *
	 * @param unknown $data
	 *
	 * 支付宝支付退款
	 *
	 */
	public static function refundZfbPay($data) {
		$now = time();
		$dpid = $data['dpid'];
		$poscode = $data['poscode'];
		$rand = rand(100,999);
		$out_request_no = $now.'-'.$dpid.'-'.$rand;
		if(isset($data['admin_id']) && $data['admin_id'] != "" ){
			$admin_id = $data['admin_id'];
			$admin = WxAdminUser::get($dpid, $admin_id);
			if(!$admin){
				$msg = array('status'=>false);
				return json_encode($msg);
			}
		}else{
			$msg = array('status'=>false);
			return json_encode($msg);
		}
		
		if(isset($data['out_trade_no']) && $data['out_trade_no']!="" && $data['out_trade_no']!=0){
			$out_trade_no = $data['out_trade_no'];
			$refund_amount = $data['refund_fee'];
			
			$microPay = MicroPayModel::get($dpid, $out_trade_no, 1);
			if(empty($microPay)||empty($microPay['transaction_id'])){
				// 不存在支付记录 则直接退款成功
				$msg = array('status'=>true, 'trade_no'=>$out_request_no);
				return json_encode($msg);
			}
			$compaychannel = WxCompany::getpaychannel($dpid);
			if($compaychannel['pay_channel']=='2'){
				$result = SqbPay::refund(array(
						'device_id'=>$poscode,
						'refund_amount'=>''.$refund_amount*100,
						'clientSn'=>$out_trade_no,
						'dpid'=>$dpid,
						'operator'=>$admin_id,
				));
				if($result['return_code']=='SUCCESS'&&$result['result_code']=='SUCCESS'){
					$msg = array('status'=>true, 'trade_no'=>$out_trade_no);
				}else{
					$msg = array('status'=>false,'msg'=>'支付宝退款失败!!!');
				}
			}else{
				$alipayAccount = AlipayAccount::get($dpid);
				$f2fpayConfig = array(
						//支付宝公钥
						'alipay_public_key' => $alipayAccount['alipay_public_key'],
						//商户私钥
						'merchant_private_key' => $alipayAccount['merchant_private_key'],
						//编码格式
						'charset' => "UTF-8",
						//支付宝网关
						'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
						//应用ID
						'app_id' => $alipayAccount['appid'],
						//异步通知地址,只有扫码支付预下单可用
						'notify_url' =>  "",
						//最大查询重试次数
						'MaxQueryRetry' => "10",
						//查询间隔
						'QueryDuration' => "3"
				);
				//第三方应用授权令牌,商户授权系统商开发模式下使用
				$appAuthToken = "";//根据真实值填写
			
				//创建退款请求builder,设置参数
				$refundRequestBuilder = new AlipayTradeRefundContentBuilder();
				$refundRequestBuilder->setOutTradeNo($out_trade_no);
				$refundRequestBuilder->setRefundAmount($refund_amount);
				$refundRequestBuilder->setOutRequestNo($out_request_no);
			
				$refundRequestBuilder->setAppAuthToken($appAuthToken);
				//初始化类对象,调用refund获取退款应答
				$refundResponse = new AlipayTradeService($f2fpayConfig);
				$refundResult =	$refundResponse->refund($refundRequestBuilder);
				//根据交易状态进行处理
				switch ($refundResult->getTradeStatus()){
					case "SUCCESS":
						$msg = array('status'=>true, 'trade_no'=>$out_request_no);
						break;
					case "FAILED":
						$msg = array('status'=>false,'msg'=>'支付宝退款失败!!!');
						break;
					case "UNKNOWN":
						$msg = array('status'=>false,'msg'=>'系统异常，订单状态未知!!!');
						break;
					default:
						$msg = array('status'=>false,'msg'=>'不支持的交易状态，交易返回异常!!!');
						break;
				}
			}
		}else{
			$msg = array('status'=>false,'msg'=>'缺少参数!!!');
		}
		return json_encode($msg);
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
		$poscode = $data['poscode'];
		$accountNo = $data ['account'];
		$retreatId = $data ['retreatid'];
		$retreatprice = $data ['retreatprice'];
		$adminId = $data ['admin_id'];
		$username =  $data ['username'];
		$pruductIds = split('==',$data ['pruductids']);
		$memo = $data ['memo'];
		$retreatTime = $data ['retreattime'];
		if($retreatTime==''){
			$retreatTime = date ( 'Y-m-d H:i:s', $time );
		}
		$content = '';
		if(isset($data ['data'])){
			$content = $data ['data'];
		}
		if(isset($adminId) && $adminId != "" ){
			$admin = WxAdminUser::get($dpid, $adminId);
			if(!$admin){
				$msg = array('status'=>false,'msg'=>'不存在该服务员');
				return json_encode($msg);
			}
		}else{
			$msg = array('status'=>false,'msg'=>'不存在该服务员');
			return json_encode($msg);
		}
		$sql = 'select * from nb_order where dpid='.$dpid.' and account_no="'.$accountNo.'" and order_status in (3,4,8)';
		$order =  Yii::app ()->db->createCommand ($sql)->queryRow();
		if($order){	
			$orderId = $order['lid'];
			$sql = 'select sum(pay_amount) as total from nb_order_pay where order_id='.$orderId.' and dpid='.$dpid.' and pay_amount < 0 and paytype != 11';
			$orderPay =  Yii::app ()->db->createCommand ($sql)->queryRow();
			if($orderPay && !empty($orderPay['total'])){
				if($order['should_total'] + $orderPay['total'] + $retreatprice < 0){
					$msg = json_encode ( array (
							'status' => true,
							'syncLid' => $syncLid,
							'content' => $content
					) );
					return $msg;
				}
			}
		}else{
			$msg = json_encode ( array (
					'status' => false,
					'msg'=>'订单不存在'
			) );
			return $msg;
		}
		$transaction = Yii::app ()->db->beginTransaction ();
		try {
				foreach ($pruductIds as $productId){
					$productArr = split(',', $productId);
					$psetId = $productArr[0];
					$pproductId = $productArr[1];
					$pamount = $productArr[2];
					$pprice = $productArr[3];
				    if($psetId > 0){
				    	$sql = 'select * from nb_order_product where order_id='.$orderId.' and dpid='.$dpid.' and set_id='.$psetId;
				    	$orderProducts =  Yii::app ()->db->createCommand ($sql)->queryAll();
				    	foreach ($orderProducts as $orderproduct){
				    		$orderProductDetailId = $orderproduct['lid'];
				    	
				    		$sql = 'select sum(retreat_amount) as total from nb_order_retreat where order_detail_id='.$orderProductDetailId.' and dpid='.$dpid;
				    		$orderRetreat = Yii::app ()->db->createCommand ($sql)->queryRow();
				    		if($orderRetreat && !empty($orderRetreat['total'])){
			    				if($orderRetreat['total'] >= $orderproduct['zhiamount']){
			    					$transaction->rollback ();
			    					$msg = json_encode ( array (
			    							'status' => true,
			    							'syncLid' => $syncLid,
			    							'content' => $content
			    					) );
			    					return $msg;
			    				}
				    		}
				    		$sql = 'update nb_order_product set is_retreat=1 where lid='.$orderProductDetailId.' and dpid='.$dpid;
				    		Yii::app ()->db->createCommand ($sql)->execute();
				    	
				    		$se = new Sequence ( "order_retreat" );
				    		$orderRetreatId = $se->nextval ();
				    		$orderRetreatData = array (
				    				'lid' => $orderRetreatId,
				    				'dpid' => $dpid,
				    				'create_at' => $retreatTime,
				    				'update_at' => date ( 'Y-m-d H:i:s', $time ),
				    				'retreat_id' => $retreatId,
				    				'order_detail_id' => $orderProductDetailId,
				    				'retreat_memo' => $memo,
				    				'username' => $username,
				    				'retreat_amount' => $pamount,
				    				'is_sync' => 0
				    		);
				    		Yii::app ()->db->createCommand ()->insert ( 'nb_order_retreat', $orderRetreatData );
				    	}
				    }else{
				    	$sql = 'select * from nb_order_product where order_id='.$orderId.' and dpid='.$dpid.' and set_id='.$psetId.' and product_id='.$pproductId.' and price='.$pprice.' and is_retreat=0';
				    	$orderProduct =  Yii::app ()->db->createCommand ($sql)->queryRow();
				    	if($orderProduct){
				    		$orderProductDetailId = $orderProduct['lid'];
				    		 
				    		$sql = 'select sum(retreat_amount) as total from nb_order_retreat where order_detail_id='.$orderProductDetailId.' and dpid='.$dpid;
				    		$orderRetreat = Yii::app ()->db->createCommand ($sql)->queryRow();
				    		if($orderRetreat && !empty($orderRetreat['total'])){
			    				if($orderRetreat['total'] >= $orderProduct['amount']){
			    					$transaction->rollback ();
			    					$msg = json_encode ( array (
			    							'status' => true,
			    							'syncLid' => $syncLid,
			    							'content' => $content
			    					) );
			    					return $msg;
			    				}
				    		}
				    		$sql = 'update nb_order_product set is_retreat=1 where lid='.$orderProductDetailId.' and dpid='.$dpid;
				    		Yii::app ()->db->createCommand ($sql)->execute();
				    		 
				    		$se = new Sequence ( "order_retreat" );
				    		$orderRetreatId = $se->nextval ();
				    		$orderRetreatData = array (
				    				'lid' => $orderRetreatId,
				    				'dpid' => $dpid,
				    				'create_at' => $retreatTime,
				    				'update_at' => date ( 'Y-m-d H:i:s', $time ),
				    				'retreat_id' => $retreatId,
				    				'order_detail_id' => $orderProductDetailId,
				    				'retreat_memo' => $memo,
				    				'username' => $username,
				    				'retreat_amount' => $pamount,
				    				'is_sync' => 0
				    		);
				    		Yii::app ()->db->createCommand ()->insert ( 'nb_order_retreat', $orderRetreatData );
				    	}
				    }
				}
				$sql = 'select * from nb_order_pay where order_id='.$orderId.' and dpid='.$dpid.' and pay_amount > 0 and paytype != 11';
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
					$remark = $pay['remark'];
					if($pay['paytype']==1||$pay['paytype']==12||$pay['paytype']==13){
						// 微信支付
						$rData = array('dpid'=>$dpid,'poscode'=>$poscode,'admin_id'=>$adminId,'paytype'=>$pay['paytype'],'out_trade_no'=>$pay['remark'],'total_fee'=>$pay['pay_amount'],'refund_fee'=>$refund_fee);
						$result = self::refundWxPay($rData);
						$resObj = json_decode($result);
						if(!$resObj->status){
							throw new Exception('微信退款失败');
						}
						$remark = $resObj->trade_no;
					}elseif($pay['paytype']==2){
						// 支付宝支付
						
						$rData = array('dpid'=>$dpid,'poscode'=>$poscode,'admin_id'=>$adminId,'out_trade_no'=>$pay['remark'],'refund_fee'=>$refund_fee);
						$result = self::refundZfbPay($rData);
						$resObj = json_decode($result);
						if(!$resObj->status){
							throw new Exception('支付宝退款失败');
						}
						$remark = $resObj->trade_no;
					}elseif($pay['paytype']==4){
						// 会员卡支付
						$rData = array(
								'dpid'=>$dpid,
								'rfid'=>$pay['paytype_id'],
								'admin_id'=>$adminId,
								'password'=>'',
								'refund_price'=>$refund_fee,
								);
						$result = self::refundMemberCard($rData);
						$resObj = json_decode($result);
						if(!$resObj->status){
							throw new Exception('会员卡退款失败');
						}
					}elseif ($pay['paytype']==9){
						if($order['order_type']){
							if($pay['remark']!='全款支付'){
								$user = WxBrandUser::getFromCardId($dpid, $pay['remark']);
							}else{
								$user = WxBrandUser::get($order['user_id'],$dpid);
							}
						}else{
							$user = WxBrandUser::getFromCardId($dpid, $pay['remark']);
						}
						WxCupon::refundCupon($orderpay['paytype_id'],$user['lid']);
					}elseif ($pay['paytype']==10){
						if($order['order_type'] > 0){
							if($pay['remark']!='全款支付'){
								$remark = $pay['remark'];
							}else{
								$user = WxBrandUser::get($order['user_id'],$dpid);
								$remark = $user['card_id'];
							}
						}else{
							$remark = $pay['remark'];
						}
						WxBrandUser::refundYue($refund_fee, $remark);
					}
					$se = new Sequence ( "order_pay" );
					$orderPayId = $se->nextval ();
					$orderPayData = array (
							'lid' => $orderPayId,
							'dpid' => $dpid,
							'create_at' => $retreatTime,
							'update_at' => date ( 'Y-m-d H:i:s', $time ),
							'order_id' => $orderId,
							'account_no' => $accountNo,
							'pay_amount' => -$refund_fee,
							'paytype' => $pay['paytype'],
							'payment_method_id' => $pay['payment_method_id'],
							'paytype_id' => $pay['paytype_id'],
							'remark' => $remark,
							'is_sync' => 0
					);
					Yii::app ()->db->createCommand ()->insert ( 'nb_order_pay', $orderPayData );
				}
				
				$transaction->commit ();
				$msg = json_encode ( array (
						'status' => true,
						'syncLid' => $syncLid,
						'content' => $content
				) );
		} catch ( Exception $e ) {
			$transaction->rollback ();
			$msg = json_encode ( array (
					'status' => false,
					'msg'=>$e->getMessage()
			) );
		}
		return $msg;
	}
	public static function batchSync($data) {
		if(isset($data) && !empty($data['data'])){
			$dpid = 0;
			$lidArr = array();
			$adminId = $data['admin_id'];
			$poscode = isset($data['poscode'])?$data['poscode']:0;
			$data = $data['data'];
			$dataArr = json_decode($data);
			foreach ($dataArr as $obj){
				$lid = $obj->lid;
				$dpid = $obj->dpid;
				$padLid = $obj->jobid;
				$type = $obj->sync_type;
				$syncurl = $obj->sync_url;
				$content = $obj->content;
				$content = str_replace(PHP_EOL, '', $content);
				if($type==2){
					// 新增订单
					$pData = array('sync_lid'=>$lid,'dpid'=>$dpid,'is_pos'=>1,'posLid'=>$padLid,'data'=>$content);
					$result = self::operateOrder($pData);
				}elseif($type==4){
					// 退款
					$contentArr = split('::', $content);
					$createAt = isset($contentArr[7])?$contentArr[7]:'';
					$pData = array('sync_lid'=>$lid,'dpid'=>$dpid,'admin_id'=>$adminId,'poscode'=>$poscode,'account'=>$contentArr[1],'username'=>$contentArr[2],'retreatid'=>$contentArr[3],'retreatprice'=>$contentArr[4],'pruductids'=>$contentArr[5],'memo'=>$contentArr[6],'retreattime'=>$createAt,'data'=>$content);
					$result = self::retreatOrder($pData);
				}elseif($type==3){
					// 增加会员卡
					$pData = array('sync_lid'=>$lid,'dpid'=>$dpid,'is_pos'=>1,'posLid'=>$padLid,'data'=>$content);
					$result = self::addMemberCard($pData);
				}
				$resObj = json_decode($result);
				if($resObj->status){
					array_push($lidArr, $lid);
				}else{
					Helper::writeLog('同步失败:同步内容:'.$dpid.json_encode($obj).'错误信息:'.$resObj->msg);
					// 插入同步不成功数据
					$data = array('dpid'=>$dpid,'jobid'=>$padLid,'pos_sync_lid'=>$lid,'sync_type'=>$type,'sync_url'=>$syncurl,'content'=>$content);
					$resFail = self::setSyncFailure($data);
					$failObj = json_decode($resFail);
					if($failObj->status){
						array_push($lidArr, $lid);
					}
				}
			}
			// 获取云端失败数据
			$syncData = self::getAllSyncFailure($dpid);
			$syncArr = json_decode($syncData);
			if(!empty($syncArr)){
				foreach ($syncArr as $sync){
					$lid = $sync->lid;
					$dpid = $sync->dpid;
					$padLid = $sync->jobid;
					$syncLid = $sync->pos_sync_lid;
					$type = $sync->sync_type;
					$syncurl = $sync->sync_url;
					$content = $sync->content;
					if($type==2){
						// 新增订单
						$pData = array('sync_lid'=>$syncLid,'dpid'=>$dpid,'is_pos'=>1,'posLid'=>$padLid,'data'=>$content);
						$result = self::operateOrder($pData);
					}elseif($type==4){
						// 退款
						$contentArr = split('::', $content);
						$createAt = isset($contentArr[7])?$contentArr[7]:'';
						$pData = array('sync_lid'=>$syncLid,'dpid'=>$dpid,'admin_id'=>$adminId,'poscode'=>$poscode,'account'=>$contentArr[1],'username'=>$contentArr[2],'retreatid'=>$contentArr[3],'retreatprice'=>$contentArr[4],'pruductids'=>$contentArr[5],'memo'=>$contentArr[6],'retreattime'=>$createAt,'data'=>$content);
						$result = self::retreatOrder($pData);
					}elseif($type==3){
						// 增加会员卡
						$pData = array('sync_lid'=>$lid,'dpid'=>$dpid,'is_pos'=>1,'posLid'=>$padLid,'data'=>$content);
						$result = self::addMemberCard($pData);
					}
					$resObj = json_decode($result);
					if($resObj->status){
						self::delSyncFailure($lid,$dpid);
					}else{
						Helper::writeLog('再次同步失败:同步内容:'.$dpid.json_encode($sync).'错误信息:'.$resObj->msg);
					}
				}
			}
			$count = count($lidArr);
			$lidStr = join(',', $lidArr);
			Helper::writeLog($dpid.'新增订单 返回:'.$lidStr);
			$msg = json_encode(array('status'=>true,'count'=>$count,'msg'=>$lidStr));
		}else{
			$msg = json_encode(array('status'=>false,'msg'=>''));
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
					'status' => true,
					'syncLid' => $syncLid,
					'content' => $orderData
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
					'status' => false
				) );
		}
		return $msg;
	}
	/**
	 *
	 * 会员卡 信息
	 *
	 */
	public static function getMemberCard($data) {
		$dpid = $data ['dpid'];
		$rfid = $data ['rfid'];
		$type = $data ['type'];
		
		$dpid = WxCompany::getDpids($dpid);
		$sql = 'select t.*,t1.level_name,t1.level_discount,t1.birthday_discount from nb_member_card t left join nb_brand_user_level t1 on t.level_id=t1.lid and t.dpid=t1.dpid and t1.level_type=0 and t1.delete_flag=0 where t.dpid in (' . $dpid . ') and t.rfid="' . $rfid . '" and t.delete_flag=0';
		$result = Yii::app ()->db->createCommand ( $sql )->queryRow ();
		if (!$result) {
			$msg = array('status'=>false,'type'=>$type);
		}else{
			$msg = array('status'=>true,'data'=>$result,'type'=>$type);
		}
		return json_encode($msg);
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
		$sql = 'select * from nb_member_card where dpid in (' . $dpid . ') and rfid="' . $rfid . '" and delete_flag=0';
		$result = Yii::app ()->db->createCommand ( $sql )->queryRow ();
		if (! $result) {
			return '0.00';
		}else{
			return $result['all_money'];
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
		$result = Yii::app ()->db->createCommand ( $sql )->queryRow ();
		if (! $result) {
			return json_encode ( array (
					'status' => false,
					'msg' => '不存在该管理员'
			) );
		}
		$dpid = WxCompany::getDpids($dpid);
		
		$sql = 'select * from nb_member_card where dpid in (' . $dpid . ') and rfid=' . $rfid . ' and delete_flag=0';
		$result = Yii::app ()->db->createCommand ( $sql )->queryRow ();
		if (! $result) {
			return json_encode ( array (
					'status' => false,
					'msg' => '不存在该会员信息' 
			) );
		}
		
		if ($payPrice > $result ['all_money']) {
			return json_encode ( array (
					'status' => false,
					'msg' => '余额不足' 
			) );
		}
		
		$sql = 'update nb_member_card set all_money=all_money-' . $payPrice . ' where dpid in (' . $dpid . ') and lid=' . $result ['lid'] . ' and rfid=' . $rfid;
		$result = Yii::app ()->db->createCommand ( $sql )->execute ();
		if ($result) {
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
		$result = Yii::app ()->db->createCommand ( $sql )->queryRow ();
		if (! $result) {
			return json_encode ( array (
					'status' => false,
					'msg' => '不存在该管理员'
			) );
		}
		
		$dpid = WxCompany::getDpids($dpid);
		
		$sql = 'select * from nb_member_card where dpid in (' . $dpid . ') and rfid="' . $rfid . '" and delete_flag=0';
		$result = Yii::app ()->db->createCommand ( $sql )->queryRow ();
		if (! $result) {
			return json_encode ( array (
					'status' => false,
					'msg' => '不存在该会员信息'
			) );
		}
		
		$sql = 'update nb_member_card set all_money=all_money+' . $refundPrice . ' where dpid in (' . $dpid . ') and lid=' . $result ['lid'] . ' and rfid=' . $rfid;
		$result = Yii::app ()->db->createCommand ( $sql )->execute ();
		if ($result) {
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
	 * @param $data
	 * dpid rfid n_card_id level_id o_card_id
	 * 
	 */
	public static function bindMemberCard($data) {
		$dpid = $data['dpid'];
		$rfid = $data['rfid'];
		$nCardId = $data['n_card_id'];
		$levelId = $data['level_id'];
		$oCardId = $data['o_card_id'];
		$sql = 'select * from nb_member_card where dpid='.$dpid.' and selfcode="'.$oCardId.'" and delete_flag=0';
		$memcard = Yii::app ()->db->createCommand ( $sql )->queryRow ();
		if($memcard){
			$sql = 'update nb_member_card set rfid="'.$rfid.'",selfcode="'.$nCardId.'",level_id='.$levelId.' where lid='.$memcard['lid'].' and dpid='.$dpid;
			$result = Yii::app ()->db->createCommand ( $sql )->execute ();
			if($result){
				return json_encode(array('status'=>true,'msg'=>'更换成功'));
			}else{
				return json_encode(array('status'=>false,'msg'=>'更换失败请重新操作'));
			}
		}else{
			return json_encode(array('status'=>false,'msg'=>'不存在该会员卡'));
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
		//Helper::writeLog('进入方法');
		if(empty($tasteArr)){
			$sql = 'select t.* from nb_product_bom t left join nb_product_material k on(t.dpid = k.dpid and t.material_id = k.lid) where t.dpid='.$dpid.' and t.product_id='.$productId.' and t.taste_id=0 and t.delete_flag=0 and k.delete_flag=0';
		}else{
			$tasteStr = join(',', $tasteArr);
			$sql = 'select t.* from nb_product_bom t left join nb_product_material k on(t.dpid = k.dpid and t.material_id = k.lid) where t.dpid='.$dpid.' and t.product_id='.$productId.' and t.taste_id=0 and t.delete_flag=0 and k.delete_flag =0'.
					' union select tt.* from nb_product_bom tt left join nb_product_material kk on(tt.dpid = kk.dpid and tt.material_id = kk.lid ) where tt.dpid='.$dpid.' and tt.product_id='.$productId.' and tt.taste_id in('.$tasteStr.') and tt.delete_flag=0 and kk.delete_flag =0';
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
	public static function updateMaterialStock($dpid, $createAt, $materialId, $stock,$orderProductId) {
		$temStock = $stock;
		$time = time ();
		$sql = 'select * from nb_product_material_stock where dpid='.$dpid.' and  material_id='.$materialId.' and stock <> 0 and delete_flag=0 order by create_at asc';
		$materialStocks = Yii::app ()->db->createCommand ( $sql )->queryAll ();
		if(!empty($materialStocks)){
			$count = count($materialStocks);
			foreach ($materialStocks as $k=>$materialStock){
				$realityStock = $materialStock['stock'];
				if($realityStock == 0 && $k+1 != $count){
					continue;
				}
				if($materialStock['batch_stock']>0){
					$stockPrice = number_format($materialStock['stock_cost']/$materialStock['batch_stock'],4);
				}else{
					$stockPrice = 0;
				}
				$temStock = $temStock - $realityStock;
				if($temStock > 0){
					if($k+1 == $count){
						$sql = 'update nb_product_material_stock set stock = stock - '.($temStock + $realityStock).' where lid='.$materialStock['lid'].' and dpid='.$dpid.' and delete_flag=0';
						Yii::app ()->db->createCommand ( $sql )->execute ();
						
						$se = new Sequence ( "material_stock_log" );
						$materialStockLogId = $se->nextval ();
						$materialStockLog = array (
								'lid' => $materialStockLogId,
								'dpid' => $dpid,
								'create_at' => $createAt,
								'update_at' => date ( 'Y-m-d H:i:s', $time ),
								'logid'=>$materialStock['lid'],
								'order_product_id'=>$orderProductId,
								'material_id' => $materialId,
								'type' => 1,
								'stock_num' => $temStock + $realityStock,
								'original_num'=>$materialStock['batch_stock'],
								'unit_price'=>$stockPrice,
								'resean' => '正常消耗',
								'is_sync' => DataSync::getInitSync ()
						);
						Yii::app ()->db->createCommand ()->insert ( 'nb_material_stock_log', $materialStockLog );
					}else{
						$sql = 'update nb_product_material_stock set stock= 0 where lid='.$materialStock['lid'].' and dpid='.$dpid.' and delete_flag=0';
						Yii::app ()->db->createCommand ( $sql )->execute ();
						
						$se = new Sequence ( "material_stock_log" );
						$materialStockLogId = $se->nextval ();
						$materialStockLog = array (
								'lid' => $materialStockLogId,
								'dpid' => $dpid,
								'create_at' => $createAt,
								'update_at' => date ( 'Y-m-d H:i:s', $time ),
								'logid'=>$materialStock['lid'],
								'order_product_id'=>$orderProductId,
								'material_id' => $materialId,
								'type' => 1,
								'stock_num' => $realityStock,
								'original_num'=>$materialStock['batch_stock'],
								'unit_price'=>$stockPrice,
								'resean' => '正常消耗',
								'is_sync' => DataSync::getInitSync ()
						);
						Yii::app ()->db->createCommand ()->insert ( 'nb_material_stock_log', $materialStockLog );
					}
				}else{
					$sql = 'update nb_product_material_stock set stock = stock - '.($temStock + $realityStock).' where lid='.$materialStock['lid'].' and dpid='.$dpid.' and delete_flag=0';
					Yii::app ()->db->createCommand ( $sql )->execute ();
					
					$se = new Sequence ( "material_stock_log" );
					$materialStockLogId = $se->nextval ();
					$materialStockLog = array (
							'lid' => $materialStockLogId,
							'dpid' => $dpid,
							'create_at' => $createAt,
							'update_at' => date ( 'Y-m-d H:i:s', $time ),
							'logid'=>$materialStock['lid'],
							'order_product_id'=>$orderProductId,
							'material_id' => $materialId,
							'type' => 1,
							'stock_num' => $temStock + $realityStock,
							'original_num'=>$materialStock['batch_stock'],
							'unit_price'=>$stockPrice,
							'resean' => '正常消耗',
							'is_sync' => DataSync::getInitSync ()
					);
					Yii::app ()->db->createCommand ()->insert ( 'nb_material_stock_log', $materialStockLog );
					break;
				}
			}
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
	/**
	 * 
	 * 同步内容失败写入云端
	 * 
	 */
	public static function setSyncFailure($data) {
		$time = time ();
		$dpid = $data['dpid'];
		$jobid = $data['jobid']; 
		$posSyncLid = $data['pos_sync_lid'];// 保存pos本地的lid
		$syncType = $data['sync_type'];
		$syncUrl = $data['sync_url'];
		$content = $data['content'];
		$sql = "select * from nb_sync_failure where dpid=".$dpid." and jobid=".$jobid." and pos_sync_lid=".$posSyncLid." and sync_type=".$syncType." and sync_url='".$syncUrl."' and content='".$content."' and delete_flag=0";
		$failresult = Yii::app ()->db->createCommand ( $sql )->queryRow ();
		if($failresult){
			$msg = json_encode(array('status'=>true));
			return $msg;
		}
		$se = new Sequence ( "sync_failure" );
		$syncFailureId = $se->nextval ();
		$syncFailure = array (
				'lid' => $syncFailureId,
				'dpid' => $dpid,
				'create_at' => date ( 'Y-m-d H:i:s', $time ),
				'update_at' => date ( 'Y-m-d H:i:s', $time ),
				'jobid' => $jobid,
				'pos_sync_lid' => $posSyncLid,
				'sync_type' => $syncType,
				'sync_url' => $syncUrl,
				'content' => $content,
				'is_sync' => DataSync::getInitSync ()
		);
		$result = Yii::app ()->db->createCommand ()->insert ( 'nb_sync_failure', $syncFailure );
		if($result){
			$msg = json_encode(array('status'=>true));
		}else{
			$msg = json_encode(array('status'=>false));
		}
		return $msg;
	}
	/**
	 * 
	 * 获取所有同步失败列表
	 * 
	 */
	public static function getAllSyncFailure($dpid) {
		$sql = 'select * from nb_sync_failure where dpid='.$dpid.' and delete_flag=0';
		$results = Yii::app ()->db->createCommand ( $sql )->queryAll ();
		return json_encode($results);
	}
	/**
	 * 
	 * 删除失败数据
	 * 
	 */
	public static function delSyncFailure($lid,$dpid) {
		$sql = 'update nb_sync_failure set delete_flag=1 where lid='.$lid.' and dpid='.$dpid;
		Yii::app ()->db->createCommand ( $sql )->execute ();
	}
	/**
	 * 
	 * 获取 微信会员信息
	 * 
	 */
	public static function getUserInfo($data) {
		$dpid = $data['dpid'];
		$cardId = $data['card_id'];
		$productIds = isset($data['pro_ids'])?$data['pro_ids']:'';
		$user = WxBrandUser::getFromCardId($dpid,$cardId);
		if($user){
			$user['user_birthday'] = date('m.d',strtotime($user['user_birthday']));
			$cupon = WxCupon::getUserPosCupon($user['lid'],$dpid,$productIds);
			$point = WxPoints::getAvaliablePoints($user['lid'], $user['dpid']);
			$msg = array('status'=>true,'user'=>$user,'cupon'=>$cupon,'points'=>$point);
		}else{
			$msg = array('status'=>false);
		}
		return json_encode($msg);
	}
	/**
	 *
	 * 获取 微信会员信息
	 *
	 */
	public static function dealWxHykPay($data) {
		$dpid = $data['dpid'];
		$cardId = $data['card_id'];
		$cupons = isset($data['cupon'])?$data['cupon']:array();
		$yue = $data['yue'];
		$points = $data['points'];
		$user = WxBrandUser::getFromCardId($dpid,$cardId);
		if($user){
			$transaction=Yii::app()->db->beginTransaction();
			try{
				if(!empty($cupons)){
					foreach ($cupons as $cupon){
						$res = WxCupon::dealCupon($cupon['dpid'], $cupon['cupon_id'], 2);
						if(!$res){
							throw new Exception('代金券核销失败');
						}
					}
				}
				if($yue!=0){
					$res = WxBrandUser::dealYue($user['lid'], $user['dpid'], -$yue);
					if(!$res){
						throw new Exception('储值支付失败');
					}
				}
				if($points!='0-0'){
					$pointArr = split('-', $points);
					$res = WxPoints::dealPoints($user['lid'], $user['dpid'],$pointArr[1]);
					if(!$res){
						throw new Exception('积分支付失败');
					}
				}
				$transaction->commit();
				$msg = array('status'=>true);
			}catch (Exception $e) {
				$message = $e->getMessage();
				$transaction->rollback();
				$msg = array('status'=>false,'msg'=>$message);
			}
		}else{
			$msg = array('status'=>false,'msg'=>'不存在该会员信息');
		}
		return json_encode($msg);
	}
	/**
	 * 
	 * 获取原材料消耗
	 * 
	 */
	public static function getMaterial($dpid,$startTime,$endTime) {
		$sql = 'select t.material_id,sum(t.stock_num) as material_num,t1.material_name,t2.unit_name from nb_material_stock_log t left join nb_product_material t1 on t.material_id=t1.lid and t.dpid=t1.dpid left join nb_material_unit t2 on t1.sales_unit_id=t2.lid and t1.dpid=t2.dpid where t.dpid='.$dpid.' and t.create_at >= "'.$startTime.'" and "'.$endTime.'" >= t.create_at and t.type=1 and t.material_id in(select k.lid from nb_product_material k where k.delete_flag = 0 and k.dpid = '.$dpid.') group by t.material_id';
		$result = Yii::app ()->db->createCommand ( $sql )->queryAll ();
		return $result;
	}
}

