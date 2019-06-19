<?php
/**
* 美团开放平台
*/
class MtOpenUnit
{
	const MTURL = 'https://waimaiopen.meituan.com/api/v1/';
	public static function getMtConfig($dpid){
		$sql = 'select * from nb_meituan_setting where dpid='.$dpid.' and delete_flag=0';
		$result = Yii::app()->db->createCommand($sql)->queryRow();
		return $result;
	}
	public static function urlToArr($url)
	{
		$urlArr = array();
		$paramsArr = explode('&',$url);
		foreach($paramsArr as $k=>$v)
		{
			$a = explode('=',$v);
			$urlArr[$a[0]] = $a[1];
		}
		return $urlArr;
	}
	public static function getSign($url,$data,$appsecret){
		ksort($data);
		$str = '';
		foreach ($data as $key => $value) {
			$str .= $key.'='.$value.'&';
		}
		$str = rtrim($str,'&');
		$str = $url.'?'.$str.$appsecret;
		$sign = md5($str);
		return $sign;
	}
	public static function getUrlStr($url,$data,$appsecret){
		$str = '';
		foreach ($data as $key => $value) {
			$str .= $key.'='.$value.'&';
		}
		$sign = self::getSign($url, $data, $appsecret);
		$str = $url.'?'.$str.'sig='.$sign;
		return $str;
	}
}