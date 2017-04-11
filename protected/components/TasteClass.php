<?php
class TasteClass
{
	//产品口味 列表
	public static function getProductTaste($productId,$dpid){
		$sql = 'select t.taste_id as lid,t1.name from nb_product_taste t,nb_taste t1 where t.taste_id=t1.lid and t.product_id=:productId and t.dpid=t1.dpid and t.dpid=:dpid and t.delete_flag=0';
		$conn = Yii::app()->db->createCommand($sql);
		$conn->bindValue(':productId',$productId);
                $conn->bindValue(':dpid',$dpid);
		$result = $conn->queryAll();
		return $result;
	}
        
        //产品口味 列表
	public static function getProductTasteGroup($productId,$dpid){
		$sql = 'select t.taste_group_id as lid,t1.name from nb_product_taste t,nb_taste_group t1 where t.taste_group_id=t1.lid and t.product_id=:productId and t.dpid=t1.dpid and t.dpid=:dpid and t.delete_flag=0';
		$conn = Yii::app()->db->createCommand($sql);
		$conn->bindValue(':productId',$productId);
                $conn->bindValue(':dpid',$dpid);
		$result = $conn->queryAll();
		return $result;
	}
	
        //全订单口味列表 1 整单 0 非整单
	public static function getAllOrderTasteGroup($dpid,$type){
		$sql = 'select lid,name from nb_taste_group where dpid=:dpid and allflae=:allflae and delete_flag=0';
		$conn = Yii::app()->db->createCommand($sql);
		$conn->bindValue(':dpid',$dpid);
		$conn->bindValue(':allflae',$type);
		$result = $conn->queryAll();
		return $result;
	}
        
        //全订单口味列表 1 整单 0 非整单
	public static function gettastes($lid,$dpid){
		$sql = 'select lid,name from nb_taste where dpid=:dpid and taste_group_id=:lid and delete_flag=0';
		$conn = Yii::app()->db->createCommand($sql);
		$conn->bindValue(':dpid',$dpid);
		$conn->bindValue(':lid',$lid);
		$result = $conn->queryAll();
		return $result;
	}
        
	//全订单口味列表 1 整单 0 非整单
	public static function getAllOrderTaste($dpid,$type){
		$sql = 'select lid,name from nb_taste where dpid=:dpid and allflae=:allflae and delete_flag=0';
		$conn = Yii::app()->db->createCommand($sql);
		$conn->bindValue(':dpid',$dpid);
		$conn->bindValue(':allflae',$type);
		$result = $conn->queryAll();
		return $result;
	}
	
	//订单口味 type = 1 全单口味 2 订单产品口味
	public static function getOrderTaste($orderlist,$type,$dpid){
		$result = array();
		if($type==1){
			$sql = 'select t.taste_id from nb_order_taste t where t.order_id in (:orderId) and t.dpid=:dpid and t.is_order=1';
			$conn = Yii::app()->db->createCommand($sql);
			$conn->bindValue(':orderId',$orderlist);
                        $conn->bindValue(':dpid',$dpid);
		}elseif($type==2){
			$sql = 'select t.taste_id from nb_order_taste t where t.order_id in (:orderId) and t.dpid=:dpid and t.is_order=0';
			$conn = Yii::app()->db->createCommand($sql);
			$conn->bindValue(':orderId',$orderlist);
                        $conn->bindValue(':dpid',$dpid);
		}
		$results = $conn->queryAll();
		foreach($results as $val){
			array_push($result,$val['taste_id']);
		}
		return $result;
	}
        
        //订单口味 type = 1 全单口味 2 订单产品口味
        //如果是全单口味，将一座位的多个订单的整体teaste合并到最新的订单
	public static function getOrderTasteKV($orderId,$orderlist,$type,$dpid){
		$result = array();
		if($type==1){
                        //将这一个桌子的订单的全局口味，全部对应当前订单。
                        $sqlup="update nb_order_taste set order_id=".$orderId." where dpid=".$dpid." and order_id in (".$orderlist.")";
                        $connup=Yii::app()->db->createCommand($sqlup);
                        $connup->execute();
                        //去除重复的
			$sql = 'select distinct t.order_id as id,t.taste_id as tasteid,t1.name as name from nb_order_taste t'
                                . ' left join nb_taste t1 on t.dpid=t1.dpid and t.taste_id=t1.lid where t.order_id'
                                . ' in ('.$orderId.') and t.dpid='.$dpid.' and t.is_order=1';
			$conn = Yii::app()->db->createCommand($sql);
			//$conn->bindValue(':orderId',$orderlist);
                        //$conn->bindValue(':dpid',$dpid);
		}elseif($type==2){
			$sql = 'select t.order_id as id,t.taste_id as tasteid,t1.name as name from nb_order_taste t'
                                . ' left join nb_taste t1 on t.dpid=t1.dpid and t.taste_id=t1.lid'
                                . '  where t.order_id in (select lid from nb_order_product'
                                . ' where dpid='.$dpid.' and order_id in ('.$orderlist.')) and t.dpid='.$dpid.' and t.is_order=0';
			$conn = Yii::app()->db->createCommand($sql);
                        //echo $sql;exit;
//			$conn->bindValue(':orderId',$orderlist);
//                        $conn->bindValue(':dpid',$dpid);
//                        $conn->bindValue(':ddpid',$dpid);
		}
		$results = $conn->queryAll();
                //$idst=array_column($results, 'id');
		//foreach($results as $val){
		//	array_push($result,$val['taste_id']);
		//}
		return $results;
	}
        
	//订单口味 type = 1 全单口味 2 订单产品口味
	public static function getOrderTasteMemo($orderlist,$type,$dpid){
		$result = array();
		if($type==1){
			$sql = 'select t.taste_memo from nb_order t where t.lid in (:orderId) and t.dpid=:dpid';
			$conn = Yii::app()->db->createCommand($sql);
			$conn->bindValue(':orderId',$orderlist);
                        $conn->bindValue(':dpid',$dpid);
		}elseif($type==2){
			$sql = 'select t.taste_memo from nb_order_product t where t.lid in (:orderId) and t.dpid=:dpid';
			$conn = Yii::app()->db->createCommand($sql);
			$conn->bindValue(':orderId',$orderlist);
                        $conn->bindValue(':dpid',$dpid);
		}
		$result = $conn->queryRow();
		return $result?$result['taste_memo']:'';
	}
	//保存订单口味
	public static function save($dpid, $type, $id = 0, $tastesIds = array(), $tastMemo=null){
		$transaction = Yii::app()->db->beginTransaction();
		try {
			//$sql = 'delete from nb_order_taste where dpid=:dpid and is_order=:type and order_id=:orderId';
                        $sql = 'update nb_order_taste set delete_flag="1" where dpid=:dpid and is_order=:type and order_id=:orderId';
			$conn = Yii::app()->db->createCommand($sql);
			$conn->bindValue(':dpid',$dpid);
			$conn->bindValue(':type',$type);
			$conn->bindValue(':orderId',$id);
			$conn->execute();
			
			if(!empty($tastesIds)){
				foreach($tastesIds as $taste){
					$sql = 'SELECT NEXTVAL("order_taste") AS id';
					$maxId = Yii::app()->db->createCommand($sql)->queryRow();
					$data = array(
					 'lid'=>$maxId['id'],
					 'dpid'=>$dpid,
					 'create_at'=>date('Y-m-d H:i:s',time()),
                                         'update_at'=>date('Y-m-d H:i:s',time()),
					 'taste_id'=>$taste,
					 'order_id'=>$id,
					 'is_order'=>$type
					);
					Yii::app()->db->createCommand()->insert('nb_order_taste',$data);
				}
			}
			if($tastMemo){
				if($type){
					$sql = 'update nb_order set taste_memo=:tastMemo where lid=:lid';
				}else{
					$sql = 'update nb_order_product set taste_memo=:tastMemo where lid=:lid';
				}
				$conn = Yii::app()->db->createCommand($sql);
				$conn->bindValue(':tastMemo',$tastMemo);
				$conn->bindValue(':lid',$id);
				$conn->execute();
			}
			$transaction->commit(); //提交事务会真正的执行数据库操作
			return true;
		} catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			return false;
		}
	}
	//保存产品口味
	public static function saveProductTaste($dpid,$productId,$tastesIds=array()){
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$sql = 'update nb_product_taste set delete_flag=1 where dpid=:dpid and product_id=:productId';
                        $conn = Yii::app()->db->createCommand($sql);
			$conn->bindValue(':dpid',$dpid);
			$conn->bindValue(':productId',$productId);
			$conn->execute();
			if(!empty($tastesIds)){
				foreach($tastesIds as $taste){
//					$sql = 'SELECT NEXTVAL("product_taste") AS id';
//					$maxId = Yii::app()->db->createCommand($sql)->queryRow();
                                        $se=new Sequence("product_taste");
                                        $lid = $se->nextval();
					$data = array(
					 'lid'=>$lid,
					 'dpid'=>$dpid,
					 'create_at'=>date('Y-m-d H:i:s',time()),
                                         'update_at'=>date('Y-m-d H:i:s',time()),
					 'taste_group_id'=>$taste,
					 'product_id'=>$productId,
					);
					Yii::app()->db->createCommand()->insert('nb_product_taste',$data);
				}
			}
			$transaction->commit(); //提交事务会真正的执行数据库操作
			return true;
		} catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			return false;
		}
	}
	public static function getTasteName($tasteId,$dpid){
		$sql = 'SELECT name from nb_taste_group where lid=:lid and dpid=:dpid and delete_flag="0"';
		$taste = Yii::app()->db->createCommand($sql)->bindValue(':lid',$tasteId)->bindValue(':dpid',$dpid)->queryRow();
		return $taste['name'];
	}
	
}