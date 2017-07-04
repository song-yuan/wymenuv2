<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('支付订单');
	$orderTatsePrice = 0.00;
	$isCupon = false;
	if(!empty($cupons)){
		$isCupon = true;
	}
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/order.css">
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
<?php endif;?>
<div class="order-info">
	<?php foreach($orderProducts as $product):?>
	<div class="item">
		<div class="lt"><?php echo $product['product_name'];?></div>
		<div class="rt">X<?php echo $product['amount'];?> ￥<?php echo number_format($product['price'],2);?></div>
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
	<div class="ht1"></div>
	<?php if(!empty($order['taste'])):?>
		<div class="taste">整单口味:
		<?php foreach ($order['taste'] as $otaste): $orderTatsePrice +=$otaste['price'];?>
		<span> <?php echo $otaste['name'].'('.$otaste['price'].')';?> </span>
		<?php endforeach;?>
		</div>
	<?php endif;?>
	<!-- 其他费用 -->
	<?php if($order['order_type']==1||$order['order_type']==3):?>
	<div class="item">
		<div class="lt">餐位费:</div>
		<div class="rt">X1 ￥<?php echo $seatingFee?number_format($seatingFee,2):'免费';?></div>
		<div class="clear"></div>
	</div>
	<?php elseif($order['order_type']==2):?>
	<div class="item">
		<div class="lt">包装费:</div>
		<div class="rt">X1 ￥<?php echo $packingFee?number_format($packingFee,2):'免费';?></div>
		<div class="clear"></div>
	</div>
	<div class="item">
		<div class="lt">配送费:</div>
		<div class="rt">X1 ￥<?php echo $freightFee?number_format($freightFee,2):'免费';?></div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
	<?php if($orderTatsePrice>0):?>
	<div class="item">
		<div class="lt">口味加价:</div><div class="rt">￥<?php echo number_format($orderTatsePrice,2);?></div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
	<div class="item">
		<div class="lt">总计:</div><div class="rt">￥<?php echo $order['reality_total'];?></div>
		<div class="clear"></div>
	</div>
	<?php if($order['reality_total'] > $order['should_total']):?>
	<div class="item">
		<div class="lt">优惠</div><div class="rt">-￥<?php echo number_format($order['reality_total'] - $order['should_total'],2);?></div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
	<div class="item">
		<div class="lt">实付:</div><div class="rt">￥<?php echo $order['should_total'];?></div>
		<div class="clear"></div>
	</div>
</div>
<?php if($order['order_type']==3):?>
<div class="order-time arrowright">
	<div class="time-lt">预约时间</div>
	<div class="time-rt"><input  type="text" class="" name="order_time" id="appDateTime" value="<?php if($order['appointment_time'] > "0000-00-00 00:00:00") echo $order['appointment_time'];?>" placeholder="选择预约时间" readonly="readonly" ></div>
	<div class="clear"></div>
</div>
<?php endif;?>
<?php if($order['cupon_branduser_lid'] > 0):?>
<div class="order-copun arrowright disabled">
	<div class="copun-lt">代金券</div>
	<div class="copun-rt"><?php echo $order['cupon_money'];?>元</div>
	<div class="clear"></div>
</div>
<?php else:?>
<!-- 完善资料才能使用代金券  -->
<?php if($user['mobile_num']&&$user['user_birthday']):?>
<div class="order-copun arrowright cupon <?php if(!$isCupon) echo 'disabled';?>">
	<div class="copun-lt">代金券</div>
	<div class="copun-rt"><?php if($isCupon):?>选择代金券<?php else:?>无可用代金券<?php endif;?></div>
	<div class="clear"></div>
</div>
<?php else:?>
<div class="order-copun arrowright disabled">
	<div class="copun-lt">代金券</div>
	<div class="copun-rt"><a href="<?php echo $this->createUrl('/user/setUserInfo',array('companyId'=>$this->companyId));?>">去完善资料</a></div>
	<div class="clear"></div>
</div>
<?php endif;?>
<?php endif;?>

<div class="order-remark">
	<textarea name="remark" placeholder="备注"></textarea>
</div>
<div class="order-paytype">
	<div class="select-type">选择支付方式</div>
	<div class="paytype">
		<div class="item wx on" paytype="2"><img src="<?php echo $baseUrl;?>/img/mall/wxpay.png"/> 微信支付</div>
		<!-- 
		<div class="item zfb" paytype="1" style="border:none;"><img src="<?php echo $baseUrl;?>/img/mall/zfbpay.png"/> 支付宝支付</div>
		-->
		<input type="hidden" name="paytype" value="2" />
	</div>
</div>
<div class="bottom"></div>

<footer>
    <div class="ft-lt">
        <p>￥<span id="total" class="total"><?php echo $order['should_total'];?></span></p>
    </div>
    <div class="ft-rt">
        <p><a id="payorder" href="javascript:;">去支付</a></p>
    </div>
    <div class="clear"></div>
</footer>

<div class="user-cupon" id="cuponList">
	<?php if($isCupon):?>
	<?php foreach($cupons as $coupon):?>
		<div class="item useCupon" user-cupon-id="<?php echo $coupon['lid'];?>" min-money="<?php echo $coupon['min_consumer'];?>" cupon-money="<?php echo $coupon['cupon_money'];?>"><?php echo $coupon['cupon_title'];?></div>
	<?php endforeach;?>
		<div class="item noCupon" user-cupon-id="0" min-money="0" cupon-money="0">不使用代金券</div>
	<?php endif;?>
</div>
	<input type="hidden" name="cupon" value="0" />
</form>

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

$(document).ready(function(){
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
	$('.user-cupon .item.useCupon').click(function(){
		var userCuponId = $(this).attr('user-cupon-id');
		var cuponMoney = $(this).attr('cupon-money');
		var noCuponMoney = $('.noCupon').attr('cupon-money');
		var minMoney = $(this).attr('min-money');
		var total = $('#total').html();
		var money = 0;
		
		$('.user-cupon .item').removeClass('on');
		$(this).addClass('on');
		$('#cuponList').css('display','none');
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
		money = money.toFixed(2);
		$('#total').html(money);
		$('.cupon').find('.copun-rt').html('满'+minMoney+'减'+cuponMoney);
	});
	$('.user-cupon .item.noCupon').click(function(){
		var userCuponId = $(this).attr('user-cupon-id');
		var cuponMoney = $(this).attr('cupon-money');
		var minMoney = $(this).attr('min-money');
		var total = $('#total').html();
		var money = 0;
		
		$('.user-cupon .item').removeClass('on');
		$(this).addClass('on');
		$('#cuponList').css('display','none');
		$('input[name="cupon"]').val(userCuponId);
		
		$(this).attr('min-money',0);
		$(this).attr('cupon-money',0);
		
		money = parseFloat(total) + parseFloat(cuponMoney);
		if(money > 0){
			money = money;
		}else{
			money = 0;
		}
		money = money.toFixed(2);
		$('#total').html(money);
		$('.cupon').find('.copun-rt').html('请选择代金券');
	});
	$('.cupon').click(function(){
		if($(this).hasClass('disabled')){
			layer.msg('无可用代金券');
			return;
		}
		$('#cuponList').css('display','block');
	});
	$('#payorder').click(function(){
		<?php if($order['order_type']==1):?>
		layer.load(2);
		$('form').submit();
		<?php elseif($order['order_type']==2):?>
		var address = $('input[name="address"]').val();
		if(parseInt(address) < 0){
			layer.msg('请添加收货地址!');
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
		layer.load(2);
		$('form').submit();
		<?php endif;?>
	});
});
</script>
