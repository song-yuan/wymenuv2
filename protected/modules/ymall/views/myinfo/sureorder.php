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
	.mui-numbox{width: 140px!important;}
</style>


		<header class="mui-bar mui-bar-nav mui-hbar">
		    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"  style="color:white;"></a>
		    <h1 class="mui-title"  style="color:white;">确认收货</h1>
		</header>
		<div class="mui-content">
			<div class="mui-scroll-wrapper" style="padding-top: 44px;">
			<div class="mui-scroll" style="padding-bottom: 50px;">
				<ul class="mui-table-view mui-table-view-radio">
					<li>
						<div class="mui-row" style="height: 30px;background-color: #00CED1; color:white;">
					    	<span class="a-store" account_no="<?php echo $account_no; ?>">订单号 : <?php echo $account_no; ?></span>
				    	</div>
					</li>
					<li class="mui-table-view-cell mui-cc mui-selected" value="0">
						<a class="mui-navigate-right">
							无运输损耗
						</a>
					</li>
					<li class="mui-table-view-cell mui-aa" value="1">
						<a class="mui-navigate-right">
							有运输损耗
						</a>
					</li>
				</ul>
				<ul class="mui-table-view mui-bb mui-hidden">
					<?php if($materials_get): ?>
						<li class=" big-li">
					        <ul class="mui-table-view" id="">
					        <?php foreach ($materials_get as $key1 => $material_s): ?>
								<li class="mui-table-view-cell mui-collapse mui-active" style="background-color: white;">
									<a class="mui-navigate-right" href="#" style="height: 40px;padding-top:5px;background-color: #F5F5F5; color:#6600CC;"><span class="ivac" invoice_accountno="<?php echo $key1; ?>"><?php echo '配送单号 : '.$key1; ?></span></a>
									<div class="mui-collapse-content">
										<?php foreach ($material_s as $material_ss): ?>
										<div style="height:120px;">
								            <img class=" mui-pull-left img-show" src="<?php echo  'http://menu.wymenu.com/'.$material_ss['main_picture']; ?>" >
								            <div class="mui-media-body" >
								                <span class="color-blue">[<?php echo $material_ss['company_name']; ?>]</span><br> <span class="color-black l-h"><?php echo $material_ss['goods_name']; ?></span><br>
								                <span>单价 : <span style="color: red;"><?php echo $material_ss['price']; ?></span>元</span>
								                <span style="color:darkslategray;">共</span>
								                <span style="color:red;"><?php echo $material_ss['num']; ?></span>
								                <span style="color:darkslategray;"><?php echo $material_ss['goods_unit']; ?></span>
								                <br><span style="color:orange;">损耗数量 : </span>
								                <div class="mui-numbox mui-right " data-numbox-step='1' data-numbox-min='0' data-numbox-max='<?php echo $material_ss['num'];?>'>
													<button class="mui-btn mui-numbox-btn-minus" type="button">-</button>
													<input class="mui-numbox-input" type="number" value="0" gidlid="<?php echo $material_ss['gidlid'];  ?>" />
													<button class="mui-btn mui-numbox-btn-plus" type="button">+</button>
												</div><span style="color:darkslategray;"> <?php echo $material_ss['goods_unit']; ?></span>
								            </div>
							        	</div>
							        	<?php endforeach; ?>
									</div>
								</li>

							<?php endforeach; ?>
							</ul>
					    </li>
					<?php endif; ?>
				</ul>
				<ul class="mui-table-view" >
					<li class="mui-table-view-cell" style="background-color: #FFFFF0;height:80px;">
						<button type="button" class="mui-btn mui-btn-danger mui-btn-outlined sureorder" style="border-radius: 10px;margin:20px auto;z-index:1;top:30%;" account_no="<?php echo $account_no; ?>">确认收货</button>
					</li>
				</ul>
			</div>
		</div>
		</div>
		<script type="text/javascript">
			mui.init()
			mui('.mui-scroll-wrapper').scroll();

//************************
	$('.mui-aa').on('tap',function(){
		$('.mui-bb').removeClass('mui-hidden');
	});
	$('.mui-cc').on('tap',function(){
		$('.mui-bb').addClass('mui-hidden');
	});

	$("#nopay").bind('DOMNodeInserted', function (e) {
		var num = $('#nopay').html();
	　　if(num == '0'){
			$(this).fadeOut(100).remove();
		}
	});

	$('.sureorder').on('tap',function(){
		var value = $('.mui-selected').attr('value');
		var invoice_accountno = $('.ivac').attr('invoice_accountno');
		var account_no = $('.a-store').attr('account_no');
		// alert(value);
		// alert(invoice_accountno);
		// alert(account_no);
		var btnArray = ['否','是'];
		mui.confirm('是否确认该配送单收货并入库 ？','提示',btnArray,function(e){
			if(e.index==1){
				if (value) {
					var info =[];
					$('.mui-numbox-input').each(function() {
						// if (this.value != 0) {
							var value = $(this).val();
							var gidlid = $(this).attr('gidlid');
							info.push(gidlid+"_"+value);
						// }
					});
					info.join(',');
					console.log(info);
					mui.post('<?php echo $this->createUrl('myinfo/sureorderd',array('companyId'=>$this->companyId)) ?>',{
						   account_no:account_no,
						   invoice_accountno:invoice_accountno,
						   info:info,
						   value:value,
						},
						function(data){
							if (data == 1) {
								mui.alert('收货成功 ! ! !');
								location.href='<?php echo $this->createUrl('myinfo/index',array('companyId'=>$this->companyId)) ?>';
							}else if(data == 0) {
								mui.alert('因网络原因确认收货失败 , 请重新确认 ! ! !');
							}
						},'json'
					);

				}else{
					mui.post('<?php echo $this->createUrl('myinfo/sureorderd',array('companyId'=>$this->companyId)) ?>',{
						   value:value,
						   account_no:account_no,
						   invoice_accountno:invoice_accountno,
						},
						function(data){
							if (data == 1) {
								mui.alert('收货成功 ! ! !');
								location.href='<?php echo $this->createUrl('myinfo/index',array('companyId'=>$this->companyId)) ?>';
							}else if(data == 0) {
								mui.alert('因网络原因确认收货失败 , 请重新确认 ! ! !');
							}
						},'json'
					);
				}
			}
	});
	});
		</script>

