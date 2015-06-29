<?php 
	$baseUrl = Yii::app()->baseUrl;
	Yii::app()->clientScript->registerCssFile($baseUrl.'/css/cartlist.css');
	$this->setPageTitle('支付');
	$orderProductListPay = array();
	$orderProductListPay = OrderList::WxPayOrderList($dpid,$orderId,1,0,1);
	$pricePay = OrderList::WxPayOrderPrice($orderProductListPay);
	$pricePayArr = explode(':',$pricePay);
	$orderPricePay = $pricePayArr[0];
	$orderPayNum = $pricePayArr[1];

	$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	//①、获取用户openid
	$tools = new JsApiPay();
	$openId = $tools->GetOpenid($url);
	//②、统一下单
	$input = new WxPayUnifiedOrder();
	$input->SetBody("test");
	$input->SetAttach("test");
	$input->SetOut_trade_no($orderId);
	$input->SetTotal_fee($orderPricePay*100);
	$input->SetTime_start(date("YmdHis"));
	$input->SetTime_expire(date("YmdHis", time() + 600));
	$input->SetGoods_tag("test");
	$input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
	$input->SetTrade_type("JSAPI");
	$input->SetOpenid($openId);
	$orderInfo = WxPayApi::unifiedOrder($input);
	
	$jsApiParameters = $tools->GetJsApiParameters($orderInfo);
?>
<?php if($orderProductListPay):?>
	<div style="color:#555da8;">
	<div class="order-top"><div class="order-top-left">下单总额 :<span> <?php echo Money::priceFormat($orderPricePay);?></span></div><div class="order-top-right"><button class="online-pay" onclick="callpay()">微信支付</button></div></div>
	<div class="order-time"><div class="order-time-left"><?php echo date('Y-m-d H:i:s',time());?></div></div>
	<?php foreach($orderProductListPay as $key=>$orderProduct):?>
		<!--非套餐-->
		<?php if($key):?>
		<div class="order-category"><?php echo OrderList::GetCatoryName($key,$dpid);?></div>
	   <?php foreach($orderProduct as $order):?>
		<div class="order-product">
			<div class="order-product-left"><img src="<?php echo $order['main_picture'];?>" /></div>
			<div class="order-product-right">
				<div class="right-up"><?php echo $order['product_name'];?></div>
				<div class="right-middle">
		                    <div class="right-down-left">￥<?php echo $order['price'];?>/例 X <font color="#ff8c00"><?php echo $order['amount'];?>例</font></div>
				</div>
				<div class="right-down">
				<font color="#ff8c00">口味要求</font>:<?php $productTasteIds = TasteClass::getOrderTaste($order['lid'],2,$dpid);if($productTasteIds){ foreach($productTasteIds as $id){ echo TasteClass::getTasteName($id).' ';}}?> 备注:<?php echo TasteClass::getOrderTasteMemo($order['lid'],2,$dpid);?>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<?php if(!empty($order['addition'])):?>
		<?php foreach($order['addition'] as $order):?>
		<div class="order-product">
			<div class="order-product-left"><img src="<?php echo $order['main_picture'];?>" /></div>
			<div class="order-product-right">
				<div class="right-up"><?php echo $order['product_name'];?>(加菜)</div>
				<div class="right-middle">
		                    <div class="right-down-left">￥<?php echo $order['price'];?>/例 X <font color="#ff8c00"><?php echo $order['amount'];?>例</font></div>
				</div>
				<div class="right-down">
				<font color="#ff8c00">口味要求</font>:<?php $productTasteIds = TasteClass::getOrderTaste($order['lid'],2,$dpid);if($productTasteIds){ foreach($productTasteIds as $id){ echo TasteClass::getTasteName($id).' ';}}?> 备注:<?php echo TasteClass::getOrderTasteMemo($order['lid'],2,$dpid);?>
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
			<div class="order-category"><?php echo ProductSetClass::GetProductSetName($dpid,$key);?></div>
			<?php foreach($productSet as $order):?>
				<div class="order-product">
				<div class="order-product-left"><img src="<?php echo $order['main_picture'];?>" /></div>
				<div class="order-product-right">
				<div class="right-up"><?php echo $order['original_price'];?></div>
				<div class="right-middle">
		                    <div class="right-down-left">￥<?php echo $order['price'];?>/例 X <font color="#ff8c00"><?php echo $order['amount'];?>例</font></div>
				</div>
				<div class="right-down">
				<font color="#ff8c00">口味要求</font>:<?php $productTasteIds = TasteClass::getOrderTaste($order['lid'],2,$dpid);if($productTasteIds){ foreach($productTasteIds as $id){ echo TasteClass::getTasteName($id).' ';}}?> 备注:<?php echo TasteClass::getOrderTasteMemo($order['lid'],2,$dpid);?>
				</div>
			</div>
			<div class="clear"></div>
		</div>
			<?php endforeach;?>
		<?php endforeach;?>
		<?php endif;?>
	<?php endforeach;?>
	</div>
	<script type="text/javascript">
	//调用微信JS api 支付
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo $jsApiParameters; ?>,
			function(res){
				alert(res.err_msg);
				 if(res.err_msg == "get_brand_wcpay_request:ok" ) {
				 	// 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。 
				 	
				 }else{
				 	//支付失败或取消支付
				 	
				 }     
			}
		);
	}

	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}
</script>
<?php endif;?>
	