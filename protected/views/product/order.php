<?php
/* @var $this ProductController */
	Yii::app()->clientScript->registerCssFile('../css/order.css');
	$orderPrice = 0;
	$orderNum = 0;
	$orderList = new OrderList($this->companyId,$this->siteNoId);
	if($orderList->order){
		$orderProductList = $orderList->OrderProductList($orderList->order['lid'],0,0,1);
		$price = $orderList->OrderPrice(0,1);
		$priceArr = explode(':',$price);
		$orderPrice = $priceArr[0];
		$orderNum = $priceArr[1];
	}else{
		$orderProductList = array();
	}
	//全单口味
	$tasteIds = TasteClass::getOrderTaste($orderList->order['lid'],1,$this->companyId);
?>
<script type="text/javascript" src="../js/product/taste.js"></script>
<form action="orderList?confirm=1&orderId=<?php echo $orderList->order['lid'];?>" method="post">
<div class="top"><?php echo yii::t('app','我的订单'); ?></div>
<div class="product-title"><?php echo yii::t('app','订单已经被锁定，其他人不能修改，需要最终修改数量和口味点击'); ?><img src="../img/product/down-arrow.png" /></div>
<?php foreach($orderProductList as $key=>$orderProduct):?>
	<!--非套餐-->
	<?php if($key):?>
	<div class="order-category"><?php echo OrderList::GetCatoryName($key,$this->companyId);?></div>
	<?php foreach($orderProduct as $order):?>
		<div class="product">
		<div class="product-up">
			<div class="product-up-left"><?php echo $order['product_name'];?></div>
		</div>
		<div class="product-middle select-taste"  data-id="<?php echo $order['lid'];?>" type="2" product-id="<?php echo $order['product_id'];?>">
			<font color="#ff8c00"><?php echo yii::t('app','口味要求'); ?></font><img src="../img/product/down-arrow.png" />:<?php $productTasteIds = TasteClass::getOrderTaste($order['lid'],2,$this->companyId);if($productTasteIds){ foreach($productTasteIds as $id){ echo TasteClass::getTasteName($id).' ';}}?> <?php echo yii::t('app','备注'); ?>:<?php echo TasteClass::getOrderTasteMemo($order['lid'],2,$this->companyId);?>
		</div>
	        <div class="product-down edit-num" product-id="<?php echo $order['product_id'];?>">￥<?php echo $order['price'];?>/ <?php echo $order['product_unit']?$order['product_unit']:'例';?> X <font color="#ff8c00"><span class="num"><?php echo $order['amount'];?></span><?php echo $order['product_unit']?$order['product_unit']:'例';?></font><img src="../img/product/down-arrow.png" /></div>
	        <input type="hidden" class="input-product" name="<?php echo $order['product_id'];?>" value="<?php echo $order['amount'];?>" price="<?php echo $order['price'];?>"/>
		</div>
		<?php if(!empty($order['addition'])):?>
			<?php foreach($order['addition'] as $order):?>
			<div class="product">
			<div class="product-up">
				<div class="product-up-left"><?php echo $order['product_name'];?>(<?php echo yii::t('app','加菜'); ?>)</div>
			</div>
			<div class="product-middle select-taste"  data-id="<?php echo $order['lid'];?>" type="2" product-id="<?php echo $order['product_id'];?>">
				<font color="#ff8c00"><?php echo yii::t('app','口味要求'); ?></font><img src="../img/product/down-arrow.png" />:<?php $productTasteIds = TasteClass::getOrderTaste($order['lid'],2,$this->companyId);if($productTasteIds){ foreach($productTasteIds as $id){ echo TasteClass::getTasteName($id).' ';}}?> <?php echo yii::t('app','备注:'); ?><?php echo TasteClass::getOrderTasteMemo($order['lid'],2,$this->companyId);?>
			</div>
		        <div class="product-down edit-num" product-id="<?php echo $order['product_id'];?>">￥<?php echo $order['price'];?>/ <?php echo $order['product_unit']?$order['product_unit']:'例';?> X <font color="#ff8c00"><span class="num"><?php echo $order['amount'];?></span><?php echo $order['product_unit']?$order['product_unit']:'例';?></font><img src="../img/product/down-arrow.png" /></div>
		        <input type="hidden" class="input-product" name="<?php echo $order['product_id'];?>" value="<?php echo $order['amount'];?>" price="<?php echo $order['price'];?>"/>
			</div>
			<?php endforeach;?>
		<?php endif;?>
	<?php endforeach;?>
	<?php else:?>
	<!--套餐-->
	<?php 
		// key是set_id $order 是该套餐对应产品的数组
		$productSets = array();
		foreach($orderProduct as $k=>$order){
		  $productSets[$order['set_id']][] = $order;
		}
	?>
	<?php foreach($productSets as $key=>$productSet):?>
		<div class="order-category"><?php echo ProductSetClass::GetProductSetName($key);?></div>
		<?php foreach($productSet as $order):?>
		<div class="product">
		<div class="product-up">
			<!--original_price 表示套餐产品的名称-->
			<div class="product-up-left"><?php echo $order['original_price']; ?></div>
		</div>
		<div class="product-middle select-taste"  data-id="<?php echo $order['lid'];?>" type="2" product-id="<?php echo $order['product_id'];?>">
			<font color="#ff8c00"><?php echo yii::t('app','口味要求'); ?></font><img src="../img/product/down-arrow.png" />:<?php $productTasteIds = TasteClass::getOrderTaste($order['lid'],2,$this->companyId);if($productTasteIds){ foreach($productTasteIds as $id){ echo TasteClass::getTasteName($id).' ';}}?> <?php echo yii::t('app','备注:'); ?><?php echo TasteClass::getOrderTasteMemo($order['lid'],2,$this->companyId);?>
		</div>
	        <div class="product-down edit-num" set-id="<?php echo $order['set_id'];?>" product-id="<?php echo $order['set_id'].'-'.$order['product_id'];?>">￥<?php echo $order['price'];?>/ <?php echo $order['product_unit']?$order['product_unit']:yii::t('app','例');?> X <font color="#ff8c00"><span class="num"><?php echo $order['amount'];?></span><?php echo $order['product_unit']?$order['product_unit']:yii::t('app','例');?></font><img src="../img/product/down-arrow.png" /></div>
	        <input type="hidden" set-id="<?php echo $order['set_id'];?>" name="<?php echo $order['set_id'].'-'.$order['product_id'];?>" value="<?php echo $order['amount'];?>"/>
		</div>
		<?php endforeach;?>
	<?php endforeach;?>
	
	<?php endif;?>
		
<?php endforeach;?>
	<div class="order-info">
		<div class="order-time"><?php echo date('Y-m-d H:i',time()); ?></div>
		<div class="order-price"><?php echo yii::t('app','订单总额:'); ?><span class="total-price"><?php echo Money::priceFormat($orderPrice); ?></span></div>
	</div>
	<div class="order-taste select-taste" data-id="<?php echo $orderList->order?$orderList->order['lid']:0;?>" type="1"><font color="#ff8c00"><?php echo yii::t('app','全单口味要求'); ?></font><img src="../img/product/down-arrow.png" />:<?php  if($tasteIds){ foreach($tasteIds as $id){ echo TasteClass::getTasteName($id).' ';}} ?> <?php echo yii::t('app','备注:'); ?><?php echo TasteClass::getOrderTasteMemo($orderList->order['lid'],1,$this->companyId);?></div>
</form>

<a id="comfirm-order" href="javascript:;"><div class="btn confirm"><?php echo yii::t('app','确认'); ?></div></a>
<a href="orderList"><div class="btn back"><?php echo yii::t('app','返回'); ?></div></a>
<div class="mask">
	<div class="mask-bottom">
		<div class="area-top"><?php echo yii::t('app','做法口味选择:'); ?></div>
		<div class="mask-taste">
			<!--<div class="taste"></div><div class="taste taste-active"></div>
			<div class="clear"></div>-->
		</div>
		<div class="mask-area">
			<div class="right-middle order-num"><span class="minus" >-</span><input type="text" name="order-product-num" value="1" readonly="true"/><span class="plus">+</span></div>
			<textarea name="taste-memo"></textarea>
			<div class="mask-button">
			  <div class="cancel"><?php echo yii::t('app','取消'); ?></div>
			  <div class="cancel-order-num"><?php echo yii::t('app','取消'); ?></div>
			  <div class="submit"><?php echo yii::t('app','确定'); ?></div>
			  <div class="submit-order-num"><?php echo yii::t('app','确定'); ?></div>
			  <div class="clear"></div>
			</div>
		</div>
		<input type="hidden" class="mask-type" value="0" />
		<input type="hidden" class="mask-id" value="0" /><!-- orderproductId or orderId-->
		<input type="hidden" class="set-id" value="0" />
		<input type="hidden" class="product-id" value="0" />
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	 $('.minus').click(function(){
		var input = $(this).siblings('input');
		var num = input.val();
		if(num > 0){
			num = num - 1;
		}
		input.val(num);			
	});
	$('.plus').click(function(){
		var input = $(this).siblings('input');
		var num = parseInt(input.val());
		num = num + 1;
		input.val(num);			
	});
	$('#comfirm-order').click(function(){
		$('form').submit();
	});
});
	
</script>
