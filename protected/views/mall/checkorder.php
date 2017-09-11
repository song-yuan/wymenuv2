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
.weui_dialog_confirm .weui_dialog .weui_dialog_hd{margin:0;padding:0;font-size:50%;}
.weui_mask{z-index:9002;}
.weui_dialog{z-index:9003;}
</style>

<form action="<?php echo $this->createUrl('/mall/generalOrder',array('companyId'=>$this->companyId,'type'=>$this->type));?>" method="post">
<div class="order-title">确认订单</div>
<?php if($this->type==1):?>
<!-- 桌号 及人数 -->
	<div class="site_no" style="background: rgb(255,255,255);margin:10px 0;">桌台:<?php echo $siteType['name'].$site['serial'];?></div>
<?php elseif(in_array($this->type, array(2,7,8))):?>
<!-- 地址 -->
	<div class="address arrowright">
		<?php if(!empty($address)):?>
			<?php $distance = WxAddress::getDistance($company['lat'],$company['lng'],$address['lat'],$address['lng']);?>
			<?php if($company['distance']*1000 < $distance):?>
			<div class="location" style="line-height: 50px;">
				<span class="add">添加收货地址</span>
				<input type="hidden" name="address" value="-1"/>
			</div>
			<?php else:?>
			<div class="location">
				<span>收货人：<?php echo $address['name'];?> <?php if($address['sex']==1){echo '先生';}else{echo '女士';}?>   <?php echo $address['mobile'];?></span><br>
				<span class="add">收货地址：<?php echo $address['province'].$address['city'].$address['area'].$address['street'];?></span>
				<input type="hidden" name="address" value="<?php echo $address['lid'];?>"/>
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
	<div class="order-site"><div class="lt">取餐方式</div><div class="rt"><button type="button" class="specialbttn bttn_orange" type_id="0" style="margin-right:20px;">堂食</button><button  type="button" class="specialbttn bttn_grey" type_id="1">打包</button></div><div class="clear"></div></div>
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
	<div class="section clearfix">
		<?php if(!empty($orderTastes)):?>
		<div class="taste-desc"></div>
	    <div class="taste left">整单口味</div>
	    <div class="taste-items" product-id="0">
	    	<?php foreach($orderTastes as $k=>$groups):?>
	    	<div class="item-group"><?php echo $groups['name'];?></div>
	    	<div class="item-group">
	    		<?php foreach($groups['tastes'] as $taste):?>
	    			<div class="item t-item taste-item"  group="<?php echo $k;?>" taste-id="<?php echo $taste['lid'];?>" taste-pirce="<?php echo $taste['price'];?>"><?php echo $taste['name'];?><?php if($taste['price']>0):?>(<span class="taste-pice"><?php echo $taste['price'];?></span>)<?php endif;?></div>
	    		<?php endforeach;?>
	    		<input type="hidden" name="taste[]" value="0" />
	    		<div class="clear"></div>
	    	</div>
	    	<?php endforeach;?>
	    </div>
	    <?php endif;?>
	    <div class="right"><a href="<?php echo $this->createUrl('/mall/index',array('companyId'=>$this->companyId,'type'=>$this->type));?>" ><img style="width:25px;height:25px;vertical-align:middle;" alt="" src="<?php echo $baseUrl; ?>/img/mall/icon_edit.png"><span style="vertical-align:middle;">订单修改</span></a></div>
	</div>
	<!--  购物车中无效产品 -->
	<?php foreach($disables as $disable):?>
	<div class="section cartProduct disable">
		<!--
	    <div class="prt-cat">/div>
	    -->
	    <div class="prt">
	        <div class="prt-lt"><?php if($disable['promotion_type']=='sent'): ?><span class="bttn_orange">赠</span><?php endif;?><?php echo $disable['product_name'];?></div>
	        <div class="prt-mt">x<span class="num"><?php echo $disable['num'];?></span></div>
	        <div class="prt-rt">￥<span class="price"><?php echo $disable['member_price'];?></span><span class="cart-delete" lid="<?php echo $disable['lid'];?>">删除</span></div>
	        <div class="clear"></div>
	    </div>
	    <div class="taste-desc"><?php echo $disable['msg'];?></div>
	</div>
	<?php endforeach;?>
	<!--  购物车中可下单产品 -->
	<?php foreach($models as $model):?>
	<div class="section cartProduct">
		<!--
	    <div class="prt-cat">/div>
	    -->
	    <div class="prt">
	        <div class="prt-lt"><?php if($model['promotion_type']=='sent'): ?><span class="bttn_orange">赠</span><?php endif;?><?php echo $model['product_name'];?></div>
	        <div class="prt-mt">x<span class="num"><?php echo $model['num'];?></span></div>
	        <div class="prt-rt">￥<span class="price"><?php echo $model['price'];?></span></div>
	        <div class="clear"></div>
	    </div>
	    <!-- b可选择口味 -->
	    <?php if(isset($model['taste_groups'])&&!empty($model['taste_groups'])):?>
	    <div class="taste-items" product-id="<?php echo $model['product_id'];?>">
	    	<?php 
	    		$tdesc = ''; 
	    		foreach($model['taste_groups'] as $k=>$groups):
	    		$tvalue = 0;
	    	?>
	    	<div class="item-group"><?php echo $groups['name'];?></div>
	    	<div class="item-group">
	    		<?php foreach($groups['tastes'] as $tk=>$taste):
	    			$active = '';
	    			if($taste['is_selected']==1){
	    				$tvalue = $groups['product_id'].'-'.$taste["lid"].'-'.$taste["price"];
	    				$active = 'on';
	    				$tprice = '';
	    				if($taste["price"]>0){
	    					$original += $taste["price"];
	    					$price += $taste["price"];
	    					$tprice = '('.$taste["price"].')';
	    				}
	    				$tdesc.='<span id="'.$k.'-'.$taste["lid"].'">'.$taste['name'].$tprice.'</span>';
	    			}
	    		?>
    			<div class="item t-item taste-item <?php echo $active;?>" allflage="<?php echo $groups['allflae'];?>" group="<?php echo $k;?>" taste-id="<?php echo $taste['lid'];?>" taste-pirce="<?php echo $taste['price'];?>"><?php echo $taste['name'];?><?php if($taste['price'] > 0):?>(<?php echo $taste['price'];?>)<?php endif;?></div>
	    		<?php endforeach;?>
	    		<input type="hidden" name="taste[]" value="<?php echo $tvalue;?>" />
	    		<div class="clear"></div>
	    	</div>
	    	<?php endforeach;?>
	    </div>
	    <div class="taste-desc"><?php echo $tdesc;?></div>
	    <div class="taste">可选口味</div>
	    <?php endif;?>
	    <!-- e可选择口味 -->
	    <!-- b可选择套餐 -->
	    <?php if(isset($model['detail'])&&!empty($model['detail'])):?>
	    <div class="detail-items" set-id="<?php echo $model['product_id'];?>">
		     <?php $detailDesc = ''; foreach ($model['detail'] as $k=>$detail): $selectItem = 0;?>
		     <div class="item-group">选择一个</div>
		     <div class="item-group">
	    		<?php 
	    			foreach($detail as $item): 
	    			$on = '';
	    			if($item['is_select']==1){
	    				$on='on';
	    				$selectItem = $model['product_id'].'-'.$item['product_id'].'-'.$item['number'].'-'.$item['price'];
	    				$detailDesc .='<span id="'. $k.'-'.$item['product_id'].'">'.$item['product_name'].'x'.$item['number'];
	    				if($item['price'] > 0){
	    					$original += $item["price"];
	    					$price += $item["price"];
	    				}
	    				$detailDesc .='</span>';
	    			}
	    		?>
	    		<!-- b套餐中产品口味 -->
	    		<?php if(!empty($item['taste_groups'])):?>
	    		<div class="taste-items" product-id="<?php echo $model['product_id'].'-'.$item['product_id'];?>">
			    	<?php 
			    		$tdesc = '';
			    		foreach($item['taste_groups'] as $kk=>$groups):
			    		$tvalue = 0;
			    	?>
			    	<div class="item-group"><?php echo $groups['name'];?></div>
			    	<div class="item-group">
			    		<?php foreach($groups['tastes'] as $tk=>$taste):
			    			$active = '';
			    			if($taste['is_selected']==1){
			    				$tvalue = $groups['product_id'].'-'.$taste["lid"].'-'.$taste["price"];
			    				$active = 'on';
			    				$tprice = '';
			    				if($taste["price"]>0){
			    					$original += $taste["price"];
			    					$price += $taste["price"];
			    				}
			    				$tdesc.='<span id="'.$kk.'-'.$taste["lid"].'">'.$taste['name'].$tprice.'</span>';
			    			}
			    		?>
		    			<div class="item t-item taste-item <?php echo $active;?>" allflage="<?php echo $groups['allflae'];?>" group="<?php echo $kk;?>" taste-id="<?php echo $taste['lid'];?>" taste-pirce="<?php echo $taste['price'];?>"><?php echo $taste['name'];?><?php if($taste['price'] > 0):?>(<?php echo $taste['price'];?>)<?php endif;?></div>
			    		<?php endforeach;?>
			    		<!-- 
			    		<input type="hidden" name="taste[2][]" value="<?php echo $tvalue;?>" />
			    		 -->
			    		<div class="clear"></div>
			    	</div>
			    	<?php endforeach;?>
			    </div>
			    <div class="item t-item detail-item has-taste <?php echo $on;?>" group="<?php echo $k;?>" product-id="<?php echo $item['product_id'];?>" detail-num="<?php echo $item['number'];?>" detail-pirce="<?php echo $item['price'];?>"><?php echo $item['product_name'].'<span class="detail-desc">('.$tdesc.')</span>'.'x'.$item['number'];?><?php if($item['price'] > 0):?>(<?php echo $item['price'];?>)<?php endif;?></div>
			    <?php else:?>
			    <div class="item t-item detail-item <?php echo $on;?>" group="<?php echo $k;?>" product-id="<?php echo $item['product_id'];?>" detail-num="<?php echo $item['number'];?>" detail-pirce="<?php echo $item['price'];?>"><?php echo $item['product_name'].'x'.$item['number'];?><?php if($item['price'] > 0):?>(<?php echo $item['price'];?>)<?php endif;?></div>
	    		<?php endif;?>
	    		<!-- e套餐中产品口味 -->
	    		<?php endforeach;?>
	    		<input type="hidden" name="set-detail[]" value="<?php echo $selectItem;?>" />
	    		<div class="clear"></div>
	    	</div>
	     	<?php endforeach;?>
	     </div>
	     <div class="detail-desc"><?php echo $detailDesc;?></div>
	     <div class="detail">可选套餐</div>
	    <?php endif;?>
	    <!-- e可选择套餐 -->
	</div>
	<?php endforeach;?>
	<?php if($this->type==3):?>
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
	<?php elseif(in_array($this->type, array(2,7,8))):?>
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

<!-- 如果是餐座 则显示下单 不需要支付  -->
<?php if($this->type!=1):?>
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
		<div class="copun-rt"><a href="<?php echo $this->createUrl('/user/setUserInfo',array('companyId'=>$this->companyId,'type'=>$this->type,'back'=>1));?>">去完善资料</a></div>
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
		<div class="left"><img src="<?php echo $baseUrl;?>/img/wechat_img/icon-wdcz.png"/> 储值支付 <span class="small font_org">剩余￥<span id="yue" yue="<?php echo $remainMoney;?>"><?php echo $remainMoney;?></span> 可使用￥<?php echo $remainMoney > $price?$price:$remainMoney;?></span></div>
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
        <p style="margin-left:10px;">付款 ￥<span id="total" class="total" total="<?php echo $price;?>"><?php echo $price;?></span></p>
    </div>
    <div class="ft-rt">
    	<a id="payorder" href="javascript:;">
        <p>提交订单</p>
        </a>
    </div>
    <div class="clear"></div>
</footer>
<?php else:?>
<footer>
    <div class="ft-lt">
        <p style="margin-left:10px;">付款 ￥<span id="total" class="total" total="<?php echo $price;?>"><?php echo $price;?></span></p>
    </div>
    <div class="ft-rt">
    	<a id="payorder" href="javascript:;">
        <p>下单</p>
        </a>
    </div>
    <div class="clear"></div>
</footer>
<?php endif;?>
<div class="user-cupon" id="cuponList">
	<div class="cupon-container">
	<?php if($isCupon):?>
	<?php foreach($cupons as $coupon):?>
		<div class="item useCupon" user-cupon-id="<?php echo $coupon['lid'].'-'.$coupon['dpid'];?>" min-money="<?php echo $coupon['min_consumer'];?>" cupon-money="<?php echo $coupon['cupon_money'];?>"><?php echo $coupon['cupon_title'];?><span class="small font_org">(满<?php echo (int)$coupon['min_consumer'];?>减<?php echo (int)$coupon['cupon_money'];?>)</span></div>
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
window.onload = emptyCart;
$(document).ready(function(){
	var cupon_layer = 0;
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
	opt.default1 = {
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

  	var optDateTime = $.extend(opt['datetime'], opt['default1']);
  	var optTime = $.extend(opt['time'], opt['default1']);
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
	<?php elseif(in_array($this->type, array(2,7,8))):?>
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
	<?php endif;?>
	
	$('.location').click(function(){
		location.href = '<?php echo $this->createUrl('/user/setAddress',array('companyId'=>$this->companyId,'type'=>$this->type,'url'=>urlencode($this->createUrl('/mall/checkOrder',array('companyId'=>$this->companyId,'type'=>$this->type)))));?>';
	});
	$('button').click(function(){
		var typeId = $(this).attr('type_id');
		$('button').addClass('bttn_grey');
		$(this).removeClass('bttn_grey');
		$(this).addClass('bttn_orange');
		$('input[name="takeout_typeid"]').val(typeId);
	});
	$('.paytype .item').click(function(){
		var paytype = $(this).attr('paytype');
		$('.paytype .item').removeClass('on');
		
		$('input[name="paytype"]').val(paytype);
		$(this).addClass('on');
	});
	function hideActionSheet(weuiActionsheet, mask) {
        weuiActionsheet.removeClass('weui_actionsheet_toggle');
        mask.removeClass('weui_fade_toggle');
        weuiActionsheet.on('transitionend', function () {
            mask.hide();
        }).on('webkitTransitionEnd', function () {
            mask.hide();
        });
    }
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
   // 口味选择
  $('.taste-items .taste-item').click(function(){
	var sectionObj = $(this).parents('.section');
  	var tasteItems = $(this).parents('.taste-items');
  	var tasteDesc = sectionObj.find('.taste-desc');
  	var productId = tasteItems.attr('product-id');
  	var tasteId = $(this).attr('taste-id');
  	var group =  $(this).attr('group');
  	var tastePrice = $(this).attr('taste-pirce');
  	var tastName = $(this).html();
  	var allflage = $(this).attr('allflage');
  	var num = 1;
  	if(sectionObj.find('.num').length > 0){
  		num = sectionObj.find('.num').html();
  	}
  	
  	if($(this).hasClass('on')){
  	  	if(allflage=='0'){
  	  	  	return;
  	  	}
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
	// 套餐选择
  $('.detail-items .detail-item').click(function(){
	  if($(this).hasClass('has-taste')){
// 		 var _this = $(this);
// 		 var setId = _this.parents('.detail-items').attr('set-id');
// 		 var productId = _this.attr('product-id');
// 		 var str = _this.siblings('.taste-items[product-id="'+setId+'-'+productId+'"]').prop("outerHTML");
// 		 layer.open({
// 			    type: 1,
// 			    title: false,
// 			    shade: false,
// 			    closeBtn: 0,
// 			    area: ['100%','60%'],
// 			    content: str,
// 			    btn: '确定',
// 			    success: function(layero, index){
// 			        layero.find('.taste-items').show();
// 			    },
// 			    yes: function(index, layero){ 
// 		        	layer.close(index);
// 		   	}
// 		});
	  }else{
		 if(!$(this).hasClass('on')){
			var sectionObj = $(this).parents('.section');
		  	var tasteItems = $(this).parents('.detail-items');
		  	var detailDesc = sectionObj.children('.detail-desc');
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
	  }
    });
	$('.section').on('touchstart','.cart-delete',function(){ 
		var _this = $(this);
	  	var lid = _this.attr('lid');
	      
	    $.ajax({
	      	url:'<?php echo $this->createUrl('/mall/deleteCartItem',array('companyId'=>$this->companyId));?>',
	      	data:{lid:lid},
	      	success:function(msg){
	      		if(msg.status){
	      			_this.parents('.section').remove();
	      		}else{
	      			layer.msg(msg.msg);
	      		}
	      	},
	      	error:function(){
	      		layer.msg('移除失败,请检查网络');
	          },
	      	dataType:'json'
	     });
	});
  // 选择代金券
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
		<?php if(in_array($this->type, array(2,7,8))):?>
			var address = $('input[name="address"]').val();
			if(parseInt(address) < 0){
				layer.msg('请添加收货地址!');
				return;
			}
			if($('input[name="yue"]').is(':checked')){
				$('#dialog1').show();
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
			if($('input[name="yue"]').is(':checked')){
				$('#dialog1').show();
				return;
			}
			$('form').submit();
		<?php elseif($this->type==6):?>
			if($('input[name="yue"]').is(':checked')){
				$('#dialog1').show();
				return;
			}
			$('form').submit();
		<?php else:?>
			$('form').submit();
		<?php endif;?>
	});
	$('#dialog .primary').click(function(){
		$('#dialog').hide();
		$('form').submit();
	});
	$('#dialog .default').click(function(){
		$('#dialog').hide();
	});
	$('#dialog1 .primary').click(function(){
		$('#dialog1').hide();
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
