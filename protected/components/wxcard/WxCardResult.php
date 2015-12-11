<?php
/**
 * MassResult.php
 * 微信群发结果处理类
 * 1.记录微信服务器推送过来的群发结果数据
 */
 
class WxCardResult {
	public static function cardPassCheck(Array $postArr){
		$sql = 'update nb_weixin_card set status=1 where card_id = "'.$postArr['CardId'].'"';
		Yii::app()->db->createCommand($sql)->execute();
	}
	
	public static function cardNotPassCheck(Array $postArr){
		$sql = 'update nb_weixin_card set status=2 where card_id = "'.$postArr['CardId'].'"';
		Yii::app()->db->createCommand($sql)->execute();
	}
	
	public static function getCard(Array $postArr,$dpid){
		$time = time();
		$se = new Sequence("weixin_card_user");
	    $lid = $se->nextval();
		$data = array(
					'lid'=>$lid,
		        	'dpid'=>$dpid,
		        	'create_at'=>date('Y-m-d H:i:s',$time),
		        	'update_at'=>date('Y-m-d H:i:s',$time), 
		            'from_user_name'=>$postArr['FromUserName'],
		            'friend_user_name'=>$postArr['FriendUserName'],
		            'card_id'=>$postArr['CardId'],
		            'is_giveby_friend'=>$postArr['IsGiveByFriend'],
		            'user_card_code'=>$postArr['UserCardCode'],
		            'create_time'=>$postArr['CreateTime'],
		            'outer_id'=>$postArr['OuterId'],
		            'is_sync'=>DataSync::getInitSync(),	
		             );
		Yii::app()->db->createCommand()->insert('nb_weixin_card_user',$data);
	}
	
	public static function delCard(Array $postArr){
		$sql = 'update nb_weixin_card set delete_flag=1 where card_id = "'.$postArr['CardId'].'" and user_card_code='.$postArr['UserCardCode'];
		Yii::app()->db->createCommand($sql)->execute();
	}
}
 
 
?>