
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
/*	.oa-contact-cell.mui-table .mui-table-cell {
		padding: 11px 0;
		vertical-align: middle;
	}*/
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
		padding:10px;
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
	.mui-table-view .mui-media-object{max-width: 60px;}
	.sign-name{padding:10px 15px;margin-right:20px;}
	.sign-name img{border-radius: 50px;width: 60px!important;height: 60px!important;}
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
								<div class="oa-contact-avatar mui-table-cell sign-name">
									<img class="mui-media-object mui-pull-left head-img" id="head-img" src="<?php if(0){echo $user_info['logo'];}else{echo 'http://menu.wymenu.com/wymenuv2/img/product_default.png';} ?>">
								</div>
								<div class="oa-contact-content mui-table-cell" >
									<div class="mui-clearfix">
										<h4 class="oa-contact-name"><?php if($user_info['staff_no']){echo $user_info['staff_no'];}else{echo $user_info['username'];} ?></h4>
										<span class="oa-contact-position mui-h6" style="color:darkblue;">[<?php echo $user_info['company_name']; ?>]</span>
									</div>
									<p class="oa-contact-email mui-h6">
										<span class="mui-pull-left"><?php if($user_info['mobile']){echo $user_info['mobile'];}else{echo $user_info['cmobile'];} ?></span>
									</p>
								</div>
							<?php endif; ?>
							</div>
						</div>
					</li>
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
						<a class="mui-navigate-right" href="tel:<?php echo $phone['telephone']; ?>">客服热线</a>
					</li>
				</ul>
<!-- 				<ul class="mui-table-view mui-table-view-chevron">
					<li class="mui-table-view-cell">
						<a class="mui-navigate-right" href="<?php echo $this->createUrl('myinfo/goodsRejected',array('companyId'=>$this->companyId));?>">运输损耗</a>
					</li>
				</ul> -->
				<ul class="mui-table-view mui-table-view-chevron">
					<li class="mui-table-view-cell">
						<a class="mui-navigate-right" href="<?php echo $this->createUrl('myinfo/goodsOrderAll',array('companyId'=>$this->companyId));?>">全部订单</a>
					</li>
				</ul>
				<ul class="mui-table-view mui-table-view-chevron">
					<li class="li-cell ">
						<div id="segmentedControl" class="mui-segmented-control">
							<a class="mui-control-item" href="<?php echo $this->createUrl('myinfo/goodsOrderCheck',array('companyId'=>$this->companyId));?>">
									<img class="mui-icons " src="<?php echo  Yii::app()->request->baseUrl; ?>/img/ymall/order_check.png">
									<?php if($nocheck_no): ?>
									<span class="mui-badge daifa" id="nopay" style="background-color: red;color: white;"><?php echo $nocheck_no; ?></span>
									<?php endif; ?>
									<span class="mui-label">审核</span>
								</a>
							<a class="mui-control-item" href="<?php echo $this->createUrl('myinfo/goodsOrderNosent',array('companyId'=>$this->companyId));?>">
									<img class="mui-icons " src="<?php echo  Yii::app()->request->baseUrl; ?>/img/ymall/waitsent.png ">
									<?php if($nosent_no): ?>
									<span class="mui-badge daifa"  style="background-color: red;color: white;"><?php echo $nosent_no; ?></span>
									<?php endif; ?>
									<span class="mui-label">待发货</span>
								</a>
							<a class="mui-control-item" href="<?php echo $this->createUrl('myinfo/goodsOrderNoget',array('companyId'=>$this->companyId));?>">
									<img class="mui-icons " src="<?php echo  Yii::app()->request->baseUrl; ?>/img/ymall/senting.png ">
									<?php if($noget_no): ?>
									<span class="mui-badge daifa" style="background-color: red;color: white;"><?php echo $noget_no; ?></span>
									<?php endif; ?>
									<span class="mui-label">待收货</span>
								</a>
							<a class="mui-control-item" href="<?php echo $this->createUrl('myinfo/goodsOrderGetted',array('companyId'=>$this->companyId));?>">
									<img class="mui-icons " src="<?php echo  Yii::app()->request->baseUrl; ?>/img/ymall/sented.jpg ">
									<?php if($getted_no): ?>
									<span class="mui-badge daifa" style="background-color: red;color: white;"><?php echo $getted_no; ?></span>
									<?php endif; ?>
									<span class="mui-label">已收货</span>
								</a>
						</div>
					</li>


				</ul>
				<ul class="mui-table-view" style="margin-top: 20px!important;margin-bottom: 30px!important;">
					<li class="mui-table-view-cell" style="text-align: center;" id='exit'>
						<span  style="text-align: center;color: #FF3B30;">退出登录</span>
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

 	//退出操作******************
	document.getElementById('exit').addEventListener('tap', function() {
	 		var btnArray = ['取消', '确定'];
			mui.confirm('确定退出 ？','提示',btnArray,function(e){
				if(e.index==1){
			 	location.href = '<?php echo $this->createUrl("login/logout",array("companyId"=>$this->companyId)) ?>';
				}
			});
	}, false);
 	//************************
	mui('body').on('tap','a',function(){document.location.href=this.href;});


</script>

