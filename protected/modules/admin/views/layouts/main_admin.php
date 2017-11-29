<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.0
Version: 1.5.3
Author: KeenThemes
Website: http://www.keenthemes.com/
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
 	
    <meta charset="utf-8"/>
    <meta name="renderer" content="webkit" />
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"> 
    <title><?php echo yii::t('app','壹点吃管理系统');?></title>
    <link rel='icon' href="<?php echo Yii::app()->request->baseUrl.'/img/yidianchilogo.ico';?>" type='image/x-ico' />
	<meta name="MobileOptimized" content="320">
	<!-- BEGIN GLOBAL MANDATORY STYLES -->
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/plugins/font-awesome/css/font-awesome.min.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/plugins/bootstrap/css/bootstrap.min.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/plugins/uniform/css/uniform.default.css');?>
	<!-- END GLOBAL MANDATORY STYLES -->
	<!-- BEGIN PAGE LEVEL STYLES --> 
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/plugins/bootstrap-fileupload/bootstrap-fileupload.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/plugins/select2/select2_metro.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/plugins/data-tables/DT_bootstrap.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/plugins/jquery-treegrid/css/jquery.treegrid.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/plugins/bootstrap-switch/static/stylesheets/bootstrap-switch-metro.css');?>
		
	<!-- END PAGE LEVEL SCRIPTS -->
	<!-- BEGIN THEME STYLES --> 
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/style-metronic.css');?>        
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/style.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/style-responsive.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/plugins.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/themes/default.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/custom.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/waiter/cf.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/plugins/jquery-multi-select/css/multi-select.css');?>
	
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/jquery-ui-1.8.17.custom.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/jquery-ui-timepicker-addon.css');?>
	<!-- END THEME STYLES -->
	
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed">
	<?php $this->beginContent('/layouts/header');?>
	<?php $this->endContent();?>
	<!-- BEGIN CONTAINER -->
	<div class="page-container">
	<?php $this->beginContent('/layouts/sidebar');?>
	<?php $this->endContent();?>
	<!-- BEGIN PAGE -->
		<?php echo $content;?>
	<!-- END PAGE -->
	</div>
	<!-- END CONTAINER -->
	<!-- BEGIN FOOTER -->
	<?php $this->beginContent('/layouts/footer');?>
	<?php $this->endContent();?>
	<!-- END FOOTER -->
	<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
	<!-- BEGIN CORE PLUGINS -->   
	<!--[if lt IE 9]>
	<script src="assets/plugins/respond.min.js"></script>
	<script src="assets/plugins/excanvas.min.js"></script> 
	<![endif]-->
	<?php Yii::app()->clientScript->registerCoreScript('jquery');?>
	<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/jquery-1.10.2.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/jquery-migrate-1.2.1.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/bootstrap/js/bootstrap.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/fuelux/js/spinner.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/jquery-slimscroll/jquery.slimscroll.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/jquery.blockui.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/jquery.cookie.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/uniform/jquery.uniform.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/jquery.form.js');?>
	<!-- END CORE PLUGINS -->
	<!-- BEGIN PAGE LEVEL PLUGINS -->
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/bootstrap-fileupload/bootstrap-fileupload.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/select2/select2.min.js');?>
	

	
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>
	
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js');?>
	
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/jquery.input-ip-address-control-1.0.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/jquery-multi-select/js/jquery.multi-select.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/jquery-multi-select/js/jquery.quicksearch.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/bootstrap-touchspin/bootstrap.touchspin.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/data-tables/jquery.dataTables.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/data-tables/DT_bootstrap.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/jquery-treegrid/js/jquery.treegrid.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/bootbox/bootbox.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/bootstrap-switch/static/js/bootstrap-switch.min.js');?>
	<!-- END PAGE LEVEL PLUGINS -->
	<!-- BEGIN PAGE LEVEL SCRIPTS -->
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/scripts/app.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/scripts/form-components.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/scripts/table-managed.js');?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/padpc.js');?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/product/jquery.form.js');?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/layer/layer.js');?>
        
	<script>
		jQuery(document).ready(function() {
		   App.init();
		   TableManaged.init();
		});
	</script>
</body>
<!-- END BODY -->
</html>
