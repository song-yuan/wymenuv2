<?php


/* @var $this \yii\web\View */
/* @var $content string */
$basePath = Yii::app()->baseUrl;;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>壹点管理系统</title>
    <link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/mui.min.css"/>
    <script type="text/javascript" src="<?php echo $basePath;?>/appreport/mui.min.js"></script> 
</head>
<body>
    <?php echo $content ?>
</body>
</html>

