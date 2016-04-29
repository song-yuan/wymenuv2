<?php
/**
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
 */
class DataSyncOperation
{
	/**
	 * 
	 * 获取基础数据表
	 * 
	 */
	 public static function getDataSyncBaseTables()
	 {
	 	$dataBase = new DataSyncTables();
        $baseTables = $dataBase->getBaseTableList();
        return $baseTables;
	 }
	 /**
	  * 
	  * 
	  * 获取所有的表 和 结构
	  * 
	  */
	  public static function getDataSyncAllTables()
	 {
	 	$dataBase = new DataSyncTables();
        $allTables = $dataBase->getAllTableList();
        
    	foreach($allTables as $k=>$table){
    		$tableStruct = $dataBase->getTableStructure($table['table']);
    		$allTables[$k]['struct'] = $tableStruct;
    	}
        return array('status'=>true,'msg'=>$allTables);
	 }
	/**
	 * 
	 * 获取初始化数据
	 * 
	 */
	 public static function getDataSyncData($dpid,$tableName)
	 {
	 	$dataBase = new DataSyncTableData($dpid,$tableName);
        $tableData = $dataBase->getInitData();
        if($tableData){
        	return array('status'=>true,'msg'=>$tableData);
        }else{
        	return array('status'=>false,'msg'=>'无数据');
        }
	 }
     /**
     * 获取需要到本地执行的sql，每次仅限1000条
     */
    public static function getCloudSqlData1000($dpid)
    {
        $allflag=substr("11111111111111111111111111111111111111111111111111111", 
                0, Yii::app()->params['sync_maxlocal']);  
        return substr_replace($allflag, "0", Yii::app()->params['sync_localnum']-1,1);
    }
    
    public static function execLocalSql($dpid,$sqldata)
    {
        
    }
    
    /**
     * 有些更新必须先同步到云端，如产品的库存数量，必须各个客户端都更新云端一个地方，然后同步下来
     * 采用的策略是用sql语句更新云端，然后从云端同步到本地，
     * 所以更新时限更新云端，然后更新本地，如果云端失败，保存sql，同步前先调用这个更新sql，再同步云端数据
     * 具体功能是：
     * 先操作云端，如果成功则返回，如果失败则存储到nb_sqlcmd_sync
     * 
     * 这些数据，必须云端一致！！！
     * @param type $sql
     */
    public static function cloudFirt($dpid,$sql)
    {
        try
        {
            $dbcloud=Yii::app()->dbcloud;
            $dbcloud->createCommand($sql)->execute();
            return true;
        }  catch (Exception $e)
        {
            $dblocal=Yii::app()->dblocal;
            $se=new Sequence("sqlcmd_sync");
            $lid = $se->nextval();
            $data = array(
                'lid'=>$lid,
                'dpid'=>$dpid,
                'create_at'=>date('Y-m-d H:i:s',time()),
                'sqlcmd'=>$sql,                
                'is_sync'=>'10000' //10000表示需要先在服务器端更新！
            );
            $dblocal->createCommand()->insert('nb_sqlcmd_sync',$data);
            return false;
        }
    }
    
    /**
     * 同步云端的数据之前，将本地需要先更新云端的数据查询出来，
     * 然后更新云端，再同步，如果失败直接返回false
     * 操作：
     * 查出所有的sqlcmd_sync，然后执行
     * @return boolean
     */
    public static function cloudFirstSync($dpid)
    {
        $dbcloud=Yii::app()->dbcloud;
        $dblocal=Yii::app()->dblocal;
        $cloudexec=$dblocal->createCommand("select lid,sqlcmd from nb_sqlcmd_sync where is_sync='10000' and dpid=".$dpid)->queryAll();
        if(empty($cloudexec))
        {
            return true;
        } 
        $dellist="(";
        $transactioncloud = $dbcloud->beginTransaction();
        try {
            foreach ($cloudexec as $row)
            {
                $dbcloud->createCommand($row['sqlcmd'])->execute();
                $dellist=$dellist.$row['lid'].",";
            }
            $dellist=$dellist."0000000000".")";
            $dblocal->createCommand("delete from nb_sqlcmd_sync where dpid=".$dpid." and lid in ".$dellist)->execute();            
            $transactioncloud->commit();
            return true;
        } catch (Exception $ex) {
            //osy//echo $ex->getMessage();
            $transactioncloud->rollback();
            return false;
        }
    }
    /**
     * 
     * 用户名密码验证
     * 
     */
     public static function validateUser($data){
     	$userName = $data['user_name'];
     	$passward = $data['passward'];
     	$sql = 'select * from nb_user where username="'.$userName.'" and password_hash="'.Helper::genPassword($passward).'" and delete_flag=0';
     	$result = Yii::app()->db->createCommand($sql)->queryRow();
     	if($result){
     		return json_encode(array('status'=>true));
     	}else{
     		return json_encode(array('status'=>false));
     	}
     }
    /**
     * 订单操作
     * 
     */
    public static function operateOrder($data){
    	$dpid = $data['dpid'];
    	$orderData = $data['data'];
    	$obj = json_decode($orderData);
    	
    	$orderInfo = $obj->order_info;
    	$orderProduct = $obj->order_product;
    	$orderPay = $obj->order_pay;
    	
    	$time = time();
	    $se = new Sequence("order");
	    $orderId = $se->nextval();
	    $accountNo = WxOrder::getAccountNo($dpid,$orderInfo->site_id,$orderInfo->is_temp,$orderId);
	   
	    $transaction=Yii::app()->db->beginTransaction();
		try{
		    $insertOrderArr = array(
		        	'lid'=>$orderId,
		        	'dpid'=>$dpid,
		        	'create_at'=>date('Y-m-d H:i:s',$time),
		        	'update_at'=>date('Y-m-d H:i:s',$time), 
		        	'account_no'=>$accountNo,
		        	'user_id'=>'0',
		        	'site_id'=>$orderInfo->site_id,
		        	'is_temp'=>$orderInfo->is_temp,
		        	'number'=>$orderInfo->number,
		        	'order_status'=>$orderInfo->order_status,
		        	'order_type'=>$orderInfo->order_type,
		        	'should_total'=>$orderInfo->should_total,
		        	'reality_total'=>$orderInfo->should_total,
		        	'is_sync'=>DataSync::getInitSync(),
		        );
			$result = Yii::app()->db->createCommand()->insert('nb_order', $insertOrderArr);
			
			foreach($orderProduct as $product){
				$se = new Sequence("order_product");
		    	$orderProductId = $se->nextval();
	         	$orderProductData = array(
								'lid'=>$orderProductId,
								'dpid'=>$dpid,
								'create_at'=>date('Y-m-d H:i:s',$time),
	        					'update_at'=>date('Y-m-d H:i:s',$time), 
								'order_id'=>$orderId,
								'set_id'=>$product->set_id,
								'product_id'=>$product->product_id,
								'product_name'=>$product->product_name,
								'product_pic'=>'',
								'price'=>$product->price,
								'original_price'=>$product->price,
								'amount'=>$product->amount,
								'product_order_status'=>2,
								'is_sync'=>DataSync::getInitSync(),
								);
				 Yii::app()->db->createCommand()->insert('nb_order_product',$orderProductData);
			}
			foreach($orderPay as $pay){
				$se = new Sequence("order_pay");
		    	$orderPayId = $se->nextval();
		    	$orderPayData = array(
								'lid'=>$orderPayId,
								'dpid'=>$dpid,
								'create_at'=>date('Y-m-d H:i:s',$time),
	        					'update_at'=>date('Y-m-d H:i:s',$time), 
								'order_id'=>$orderId,
								'account_no'=>$accountNo,
								'pay_amount'=>$pay->pay_amount,
								'paytype'=>$pay->paytype,
								'payment_method_id'=>$pay->payment_method_id,
								'paytype_id'=>$pay->paytype_id,
								'is_sync'=>DataSync::getInitSync(),
								);
				 Yii::app()->db->createCommand()->insert('nb_order_pay',$orderPayData);
		    	
			}
		   $transaction->commit();
		   $msg = json_encode(array('status'=>true,'orderId'=>$orderId));
		}catch (Exception $e) {
		   $transaction->rollback();
		   $msg = json_encode(array('status'=>false,'orderId'=>''));
		}
	    return $msg;
    } 
    /**
     * 增加会员卡
     * 
     */
    public static function addMemberCard($data){
    	$dpid = $data['dpid'];
    	$orderData = $data['data'];
    	$obj = json_decode($orderData);
    	
    	$time = time();
	    $se = new Sequence("member_card");
	    $memberCardId = $se->nextval();
    	$inserMemberCardrArr = array(
		        	'lid'=>$memberCardId,
		        	'dpid'=>$dpid,
		        	'create_at'=>date('Y-m-d H:i:s',$time),
		        	'update_at'=>date('Y-m-d H:i:s',$time), 
		        	'selfcode'=>$obj->selfcode,
		        	'rfid'=>$obj->rfid,
		        	'level_id'=>$obj->level_id,
		        	'name'=>$obj->name,
		        	'mobile'=>$obj->mobile,
		        	'sex'=>$obj->sex,
		        	'ages'=>$obj->ages,
		        	'is_sync'=>DataSync::getInitSync(),
		        );
	   $result = Yii::app()->db->createCommand()->insert('nb_member_card', $inserMemberCardrArr);
	   if($result){
	      return true;
	   }else{
	      return false;
	   }
    }
    /**
     * 
     * 获取订单
     * 
     */
     public static function getOrderStaus($dpid,$orderId){
     	$order = WxOrder::getOrder($orderId,$dpid);
     	if($order){
     		return json_encode(array('status'=>true,'order_status'=>$order['order_status']));
     	}else{
     		return json_encode(array('status'=>false,'order_status'=>''));
     	}
     }
}