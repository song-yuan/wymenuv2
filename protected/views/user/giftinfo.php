<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('礼品券');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/common.css">

<body class="gift_redeem bg_lgrey2">

	<!-- 获取兑换码 -->
	<div class="infoarea center">
		<span class="font_brown">请向店员出示此页面</span><br>
		<span class=""><?php echo $gift['intro'];?></span><br>
		<span class="small">有效期：<?php echo $gift['begin_time'];?>-<?php echo $gift['end_time'];?></span><br>
		<span class=""><img src="<?php echo $baseUrl.'/'.$gift['qrcode'];?>"/></span><br>
		<span class="redeemnum">兑换码：<?php echo $gift['code'];?></span><br>
		<!--
		<button class="bttn_large bttn_green">兑换</button>
		-->
	</div>
	<!-- 获取兑换码 -->

</body>


  
