<?php
/**
 * Created by PhpStorm.
 * User: xudong.ding
 * Date: 16/5/19
 * Time: 下午2:09
 */
class AlipayTradeWapService {

	//支付宝网关地址
	public $gateway_url = "https://openapi.alipay.com/gateway.do";

	//异步通知回调地址
	public $notify_url;

	//支付宝公钥地址
	public $alipay_public_key;

	//商户私钥地址
	public $private_key;

	//应用id
	public $appid;

	//编码格式
	public $charset = "UTF-8";

	//返回数据格式
	public $format = "json";


	function __construct($alipay_config){
		$this->gateway_url = $alipay_config['gatewayUrl'];
		$this->appid = $alipay_config['app_id'];
		//$this->private_key = $alipay_config['merchant_private_key_file'];
		$this->private_key = $alipay_config['merchant_private_key'];
		//$this->alipay_public_key = $alipay_config['alipay_public_key_file'];
		$this->alipay_public_key = $alipay_config['alipay_public_key'];
		$this->charset = $alipay_config['charset'];
		$this->notify_url = $alipay_config['notify_url'];

		if(empty($this->appid)||trim($this->appid)==""){
			throw new Exception("appid should not be NULL!");
		}
		if(empty($this->private_key)||trim($this->private_key)==""){
			throw new Exception("private_key should not be NULL!");
		}
		if(empty($this->alipay_public_key)||trim($this->alipay_public_key)==""){
			throw new Exception("alipay_public_key should not be NULL!");
		}
		if(empty($this->charset)||trim($this->charset)==""){
			throw new Exception("charset should not be NULL!");
		}
		if(empty($this->gateway_url)||trim($this->gateway_url)==""){
			throw new Exception("gateway_url should not be NULL!");
		}
	}
	function AlipayWapPayService($alipay_config) {
		$this->__construct($alipay_config);
	}
	
	// 手机网站支付
	public function wapPay($req,$companyId) {

		$bizContent = $req->getBizContent();

		$this->writeLog($bizContent);

		//echo $bizContent;
		
		$request = new AlipayTradeWapPayRequest();
		$request->setNotifyUrl ( Yii::app()->request->hostInfo."/wymenuv2/alipay/notify?companyId=".$companyId );
		$request->setReturnUrl ( Yii::app()->request->hostInfo."/wymenuv2/alipay/return?companyId=".$companyId );
		$request->setBizContent ( $bizContent );


		$result = $this->aopclientRequestExecute ( $request, $httpmethod = "GET");
		
		return $result;

	}
	/**
	 * 使用SDK执行提交页面接口请求
	 * @param unknown $request
	 * @param string $token
	 * @param string $appAuthToken
	 * @return string $$result
	 */
	private function aopclientRequestExecute( $request, $httpmethod = "POST") {

		$aop = new AopClient ();
		$aop->gatewayUrl = $this->gateway_url;
		$aop->appId = $this->appid;
		//$aop->rsaPrivateKeyFilePath = $this->private_key;
		$aop->rsaPrivateKey = $this->private_key;
		//$aop->alipayPublicKey = $this->alipay_public_key;
		$aop->alipayrsaPublicKey = $this->alipay_public_key;
		$aop->apiVersion = "1.0";
		$aop->postCharset = $this->charset;


		$aop->format=$this->format;
		// 开启页面信息输出
		$aop->debugInfo=true;
		$result = $aop->pageExecute($request);

		//打开后，将url形式请求报文写入log文件
		//$this->writeLog("response: ".var_export($result,true));
		return $result;
	}

	function writeLog($text) {
		// $text=iconv("GBK", "UTF-8//IGNORE", $text);
		//$text = characet ( $text );
		file_put_contents ( Yii::app()->basePath."/data/log.txt", date ( "Y-m-d H:i:s" ) . "  " . $text . "\r\n", FILE_APPEND );
	}

}