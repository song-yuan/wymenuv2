<?php
/**
* 美团开放平台
*/
class MtOpenUnit
{
	const MTURL = 'https://waimaiopen.meituan.com/api/v1/';
	const YDCURL = 'http://menu.wymenu.com/wymenuv2/';
	public static function getMtConfig($dpid){
		$sql = 'select * from nb_meituan_setting where dpid='.$dpid.' and delete_flag=0';
		$result = Yii::app()->db->createCommand($sql)->queryRow();
		return $result;
	}
	public static function getMtConfigByAppid($appid){
		$sql = 'select * from nb_meituan_setting where app_id='.$appid.' and delete_flag=0';
		$result = Yii::app()->db->createCommand($sql)->queryRow();
		return $result;
	}
	public static function getSigUrl($type)
	{
		$urlArr = array(
				'new'=>'meituanOpen/receiveOrder',
				'confirm'=>'meituanOpen/confirmOrder',
				'cancel'=>'meituanOpen/cancelOrder',
				'shipper'=>'meituanOpen/orderShipper',
				'reminder'=>'meituanOpen/orderReminder',
				'refund'=>'meituanOpen/orderRefund',
				'privacynumber'=>'meituanOpen/privacyNumber'
		);
		return $urlArr[$type];
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
	public static function checkSign($type,$data,$appsecret){
		$sign = $data['sig'];
		unset($data['sig']);
		ksort($data);
		$str = '';
		foreach ($data as $key => $value) {
			$value = urldecode($value);
			$str .= $key.'='.$value.'&';
		}
		$str = rtrim($str,'&');
		$str = self::YDCURL.self::getSigUrl($type).$url.'?'.$str.$appsecret;
		$hsign = md5($str);
		if($sign==$hsign){
			return true;
		}
		return false;
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