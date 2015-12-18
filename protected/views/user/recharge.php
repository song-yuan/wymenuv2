<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('个人中心');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/recharge.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl.'/js/layer/layer.js';?>"></script>
<div class="section">
	<div class="title">充值</div>
	<div class="item"><div class="top">50元</div><div class="down">返0元</div></div>
	<div class="item"><div class="top">100元</div><div class="down">返10元</div></div>
	<div class="item"><div class="top">150元</div><div class="down">返20元</div></div>
	<div class="item"><div class="top">200元</div><div class="down">返30元</div></div>
	<div class="clear"></div>
</div>