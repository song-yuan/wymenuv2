<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('确认订单');
	$fval = '0-0-0';// 满类型-满lid-送lid
	$isCupon = false;
	if(!empty($cupons)){
		$isCupon = true;
	}
	
?>
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/cart.css?_t=2018121001">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/order.css">

<style>
.weui-dialog__btn_primary {
    color: #0bb20c !important;
}
.weui-badge{
	margin-left:5px;
	display:inline-block !important;
	color:#fff !important;
	font-size:12px !important;
}
</style>
<form action="<?php echo $this->createUrl('/mall/generalOrder',array('companyId'=>$this->companyId,'type'=>$this->type));?>" method="post">
<div class="order-time" style="margin:10px 0;font-size: 14px;">
	<p>餐厅名称:<?php echo $this->company['company_name'];?></p>
	<p>餐厅地址:<?php echo $this->company['address'];?></p>
</div>
<!-- 购物车商品 -->
<div class="cart-info">
	<!--  购物车中可下单产品 -->
	<?php foreach($models as $model):?>
	<div class="section cartProduct">
		<!--
	    <div class="prt-cat">/div>
	    -->
	    <?php 
	    	$isSent = false;
	    	$isPromotion = false;// 是否普通优惠活动
	    	$prodiscount = 1; //活动折扣
	    	$pprice = $model['price'];
	    	$pptype = $model['promotion_type'];
	    	if($pptype=='sent'){
	    		$isSent = true;
	    	}
	    	if($model['promotion_id'] > 0){
	    		$isPromotion = true;
	    		$proinfo = $model['promotion']['promotion_info'];
	    		if(!empty($proinfo)){
	    			$protype = $proinfo['is_discount'];
	    			if($protype > 0){
	    				$prodiscount = $proinfo['promotion_discount'];
	    			}
	    		}
	    	}
	    	$tasteHtml = '';// 已选口味html
	    	$prosetHtml = '';// 已选套餐的html
	    	$detailArr = explode(',', $model['detail_id']);
	    	// 单品口味详情
	    	if(isset($model['taste_groups'])&&!empty($model['taste_groups'])){
	    		$tdesc = '';
	    		foreach($model['taste_groups'] as $k=>$groups){
	    			$tvalue = 0;
	    			foreach($groups['tastes'] as $tk=>$taste){
	    				$active = '';
	    				if(in_array($taste['lid'], $detailArr)){
	    					if($taste["price"]>0){
	    						$mtasteprice = $taste['price']*$prodiscount;
		    					if(!$isPromotion&&$model['is_member_discount']){
		    						$memdisprice += number_format($mtasteprice*(1-$levelDiscount),2);
		    						$mtasteprice = number_format($mtasteprice*$levelDiscount,2);
		    					}
		    					if(!$isSent){
		    						$pprice += $mtasteprice;
		    						$price += $mtasteprice*$model['num'];
		    					}
	    					}
	    					$tvalue = $model['lid'].'-'.$groups['product_id'].'-'.$taste["lid"].'-'.$taste["price"].'-'.$taste['name'];
	    					$tdesc.='<span>'.$taste['name'].'</span>';
	    				}
	    			}
	    			$tasteHtml .= '<input type="hidden" name="taste[]" value="'.$tvalue.'" />';
	    		}
	    		$tasteHtml .= '<div class="taste-desc">'.$tdesc.'</div>';
	    	}
	    	
	    	// 套餐详情
	    	if(isset($model['detail'])&&!empty($model['detail'])){
	    		$detailDesc = '';
	    		foreach ($model['detail'] as $k=>$detail){
	    			$selectItem = '';
	    			foreach($detail as $item){
	    				if(in_array($item['product_id'].'-'.$item['group_no'], $detailArr)){
	    					$selectItem = $model['lid'].'-'.$model['product_id'].'-'.$item['product_id'].'-'.$item['number'].'-'.$item['price'];
	    					$detailDesc .='<span>'.$item['product_name'].'x'.$item['number'].'</span>';
	    					if($item['price'] > 0){
	    						$mdprice = $item['price']*$prodiscount;
	    						if(!$isPromotion&&$model['is_member_discount']){
	    							$memdisprice += number_format($mdprice*(1-$levelDiscount),2);
	    							$mdprice = number_format($mdprice*$levelDiscount,2);
	    						}
	    						if(!$isSent){
	    							$pprice += $mdprice;
	    							$price += $mdprice*$model['num'];
	    						}
	    					}
	    				}
	    			}
	    			$prosetHtml .= '<input type="hidden" name="set-detail[]" value="'. $selectItem.'"/>';
	    		}
	    		$prosetHtml .= '<div class="detail-desc">'.$detailDesc.'</div>';
	    	}
	    	$pprice = number_format($pprice,2);
	    ?>
	    
	    <div class="prt">
	        <div class="prt-lt"><?php if($isSent): ?><span class="bttn_orange">赠</span><?php endif;?><?php echo $model['product_name'];?></div>
	        <div class="prt-mt">x<span class="num"><?php echo $model['num'];?></span></div>
	        <div class="prt-rt">￥<span class="price"><?php echo $pprice;?></span></div>
	        <div class="clear"></div>
	    </div>
	    <!-- b已选择口味 -->
	    <?php echo $tasteHtml;?>
	    <!-- e已选择口味 -->
	    <!-- b可选择套餐 -->
	    <?php echo $prosetHtml;?>
	    <!-- e可选择套餐 -->
	</div>
	<?php endforeach;?>
</div>
<!-- 如果是餐座 则显示下单 不需要支付  -->
<?php if($this->type!=1):?>
<div class="activity-info">
	<?php if($memdisprice):?>
	<div class="order-copun userdiscount disabled">
		<div class="copun-lt">会员折扣</div>
		<div class="copun-rt"><?php echo '-￥'.number_format($memdisprice,2);?></div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
	<?php if(!empty($fullsent)):?>
		<?php if($fullsent['full_type']):
				$fminusprice = $fullsent['extra_cost'];
				$fval = '1-'.$fullsent['lid'].'-0';
		?>
		<div class="order-copun arrowright fullsent">
			<div class="copun-lt">满减优惠</div>
			<div class="copun-rt">可选&nbsp;</div>
			<div class="clear"></div>
		</div>
		<?php else:?>
			<div class="order-copun arrowright fullsent">
				<div class="copun-lt">满送优惠</div>
				<div class="copun-rt">选择产品&nbsp;</div>
				<div class="clear"></div>
			</div>
		<?php endif;?>
	<?php endif;?>
	<!-- 完善资料才能使用代金券  -->
	<?php if($user['mobile_num']&&$user['user_birthday']):?>
		<div class="order-copun arrowright cupon <?php if(!$isCupon) echo 'disabled';?>">
			<div class="copun-lt">代金券</div>
			<div class="copun-rt"><?php if($isCupon){echo count($cupons).'张可用';}else{echo '暂无可用';}?>&nbsp;</div>
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
<?php endif;?>
<div class="order-remark">
	<textarea name="taste_memo" placeholder="请输入备注内容(可不填)"></textarea>
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
        <p style="margin-left:10px;">付款 ￥<span id="total" class="total" original="<?php echo $original;?>" memdiscount="<?php echo $memdisprice;?>" total="<?php echo $price;?>"><?php echo $price;?></span></p>
    </div>
    <div class="ft-rt" id="payorder">
    	<a href="javascript:;">
        <p>提交订单</p>
        </a>
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
		<?php if(!empty($fullsent)&&$fullsent['full_type']==1):?>
		<div class="item useSent" sent-type="1" sent-id="<?php echo $fullsent['lid'];?>" full-price="<?php echo $fullsent['full_cost'];?>" minus-price="<?php echo $fullsent['extra_cost'];?>">
			<div class="item-top">
				<div class="item-top-left"><?php echo $fullsent['title'];?></div>
				<div class="item-top-right"><span style="font-size:15px;color:gray;"><?php echo '满'.$fullsent['full_cost'].'减'.$fullsent['extra_cost'];?></span></div>
				<div class="clear"></div>
			</div>
		</div>
		<div class="item noSent" sent-type="1" sent-id="0" sent-product-id="0" sent-price="0.00">不选择满减活动</div>
		<?php endif;?>
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
		<div class="item useSent" sent-type="0" sent-id="<?php echo $fullsent['lid'];?>" sent-product-id="<?php echo $sent['lid'];?>" dis-price="<?php echo number_format($sent['original_price'] - $sentProPrice,2);?>" sent-price="<?php echo $sentProPrice;?>">
			<div class="item-top">
				<div class="item-top-left"><?php echo $sent['product_name'];?></div>
				<div class="item-top-right"><span style="font-size:15px;color:gray;"><strike>￥<?php echo $sent['original_price'];?></strike></span> <span>￥<?php echo $sentProPrice;?></span></div>
				<div class="clear"></div>
			</div>
		</div>
		<?php endforeach;?>
		<div class="item noSent" sent-type="0" sent-id="0" sent-product-id="0" sent-price="0.00">不选择赠送产品</div>
		<?php endif;?>
	</div>
</div>
	<input type="hidden" name="fullsent" value="<?php echo $fval;?>" />
	<input type="hidden" name="cupon" value="0" />
	<input type="hidden" name="takeout_typeid" value="0" />
</form>

<div id="dialogs" style="font-size:22px;">
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
	<!--BEGIN dialog-->
	<div class="js_dialog" id="dialog" style="display: none;">
	    <div class="weui-mask"></div>
	    <div class="weui-dialog">
	         <div class="weui-dialog__hd"><strong class="weui-dialog__title">餐位数提示</strong></div>
	         <div class="weui-dialog__bd"></div>
	         <div class="weui-dialog__ft">
	         	<a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_default">取消</a>
	         	<a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_primary">确定</a>
	         </div>
	     </div>
	</div>
	<!--END dialog-->
</div>
<script>
function reset_cupon(){
	var obj = $('#cuponList .item.noCupon');
	if(obj.length > 0){
		var cuponNum = obj.attr('cupon-num');
		var userCuponId = obj.attr('user-cupon-id');
		var total = $('#total').attr('original');
		var memdis = $('#total').attr('memdiscount');
		var yue = parseFloat($('#yue').attr('yue'));
		var money = 0;

		if($('.userdiscount').length > 0){
			$('.userdiscount').show();
		}
		$('#cuponList .item').removeClass('on');
		$('input[name="cupon"]').val(userCuponId);
		
		$(this).attr('min-money',0);
		$(this).attr('cupon-money',0);
		
		money = parseFloat(total) - parseFloat(memdis);
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
		$('.cart-price').html(money);
		$('#total').html(money);
		$('#total').attr('total',money);
		$('.cart-discount').html(memdis);
		$('.cupon').find('.copun-rt').html(cuponNum+'张可用&nbsp;');
	}else{
		$('.cupon').find('.copun-rt').html('暂无可用&nbsp;');
	}
}

$(document).ready(function(){
	var cupon_layer = 0;
	var sent_layer = 0;
	var msg = "<?php echo $msg;?>";
	if(msg){
		layer.msg(msg);
	}
	var isMustYue = false;
	<?php if($isMustYue):?>;
	isMustYue = true;
	var totalPrice = $('#total').html();
	var yue = $('#yue').attr('yue');
	if(parseFloat(yue) < parseFloat(totalPrice)){
		$('#dialog2').show();
	}
	<?php endif;?>
	
	function hideActionSheet(weuiActionsheet, mask) {
        weuiActionsheet.removeClass('weui_actionsheet_toggle');
        mask.removeClass('weui_fade_toggle');
        weuiActionsheet.on('transitionend', function () {
            mask.hide();
        }).on('webkitTransitionEnd', function () {
            mask.hide();
        });
    }
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
    // 选择代金券 会员打折取消
	$('#cuponList .item.useCupon').click(function(){
		reset_fullsent();
		var userCuponId = $(this).attr('user-cupon-id');
		var cuponMoney = $(this).attr('cupon-money');
		var minMoney = $(this).attr('min-money');
		var total = $('#total').attr('original');
		var yue = parseFloat($('#yue').attr('yue'));
		var money = 0;

		if($('.userdiscount').length > 0){
			$('.userdiscount').hide();
		}
		$('#cuponList .item').removeClass('on');
		$(this).addClass('on');
		$('input[name="cupon"]').val(userCuponId);
		
		money = parseFloat(total) - parseFloat(cuponMoney);
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
		$('.cart-price').html(money);
		$('#total').html(money);
		$('#total').attr('total',money);
		$('.cart-discount').html(cuponMoney);
		$('.cupon').find('.copun-rt').html('-￥'+cuponMoney);
		layer.close(cupon_layer);
	});
	// 选择不使用代金券 默认会员打折
	$('#cuponList .item.noCupon').click(function(){
		reset_cupon();
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
		reset_cupon();
		var sentType = $(this).attr('sent-type');
		var sentId = $(this).attr('sent-id');
		var sentProductId = $(this).attr('sent-product-id');
		var sval = sentType+'-'+sentId+'-'+sentProductId;
		var pName = $(this).find('.item-top-left').html();
		var yue = parseFloat($('#yue').attr('yue'));
		var total = $('#total').attr('original');
		var money = 0;
		$('#sentList .item').removeClass('on');
		$(this).addClass('on');

		if($('.userdiscount').length > 0){
			$('.userdiscount').hide();
		}
		$('input[name="fullsent"]').val(sval);
		if(sentType=='0'){
			var sentPrice = $(this).attr('sent-price');
			var disPrice = $(this).attr('dis-price');
			$('.noSent').attr('sent-price',sentPrice);
			$('.fullsent').find('.copun-rt').html(pName+'￥'+sentPrice);
			money = parseFloat(total) + parseFloat(sentPrice);
		}else{
			var disPrice = $(this).attr('minus-price');
			$('.noSent').attr('sent-price',sentPrice);
			$('.fullsent').find('.copun-rt').html('-￥'+disPrice);
			money = parseFloat(total) - parseFloat(disPrice);
		}

		if(yue > 0){
			if(yue > money){
				$('.use-yue').html(money.toFixed(2));
			}else{
				$('.use-yue').html(yue.toFixed(2));
			}
		}
		money = money.toFixed(2);
		$('.cart-price').html(money);
		$('#total').html(money);
		$('#total').attr('total',money);
		$('.cart-discount').html(disPrice);
		
		layer.close(sent_layer);
	});
	$('#sentList .item.noSent').click(function(){
		reset_fullsent();
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
});
</script>
