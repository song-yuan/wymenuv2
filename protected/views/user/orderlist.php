<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('订单');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/user.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<section class="prolist">
	<ul class="orderList">
		<li><div class="item">时间</div><div class="item">类别</div><div class="item">金额</div><div class="item">状态</div><div class="clear"></div></li>
		<?php foreach($models as $model):?>
		<li><div class="item"></div><div class="item"><?php echo $model['create_at'];?></div><div class="item"><?php if($model['order_type']==1) echo '堂吃';else echo '外卖';?></div><div class="item"><?php echo $model['should_total'];?>元</div><div class="item"><?php if($model['order_status'] < 3) echo '<span class="ispay">待支付</span>';elseif($model['order_status'] == 3) echo '已支付';else echo '已完成';?></div><div class="clear"></div></li>
		<?php endforeach;?>
	</ul>
</section>