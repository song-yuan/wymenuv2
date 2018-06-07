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
	 **/
	public static function sentMessage($dpid,$mobile,$type,$userId,$content = ''){
		$account = 'jksc344';
		$password = 'wymenu6688';
		$url = 'http://sh2.ipyy.com/smsJson.aspx?action=send&userid='.$userId.'&account='.$account.'&password='.$password.'&mobile='.trim($mobile).'&content='.$content.'&sendTime=&extno=';
		$result = Curl::httpsRequest($url);
		$resObj = json_decode($result);
		if($resObj->returnstatus=='Success'){
			// 发送成功
			self::insert($dpid, $mobile, $type, $userId, $content);
			$res = true;
		}else{
			$res = false;
		}
		return $res;
	}
	/**
	 * 
	 * 记录短信
	 * 
	 **/
	public static function insert($dpid,$mobile,$type,$user_id,$content){      
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
			        	'code'=>0,
						'status'=>1,
						'comment'=>$content,
			        );
		$result = Yii::app()->db->createCommand()->insert('nb_mobile_message', $insertArr);
		return array('status'=>$result,'lid'=>$lid);
	}
}