<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('支付宝');
?>
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/weui.min.css">
<style type="text/css">
.ali_toptips{background:#04BE02;color:#FFF;text-align: center;font-size: 15px;line-height:2.33333333}
</style>
 <div class="ali_toptips">请点击右上角，选择在浏览器中打开完成支付</div>
<?php
    echo $htmlText."<script>document.forms['alipaysubmit'].submit();</script>";
?>

