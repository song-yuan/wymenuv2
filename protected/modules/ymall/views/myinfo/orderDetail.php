
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
			.margin-b{margin:0;margin-bottom: 120px!important;}
			#suretopay{margin:0;height:50px;top:0;border-radius: 0;}
		</style>


		<header class="mui-bar mui-bar-nav mui-hbar">
		    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" style="color:white;"></a>
		    <h1 class="mui-title" style="color:white;">订单详情</h1>
		</header>
		<div class="mui-content mui-scroll-wrapper">
			<div class="mui-scroll">
				<div class="mui-row back-color padding banma" style="">
			    	<div class="mui-col-xs-1" style="height:50px;">
			    		<span class="mui-icon mui-icon-location" style="margin-top:5px;color:red;font-weight:900;"></span>
			    	</div>
			    	<div class="mui-col-xs-11 ">
			    	<?php if ($goods_orders):?>
			    		<span class="mui-navigate-right">
							<div class="mui-row back-color">
								<span class="left color-l-gray">收货人:<?php echo $goods_orders[0]['ganame'];?></span>
								<span class="right padding-right1 color-l-gray"><?php echo $goods_orders[0]['amobile'];?></span>
							</div>
							<div class="mui-row back-color ">
								<span class="left font-small color-l-gray" style="height: 23px;line-height: 23px;">收货地址 : </span>
								<span class=" font-small mui-ellipsis-2 padding-right"><?php echo $goods_orders[0]['pcc'].' '.$goods_orders[0]['street'];?></a>
							</div>
						</span>
					<?php endif;?>
			    	</div>
			    </div>
			    	<?php if ($goods_orders): ?>
					<div class="mui-card" style="margin:0;">
						<div class="mui-card-header mui-card-media">
							<img src="<?php echo  Yii::app()->request->baseUrl; ?>/img/order_list.png" />
							<div class="mui-media-body">
								订单号:<?php echo $goods_orders[0]['account_no'];?>
								<p>下单日期: <?php echo $goods_orders[0]['create_at'];?></p>
							</div>
						</div>
						<div class="mui-card-content" >
						<ul class="mui-table-view">
							<?php foreach ($goods_orders as $key => $value):?>
							<li class="mui-table-view-cell mui-media">
								
								<img class="mui-media-object mui-pull-left" src="<?php if($value['main_picture']){ echo $value['main_picture'];}else{ echo 'http://menu.wymenu.com/wymenuv2/img/product_default.png';}?>">
								<div class="mui-media-body">
								<?php echo $value['goods_name'];?>
								<p class='mui-ellipsis'>
									<span class="mui-pull-left">单价 : ¥<?php echo $value['price'];?></span>
									<span class="mui-pull-right">x <?php echo $value['num'];?> </span>
								</p>
								<p class='mui-ellipsis'>
									<?php if($value['invoice_accountno']): ?>
									<span class="mui-pull-left">配送单号 : <?php echo $value['invoice_accountno'];?> </span>
										<?php if($value['istatus']==2): ?>
											<span class="mui-pull-right" style="color:green;"><?php echo '已签收';?> </span>
										<?php elseif($value['istatus']==1): ?>
											<span class="mui-pull-right" style="color:red;"><?php echo '运输中';?> </span>
										<?php elseif($value['istatus']==0): ?>
											<span class="mui-pull-right" style="color:red;"><?php echo '备货中';?> </span>
										<?php endif; ?>
									<?php else: ?>
									<span class="mui-pull-left">仓库 : <?php echo $value['company_name'];?></span><br>
									<?php endif; ?>
								</p>
								</div>
							</li>
							<?php endforeach; ?>

						</ul>
						</div>
						<div class="mui-card-footer">
							<a class="mui-card-link">合计 : ¥ <?php echo $goods_orders[0]['reality_total']; ?></a>
							<a class="mui-card-link"></a>
						</div>
					</div>
					<?php endif; ?>


			</div>
	    </div>

		<script type="text/javascript">
			mui.init();
			mui('.mui-scroll-wrapper').scroll();
		</script>
