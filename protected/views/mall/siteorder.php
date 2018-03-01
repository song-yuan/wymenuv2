<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('已点菜品');
	$orderPrice = 0;
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/order.css">
<script type="text/javascript" src="<?php echo $baseUrl.'/js/layer/layer.js';?>"></script>
<style>
.layui-layer-btn{height:42px;}
</style>
<!--  
<div class="order-title">已点菜品</div>
-->
<div class="order-site">
	<div class="lt">桌号:<?php echo $siteType['name'];?><?php echo $site['serial'];?></div>
	<div  class="rt"><a href="<?php echo $this->createUrl('/mall/index',array('companyId'=>$this->companyId,'type'=>$this->type));?>"><img style="width:25px;height:25px;vertical-align:middle;" alt="" src="../img/mall/icon_add.png">加菜</a></div>
	<div class="clear"></div>
</div>

<?php 
	foreach ($orders as $order):
	$orderPrice += $order['should_total'];
	$orderProducts = $order['product_list'];
?>
<div class="order-info">
	<div class="order-info-title">下单时间:<?php echo $order['create_at'];?></div>
	<?php if(!empty($order['taste'])):?>
	<div class="taste">整单口味:
		<?php foreach ($order['taste'] as $otaste): ?>
		<span> <?php echo $otaste['name'].'('.$otaste['price'].')';?> </span>
		<?php endforeach;?>
	</div>
	<?php endif;?>
		<?php foreach($orderProducts as $product):?>
		<div class="item">
			<div class="lt"><?php echo $product['product_name'];?></div>
			<div class="rt">x<?php echo $product['amount'];?> ￥<?php echo number_format($product['price'],2);?></div>
			<div class="clear"></div>
		</div>
			<?php if(isset($product['taste'])&&!empty($product['taste'])):?>
			<div class="taste">口味:
			<?php foreach ($product['taste'] as $taste):?>
			<span> <?php echo $taste['name'].'('.$taste['price'].')';?> </span>
			<?php endforeach;?>
			</div>
			<?php endif;?>
			
			<?php if(isset($product['detail'])&&!empty($product['detail'])):?>
			<div class="taste">
			<?php foreach ($product['detail'] as $detail):?>
			<span> <?php echo $detail['product_name'];?> </span>
			<?php endforeach;?>
			</div>
			<?php endif;?>
			
		<?php endforeach;?>
	</div>
<div class="ht-10"></div>
<?php endforeach;?>

<div class="bottom"></div>

<footer>
    <div class="ft-lt">
        <p>总计￥<span id="total" class="total"><?php echo number_format($orderPrice,2);?></span></p>
    </div>
    <div class="ft-rt">
        <p><a id="payorder" href="javascript:;">去买单</a></p>
    </div>
    <div class="clear"></div>
</footer>



<script>
$(document).ready(function(){
	$('#payorder').click(function(){
		location.href = '<?php echo $this->createUrl('/mall/checkSiteOrder',array('companyId'=>$this->companyId,'siteNoId'=>$siteNo['lid']));?>';
	});
});
</script>
