<?php 
	
	// 获取云端失败数据
	$sql = 'select * from nb_sync_failure where delete_flag=0 limit 1';
	$syncArr = Yii::app ()->db->createCommand ( $sql )->queryAll ();
	if(!empty($syncArr)){
		foreach ($syncArr as $sync){
			$lid = $sync['lid'];
			$dpid = $sync['dpid'];
			$orderData = $sync['content'];
			$orderDataArr = json_decode($orderData,true);
			if(!is_array($orderDataArr)){
				continue;
			}
			$type = $orderDataArr['type'];
			if($type==2){
				// 新增订单
				$result = DataSyncOperation::operateOrder($orderDataArr);
			}elseif($type==4){
				// 退款
				$result = DataSyncOperation::retreatOrder($orderDataArr);
			}elseif($type==3){
				// 增加会员卡
				$result = DataSyncOperation::addMemberCard($orderDataArr);
			}elseif($type==5){
				$content = $orderDataArr['data'];
				$contentArr = explode('::', $content);
				$rjDpid = $contentArr[0];
				$rjUserId = $contentArr[1];
				$rjCreateAt = $contentArr[2];
				$rjPoscode = $contentArr[3];
				$rjBtime = $contentArr[4];
				$rjEtime = $contentArr[5];
				$rjcode = $contentArr[6];
				$result = WxRiJie::setRijieCode($rjDpid,$rjCreateAt,$rjPoscode,$rjBtime,$rjEtime,$rjcode);
			}
			$resObj = json_decode($result);
			if($resObj->status){
				DataSyncOperation::delSyncFailure($lid,$dpid);
			}else{
				Helper::writeLog('再次同步失败:同步内容:'.$dpid.json_encode($sync).'错误信息:'.$resObj->msg);
			}
		}
	}

?>


