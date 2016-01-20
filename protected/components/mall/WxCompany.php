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
				  ->queryAll();
		if(!$company){
			throw new Exception('不存在该公司信息');
		}
	    return $company;
	}
}