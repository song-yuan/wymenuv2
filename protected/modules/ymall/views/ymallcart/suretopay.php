
		<style>
			.back-color{background-color: #F0F0E1;}
			.left{float:left;}
			.right{float:right;}
			.padding-right{padding-right:35px;}
			.padding-right1{padding-right:40px;}
			.nav-on{margin-bottom: 50px;}
			.padding{padding:5px;}
			.font-small{font-size: 14px;}
			.color-l-gray{color:#323232;}
			.img-show{width: 78px;height:78px;margin-left: -14px;margin-right: 10px;border-radius: 10px;}
			.banma{border-bottom:3px dashed red;padding-bottom: 0;}
			.big-ul{margin-top:2px!important;}
			.margin-b{margin:0;margin-bottom: 120px!important;}
			#suretopay{margin:0;height:50px;top:0;border-radius: 0;}
			.mui-toast-container{bottom: 50%!important;}
		</style>


		<header class="mui-bar mui-bar-nav mui-hbar">
		    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" style="color:white;"></a>
		    <h1 class="mui-title" style="color:white;">确认订单</h1>
		</header>
		<div class="mui-content mui-scroll-wrapper">
			<div class="mui-scroll">
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
						    <li class="mui-row mui-table-view-cell mui-media" style="padding-bottom: 0;">
					    		<div>
						            <img class=" mui-pull-left img-show" src="<?php if($product['main_picture']){ echo $product['main_picture'];}else{ echo 'http://menu.wymenu.com/wymenuv2/img/product_default.png';} ?>" >
						            <div class="mui-media-body" style="font-size: 14px;">
						                <span><?php echo $product['goods_name']; ?></span>
						                <p class='mui-ellipsis'><?php echo $product['unit_name']; ?></p>
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
				<h5 class="mui-content-padded">支付途径</h5>
				<div class="mui-card ">
					<form class="mui-input-group">
						<?php if ($company_property['stock_paytype']==2): ?>
						<div class="mui-input-row mui-radio">
							<label for="daofu" id="daof"> 现金支付</label>
							<input name="pay-style" type="radio" value="1" id="daofu" checked >
						</div>
						<div class="mui-input-row mui-radio">
							<label for="daofu1" id="daof1"> 微信支付</label>
							<input name="pay-style" type="radio" value="1" id="daofu1">
						</div>
						<div class="mui-input-row mui-radio">
							<label for="daofu2" id="daof2"> 支付宝支付</label>
							<input name="pay-style" type="radio" value="1" id="daofu2">
						</div>
						<div class="mui-input-row mui-radio">
							<label for="daofu3" id="daof3"> 银行卡支付</label>
							<input name="pay-style" type="radio" value="1" id="daofu3">
						</div>
						<div class="mui-input-row mui-radio">
							<label for="other" id="other1"> 其他支付</label>
							<input name="pay-style" type="radio" value="1" id="other">
						</div>
						<div class="mui-input-row" id="other2" style="display: none;">
							<input name="pay-style" type="text" style="font-size: 14px;" placeholder="请输入支付方式" disabled>
						</div>
						<?php elseif ($company_property['stock_paytype']==1): ?>
						<div class="mui-input-row mui-radio">
							<label for="wxpay"> 微信支付</label>
							<input name="pay-style" type="radio" value="0" id="wxpay" checked >
						</div>
						<?php endif;?>
					</form>
				</div>
				<h5 class="mui-content-padded">订单备注</h5>
				<div class="mui-card margin-b">
				<form class="mui-input-group">
					<div class="mui-input-row" style="margin: 10px 5px;">
						<textarea id="textarea" style="font-size: 14px;" rows="2" placeholder="输入备注信息"></textarea>
					</div>
				</form>
				</div>
			</div>
	    </div>
	    <nav class="mui-bar mui-bar-tab nav-on" id="gopay" >
	        <div class="mui-tab-item " style="width:10%;">
	        </div>
	        <div class="mui-tab-item mui-disabled" style="width:55%;color:gray;" >
	            	实付款 : ￥<span style="color: red;margin-right: 10px;padding-right: 10px;"><?php echo $reality_total; ?></span>
	        </div>
	        <div class="mui-tab-item " style="width:35%;">
	            <button type="button" class="mui-btn mui-btn-red mui-btn-block" id="suretopay">立即下单</button>
	        </div>
	    </nav>

		<script type="text/javascript">
			mui.init();
			mui('.mui-scroll-wrapper').scroll();

		    $('#suretopay').on('tap',function(){
		    	var pay_style = $('input[name="pay-style"]:checked').val();
		    	var textarea = $('#textarea').val();
		    	var pay_way = $('input[name="pay-style"]:checked').prev('label').text();
		    	console.log(pay_style);
		    	console.log(pay_way);
		    	if (pay_way == ' 其他支付') {
		    		var pay_way1 = $('input[name="pay-style"]:checked').parent().next().children().val();
			    	if(pay_way1!=''){
			    		pay_way=pay_way1;
			    	}
		    	}
		    	if (pay_style==1) {
		    		//线下支付  1
		    		// alert(pay_style);
		    		location.href='<?php echo $this->createUrl('ymallcart/editgoodsorder',array('companyId'=>$this->companyId,'account_no'=>$account_no)) ?>/daofu/'+pay_style+'/pay_way/'+pay_way+'/textarea/'+textarea;
		    	}else{
		    		// alert(pay_style);
		    		//微信支付
					var account_no ='<?php echo $account_no; ?>';
					var companyId ='<?php echo $this->companyId; ?>';
					location.href = '<?php echo $this->createUrl("ymallcart/wxPay") ?>?companyId='+companyId+'&account_no='+account_no;
		    	}
		    });

		    $('#other,#other1').on('tap',function(){
		    	$('#other2').css('display','block');
		    	$('#other2').children().removeAttr('disabled');
		    });

		    $('#daofu,#daofu1,#daofu2,#daofu3,#daof,#daof1,#daof2,#daof3').on('tap',function(){
		    	$('#other2').css('display','none');
		    	$('#other2').children().attr('disabled','true');
		    });



		    <?php if ($success==3): ?>
		    	mui.toast('商户没有开通微信商户的支付功能,请选择到付!!!',{ duration:'long', type:'div' });
		    <?php endif; ?>
		</script>
