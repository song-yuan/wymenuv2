<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('确认订单');
	$isCupon = false;
	if(!empty($cupons)){
		$isCupon = true;
	}
	if($isSeatingFee){
		$seatingFee = $isSeatingFee['fee_price'];
		$seatingTips = $isSeatingFee['fee_abstract'];
	}else{
		$seatingFee = 0;
		$seatingTips = '';
	}
	if($isPackingFee){
		$packingFee = $isPackingFee['fee_price'];
	}else{
		$packingFee = 0;
	}
	if($isFreightFee){
		$freightFee = $isFreightFee['fee_price'];
	}else{
		$freightFee = 0;
	}
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/cart.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/order.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/weui.min.css">

<script src="<?php echo $baseUrl;?>/js/mall/date/mobiscroll_002.js" type="text/javascript"></script>
<script src="<?php echo $baseUrl;?>/js/mall/date/mobiscroll_004.js" type="text/javascript"></script>
<link href="<?php echo $baseUrl;?>/css/mall/date/mobiscroll_002.css" rel="stylesheet" type="text/css">
<link href="<?php echo $baseUrl;?>/css/mall/date/mobiscroll.css" rel="stylesheet" type="text/css">
<script src="<?php echo $baseUrl;?>/js/mall/date/mobiscroll.js" type="text/javascript"></script>
<script src="<?php echo $baseUrl;?>/js/mall/date/mobiscroll_003.js" type="text/javascript"></script>
<script src="<?php echo $baseUrl;?>/js/mall/date/mobiscroll_005.js" type="text/javascript"></script>
<link href="<?php echo $baseUrl;?>/css/mall/date/mobiscroll_003.css" rel="stylesheet" type="text/css">
<style>
.layui-layer-btn{height:42px;}
.weui_dialog_confirm .weui_dialog .weui_dialog_hd{margin:0;padding:0;font-size:65%;}
</style>

<form action="<?php echo $this->createUrl('/mall/generalOrder',array('companyId'=>$this->companyId,'type'=>$this->type));?>" method="post">
<div class="order-title">确认订单</div>
<?php if($this->type==1):?>
<!-- 桌号 及人数 -->
	<div class="site_no" style="background: rgb(255,255,255);margin:10px 0;">桌号:<input type="text" class="serial" name="serial" value="<?php if($siteType){echo $siteType['name'].'>';}?><?php echo isset($site['serial'])?$site['serial']:'';?>" placeholder="输入座位号" style="background: rgb(255,255,255);"/>餐位数: <input type="button" class="num-minus"  value="-" style="background: rgb(255,255,255);"><input type="text" class="number" name="number" value="<?php if($siteOpen){echo '0';}else{if($siteNum){ echo (int)$siteNum['max_persons'];}else{echo '3';}}?>" readonly="readonly" style="background: rgb(255,255,255);"/> <input type="button" class="num-add"  value="+" style="background: rgb(255,255,255);"></div>
<?php elseif($this->type==2):?>
<!-- 地址 -->
	<div class="address arrowright">
		<?php if($address):?>
			<?php $distance = WxAddress::getDistance($company['lat'],$company['lng'],$address['lat'],$address['lng']);?>
			<?php if($company['distance']*1000 > $distance):?>
			<div class="location">
				<span>收货人：<?php echo $address['name'];?> <?php if($address['sex']==1){echo '先生';}else{echo '女士';}?>   <?php echo $address['mobile'];?></span><br>
				<span class="add">收货地址：<?php echo $address['province'].$address['city'].$address['area'].$address['street'];?></span>
				<input type="hidden" name="address" value="<?php echo $address['lid'];?>"/>
			</div>
			<?php else:?>
			<div class="location" style="line-height: 50px;">
				<span class="add">添加收货地址</span>
				<input type="hidden" name="address" value="-1"/>
			</div>
			<?php endif;?>
		<?php else:?>
		<div class="location" style="line-height: 50px;">
			<span class="add">添加收货地址</span>
			<input type="hidden" name="address" value="-1"/>
		</div>
		<?php endif;?>
	</div>
<?php elseif($this->type==3):?>
	<div class="site_no" style="background: rgb(255,255,255);margin:10px 0;">就餐人数: <input type="button" class="num-minus"  value="-" style="background: rgb(255,255,255);"><input type="text" class="number" name="number" value="3" readonly="readonly" style="background: rgb(255,255,255);"/> <input type="button" class="num-add"  value="+" style="background: rgb(255,255,255);"></div>
	<div class="address arrowright">
		<?php if($address):?>
		<div class="location" style="line-height: 50px;">
			<span>预约人：<?php echo $address['name'];?> <?php if($address['sex']==1){echo '先生';}else{echo '女士';}?> <?php echo $address['mobile'];?></span><br>
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
	<div class="order-time arrowright" style="margin:10px 0;">
		<div class="time-lt">预约时间</div>
		<div class="time-rt"><input  type="text" class="" name="order_time" id="appDateTime" value="" placeholder="选择预约时间" readonly="readonly" ></div>
		<div class="clear"></div>
	</div>
<?php elseif($this->type==6):?>
	<div class="order-site"><div class="lt">取餐方式:</div><div class="rt"><button type="button" class="specialbttn bttn_orange" type_id="0" style="margin-right:20px;">堂食</button><button  type="button" class="specialbttn bttn_grey" type_id="1">打包</button></div><div class="clear"></div></div>
	<div class="order-time arrowright" style="margin:10px 0;">
		<div class="time-lt">取餐时间</div>
		<div class="time-rt">
			<select name="order_time">
                 <option selected="selected" value="0">即食</option>
                 <option value="15">15分钟</option>
                 <option value="20">20分钟</option>
                 <option value="25">25分钟</option>
                 <option value="30">30分钟</option>
                 <option value="40">40分钟</option>
                 <option value="50">50分钟</option>
                 <option value="60">1小时</option>
            </select>
		</div>
		<div class="clear"></div>
	</div>
<?php endif;?>

<!-- 购物车商品 -->
<div class="cart-info">
	<?php if(!empty($orderTastes)):?>
	<div class="section">
		<div class="taste-desc"></div>
	    <div class="taste">整单口味</div>
	    <div class="taste-items" product-id="0">
	    	<?php foreach($orderTastes as $k=>$groups):?>
	    	<div class="item-group"><?php echo $groups['name'];?></div>
	    	<div class="item-group">
	    		<?php foreach($groups['tastes'] as $taste):?>
	    			<div class="item t-item" group="<?php echo $k;?>" taste-id="<?php echo $taste['lid'];?>" taste-pirce="<?php echo $taste['price'];?>"><?php echo $taste['name'];?><?php if($taste['price']):?>(<span class="taste-pice"><?php echo $taste['price'];?></span>)<?php endif;?></div>
	    		<?php endforeach;?>
	    		<input type="hidden" name="taste[]" value="0" />
	    		<div class="clear"></div>
	    	</div>
	    	<?php endforeach;?>
	    </div>
	</div>
	<?php endif;?>
	<?php foreach($models as $model):?>
	<div class="section cartProduct">
		<!--
	    <div class="prt-cat">/div>
	    -->
	    <div class="prt">
	        <div class="prt-lt"><?php echo $model['product_name']?></div>
	        <div class="prt-mt">x<span class="num"><?php echo $model['num']?></span></div>
	        <div class="prt-rt">￥<span class="price"><?php echo $model['price']?></span></div>
	        <div class="clear"></div>
	    </div>
	    <!-- 可选择口味 -->
	    <?php if(isset($model['taste_groups'])&&!empty($model['taste_groups'])):?>
	    <div class="taste-desc"></div>
	    <div class="taste">可选口味</div>
	    <div class="taste-items" product-id="<?php echo $model['product_id'];?>">
	    	<?php foreach($model['taste_groups'] as $k=>$groups):?>
	    	<div class="item-group"><?php echo $groups['name'];?></div>
	    	<div class="item-group">
	    		<?php foreach($groups['tastes'] as $taste):?>
	    			<div class="item t-item" group="<?php echo $k;?>" taste-id="<?php echo $taste['lid'];?>" taste-pirce="<?php echo $taste['price'];?>"><?php echo $taste['name'];?><?php if($taste['price'] > 0):?>(<?php echo $taste['price'];?>)<?php endif;?></div>
	    		<?php endforeach;?>
	    		<input type="hidden" name="taste[]" value="0" />
	    		<div class="clear"></div>
	    	</div>
	    	<?php endforeach;?>
	    </div>
	    <?php endif;?>
	    <!-- 可选择套餐 -->
	    <?php if(isset($model['detail'])&&!empty($model['detail'])):?>
	     <div class="detail-desc">
	     <?php foreach ($model['detail'] as $k=>$detail):?>
	     	<?php foreach($detail as $item):?>
	     		<?php if($item['is_select'] > 0):?>
    			<span id="<?php echo $k.'-'.$item['product_id'];?>"><?php echo $item['product_name'].'x'.$item['number'];?><?php if($item['price'] > 0):?>(<?php echo $item['price'];?>)<?php endif;?></span>
    			<?php endif;?>
    		<?php endforeach;?>
	     <?php endforeach;?>
	     </div>
	     <div class="detail">可选套餐</div>
	     <div class="detail-items" set-id="<?php echo $model['product_id'];?>">
		     <?php foreach ($model['detail'] as $k=>$detail): $selectItem = 0;?>
		     <div class="item-group">选择一个</div>
		     <div class="item-group">
	    		<?php foreach($detail as $item): $on = ''; if($item['is_select'] > 0){$on='on';$selectItem = $model['product_id'].'-'.$item['product_id'].'-'.$item['number'].'-'.$item['price'];}?>
	    			<div class="item t-item <?php echo $on;?>" group="<?php echo $k;?>" product-id="<?php echo $item['product_id'];?>" detail-num="<?php echo $item['number'];?>" detail-pirce="<?php echo $item['price'];?>"><?php echo $item['product_name'].'x'.$item['number'];?><?php if($item['price'] > 0):?>(<?php echo $item['price'];?>)<?php endif;?></div>
	    		<?php endforeach;?>
	    		<input type="hidden" name="set-detail[]" value="<?php echo $selectItem;?>" />
	    		<div class="clear"></div>
	    	</div>
	     	<?php endforeach;?>
	     </div>
	    <?php endif;?>
	</div>
	<?php endforeach;?>
	<?php if($this->type==1||$this->type==3):?>
		<!-- begain餐位费 -->
		<div class="section seatingFee" price="<?php echo $seatingFee;?>">
			 <div class="prt">
		        <div class="prt-lt">餐位费</div>
		        <div class="prt-mt">x<span class="num"></span></div>
		        <div class="prt-rt">￥<span class="price"></span></div>
		        <div class="clear"></div>
		    </div>
		</div>
		<div class="weui_cells_tips"><?php echo $seatingTips;?></div>
		<!-- end餐位费 -->
	<?php elseif($this->type==2):?>
		<!-- begain餐位费 -->
		<div class="section packingFee" price="<?php echo $packingFee;?>">
			 <div class="prt">
		        <div class="prt-lt">包装费</div>
		        <div class="prt-mt">x<span class="num"></span></div>
		        <div class="prt-rt">￥<span class="price"></span></div>
		        <div class="clear"></div>
		    </div>
		</div>
		<!-- end餐位费 -->
		<!-- begain餐位费 -->
		<div class="section freightFee" price="<?php echo $freightFee;?>">
			 <div class="prt">
		        <div class="prt-lt">配送费</div>
		        <div class="prt-mt">x<span class="num">1</span></div>
		        <div class="prt-rt">￥<span class="price"><?php echo number_format($freightFee,2);?></span></div>
		        <div class="clear"></div>
		    </div>
		</div>
		<!-- end餐位费 -->
	<?php endif;?>
	<div class="totalinfo" style="padding-top:10px"><span class="font_l" style="margin-right:20px;">总计￥<?php echo $original;?></span><?php if($original!=$price) echo '<span class="font_l" style="margin-right:20px;">会员优惠￥'.number_format($original-$price,2).'</span>';?><span>实付￥<?php echo $price;?></span></div>
</div>
<?php if($this->type!=2&&$user['level']):?>
<!-- 
<div class="discount">
	<ul>
		<li><img src="<?php echo $baseUrl;?>/img/mall/act_03.png" alt="">无优惠商品享受<?php echo $user['level']['level_discount']*10;?>折优惠</li>
		<li><img src="<?php echo $baseUrl;?>/img/mall/act_03.png" alt="">无优惠商品商品享受生日<?php echo $user['level']['birthday_discount']*10;?>折优惠</li>
	</ul>
</div>
 -->
<?php endif;?>
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

<div class="order-remark">
	<textarea name="taste_memo" placeholder="备注"></textarea>
</div>
<div class="order-paytype">
	<div class="select-type">选择支付方式</div>
	<!-- 余额 -->
	<div class="chooselist points" style="padding:15px;">
		<div class="left"><img src="<?php echo $baseUrl;?>/img/wechat_img/icon-wdcz.png"/> 储值支付 <span class="small font_l"><span id="yue" yue="<?php echo $remainMoney;?>"><?php echo $remainMoney;?></span>元</span></div>
		<div class="right">
		<label><input type="checkbox" name="yue" class="ios-switch green  bigswitch" value="1" /><div><div></div></div></label>
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
        <p>待付款 ￥<span id="total" class="total" total="<?php echo $price;?>"><?php echo $price;?></span></p>
    </div>
    <div class="ft-rt">
        <p><a id="payorder" href="javascript:;">提交订单</a></p>
    </div>
    <div class="clear"></div>
</footer>

<div class="user-cupon" id="cuponList">
	<div class="cupon-container">
	<?php if($isCupon):?>
	<?php foreach($cupons as $coupon):?>
		<div class="item useCupon" user-cupon-id="<?php echo $coupon['lid'];?>" min-money="<?php echo $coupon['min_consumer'];?>" cupon-money="<?php echo $coupon['cupon_money'];?>"><?php echo $coupon['cupon_title'];?></div>
	<?php endforeach;?>
		<div class="item noCupon" user-cupon-id="0" min-money="0" cupon-money="0">不使用代金券</div>
	<?php endif;?>
	</div>
</div>
	<input type="hidden" name="cupon" value="0" />
	<input type="hidden" name="takeout_typeid" value="0" />
</form>

 <!--BEGIN dialog1-->
<div class="weui_dialog_confirm" id="dialog" style="display: none;">
    <div class="weui_mask"></div>
    <div class="weui_dialog">
        <div class="weui_dialog_hd"><strong class="weui_dialog_title">餐位数提示</strong></div>
        <div class="weui_dialog_bd content" style="text-align:center;"></div>
        <div class="weui_dialog_ft">
            <a href="javascript:;" class="weui_btn_dialog default">取消</a>
            <a href="javascript:;" class="weui_btn_dialog primary">确定</a>
        </div>
    </div>
</div>
<!--END dialog1-->

<script>
function emptyCart(){
	var timestamp=new Date().getTime()
    var random = timestamp +''+ (Math.random()*899+100);
	$.ajax({
		url:'<?php echo $this->createUrl('/mall/emptyCart',array('companyId'=>$this->companyId,'userId'=>$user['lid']));?>',
		type:'GET',
		data:{random:random},
		success:function(msg){
			if(parseInt(msg)==1){
				location.href = '<?php echo $this->createUrl('/mall/index',array('companyId'=>$this->companyId));?>';
			}
		}
	});
}
function reset_total(price){
	var setTotal = $('#total').attr('total');
	var yue = $('#yue').attr('yue');
	var total = $('#total').html();
	var totalFee = parseFloat(total) + parseFloat(price);
	$('#total').attr('total',parseFloat(setTotal) + parseFloat(price));
	
	if($('input[name="yue"]').is(':checked')){
		if(parseFloat(yue) > (parseFloat(setTotal) + parseFloat(totalFee))){
			totalFee = 0;
		}
	}
	if(totalFee > 0){
		totalFee =  totalFee.toFixed(2);
	}else{
		totalFee = '0.00';
	}
	
	$('#total').html(totalFee);
}
window.onload = emptyCart;
$(document).ready(function(){
	var cupon_layer = 0;
	var msg = "<?php echo $msg;?>";
	if(msg){
		layer.msg(msg);
	}
	<?php if($this->type==3):?>
	var today = new Date();
	var currYear = today.getFullYear();
	var currMonth = today.getMonth();
	var currDay = today.getDate();
	var currHours = today.getHours();
	var currMinutes = today.getMinutes();
		
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
    
    var totalPackFee = 0;
    var totalPackNum = 0;
    var number = $('.number').val();
	var total = $('#total').html();
	
	var packingFee = $('.packingFee').attr('price');
	$('.cartProduct').each(function(){
		var num = $(this).find('.num').html();
		totalPackNum += parseInt(num);
		totalPackFee += parseInt(num)*parseFloat(packingFee);
	});
	totalPackFee = totalPackFee.toFixed(2);
	$('.packingFee').find('.num').html(totalPackNum);
	$('.packingFee').find('.price').html(totalPackFee);
	
	var totalFee = parseFloat(total) + parseFloat(totalPackFee);
	totalFee =  totalFee.toFixed(2);
	
	$('#total').html(totalFee);
	$('#total').attr('total',totalFee);
	
	$('.location').click(function(){
		location.href = '<?php echo $this->createUrl('/user/setAddress',array('companyId'=>$this->companyId,'url'=>urlencode($this->createUrl('/mall/checkOrder',array('companyId'=>$this->companyId,'type'=>$this->type)))));?>';
	});
	<?php elseif($this->type==2):?>
	var totalPackFee = 0;
	var totalPackNum = 0;
	var total = $('#total').html();
	var packingFee = $('.packingFee').attr('price');
	var freightFee = $('.freightFee').attr('price');
	$('.cartProduct').each(function(){
		var num = $(this).find('.num').html();
		totalPackNum += parseInt(num);
		totalPackFee += parseInt(num)*parseFloat(packingFee);
	});
	totalPackFee = totalPackFee.toFixed(2);
	$('.packingFee').find('.num').html(totalPackNum);
	$('.packingFee').find('.price').html(totalPackFee);
	
	var totalFee = parseFloat(total) + parseFloat(totalPackFee) + parseFloat(freightFee);
	totalFee =  totalFee.toFixed(2);
	$('#total').html(totalFee);
	$('#total').attr('total',totalFee);
	
	$('.location').click(function(){
		location.href = '<?php echo $this->createUrl('/user/setAddress',array('companyId'=>$this->companyId,'type'=>$this->type,'url'=>urlencode($this->createUrl('/mall/checkOrder',array('companyId'=>$this->companyId,'type'=>$this->type)))));?>';
	});
	<?php elseif($this->type==1):?>
	var number = $('.number').val();
	var seatFee = $('.seatingFee').attr('price');
	var total = $('#total').html();
	
	$('.seatingFee').find('.num').html(number);
	$('.seatingFee').find('.price').html(parseInt(number)*seatFee);
	
	var totalFee = parseFloat(total) + parseInt(number)*seatFee;
	totalFee =  totalFee.toFixed(2);
	
	$('#total').html(totalFee);
	$('#total').attr('total',totalFee);
	<?php endif;?>

	$('button').click(function(){
		var typeId = $(this).attr('type_id');
		$('button').addClass('bttn_grey');
		$(this).removeClass('bttn_grey');
		$(this).addClass('bttn_orange');
		$('input[name="takeout_typeid"]').val(typeId);
	});
	$('.num-minus').click(function(){
		var number = $('.number').val();
		<?php if($this->type==1):?>
		var seatFee = $('.seatingFee').attr('price');
		<?php elseif($this->type==3):?>
		var seatFee = $('.packingFee').attr('price');
		<?php endif;?>
		
		if(parseInt(number) > 1 ){
			$('.number').val(parseInt(number)-1);
			<?php if($this->type==1):?>
			$('.seatingFee').find('.num').html(parseInt(number)-1);
			$('.seatingFee').find('.price').html((parseInt(number)-1)*seatFee);
			<?php endif;?>
			if(parseFloat(seatFee)>0){
				reset_total(-seatFee);
			}
		}else if(parseInt(number) == 1){
			<?php if($siteOpen):?>
				$('.number').val(parseInt(number)-1);
				<?php if($this->type==1):?>
				$('.seatingFee').find('.num').html(parseInt(number)-1);
				$('.seatingFee').find('.price').html((parseInt(number)-1)*seatFee);
				<?php endif;?>
			
				if(parseFloat(seatFee)>0){
					reset_total(-seatFee);
				}
			<?php endif;?>
		}
	});
	
	//参数人数增减
	$('.num-add').click(function(){
		var number = $('.number').val();
		<?php if($this->type==1):?>
		var seatFee = $('.seatingFee').attr('price');
		<?php elseif($this->type==3):?>
		var seatFee = $('.packingFee').attr('price');
		<?php endif;?>
		$('.number').val(parseInt(number)+1);
		<?php if($this->type==1):?>
		$('.seatingFee').find('.num').html(parseInt(number)+1);
		$('.seatingFee').find('.price').html((parseInt(number)+1)*seatFee);
		<?php endif;?>
		
		if(parseFloat(seatFee)>0){
			reset_total(seatFee);
		}
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
	    area: ['100%','60%'],
	    content:_this.siblings('.taste-items'),
	    btn: '确定',
	    yes: function(index, layero){ 
         layer.close(index);
    	}
	});
  });
  $('.taste-items .t-item').click(function(){
	var sectionObj = $(this).parents('.section');
  	var tasteItems = $(this).parents('.taste-items');
  	var tasteDesc = sectionObj.find('.taste-desc');
  	var productId = tasteItems.attr('product-id');
  	var tasteId = $(this).attr('taste-id');
  	var group =  $(this).attr('group');
  	var tastePrice = $(this).attr('taste-pirce');
  	var tastName = $(this).html();
  	var num = 1;
  	if(sectionObj.find('.num').length > 0){
  		num = sectionObj.find('.num').html();
  	}
  	
  	if($(this).hasClass('on')){
  		$(this).removeClass('on');
  		$(this).siblings('input').val(0);
  		tasteDesc.find('#'+group+'-'+tasteId).remove();
  		if(parseFloat(tastePrice) > 0){
  			reset_total(-tastePrice*num);
  	  	}
  	}else{
  	  	var onObj = $(this).siblings('.on');
  	  	if(onObj.length > 0){
	  	  	var onPrice = onObj.attr('taste-pirce');
	  	  	if(parseFloat(onPrice) > 0){
	  	  		reset_total(-onPrice*num);
	  	  	}
	  	  	onObj.removeClass('on');
  	  	}
	  	$(this).addClass('on');
	  	$(this).siblings('input').val(productId+'-'+tasteId+'-'+tastePrice);
	  	tasteDesc.find('span[id^='+group+'-]').remove();
	  	var str = '<span id="'+group+'-'+tasteId+'">'+tastName+'</span>';
	  	tasteDesc.append(str);
	  	if(parseFloat(tastePrice) > 0){
  			reset_total(tastePrice*num);
  	  	}
  	}
  });

  $('.detail').click(function(){
	 	var _this = $(this);
	 	layer.open({
		    type: 1,
		    title: false,
		    shadeClose: false,
		    closeBtn: 0,
		    area: ['100%','60%'],
		    content:_this.siblings('.detail-items'),
		    btn: '确定',
		    yes: function(index, layero){ 
	        layer.close(index);
	   	}
		});
 	});
  $('.detail-items .t-item').click(function(){
	  if(!$(this).hasClass('on')){
			var sectionObj = $(this).parents('.section');
		  	var tasteItems = $(this).parents('.detail-items');
		  	var detailDesc = sectionObj.find('.detail-desc');
		  	var setId = tasteItems.attr('set-id');
		  	var productId = $(this).attr('product-id');
		  	var group =  $(this).attr('group');
			var detailNum = $(this).attr('detail-num');
		  	var detailPrice = $(this).attr('detail-pirce');
		  	var detailName = $(this).html();
		  	var num = sectionObj.find('.num').html();
	  	  	var onObj = $(this).siblings('.on');
	  	  	if(onObj.length > 0){
		  	  	var onPrice = onObj.attr('detail-pirce');
		  	  	if(parseFloat(onPrice) > 0){
		  	  		reset_total(-onPrice*num);
		  	  	}
		  	  	onObj.removeClass('on');
	  	  	}
		  	$(this).addClass('on');
		  	$(this).siblings('input').val(setId+'-'+productId+'-'+detailNum+'-'+detailPrice);
		  	detailDesc.find('span[id^='+group+'-]').remove();
		  	var str = '<span id="'+group+'-'+productId+'">'+detailName+'</span>';
		  	detailDesc.append(str);
		  	if(parseFloat(detailPrice) > 0){
	  			reset_total(detailPrice*num);
	  	  	}
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
		$('#total').attr('total',money);
		$('.cupon').find('.copun-rt').html('满'+minMoney+'减'+cuponMoney);
		layer.close(cupon_layer);
	});
	$('.user-cupon .item.noCupon').click(function(){
		var userCuponId = $(this).attr('user-cupon-id');
		var cuponMoney = $(this).attr('cupon-money');
		var minMoney = $(this).attr('min-money');
		var total = $('#total').html();
		var money = 0;
		
		$('.user-cupon .item').removeClass('on');
		$(this).addClass('on');
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
		$('#total').attr('total',money);
		$('.cupon').find('.copun-rt').html('请选择代金券');
		layer.close(cupon_layer);
	});
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
	$('input[name="yue"]').change(function(){
		var total = $('#total').attr('total');
		var yue = $('#yue').attr('yue');
		if(parseFloat(yue) == 0){
			layer.msg('余额不足!');
			$(this).prop('checked',false);
		}
		
		if($(this).is(':checked')){
			if(parseFloat(yue) > parseFloat(total)){
				var money = 0;
				money = money.toFixed(2);
				$('#total').html(money);
			}else{
				var money = total - yue;
				money = money.toFixed(2);
				$('#total').html(money);
			}
		}else{
			if(parseFloat(yue) > parseFloat(total)){
				var money = parseFloat(total);
				money = money.toFixed(2);
				$('#total').html(money);
			}else{
				var money = parseFloat(total);
				money = money.toFixed(2);
				$('#total').html(money);
			}
		}
		
	});
	$('#payorder').click(function(){
		<?php if($this->type==1):?>
			var serial = $('.serial').val();
			var number = $('.number').val();
			var seatingFee = $('.seatingFee').find('.price').html();
			if(serial && number){
				if(serial=='>'){
					layer.msg('请输入座位号!');
					return;
				}
				if(isNaN(number)||(parseInt(number)!=number)||number < 0){
					layer.msg('输入人数为大于0的整数!');
					return;
				}
				$('#dialog .content').html('餐位数:'+number+'人;餐位费:'+seatingFee+'元');
				$('#dialog').show();
			}else{
				if(!serial||serial=='>'){
					layer.msg('请输入座位号!');
					return;
				}
				if(!number){
					layer.msg('请输入人数!');
					return;
				}
			}
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
			var timestamp = Date.parse(today) / 1000;
			var pointsTime = Date.parse(orderTime) / 1000;
			if(timestamp > pointsTime){
				layer.msg('预约时间必须大于当前时间!');
				return;
			}
			layer.load(2);
			$('form').submit();
			layer.closeAll('loading');
		<?php else:?>
			layer.load(2);
			$('form').submit();
			layer.closeAll('loading');
		<?php endif;?>
	});
	$('#dialog .primary').click(function(){
		layer.load(2);
		$('#dialog').hide();
		$('form').submit();
		layer.closeAll('loading');
	});
	$('#dialog .default').click(function(){
		$('#dialog').hide();
	});
});
</script>
