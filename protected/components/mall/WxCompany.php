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
	// 获取公司的信息
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
	public static function getpaychannel($dpid){
		$sql = 'select * from nb_company_property where dpid=:dpid';
		$company = Yii::app()->db->createCommand($sql)
		->bindValue(':dpid',$dpid)
		->queryRow();
		if(!$company){
			$company = array('pay_channel'=>'0');
		}
		return $company;
	}
	// 获取总部的字店铺
	public static function getCompanyChildren($dpid){
		$sql = 'select * from nb_company where comp_dpid=:dpid and type=1';
		$companys = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->queryAll();
		return $companys;
	}
	//  获取总部的 dpid
	public static function getCompanyDpid($dpid){
		$company = self::get($dpid);
		return $company['comp_dpid'];
	}
	// 查询出公司 会员密码一致的 拼接起来
	public static function getDpids($dpid){
		$coompany = self::get($dpid);
		$memCode = $coompany['membercard_code'];
		if($memCode!=''){
			$comDpid = $coompany['comp_dpid'];
			if($comDpid==0){
				$comDpid = $dpid;
			}
			$sql = 'select dpid from nb_company where (comp_dpid='.$comDpid.' or (dpid='.$dpid.' and comp_dpid=0)) and membercard_code="'.$memCode.'" and delete_flag=0';
			$dpids = Yii::app()->db->createCommand($sql)->queryColumn();
			$dpidJoin = join(',',$dpids);
		}else{
			$dpidJoin = $dpid;
		}
		return $dpidJoin;
	}
}