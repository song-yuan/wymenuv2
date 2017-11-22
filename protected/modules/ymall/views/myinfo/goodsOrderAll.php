<header class="mui-bar mui-bar-nav  mui-hbar">
	<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" style="color:white;"></a>
	<h1 class="mui-title" style="color:white;">全部订单</h1>
</header>
<div class="mui-content mui-scroll-wrapper" style="margin-bottom: 50px;">
	<div class=" mui-scroll">
	<?php if($goods_orders): ?>
	<?php foreach($goods_orders as  $goods_order): ?>
	<div class="mui-card">
		<div class="mui-card-header mui-card-media">
			<img src="<?php echo  Yii::app()->request->baseUrl; ?>/img/order_list.png" />
			<div class="mui-media-body">
				订单号:<?php echo $goods_order['account_no'];?>
				<p>下单日期: <?php echo $goods_order['create_at'];?></p>
			</div>
		</div>
		<div class="mui-card-content" >
			
		</div>
		<div class="mui-card-footer">
			<a class="mui-card-link">合计 : ¥ <?php echo $goods_order['reality_total']; ?></a>
			<a class="mui-card-link"><?php if($goods_order['paytype']==1){echo '<span style="color:green">线上支付</span>';}else if($goods_order['paytype']==2){echo '<span style="color:red">货到付款</span>';} ?></a>
			<a class="mui-card-link"><?php if($goods_order['pay_status']==1){echo '<span style="color:green">已付款</span>';}else if($goods_order['pay_status']==0){echo '<span style="color:red">未付款</span>';} ?></a>
			<a class="mui-card-link" href="<?php echo $this->createUrl('myinfo/orderDetail',array('companyId'=>$this->companyId,'account_no'=>$goods_order['account_no']));?>">查看详情</a>
		</div>
	</div>
	<?php endforeach;?>
	<?php endif;?>
	</div>
</div>
<script>
mui('.mui-scroll-wrapper').scroll();
</script>