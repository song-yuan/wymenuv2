<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('现金券');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/common.css">

<body class="gift_exchange bg_lgrey2">
	<div id="topnav">
		<ul>
			<li class="notuse current"><a href="<?php echo $this->createUrl('/user/cupon',array('companyId'=>$this->companyId));?>"><span>未使用</span></a></li>
			<li class="used"><a href="<?php echo $this->createUrl('/user/usedCupon',array('companyId'=>$this->companyId));?>"><span>已使用</span></a></li>
			<li class="expired"><a href="<?php echo $this->createUrl('/user/expireCupon',array('companyId'=>$this->companyId));?>"><span>已过期</span></a></li>
		</ul>
	</div>
	<div class="couponlist with_topbar">
		<!-- 未使用 -->
		<ul id="notuse">
			<?php foreach($cupons as $cupon):?>
			<li>
				<img src="<?php echo $baseUrl.$cupon['main_picture'];?>" alt="">
				<span class="info">
					<h2 style="margin-bottom:10px;"><?php echo $cupon['cupon_title'];?></h2>
					<span class="small">满<?php echo $cupon['min_consumer'];?>元减<?php echo $cupon['cupon_money'];?>元</span><br />
					<span class="small">有效期：<?php echo date('Y-m-d',strtotime($cupon['valid_day']));?>-<?php echo date('Y-m-d',strtotime($cupon['close_day']));?></span>
				</span>
			</li>
			<?php endforeach;?>
		</ul>
		<!-- 未使用 -->
	</div>

</body>


  
