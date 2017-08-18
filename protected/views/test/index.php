<?php 
	$sql = 'select * from nb_pad_setting where delete_flag=0';
	$results = Yii::app ()->db->createCommand ( $sql )->queryAll();
	foreach ($results as $result){
		$sql = 'select * from nb_pad_setting_status where pad_setting_id='.$result['lid'].' and dpid='.$result['dpid'];
		$res = Yii::app ()->db->createCommand ( $sql )->queryRow ();
		if(!$res){
			$isSync = DataSync::getInitSync ();
			$se = new Sequence ( "pad_setting_status" );
			$lid = $se->nextval ();
			$data = array (
					'lid' => $lid,
					'dpid' => $result['dpid'],
					'create_at' => date ( 'Y-m-d H:i:s', time () ),
					'update_at' => date ( 'Y-m-d H:i:s', time () ),
					'pad_setting_id' => $result['lid'],
					'status' => '0',
					'use_status'=>0,
					'pad_no'=>1,
					'is_sync' => $isSync
			);
			$res = Yii::app()->db->createCommand ()->insert ( 'nb_pad_setting_status', $data );
		}
	}
	exit;
?>