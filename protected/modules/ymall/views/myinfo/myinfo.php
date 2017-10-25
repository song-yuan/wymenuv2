
<style>
	html,
	body {
		background-color: #efeff4;
	}
	.mui-views,
	.mui-view,
	.mui-pages,
	.mui-page,
	.mui-page-content {
		position: absolute;
		left: 0;
		right: 0;
		top: 0;
		bottom: 0;
		width: 100%;
		height: 100%;
		background-color: #efeff4;
	}
	.mui-pages {
		top: 46px;
		height: auto;
	}
	.mui-scroll-wrapper,
	.mui-scroll {
		background-color: #efeff4;
	}
	.mui-page.mui-transitioning {
		-webkit-transition: -webkit-transform 200ms ease;
		transition: transform 200ms ease;
	}
	.mui-page-left {
		-webkit-transform: translate3d(0, 0, 0);
		transform: translate3d(0, 0, 0);
	}
	.mui-ios .mui-page-left {
		-webkit-transform: translate3d(-20%, 0, 0);
		transform: translate3d(-20%, 0, 0);
	}
	.mui-navbar {
		position: fixed;
		right: 0;
		left: 0;
		z-index: 10;
		height: 44px;
		background-color: #f7f7f8;
	}
	.mui-navbar .mui-bar {
		position: absolute;
		background: transparent;
		text-align: center;
	}
	.mui-android .mui-navbar-inner.mui-navbar-left {
		opacity: 0;
	}
	.mui-ios .mui-navbar-left .mui-left,
	.mui-ios .mui-navbar-left .mui-center,
	.mui-ios .mui-navbar-left .mui-right {
		opacity: 0;
	}
	.mui-navbar .mui-btn-nav {
		-webkit-transition: none;
		transition: none;
		-webkit-transition-duration: .0s;
		transition-duration: .0s;
	}
	.mui-navbar .mui-bar .mui-title {
		display: inline-block;
		position: static;
		width: auto;
	}
	.mui-page-shadow {
		position: absolute;
		right: 100%;
		top: 0;
		width: 16px;
		height: 100%;
		z-index: -1;
		content: '';
	}
	.mui-page-shadow {
		background: -webkit-linear-gradient(left, rgba(0, 0, 0, 0) 0, rgba(0, 0, 0, 0) 10%, rgba(0, 0, 0, .01) 50%, rgba(0, 0, 0, .2) 100%);
		background: linear-gradient(to right, rgba(0, 0, 0, 0) 0, rgba(0, 0, 0, 0) 10%, rgba(0, 0, 0, .01) 50%, rgba(0, 0, 0, .2) 100%);
	}
	.mui-navbar-inner.mui-transitioning,
	.mui-navbar-inner .mui-transitioning {
		-webkit-transition: opacity 200ms ease, -webkit-transform 200ms ease;
		transition: opacity 200ms ease, transform 200ms ease;
	}
	.mui-page {
		display: none;
	}
	.mui-pages .mui-page {
		display: block;
	}
	.mui-page .mui-table-view:first-child {
		margin-top: 15px;
	}
	.mui-page .mui-table-view:last-child {
		margin-bottom: 30px;
	}
	.mui-table-view {
		margin-top: 20px;
	}
	.mui-table-view:after {
		height: 0;
	}
	.mui-table-view span.mui-pull-right {
		color: #999;
	}
	.mui-table-view-divider {
		background-color: #efeff4;
		font-size: 14px;
	}
	.mui-table-view-divider:before,
	.mui-table-view-divider:after {
		height: 0;
	}
	.mui-content-padded {
		margin: 10px 0px;
	}
	.mui-locker {
		margin: 35px auto;
		display: none;
	}
	.title{
		margin: 20px 15px 10px;
		color: #6d6d72;
		font-size: 15px;
	}
	.oa-contact-cell.mui-table .mui-table-cell {
		padding: 11px 0;
		vertical-align: middle;
	}
	.oa-contact-cell {
		position: relative;
		margin: -11px 0;
	}
	.oa-contact-avatar {
		width: 75px;
	}
	.oa-contact-avatar img {
		border-radius: 50%;
	}
	.oa-contact-content {
		width: 100%;
	}
	.oa-contact-name {
		margin-right: 20px;
	}
	.oa-contact-name, oa-contact-position {
		float: left;
	}
	.head-background {
		background-color: #F7F7F8;
	}
	.fl{
		float: left;
		height:60px;
		width: 25%;
		position: relative;
	}
	.li-cell{
		padding-top: 10px;
	}
	.mui-icons{
		height: 30px;
		width: 30px;
		display: block;
		margin:2px auto;
	}
	.mui-label{
		width: 100%;
		height: 1.2em;
		display: inline-block;
		text-align: center;
	}
	.mui-segmented-control{
		border: 1px solid white;
	}
	.mui-content-padded{
		margin: 0 0;
	}
	.mui-table-view{
		margin:0 0!important;
	}
	.mui-segmented-control .mui-control-item{
		border-left: 1px solid #fff;
		color:Gray;
	}
	.mui-segmented-control .mui-control-item.mui-active{
		color: #007aff!important;
		background-color: #fff;
	}
	.mui-control-item{
		position: relative;
	}
	.daifa{
		position:absolute;
		top: 0px;
		right: 15%;
	}
	.mui-scroll-wrapper{
		overflow:auto;
	}
	.big-ul{margin-bottom: 50px;margin-top:2px!important;}
	.img-show{width: 80px;height:80px;margin-right: 3px;}
	.a-store{line-height: 30px;padding-left: 15px;}
	.color-blue{
		color:darkblue;
	}
	.color-black{
		color:#323232;
	}
	.l-h{
		line-height: 25px;
	}
	.mui-table-view-cell:after {
		position: absolute;
		right: 0;
		bottom: 0;
		left: 15px;
		height: 0px;
		content: '';
		-webkit-transform: scaleY(.5);
		transform: scaleY(.5);
		background-color: #c8c7cc;
	}
</style>


<!--页面主结构开始-->
<div id="app" class="mui-views">
	<div class="mui-view">
		<div class="mui-navbar">
		</div>
		<div class="mui-pages">
		</div>
	</div>
</div>
<!--页面主结构结束-->
<!--单页面开始-->
<div id="setting" class="mui-page">
	<!--页面标题栏开始-->
	<div class="mui-navbar-inner mui-bar mui-bar-nav mui-hbar">
		<button type="button" class="mui-left mui-action-back mui-btn  mui-btn-link mui-btn-nav mui-pull-left" style="color:white;">
			<span class="mui-icon mui-icon-left-nav" style="color:white;"></span>
		</button>
		<h1 class="mui-center mui-title" style="color:white;">我的</h1>
	</div>
	<!--页面标题栏结束-->
	<!--页面主内容区开始-->
	<div class="mui-page-content">
		<div class="mui-scroll-wrapper" style="margin-bottom: 50px">
			<div class="mui-scroll">
				<ul class="mui-table-view mui-table-view-striped mui-table-view-condensed ">
					<li class="mui-table-view-cell">
						<div class="mui-slider-cell">
							<div class="oa-contact-cell mui-table">
							<?php if ($user_info): ?>
								<div class="oa-contact-avatar mui-table-cell" style="font-size:50px;line-height:65px;text-align: center; padding:10px 15px;margin-right:20px;font-family: '华文新魏';font-weight: 900;">
									<div style="background: yellow;color:lightblue;border-radius: 50px;width: 60px;height: 60px;"><?php echo mb_substr($user_info->staff_no,0,1,'utf-8'); ?></div>
								</div>
								<div class="oa-contact-content mui-table-cell" style="padding-left: 20px;">
									<div class="mui-clearfix">
										<h4 class="oa-contact-name"><?php echo $user_info->staff_no; ?></h4>
										<span class="oa-contact-position mui-h6"><?php echo $user_info->mobile; ?></span>
									</div>
									<p class="oa-contact-email mui-h6">
										<?php echo $user_info->email; ?>
									</p>
								</div>
							<?php endif; ?>
							</div>
						</div>
					</li>
				</ul>
				<ul class="mui-table-view mui-table-view-chevron">
				<!-- <li class="mui-table-view-cell mui-collapse" style="background-color: white;">
			            <a class="mui-navigate-right" href="#">设置当前店铺</a>
			            <div class="mui-collapse-content" style="width:125%;padding:0;">
			                <div class="mui-card">
								<form class="mui-input-group">
									<div class="mui-input-row mui-radio">
										<label>北京店铺1</label>
										<input name="style" type="radio" checked value="">
									</div>
									<div class="mui-input-row mui-radio">
										<label>北京店铺2</label>
										<input name="style" type="radio" value="inverted">
									</div>
								</form>
							</div>
			            </div>
			        </li> -->
				</ul>
				<ul class="mui-table-view mui-table-view-chevron">
					<li class="mui-table-view-cell">
						<a class="mui-navigate-right" href="<?php echo $this->createUrl('address/addressmanage',array('companyId'=>$this->companyId));?>">收货地址管理</a>
					</li>
				</ul>
				<ul class="mui-table-view mui-table-view-chevron">
					<li class="mui-table-view-cell">
						<a class="mui-navigate-right" href="<?php echo $this->createUrl('myinfo/normalsetting',array('companyId'=>$this->companyId));?>">常用设置</a>
					</li>
				</ul>
				<ul class="mui-table-view mui-table-view-chevron">
					<li class="mui-table-view-cell">
						<a class="mui-navigate-right" href="tel:10010">联系总部客服</a>
					</li>
				</ul>
				<ul class="mui-table-view mui-table-view-chevron">
					<li class="mui-table-view-cell">
						<a class="mui-navigate-right" >我的订单</a>
					</li>
				</ul>
				<ul class="mui-table-view mui-table-view-chevron">
					<li class="li-cell ">
						<div id="segmentedControl" class="mui-segmented-control">
							<a class="mui-control-item mui-active" href="#item1mobile">
									<img class="mui-icons " src="<?php echo  Yii::app()->request->baseUrl; ?>/img/ymall/wallet.jpg ">
									<?php if($materials_nopay): ?>
									<span class="mui-badge daifa" id="nopay" style="background-color: red;color: white;"><?php echo count($materials_nopay); ?></span>
									<?php endif; ?>
									<span class="mui-label">待付款</span>
								</a>
							<a class="mui-control-item" href="#item2mobile">
									<img class="mui-icons " src="<?php echo  Yii::app()->request->baseUrl; ?>/img/ymall/waitsent.png ">
									<?php if($materials_pay): ?>
									<span class="mui-badge daifa"  style="background-color: red;color: white;"><?php echo count($materials_pay); ?></span>
									<?php endif; ?>
									<span class="mui-label">待发货</span>
								</a>
							<a class="mui-control-item" href="#item3mobile">
									<img class="mui-icons " src="<?php echo  Yii::app()->request->baseUrl; ?>/img/ymall/senting.png ">
									<?php if($materials_send): ?>
									<span class="mui-badge daifa" style="background-color: red;color: white;"><?php echo count($materials_send); ?></span>
									<?php endif; ?>
									<span class="mui-label">待收货</span>
								</a>
							<a class="mui-control-item" href="#item4mobile">
									<img class="mui-icons " src="<?php echo  Yii::app()->request->baseUrl; ?>/img/ymall/sented.jpg ">
									<?php if($materials_get): ?>
									<span class="mui-badge daifa" style="background-color: red;color: white;"><?php echo count($materials_get); ?></span>
									<?php endif; ?>
									<span class="mui-label">已收货</span>
								</a>
						</div>
					</li>

					<li class="">
						<div class="mui-content-padded">
						<div id="item1mobile" class="mui-control-content mui-active">
							<ul class="mui-table-view big-ul">
							<?php if($materials_nopay): ?>
								<?php foreach ($materials_nopay as $key => $material_nopay): ?>
							    <li class=" big-li">
							    	<div class="mui-row" style="height: 30px;background-color: #E0FFFF; ">
								    		<span class="a-store">订单号 : <?php echo $key; ?></span>
							    	</div>
							        <ul class="mui-table-view" id="">
							        	<?php foreach ($material_nopay as $nopay): ?>
									    <li class="mui-row mui-table-view-cell mui-media" style="padding-right: 10px;">
								    		<div>
									            <img class=" mui-pull-left img-show" src="<?php echo  'http://menu.wymenu.com/'.$nopay['main_picture']; ?>" >
									            <div class="mui-media-body" >
									                <span class="color-blue">[<?php echo $nopay['company_name']; ?>]</span> <span class="color-black l-h"><?php echo $nopay['goods_name']; ?></span><br>
									                <span>单价 : <span style="color: red;"><?php echo $nopay['price']; ?></span>元</span>
									                <span style="color:darkslategray;">共</span>
									                <span style="color:red;"><?php echo $nopay['num']; ?></span>
									                <span style="color:darkslategray;"><?php echo $nopay['goods_unit']; ?></span>
									                <div>
									                	<span >合计 : </span>
									                	<span style="color:red;"><?php echo round($nopay['num']*$nopay['price'],2); ?></span>
									                	<span style="color:darkslategray;">元</span>
									                </div>
									            </div>
									        </div>
									    </li>
										<?php endforeach; ?>
									</ul>
									<div style="height:60px;border:1px solid #F2F2F2;padding-top:6px;">
									<span style="display: inline-block;margin-top: 3px;margin-left: 10px;">总计 : <span style="color:red;"><?php echo $material_nopay[0]['reality_total'] ?></span></span>
									<button type="button" class="mui-btn mui-btn-success mui-btn-outlined gotopay" style="padding: 2px 12px;border-radius: 10px;float:right;margin-left:20px;margin-right:10px;" account_no="<?php echo $key; ?>">直接付款</button>
									<button type="button" class="mui-btn mui-btn-danger mui-btn-outlined delete_nopay" style="padding: 2px 12px;border-radius: 10px;float:right;" account_no="<?php echo $key; ?>">删除订单</button>
									</div>
							    </li>
								<?php endforeach; ?>
							<?php endif; ?>
							</ul>
						</div>
						<div id="item2mobile" class="mui-control-content">
							<ul class="mui-table-view">
							<?php if($materials_pay): ?>
								<?php foreach ($materials_pay as $key => $material_pay): ?>
									<?php
										$material_pays =array();
										foreach ($material_pay as $material) {
											if(!isset($material_pays[$material['stock_dpid']])){
												$material_pays[$material['stock_dpid']] = array();
											}
											array_push($material_pays[$material['stock_dpid']], $material);
										}
										// p($material_pay);
									 ?>
								<li class=" big-li">
							    	<div class="mui-row" style="height: 30px;background-color: #FFA500; color:white;">
								    		<span class="a-store">订单号 : <?php echo $key; ?></span>
							    	</div>
							        <ul class="mui-table-view" id="">
							        <?php foreach ($material_pays as  $material_p): ?>
										<li class="mui-table-view-cell mui-collapse" style="background-color: white;">
											<a class="mui-navigate-right" href="#" style="height: 40px;padding-top:5px;background-color: #F0F8FF; "><span class="a-store"><?php echo $material_p[0]['company_name']; ?></span></a>
											<div class="mui-collapse-content">
												<?php foreach ($material_p as  $material_pp): ?>
												<div style="height:100px;">
										            <img class=" mui-pull-left img-show" src="<?php echo 'http://menu.wymenu.com/'.$material_pp['main_picture']; ?>" >
										            <div class="mui-media-body" >
										                <span class="color-blue">[<?php echo $material_pp['company_name']; ?>]</span> <span class="color-black l-h"><?php echo $material_pp['goods_name']; ?></span><br>
										                <span>单价 : <span style="color: red;"><?php echo $material_pp['price']; ?></span>元</span>
										                <span style="color:darkslategray;">共</span>
										                <span style="color:red;"><?php echo $material_pp['num']; ?></span>
										                <span style="color:darkslategray;"><?php echo $material_pp['goods_unit']; ?></span>
										                <div>
										                	<span >合计 : </span>
										                	<span style="color:red;"><?php echo round($material_pp['num']*$material_pp['price'],2); ?></span>
										                	<span style="color:darkslategray;">元</span>
										                </div>
										            </div>
									        	</div>
									        	<?php endforeach; ?>
											</div>
											<div style="margin-top:20px;color:pink;font-weight: 900;"><span style="float: right;margin-right: -35px;">等待商家确认</span></div>
										</li>
									<?php endforeach; ?>
									</ul>
							    </li>
								<?php endforeach; ?>
							<?php endif; ?>
							</ul>
						</div>
						<div id="item3mobile" class="mui-control-content">
							<ul class="mui-table-view">
							<?php if($materials_send): ?>
								<?php foreach ($materials_send as $key => $material_send): ?>
									<?php
										$material_sends =array();
										foreach ($material_send as $material) {
											if ($material['status'] !=2) {
												if(!isset($material_sends[$material['invoice_accountno']])){
													$material_sends[$material['invoice_accountno']] = array();
												}
												array_push($material_sends[$material['invoice_accountno']], $material);
											}
										}
										if($material_sends):
										// p($material_pay);
									 ?>
								<li class=" big-li">
							    	<div class="mui-row" style="height: 30px;background-color: #00CED1; color:white;">
								    		<span class="a-store">订单号 : <?php echo $key; ?></span>
							    	</div>
							        <ul class="mui-table-view" id="">
							        <?php foreach ($material_sends as $key1 => $material_s): ?>
										<li class="mui-table-view-cell mui-collapse" style="background-color: white;">
											<a class="mui-navigate-right" href="#" style="height: 40px;padding-top:5px;background-color: #F5F5F5; color:#6600CC;"><span><?php if($key1){echo '配送单号 : '.$key1;}else{echo '仓库备货中';} ?></span></a>
											<div class="mui-collapse-content">
												<?php foreach ($material_s as  $material_ss): ?>
												<div style="height:100px;">
										            <img class=" mui-pull-left img-show" src="<?php echo  'http://menu.wymenu.com/'.$material_ss['main_picture']; ?>" >
										            <div class="mui-media-body" >
										                <span class="color-blue">[<?php echo $material_ss['company_name']; ?>]</span> <span class="color-black l-h"><?php echo $material_ss['goods_name']; ?></span><br>
										                <span>单价 : <span style="color: red;"><?php echo $material_ss['price']; ?></span>元</span>
										                <span style="color:darkslategray;">共</span>
										                <span style="color:red;"><?php echo $material_ss['num']; ?></span>
										                <span style="color:darkslategray;"><?php echo $material_ss['goods_unit']; ?></span>
										            </div>
									        	</div>
									        	<?php endforeach; ?>
											</div>
										</li>
										<li class="mui-table-view-cell" style="background-color: #FFFFF0">
											<div >
											<span style="display:inline-block;margin-top: 3px;margin-left: 10px;"><?php if($material_s[0]['sent_type']!=3){ echo '配送员 : '.$material_s[0]['sent_personnel'];}else{ echo '第三方配送 : '.$material_s[0]['sent_personnel'];} ?><br>
											<span ><?php if($material_s[0]['sent_type']!=3){echo '联系电话 : '.$material_s[0]['mobile'];}else{ echo '配送单号 : '.$material_s[0]['mobile'];}  ?></span></span>
											<?php if($material_s[0]['status']==0):?>
											<span style="color:red;float:right;margin-right:-50px;margin-top:20px;z-index:1;"><?php echo '(出货中)'; ?></span>
											<?php elseif($material_s[0]['status']==1):?>
											<button type="button" class="mui-btn mui-btn-danger mui-btn-outlined mui-sureo" style="padding: 2px 12px;border-radius: 10px;float:right;margin-right:-50px;margin-top:20px;z-index:1;" invoice_accountno="<?php echo $key1; ?>" account_no="<?php echo $key; ?>">确认收货</button>
											<?php endif; ?>
											</div>
										</li>
									<?php endforeach; ?>
									</ul>
							    </li>
								<?php endif; ?>
								<?php endforeach; ?>
							<?php endif; ?>
							</ul>
						</div>
						<div id="item4mobile" class="mui-control-content">
							<ul class="mui-table-view">
							<?php if($materials_get): ?>
								<?php foreach ($materials_get as $key3 => $material_get): ?>
									<?php
										$material_gets =array();
										foreach ($material_get as $material) {
												if(!isset($material_gets[$material['invoice_accountno']])){
													$material_gets[$material['invoice_accountno']] = array();
												}
												array_push($material_gets[$material['invoice_accountno']], $material);
										}
									?>
								<li class=" big-li">
							    	<div class="mui-row" style="height: 30px;background-color: #DAA520; color:white;">
								    		<span class="a-store">订单号 : <?php echo $key3; ?></span>
							    	</div>
							        <ul class="mui-table-view" id="">
							        <?php foreach ($material_gets as $key4 => $material_g): ?>
										<li class="mui-table-view-cell mui-collapse" style="background-color: white;">
											<a class="mui-navigate-right" href="#" style="height: 40px;padding-top:5px;background-color: #F5F5DC; color:#00BFFF;"><span><?php if($key4):echo '配送单号 : '.$key4;else:echo '仓库备货中';endif; ?></span></a>
											<div class="mui-collapse-content">
												<?php foreach ($material_g as  $material_gg): ?>
												<div style="height:100px;">
										            <img class=" mui-pull-left img-show" src="<?php echo  'http://menu.wymenu.com/'.$material_gg['main_picture']; ?>" >
										            <div class="mui-media-body" >
										                <span class="color-blue">[<?php echo $material_gg['company_name']; ?>]</span> <span class="color-black l-h"><?php echo $material_gg['goods_name']; ?></span><br>
										                <span>单价 : <span style="color: red;"><?php echo $material_gg['price']; ?></span>元</span>
										                <span style="color:darkslategray;">共</span>
										                <span style="color:red;"><?php echo $material_gg['num']; ?></span>
										                <span style="color:darkslategray;"><?php echo $material_gg['goods_unit']; ?></span>
										                <!-- <div>
										                	<span >合计 : </span>
										                	<span style="color:red;"><?php echo round($material_gg['num']*$material_gg['price'],2); ?></span>
										                	<span style="color:darkslategray;">元</span>
										                </div> -->
										            </div>
									        	</div>
									        	<?php endforeach; ?>
											</div>
										</li>
										<li class="mui-table-view-cell" style="background-color: #FFFFF0">
											<div >
											<span style="display:inline-block;margin-top: 3px;margin-left: 10px;"><?php  if($material_g[0]['sent_type']!=3){ echo '配送员 : '.$material_g[0]['sent_personnel'];}else{ echo '第三方配送 : '.$material_g[0]['sent_personnel'];} ?><br>
											<span ><?php if($material_g[0]['sent_type']!=3){echo '联系电话 : '.$material_g[0]['mobile'];}else{ echo '配送单号 : '.$material_g[0]['mobile'];}   ?></span></span>
											<span type="button" style="padding: 2px 12px;border-radius: 10px;float:right;margin-right:-50px;margin-top:20px;z-index:1; color:red;" account_no="<?php echo $key4; ?>">已收货</span>
											</div>
										</li>
									<?php endforeach; ?>
									</ul>
							    </li>
								<?php endforeach; ?>
							<?php endif; ?>
							</ul>
						</div>
					</div>

					</li>

				</ul>
				<!-- <ul class="mui-table-view mui-table-view-chevron">
					<li class="mui-table-view-cell">
						<a href="#lock" class="mui-navigate-right">常见问题速览</a>
					</li>
				</ul> -->
				<ul class="mui-table-view" >
					<li class="mui-table-view-cell" style="text-align: center;">
						<a id='exit' style="text-align: center;color: #FF3B30;">退出登录</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<!--页面主内容区结束-->
</div>



<script>
mui.init();
 //初始化单页view
var viewApi = mui('#app').view({
	defaultPage: '#setting'
});
 //初始化单页的区域滚动
mui('.mui-scroll-wrapper').scroll();
 //分享操作

 //退出操作******************
document.getElementById('exit').addEventListener('tap', function() {

}, false);
 //************************
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
					$('#aa').parent().parent('.big-li').fadeOut(1000).remove();
					//将图标的数量减去
					var num = $('#nopay').html();
					$('#nopay').html(num-1);
					mui.alert('删除成功 ! ! !');
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
 	// 	var btnArray = ['是','否'];
		// mui.confirm('是否确定该配送单收货 ？','提示',btnArray,function(e){
		// 	if(e.index==0){
		 	location.href = '<?php echo $this->createUrl("myinfo/sureorder",array("companyId"=>$this->companyId)) ?>/account_no/'+account_no+'/invoice_accountno/'+invoice_accountno;
			// }
		// });
 	} else{
 		mui.alert('仓库正在配货 , 无法确认收货');
 	}

});

$('.gotopay').on('tap',function(){
	var account_no = $(this).attr('account_no');
	console.log(account_no);
	location.href = '<?php echo $this->createUrl("ymallcart/orderlist",array("companyId"=>$this->companyId)) ?>/account_no/'+account_no;

});

	$("#nopay").bind('DOMNodeInserted', function (e) {
		var num = $('#nopay').html();
	　　if(num == '0'){
			$(this).fadeOut(100).remove();
		}
	});


</script>

