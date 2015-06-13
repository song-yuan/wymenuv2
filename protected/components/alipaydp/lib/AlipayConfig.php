<?php
/* *
 * 类名：AlipayConfig
 * 功能：支付宝各接口请求提交类
 * 详细：构造支付宝各接口表单HTML文本，获取远程HTTP数据
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
 */
require_once("alipay.config.php");
class AlipayConfig {

	var $config;
	
	function __construct(){
		$this->config = $alipay_config;
	}
    function AlipayConfig() {
    	$this->__construct();
    }

    /**
     * 取得alipay参数
     */
	function getAlipayConfig() {
		//待请求参数数组
		return $this->config;
	}
	
	
}
?>