<!DOCTYPE html>
<html lang="en">
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title><?php echo yii::t('app','我要点单 - 排队取号系统');?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<meta name="MobileOptimized" content="320">
	<!-- BEGIN GLOBAL MANDATORY STYLES -->
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/plugins/font-awesome/css/font-awesome.min.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/plugins/bootstrap/css/bootstrap.min.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/plugins/uniform/css/uniform.default.css');?>
		
	<!-- END GLOBAL MANDATORY STYLES -->
	<!-- BEGIN PAGE LEVEL STYLES --> 
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/plugins/select2/select2_metro.css');?>
	<!-- END PAGE LEVEL SCRIPTS -->
	<!-- BEGIN THEME STYLES --> 
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/style-metronic.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/style.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/style-responsive.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/themes/default.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/pages/login-soft.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/custom.css');?>
		<!-- END THEME STYLES -->
	<link rel="shortcut icon" href="favicon.ico" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
	<!-- BEGIN LOGO -->
	<div style="text-align: center;">
                <h1 style="color:white;"><?php echo yii::t('app','排队取号');?></h1> 
                
        </div>
	<!-- END LOGO -->
	<!-- BEGIN LOGIN -->
        <div class="content" style="width:95% !important;">
		<!-- BEGIN LOGIN FORM -->
		<?php echo $content ;?>
		<!-- END REGISTRATION FORM -->
	</div>
	<!-- END LOGIN -->
	
	<!-- END COPYRIGHT -->
	<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
	<!-- BEGIN CORE PLUGINS -->   
	<!-- END CORE PLUGINS -->
	<!-- END JAVASCRIPTS -->
	<?php Yii::app()->clientScript->registerCoreScript('jquery');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/jquery-migrate-1.2.1.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/bootstrap/js/bootstrap.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/jquery-slimscroll/jquery.slimscroll.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/jquery.blockui.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/jquery.cookie.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/uniform/jquery.uniform.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/jquery-validation/dist/jquery.validate.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/backstretch/jquery.backstretch.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/select2/select2.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/scripts/app.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/scripts/login-soft.js');?>
	<script>
		jQuery(document).ready(function() {     
		  App.init();
		 $.backstretch([
		        "/wymenuv2/admin/img/bg/1.jpg",
		        "/wymenuv2/admin/img/bg/2.jpg",
		        "/wymenuv2/admin/img/bg/3.jpg",
		        "/wymenuv2/admin/img/bg/4.jpg"
		        ], {
		          fade: 800,
		          duration: 4000
		    });
		});
	</script>
</body>
<!-- END BODY -->
</html>
