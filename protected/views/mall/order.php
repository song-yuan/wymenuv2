<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('支付订单');
	$orderTatsePrice = 0.00;
	$fval = '0-0-0';// 满类型-满lid-送lid
	$isCupon = false;
	if(!empty($cupons)){
		$isCupon = true;
	}
?>
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/weui.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/order.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/cart.css">
<script src="<?php echo $baseUrl;?>/js/mall/date/mobiscroll_002.js" type="text/javascript"></script>
<script src="<?php echo $baseUrl;?>/js/mall/date/mobiscroll_004.js" type="text/javascript"></script>
<link href="<?php echo $baseUrl;?>/css/mall/date/mobiscroll_002.css" rel="stylesheet" type="text/css">
<link href="<?php echo $baseUrl;?>/css/mall/date/mobiscroll.css" rel="stylesheet" type="text/css">
<script src="<?php echo $baseUrl;?>/js/mall/date/mobiscroll.js" type="text/javascript"></script>
<script src="<?php echo $baseUrl;?>/js/mall/date/mobiscroll_003.js" type="text/javascript"></script>
<script src="<?php echo $baseUrl;?>/js/mall/date/mobiscroll_005.js" type="text/javascript"></script>
<link href="<?php echo $baseUrl;?>/css/mall/date/mobiscroll_003.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo $baseUrl.'/js/layer/layer.js';?>"></script>
<style>
.layui-layer-btn{height:42px;}
.weui_dialog_confirm .weui_dialog .weui_dialog_hd{margin:0;padding:0;font-size:50%;}
.weui_mask{z-index:9002;}
.weui_dialog{z-index:9003;}
</style>

<form action="<?php echo $this->createUrl('/mall/orderCupon',array('companyId'=>$this->companyId,'orderId'=>$order['lid'],'type'=>$this->type));?>" method="post">
<div class="order-title">我的订单</div>
<?php if($order['order_type']==1):?>
<div class="order-site">桌号:<?php if($siteType){echo $siteType['name'];}?><?php echo $site['serial'];?></div>
<?php elseif($order['order_type']==2):?>
<!-- 地址 -->
<div class="address arrowright">
	<?php if($address):?>
	<div class="location">
		<span>收货人：<?php echo $address['name'];?>   <?php echo $address['mobile'];?></span><br>
		<span class="add">收货地址：<?php echo $address['province'].$address['city'].$address['area'].$address['street'];?></span>
		<input type="hidden" name="address" value="<?php echo $address['lid'];?>"/>
	</div>
	<?php else:?>
	<div class="location" style="line-height: 50px;">
		<span class="add">添加收货地址</span>
		<input type="hidden" name="address" value="-1"/>
	</div>
	<?php endif;?>
</div>
<?php elseif($order['order_type']==3):?>
<div class="address arrowright">
	<?php if($address):?>
	<div class="location" style="line-height: 50px;">
		<span>预约人：<?php echo $address['name'];?>   <?php echo $address['mobile'];?></span><br>
		<input type="hidden" name="address" value="<?php echo $address['lid'];?>"/>
	</div>
	<?php else:?>
	<div class="location" style="line-height: 50px;">
		<span class="add">添加预约人信息</span>
		<input type="hidden" name="address" value="-1"/>
	</div>
	<?php endif;?>
</div>
<!-- 地址 -->
<div class="order-time arrowright">
	<div class="time-lt">预约时间</div>
	<div class="time-rt"><input  type="text" class="" name="order_time" id="appDateTime" value="<?php if($order['appointment_time'] > "0000-00-00 00:00:00") echo $order['appointment_time'];?>" placeholder="选择预约时间" readonly="readonly" ></div>
	<div class="clear"></div>
</div>
<?php endif;?>
<div class="ht1"></div>
<?php if(!empty($order['taste'])):?>
	<div class="taste">整单口味:
	<?php foreach ($order['taste'] as $otaste):?>
	<span> <?php echo $otaste['name'].'('.$otaste['price'].')';?> </span>
	<?php endforeach;?>
	</div>
<?php endif;?>
<div class="order-info">
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
	<!-- 其他费用 -->
	<?php if($order['order_type']==1||$order['order_type']==3):?>
	<div class="item">
		<div class="lt">餐位费:</div>
		<div class="rt">x1 ￥<?php echo $seatingFee?number_format($seatingFee,2):'0.00';?></div>
		<div class="clear"></div>
	</div>
	<?php elseif($order['order_type']==2):?>
	<div class="item">
		<div class="lt">包装费:</div>
		<div class="rt">x1 ￥<?php echo $packingFee?number_format($packingFee,2):'0.00';?></div>
		<div class="clear"></div>
	</div>
	<div class="item">
		<div class="lt">配送费:</div>
		<div class="rt">x1 ￥<?php echo $freightFee?number_format($freightFee,2):'0.00';?></div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
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
	<textarea name="remark" placeholder="请输入备注内容(可不填)"></textarea>
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
        <p>￥<span id="total" class="total" total="<?php echo $price;?>"><?php echo $price;?></span></p>
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

<!--BEGIN dialog1-->
<div class="weui_dialog_confirm" id="dialog1" style="display: none;">
    <div class="weui_mask"></div>
    <div class="weui_dialog">
        <div class="weui_dialog_hd"><strong class="weui_dialog_title">储值支付提示</strong></div>
        <div class="weui_dialog_bd content" style="text-align:center;">确定使用储值支付?</div>
        <div class="weui_dialog_ft">
            <a href="javascript:;" class="weui_btn_dialog default">取消</a>
            <a href="javascript:;" class="weui_btn_dialog primary">确定</a>
        </div>
    </div>
</div>
<!--END dialog1-->
<!--BEGIN dialog2-->
<div class="weui_dialog_confirm" id="dialog2" style="display: none;">
    <div class="weui_mask"></div>
    <div class="weui_dialog">
        <div class="weui_dialog_hd"><strong class="weui_dialog_title">储值支付提示</strong></div>
        <div class="weui_dialog_bd content" style="text-align:center;">储值余额不足,请去充值后再下单</div>
        <div class="weui_dialog_ft">
            <a href="javascript:;" class="weui_btn_dialog default">取消</a>
            <a href="javascript:;" class="weui_btn_dialog primary">去充值</a>
        </div>
    </div>
</div>
<!--END dialog2-->
<!--BEGIN actionSheet-->
<div id="actionSheet_wrap">
   <div class="weui_mask_transition" id="mask"></div>
   <div class="weui_actionsheet" id="weui_actionsheet" style="z-index:9002;">
         <div class="weui_actionsheet_menu" style="height:3em;overflow-y:auto;">
         </div>
         <div class="weui_actionsheet_action">
         	<div class="weui_actionsheet_cell" id="actionsheet_cancel">确定</div>
         </div>
    </div>
</div>
<!--END actionSheet-->  
<script>
function getOrderStatus(){
	var timestamp=new Date().getTime()
    var random = ''+timestamp + parseInt(Math.random()*899+100)+'';
	
	$.get('<?php echo $this->createUrl('/mall/getOrderStatus',array('companyId'=>$this->companyId,'orderId'=>$order['lid']))?>',{random:random},function(msg){
		if(parseInt(msg) > 1){
			layer.alert('服务员已经确认,请点击待付!');
		}else{
			getOrderStatus();
		}
	});
}
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
	<?php if($order['order_type']!=1):?>
	var currYear = (new Date()).getFullYear();	
	var opt={};
	opt.date = {preset : 'date'};
	opt.datetime = {preset : 'datetime'};
	opt.time = {preset : 'time'};
	opt.default = {
		theme: 'android-ics light', //皮肤样式
        display: 'modal', //显示方式 
        mode: 'scroller', //日期选择模式
		dateFormat: 'yyyy-mm-dd',
		lang: 'zh',
		showNow: true,
		nowText: "今天",
        startYear: currYear, //开始年份
        endYear: currYear + 1 //结束年份
	};

  	var optDateTime = $.extend(opt['datetime'], opt['default']);
  	var optTime = $.extend(opt['time'], opt['default']);
    $("#appDateTime").mobiscroll(optDateTime).datetime(optDateTime);
    
	$('.location').click(function(){
		location.href = '<?php echo $this->createUrl('/user/setAddress',array('companyId'=>$this->companyId,'url'=>urlencode($this->createUrl('/mall/order',array('companyId'=>$this->companyId,'type'=>$this->type,'orderId'=>$order['lid'])))));?>';
	});
	<?php endif;?>
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
		<?php if($order['order_type']==1):?>
		if($('input[name="yue"]').is(':checked')){
			$('#dialog1').show();
			return;
		}
		layer.load(2);
		$('form').submit();
		<?php elseif($order['order_type']==2):?>
		var address = $('input[name="address"]').val();
		if(parseInt(address) < 0){
			layer.msg('请添加收货地址!');
			return;
		}
		if($('input[name="yue"]').is(':checked')){
			$('#dialog1').show();
			return;
		}
		layer.load(2);
		$('form').submit();
		<?php elseif($order['order_type']==3):?>
		var address = $('input[name="address"]').val();
		if(parseInt(address) < 0){
			layer.msg('请添加预约人信息!');
			return;
		}
		var orderTime = $('input[name="order_time"]').val();
		if(!orderTime){
			layer.msg('请选择预约时间!');
			return;
		}
		if($('input[name="yue"]').is(':checked')){
			$('#dialog1').show();
			return;
		}
		layer.load(2);
		$('form').submit();
		<?php endif;?>
	});
	$('#dialog .primary').click(function(){
		$('#dialog').hide();
		layer.load(2);
		$('form').submit();
	});
	$('#dialog .default').click(function(){
		$('#dialog').hide();
	});
	$('#dialog1 .primary').click(function(){
		$('#dialog1').hide();
		layer.load(2);
		$('form').submit();
	});
	$('#dialog1 .default').click(function(){
		if(isMustYue){
			layer.msg('有储值支付活动产品<br>需使用储值支付');
			location.href = "<?php echo $this->createUrl('/mall/index',array('companyId'=>$this->companyId,'type'=>$this->type));?>";
		}else{
			$('input[name="yue"]').removeAttr('checked');
			$('#dialog1').hide();
		}
	});
	$('#dialog2 .primary').click(function(){
		location.href = "<?php echo $this->createUrl('/mall/reCharge',array('companyId'=>$user['dpid'],'url'=>urlencode($this->createUrl('/mall/checkOrder',array('companyId'=>$this->companyId,'type'=>$this->type)))));?>";
	});
	$('#dialog2 .default').click(function(){
		location.href = "<?php echo $this->createUrl('/mall/index',array('companyId'=>$this->companyId,'type'=>$this->type));?>";
	});
});
</script>
