<?php


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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>我要点单</title>
    <link rel="stylesheet" type="text/css" href="css/productmain.css"/>
    <script type="text/javascript" src="plugins/jquery-1.10.2.min.js"></script>
    <script type="text/javascript">
    var mobilemac='nil';
	var localgwip='nil';
	var servermac='<?php echo isset($_SESSION['smac'])?$_SESSION['smac']:'nil';?>';
    </script> 
    <script type="text/javascript" src="http://menu.wymenu.com/enthome/js/yun_adlocal.js"></script>
	<script type="text/javascript" name="baidu-tc-cerfication" data-appid="4756224" src="http://apps.bdimg.com/cloudaapi/lightapp.js"></script>
</head>
<body>
    <div class="page">
    <?php echo $content ?>
    </div>
</body>
</html>