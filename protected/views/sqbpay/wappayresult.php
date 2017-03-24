
<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('支付中心');
?>


<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/wechat_js/zepto.min.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/wechat_js/example.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-1.7.1.min.js');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/wechat_css/weui.css');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/wechat_css/example.css');?>
<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/plugins/font-awesome/css/font-awesome.min.css');?>

