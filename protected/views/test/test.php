<?php 
Yii::app()->cache->flush();
$dpid = 27;
$adminId = 362;
$poscode = '04480027819609';
$syncData = DataSyncOperation::getAllSyncFailure($dpid);
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
			$result = DataSyncOperation::operateOrder($pData);
		}elseif($type==4){
			// 退款
			$contentArr = explode('::', $content);
			$createAt = isset($contentArr[7])?$contentArr[7]:'';
			$pData = array('sync_lid'=>$syncLid,'dpid'=>$dpid,'admin_id'=>$adminId,'poscode'=>$poscode,'account'=>$contentArr[1],'username'=>$contentArr[2],'retreatid'=>$contentArr[3],'retreatprice'=>$contentArr[4],'pruductids'=>$contentArr[5],'memo'=>$contentArr[6],'retreattime'=>$createAt,'data'=>$content);
			$result = DataSyncOperation::retreatOrder($pData);
		}elseif($type==3){
			// 增加会员卡
			$pData = array('sync_lid'=>$lid,'dpid'=>$dpid,'is_pos'=>1,'posLid'=>$padLid,'data'=>$content);
			$result = DataSyncOperation::addMemberCard($pData);
		}elseif($type==5){
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
		var_dump($resObj);
		if($resObj->status){
			DataSyncOperation::delSyncFailure($lid,$dpid);
		}else{
			Helper::writeLog('再次同步失败:同步内容:'.$dpid.json_encode($sync).'错误信息:'.$resObj->msg);
		}
	}
}

?>

