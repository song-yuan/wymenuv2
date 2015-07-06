<?php
$language = 0;
$baseUrl = Yii::app()->baseUrl;
/* @var $this \yii\web\View */
/* @var $content string */
if(isset($_GET['wuyimenusysosyoyhmac']))
{
	$_SESSION['smac']=$_GET['wuyimenusysosyoyhmac'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, minimum-scale=1, user-scalable=no">
    <title><?php echo yii::t('app','我要点单'); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo $baseUrl.'/css/productmain.css';?>"/>
    <script type="text/javascript" src="<?php echo $baseUrl.'/plugins/jquery-1.10.2.min.js';?>"></script>
    <script type="text/javascript" src="<?php echo $baseUrl.'/js/layer/layer.js';?>"></script>
</head>
<body>
    <div class="page">
    <?php echo $content ?>
    </div>
    
</body>
</html>