<?php
/**
 * MassResult.php
 * 微信群发结果处理类
 * 1.记录微信服务器推送过来的群发结果数据
 */
 
class GetWxCard {
	public static function get($brandId){
		$time = time();
		$sql = 'select * from nb_weixin_card where dpid = '.$brandId.' and ((date_info_type=1 and begin_timestamp<'.$time.' and end_timestamp>'.$time.') or date_info_type=2)  and delete_flag = 0';
		$result = Yii::app()->db->createCommand($sql)->queryAll();
		return $result;
	}
}
 
 
?>