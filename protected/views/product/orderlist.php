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
		$orderProductListPay = $orderList->OrderProductList($orderList->order['lid'],1,0,1);
		$price = $orderList->OrderPrice(0,1);
		$priceArr = explode(':',$price);
		$orderPrice = $priceArr[0];
		$orderNum = $priceArr[1];
		
		$pricePay = $orderList->OrderPrice(1,0,1);
		$pricePayArr = explode(':',$pricePay);
		$orderPricePay = $pricePayArr[0];
		$orderPayNum = $pricePayArr[1];
	}else{
		$orderProductList = array();
		$orderProductListPay = array();
	}
?>
<script type="text/javascript" src="../js/product/taste.js"></script>
	<div class="top"><a href="index"><div class="back"><img src="../img/product/back.png" /> <?php echo yii::t('app','返回'); ?></div></a><a id="order" href="javascript:;"><button class="create-order"><?php echo yii::t('app','下单'); ?></button></a></div>
	<form action="order?orderId=<?php echo $orderList->order?$orderList->order['lid']:0;?>" method="post">
	<div class="order-top"><div class="order-top-left"><span>￥<span class="total-price"><?php echo Money::priceFormat($orderPrice); ?></span><?php echo yii::t('app','共'); ?><span class="total-num"><?php echo $orderNum;?></span><?php echo yii::t('app','份'); ?></span></div><div class="order-top-right select-taste" data-id="<?php echo $orderList->order?$orderList->order['lid']:0;?>" type="1" style="color:#ff8c00"><?php echo yii::t('app','全单口味'); ?><img src="../img/product/down-arrow.png" /></div></div>
	<?php if($orderProductList):?>
	<?php foreach($orderProductList as $key=>$orderProduct):?>
		<!--非套餐-->
		<?php if($key):?>
		<div class="order-category"><?php echo OrderList::GetCatoryName($key,$this->companyId);?></div>
		<?php foreach($orderProduct as $order):?>
		<div class="order-product">
			<div class="order-product-left"><img src="<?php echo $order['main_picture'];?>" /></div>
			<div class="order-product-right">
				<div class="right-up"><?php echo $order['product_name'];?></div>
		               <div class="right-middle"><span class="minus" >-</span><input class="input-product" type="text" name="<?php echo $order['product_id'];?>" value="<?php echo $order['amount'];?>" price="<?php echo $order['price'];?>" readonly="true"/><span class="plus">+</span></div>
				<div class="right-down">
		          <div class="right-down-left">￥<?php echo $order['price'];?></div>
		         <?php if(!empty($order['addition'])):?>
		         <div class="right-down-right add-product"  data-id="<?php echo $order['lid'];?>" product-id="<?php echo $order['product_id'];?>" style="color:#ff8c00"><?php echo yii::t('app','加菜'); ?><img src="../img/product/down-arrow.png" /></div>	
				 <?php endif;?>
				 <div class="right-down-right select-taste"  data-id="<?php echo $order['lid'];?>" type="2" product-id="<?php echo $order['product_id'];?>" style="color:#ff8c00"><?php echo yii::t('app','口味'); ?><img src="../img/product/down-arrow.png" /></div>	
				</div>
			</div>
			<div class="clear"></div>
		</div>
		
		<div class="product-has-addtion">
		<?php if(!empty($order['hasAddition'])):?>
		<?php foreach($order['hasAddition'] as $product):?>
			<div class="order-product">
				<div class="order-product-left"><img src="<?php echo $product['main_picture'];?>" /></div>
				<div class="order-product-right">
					<div class="right-up"><?php echo $product['product_name'];?>(<?php echo yii::t('app','加菜'); ?>)</div>
			               <div class="right-middle"><span class="minus" >-</span><input class="input-product" type="text" name="<?php echo $product['product_id'];?>" addtionid="<?php echo $product['lid'];?>" value="<?php echo $product['amount'];?>"  price="<?php echo $product['price'];?>" readonly="true"/><span class="plus">+</span></div>
					<div class="right-down">
			          <div class="right-down-left">￥<?php echo $product['price'];?></div>
					   <div class="right-down-right select-taste"  data-id="<?php echo $order['lid'];?>" type="2" product-id="<?php echo $order['product_id'];?>" style="color:#ff8c00"><?php echo yii::t('app','口味'); ?><img src="../img/product/down-arrow.png" /></div>						
					</div>
				</div>
				<div class="clear"></div>
			</div>
		<?php endforeach;?>
		<?php endif;?>
		</div>
		
		<div class="product-addtion product-addtion-<?php echo $order['product_id']?>">
			<?php if(!empty($order['addition'])):?>
			<?php foreach($order['addition'] as $product):?>
				<div class="order-product">
					<div class="order-product-left"><img src="<?php echo $product['main_picture'];?>" /></div>
					<div class="order-product-right">
						<div class="right-up"><?php echo $product['product_name'];?></div>
				               <div class="right-middle"><a href="javascript:;" class="right-down-right add-product-addition"  order-id="<?php echo $order['order_id'];?>" data-id="<?php echo $product['lid'];?>" style="color:#ff8c00"><?php echo yii::t('app','加菜 +'); ?></a></div>
						<div class="right-down">
				          <div class="right-down-left">￥<?php echo $product['price'];?></div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			<?php endforeach;?>
			<?php endif;?>
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
				               <div class="right-middle"><?php echo yii::t('app','组'); ?><?php echo $key+1;?><input class="set-group-radio" name="group-<?php echo $k.'-'.$key;?>" type="radio" set-id="<?php echo $order['set_id'];?>" product-id="<?php echo $product['product_id'];?>" value="<?php echo $product['product_id'];?>" <?php if($product['is_select']) echo 'checked';?>/>
				               <?php if(count($setProduct) > 1):?><div class="right-down-right select-setproduct"  style="color:#ff8c00"><?php echo yii::t('app','更换'); ?><img src="../img/product/down-arrow.png" /></div>	<?php endif;?>
				        </div>
						<div class="right-down">
				                    <div class="right-down-left">￥<?php echo $product['price'];?></div>
						 <div class="right-down-right select-taste"  data-id="<?php echo $order['lid'];?>" type="2" product-id="<?php echo $product['product_id'];?>" style="color:#ff8c00"><?php echo yii::t('app','口味'); ?><img src="../img/product/down-arrow.png" /></div>	
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
	<div class="order-top"><div class="order-top-left"><?php echo yii::t('app','下单总额 :'); ?><span> <?php echo Money::priceFormat($orderPricePay);?></span></div><div class="order-top-right"><button class="online-pay"><?php echo yii::t('app','在线支付'); ?></button></div></div>
	<div class="order-time"><div class="order-time-left"><?php echo date('Y-m-d H:i:s',time());?></div><div class="order-time-right select-taste" data-id="<?php echo $orderList->order?$orderList->order['lid']:0;?>" type="3" product-id="0" style="color:#ff8c00"><?php echo yii::t('app','呼叫服务员'); ?><img src="../img/product/down-arrow.png" /></div></div>
	<?php foreach($orderProductListPay as $key=>$orderProduct):?>
		<!--非套餐-->
		<?php if($key):?>
		<div class="order-category"><?php echo OrderList::GetCatoryName($key,$this->companyId);?></div>
	   <?php foreach($orderProduct as $order):?>
		<div class="order-product">
			<div class="order-product-left"><img src="<?php echo $order['main_picture'];?>" /></div>
			<div class="order-product-right">
				<div class="right-up"><?php echo $order['product_name'];?></div>
				<div class="right-middle">
		                    <div class="right-down-left">￥<?php echo $order['price'];?><?php echo yii::t('app','/例 X'); ?> <font color="#ff8c00"><?php echo $order['amount'];?><?php echo yii::t('app','例'); ?></font></div>
				</div>
				<div class="right-down">
				<font color="#ff8c00"><?php echo yii::t('app','口味要求'); ?></font>:<?php $productTasteIds = TasteClass::getOrderTaste($order['lid'],2,$this->companyId);if($productTasteIds){ foreach($productTasteIds as $id){ echo TasteClass::getTasteName($id).' ';}}?> <?php echo yii::t('app','备注:'); ?><?php echo TasteClass::getOrderTasteMemo($order['lid'],2,$this->companyId);?>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<?php if(!empty($order['addition'])):?>
		<?php foreach($order['addition'] as $order):?>
		<div class="order-product">
			<div class="order-product-left"><img src="<?php echo $order['main_picture'];?>" /></div>
			<div class="order-product-right">
				<div class="right-up"><?php echo $order['product_name'];?>(<?php echo yii::t('app','加菜'); ?>)</div>
				<div class="right-middle">
		                    <div class="right-down-left">￥<?php echo $order['price'];?><?php echo yii::t('app','/例 X'); ?> <font color="#ff8c00"><?php echo $order['amount'];?><?php echo yii::t('app','例'); ?></font></div>
				</div>
				<div class="right-down">
				<font color="#ff8c00"><?php echo yii::t('app','口味要求'); ?></font>:<?php $productTasteIds = TasteClass::getOrderTaste($order['lid'],2,$this->companyId);if($productTasteIds){ foreach($productTasteIds as $id){ echo TasteClass::getTasteName($id).' ';}}?> <?php echo yii::t('app','备注:'); ?><?php echo TasteClass::getOrderTasteMemo($order['lid'],2,$this->companyId);?>
				</div>
			</div>
			<div class="clear"></div>
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
			<div class="order-category"><?php echo ProductSetClass::GetProductSetName($this->companyId,$key);?></div>
			<?php foreach($productSet as $order):?>
				<div class="order-product">
				<div class="order-product-left"><img src="<?php echo $order['main_picture'];?>" /></div>
				<div class="order-product-right">
				<div class="right-up"><?php echo $order['original_price'];?></div>
				<div class="right-middle">
		                    <div class="right-down-left">￥<?php echo $order['price'];?><?php echo yii::t('app','/例 X'); ?> <font color="#ff8c00"><?php echo $order['amount'];?><?php echo yii::t('app','例'); ?></font></div>
				</div>
				<div class="right-down">
				<font color="#ff8c00"><?php echo yii::t('app','口味要求'); ?></font>:<?php $productTasteIds = TasteClass::getOrderTaste($order['lid'],2,$this->companyId);if($productTasteIds){ foreach($productTasteIds as $id){ echo TasteClass::getTasteName($id).' ';}}?> <?php echo yii::t('app','备注:'); ?><?php echo TasteClass::getOrderTasteMemo($order['lid'],2,$this->companyId);?>
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
	<div class="product-mask-top"></div>
	<div class="product-mask-bottom"></div>
	<div class="mask">
		<div class="mask-bottom">
			<div class="area-top"><?php echo yii::t('app','做法口味选择:'); ?></div>
			<div class="mask-taste">
				<!--<div class="taste"></div><div class="taste taste-active"></div>
				<div class="clear"></div>-->
			</div>
			<div class="mask-area">
				<textarea name="taste-memo"></textarea>
				<div class="mask-button">
				  <div class="cancel"><?php echo yii::t('app','取消'); ?></div>
				  <div class="submit"><?php echo yii::t('app','确定'); ?></div>
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
			totalPrice();
			totalNum();		
		});
		$('.plus').click(function(){
			var input = $(this).siblings('input');
			var num = parseInt(input.val());
			num = num + 1;
			input.val(num);	
			totalPrice();	
			totalNum();	
		});
		$('#order').click(function(){
			$('form').submit();
		});
		//加菜
		$('.add-product').click(function(){
			var productId = $(this).attr('product-id');
			var docHeight = $(document).height();
			var parents = $(this).parents('.order-product').next('.product-has-addtion');
			var top = $('.product-mask-top');
			var bottom = $('.product-mask-bottom');
			var height = parseInt(parents.offset().top) + parents.height();
			top.css('height',height);
			bottom.css('height',docHeight - height);
			$('.product-addtion-'+productId).css('display','block');
			top.attr('product-id',productId);
			bottom.attr('product-id',productId);
			top.css('display','block');
			bottom.css('display','block');
			$('body').scrollTop(parseInt(height) - 40);
		});
		
		//隐藏
		$('.product-mask-top').click(function(){
			var productId = $(this).attr('product-id');
			$('.product-addtion-'+productId).css('display','none');
			$(this).css('display','none');
			$('.product-mask-bottom').css('display','none');
		});
		$('.product-mask-bottom').click(function(){
			var productId = $(this).attr('product-id');
			$('.product-addtion-'+productId).css('display','none');
			$(this).css('display','none');
			$('.product-mask-top').css('display','none');
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
		
		$('.add-product-addition').click(function(){
			var orderId = $(this).attr('order-id');
			var id = $(this).attr('data-id');
			$.ajax({
				url:'/wymenuv2/product/addProductAddition',
				data:{orderId:orderId,id:id},
				success:function(msg){
					if(msg.status){
						var orderProductLid = msg.lastLid;
						var addtionInput = $('input[addtionid="'+orderProductLid+'"]');
						if(addtionInput.length>0){
							var val = addtionInput.val();
							addtionInput.val(parseInt(val)+1);
						}else{
							var str = '';
							str +='<div class="order-product">';
							str +='<div class="order-product-left"><img src="'+msg.data.main_picture+'" /></div>';
							str +='<div class="order-product-right">';
							str +='<div class="right-up">'+msg.data.product_name+'(<?php echo yii::t('app','加菜'); ?>)</div>';
							str +='<div class="right-middle"><span class="minus" >-</span><input class="input-product" type="text" name="'+msg.data.sproduct_id+'" addtionid="'+msg.lastLid+'" value="1" price="'+msg.data.price+'" readonly="true"/><span class="plus">+</span></div>';
							str +='<div class="right-down">';
							str +='<div class="right-down-left">￥'+msg.data.price+'</div>';
							str +='<div class="right-down-right select-taste"  data-id="'+msg.lastLid+'" type="2" product-id="'+msg.data.sproduct+'" style="color:#ff8c00">口味<img src="../img/product/down-arrow.png" /></div>';						
							str +='</div></div><div class="clear"></div></div>';
							$('.product-has-addtion').append(str);
							var top = $('.product-mask-top');
							var height = top.height() + 141;
							top.css('height',height);
						}
						totalPrice();
						totalNum();
						alert(msg.msg);
					}else{
						alert(msg.msg);
					}
				},
				'dataType':'json',
			});
		});
	});
</script>