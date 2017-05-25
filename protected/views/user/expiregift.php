<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('礼品券');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/common.css">

<body class="gift_exchange bg_lgrey2">
	<div id="topnav">
		<ul>
			<li class="notuse"><a href="<?php echo $this->createUrl('/user/gift',array('companyId'=>$this->companyId));?>"><span>未使用</span></a></li>
			<li class="used"><a href="<?php echo $this->createUrl('/user/usedGift',array('companyId'=>$this->companyId));?>"><span>已使用</span></a></li>
			<li class="expired current"><a href="<?php echo $this->createUrl('/user/expireGift',array('companyId'=>$this->companyId));?>"><span>已过期</span></a></li>
		</ul>
	</div>
	<div class="couponlist with_topbar">
		
		<!-- 已过期 -->
		<ul id="expired">
			<?php foreach($gifts as $gift):?>
			<li>
				<img src="<?php echo $baseUrl.$gift['gift_pic'];?>" alt="">
				<span class="info unavailable"><h2><?php echo $gift['title'];?></h2><span class="small">有效期：<?php echo date('Y-m-d',strtotime($gift['valid_day']));?>-<?php echo date('Y-m-d',strtotime($gift['close_day']));?></span></span>
				<span class="status"><img src="<?php echo $baseUrl;?>/img/mall/coupon_expired.png" alt=""></span>
			</li>
			<?php endforeach;?>
		</ul>
		<!-- 已过期 -->

	</div>

</body>


  
