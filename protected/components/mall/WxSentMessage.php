<?php 
/**
 * 
 * 
 * 发生短信类
 * 
 * 
 */
class WxSentMessage
{
	/**
	 * 
	 * 发送手机短信
	 * 
	 */
	public static function sentMessage($mobile,$content = ''){
		$userid = '';
		$account = 'jksc344';
		$password = 'wymenu6688';
		$url = 'http://sh2.ipyy.com/smsJson.aspx?action=send&userid='.$userid.'&account='.$account.'&password='.$password.'&mobile='.trim($mobile).'&content='.$content.'&sendTime=&extno=';
		$result = Curl::httpsRequest($url);
		return $result;
	}
	/**
	 * 
	 * 记录短信
	 * 
	 */
	public static function insert($dpid,$mobile,$code,$type,$user_id){      
		$time = time();
		$se = new Sequence("mobile_message");
                $lid = $se->nextval();
		$insertArr = array(
			        	'lid'=>$lid,
			        	'dpid'=>$dpid,
			        	'create_at'=>date('Y-m-d H:i:s',$time),
			        	'update_at'=>date('Y-m-d H:i:s',$time),
                                        'user_id' => $user_id,
                                        'type'=>$type,
			        	'mobile'=>$mobile,
			        	'code'=>$code,
			        	'is_sync'=>DataSync::getInitSync(),
			        );
		$result = Yii::app()->db->createCommand()->insert('nb_mobile_message', $insertArr);
		return array('status'=>$result,'lid'=>$lid);
	}
	/**
	 * 
	 * 查找短信
	 * 
	 */
        public static function update($lid,$status){  
            $sql = 'UPDATE nb_mobile_message set status = '.$status .' WHERE  lid = ' .$lid  ;
            $result = Yii::app()->db->createCommand($sql)->execute(); 
        }      
	/**
	 * 
	 * 查找短信
	 * 
	 */
	public static function getCode($dpid,$mobile){
		$sql = 'select * from nb_mobile_message where dpid=:dpid and mobile=:mobile order by lid desc';
		$result = Yii::app()->db->createCommand($sql)
				  ->bindValue(':mobile',$mobile)
				  ->bindValue(':dpid',$dpid)
                                 
				  ->queryRow();
	    return $result;
	}
}