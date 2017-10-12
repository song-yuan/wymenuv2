<?php


/* @var $this \yii\web\View */
/* @var $content string */
$basePath = Yii::app()->baseUrl;;
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
    <title>壹点管理系统</title>
    <link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/mui.min.css"/>
    <script type="text/javascript" src="<?php echo $basePath;?>/js/appreport/mui.min.js"></script> 
</head>
<body>
    <?php echo $content ?>
</body>
</html>

