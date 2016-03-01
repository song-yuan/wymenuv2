<?php 
/**
 * 
 * 
 * 公司基础费用类
 * 
 * $type表示费用类型 1表示餐位费，2表示打包费，3表示运费，4外卖起步价。
 * 
 */
class WxCompanyFee
{
	public static function get($type,$dpid){
		$sql = 'select * from nb_company_basic_fee where dpid=:dpid and fee_type=:type and delete_flag=0';
		$companyFee = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':type',$type)
				  ->queryRow();
	    return $companyFee;
	}
}