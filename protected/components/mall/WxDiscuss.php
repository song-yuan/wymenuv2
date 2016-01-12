<?php 
/**
 * 
 * 
 * 大屏类
 * 
 * 
 */
class WxDiscuss
{
	public static function get($dpid){
		$sql = 'select * from nb_discuss where dpid=:dpid and show_flag=0 and delete_flag=0';
		$discusses = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->queryAll();
		foreach($discusses as $discus){
			self::showDiscuss($discus['lid'],$discus['dpid']);
		}
	    return $discusses;
	}
	
	public static function insert($param){
		$time = time();
		$se = new Sequence("discuss");
	    $lid = $se->nextval();
		$insertData = array(
							'lid'=>$lid,
				        	'dpid'=>$param['dpid'],
				        	'create_at'=>date('Y-m-d H:i:s',$time),
				        	'update_at'=>date('Y-m-d H:i:s',$time), 
				        	'branduser_lid'=>$param['user_id'],
				        	'content'=>$param['content'],
				        	'is_sync'=>DataSync::getInitSync(),
							);
		$result = Yii::app()->db->createCommand()->insert('nb_discuss', $insertData);
		return $result;
	}
	public static function showDiscuss($lid,$dpid){
		$isSync = DataSync::getInitSync();
		$sql = 'update nb_discuss set show_flag=1,is_sync='.$isSync.' where dpid='.$dpid.' and lid='.$lid;
		$result = Yii::app()->db->createCommand($sql)->execute();
	}
}