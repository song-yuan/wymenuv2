
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
			.big-ul{margin-bottom: 100px;margin-top:2px!important;}
		</style>

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
			        <ul class="mui-table-view" id="a1">
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
	    </div>
	    <nav class="mui-bar mui-bar-tab nav-on" id="gopay" >
	        <div class="mui-tab-item " style="width:10%;">
	        </div>
	        <div class="mui-tab-item " style="width:55%;color:gray;">
	            	实付款 : ￥<span style="color: red;margin-right: 10px;padding-right: 10px;"><?php echo $reality_total; ?></span>
	        </div>
	        <div class="mui-tab-item " style="width:35%;">
	            <button type="button" class="mui-btn mui-btn-red mui-btn-block" style="margin:0;height:50px;top:0;border-radius: 0;" id="suretopay">立即支付</button>
	        </div>
	    </nav>

		<script type="text/javascript">
			mui.init();
			var button = document.getElementById('suretopay');
			button.addEventListener('tap',function(){
				alert('111');
		    });

			//状态提示
			var status = '<?php echo $success; ?>';
			if (status == '1') {
				mui.toast('修改地址成功');
			}else if(status == '2'){
				mui.toast('修改地址失败');
			}else if(status == '3'){
				mui.toast('订单选择有问题 , 修改地址失败');
			}
		</script>
