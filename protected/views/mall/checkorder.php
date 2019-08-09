<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('确认订单');
	$fval = '0-0-0';// 满类型-满lid-送lid
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
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/cart.css?_t=2018121001">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/order.css">

<script src="<?php echo $baseUrl;?>/js/mall/date/mobiscroll_002.js" type="text/javascript"></script>
<script src="<?php echo $baseUrl;?>/js/mall/date/mobiscroll_004.js" type="text/javascript"></script>
<link href="<?php echo $baseUrl;?>/css/mall/date/mobiscroll_002.css" rel="stylesheet" type="text/css">
<link href="<?php echo $baseUrl;?>/css/mall/date/mobiscroll.css" rel="stylesheet" type="text/css">
<script src="<?php echo $baseUrl;?>/js/mall/date/mobiscroll.js" type="text/javascript"></script>
<script src="<?php echo $baseUrl;?>/js/mall/date/mobiscroll_003.js" type="text/javascript"></script>
<script src="<?php echo $baseUrl;?>/js/mall/date/mobiscroll_005.js" type="text/javascript"></script>
<link href="<?php echo $baseUrl;?>/css/mall/date/mobiscroll_003.css" rel="stylesheet" type="text/css">
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
	<div class="order-time arrowright" style="margin:10px 0;">
		<div class="time-lt">送达时间</div>
		<div class="time-rt">
			<select name="order_time">
                 <option selected="selected" value="0">立即送达</option>
                 <option value="60">1小时</option>
                 <option value="90">1个半小时</option>
                 <option value="120">2个小时</option>
                 <option value="150">2个半小时</option>
                 <option value="180">3个小时</option>
            </select>
		</div>
		<div class="clear"></div>
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
	<div class="order-site"><div class="lt">取餐方式<span class="weui-badge">可选</span></div><div class="rt"><button type="button" class="specialbttn bttn_orange" type_id="0" style="margin-right:20px;">堂食</button><button  type="button" class="specialbttn bttn_grey" type_id="1">打包</button></div><div class="clear"></div></div>
	<div class="order-time arrowright" style="margin:10px 0;">
		<div class="time-lt">取餐时间<span class="weui-badge">可选</span></div>
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
	    		<?php 
	    			foreach($groups['tastes'] as $taste):
	    			$taste['price'] = number_format($taste['price']*$levelDiscount,2);
	    		?>
	    			<div class="item t-item taste-item"  group="<?php echo $k;?>" taste-id="<?php echo $taste['lid'];?>" allflage="<?php echo $taste['allflae'];?>" taste-pirce="<?php echo $taste['price'];?>" taste-name="<?php echo $taste['name'];?>"><?php echo $taste['name'];?><?php if($taste['price']>0):?>(<span class="taste-pice"><?php echo $taste['price'];?></span>)<?php endif;?></div>
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
	    				if(in_array($item['product_id'], $detailArr)){
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
				$memdisprice += $fminusprice;
				$fval = '1-'.$fullsent['lid'].'-0';
		?>
		<div class="order-copun arrowright fullminus">
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
<?php endif;?>
<div class="order-remark">
	<textarea name="taste_memo" placeholder="请输入备注内容(可不填)"></textarea>
</div>
<?php if($this->type!=1):?>
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
<?php else:?>
<footer>
    <div class="ft-lt">
        <p style="margin-left:10px;">总计 ￥<span id="total" class="total" total="<?php echo $price;?>"><?php echo $price;?></span></p>
    </div>
    <div class="ft-rt" id="payorder">
    	<a href="javascript:;">
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
<div class="user-cupon" id="minusList">
	<div class="cupon-container">
		<?php if(!empty($fullsent)&&$fullsent['full_type']==1):?>
		<div class="item useMinus" sent-type="1" sent-id="<?php echo $fullsent['lid'];?>" minus-price="<?php echo $fullsent['full_cost'];?>" minus-price="<?php echo $fullsent['extra_cost'];?>">
			<div class="item-top">
				<div class="item-top-left"><?php echo $fullsent['title'];?></div>
				<div class="item-top-right"><span style="font-size:15px;color:gray;"><?php echo '满'.$fullsent['full_cost'].'减'.$fullsent['extra_cost'];?></span></div>
				<div class="clear"></div>
			</div>
		</div>
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
	var setTotal = $('#total').attr('original');
	var totalFee = parseFloat(setTotal) + parseFloat(price);
	
	if(totalFee > 0){
		totalFee =  totalFee.toFixed(2);
	}else{
		totalFee = '0.00';
	}
	$('#total').attr('total',totalFee);
	$('#total').html(totalFee);
}
window.onload = emptyCart;
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
	function hideActionSheet(weuiActionsheet, mask) {
        weuiActionsheet.removeClass('weui_actionsheet_toggle');
        mask.removeClass('weui_fade_toggle');
        weuiActionsheet.on('transitionend', function () {
            mask.hide();
        }).on('webkitTransitionEnd', function () {
            mask.hide();
        });
    }
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
		var cuponNum = $(this).attr('cupon-num');
		var userCuponId = $(this).attr('user-cupon-id');
		var total = $('#total').attr('original');
		var memdis = $('#total').attr('memdiscount');
		var yue = parseFloat($('#yue').attr('yue'));
		var money = 0;

		if($('.userdiscount').length > 0){
			$('.userdiscount').hide();
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
			layer.load(2);
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
			layer.load(2);
			$('form').submit();
		<?php elseif($this->type==6):?>
			if($('input[name="yue"]').is(':checked')){
				$('#dialog1').show();
				return;
			}
			layer.load(2);
			$('form').submit();
		<?php else:?>
			layer.load(2);
			$('form').submit();
		<?php endif;?>
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
