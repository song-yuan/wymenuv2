
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
			<div class="mui-navbar-inner mui-bar mui-bar-nav">
				<button type="button" class="mui-left mui-action-back mui-btn  mui-btn-link mui-btn-nav mui-pull-left">
					<span class="mui-icon mui-icon-left-nav"></span>
				</button>
				<h1 class="mui-center mui-title">我的</h1>
			</div>
			<!--页面标题栏结束-->
			<!--页面主内容区开始-->
			<div class="mui-page-content">
				<div class="mui-scroll-wrapper">
					<div class="mui-scroll">
						<ul class="mui-table-view mui-table-view-striped mui-table-view-condensed ">
							<li class="mui-table-view-cell">
								<div class="mui-slider-cell">
									<div class="oa-contact-cell mui-table">
										<div class="oa-contact-avatar mui-table-cell" style="font-size:50px;line-height:65px;text-align: center; padding:10px 15px;margin-right:20px;font-family: '华文新魏';font-weight: 900;">
											<div style="background: yellow;color:lightblue;border-radius: 50px;width: 60px;height: 60px;">叶</div>
										</div>
										<div class="oa-contact-content mui-table-cell" style="padding-left: 20px;">
											<div class="mui-clearfix">
												<h4 class="oa-contact-name">叶文洁</h4>
												<span class="oa-contact-position mui-h6">董事长</span>
											</div>
											<p class="oa-contact-email mui-h6">
												yewenjie@sina.com
											</p>
										</div>
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
											<span class="mui-badge daifa" style="background-color: red;color: white;">9</span>
											<span class="mui-label">待发货</span>
										</a>
									<a class="mui-control-item" href="#item3mobile">
											<img class="mui-icons " src="<?php echo  Yii::app()->request->baseUrl; ?>/img/ymall/senting.png ">
											<span class="mui-badge daifa" style="background-color: red;color: white;">9</span>
											<span class="mui-label">待收货</span>
										</a>
									<a class="mui-control-item" href="#item4mobile">
											<img class="mui-icons " src="<?php echo  Yii::app()->request->baseUrl; ?>/img/ymall/sented.jpg ">
											<span class="mui-badge daifa" style="background-color: red;color: white;">9</span>
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
										    		<span class="mui-navigate-right a-store">订单号 : <?php echo $key; ?></span>
									    	</div>
									        <ul class="mui-table-view" id="">
									        	<?php foreach ($material_nopay as $nopay): ?>
											    <li class="mui-row mui-table-view-cell mui-media" style="padding-right: 10px;">
										    		<div>
											            <img class=" mui-pull-left img-show" src="<?php echo  Yii::app()->request->baseUrl.$nopay['main_picture']; ?>" >
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
											<button type="button" class="mui-btn mui-btn-success mui-btn-outlined" style="padding: 2px 12px;border-radius: 10px;float:right;margin-left:20px;margin-right:10px;">直接付款</button>
											<button type="button" class="mui-btn mui-btn-danger mui-btn-outlined delete_nopay" style="padding: 2px 12px;border-radius: 10px;float:right;" account_no="<?php echo $key; ?>">删除订单</button>
											</div>
									    </li>
										<?php endforeach; ?>
									<?php endif; ?>
									</ul>
								</div>
								<div id="item2mobile" class="mui-control-content">
									<ul class="mui-table-view">
										<li class="mui-table-view-cell">
											第二个选项卡子项-1
										</li>
										<li class="mui-table-view-cell">
											第二个选项卡子项-2
										</li>
										<li class="mui-table-view-cell">
											第二个选项卡子项-3
										</li>
										<li class="mui-table-view-cell">
											第二个选项卡子项-4
										</li>
									</ul>
								</div>
								<div id="item3mobile" class="mui-control-content">
									<ul class="mui-table-view">
										<li class="mui-table-view-cell">
											第三个选项卡子项-1
										</li>
										<li class="mui-table-view-cell">
											第三个选项卡子项-2
										</li>
										<li class="mui-table-view-cell">
											第三个选项卡子项-3
										</li>
										<li class="mui-table-view-cell">
											第三个选项卡子项-4
										</li>
										<li class="mui-table-view-cell">
											第三个选项卡子项-5
										</li>
									</ul>
								</div>
								<div id="item4mobile" class="mui-control-content">
									<ul class="mui-table-view">

									</ul>
								</div>
							</div>

							</li>

						</ul>
						<ul class="mui-table-view mui-table-view-chevron">
							<li class="mui-table-view-cell">
								<a href="#lock" class="mui-navigate-right">常见问题速览</a>
							</li>
						</ul>
						<ul class="mui-table-view" style="padding-bottom: 50px">
							<li class="mui-table-view-cell" style="text-align: center;">
								<a id='exit' style="text-align: center;color: #FF3B30;">退出登录</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<!--页面主内容区结束-->
		</div>


		<div id="lock" class="mui-page">
			<div class="mui-navbar-inner mui-bar mui-bar-nav">
				<button type="button" class="mui-left mui-action-back mui-btn  mui-btn-link mui-btn-nav mui-pull-left">
					<span class="mui-icon mui-icon-left-nav"></span>我的
				</button>
				<h1 class="mui-center mui-title head-background">常见问题速览</h1>
			</div>
			<div class="mui-page-content">
				<div class="mui-content-padded">
					<ul class="mui-table-view mui-table-view-chevron">
						<li class="mui-table-view-cell">
							111111111
						</li>
					</ul>
				</div>
			</div>
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
			});
			$("#nopay").bind('DOMNodeInserted', function (e) {
				var num = $('#nopay').html();
			　　if(num == '0'){
					$(this).fadeOut(100).remove();
				}
			});

	</script>

