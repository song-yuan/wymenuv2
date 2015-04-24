<?php
class SiteClass
{
	public static function getTypes($companyId){
		$types = SiteType::model()->findAll('dpid=:companyId and delete_flag=0' , array(':companyId' => $companyId)) ;
		$types = $types ? $types : array();
		return CHtml::listData($types, 'lid', 'name');
	}
        
         public static function getRandChar($length){
            $str = null;
            $strPol = "0123456789";
            $max = strlen($strPol)-1;

            for($i=0;$i<$length;$i++){
                $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
            }
            return $str;
        }
        
        public static function getCode($companyId){
		$code= SiteClass::getRandChar(6);
                //var_dump($code);exit;
                //return $code;/*apc should be deleted*/
                if(Yii::app()->params->has_cache)
                {
                    $ccode = apc_fetch($companyId.$code);
                    while(!empty($ccode))
                    {
                        $code= $this->getRandChar(6);                    
                        $ccode = apc_fetch($companyId.$code);
                    }
                    apc_store($companyId.$code,'1',0);//永久存储用apc_delete($key)删除
                }
                return $code;
	}
        
        public static function deleteCode($companyId,$code){
		
                if(Yii::app()->params->has_cache)
                {
                    $ccode = apc_delete($companyId.$code);                    
                }
                return;
	}
        
        public static function getSiteNmae($companyId,$id,$istemp){
		if($istemp)
                {
                    return '临时座位：'.$id%1000;
                }else{
                    $site=Site::model()->with('siteType')->find(' t.dpid=:dpid and t.lid=:lid',array(':dpid'=>$companyId,':lid'=>$id));
                    //var_dump($site);exit;
                    return $site->siteType->name.': '.$site->serial;
                }                
	}
}