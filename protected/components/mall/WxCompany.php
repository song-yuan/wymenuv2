<?php 
/**
 * 
 * 
 * 公司类
 * 
 * 
 */
class WxCompany
{
	public static function get($dpid){
		$sql = 'select * from nb_company where dpid=:dpid';
		$company = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->queryRow();
		if(!$company){
			throw new Exception('不存在该公司信息');
		}
	    return $company;
	}
	public static function getDpids($dpid){
		$coompany = self::get($dpid);
		$memCode = $coompany['membercard_code'];
		$sql = 'select dpid from nb_company where membercard_code="'.$memCode.'" and delete_flag=0';
		$dpids = Yii::app()->db->createCommand($sql)->queryColumn();
		$dpidJoin = join(',',$dpids);
		return $dpidJoin;
	}
}