<?php
class FeedBackClass
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
	
	public static function save($dpid,$siteNoId, $type, $id = 0, $feebackIds = 0, $feebackMemo=null){
		$sql = 'select * from nb_site_no where lid=:lid and dpid=:dpid';
		$conn = Yii::app()->db->createCommand($sql);
		$conn->bindValue(':lid',$siteNoId);
		$conn->bindValue(':dpid',$dpid);
		$siteNo = $conn->queryRow();
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
                                 'update_at'=>date('Y-m-d H:i:s',time()),
				 'site_id'=>$siteNo['site_id'],
				 'is_temp'=>$siteNo['is_temp'],
				 'is_deal'=>0,
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
	
        public static function getSiteGroupMessage($companyId)
	{
		$sql = 'select site_id,is_temp,"" as name,max(create_at) as ltime,count(*) as lcount from nb_order_feedback where dpid=:dpid and is_deal=0 and delete_flag=0 group by site_id,is_temp,name having lcount>0 order by ltime';
                $conn = Yii::app()->db->createCommand($sql);
                $conn->bindValue(':dpid',$companyId);                
                $results = $conn->queryAll();
		return $results;
	}
        
        public static function cancelAllOrderMsg($siteId,$istemp,$orderId,$companyId)
	{
            $transaction = Yii::app()->db->beginTransaction();
            try {
                    if($siteId!="0000000000")
                    {
                        $sqlsite = 'update nb_order_feedback set is_deal=1 where dpid=:dpid and site_id=:siteId and is_temp=:istemp and is_order=1';
                        $conn = Yii::app()->db->createCommand($sqlsite);
                        $conn->bindValue(':dpid',$companyId);
                        $conn->bindValue(':siteId',$siteId);
                        $conn->bindValue(':istemp',$istemp);
                        //var_dump($sqlsite);exit;
                        $result = $conn->excute();
                    }
                    if($orderId!="0000000000")
                    {
                        $sqlall = 'update nb_order_feedback set is_deal=1 where dpid=:dpid and order_id=:orderId and is_order=1';
                        $conn = Yii::app()->db->createCommand($sqlall);
                        $conn->bindValue(':dpid',$companyId);
                        $conn->bindValue(':orderId',$orderId);
                        $result = $conn->execute();

                        $sql = 'update nb_order_feedback set is_deal=1 where dpid=:dpid and order_id in (select lid from nb_order_product where dpid=:sdpid and order_id=:sorderId) and is_order=0';
                        $conn = Yii::app()->db->createCommand($sql);
                        $conn->bindValue(':dpid',$companyId);
                        $conn->bindValue(':sdpid',$companyId);
                        $conn->bindValue(':sorderId',$orderId);
                        $result = $conn->execute();
                    }
                    $transaction->commit(); //提交事务会真正的执行数据库操作
                    
                    //return true;
		} catch (Exception $e) {
                    $transaction->rollback(); //如果操作失败, 数据回滚
                    throw $e;
                    //return false;
		}            
	}
        
        public static function getFeedbackSite($companyId,$siteId,$istemp,$orderId,$isOrder)
	{
            if($siteId!=='0000000000')
            {
               return SiteClass::getSiteNmae($companyId, $siteId, $istemp);
            }
            if($orderId!=='0000000000')
            {
                if($isOrder=='1')
                {
                    $order=Order::model()->find(" dpid=:dpid and lid=:orderid",array(':dpid'=>$companyId,':orderid'=>$orderId));
                    return SiteClass::getSiteNmae($companyId, $order->site_id, $order->istemp);
                }else{
                    $orderProduct=  OrderProduct::model()->with("order")->find(" dpid=:dpid and lid=:orderid",array(':dpid'=>$companyId,':orderid'=>$orderId));
                    return SiteClass::getSiteNmae($companyId, $orderProduct->order->site_id, $orderProduct->order->istemp);
                }
            }
            
	}
        
        public static function getFeedbackName($feedbackId,$companyId)
	{
            if($feedbackId=='0000000000')
            {
                return yii::t('app','系统消息');
            }else{
		$sql = 'select name from nb_feedback where dpid=:dpid and lid=:feedbackId';
                $conn = Yii::app()->db->createCommand($sql);
                $conn->bindValue(':dpid',$companyId);
                $conn->bindValue(':feedbackId',$feedbackId);
                $result = $conn->queryScalar();
		return $result;
            }
	}
        
        public static function getFeedbackObject($orderId,$isOrder,$companyId)
	{
            if($isOrder=='1')
            {
                return yii::t('app','全单消息');
            }else{
                $sql = 'select t.product_name from nb_product t,nb_order_product t1 where t.dpid=t1.dpid and t.lid=t1.product_id and t1.dpid=:dpid and t1.lid=:orderId';
                $conn = Yii::app()->db->createCommand($sql);
                $conn->bindValue(':dpid',$companyId);
                $conn->bindValue(':orderId',$orderId);
                $result = $conn->queryScalar();
		return $result;
            }
	}
}