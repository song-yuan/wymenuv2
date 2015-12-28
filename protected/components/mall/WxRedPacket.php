<?php 
/**
 * 
 * 
 * 微信 分享送红包类
 *
 * 
 * 
 */
class WxRedPacket
{
	public static function getRedPacketList($dpid){
		$now = date('Y-m-d H:i:s',time());
		$sql = 'select t.*,t1.min_money,t1.max_money,t1.send_type from nb_redpacket t,nb_redpacket_send_strategy t1 where t.lid=t1.redpacket_lid and t.dpid=t1.dpid and t.dpid=:dpid and :now <= t.end_time and t.delete_flag=0 and t1.is_available=0 and t1.delete_flag=0';
		$packets = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':now',$now)
				  ->queryAll();
	    return $packets;
	}
	public static function getRedPacket($dpid,$redPacketId){
		$now = date('Y-m-d H:i:s',time());
		$sql = 'select t.*,t1.min_money,t1.max_money,t1.send_type from nb_redpacket t,nb_redpacket_send_strategy t1 where t.lid=t1.redpacket_lid and t.dpid=t1.dpid and t.lid=:lid and t.dpid=:dpid and :now <= t.end_time and t.delete_flag=0 and t1.is_available=0 and t1.delete_flag=0';
		$packet = Yii::app()->db->createCommand($sql)
				  ->bindValue(':lid',$redPacketId)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':now',$now)
				  ->queryRow();
	    return $packet;
	}
	public static function getRedPacketDetail($dpid,$redPacketId){
		$sql = 'select * from nb_redpacket_detail where dpid=:dpid and redpacket_lid=:redPacketId and delete_flag=0';
		$packetDetails = Yii::app()->db->createCommand($sql)
				  ->bindValue(':redPacketId',$redPacketId)
				  ->bindValue(':dpid',$dpid)
				  ->queryAll();
		foreach($packetDetails as $k=>$detail){
			if($detail['promotion_type']==1){
				$promote = WxPromotion::getPromotion($dpid,$detail['promotion_lid']);
				$packetDetails[$k]['item'] = $promote;
			}else{
				$cupon = WxCupon::getCupon($dpid,$detail['promotion_lid']);
				$packetDetails[$k]['item'] = $cupon;
			}
		}
	    return $packetDetails;
	}
	//订单分享红包 获取对所有人都最新的
	public static function getOrderShareRedPacket($dpid,$total){
		$now = date('Y-m-d H:i:s',time());
		$sql = 'select t.*,t1.min_money,t1.max_money,t1.send_type from nb_redpacket t,nb_redpacket_send_strategy t1 where t.lid=t1.redpacket_lid and t.dpid=t1.dpid and t.dpid=:dpid and :now <= t.end_time and t.delete_flag=0 and t1.min_money <= :total and t1.is_available=0 and t1.delete_flag=0 order by lid desc';
		$packet = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':now',$now)
				  ->bindValue(':total',$total)
				  ->queryRow();
	    return $packet;
	}
	//发送该红包
	public static function sent($userId,$dpid,$redPacket,$redPacketDetails){
		$now = date('Y-m-d H:i:s',time());
		$total = $redPacket['total'];
		$transaction = Yii::app()->db->beginTransaction();
		try{
			foreach($redPacketDetails as $detail){
				$type = $detail['promotion_type'];
				$sentTotal = self::getUserRedPacket($type,$userId,$detail['redpacket_lid'],$total);
				if(!$sentTotal){
					return false;
				}
				if($type==1){
					$se = new Sequence("private_branduser");
					$lid = $se->nextval();
					$data = array(
							'lid'=>$lid,
				        	'dpid'=>$dpid,
				        	'create_at'=>$now,
				        	'update_at'=>$now,
				        	'private_promotion_id'=>$detail['promotion_lid'],
				        	'to_group'=>3,
				        	'brand_user_lid'=>$userId,
				        	'cupon_source'=>1,
				        	'source_id'=>$detail['redpacket_lid'],
				        	'is_used'=>1,
				        	'get_time'=>$now,
				        	'is_sync'=>DataSync::getInitSync(),
							);
					$result = Yii::app()->db->createCommand()->insert('nb_private_branduser', $data);
				}elseif($type==0){
					$se = new Sequence("cupon_branduser");
					$lid = $se->nextval();
					$data = array(
							'lid'=>$lid,
				        	'dpid'=>$dpid,
				        	'create_at'=>$now,
				        	'update_at'=>$now,
				        	'cupon_id'=>$detail['promotion_lid'],
				        	'to_group'=>3,
				        	'brand_user_lid'=>$userId,
				        	'cupon_source'=>1,
				        	'source_id'=>$detail['redpacket_lid'],
				        	'is_used'=>1,
				        	'is_sync'=>DataSync::getInitSync(),
							);
					$result = Yii::app()->db->createCommand()->insert('nb_cupon_branduser', $data);
				}
			}
			$transaction->commit();
		}catch(Exception $e){
			 $transaction->rollBack();
		}
		
	}
	// 查询该红包的领取情况
	public static function getUserRedPacket($type,$userId,$redPacketId,$total){
		if($type==1){
			$sql = 'select * from nb_private_branduser where to_group=3 and brand_user_lid='.$userId.' and cupon_source=1 and source_id='.$redPacketId;
		}elseif($type==0){
			$sql = 'select * from nb_cupon_branduser where to_group=3 and brand_user_lid='.$userId.' and cupon_source=1 and source_id='.$redPacketId;
		}
		$brandUser = Yii::app()->db->createCommand($sql)->queryRow();
		
		if($type==1){
			$sql = 'select * from nb_private_branduser where cupon_source=1 and source_id='.$redPacketId;
		}elseif($type==0){
			$sql = 'select * from nb_cupon_branduser where cupon_source=1 and source_id='.$redPacketId;
		}
		$brandUsers = Yii::app()->db->createCommand($sql)->queryAll();
		
		if($brandUser || ($total >= count($brandUsers))){
			return false;
		}else{
			return true;
		}
	}
}