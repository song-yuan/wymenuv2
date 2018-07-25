<?php
/* @var $this \yii\web\View */
/* @var $content string */
$baseUrl = Yii::app()->baseUrl;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, minimum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>  
    <link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/common.css">
    <link rel="stylesheet" href="<?php echo $baseUrl;?>/css/weui.min.css">
    <script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="<?php echo $baseUrl;?>/js/layer/layer.js"></script>
</head>
<body>
    <?php echo $content ?>
</body>
</html>