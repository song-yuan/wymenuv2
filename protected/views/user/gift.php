<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('礼品券');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/common.css">

<body class="gift_exchange bg_lgrey2">
	<div id="topnav">
		<ul>
			<li class="notuse current"><a href="<?php echo $this->createUrl('/user/gift',array('companyId'=>$this->companyId));?>"><span>未使用</span></a></li>
			<li class="used"><a href="<?php echo $this->createUrl('/user/usedGift',array('companyId'=>$this->companyId));?>"><span>已使用</span></a></li>
			<li class="expired"><a href="<?php echo $this->createUrl('/user/expireGift',array('companyId'=>$this->companyId));?>"><span>已过期</span></a></li>
		</ul>
	</div>
	<div class="couponlist with_topbar">
		<!-- 未使用 -->
		<ul id="notuse">
			<?php foreach($gifts as $gift):?>
			<li>
				<img src="<?php echo $baseUrl.$gift['gift_pic'];?>" alt="">
				<span class="info">
					<h2><?php echo $gift['title'];?></h2>
					<div class="code">兑换码：<span class="font_org"><?php echo $gift['code'];?></span></div>
					<span class="small">有效期：<?php echo date('Y-m-d',strtotime($gift['begin_time']));?>-<?php echo date('Y-m-d',strtotime($gift['end_time']));?></span>
				</span>
			</li>
			<?php endforeach;?>
		</ul>
		<!-- 未使用 -->
	</div>

</body>


  
