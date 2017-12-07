
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
	.gotopay{padding: 2px 12px;float:right;margin-left:20px;margin-right:10px;}
	.delete_nopay{padding: 2px 12px;float:right;}
	.mcard{margin-top:0px;margin-right:0px;margin-left: 0px;margin-bottom: 60px;background-color: #F2F2F2;}
	.card-head{background-color: #DDFFDD;}
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
			<div class="mui-card mcard">
				<div class="mui-card-header mui-card-media">
					<img src="<?php echo  Yii::app()->request->baseUrl; ?>/img/order_list.png" />
					<div class="mui-media-body">
						订单号:<?php echo $goods_orders[0]['account_no'];?>
						<p>下单日期: <?php echo $goods_orders[0]['create_at'];?></p>
					</div>
				</div>
				<div class="mui-card-content" >
				<?php if ($type==0 )://全部  ?>

					<?php if($goods_orders[0]['paytype']==1 && $goods_orders[0]['pay_status']==0)://待付款 ?>
						<ul class="mui-table-view">
							<?php foreach ($goods_orders as $key => $value):?>
							<li class="mui-table-view-cell mui-media">
								<img class="mui-media-object mui-pull-left" src="<?php if($value['main_picture']){ echo $value['main_picture'];}else{ echo 'http://menu.wymenu.com/wymenuv2/img/product_default.png';}?>">
								<div class="mui-media-body">
								<?php echo $value['goods_name'];?>
								<p class='mui-ellipsis'>
									<span class="mui-pull-left">单价 : ¥<?php echo $value['price'];?></span>
									<span class="mui-pull-right">x <?php echo $value['num'];?> / <?php echo $value['goods_unit'];?></span>
								</p>
								<p class='mui-ellipsis'>
								<?php if($value['paytype']==2 || ($value['paytype']==1 && $value['pay_status']==1)): ?>
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
									<span class="mui-pull-left">仓库 : <?php echo $value['company_name'];?></span>
									<span class="mui-pull-right" style="color:red;"><?php echo '备货中';?></span>
									<?php endif; ?>
								<?php endif; ?>
								</p>
								</div>
							</li>
							<?php endforeach; ?>
						</ul>
					<?php elseif((($goods_orders[0]['paytype']==2 && $goods_orders[0]['pay_status']==0) || $goods_orders[0]['pay_status']==1) && $goods_orders[0]['order_status']<5)://待发货 ?>
						<?php
							$nosents =array();
							foreach ($goods_orders as $key1 => $goods_order) {
								if(!isset($nosents[$goods_order['stock_dpid']])){
									$nosents[$goods_order['stock_dpid']] = array();
								}
								array_push($nosents[$goods_order['stock_dpid']], $goods_order);
							}
							foreach ($nosents as $key2 => $nosent):
						?>
						<div class="mui-card">
							<div class="mui-card-header mui-card-media card-head">
								<img src="<?php echo  Yii::app()->request->baseUrl; ?>/img/cangku.png" />
								<div class="mui-media-body">
									[ <span style="color:darkblue;">仓库</span> ]<?php echo $nosent[0]['company_name'] ?>
									<p>
									<?php if($nosent[0]['invoice_accountno']): ?>
									<span class="mui-pull-left">配送单号 : <?php echo $nosent[0]['invoice_accountno'];?> </span>
									<?php else: ?>
									<span class="mui-pull-left" style="color:red;"><?php echo '';?></span>
									<?php endif; ?>
									<br>
								</p>
								</div>
							</div>
							<div class="mui-card-content" >
								<ul class="mui-table-view">
									<?php foreach ($nosent as $key3 => $value):?>
									<li class="mui-table-view-cell mui-media">
										<img class="mui-media-object mui-pull-left" src="<?php if($value['main_picture']){ echo $value['main_picture'];}else{ echo 'http://menu.wymenu.com/wymenuv2/img/product_default.png';}?>">
										<div class="mui-media-body">
										<?php echo $value['goods_name'];?>
										<p class='mui-ellipsis'>
											<span class="mui-pull-left">单价 : ¥<?php echo $value['price'];?></span>
											<span class="mui-pull-right">x <?php echo $value['num'];?> / <?php echo $value['goods_unit'];?></span>
										</p>
										</div>
									</li>
									<?php endforeach; ?>
								</ul>
							</div>
							<div class="mui-card-footer">
								<?php if($value['invoice_accountno']): ?>
								<a class="mui-card-link">
								<span class="mui-pull-left">配送单号 : <?php echo $value['invoice_accountno'];?> </span>
									<?php if($value['istatus']==2): ?>
										<span class="mui-pull-right" style="color:green;"><?php echo '已签收';?> </span>
									<?php elseif($value['istatus']==1): ?>
										<span class="mui-pull-right" style="color:red;"><?php echo '运输中...';?> </span>
									<?php elseif($value['istatus']==0): ?>
										<span class="mui-pull-right" style="color:red;"><?php echo '备货中...';?> </span>
									<?php endif; ?>
								</a>
								<?php else: ?>
								<a class="mui-card-link"></a>
								<a class="mui-card-link">
								<span class="mui-pull-right" style="color:red;"><?php echo '备货中...';?></span>
								</a>
								<?php endif; ?>
							</div>
						</div>
						<?php endforeach; ?>
					<?php elseif($goods_orders[0]['order_status']==5 && ($goods_orders[0]['istatus']==0 || $goods_orders[0]['istatus']==1) && (($goods_orders[0]['order_type']==1 && $goods_orders[0]['pay_status']==1) || ($goods_orders[0]['paytype']==2 && $goods_orders[0]['pay_status']==0)))://待收货 ?>
						<?php
							$nogets =array();
							foreach ($goods_orders as $key1 => $goods_order) {
								if(!isset($nogets[$goods_order['invoice_accountno']])){
									$nogets[$goods_order['invoice_accountno']] = array();
								}
								array_push($nogets[$goods_order['invoice_accountno']], $goods_order);
							}
							foreach ($nogets as $key4 => $noget):
						?>
						<div class="mui-card">
							<div class="mui-card-header mui-card-media card-head">
								<img src="<?php echo  Yii::app()->request->baseUrl; ?>/img/cangku.png" />
								<div class="mui-media-body">
									[ <span style="color:darkblue;">仓库</span> ]<?php echo $noget[0]['company_name'] ?>
									<p>
									<?php if($noget[0]['invoice_accountno']): ?>
									<span class="mui-pull-left">出库单号 : <?php echo $noget[0]['invoice_accountno'];?> </span>
									<?php if($noget[0]['istatus']==2): ?>
										<span class="mui-pull-right" style="color:green;"><?php echo '已签收';?> </span>
									<?php elseif($noget[0]['istatus']==1): ?>
										<span class="mui-pull-right" style="color:red;"><?php echo '运输中...';?> </span>
									<?php elseif($noget[0]['istatus']==0): ?>
										<span class="mui-pull-right" style="color:red;"><?php echo '备货中...';?> </span>
									<?php endif; ?>
									<?php else: ?>
									<span class="mui-pull-left" style="color:red;">正在处理...<?php echo '';?></span>
									<?php endif; ?>
									<br>
								</p>
								</div>
							</div>
							<div class="mui-card-content" >
								<ul class="mui-table-view">
									<?php foreach ($noget as $key5 => $value):?>
									<li class="mui-table-view-cell mui-media">
										<img class="mui-media-object mui-pull-left" src="<?php if($value['main_picture']){ echo $value['main_picture'];}else{ echo 'http://menu.wymenu.com/wymenuv2/img/product_default.png';}?>">
										<div class="mui-media-body">
										<?php echo $value['goods_name'];?>
										<p class='mui-ellipsis'>
											<span class="mui-pull-left">单价 : ¥<?php echo $value['price'];?></span>
											<span class="mui-pull-right">x <?php echo $value['num'];?> / <?php echo $value['goods_unit'];?> </span>
										</p>
										</div>
									</li>
									<?php endforeach; ?>
								</ul>
							</div>
							<div class="mui-card-footer">
								<?php if($value['invoice_accountno']): ?>
									<?php if($value['sent_type']==3): ?>
										<a class="mui-card-link">
										<span class="mui-pull-right"><?php echo $value['sent_personnel'];?> </span>
										</a>
										<a class="mui-card-link" href="tel:<?php echo $value['mobile'];?>">物流单号 : 
										<span class="mui-pull-right" > <?php echo $value['mobile'];?> </span>
										</a>
									<?php else: ?>
										<a class="mui-card-link">配送员 : 
										<span class="mui-pull-right" style="color:black;"> <?php echo $value['sent_personnel'];?> </span>
										</a>
										<a class="mui-card-link" href="tel:<?php echo $value['mobile'];?>">手机号 : 
										<span class="mui-pull-right" > <?php echo $value['mobile'];?> </span>
										</a>
									<?php endif; ?>
									<?php if($value['istatus']==1): ?>
										<a class="mui-card-link">
										<button type="button" class="mui-btn mui-btn-danger mui-btn-outlined mui-sureo" style="padding: 2px 12px;float:right;" invoice_accountno="<?php echo $value['invoice_accountno']; ?>" account_no="<?php echo $value['account_no']; ?>">确认收货</button>
										</a>
									<?php elseif($value['istatus']==2): ?>
										<a class="mui-card-link">
										<button type="button" class="mui-btn mui-btn-danger mui-btn-outlined mui-sureo" style="padding: 2px 12px;float:right;" disabled invoice_accountno="<?php echo $value['invoice_accountno']; ?>" account_no="<?php echo $value['account_no']; ?>">已收货</button>
										</a>
									<?php endif; ?>
								<?php else: ?>
									<a class="mui-card-link"></a>
									<a class="mui-card-link">
										<span class="mui-pull-right" style="color:red;"><?php echo '备货中...';?></span>
									</a>
								<?php endif; ?>
							</div>
						</div>
						<?php endforeach; ?>
					<?php elseif($goods_orders[0]['order_status']==5 && $goods_orders[0]['istatus']==2  && (($goods_orders[0]['order_type']==1 && $goods_orders[0]['pay_status']==1) || ($goods_orders[0]['paytype']==2 && $goods_orders[0]['pay_status']==0)))://已签收 ?>
						<?php
							$nogets =array();
							foreach ($goods_orders as $key1 => $goods_order) {
								if(!isset($nogets[$goods_order['invoice_accountno']])){
									$nogets[$goods_order['invoice_accountno']] = array();
								}
								array_push($nogets[$goods_order['invoice_accountno']], $goods_order);
							}
							foreach ($nogets as $key4 => $noget):
						?>
						<div class="mui-card">
							<div class="mui-card-header mui-card-media card-head">
								<img src="<?php echo  Yii::app()->request->baseUrl; ?>/img/cangku.png" />
								<div class="mui-media-body">
									[ <span style="color:darkblue;">仓库</span> ]<?php echo $noget[0]['company_name'] ?>
									<p>
									<?php if($noget[0]['invoice_accountno']): ?>
									<span class="mui-pull-left">出库单号 : <?php echo $noget[0]['invoice_accountno'];?> </span>
									<?php if($noget[0]['istatus']==2): ?>
										<span class="mui-pull-right" style="color:green;"><?php echo '已签收';?> </span>
									<?php elseif($noget[0]['istatus']==1): ?>
										<span class="mui-pull-right" style="color:red;"><?php echo '运输中...';?> </span>
									<?php elseif($noget[0]['istatus']==0): ?>
										<span class="mui-pull-right" style="color:red;"><?php echo '备货中...';?> </span>
									<?php endif; ?>
									<?php else: ?>
									<span class="mui-pull-left" style="color:red;">正在处理...<?php echo '';?></span>
									<?php endif; ?>
									<br>
								</p>
								</div>
							</div>
							<div class="mui-card-content" >
								<ul class="mui-table-view">
									<?php foreach ($noget as $key5 => $value):?>
									<li class="mui-table-view-cell mui-media">
										<img class="mui-media-object mui-pull-left" src="<?php if($value['main_picture']){ echo $value['main_picture'];}else{ echo 'http://menu.wymenu.com/wymenuv2/img/product_default.png';}?>">
										<div class="mui-media-body">
										<?php echo $value['goods_name'];?>
										<p class='mui-ellipsis'>
											<span class="mui-pull-left">单价 : ¥<?php echo $value['price'];?></span>
											<span class="mui-pull-right">x <?php echo $value['num'];?> / <?php echo $value['goods_unit'];?></span>
										</p>
										</div>
									</li>
									<?php endforeach; ?>
								</ul>
							</div>
							<div class="mui-card-footer">
								<?php if($value['invoice_accountno']): ?>
									<?php if($value['sent_type']==3): ?>
										<a class="mui-card-link">
										<span class="mui-pull-right"><?php echo $value['sent_personnel'];?> </span>
										</a>
										<a class="mui-card-link" href="tel:<?php echo $value['mobile'];?>">物流单号 : 
										<span class="mui-pull-right" > <?php echo $value['mobile'];?> </span>
										</a>
									<?php else: ?>
										<a class="mui-card-link">配送员 : 
										<span class="mui-pull-right" style="color:black;"> <?php echo $value['sent_personnel'];?> </span>
										</a>
										<a class="mui-card-link" href="tel:<?php echo $value['mobile'];?>">手机号 : 
										<span class="mui-pull-right" > <?php echo $value['mobile'];?> </span>
										</a>
									<?php endif; ?>
									<?php if($value['istatus']==1): ?>
										<a class="mui-card-link">
										<button type="button" class="mui-btn mui-btn-danger mui-btn-outlined mui-sureo" style="padding: 2px 12px;float:right;" invoice_accountno="<?php echo $value['invoice_accountno']; ?>" account_no="<?php echo $value['account_no']; ?>">确认收货</button>
										</a>
									<?php elseif($value['istatus']==2): ?>
										<a class="mui-card-link">
										<button type="button" class="mui-btn mui-btn-danger mui-btn-outlined mui-sureo" style="padding: 2px 12px;float:right;" disabled invoice_accountno="<?php echo $value['invoice_accountno']; ?>" account_no="<?php echo $value['account_no']; ?>">已收货</button>
										</a>
									<?php endif; ?>
								<?php else: ?>
									<a class="mui-card-link"></a>
									<a class="mui-card-link">
										<span class="mui-pull-right" style="color:red;"><?php echo '备货中...';?></span>
									</a>
								<?php endif; ?>
							</div>
						</div>
						<?php endforeach; ?>
					<?php endif; ?>



				<?php elseif($type==1)://线上支付待付款 ?>
					<ul class="mui-table-view">
						<?php foreach ($goods_orders as $key => $value):?>
						<li class="mui-table-view-cell mui-media">
							<img class="mui-media-object mui-pull-left" src="<?php if($value['main_picture']){ echo $value['main_picture'];}else{ echo 'http://menu.wymenu.com/wymenuv2/img/product_default.png';}?>">
							<div class="mui-media-body">
							<?php echo $value['goods_name'];?>
							<p class='mui-ellipsis'>
								<span class="mui-pull-left">单价 : ¥<?php echo $value['price'];?></span>
								<span class="mui-pull-right">x <?php echo $value['num'];?> / <?php echo $value['goods_unit'];?></span>
							</p>
							<p class='mui-ellipsis'>
							<?php if($value['paytype']==2 || ($value['paytype']==1 && $value['pay_status']==1)): ?>
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
								<span class="mui-pull-left">仓库 : <?php echo $value['company_name'];?></span>
								<span class="mui-pull-right" style="color:red;"><?php echo '备货中';?></span>
								<?php endif; ?>
							<?php endif; ?>
							</p>
							</div>
						</li>
						<?php endforeach; ?>
					</ul>
				<?php elseif($type==2)://待发货 ?>
					<?php
						$nosents =array();
						foreach ($goods_orders as $key1 => $goods_order) {
							if(!isset($nosents[$goods_order['stock_dpid']])){
								$nosents[$goods_order['stock_dpid']] = array();
							}
							array_push($nosents[$goods_order['stock_dpid']], $goods_order);
						}
						foreach ($nosents as $key2 => $nosent):
					?>
					<div class="mui-card">
						<div class="mui-card-header mui-card-media card-head">
							<img src="<?php echo  Yii::app()->request->baseUrl; ?>/img/cangku.png" />
							<div class="mui-media-body">
								[ <span style="color:darkblue;">仓库</span> ]<?php echo $nosent[0]['company_name'] ?>
								<p>
								<?php if($nosent[0]['invoice_accountno']): ?>
								<span class="mui-pull-left">配送单号 : <?php echo $nosent[0]['invoice_accountno'];?> </span>
								<?php else: ?>
								<span class="mui-pull-left" style="color:red;"><?php echo '';?></span>
								<?php endif; ?>
								<br>
							</p>
							</div>
						</div>
						<div class="mui-card-content" >
							<ul class="mui-table-view">
								<?php foreach ($nosent as $key3 => $value):?>
								<li class="mui-table-view-cell mui-media">
									<img class="mui-media-object mui-pull-left" src="<?php if($value['main_picture']){ echo $value['main_picture'];}else{ echo 'http://menu.wymenu.com/wymenuv2/img/product_default.png';}?>">
									<div class="mui-media-body">
									<?php echo $value['goods_name'];?>
									<p class='mui-ellipsis'>
										<span class="mui-pull-left">单价 : ¥<?php echo $value['price'];?></span>
										<span class="mui-pull-right">x <?php echo $value['num'];?> / <?php echo $value['goods_unit'];?></span>
									</p>
									</div>
								</li>
								<?php endforeach; ?>
							</ul>
						</div>
						<div class="mui-card-footer">
							<?php if($value['invoice_accountno']): ?>
							<a class="mui-card-link">
							<span class="mui-pull-left">配送单号 : <?php echo $value['invoice_accountno'];?> </span>
								<?php if($value['istatus']==2): ?>
									<span class="mui-pull-right" style="color:green;"><?php echo '已签收';?> </span>
								<?php elseif($value['istatus']==1): ?>
									<span class="mui-pull-right" style="color:red;"><?php echo '运输中...';?> </span>
								<?php elseif($value['istatus']==0): ?>
									<span class="mui-pull-right" style="color:red;"><?php echo '备货中...';?> </span>
								<?php endif; ?>
							</a>
							<?php else: ?>
							<a class="mui-card-link"></a>
							<a class="mui-card-link">
							<span class="mui-pull-right" style="color:red;"><?php echo '备货中...';?></span>
							</a>
							<?php endif; ?>
						</div>
					</div>
					<?php endforeach; ?>
				<?php elseif($type==3||$type==4)://待收货//已收货 ?>
					<?php
						$nogets =array();
						foreach ($goods_orders as $key1 => $goods_order) {
							if(!isset($nogets[$goods_order['invoice_accountno']])){
								$nogets[$goods_order['invoice_accountno']] = array();
							}
							array_push($nogets[$goods_order['invoice_accountno']], $goods_order);
						}
						foreach ($nogets as $key4 => $noget):
					?>
					<div class="mui-card">
						<div class="mui-card-header mui-card-media card-head">
							<img src="<?php echo  Yii::app()->request->baseUrl; ?>/img/cangku.png" />
							<div class="mui-media-body">
								[ <span style="color:darkblue;">仓库</span> ]<?php echo $noget[0]['company_name'] ?>
								<p>
								<?php if($noget[0]['invoice_accountno']): ?>
								<span class="mui-pull-left">出库单号 : <?php echo $noget[0]['invoice_accountno'];?> </span>
								<?php if($noget[0]['istatus']==2): ?>
									<span class="mui-pull-right" style="color:green;"><?php echo '已签收';?> </span>
								<?php elseif($noget[0]['istatus']==1): ?>
									<span class="mui-pull-right" style="color:red;"><?php echo '运输中...';?> </span>
								<?php elseif($noget[0]['istatus']==0): ?>
									<span class="mui-pull-right" style="color:red;"><?php echo '备货中...';?> </span>
								<?php endif; ?>
								<?php else: ?>
								<span class="mui-pull-left" style="color:red;">正在处理...<?php echo '';?></span>
								<?php endif; ?>
								<br>
							</p>
							</div>
						</div>
						<div class="mui-card-content" >
							<ul class="mui-table-view">
								<?php foreach ($noget as $key5 => $value):?>
								<li class="mui-table-view-cell mui-media">
									<img class="mui-media-object mui-pull-left" src="<?php if($value['main_picture']){ echo $value['main_picture'];}else{ echo 'http://menu.wymenu.com/wymenuv2/img/product_default.png';}?>">
									<div class="mui-media-body">
									<?php echo $value['goods_name'];?>
									<p class='mui-ellipsis'>
										<span class="mui-pull-left">单价 : ¥<?php echo $value['price'];?></span>
										<span class="mui-pull-right">x <?php echo $value['num'];?> / <?php echo $value['goods_unit'];?></span>
									</p>
									</div>
								</li>
								<?php endforeach; ?>
							</ul>
						</div>
						<div class="mui-card-footer">
							<?php if($value['invoice_accountno']): ?>
								<?php if($value['sent_type']==3): ?>
									<a class="mui-card-link">
									<span class="mui-pull-right"><?php echo $value['sent_personnel'];?> </span>
									</a>
									<a class="mui-card-link" href="tel:<?php echo $value['mobile'];?>">物流单号 : 
									<span class="mui-pull-right" > <?php echo $value['mobile'];?> </span>
									</a>
								<?php else: ?>
									<a class="mui-card-link">配送员 : 
									<span class="mui-pull-right" style="color:black;"> <?php echo $value['sent_personnel'];?> </span>
									</a>
									<a class="mui-card-link" href="tel:<?php echo $value['mobile'];?>">手机号 : 
									<span class="mui-pull-right" > <?php echo $value['mobile'];?> </span>
									</a>
								<?php endif; ?>
								<?php if($value['istatus']==1): ?>
									<a class="mui-card-link">
									<button type="button" class="mui-btn mui-btn-danger mui-btn-outlined mui-sureo" style="padding: 2px 12px;float:right;" invoice_accountno="<?php echo $value['invoice_accountno']; ?>" account_no="<?php echo $value['account_no']; ?>">确认收货</button>
									</a>
								<?php elseif($value['istatus']==2): ?>
									<a class="mui-card-link">
									<button type="button" class="mui-btn mui-btn-danger mui-btn-outlined mui-sureo" style="padding: 2px 12px;float:right;" disabled invoice_accountno="<?php echo $value['invoice_accountno']; ?>" account_no="<?php echo $value['account_no']; ?>">已收货</button>
									</a>
								<?php endif; ?>
							<?php else: ?>
								<a class="mui-card-link"></a>
								<a class="mui-card-link">
									<span class="mui-pull-right" style="color:red;"><?php echo '备货中...';?></span>
								</a>
							<?php endif; ?>
						</div>
					</div>
					<?php endforeach; ?>
				<?php endif; ?>
				</div>
				<div class="mui-card-footer">
				<?php if($type==0)://全部 ?>
					<?php if ($goods_orders[0]['reality_total']): ?>
					<a class="mui-card-link">合计 : ¥ <?php echo $goods_orders[0]['reality_total']; ?></a>
					<?php endif; ?>
					<?php if($goods_orders[0]['paytype']==1 && $goods_orders[0]['pay_status']==0)://待付款 ?>
						<button type="button" class="mui-btn mui-btn-success mui-btn-outlined gotopay" account_no="<?php echo $goods_orders[0]['account_no']; ?>">直接付款</button>
						<button type="button" class="mui-btn mui-btn-danger mui-btn-outlined delete_nopay" account_no="<?php echo $goods_orders[0]['account_no']; ?>">删除订单</button>
					<?php elseif((($goods_orders[0]['paytype']==2 && $goods_orders[0]['pay_status']==0) || $goods_orders[0]['pay_status']==1) && $goods_orders[0]['order_status']<5)://待发货 ?>
						<a class="mui-card-link"><?php if($goods_orders[0]['paytype']==1){echo '<span style="color:green">线上支付</span>';}else if($goods_orders[0]['paytype']==2){echo '<span style="color:red">线下支付</span>';}  ?></a>
						<a class="mui-card-link"><?php if($goods_orders[0]['pay_status']==1){echo '<span style="color:green">已付款</span>';}else if($goods_orders[0]['pay_status']==0){echo '<span style="color:red">未付款</span>';}  ?></a>
					<?php elseif($goods_orders[0]['order_status']==5 && ($goods_orders[0]['istatus']==0 || $goods_orders[0]['istatus']==1) && (($goods_orders[0]['order_type']==1 && $goods_orders[0]['pay_status']==1) || ($goods_orders[0]['paytype']==2 && $goods_orders[0]['pay_status']==0)))://待收货 ?>
						<a class="mui-card-link"><?php if($goods_orders[0]['paytype']==1){echo '<span style="color:green">线上支付</span>';}else if($goods_orders[0]['paytype']==2){echo '<span style="color:red">线下支付</span>';}  ?></a>
						<a class="mui-card-link"><?php if($goods_orders[0]['pay_status']==1){echo '<span style="color:green">已付款</span>';}else if($goods_orders[0]['pay_status']==0){echo '<span style="color:red">未付款</span>';}  ?></a>
						<a class="mui-card-link" href="<?php echo $this->createUrl('myinfo/goodsRejected',array('companyId'=>$this->companyId,'account_no'=>$goods_orders[0]['account_no'])); ?>">查看运输损耗</a>
					<?php elseif($goods_orders[0]['order_status']==5 && $goods_orders[0]['istatus']==2  && (($goods_orders[0]['order_type']==1 && $goods_orders[0]['pay_status']==1) || ($goods_orders[0]['paytype']==2 && $goods_orders[0]['pay_status']==0)))://已签收 ?>
						<a class="mui-card-link"><?php if($goods_orders[0]['paytype']==1){echo '<span style="color:green">线上支付</span>';}else if($goods_orders[0]['paytype']==2){echo '<span style="color:red">线下支付</span>';}  ?></a>
						<a class="mui-card-link"><?php if($goods_orders[0]['pay_status']==1){echo '<span style="color:green">已付款</span>';}else if($goods_orders[0]['pay_status']==0){echo '<span style="color:red">未付款</span>';}  ?></a>
						<a class="mui-card-link" href="<?php echo $this->createUrl('myinfo/goodsRejected',array('companyId'=>$this->companyId,'account_no'=>$goods_orders[0]['account_no'])); ?>">查看运输损耗</a>
					<?php endif; ?>
				<?php elseif($type==1)://待付款 ?>
					<a class="mui-card-link">合计 : ¥ <?php echo $goods_orders[0]['reality_total']; ?></a>
					<button type="button" class="mui-btn mui-btn-success mui-btn-outlined gotopay" account_no="<?php echo $goods_orders[0]['account_no']; ?>">直接付款</button>
					<button type="button" class="mui-btn mui-btn-danger mui-btn-outlined delete_nopay" account_no="<?php echo $goods_orders[0]['account_no']; ?>">删除订单</button>
				<?php elseif($type==2)://待发货 ?>
					<a class="mui-card-link">合计 : ¥ <?php echo $goods_orders[0]['reality_total']; ?></a>
					<a class="mui-card-link"><?php if($goods_orders[0]['paytype']==1){echo '<span style="color:green">线上支付</span>';}else if($goods_orders[0]['paytype']==2){echo '<span style="color:red">线下支付</span>';}  ?></a>
					<a class="mui-card-link"><?php if($goods_orders[0]['pay_status']==1){echo '<span style="color:green">已付款</span>';}else if($goods_orders[0]['pay_status']==0){echo '<span style="color:red">未付款</span>';}  ?></a>
				<?php elseif($type==3||$type==4)://待收货 ?>
					<a class="mui-card-link">合计 : ¥ <?php echo $goods_orders[0]['reality_total']; ?></a>
					<a class="mui-card-link"><?php if($goods_orders[0]['paytype']==1){echo '<span style="color:green">线上支付</span>';}else if($goods_orders[0]['paytype']==2){echo '<span style="color:red">线下支付</span>';}  ?></a>
					<a class="mui-card-link"><?php if($goods_orders[0]['pay_status']==1){echo '<span style="color:green">已付款</span>';}else if($goods_orders[0]['pay_status']==0){echo '<span style="color:red">未付款</span>';}  ?></a>
					<a class="mui-card-link" href="<?php echo $this->createUrl('myinfo/goodsRejected',array('companyId'=>$this->companyId,'account_no'=>$goods_orders[0]['account_no'])); ?>">查看运输损耗</a>
				<?php endif; ?>
				</div>
			</div>
			<?php endif; ?>

	</div>
</div>

<script type="text/javascript">
	mui.init();
	mui('.mui-scroll-wrapper').scroll();
	$('.gotopay').on('tap',function(){
		var account_no = $(this).attr('account_no');
		var companyId ='<?php echo $this->companyId; ?>';
		console.log(account_no);
		location.href = '<?php echo $this->createUrl("ymallcart/orderlist") ?>?companyId='+companyId+'&account_no='+account_no;
	});
	$('.delete_nopay').on('tap',function(){
		var account_no = $(this).attr('account_no');
		console.log(account_no);
		$(this).attr('id', 'aa');
		var btnArray = ['否','是'];
		mui.confirm('是否确定删除所选产品 ？','提示',btnArray,function(e){
			if(e.index==1){
			mui.post('<?php echo $this->createUrl("myinfo/delete_nopay",array("companyId"=>$this->companyId)) ?>',{
				   account_no:account_no,
				},
				function(data){
					if (data == 1) {
					 	// var x = $('#aa').parent('div').parent('.big-li').attr('class');
					 	// alert(x);
						// $('#aa').parent().parent('.big-li').fadeOut(1000).remove();
						//将图标的数量减去
						// var num = $('#nopay').html();
						// $('#nopay').html(num-1);
						mui.alert('删除成功 ! ! !');
						location.href="<?php echo $this->createUrl('myinfo/goodsOrderNopay',array('companyId'=>$this->companyId));?>";
					}else if(data == 2) {
						mui.alert('因网络原因删除失败 , 请重新删除 ! ! !');
					}else if(data == 3) {
						mui.alert('未查寻到商品删除失败 ! ! !');
					}
				},'json'
			);
		}
		});
	});
	$('.mui-sureo').on('tap',function(){
	 	var account_no = $(this).attr('account_no');
	 	var invoice_accountno = $(this).attr('invoice_accountno');
	 	console.log(account_no);
	 	if (invoice_accountno) {
			location.href = '<?php echo $this->createUrl("myinfo/sureorder",array("companyId"=>$this->companyId)) ?>/account_no/'+account_no+'/invoice_accountno/'+invoice_accountno;
	 	} else {
	 		mui.alert('仓库正在配货 , 无法确认收货');
	 	}
	});
</script>
