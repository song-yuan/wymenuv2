<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('确认订单');
	$orderTatsePrice = 0.00;
	$fval = '0-0-0';// 满类型-满lid-送lid
	$isCupon = false;
	if(!empty($cupons)){
		$isCupon = true;
	}
?>
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/weui.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/cart.css?_t=201911201607">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/order.css">

<form action="<?php echo $this->createUrl('/mall/generalZizhuOrder',array('companyId'=>$this->companyId,'orderId'=>$orderId,'type'=>$this->type));?>" method="post">

<div class="order-info">
	<?php foreach($orderProducts as $product):?>
	<div class="item">
		<div class="lt"><?php echo $product['product_name'];?></div>
		<div class="rt">x<?php echo $product['amount'];?> ￥<?php echo number_format($product['price'],2);?></div>
		<div class="clear"></div>
	</div>
		<?php if(isset($product['taste'])&&!empty($product['taste'])):?>
		<div class="taste">
		<?php foreach ($product['taste'] as $taste):?>
		<span> <?php echo $taste['name'];?> </span>
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

<div class="activity-info">
	<?php if($memdisprice):?>
	<div class="order-copun disabled">
		<div class="copun-lt">会员折扣</div>
		<div class="copun-rt"><?php echo '-￥'.number_format($memdisprice,2);?></div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
	<?php if(!empty($fullsent)):?>
		<?php if($fullsent['full_type']):
				$fminusprice = $fullsent['extra_cost'];
				$memdisprice += $fminusprice;
				$fval = '1-'.$fullsent['lid'].'-0';
		?>
		<div class="order-copun disabled">
			<div class="copun-lt">满减优惠</div>
			<div class="copun-rt"><?php echo '-￥'.$fminusprice;?></div>
			<div class="clear"></div>
		</div>
		<?php else:?>
			<div class="order-copun arrowright fullsent">
				<div class="copun-lt">满送优惠</div>
				<div class="copun-rt">选择产品</div>
				<div class="clear"></div>
			</div>
		<?php endif;?>
	<?php endif;?>
	<!-- 完善资料才能使用代金券  -->
	<?php if($user['mobile_num']&&$user['user_birthday']):?>
		<div class="order-copun arrowright cupon <?php if(!$isCupon) echo 'disabled';?>">
			<div class="copun-lt">代金券</div>
			<div class="copun-rt"><?php if($isCupon){echo count($cupons).'张可用';}else{echo '暂无可用';}?></div>
			<div class="clear"></div>
		</div>
	<?php else:?>
		<div class="order-copun arrowright disabled">
			<div class="copun-lt">代金券</div>
			<div class="copun-rt"><a href="<?php echo $this->createUrl('/user/setUserInfo',array('companyId'=>$this->companyId,'type'=>$this->type,'back'=>1));?>">去完善资料</a></div>
			<div class="clear"></div>
		</div>
	<?php endif;?>
</div>

<div class="totalinfo"><span class="font_l" style="margin-right:20px;<?php if(!$memdisprice) echo 'dispaly:none';?>">优惠￥<span class="cart-discount"><?php echo number_format($memdisprice,2);?></span></span><span>实付￥<span class="cart-price"><?php echo $price;?></span></span></div>


<div class="order-remark">
	<textarea name="remark" placeholder="请输入备注内容"></textarea>
</div>
<div class="order-paytype">
	<div class="select-type">选择支付方式</div>
	<!-- 余额 -->
	<div class="chooselist points" style="padding:15px;">
		<div class="left"><img src="<?php echo $baseUrl;?>/img/wechat_img/icon-wdcz.png"/> 储值支付 <span class="small font_org">剩余￥<span id="yue" yue="<?php echo $remainMoney;?>"><?php echo $remainMoney;?></span> 可使用￥<span class="use-yue"><?php echo $remainMoney > $price?$price:$remainMoney;?></span></span></div>
		<div class="right">
		<?php if($remainMoney > 0):?>
		<label><input type="checkbox" name="yue" checked="checked" class="ios-switch green  bigswitch" value="1" /><div><div></div></div></label>
		<?php else:?>
		<label><input type="checkbox" name="yue" class="ios-switch green  bigswitch" value="1" /><div><div></div></div></label>
		<?php endif;?>
		</div>
	</div>
	<!-- 余额 -->
	<div class="paytype">
		<div class="item wx on" paytype="2" style="border:none;"><img src="<?php echo $baseUrl;?>/img/mall/wxpay.png"/> 微信支付</div>
		<!-- 
		<div class="item zfb" paytype="1" style="border:none;"><img src="<?php echo $baseUrl;?>/img/mall/zfbpay.png"/> 支付宝支付</div>
		-->
		<input type="hidden" name="paytype" value="2" />
	</div>
</div>
<div class="bottom"></div>

<footer>
    <div class="ft-lt">
        <p>实付￥<span id="total" class="total" total="<?php echo $price;?>"><?php echo $price;?></span></p>
    </div>
    <div class="ft-rt">
        <p><a id="payorder" href="javascript:;">去支付</a></p>
    </div>
    <div class="clear"></div>
</footer>

<div class="user-cupon" id="cuponList">
	<div class="cupon-container">
	<?php if($isCupon):?>
	<?php foreach($cupons as $coupon):?>
		<div class="item useCupon" user-cupon-id="<?php echo $coupon['lid'].'-'.$coupon['dpid'];?>" min-money="<?php echo $coupon['min_consumer'];?>" cupon-money="<?php echo $coupon['cupon_money'];?>">
			<div class="item-top">
				<div class="item-top-left"><?php echo $coupon['cupon_title'];?></div>
				<div class="item-top-right">￥<?php echo (int)$coupon['cupon_money'];?></div>
				<div class="clear"></div>
			</div>
			<div class="item-bottom">
				<div class="item-bottom-left">有效期至<?php echo date('Y.m.d',strtotime($coupon['close_day']));?></div>
				<div class="item-bottom-right">满<?php echo (int)$coupon['min_consumer'];?>元可用</div>
				<div class="clear"></div>
			</div>
		</div>
	<?php endforeach;?>
		<div class="item noCupon" cupon-num="<?php echo count($cupons);?>" user-cupon-id="0" min-money="0" cupon-money="0">不使用代金券</div>
	<?php endif;?>
	</div>
</div>
<div class="user-cupon" id="sentList">
	<div class="cupon-container">
		<?php if(!empty($fullsent)&&$fullsent['full_type']==0):?>
		<?php 
			foreach ($fullsent['sent_product'] as $sent):
			$sentProPrice = 0;
			if($sent['is_discount']){
				$sentProPrice = number_format($sent['original_price']*$sent['promotion_discount'],2);
			}else{
				$sentProPrice = number_format($sent['original_price'] - $sent['promotion_money'],2);
			}
		?>
		<div class="item useSent" sent-type="0" sent-id="<?php echo $fullsent['lid'];?>" sent-product-id="<?php echo $sent['lid'];?>" sent-price="<?php echo $sentProPrice;?>">
			<div class="item-top">
				<div class="item-top-left"><?php echo $sent['product_name'];?></div>
				<div class="item-top-right"><span style="font-size:15px;color:gray;"><strike>￥<?php echo $sent['original_price'];?></strike></span> <span>￥<?php echo $sentProPrice;?></span></div>
				<div class="clear"></div>
			</div>
		</div>
		<?php endforeach;?>
		<div class="item noSent" sent-type="0" sent-id="0" sent-product-id="0" sent-price="0.00" >不选择赠送产品</div>
		<?php endif;?>
	</div>
</div>
	<input type="hidden" name="fullsent" value="<?php echo $fval;?>" />
	<input type="hidden" name="cupon" value="0" />
</form>

<div id="dialogs">
	<!--BEGIN dialog1-->
	<div class="js_dialog" id="dialog1" style="display: none;">
	    <div class="weui-mask"></div>
	    <div class="weui-dialog">
	         <div class="weui-dialog__hd"><strong class="weui-dialog__title">储值支付提示</strong></div>
	         <div class="weui-dialog__bd">确定使用储值支付?</div>
	         <div class="weui-dialog__ft">
	         	<a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_default">取消</a>
	         	<a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_primary">确定</a>
	         </div>
	     </div>
	</div>
	<!--END dialog1-->
	<!--BEGIN dialog2-->
	<div class="js_dialog" id="dialog2" style="display: none;">
	    <div class="weui-mask"></div>
	    <div class="weui-dialog">
	         <div class="weui-dialog__hd"><strong class="weui-dialog__title">储值支付提示</strong></div>
	         <div class="weui-dialog__bd">储值余额不足,请去充值后再下单</div>
	         <div class="weui-dialog__ft">
	         	<a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_default">取消</a>
	         	<a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_primary">去充值</a>
	         </div>
	     </div>
	</div>      
	<!--END dialog2-->
</div>
<script>
function reset_total(price){
	var setTotal = $('#total').attr('total');
	var total = $('#total').html();
	var totalFee = parseFloat(total) + parseFloat(price);
	$('#total').attr('total',parseFloat(setTotal) + parseFloat(price));
	
	if(totalFee > 0){
		totalFee =  totalFee.toFixed(2);
	}else{
		totalFee = '0.00';
	}
	
	$('#total').html(totalFee);
}
$(document).ready(function(){
	var cupon_layer = 0;
	var sent_layer = 0;
	var isMustYue = false;
	
	$('.paytype .item').click(function(){
		var paytype = $(this).attr('paytype');
		$('.paytype .item').removeClass('on');
		
		$('input[name="paytype"]').val(paytype);
		$(this).addClass('on');
	});
	// 点击选择代金券
	$('.cupon').click(function(){
	    if($(this).hasClass('disabled')){
	      layer.msg('无可用代金券');
	      return;
	    }
	    cupon_layer = layer.open({
	      				    type: 1,
	      				    title: false,
	      				    shadeClose: true,
	      				    closeBtn: 0,
	      				    area: ['100%','100%'],
	      				    content:$('#cuponList'),
	      				});
	});
	 // 选择代金券
	$('#cuponList .item.useCupon').click(function(){
		var userCuponId = $(this).attr('user-cupon-id');
		var cuponMoney = $(this).attr('cupon-money');
		var noCuponMoney = $('.noCupon').attr('cupon-money');
		var minMoney = $(this).attr('min-money');
		var cartDiscount = $('.cart-discount').html();
		var total = $('#total').html();
		var yue = parseFloat($('#yue').attr('yue'));
		var money = 0;
		
		$('#cuponList .item').removeClass('on');
		$(this).addClass('on');
		$('input[name="cupon"]').val(userCuponId);
		$('.noCupon').attr('min-money',minMoney);
		$('.noCupon').attr('cupon-money',cuponMoney);
		
		money = parseFloat(total) + parseFloat(noCuponMoney) - parseFloat(cuponMoney);
		if(money > 0){
			money = money;
		}else{
			money = 0;
			$('.noCupon').attr('cupon-money',total);
		}
		if(yue > 0){
			if(yue > money){
				$('.use-yue').html(money.toFixed(2));
			}else{
				$('.use-yue').html(yue.toFixed(2));
			}
		}
		money = money.toFixed(2);
		cartDiscount = parseFloat(cartDiscount)-parseFloat(noCuponMoney)+parseFloat(cuponMoney);
		cartDiscount = cartDiscount.toFixed(2);
		$('.cart-discount').html(cartDiscount);
		$('.cart-price').html(money);
		$('#total').html(money);
		$('#total').attr('total',money);
		$('.cupon').find('.copun-rt').html('-￥'+cuponMoney);
		layer.close(cupon_layer);
	});
	// 选择不使用代金券
	$('#cuponList .item.noCupon').click(function(){
		var cuponNum = $(this).attr('cupon-num');
		var userCuponId = $(this).attr('user-cupon-id');
		var cuponMoney = $(this).attr('cupon-money');
		var minMoney = $(this).attr('min-money');
		var cartDiscount = $('.cart-discount').html();
		var total = $('#total').html();
		var yue = parseFloat($('#yue').attr('yue'));
		var money = 0;
		
		$('#cuponList .item').removeClass('on');
		$('input[name="cupon"]').val(userCuponId);
		
		$(this).attr('min-money',0);
		$(this).attr('cupon-money',0);
		
		money = parseFloat(total) + parseFloat(cuponMoney);
		if(money > 0){
			money = money;
		}else{
			money = 0;
		}
		if(yue > 0){
			if(yue > money){
				$('.use-yue').html(money.toFixed(2));
			}else{
				$('.use-yue').html(yue.toFixed(2));
			}
		}
		money = money.toFixed(2);
		cartDiscount = parseFloat(cartDiscount)-parseFloat(cuponMoney);
		cartDiscount = cartDiscount.toFixed(2);
		$('.cart-discount').html(cartDiscount);
		$('.cart-price').html(money);
		$('#total').html(money);
		$('#total').attr('total',money);
		$('.cupon').find('.copun-rt').html(cuponNum+'张可用');
		layer.close(cupon_layer);
	});
	// 点击选择赠送产品
	$('.fullsent').click(function(){
		sent_layer = layer.open({
		    type: 1,
		    title: false,
		    shadeClose: true,
		    closeBtn: 0,
		    area: ['100%','100%'],
		    content:$('#sentList'),
		});
	});
	$('#sentList .item.useSent').click(function(){
		var sentType = $(this).attr('sent-type');
		var sentId = $(this).attr('sent-id');
		var sentProductId = $(this).attr('sent-product-id');
		var sentPrice = $(this).attr('sent-price');
		var noSentMoney = $('.noSent').attr('sent-price');
		var sval = sentType+'-'+sentId+'-'+sentProductId;
		var pName = $(this).find('.item-top-left').html();
		var yue = parseFloat($('#yue').attr('yue'));
		var total = $('#total').html();
		var money = 0;
		$('#sentList .item').removeClass('on');
		$(this).addClass('on');
		
		$('.noSent').attr('sent-price',sentPrice);
		$('input[name="fullsent"]').val(sval);
		$('.fullsent').find('.copun-rt').html(pName+'￥'+sentPrice);

		money = parseFloat(total) - parseFloat(noSentMoney) + parseFloat(sentPrice);

		if(yue > 0){
			if(yue > money){
				$('.use-yue').html(money.toFixed(2));
			}else{
				$('.use-yue').html(yue.toFixed(2));
			}
		}
		money = money.toFixed(2);
		$('#total').html(money);
		$('#total').attr('total',money);
		
		layer.close(sent_layer);
	});
	$('#sentList .item.noSent').click(function(){
		var sval = '0-0-0';
		var sentPrice = $(this).attr('sent-price');
		var yue = parseFloat($('#yue').attr('yue'));
		var total = $('#total').html();
		var money = 0;
		$('#sentList .item').removeClass('on');
		$('input[name="fullsent"]').val(sval);
		$(this).attr('sent-price','0.00');
		money = parseFloat(total) - parseFloat(sentPrice);
		if(yue > 0){
			if(yue > money){
				$('.use-yue').html(money.toFixed(2));
			}else{
				$('.use-yue').html(yue.toFixed(2));
			}
		}
		money = money.toFixed(2);
		$('#total').html(money);
		$('#total').attr('total',money);
		$('.fullsent').find('.copun-rt').html('选择产品');
		
		layer.close(sent_layer);
	});	
	
	$('input[name="yue"]').change(function(){
		if(isMustYue){
			layer.msg('有储值支付活动产品<br>需使用储值支付');
			$(this).prop('checked',true);
			return;
		}
		var yue = $('#yue').attr('yue');
		if(parseFloat(yue) == 0){
			layer.msg('储值不足!');
			$(this).prop('checked',false);
		}
	});
	$('#payorder').click(function(){
		if($('input[name="yue"]').is(':checked')){
			$('#dialog1').show();
			return;
		}
		layer.load(2);
		$('form').submit();
	});
	$('#dialog .weui-dialog__btn_primary').click(function(){
		$('#dialog').hide();
		layer.load(2);
		$('form').submit();
	});
	$('#dialog .weui-dialog__btn_default').click(function(){
		$('#dialog').hide();
	});
	$('#dialog1 .weui-dialog__btn_primary').click(function(){
		$('#dialog1').hide();
		layer.load(2);
		$('form').submit();
	});
	$('#dialog1 .weui-dialog__btn_default').click(function(){
		if(isMustYue){
			layer.msg('有储值支付活动产品<br>需使用储值支付');
			location.href = "<?php echo $this->createUrl('/mall/index',array('companyId'=>$this->companyId,'type'=>$this->type));?>";
		}else{
			$('input[name="yue"]').removeAttr('checked');
			$('#dialog1').hide();
		}
	});
	$('#dialog2 .weui-dialog__btn_primary').click(function(){
		location.href = "<?php echo $this->createUrl('/mall/reCharge',array('companyId'=>$user['dpid'],'url'=>urlencode($this->createUrl('/mall/checkOrder',array('companyId'=>$this->companyId,'type'=>$this->type)))));?>";
	});
	$('#dialog2 .weui-dialog__btn_default').click(function(){
		location.href = "<?php echo $this->createUrl('/mall/index',array('companyId'=>$this->companyId,'type'=>$this->type));?>";
	});
});
</script>
