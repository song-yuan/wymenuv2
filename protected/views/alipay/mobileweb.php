<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('支付宝');
?>
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/weui.min.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
 <style type="text/css">
.ali_toptips{background:#04BE02;color:#FFF;text-align: center;font-size: 18px;line-height:2.33333333}
</style>
 <div class="ali_toptips">请点击右上角，选择在浏览器中打开完成支付</div>
<?php
    echo $htmlText;
?>
<script type="text/javascript">
$(document).ready(function(){
//	$('#alipaysubmit').submit();
});
</script>
