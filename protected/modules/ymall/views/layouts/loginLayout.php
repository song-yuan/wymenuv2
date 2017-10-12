<!DOCTYPE html>
<html lang="en">
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title>壹点吃商城登陆</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<meta name="MobileOptimized" content="320">
	<!-- BEGIN GLOBAL MANDATORY STYLES -->
	<?php Yii::app()->clientScript->registerCssFile('../plugins/font-awesome/css/font-awesome.min.css');?>
	<?php Yii::app()->clientScript->registerCssFile('../plugins/bootstrap/css/bootstrap.min.css');?>
	<?php Yii::app()->clientScript->registerCssFile('../plugins/uniform/css/uniform.default.css');?>

	<!-- END GLOBAL MANDATORY STYLES -->
	<!-- BEGIN PAGE LEVEL STYLES -->
	<?php Yii::app()->clientScript->registerCssFile('../plugins/select2/select2_metro.css');?>
	<!-- END PAGE LEVEL SCRIPTS -->
	<!-- BEGIN THEME STYLES -->
	<?php Yii::app()->clientScript->registerCssFile('../css/style-metronic.css');?>
	<?php Yii::app()->clientScript->registerCssFile('../css/style.css');?>
	<?php Yii::app()->clientScript->registerCssFile('../css/style-responsive.css');?>
	<?php Yii::app()->clientScript->registerCssFile('../css/themes/default.css');?>
	<?php Yii::app()->clientScript->registerCssFile('../css/pages/login-soft.css');?>
	<?php Yii::app()->clientScript->registerCssFile('../css/custom.css');?>
	<!-- END THEME STYLES -->
	<link rel="shortcut icon" href="favicon.ico" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
	<!-- BEGIN LOGO -->
	<div class="logo">
		<h1 style="color:white">壹点吃商城</h1>
	</div>
	<!-- END LOGO -->
	<!-- BEGIN LOGIN -->
	<div class="content">
		<!-- BEGIN LOGIN FORM -->
		<?php echo $content ;?>
		<!-- END REGISTRATION FORM -->
	</div>
	<!-- END LOGIN -->
	<!-- BEGIN COPYRIGHT -->
<!-- 	<div class="copyright"> -->
<!-- 		2017 &copy; Metronic - Admin Dashboard Template. -->
<!-- 	</div> -->
	<!-- END COPYRIGHT -->
	<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
	<!-- BEGIN CORE PLUGINS -->
	<!-- END CORE PLUGINS -->
	<!-- END JAVASCRIPTS -->
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
		  //App.init();
		  //Login.init();
		});
	</script>
</body>
<!-- END BODY -->
</html>
