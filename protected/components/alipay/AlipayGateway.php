<?php
/**
 * 
 * @author 
 * 支付宝网关 公共函数
 *
 */
class Gateway {
	public $config;
	public $biz_content;
	public function __construct($config,$biz_content){
		$this->config = $config;
		$this->biz_content = $biz_content;
	}
	public function verifygw($is_sign_success) {
		$config = $this->config;
		$biz_content = $this->biz_content;
		AlipayGatewayUnit::writeLog ( "gateway: " . $biz_content );
		$as = new AlipaySign ();
		$xml = simplexml_load_string ( $biz_content );
		AlipayGatewayUnit::writeLog ( "xml: " . $xml );
		// print_r($xml);
		$EventType = ( string ) $xml->EventType;
		// echo $EventType;
		AlipayGatewayUnit::writeLog ( "response_xml: " . $EventType );
		if ($EventType == "verifygw") {
			if ($is_sign_success) {
				$response_xml = "<success>true</success><biz_content>" . $as->getPublicKeyStr ( $config ['merchant_public_key_file'] ) . "</biz_content>";
			} else { // echo $response_xml;
				$response_xml = "<success>false</success><error_code>VERIFY_FAILED</error_code><biz_content>" . $as->getPublicKeyStr ( $config ['merchant_public_key_file'] ) . "</biz_content>";
			}
			$return_xml = $as->sign_response ( $response_xml, $config ['charset'], $config ['merchant_private_key_file'] );
			AlipayGatewayUnit::writeLog ( "response_xml: " . $return_xml );
			echo $return_xml;
			exit ();
		}
	}
}