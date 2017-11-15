	<?php 
		$baseUrl = Yii::app()->baseUrl;
		$weixinServerAccount = WxAccount::get($this->companyId);
		$jsSdk = new WeixinJsSdk($weixinServerAccount['appid'],$weixinServerAccount['appsecret'],$this->companyId);
		$signPackage = $jsSdk->GetSignPackage();

	?>
		<style>
			.back-color{background-color: #F0F0E1;}
			.left{float:left;}
			.right{float:right;}
			.padding-right{padding-right:35px;}
			.padding-right1{padding-right:40px;}
			.nav-on{margin-bottom: 50px;}
			.padding{padding:5px;}
			.font-small{font-size: 12px;}
			.color-l-gray{color:#323232;}
			.img-show{width: 98px;height:98px;margin-left: -14px;margin-right: 10px;}
			.banma{border-bottom:3px dashed red;}
			.big-ul{margin-top:2px!important;}
			.margin-b{margin:0;margin-bottom: 65px;}
			#suretopay{margin:0;height:50px;top:0;border-radius: 0;}
		</style>
		<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
		<script>
		wx.config({
		    debug: false,
		    appId: '<?php echo $signPackage["appId"];?>',
		    timestamp: <?php echo $signPackage["timestamp"];?>,
		    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
		    signature: '<?php echo $signPackage["signature"];?>',
		    jsApiList: [
		      // 所有要调用的 API 都要加到这个列表中
		      'onMenuShareTimeline',
		      'onMenuShareAppMessage',
		      'getLocation',
		      'openLocation',
		      'showMenuItems'
		    ]
		});
		</script>
		<?php
			$baseUrl = Yii::app()->baseUrl;
			$company = WxCompany::get($this->companyId);
			$this->setPageTitle('支付订单');

			$payPrice = number_format($reality_total,2); // 最终支付价格



			$orderId = $golid['account_no'].'-'.$this->companyId;

			$canpWxpay = true;
			$compaychannel = WxCompany::getpaychannel($this->companyId);
			$payChannel = $compaychannel?$compaychannel['pay_channel']:0;
			if($payChannel==1){
				// Helper::writeLog('ZHH:payChannel=1');
				$notifyUrl = 'http://'.$_SERVER['HTTP_HOST'].$this->createUrl('/weixin/notify');
				$returnUrl = 'http://'.$_SERVER['HTTP_HOST'].$this->createUrl('myinfo/index',array('companyId'=>$this->companyId));
			// p($returnUrl);
				//①、获取用户openid
				try{
						$tools = new JsApiPay();
						$account = WxAccount::get($companyId);
					 	$baseInfo = new WxUserBase($account['appid'],$account['appsecret']);
					 	$userInfo = $baseInfo->getSnsapiBase();
					 	$openId = $userInfo['openid'];
						//②、统一下单
						$input = new WxPayUnifiedOrder();
						$input->SetBody($company['company_name']."-商铺原料订单");
						$input->SetAttach("3");
						$input->SetOut_trade_no($orderId);
						$input->SetTotal_fee($payPrice*100);
						$input->SetTime_start(date("YmdHis"));
						$input->SetTime_expire(date("YmdHis", time() + 600));
						$input->SetGoods_tag($company['company_name']."-商铺原料订单");
						$input->SetNotify_url($notifyUrl);
						$input->SetTrade_type("JSAPI");
						if($account['multi_customer_service_status'] == 1){
							$input->SetSubOpenid($openId);
						}else{
							$input->SetOpenid($openId);
						}
						$orderInfo = WxPayApi::unifiedOrder($input);

						$jsApiParameters = $tools->GetJsApiParameters($orderInfo);

				}catch(Exception $e){
					$canpWxpay = false;
					$jsApiParameters = $e->getMessage();
				}
			}//elseif($payChannel==2){
				// Helper::writeLog('ZHH:payChannel=2');
			// 	$notifyUrl = 'http://'.$_SERVER['HTTP_HOST'].$this->createUrl('/sqbpay/wappayresult');
			// 	$returnUrl = 'http://'.$_SERVER['HTTP_HOST'].$this->createUrl('/sqbpay/wappayreturn');
			// 	$reflect = json_encode(array('companyId'=>$this->companyId,'dpid'=>$this->companyId));
			// 	$data = array(
			// 			'companyId'=>$this->companyId,
			// 			'dpid'=>$this->companyId,
			// 			'client_sn'=>$orderId,
			// 			'total_amount'=>$payPrice,
			// 			'subject'=>$company['company_name']."-商铺原料订单",
			// 			'payway'=>3,
			// 			'operator'=>$user['nickname'],
			// 			'reflect'=>$reflect,
			// 			'notify_url'=>$notifyUrl,
			// 			'return_url'=>$returnUrl,
			// 	);
			// 	$sqbpayUrl = $this->createUrl('/mall/sqbPayOrder',$data);
			// }else{
			// 	$jsApiParameters = '';
			// }
		?>

		<header class="mui-bar mui-bar-nav mui-hbar">
		    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" style="color:white;"></a>
		    <h1 class="mui-title" style="color:white;">确认订单</h1>
		</header>
		<div class="mui-content">
			<div class="mui-row back-color padding banma" style="">
		    	<div class="mui-col-xs-1" style="height:63px;">
		    		<span class="mui-icon mui-icon-location" style="margin-top:20px;color:red;font-weight:900;"></span>
		    	</div>
		    	<div class="mui-col-xs-11 ">
		    	<?php if ($address):?>
		    		<a href="<?php echo $this->createUrl('address/addresslist',array('companyId'=>$this->companyId,'account_no'=>$account_no));?>" class="mui-navigate-right">
						<div class="mui-row back-color">
							<span class="left color-l-gray">收货人:<?php echo $address['name'];?></span>
							<span class="right padding-right1 color-l-gray"><?php echo $address['mobile'];?></span>
						</div>
						<div class="mui-row back-color ">
							<span class="left font-small color-l-gray" style="height: 23px;line-height: 23px;">收货地址 : </span>
							<span class=" font-small mui-ellipsis-2 padding-right"><?php echo $address['pcc'].' '.$address['street'];?></a>
						</div>
					</a>
				<?php endif;?>
		    	</div>
		    </div>
		    <ul class="mui-table-view big-ul">
		    	<?php if ($materials): ?>
		    	<?php foreach ($materials as $key => $products): ?>
			    <li class="mui-table-view-cell big-li">
			    	<div class="mui-row" style="height: 30px;">
				    	<span class="mui-navigate-right a-store"><?php echo $products[0]['company_name']; ?></span>
			    	</div>
			        <ul class="mui-table-view" >
			        	<?php foreach ($products as $product):?>
					    <li class="mui-row mui-table-view-cell mui-media">
				    		<div>
					            <img class=" mui-pull-left img-show" src="<?php echo  'http://menu.wymenu.com/'.$product['main_picture']; ?>" >
					            <div class="mui-media-body" >
					                <span><?php echo $product['goods_name']; ?></span>
					                <p class='mui-ellipsis'><?php echo $product['description']?$product['description']:'操作员偷懒,没有描述'; ?></p>
					                <span>单价 : <span style="color: red;"><?php echo $product['price']; ?></span>元</span>
					                <span style="color:darkslategray;">共</span>
					                <span style="color:red;"><?php echo $product['num']; ?></span>
					                <span style="color:darkslategray;"><?php echo $product['goods_unit']; ?></span>
					                <div>
					                	<span >合计 : </span>
					                	<span style="color:red;"><?php echo $product['num']*$product['price']; ?></span>
					                	<span style="color:darkslategray;">元</span>
					                </div>
					            </div>
					        </div>
					    </li>
						<?php endforeach; ?>
					</ul>
			    </li>
				<?php endforeach; ?>
				<?php else: ?>
				    <li class="mui-table-view-cell big-li">
				    	<div class="mui-row" >
					    	<div class="mui-col-xs-12 " style="height: 80px;line-height: 80px;text-align: center;">
					    		<a class="a-store" >您的订单是空的 ! ! !</a>
					    	</div>
				    	</div>
				    </li>
				<?php endif; ?>
			</ul>
			<h5 class="mui-content-padded">支付方式</h5>
			<div class="mui-card margin-b">
				<form class="mui-input-group">
					<?php if ($company_property['material_pay_type']==1): ?>
					<div class="mui-input-row mui-radio">
						<label for="daofu"> 货到付款</label>
						<input name="pay-style" type="radio" value="1" id="daofu">
					</div>
					<?php endif; ?>

					<div class="mui-input-row mui-radio">
						<label for="wxpay"> 微信支付</label>
						<input name="pay-style" type="radio" value="0" id="wxpay" checked >
					</div>
				</form>

			</div>
	    </div>
	    <nav class="mui-bar mui-bar-tab nav-on" id="gopay" >
	        <div class="mui-tab-item " style="width:10%;">
	        </div>
	        <div class="mui-tab-item " style="width:55%;color:gray;">
	            	实付款 : ￥<span style="color: red;margin-right: 10px;padding-right: 10px;"><?php echo $reality_total; ?></span>
	        </div>
	        <div class="mui-tab-item " style="width:35%;">
	            <button type="button" class="mui-btn mui-btn-red mui-btn-block" id="suretopay">立即下单</button>
	        </div>
	    </nav>

		<script type="text/javascript">
			mui.init();
			//状态提示
			var status = '<?php echo $success; ?>';
			if (status == '1') {
				mui.toast('修改地址成功');
			}else if(status == '2'){
				mui.toast('修改地址失败');
			}else if(status == '3'){
				mui.toast('订单选择有问题 , 修改地址失败');
			}

			//调用微信JS api 支付
			function jsApiCall()
			{
				<?php if ($payChannel==1):?>
				<?php if($canpWxpay):?>
				WeixinJSBridge.invoke(
					'getBrandWCPayRequest',
					<?php echo $jsApiParameters; ?>,
					function(res){
						// console.log(res);
						 if(res.err_msg == "get_brand_wcpay_request:ok" ) {
						 	// 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。
						 	mui.alert('支付成功!');
						 	location.href = '<?php echo $returnUrl;?>';
						 }else{
						 	//支付失败或取消支付
							 mui.alert('支付失败,请重新支付!');
						 }
					}
				);
				<?php endif;?>
				<?php elseif($payChannel==2):?>
				location.href = '<?php echo $sqbpayUrl;?>';
				<?php else:?>
				mui.alert('无支付信息,请联系客服!');
				<?php endif;?>
			}
			function callpay()
			{
				<?php if(!$canpWxpay):?>
				mui.alert('<?php echo $jsApiParameters;?>');
				return;
				<?php endif;?>
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
				// window.event.returnValue = false;
			}

		    $('#suretopay').on('tap',function(){
		    	var pay_style = $('input[name="pay-style"]:checked').val();
		    	if (pay_style) {
		    		//货到付款  1
		    		location.href='<?php echo $this->createUrl('ymallcart/editgoodsorder',array('companyId'=>$this->companyId,'account_no'=>$account_no)) ?>/daofu/'+pay_style;
		    	}else{
		    		//微信支付
		    		callpay();
		    	}
		    })
		</script>
