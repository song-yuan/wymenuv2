<?php
class FeedBack
{
	//全订单反馈 1 整单 0 非整单
	public static function getAllFeeBack($dpid,$type){
		$sql = 'select lid,name,tip from nb_feedback where dpid=:dpid and allflag=:allflae and delete_flag=0';
		$conn = Yii::app()->db->createCommand($sql);
		$conn->bindValue(':dpid',$dpid);
		$conn->bindValue(':allflae',$type);
		$result = $conn->queryAll();
		return $result;
	}
	
	//订单反馈 type = 1 全单反馈 2 订单产品反馈
	public static function getOrderFeeBack($orderId,$type){
		if($type==1){
			$sql = 'select t.feedback_id, feedback_memo from nb_order_feedback t where t.order_id=:orderId and t.is_order=1';
			$conn = Yii::app()->db->createCommand($sql);
			$conn->bindValue(':orderId',$orderId);
		}elseif($type==2){
			$sql = 'select t.feedback_id, feedback_memo from nb_order_feedback t where t.order_id=:orderId and t.is_order=0';
			$conn = Yii::app()->db->createCommand($sql);
			$conn->bindValue(':orderId',$orderId);
		}
		$results = $conn->queryAll();
		return $results;
	}
	
	public static function save($dpid, $type, $id = 0, $feebackIds = 0, $feebackMemo=null){
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$sql = 'delete from nb_order_feedback where dpid=:dpid and feedback_id=:feedbackId and is_order=:type and order_id=:orderId';
			$conn = Yii::app()->db->createCommand($sql);
			$conn->bindValue(':dpid',$dpid);
			$conn->bindValue(':type',$type);
			$conn->bindValue(':feedbackId',$feebackIds);
			$conn->bindValue(':orderId',$id);
			$conn->execute();
			
			if($feebackIds){
				$sql = 'SELECT NEXTVAL("order_feedback") AS id';
				$maxId = Yii::app()->db->createCommand($sql)->queryRow();
				$data = array(
				 'lid'=>$maxId['id'],
				 'dpid'=>$dpid,
				 'create_at'=>date('Y-m-d H:i:s',time()),
				 'feedback_id'=>$feebackIds,
				 'order_id'=>$id,
				 'is_order'=>$type,
				 'feedback_memo'=>$feebackMemo,
				);
				Yii::app()->db->createCommand()->insert('nb_order_feedback',$data);
			}
			$transaction->commit(); //提交事务会真正的执行数据库操作
			return true;
		} catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			return false;
		}
	}
	
}