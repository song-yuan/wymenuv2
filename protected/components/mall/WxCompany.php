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
		$sql = 'select t.*,t1.material_pay_type,t1.posfee_pay_type,t1.pay_type,t1.pay_channel,t1.appId,t1.code,t1.qr_code,t1.is_rest,t1.sale_type,t1.rest_message,t1.shop_time,t1.closing_time,t1.wm_shop_time,t1.wm_closing_time from nb_company t left join nb_company_property t1 on t.dpid=t1.dpid where t.dpid=:dpid and t.delete_flag=0 and t1.delete_flag=0';
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
			$company = array('material_pay_type'=>'1','posfee_pay_type'=>'1','pay_type'=>'0','pay_channel'=>'0');
		}
		return $company;
	}
	// 获取总部的字店铺
	public static function getCompanyChildren($dpid){
		$sql = 'select t.*,t1.material_pay_type,t1.posfee_pay_type,t1.pay_type,t1.pay_channel,t1.appId,t1.code,t1.qr_code,t1.is_rest,t1.rest_message,t1.shop_time,t1.closing_time from nb_company t left join nb_company_property t1 on t.dpid=t1.dpid where t.comp_dpid=:dpid and t.type=1 and t.delete_flag=0 and t1.delete_flag=0';
		$companys = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->queryAll();
		return $companys;
	}
	// 获取总部的字店铺
	public static function getCompanyChildrenPage($dpid,$type,$lat,$lng,$page,$keyword){
		$sql = 'select t.dpid,t.company_name,t.telephone,t.province,t.city,t.county_area,t.address,t.lng,t.lat,round(6378.138*2*asin(sqrt(pow(sin( ('.$lat.'*pi()/180-lat*pi()/180)/2),2)+cos('.$lat.'*pi()/180)*cos(lat*pi()/180)* pow(sin( ('.$lng.'*pi()/180-lng*pi()/180)/2),2)))*1000) as juli,t1.pay_type,t1.pay_channel,t1.appId,t1.code,t1.qr_code,t1.is_rest,t1.rest_message,t1.shop_time,t1.closing_time,t1.wm_shop_time,t1.wm_closing_time from nb_company t left join nb_company_property t1 on t.dpid=t1.dpid';
		$sql .=' where t.comp_dpid=:dpid and t.type=1 and t1.is_rest=3 and t.delete_flag=0 and t1.delete_flag=0';
		if($type==2){
			$sql .= ' and t1.sale_type in(1,3)';
		}else{
			$sql .= ' and t1.sale_type in(1,2)';
		}
		if($keyword!=''){
			$sql .= ' and t.company_name like "%'.$keyword.'%"';
		}
		$sql .= '  order by juli asc limit ' . $page*10 . ', 10';
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
	// 查出总部下所有店铺
	public static function getAllDpids($dpid){
		$coompany = self::get($dpid);
		$comDpid = $coompany['comp_dpid'];
		if($comDpid==0){
			$comDpid = $dpid;
		}
		$sql = 'select dpid from nb_company where (comp_dpid='.$comDpid.' or (dpid='.$dpid.' and comp_dpid=0)) and delete_flag=0';
		$dpids = Yii::app()->db->createCommand($sql)->queryColumn();
		$dpidJoin = join(',',$dpids);
		return $dpidJoin;
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
	/**
	 * 
	 * 获取店铺的收钱吧支付内容
	 * 
	 */
	public static function getSqbPayinfo($dpid,$poscode = null){
		if($poscode){
			$sql = 'select * from nb_sqb_possetting where dpid='.$dpid.' and device_id="'.$poscode.'" and delete_flag=0 ';
		}else{
			$sql = 'select * from nb_sqb_possetting where dpid='.$dpid.' and delete_flag=0 order by lid desc';
		}
		$sqbinfor = Yii::app()->db->createCommand($sql)
			->queryRow();
		return $sqbinfor;
	}
}