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
	<?php $this->registerCssFile('plugins/fancybox/source/jquery.fancybox.css');?>
	<?php $this->registerCssFile('plugins/jquery-file-upload/css/jquery.fileupload-ui.css');?>
	
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
	<?php $this->registerJsFile('plugins/fancybox/source/jquery.fancybox.pack.js');?>
	<!-- BEGIN:File Upload Plugin JS files-->
	<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
	<?php $this->registerJsFile('plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js');?>
	<!-- The Templates plugin is included to render the upload/download listings -->
	<?php $this->registerJsFile('plugins/jquery-file-upload/js/vendor/tmpl.min.js');?>
	<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
	<?php $this->registerJsFile('plugins/jquery-file-upload/js/vendor/load-image.min.js');?>
	<!-- The Canvas to Blob plugin is included for image resizing functionality -->
	<?php $this->registerJsFile('plugins/jquery-file-upload/js/vendor/canvas-to-blob.min.js');?>
	<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
	<?php $this->registerJsFile('plugins/jquery-file-upload/js/jquery.iframe-transport.js');?>
	<!-- The basic File Upload plugin -->
	<?php $this->registerJsFile('plugins/jquery-file-upload/js/jquery.fileupload.js');?>
	<!-- The File Upload processing plugin -->
	<?php $this->registerJsFile('plugins/jquery-file-upload/js/jquery.fileupload-process.js');?>
	<!-- The File Upload image preview & resize plugin -->
	<?php $this->registerJsFile('plugins/jquery-file-upload/js/jquery.fileupload-image.js');?>
	<!-- The File Upload audio preview plugin -->
	<?php $this->registerJsFile('plugins/jquery-file-upload/js/jquery.fileupload-audio.js');?>
	<!-- The File Upload video preview plugin -->
	<?php $this->registerJsFile('plugins/jquery-file-upload/js/jquery.fileupload-video.js');?>
	<!-- The File Upload validation plugin -->
	<?php $this->registerJsFile('plugins/jquery-file-upload/js/jquery.fileupload-validate.js');?>
	<!-- The File Upload user interface plugin -->
	<?php $this->registerJsFile('plugins/jquery-file-upload/js/jquery.fileupload-ui.js');?>
	<!-- The main application script -->
	<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
	<!--[if (gte IE 8)&(lt IE 10)]>
	<script src="assets/plugins/jquery-file-upload/js/cors/jquery.xdr-transport.js"></script>
	<![endif]-->
	<!-- END:File Upload Plugin JS files-->	
	<!-- END PAGE LEVEL PLUGINS -->
	<?php $this->registerJsFile('scripts/app.js');?>
	<?php $this->registerJsFile('scripts/form-fileupload.js');?>
	<script>
		jQuery(document).ready(function() {
		   App.init();
		   FormFileUpload.init();
		});
	</script>
</body>
<!-- END BODY -->
</html>
<?php $this->endPage() ?>
