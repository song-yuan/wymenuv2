<?php 
/**
 * 
 * 
 * 微信端会员地址类
 * 
 * 
 */
class WxAddress
{
	public static function get($userId,$dpid){
		$sql = 'select * from nb_address where brand_user_lid=:userId and dpid=:dpid and delete_flag=0';
		$site = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
				  ->bindValue(':dpid',$dpid)
				  ->queryAll();
	    return $site;
	}
	public static function insert($param){
		$time = time();
		$se = new Sequence("address");
	    $lid = $se->nextval();
		$insertData = array(
							'lid'=>$lid,
				        	'dpid'=>$param['dpid'],
				        	'create_at'=>date('Y-m-d H:i:s',$time),
				        	'update_at'=>date('Y-m-d H:i:s',$time), 
				        	'brand_user_lid'=>$param['user_id'],
				        	'name'=>$param['name'],
				        	'province'=>$param['province'],
				        	'city'=>$param['city'],
				        	'area'=>$param['area'],
				        	'street'=>$param['street'],
				        	'postcode'=>$param['postcode'],
				        	'mobile'=>$param['mobile'],
				        	'default_address'=>isset($param['default_address'])?1:0,
							);
		$result = Yii::app()->db->createCommand()->insert('nb_address', $insertData);
		return $result;
	}
}