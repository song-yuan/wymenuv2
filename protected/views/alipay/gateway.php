<?php
/***************************************************************
 *                           免责申明
 * 此DEMO仅供参考，支付宝不对次Demo可能隐含的bug负责任，请商户开发人员谨慎使用。
 * 
 * 
 **************************************************************/
header ( "Content-type: text/html; charset=gbk" );
/**
 * 此文件未对接支付宝服务器的网关文件，将此文件的访问路径填入支付宝服务窗的开发中验证网关的页面中。
 * 次文件接收支付宝服务器发送的请求
*/
$config = $this->gateway_config;
if (get_magic_quotes_gpc ()) {
	foreach ( $_POST as $key => $value ) {
		$_POST [$key] = stripslashes ( $value );
	}
	foreach ( $_GET as $key => $value ) {
		$_GET [$key] = stripslashes ( $value );
	}
	foreach ( $_REQUEST as $key => $value ) {
		$_REQUEST [$key] = stripslashes ( $value );
	}
}

// 日志记录下受到的请求
AlipayGatewayUnit::writeLog ( "POST: " . var_export ( $_POST, true ) );
AlipayGatewayUnit::writeLog ( "GET: " . var_export ( $_GET, true ) );
$sign = AlipayGatewayUnit::getRequest ( "sign" );
$sign_type = AlipayGatewayUnit::getRequest ( "sign_type" );
$biz_content = AlipayGatewayUnit::getRequest ( "biz_content" );
$service = AlipayGatewayUnit::getRequest ( "service" );
$charset = AlipayGatewayUnit::getRequest ( "charset" );

if (empty ( $sign ) || empty ( $sign_type ) || empty ( $biz_content ) || empty ( $service ) || empty ( $charset )) {
	echo "some parameter is empty.";
	AlipayGatewayUnit::writeLog ( "some parameter is empty.");
	exit ();
}

// 收到请求，先验证签名

$as = new AlipaySign ();
$sign_verify = $as->rsaCheckV2 ( $_REQUEST, $config ['alipay_public_key_file'] );

AlipayGatewayUnit::writeLog ( "sign verfiy begain");
if (! $sign_verify) {
	// 如果验证网关时，请求参数签名失败，则按照标准格式返回，方便在服务窗后台查看。
	if (AlipayGatewayUnit::getRequest ( "service" ) == "alipay.service.check") {
		$gw = new Gateway ($config,$biz_content);
		$gw->verifygw ( false );
	} else {
		echo "sign verfiy fail.";
		AlipayGatewayUnit::writeLog ( "sign verfiy fail.");
	}
	exit ();
}
AlipayGatewayUnit::writeLog ( "sign verfiy begain1");
// 验证网关请求
if (AlipayGatewayUnit::getRequest ( "service" ) == "alipay.service.check") {
	// Gateway::verifygw();
	$gw = new Gateway ($config,$biz_content);
	$gw->verifygw ( true );
} else if (AlipayGatewayUnit::getRequest ( "service" ) == "alipay.mobile.public.message.notify") {
	// 处理收到的消息
	$msg = new Message ( $biz_content );
}
?>