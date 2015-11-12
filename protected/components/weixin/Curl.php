<?php
/**
 * Curl.php
 * 
 */
 
class Curl {
	
	/**
	 * curl获取https的内容,无post数据
	 * @param String $url 请求网址
	 */
	public static function https($url) {
		$ch = curl_init();									// 创建一个新cURL资源
		curl_setopt($ch, CURLOPT_URL, $url);				// 需要获取的URL地址，也可以在curl_init()函数中设置
		curl_setopt($ch, CURLOPT_HEADER, false);			// 不启用头文件的信息作为数据流输出
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);		// 将curl_exec()获取的信息以文件流的形式返回，而不是直接输出
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);	// https注意需要设置关于SSL的opition
		$returnTransfer = curl_exec($ch);					// 因设置CURLOPT_RETURNTRANSFER为TURE，curl_exec()返回获取的内容
		curl_close($ch);									// 关闭cURL资源
		return $returnTransfer;								// 返回获取的数据
	}
	
	/**
	 * url获取https的内容,有post数据
	 * @param String $url 请求网址
	 * @param Mixed $data
	 */
	public static function postHttps($url, $data=null) {
		$ch = curl_init();									// 创建一个新cURL资源
		curl_setopt($ch, CURLOPT_URL, $url);				// 需要获取的URL地址，也可以在curl_init()函数中设置
		curl_setopt($ch, CURLOPT_HEADER, false);			// 不启用头文件的信息作为数据流输出
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);		// 将curl_exec()获取的信息以文件流的形式返回，而不是直接输出
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);	// https注意需要设置关于SSL的opition
		curl_setopt($ch, CURLOPT_POST, true);				// 启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);		// 传递post数据 
		$returnTransfer = curl_exec($ch);					// 因设置CURLOPT_RETURNTRANSFER为TURE，curl_exec()返回获取的内容
		curl_close($ch);									// 关闭cURL资源
		return $returnTransfer;	
	}
	
	/**
	 * 获取图片数据
	 * 注意本类不返回数据，直接输出头文件和文件数据
	 */
	public static function imageHttps($url) {
		$ch = curl_init();									// 创建一个新cURL资源
		curl_setopt($ch, CURLOPT_URL, $url);				// 需要获取的URL地址，也可以在curl_init()函数中设置
		curl_setopt($ch, CURLOPT_HEADER, false);			// 不启用头文件的信息作为数据流输出
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);		// 将curl_exec()获取的信息以文件流的形式返回，而不是直接输出
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);	// https注意需要设置关于SSL的opition
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); 	// 在启用CURLOPT_RETURNTRANSFER的时候，返回原生的（Raw）输出
		curl_setopt($ch, CURLOPT_FAILONERROR, true); 		// 显示HTTP状态码，默认行为是忽略编号小于等于400的HTTP信息
		curl_setopt($ch, CURLOPT_SSLVERSION,CURL_SSLVERSION_TLSv1);			// 必须设置此选项
		$returnTransfer = curl_exec($ch);					// 因设置CURLOPT_RETURNTRANSFER为TURE，curl_exec()返回获取的内容
		if(!curl_errno($ch)){								// 输出文件头信息，并且输出文件
    		header ("Content-type: ".curl_getinfo($ch, CURLINFO_CONTENT_TYPE)."");
    		header ("Content-Length: ".curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD)."");
    		echo $returnTransfer;
		}else 
			echo 'Curl error: ' . curl_error($ch); 			// 输出错误信息	
		curl_close($ch);									// 关闭cURL资源
	}
	
	/**
	 * url获取http的内容,有post数据
	 * @param String $url 请求网址
	 * @param Mixed $data
	 */
	public static function postHttp($url, $data=null) {
		$ch = curl_init();									// 创建一个新cURL资源
		curl_setopt($ch, CURLOPT_URL, $url);				// 需要获取的URL地址，也可以在curl_init()函数中设置
		curl_setopt($ch, CURLOPT_HEADER, false);			// 不启用头文件的信息作为数据流输出
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);		// 将curl_exec()获取的信息以文件流的形式返回，而不是直接输出
		curl_setopt($ch, CURLOPT_POST, true);				// 启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);		// 传递post数据 
		$returnTransfer = curl_exec($ch);					// 因设置CURLOPT_RETURNTRANSFER为TURE，curl_exec()返回获取的内容
		curl_close($ch);									// 关闭cURL资源
		return $returnTransfer;	
	}
	
	/**
	 * url获取http的内容,有post数据
	 * @param String $url 请求网址
	 * @param Mixed $data post的数据信息
	 * @param Array $header CURLOPT_HTTPHEADER 设置HTTP头字段的数组
	 * @param Boolean $headerStatus 是否启用头文件的信息作为数据流输出，默认否
	 */
	public static function postHttpHeader($url, $data, Array $header, $headerStatus = false) {
		$ch = curl_init(); 									// 创建一个新cURL资源
		curl_setopt($ch, CURLOPT_URL,$url); 				// 需要获取的URL地址，也可以在curl_init()函数中设置
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 	// 将curl_exec()获取的信息以文件流的形式返回，而不是直接输出
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); 				// 设置cURL允许执行的最长秒数
		curl_setopt($ch, CURLOPT_HEADER, $headerStatus);	// 启用头文件的信息作为数据流输出
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 		// 用来设置HTTP头字段的数组。使用如下的形式的数组进行设置： array('Content-type: text/plain', 'Content-length: 100')
		curl_setopt($ch, CURLOPT_POST, true);				// 启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);		// 传递post数据 
		$returnTransfer = curl_exec($ch);					// 因设置CURLOPT_RETURNTRANSFER为TURE，curl_exec()返回获取的内容
		curl_close($ch);									// 关闭cURL资源
		return $returnTransfer;	
	}
	
	/**
	 * 需证书的请求，不方法没有POST数据
	 * @param String $url 请求网址
	 * @param String $certFile 包含公钥和私钥的证书，pem类型，财付通中由pfx转换的pem文件
	 * @param String $certPasswd 文件加密密码
	 * @param String $certType 证书类型，PHP一般是PEM
	 * @param String $caFile CA证书，与上述参数涉及不是同一个证书
	 */
	public static function certificateHttps($url, $certFile, $certPasswd, $certType, $caFile) {
		$ch = curl_init();									// 创建一个新cURL资源
		curl_setopt($ch, CURLOPT_URL, $url);				// 需要获取的URL地址，也可以在curl_init()函数中设置
		curl_setopt($ch, CURLOPT_HEADER, false);			// 不启用头文件的信息作为数据流输出
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);		// 将curl_exec()获取的信息以文件流的形式返回，而不是直接输出
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_SSLCERT, $certFile);
		curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $certPasswd);
		curl_setopt($ch, CURLOPT_SSLCERTTYPE, $certType);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_CAINFO, $caFile);
		$returnTransfer = curl_exec($ch);					// 因设置CURLOPT_RETURNTRANSFER为TURE，curl_exec()返回获取的内容
		curl_close($ch);									// 关闭cURL资源
		return $returnTransfer;								// 返回获取的数据
	}
	
	/**
	 * 	作用：使用证书，以post方式提交xml到对应的接口url 退款
	 */
	function postXmlSSLCurl($xml, $url, $certificate, $apiclient_key, $second=30)
	{
		$ch = curl_init();
		//超时时间
		curl_setopt($ch,CURLOPT_TIMEOUT,$second);
		//这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		//设置header
		curl_setopt($ch,CURLOPT_HEADER,FALSE);
		//要求结果为字符串且输出到屏幕上
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
		//设置证书
		//使用证书：cert 与 key 分别属于两个.pem文件
		//默认格式为PEM，可以注释
		curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
		curl_setopt($ch,CURLOPT_SSLCERT, $certificate);
		//默认格式为PEM，可以注释
		curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
		curl_setopt($ch,CURLOPT_SSLKEY, $apiclient_key);
		//post提交方式
		curl_setopt($ch,CURLOPT_POST, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);
		$data = curl_exec($ch);
		//返回结果
		if($data){
			curl_close($ch);
			return $data;
		}
		else { 
			$error = curl_errno($ch);
			echo "curl出错，错误码:$error"."<br>"; 
			echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
			curl_close($ch);
			return false;
		}
	}
}
 
?>