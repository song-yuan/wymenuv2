<?php
/**
* 
*/
class MtUnit
{
	const MTHOST = 'https://api-open-cater.meituan.com';
	public static function dealData($params)
	{
		$paramsArr = explode('&',$params); 
   
       	foreach($paramsArr as $k=>$v) 
       	{ 
          	$a = explode('=',$v); 
          	$arr[$a[0]] = $a[1]; 
       	} 
       	return $arr;
	}
	public static function getWmSetting($dpid)
	{
		$sql = 'select * from nb_waimai_setting where dpid='.$dpid.' and delete_flag=0';
		$result = Yii::app()->db->createCommand($sql)->queryRow();
		return $result;
	}
	public static function postHttps($url, $data=null) {
		$ch = curl_init();									// 创建一个新cURL资源
		curl_setopt($ch, CURLOPT_URL, $url);				// 需要获取的URL地址，也可以在curl_init()函数中设置
		curl_setopt($ch, CURLOPT_HEADER, false);			// 不启用头文件的信息作为数据流输出
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);		// 将curl_exec()获取的信息以文件流的形式返回，而不是直接输出
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);	// https注意需要设置关于SSL的opition
		if(!empty($data)){
			curl_setopt($ch, CURLOPT_POST, true);				// 启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		$returnTransfer = curl_exec($ch);					// 因设置CURLOPT_RETURNTRANSFER为TURE，curl_exec()返回获取的内容
		curl_close($ch);									// 关闭cURL资源
		return $returnTransfer;	
	}
	public static function sign($data,$signkey){
		ksort($data);
		$sign='';
		foreach ($data as $key => $value) {
			$sign.= $key.$value;
		}
		$sign = $signkey.$sign;
		$sign = strtolower(sha1($sign));
		return $sign;
	}
}