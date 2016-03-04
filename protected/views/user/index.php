<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('个人中心');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/common.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/members.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<body class="members bg_lgrey2">
	<div class="toparea">
		<div class="maininfo">
		<div class="left headImg">
			<img src="<?php echo $user['head_icon'];?>" class="avatar">
		</div>
		
		<div class="overlay_blk overlay"></div>
		<div class="info">
			<span><?php echo $user['nickname'];?></span><br>
			<span class="small">会员卡号：<?php echo substr($user['card_id'],5);?></span><br>
			<button type="button" class="bttnupdate"><?php if($userLevel):?><?php echo $userLevel['level_name'];?><?php else:?>普通会员<?php endif;?></button>
		</div>
	</div>
	
	</div>
	
	<!--
	<div class="listset withtop">
		<div class="arrowright"><a href="<?php echo $this->createUrl('/user/orderList',array('companyId'=>$this->companyId));?>">我的订单</a></div>
	</div>
	
	<div class="listset">
		<div class="arrowright"><a href="shopping_cart.php">购物车 <span class="dot bg_red"></span></a></div>
	</div>
	-->
	<div class="listset">
		<div class="arrowright"><a href="<?php echo $this->createUrl('/mall/reCharge',array('companyId'=>$this->companyId));?>">余额 <span class="small font_l"> <?php echo $remainMoney;?></span></a>
		</div>
	</div>
	
	<div class="listset">
		<div class="arrowright"><a href="<?php echo $this->createUrl('/user/cupon',array('companyId'=>$this->companyId));?>">现金券</a></div>
		<div class="arrowright"><a href="<?php echo $this->createUrl('/user/gift',array('companyId'=>$this->companyId));?>">礼品券</a></div>
	</div>
	<div class="listset">
		<div class="arrowright"><a href="<?php echo $this->createUrl('/user/address',array('companyId'=>$this->companyId));?>">收货地址管理</a></div>
		<div class="arrowright"><a href="<?php echo $this->createUrl('/user/setUserInfo',array('companyId'=>$this->companyId));?>">完善个人资料</a></div>
	</div>
	<?php 
	include_once(Yii::app()->basePath.'/views/layouts/footernav.php');
	?>
	<script type="text/javascript">
	$('document').ready(function(){
		$('.headImg').click(function(){
			$.ajax({
				url:'<?php echo $this->createUrl('/user/ajaxHeadIcon',array('companyId'=>$this->companyId));?>',
				dataType:'post',
				data:{userId:<?php echo $user['lid'];?>},
				success:function(msg){
					if(msg){
						$('.headImg').find('img').attr('src',msg);
					}
				}
			});
		});
	});
	</script>
</body>