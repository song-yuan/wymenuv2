<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('支付宝');
?>
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/weui.min.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>

<?php
    echo $htmlText;
?>
<script type="text/javascript">
$(document).ready(function(){
	$('#alipaysubmit').submit();
});
</script>
