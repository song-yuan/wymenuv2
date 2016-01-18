<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('确认订单');
	$isCupon = false;
	if(!empty($cupons)){
		$isCupon = true;
	}
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/cart.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/order.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
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

<form action="<?php echo $this->createUrl('/mall/generalOrder',array('companyId'=>$this->companyId,'type'=>$this->type));?>" method="post">
<div class="order-title">确认订单</div>
<?php if($this->type==1):?>
<div class="site_no" style="background: rgb(255,255,255);margin:10px 0;">桌号:<input type="text" class="serial" name="serial" value="<?php if($siteType){echo $siteType['name'];}?>><?php echo isset($site['serial'])?$site['serial']:'';?>" placeholder="输入座位号" style="background: rgb(255,255,255);"/>人数: <input type="button" class="num-minus"  value="-" style="background: rgb(255,255,255);"><input type="text" class="number" name="number" value="<?php if($siteNum){ echo (int)(($siteNum['min_persons'] + $siteNum['max_persons'])/2);}else{echo '3';}?>" readonly="readonly" style="background: rgb(255,255,255);"/> <input type="button" class="num-add"  value="+" style="background: rgb(255,255,255);"></div>
<?php elseif($this->type==2):?>
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
<?php else:?>
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

<div class="cart-info">
	<div class="section" style="padding-top:10px;">
	    <?php if(!empty($orderTastes)):?>
	    <div class="taste">整单口味</div><div class="taste-desc"></div>
	    <div class="taste-items" product-id="0">
	    	<?php foreach($orderTastes as $k=>$groups):?>
	    	<div class="item-group">
	    		<div class="item group"><?php echo $groups['name'];?></div>
	    		<?php foreach($groups['tastes'] as $taste):?>
	    			<div class="item t-item" group="<?php echo $k;?>" taste-id="<?php echo $taste['lid'];?>"><?php echo $taste['name'];?></div>
	    		<?php endforeach;?>
	    		<input type="hidden" name="taste[]" value="0" />
	    		<div class="clear"></div>
	    	</div>
	    	<?php endforeach;?>
	    </div>
	    <?php endif;?>
	</div>
	<?php foreach($models as $model):?>
	<div class="section">
		<!--
	    <div class="prt-cat">/div>
	    -->
	    <div class="prt">
	        <div class="prt-lt"><?php echo $model['product_name']?></div>
	        <div class="prt-mt">x<span class="num"><?php echo $model['num']?></span></div>
	        <div class="prt-rt">￥<span class="price"><?php echo $model['price']?></span></div>
	        <div class="clear"></div>
	    </div>
	    <?php if(!empty($model['taste_groups'])):?>
	    <div class="taste">可选口味</div><div class="taste-desc"></div>
	    <div class="taste-items" product-id="<?php echo $model['product_id'];?>">
	    	<?php foreach($model['taste_groups'] as $k=>$groups):?>
	    	<div class="item-group">
	    		<div class="item group"><?php echo $groups['name'];?></div>
	    		<?php foreach($groups['tastes'] as $taste):?>
	    			<div class="item t-item" group="<?php echo $k;?>" taste-id="<?php echo $taste['lid'];?>"><?php echo $taste['name'];?></div>
	    		<?php endforeach;?>
	    		<input type="hidden" name="taste[]" value="0" />
	    		<div class="clear"></div>
	    	</div>
	    	<?php endforeach;?>
	    </div>
	    <?php endif;?>
	</div>
	<?php endforeach;?>
</div>

<?php if($this->type==3):?>
<div class="order-time arrowright">
	<div class="time-lt">预约时间</div>
	<div class="time-rt"><input  type="text" class="" name="order_time" id="appDateTime" value="" placeholder="选择预约时间" readonly="readonly" ></div>
	<div class="clear"></div>
</div>
<?php endif;?>
<div class="order-copun arrowright cupon <?php if(!$isCupon) echo 'disabled';?>">
	<div class="copun-lt">代金券</div>
	<div class="copun-rt"><?php if($isCupon):?>选择代金券<?php else:?>无可用代金券<?php endif;?></div>
	<div class="clear"></div>
</div>
<div class="order-remark">
	<textarea name="remark" placeholder="备注"></textarea>
</div>
<div class="order-paytype">
	<div class="select-type">选择支付方式</div>
	<div class="paytype">
		<?php if($this->type==1):?>
		<div class="item  on" paytype="2">微信支付</div>
		<div class="item" paytype="1" style="border:none;">饭后支付</div>
		<input type="hidden" name="paytype" value="2" />
		<?php else:?>
		<div class="item on" paytype="2" style="border:none;">微信支付</div>
		<input type="hidden" name="paytype" value="2" />
		<?php endif;?>
	</div>
</div>

<!-- 余额 -->
<div class="chooselist points">
	<div class="left">余额 <span class="small font_l"><span id="yue"><?php echo number_format(12.4,2);?></span>元</span></div>
	<div class="right">
	<label><input type="checkbox" name="yue" class="ios-switch green  bigswitch" value="1" /><div><div></div></div></label>
	</div>
</div>
<!-- 余额 -->

<div class="bottom"></div>

<footer>
    <div class="ft-lt">
        <p>￥<span id="total" class="total">0.00</span></p>
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
function setTotal(){ 
    var s=0;
    var v=0;
    var n=0;
    <!--计算总额--> 
    $(".prt").each(function(){ 
   		 s+=parseInt($(this).find('span[class*=num]').text())*parseFloat($(this).find('span[class*=price]').text()); 
    });
    $("#total").html(s.toFixed(2)); 
} 

$(document).ready(function(){
	setTotal();
	<?php if($this->type!=1):?>
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
		location.href = '<?php echo $this->createUrl('/user/setAddress',array('companyId'=>$this->companyId,'url'=>urlencode($this->createUrl('/mall/checkOrder',array('companyId'=>$this->companyId,'type'=>$this->type)))));?>';
	});
	<?php endif;?>
	
	$('.num-minus').click(function(){
		var number = $('.number').val();
		if(parseInt(number) > 1 ){
			$('.number').val(parseInt(number)-1);
		}else{
			$('.number').val(1);
		}
	});
	
	//参数人数增减
	$('.num-add').click(function(){
		var number = $('.number').val();
		$('.number').val(parseInt(number)+1);
	});
	$('.paytype .item').click(function(){
		var paytype = $(this).attr('paytype');
		$('.paytype .item').removeClass('on');
		
		$('input[name="paytype"]').val(paytype);
		$(this).addClass('on');
	});
	
   $('.taste').click(function(){
  	var _this = $(this);
  	layer.open({
	    type: 1,
	    title: false,
	    shadeClose: true,
	    closeBtn: 0,
	    area: ['80%'],
	    content:_this.siblings('.taste-items'),
	    btn: '确定',
	    yes: function(index, layero){ 
         layer.close(index);
    	}
	});
  });
  $('.t-item').click(function(){
  	var tasteItems = $(this).parents('.taste-items');
  	var tasteDesc = $(this).parents('.section').find('.taste-desc');
  	var productId = tasteItems.attr('product-id');
  	var tasteId = $(this).attr('taste-id');
  	var group =  $(this).attr('group');
  	var tastName = $(this).html();
  	
  	if($(this).hasClass('on')){
  		$(this).removeClass('on');
  		$(this).siblings('input').val(0);
  		tasteDesc.find('#'+group+'-'+tasteId).remove();
  	}else{
  		$(this).siblings().removeClass('on');
	  	$(this).addClass('on');
	  	$(this).siblings('input').val(productId+'-'+tasteId);
	  	tasteDesc.find('span[id^='+group+'-]').remove();
	  	var str = '<span id="'+group+'-'+tasteId+'">'+tastName+'</span>';
	  	tasteDesc.append(str);
  	}
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
		<?php if($this->type==1):?>
		var serial = $('.serial').val();
		var number = $('.number').val();
		if(serial && number){
			if(isNaN(number)||(parseInt(number)!=number)||number < 0){
				layer.msg('输入人数为大于0的整数!');
				return;
			}
			$('form').submit();
		}else{
			if(!serial){
				layer.msg('请输入座位号!');
				return;
			}
			if(!number){
				layer.msg('请输入人数!');
				return;
			}
			
		}
		$('form').submit();
		<?php elseif($this->type==2):?>
		var address = $('input[name="address"]').val();
		if(parseInt(address) < 0){
			layer.msg('请添加收货地址!');
			return;
		}
		$('form').submit();
		<?php elseif($this->type==3):?>
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
		$('form').submit();
		<?php endif;?>
	});
});
</script>
