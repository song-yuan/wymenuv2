<header class="mui-bar mui-bar-nav  mui-hbar">
	<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" style="color:white;"></a>
	<h1 class="mui-title" style="color:white;">运输损耗</h1>
</header>
<div class="mui-content mui-scroll-wrapper" style="margin-bottom: 50px;">
	<div class=" mui-scroll">
	<?php if($goods_rejecteds): ?>
	<?php foreach($goods_rejecteds as $accountno => $goods_rejected): ?>
	<div class="mui-card">
		<div class="mui-card-header mui-card-media">
			<img src="<?php echo  Yii::app()->request->baseUrl; ?>/img/order_list.png" />
			<div class="mui-media-body">
				订单号:<?php echo $accountno;?>
				<p>退货日期: <?php echo $goods_rejected[0]['create_at'];?></p>
			</div>
		</div>
		<div class="mui-card-content" >
		<ul class="mui-table-view">
			<?php $toatle_price = 0; ?>
			<?php foreach ($goods_rejected as $key => $value):?>
			<li class="mui-table-view-cell mui-media">
				
				<img class="mui-media-object mui-pull-left" src="<?php if($value['main_picture']){ echo $value['main_picture'];}else{ echo 'http://menu.wymenu.com/wymenuv2/img/product_default.png';}?>">
				<div class="mui-media-body">
				<?php echo $value['goods_name'];?>
				<p class='mui-ellipsis'>
					<span class="mui-pull-left">单价 : ¥<?php echo $value['price'];?></span>
					<span class="mui-pull-right">x <?php echo $value['num'];?> </span>
				</p>
				<?php $toatle_price += $value['price']*$value['num']; ?>
				</div>
			</li>
			<?php endforeach; ?>

		</ul>
		</div>
		<div class="mui-card-footer">
			<a class="mui-card-link">合计 : </a>
			<a class="mui-card-link">¥ <?php echo $toatle_price; ?></a>
		</div>
	</div>
	<?php endforeach;?>
	<?php else: ?>
		<div class="mui-card">
			<div class="mui-card-header mui-card-media" style="height:85vw;background-image:url(http://menu.wymenu.com/wymenuv2/img/product_default.png)"></div>
				<div class="mui-card-content">
					<div class="mui-card-content-inner">
						<p style="color: #333;">温馨提示 : 该订单暂时没有损耗信息 ! ! !</p>
						<p>订单号 : <?php echo $account_no; ?></p>
					</div>
				</div>
				<div class="mui-card-footer">
					<a class="mui-pull-right">壹点吃</a>
				</div>
		</div>
	<?php endif;?>
	</div>
</div>
<script>
mui('.mui-scroll-wrapper').scroll();
</script>