<!DOCTYPE html>
<html lang="en>">
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
 <html lang="en" class="ie8 no-js"> 
<!-- BEGIN HEAD -->
<head>
 	
    <meta charset="utf-8"/>
    <meta name="renderer" content="webkit" />
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"> 
    <title><?php echo yii::t('app','壹点吃商城');?></title>
    <link rel='icon' href="<?php echo Yii::app()->request->baseUrl.'/img/yidianchilogo.ico';?>" type='image/x-ico' />
    <link rel="stylesheet" type="text/css" href="../../../../css/ymall/ymall.css"/>
	<meta name="MobileOptimized" content="320">

	<!-- END THEME STYLES -->
	<?php

	Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-1.10.2.min.js');
	Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery.fly.min.js');  		 	
?>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed">

	<!-- BEGIN PAGE -->
		<?php echo $content;?>
	<!-- END PAGE -->
	</div>
	<!-- END CONTAINER -->
	<!-- BEGIN FOOTER -->
	<?php $this->beginContent('/layouts/productmain');?>
	<?php $this->endContent();?>

	<script>
		jQuery(document).ready(function() {
		   App.init();
		   TableManaged.init();
		});
	</script>
</body>
<!-- END BODY -->
</html>
