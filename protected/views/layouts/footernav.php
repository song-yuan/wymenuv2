<?php 
 $baseUrl = $baseUrl = Yii::app()->baseUrl;
?>
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/footernav.css">
<!-- 固定按钮 -->
	<div class="bttnbar bg_dgrey2">
	<?php if($this->id == 'user'&&$this->getAction()->getId()=='orderList'):?>
		<ul>
			<li><a href="#"><div><img src="<?php echo $baseUrl;?>/img/mall/navmain.png" alt=""></div><div class="name">首页</div></a></li>
			<li><a href="<?php echo $this->createUrl('/user/orderList',array('companyId'=>$this->companyId));?>"><div><img src="<?php echo $baseUrl;?>/img/mall/navmall1.png" alt=""></div><div class="name active">订单</div></a></li>
			<li><a href="<?php echo $this->createUrl('/user/index',array('companyId'=>$this->companyId));?>"><div><img src="<?php echo $baseUrl;?>/img/mall/navuser.png" alt=""></div><div class="name">我</div></a></li>
		</ul>
	<?php elseif($this->id == 'user'&&$this->getAction()->getId()=='index'):?>
		<ul>
			<li><a href="#"><div><img src="<?php echo $baseUrl;?>/img/mall/navmain.png" alt=""></div><div class="name">首页</div></a></li>
			<li><a href="<?php echo $this->createUrl('/user/orderList',array('companyId'=>$this->companyId));?>"><div><img src="<?php echo $baseUrl;?>/img/mall/navmall.png" alt=""></div><div class="name">订单</div></a></li>
			<li><a href="<?php echo $this->createUrl('/user/index',array('companyId'=>$this->companyId));?>"><div><img src="<?php echo $baseUrl;?>/img/mall/navuser1.png" alt=""></div><div class="name active">我</div></a></li>
		</ul>
	<?php else:?>
		<ul>
			<li><a href="#"><div><img src="<?php echo $baseUrl;?>/img/mall/navmain1.png" alt=""></div><div class="name active">首页</div></a></li>
			<li><a href="<?php echo $this->createUrl('/user/orderList',array('companyId'=>$this->companyId));?>"><div><img src="<?php echo $baseUrl;?>/img/mall/navmall.png" alt=""></div><div class="name">订单</div></a></li>
			<li><a href="<?php echo $this->createUrl('/user/index',array('companyId'=>$this->companyId));?>"><div><img src="<?php echo $baseUrl;?>/img/mall/navuser.png" alt=""></div><div class="name">我</div></a></li>
		</ul>
	<?php endif;?>
	</div>
<!-- 固定按钮 -->