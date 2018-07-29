<?php
/* @var $this \yii\web\View */
/* @var $content string */
	$baseUrl = Yii::app()->baseUrl;
	$weixinServerAccount = WxAccount::get($this->companyId);
	$jsSdk = new WeixinJsSdk($weixinServerAccount['appid'],$weixinServerAccount['appsecret'],$this->companyId);
	$signPackage = $jsSdk->GetSignPackage();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, minimum-scale=1, user-scalable=no">
    <meta http-equiv="Pragma" content="no-cache" /> 
	<meta http-equiv="Cache-Control" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
    <meta name = "format-detection" content = "telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/common.css">
   	<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/weui.min.css?v=_201807300441">
    <script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="<?php echo $baseUrl;?>/js/layer/layer.js"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script>
	  wx.config({
	    debug: false,
	    appId: '<?php echo $signPackage["appId"];?>',
	    timestamp: <?php echo $signPackage["timestamp"];?>,
	    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
	    signature: '<?php echo $signPackage["signature"];?>',
	    jsApiList: [
	      // 所有要调用的 API 都要加到这个列表中
	      'onMenuShareTimeline',
	      'onMenuShareAppMessage',
	      'getLocation',
	      'openLocation',
	      'showMenuItems'
	    ]
	  });
	</script>
</head>
<body>
    <?php echo $content ?>
</body>
</html>