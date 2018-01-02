<?php 
/**
 * 
 * 
 * 获取微信充值模板
 * 
 * 
 */
class WxRecharge
{
	public function __construct($rechargeId,$dpid,$userId){
		$this->rechargeId = $rechargeId;
		$this->dpid = $dpid;
		$this->userId = $userId;
		$transaction = Yii::app()->db->beginTransaction();
 		try {
			$this->getRecharge();
			$this->recharge();
			$this->updateBrandUser();
			$this->getPointsValid();
			$this->insertPoints();
			$transaction->commit();	
		 } catch (Exception $e) {
            $transaction->rollback(); //如果操作失败, 数据回滚
            throw new Exception($e->getMessage());
        } 
	}
	public function getRecharge(){
		$sql = 'select * from nb_weixin_recharge where lid=:lid and dpid=:dpid and is_available=0 and delete_flag=0';
		$this->recharge = Yii::app()->db->createCommand($sql)
						  ->bindValue(':dpid',$this->dpid)
						  ->bindValue(':lid',$this->rechargeId)
						  ->queryRow();
	}
	/**
	 * 
	 * 充值记录
	 * 
	 */
	 public function recharge(){
	 	$time = time();
	 	$se = new Sequence("recharge_record");
        $lid = $se->nextval();
        $insertDataArr = array(
        	'lid'=>$lid,
        	'dpid'=>$this->dpid,
        	'create_at'=>date('Y-m-d H:i:s',$time),
        	'update_at'=>date('Y-m-d H:i:s',$time),
        	'recharge_lid'=>$this->rechargeId,
        	'recharge_money'=>$this->recharge['recharge_money'],
        	'cashback_num'=>$this->recharge['recharge_cashback'],
        	'point_num'=>$this->recharge['recharge_pointback'],
        	'brand_user_lid'=>$this->userId,
        	'is_sync'=>DataSync::getInitSync(),	
        	);
       $result = Yii::app()->db->createCommand()->insert('nb_recharge_record', $insertDataArr);
       if(!$result){
       		throw new Exception('插入记录失败!');
       }
	 }
	 /**
	  * 
	  *更改会员信息 
	  * 
	  */
	  public function updateBrandUser(){
		  $isSync = DataSync::getInitSync();
		  $sql = 'update nb_brand_user set remain_money = remain_money + '.$this->recharge['recharge_money'].',remain_back_money = remain_back_money + '.$this->recharge['recharge_cashback'].',is_sync='.$isSync.' where lid='.$this->userId.' and dpid='.$this->dpid;
		  $result = Yii::app()->db->createCommand($sql)->execute();
		  if(!$result){
       		throw new Exception('更新会员余额失败!');
       	   }
	  }
	/**
	 * 
	 * 
	 * 获取积分有效期
	 * 
	 */
	 public function getPointsValid(){
	 	$sql = 'select * from nb_points_valid where dpid='.$this->dpid.' and is_available=0 and delete_flag=0';
		$this->pointsValid = Yii::app()->db->createCommand($sql)->queryRow();
	 }
 	 /**
   	* 
  	 * 插入积分、返现记录
  	 * 
   	* 
   	*/
   	public function insertPoints(){
   	   $time = time();
   	   if($this->recharge['recharge_pointback']){
   	   		if($this->pointsValid){
				$endTime = date('Y-m-d H:i:s',strtotime('+'.$this->pointsValid['valid_days'].' day'));
			}else{
				$endTime = date('Y-m-d H:i:s',strtotime('+1 year'));
			}
			$se = new Sequence("member_points");
		    $lid = $se->nextval();
			$pointRecordData = array(
								'lid'=>$lid,
					        	'dpid'=>$this->dpid,
					        	'create_at'=>date('Y-m-d H:i:s',$time),
					        	'update_at'=>date('Y-m-d H:i:s',$time),
					        	'card_type'=>1,
								'card_id'=>$this->userId,
								'point_resource'=>1,
					        	'resource_id'=>$this->rechargeId,
					        	'points'=>$this->recharge['recharge_pointback'],
					        	'remain_points'=>$this->recharge['recharge_pointback'],
					        	'end_time'=>$endTime,
					        	'is_sync'=>DataSync::getInitSync(),
								);
			$result = Yii::app()->db->createCommand()->insert('nb_member_points', $pointRecordData);
			if(!$result){
       			throw new Exception('插入积分失败!');
       	   }
   	   }
   	   
   	   if($this->recharge['recharge_cashback']){
			$se = new Sequence("cashback_record");
		    $lid = $se->nextval();
			$pointRecordData = array(
								'lid'=>$lid,
					        	'dpid'=>$this->dpid,
					        	'create_at'=>date('Y-m-d H:i:s',$time),
					        	'update_at'=>date('Y-m-d H:i:s',$time),
					        	'point_type'=>1,
					        	'type_lid'=>$this->rechargeId,
					        	'cashback_num'=>$this->recharge['recharge_cashback'],
					        	'remain_cashback_num'=>$this->recharge['recharge_cashback'],
					        	'brand_user_lid'=>$this->userId,
					        	'is_sync'=>DataSync::getInitSync(),
								);
			$result = Yii::app()->db->createCommand()->insert('nb_cashback_record', $pointRecordData);
			if(!$result){
       			throw new Exception('插入返现失败!');
       	   }
   	   }
   	}
   	public static function getWxRechargeComment($dpid,$type,$useType){
   		$sql = 'select * from nb_announcement where dpid=:dpid and type=:type and use_type=:userType and delete_flag=0';
   		$comments = Yii::app()->db->createCommand($sql)
			   		->bindValue(':dpid',$dpid)
			   		->bindValue(':type',$type)
			   		->bindValue(':userType',$useType)
			   		->queryAll();
   		return $comments;
   	}  
	/**
	 * 
	 * 获取微信充值模板
	 * 
	 */
	public static function getWxRecharge($dpid){
		$sql = 'select * from nb_weixin_recharge where dpid=:dpid and is_available=0 and delete_flag=0';
		$recharges = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->queryAll();
	    return $recharges;		  
	}
	/**
	 * 
	 * 获取充值记录
	 * 
	 */
	public static function getRechargeRecord($dpid,$userId,$page = 0){
		$sql = 'select * from nb_recharge_record where dpid=:dpid and brand_user_lid=:userId and delete_flag=0 order by lid desc limit '. $page*10 .', 10';
		$recharges = Yii::app()->db->createCommand($sql)
		->bindValue(':dpid',$dpid)
		->bindValue(':userId',$userId)
		->queryAll();
		return $recharges;
	}
	/**
	 *
	 * 获取消费记录
	 *
	 */
	public static function getConsumeRecord($userId,$page = 0){
		$sql = 'select * from nb_member_consume_record where type=2 and card_id=:userId and delete_flag=0 order by lid desc limit '. $page*10 .', 10';
		$recharges = Yii::app()->db->createCommand($sql)
		->bindValue(':userId',$userId)
		->queryAll();
		return $recharges;
	}
}