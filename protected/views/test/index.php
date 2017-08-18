<?php 
	$sql = 'select * from nb_pad_setting where delete_flag=0';
	$results = Yii::app ()->db->createCommand ( $sql )->queryAll();
	foreach ($results as $result){
		$sql = 'select * from nb_pad_setting_detail where pad_setting_id='.$result['lid'].' and dpid='.$result['dpid'].' order by lid desc limit 1';
		$res = Yii::app ()->db->createCommand ( $sql )->queryRow();
		if($res){
			$sql = 'update nb_pad_setting_status set used_at="'.$res['create_at'].'" where dpid='.$result['dpid'].' and pad_setting_id='.$result['lid'];
			Yii::app ()->db->createCommand ( $sql )->execute();
		}
	}
	exit;
?>