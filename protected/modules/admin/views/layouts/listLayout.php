<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if lt IE 9]>
 <script src="http://apps.bdimg.com/libs/html5shiv/3.7/html5shiv.min.js"></script>
 <script src="http://apps.bdimg.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
	<meta name="MobileOptimized" content="320">
	<!-- BEGIN GLOBAL MANDATORY STYLES -->
	<?php $this->registerCssFile('plugins/font-awesome/css/font-awesome.min.css');?>
	<?php $this->registerCssFile('plugins/uniform/css/uniform.default.css');?>
	<!-- END GLOBAL MANDATORY STYLES -->
	<!-- BEGIN PAGE LEVEL STYLES --> 
	<?php $this->registerCssFile('plugins/select2/select2_metro.css');?>
	<!-- END PAGE LEVEL SCRIPTS -->
	<!-- BEGIN THEME STYLES --> 
	<?php $this->registerCssFile('css/style-metronic.css');?>
	<?php $this->registerCssFile('css/style.css');?>
	<?php $this->registerCssFile('css/style-responsive.css');?>
	<?php $this->registerCssFile('css/themes/default.css');?>
	<?php $this->registerCssFile('css/custom.css');?>
	<!-- END THEME STYLES -->
	<link rel="shortcut icon" href="favicon.ico" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed">
	<?php $this->beginBody() ?>
	<?php $this->beginContent('@backend/views/layouts/header.php');?>
	<?php $this->endContent();?>
	<!-- BEGIN CONTAINER -->
	<div class="page-container">
	<?php $this->beginContent('@backend/views/layouts/sidebar.php');?>
	<?php $this->endContent();?>
	<!-- BEGIN PAGE -->
		<?php echo $content;?>
	<!-- END PAGE -->
	</div>
	<!-- END CONTAINER -->
	<!-- BEGIN FOOTER -->
	<?php $this->beginContent('@backend/views/layouts/footer.php');?>
	<?php $this->endContent();?>
	<!-- END FOOTER -->
	<?php $this->endBody() ?>
	<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
	<!-- BEGIN CORE PLUGINS -->   
	<!--[if lt IE 9]>
	<script src="assets/plugins/respond.min.js"></script>
	<script src="assets/plugins/excanvas.min.js"></script> 
	<![endif]-->
	<?php $this->registerJsFile('plugins/jquery-migrate-1.2.1.min.js');?>
	<?php $this->registerJsFile('plugins/bootstrap/js/bootstrap.min.js');?>
	<?php $this->registerJsFile('plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js');?>
	<?php $this->registerJsFile('plugins/jquery-slimscroll/jquery.slimscroll.min.js');?>
	<?php $this->registerJsFile('plugins/jquery.blockui.min.js');?>
	<?php $this->registerJsFile('plugins/jquery.cookie.min.js');?>
	<?php $this->registerJsFile('plugins/uniform/jquery.uniform.min.js');?>
	<!-- END CORE PLUGINS -->
	<!-- BEGIN PAGE LEVEL PLUGINS -->
	<?php $this->registerJsFile('plugins/select2/select2.min.js');?>
	<?php $this->registerJsFile('plugins/data-tables/jquery.dataTables.js');?>
	<?php $this->registerJsFile('plugins/data-tables/DT_bootstrap.js');?>
	<!-- END PAGE LEVEL PLUGINS -->
	<!-- BEGIN PAGE LEVEL SCRIPTS -->
	<?php $this->registerJsFile('scripts/app.js');?>
	<?php $this->registerJsFile('scripts/table-managed.js');?>
	<script>
		jQuery(document).ready(function() {
		   App.init();
		   TableManaged.init();
		});
	</script>
</body>
<!-- END BODY -->
</html>
<?php $this->endPage() ?>
