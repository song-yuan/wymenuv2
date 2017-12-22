<!DOCTYPE html>
<html style="height: 100%;">
<head>
    <meta charset="utf-8"/>
    <title>壹点吃商城登陆</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">


	<link rel="stylesheet" href="<?php echo  Yii::app()->request->baseUrl; ?>/css/ymall/mui.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo  Yii::app()->request->baseUrl; ?>/css/ymall/feedback-page.css" />
	<link href="<?php echo  Yii::app()->request->baseUrl; ?>/css/ymall/iconfont.css" rel="stylesheet"/>
	<link rel="stylesheet" type="text/css" href="<?php echo  Yii::app()->request->baseUrl; ?>/css/ymall/ymall.css"/>
	<style>
		.mui-hbar{background-color: #B22222!important;color:white;}
		.mui-a{color:yellow!important;font-weight: 900;}
		/*.mui-hbar{background-color: #FF8C00!important;color:white;}
		.mui-a{color:#3ff!important;font-weight: 900;}*/
		/* .mui-bar-tab .mui-tab-item {color:#FF8C00;} */
		.mui-bar-tab .mui-tab-item {color:#fff;}
	</style>
	<script type="text/javascript" src="<?php echo  Yii::app()->request->baseUrl; ?>/plugins/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="<?php echo  Yii::app()->request->baseUrl; ?>/scripts/flipsnap.js"></script>
	<script src="<?php echo  Yii::app()->request->baseUrl; ?>/js/ymall/mui.min.js "></script>
	<script src="<?php echo  Yii::app()->request->baseUrl; ?>/js/ymall/mui.view.js "></script>
	<script src="<?php echo  Yii::app()->request->baseUrl; ?>/js/ymall/app.js"></script>
	<script src="<?php echo  Yii::app()->request->baseUrl; ?>/js/ymall/jquery-1.7.1.min.js"></script>
</head>
<body>

	<?php echo $content ;?>

	<?php Yii::app()->clientScript->registerCoreScript('jquery');?>
	<?php Yii::app()->clientScript->registerScriptFile('../plugins/jquery-migrate-1.2.1.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile('../plugins/bootstrap/js/bootstrap.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile('../plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile('../plugins/jquery-slimscroll/jquery.slimscroll.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile('../plugins/jquery.blockui.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile('../plugins/jquery.cookie.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile('../plugins/uniform/jquery.uniform.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile('../plugins/jquery-validation/dist/jquery.validate.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile('../plugins/backstretch/jquery.backstretch.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile('../plugins/select2/select2.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile('../scripts/app.js');?>
	<?php Yii::app()->clientScript->registerScriptFile('../scripts/login-soft.js');?>
	<script>
		jQuery(document).ready(function() {
		});
	</script>
</body>
<!-- END BODY -->
</html>
