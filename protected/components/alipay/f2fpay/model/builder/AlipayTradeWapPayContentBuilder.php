<?php
/**
 * Created by PhpStorm.
 * User: xudong.ding
 * Date: 16/5/18
 * Time: 下午2:09
 */
class AlipayTradeWapPayContentBuilder extends ContentBuilder
{
    // 商户网站订单系统中唯一订单号，64个字符以内，只能包含字母、数字、下划线，
    // 需保证商户系统端不能重复，建议通过数据库sequence生成，
    private $outTradeNo;

    // 卖家支付宝账号ID，用于支持一个签约账号下支持打款到不同的收款账号，(打款到sellerId对应的支付宝账号)
    // 如果该字段为空，则默认为与支付宝签约的商户的PID，也就是appid对应的PID
    private $sellerId;

    // 订单总金额，整形，此处单位为元，精确到小数点后2位，不能超过1亿元
    // 如果同时传入了【打折金额】,【不可打折金额】,【订单总金额】三者,则必须满足如下条件:【订单总金额】=【打折金额】+【不可打折金额】
    private $totalAmount;

    // 订单标题，粗略描述用户的支付目的。如“XX品牌XXX门店消费”
    private $subject;

    // 订单描述，可以对交易或商品进行一个详细地描述，比如填写"购买商品2件共15.00元"
    private $body;

    // (推荐使用，相对时间) 支付超时时间，5m 5分钟
    private $timeExpress;
    
    // 针对用户授权接口，获取用户相关数据时，用于标识用户授权关系
    private $authToken;
    
    // 销售产品码，商家和支付宝签约的产品码
    private $productCode;

    private $bizContent = NULL;

    private $bizParas = array();


    public function __construct()
    {
        $this->bizParas['product_code'] = "QUICK_WAP_PAY";
    }

    public function AlipayTradeWapPayContentBuilder()
    {
        $this->__construct();
    }

    public function getBizContent()
    {
        /*$this->bizContent = "{";
        foreach ($this->bizParas as $k=>$v){
            $this->bizContent.= "\"".$k."\":\"".$v."\",";
        }
        $this->bizContent = substr($this->bizContent,0,-1);
        $this->bizContent.= "}";*/
        if(!empty($this->bizParas)){
            $this->bizContent = json_encode($this->bizParas,JSON_UNESCAPED_UNICODE);
        }

        return $this->bizContent;
    }
    
    public function setOutTradeNo($outTradeNo)
    {
        $this->outTradeNo = $outTradeNo;
        $this->bizParas['out_trade_no'] = $outTradeNo;
    }
    public function getOutTradeNo()
    {
        return $this->outTradeNo;
    }

    public function setSellerId($sellerId)
    {
        $this->sellerId = $sellerId;
        $this->bizParas['seller_id'] = $sellerId;
    }

    public function getSellerId()
    {
        return $this->sellerId;
    }

    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;
        $this->bizParas['total_amount'] = $totalAmount;
    }

    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        $this->bizParas['subject'] = $subject;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setBody($body)
    {
        $this->body = $body;
        $this->bizParas['body'] = $body;
    }

    public function getBody()
    {
        return $this->body;
    }


    public function setTimeExpress($timeExpress)
    {
        $this->timeExpress = $timeExpress;
        $this->bizParas['timeout_express'] = $timeExpress;
    }

    public function getTimeExpress()
    {
        return $this->timeExpress;
    }
    
    public function setAuthToken($authToken)
    {
    	$this->authToken = $authToken;
    	$this->bizParas['auth_token'] = $authToken;
    }
    public function getAuthToken()
    {
    	return $this->authToken;
    }
    
    public function setProductCode($productCode)
    {
    	$this->productCode = $productCode;
    	$this->bizParas['product_code'] = $productCode;
    }
    
    public function getProductCode()
    {
    	return $this->productCode;
    }

}

?>