<?php
/* @var $this ProductController */
	Yii::app()->clientScript->registerCssFile('css/productinfo.css');
?>
<!--<div class="pro-img">
	<img src="<?php echo $product->main_picture;?>" width="100%" />
</div>-->
<div class="pro-info">
	<div class="pro-name">
	<?php echo $product->product_name;?>
	</div>
	<div class="pro-price">
	 <label style="color:red;font-size:22px;">￥<?php echo sprintf("%.2f", $product->price);?></label><br/>
	 <label>原价:<strike>￥<?php echo sprintf("%.2f", $product->origin_price);?></strike></label>
	</div>
</div>
<div class="pro-des">
<?php echo $product->description;?>
</div>