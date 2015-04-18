<?php
/* @var $this ProductController */
	Yii::app()->clientScript->registerCssFile('../css/cartlist.css');
	$orderPrice = 0;
	$orderNum = 0;
	$orderPricePay = 0;
	$orderPayNum = 0;
	$orderList = new OrderList($this->companyId,$this->siteNoId);
	if($orderList->order){
		$orderProductList = $orderList->OrderProductList($orderList->order['lid'],0,1);
		$orderProductListPay = $orderList->OrderProductList($orderList->order['lid'],1);
		$price = $orderList->OrderPrice(0);
		$priceArr = explode(':',$price);
		$orderPrice = $priceArr[0];
		$orderNum = $priceArr[1];
		
		$pricePay = $orderList->OrderPrice(1);
		$pricePayArr = explode(':',$pricePay);
		$orderPricePay = $pricePayArr[0];
		$orderPayNum = $pricePayArr[1];
	}else{
		$orderProductList = array();
		$orderProductListPay = array();
	}
?>
<script type="text/javascript" src="../js/product/taste.js"></script>
	<div class="top"><a href="index"><div class="back"><img src="../img/product/back.png" /> 返回</div></a><a id="order" href="javascript:;"><button class="create-order">下单</button></a></div>
	<form action="order?orderId=<?php echo $orderList->order?$orderList->order['lid']:0;?>" method="post">
	<div class="order-top"><div class="order-top-left"><span>￥<?php echo Money::priceFormat($orderPrice);?> 共<?php echo $orderNum;?>份</span></div><div class="order-top-right select-taste" data-id="<?php echo $orderList->order?$orderList->order['lid']:0;?>" type="1" style="color:#ff8c00">全单口味<img src="../img/product/down-arrow.png" /></div></div>
	<?php if($orderProductList):?>
	<?php foreach($orderProductList as $key=>$orderProduct):?>
		<!--非套餐-->
		<?php if($key):?>
		<div class="order-category"><?php echo OrderList::GetCatoryName($key);?></div>
		<?php foreach($orderProduct as $order):?>
		<div class="order-product">
			<div class="order-product-left"><img src="<?php echo $order['main_picture'];?>" /></div>
			<div class="order-product-right">
				<div class="right-up"><?php echo $order['product_name'];?></div>
		               <div class="right-middle"><span class="minus" >-</span><input type="text" name="<?php echo $order['product_id'];?>" value="<?php echo $order['amount'];?>" readonly="true"/><span class="plus">+</span></div>
				<div class="right-down">
		                    <div class="right-down-left">￥<?php echo $order['price'];?></div>
				 <div class="right-down-right select-taste"  data-id="<?php echo $order['lid'];?>" type="2" product-id="<?php echo $order['product_id'];?>" style="color:#ff8c00">口味<img src="../img/product/down-arrow.png" /></div>	
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<?php endforeach;?>
		<?php else:?>
		<!--套餐-->
		<?php foreach($orderProduct as $k=>$order):?>
		<div class="order" set-id="<?php echo $order['set_id'];?>">
		<div class="order-category"><?php echo $order['product_name'];?><div class="order-category-right"><span class="minus" >-</span><input class="set-num" type="text" name="<?php echo $order['set_id'];?>" value="<?php echo $order['amount'];?>" readonly="true"/><span class="plus">+</span></div></div>
			<?php $setProducts = $orderList->GetSetProduct($order['set_id']);?>
			<?php foreach($setProducts as $key=>$setProduct):?>
				<?php foreach($setProduct as $product):?>
				<div class="order-product group-<?php echo $key;?>" <?php if($product['is_select']) echo 'style="display:block;"';else echo 'style="display:none;"';?>>
					<div class="order-product-left"><img src="<?php echo $product['main_picture'];?>" /></div>
					<div class="order-product-right">
						<div class="right-up"><?php echo $product['product_name'];?></div>
				               <div class="right-middle">组<?php echo $key+1;?><input class="set-group-radio" name="group-<?php echo $k.'-'.$key;?>" type="radio" set-id="<?php echo $order['set_id'];?>" product-id="<?php echo $product['product_id'];?>" value="<?php echo $product['product_id'];?>" <?php if($product['is_select']) echo 'checked';?>/>
				               <?php if(count($setProduct) > 1):?><div class="right-down-right select-setproduct"  style="color:#ff8c00">更换<img src="../img/product/down-arrow.png" /></div>	<?php endif;?>
				        </div>
						<div class="right-down">
				                    <div class="right-down-left">￥<?php echo $product['price'];?></div>
						 <div class="right-down-right select-taste"  data-id="<?php echo $order['lid'];?>" type="2" product-id="<?php echo $product['product_id'];?>" style="color:#ff8c00">口味<img src="../img/product/down-arrow.png" /></div>	
						</div>
					</div>
					<div class="clear"></div>
				</div>
				<?php endforeach;?>
			<?php endforeach;?>
			</div>
		<?php endforeach;?>
		<?php endif;?>
	<?php endforeach;?>
	<?php endif;?>
	</form>

	<?php if($orderProductListPay):?>
	<div style="color:#555da8;border-top:2px solid;margin-top: 5px">
	<div class="order-top"><div class="order-top-left">下单总额 :<span> <?php echo Money::priceFormat($orderPricePay);?></span></div><div class="order-top-right"><button class="online-pay">在线支付</button></div></div>
	<div class="order-time"><div class="order-time-left"><?php echo date('Y-m-d H:i:s',time());?></div><div class="order-time-right select-taste" data-id="<?php echo $orderList->order?$orderList->order['lid']:0;?>" type="3" product-id="0" style="color:#ff8c00">全单有话要说<img src="../img/product/down-arrow.png" /></div></div>
	<?php foreach($orderProductListPay as $key=>$orderProduct):?>
		<!--非套餐-->
		<?php if($key):?>
		<div class="order-category"><?php echo OrderList::GetCatoryName($key);?></div>
	   <?php foreach($orderProduct as $order):?>
		<div class="order-product">
			<div class="order-product-left"><img src="<?php echo $order['main_picture'];?>" /></div>
			<div class="order-product-right">
				<div class="right-up"><?php echo $order['product_name'];?></div>
				<div class="right-middle">
		                    <div class="right-down-left">￥<?php echo $order['price'];?>/例 X <font color="#ff8c00"><?php echo $order['amount'];?>例</font></div>
				</div>
				<div class="right-down">
				<font color="#ff8c00">口味要求</font>:<?php $productTasteIds = TasteClass::getOrderTaste($order['lid'],2);if($productTasteIds){ foreach($productTasteIds as $id){ echo TasteClass::getTasteName($id).' ';}}?> 备注:<?php echo TasteClass::getOrderTasteMemo($order['lid'],2);?>
				</div>
			</div>
			<div class="clear"></div>
		</div>
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
				<div class="order-product">
				<div class="order-product-left"><img src="<?php echo $order['main_picture'];?>" /></div>
				<div class="order-product-right">
				<div class="right-up"><?php echo $order['original_price'];?></div>
				<div class="right-middle">
		                    <div class="right-down-left">￥<?php echo $order['price'];?>/例 X <font color="#ff8c00"><?php echo $order['amount'];?>例</font></div>
				</div>
				<div class="right-down">
				<font color="#ff8c00">口味要求</font>:<?php $productTasteIds = TasteClass::getOrderTaste($order['lid'],2);if($productTasteIds){ foreach($productTasteIds as $id){ echo TasteClass::getTasteName($id).' ';}}?> 备注:<?php echo TasteClass::getOrderTasteMemo($order['lid'],2);?>
				</div>
			</div>
			<div class="clear"></div>
		</div>
			<?php endforeach;?>
		<?php endforeach;?>
		<?php endif;?>
	<?php endforeach;?>
	</div>
	<?php endif;?>
	<div class="mask">
		<div class="mask-bottom">
			<div class="area-top">做法口味选择:</div>
			<div class="mask-taste">
				<!--<div class="taste"></div><div class="taste taste-active"></div>
				<div class="clear"></div>-->
			</div>
			<div class="mask-area">
				<textarea name="taste-memo"></textarea>
				<div class="mask-button">
				  <div class="cancel">取消</div>
				  <div class="submit">确定</div>
				  <div class="clear"></div>
				</div>
			</div>
			<input type="hidden" class="mask-type" value="0" />
			<input type="hidden" class="mask-id" value="0" /><!-- orderproductId or orderId-->
			<input type="hidden" class="product-id" value="0" />
		</div>
	</div>
<script>
	function set_num(){
		$('.order').each(function(){
			var setnumObj = $(this).find('input[class="set-num"]');
			var setId = $(this).attr('set-id');
			var productId = '';
			$(this).find('input[class="set-group-radio"]:checked').each(function(){
				productId +=$(this).attr('product-id')+'-';
			});
			productId = productId.substring(0,productId.length-1);
	        setnumObj.attr('name',setId+','+productId);
		});
	}
	$(function(){
		window.onload = set_num;
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
		$('#order').click(function(){
			$('form').submit();
		});
		$('.select-setproduct').click(function(){
			var group = $(this).parents('.order-product').attr('class');
			var groupObj= new Array();
			groupObj=group.split(" "); 
			$('.'+groupObj[1]).each(function(e){
				if(!$(this).find('input[type="radio"]').is(':checked')){
					$(this).toggle();
				}
			});
		});
		$('.set-group-radio').change(function(){
			var setId = $(this).attr('set-id');
			var productId = '';
			$('input[class="set-group-radio"]:checked').each(function(){
				productId +=$(this).attr('product-id')+'-';
			});
			productId = productId.substring(0,productId.length-1);
			$(this).parents('.order-product').find('.select-setproduct').trigger('click');
			var setnumObj = $(this).parents('.order').find('input[class="set-num"]');
	        setnumObj.attr('name',setId+','+productId);
		});
	});
</script>