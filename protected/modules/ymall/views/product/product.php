		<style>
			.mui-scroll-wrapper{margin-bottom: 50px;}
			.mui-grid-view.mui-grid-9{background-color: white;}
			input[type=search]{background-color: white;}
			.search-form{
				width: 60%;
				margin-right:5%;
				float: right;
				color:#999;
			}
			.color-blue{
				color:darkblue;
			}
			.mui-grid-view.mui-grid-9 .ui-table-view-cell {
			    margin: 0;
			    padding: 11px 15px;
			    vertical-align: top;
			    border-right: 1px solid #eee;
			    border-bottom: 1px solid #eee;
			}
			.mui-table-view.mui-grid-view .ui-table-view-cell {
			    font-size: 17px;
			    display: inline-block;
			    margin-right: -4px;
			    padding: 10px 14px;
			    vertical-align: middle;
			    background: 0 0;
			}
			.mui-ios .ui-table-view-cell {
			    -webkit-transform-style: preserve-3d;
			    transform-style: preserve-3d;
			}
			.ui-table-view-cell {
			    position: relative;
			    overflow: hidden;
			    padding: 11px 15px;
			    -webkit-touch-callout: none;
			}
			.mui-col-xs-6 {
			    width: 50%;
			    height:229px;
			}
			.bottom{
				width: 85%;
				height: 21px;
				position:absolute;
				bottom:8px;
			}
			/*.mui-search.mui-active .mui-placeholder {
			    right: 200px!important;
			}*/
			.addicon1{
				width: 30px!important;
				height: 30px!important;
				background-color: red;
				border-radius: 30px!important;
				color: #fff;
				vertical-align: middle;
				text-align: center;
				align-items: center;
				line-height: 30px!important;
				font-size: 30px!important;
				font-weight: 600;
			}
			.mui-badge{
				font-size: 12px!important;
			}
			.mui-badge1{
				font-size: 16px!important;
			}
			.mui-cell{background-color: #EFEFF4!important;}
			.processbar{height:1.3em;width: 70%;}
			.mui-content{background-color: white!important;}
			/**		*/

			@import url(http://fonts.googleapis.com/css?family=Expletus+Sans);
			* {
				margin:0; padding:0;
				box-sizing: border-box;
			}
			body {
			font-family: "Expletus Sans", sans-serif;
			}
			h4 {
				color:gray;
				padding-left: 15px;
				border-bottom: 1px solid #ccc;
				padding-bottom: 9px;
			}
			progress:not(value) {
			}
			progress[value] {
				appearance: none;
				border: none;
				width: 100%; height: 20px;
				background-color: whiteSmoke;
				border-radius: 3px;
				box-shadow: 0 2px 3px rgba(0,0,0,.5) inset;
				color: royalblue;
				position: relative;
				margin: 0 0 0.5em;
			}
			progress[value]::-webkit-progress-bar {
				background-color: whiteSmoke;
				border-radius: 3px;
				box-shadow: 0 2px 3px rgba(0,0,0,.5) inset;
			}
			progress[value]::-webkit-progress-value {
				position: relative;
				background-size: 35px 20px, 100% 100%, 100% 100%;
				border-radius:3px;
				animation: animate-stripes 5s linear infinite;
			}
			@keyframes animate-stripes { 100% { background-position: -100px 0; } }
			progress[value]::-webkit-progress-value:after {
				content: '';
				position: absolute;
				width:5px; height:5px;
				top:7px; right:7px;
				background-color: white;
				border-radius: 100%;
			}
			.progress-bar {
				background-color: whiteSmoke;
				border-radius: 3px;
				box-shadow: 0 2px 3px rgba(0,0,0,.5) inset;
				width: 100%; height:20px;
			}
			.progress-bar span {
				background-color: royalblue;
				border-radius: 3px;
				display: block;
				text-indent: -9999px;
			}
			p[data-value] {
			  position: relative;
			}
			p[data-value]:after {
				content: attr(data-value) '';
				position: absolute; right:0;
			}
			.html5::-webkit-progress-value{
				/* Gradient background with Stripes */
				background-image:
				-webkit-linear-gradient( 135deg,
										 transparent,
										 transparent 33%,
										 rgba(0,0,0,.1) 33%,
										 rgba(0,0,0,.1) 66%,
										 transparent 66%),
			    -webkit-linear-gradient( top,
										rgba(255, 255, 255, .25),
										rgba(0,0,0,.2)),
			    -webkit-linear-gradient( left, #f44, #ff0);
			}

			/**		*/
			#popover{
				height: 100px;
				width:200px;
			}
			.img-lunbo{
				width:100%;
				height: 250px;
			}
		</style>

		<div id="popover" class="mui-popover">
			<ul class="mui-table-view">
				<li class="mui-table-view-cell">
					<div class="mui-input-row mui-radio">
						<label>北京店铺1</label>
						<input class="companyId" name="companyId" type="radio" checked value="27">
					</div>
				</li>
				<li class="mui-table-view-cell">
					<div class="mui-input-row mui-radio">
						<label>北京店铺2</label>
						<input class="companyId" name="companyId" type="radio" value="35">
					</div>
				</li>
			</ul>
		</div>
		<div class="mui-off-canvas-wrap mui-draggable">
			<div class="mui-inner-wrap"  id="Main">
				<header class="mui-bar mui-bar-nav mui-hbar">
					<a id="mui-popover1" class="mui-icon mui-icon-download mui-pull-right" style="color:white;"></a>
					<a href="popover" style="z-index:1;color:white;width: 25%;height:40px;display:inline-block;">
						<span class="mui-icon mui-icon-map"></span>
						<p style="color:white;width:66%;height:40px;float:right;overflow:hidden;font-weight:900;"><?php echo $company_name; ?></p>
					</a>
					<form action=" <?php echo  $this->createUrl('kind/search',array('companyId'=>$this->companyId)); ?>" class="search-form" method="GET">
						<div class="mui-input-row mui-search">
							<input type="search" id="search" class="mui-input-clear" name="content" placeholder="搜索产品">
						</div>
					</form>
				</header>

				<!-- 产品内容开始 -->
				<div class="mui-content mui-scroll-wrapper" id="product">
					<div class="mui-scroll">
						<!-- 滚动条公告 -->
						<div class="top">
							<marquee>滚动条公告</marquee>
						</div>
						<!-- 活动轮播图 -->
						<?php if($ads): $x=count($ads); ?>
						<div id="slider" class="mui-slider">
							<div class="mui-slider-group mui-slider-loop">
							<!--支持循环，需要重复图片节点   最后一张图-->
								<div class="mui-slider-item mui-slider-item-duplicate">
									<a href="#">
										<img src="<?php echo $ads[0]['main_picture']; ?>" class="img-lunbo">
									</a>
									<p class="mui-slider-title">
										<?php echo $ads[0]['name']; ?>
									</p>
								</div>

								<!-- 主体循环的图片开始 -->
								<?php foreach ($ads as $key => $ad): ?>
								<div class="mui-slider-item">
									<a href="#">
										<img src="<?php echo $ad['main_picture']; ?>" class="img-lunbo">
									</a>
									<p class="mui-slider-title">
										<?php echo $ad['name']; ?>
									</p>
								</div>
								<?php endforeach; ?>

								<!-- 主体循环的图片结束 -->


								<!--支持循环，需要重复图片节点     第一张图-->
								<div class="mui-slider-item mui-slider-item-duplicate">
									<a href="#">
										<img src="<?php echo $ads[$x-1]['main_picture']; ?>" class="img-lunbo">
									</a>
									<p class="mui-slider-title">
										<?php echo $ads[$x-1]['name']; ?>
									</p>
								</div>
							</div>
							<div class="mui-slider-indicator mui-text-right">
							<?php foreach ($ads as $key => $ad): ?>
								<div class="mui-indicator <?php if($key==0){echo 'mui-active';} ?>"></div>
							<?php endforeach; ?>
							</div>
						</div>
						<?php endif; ?>

						<div>
							<h4>店铺原料库存</h4>
							<!-- HTML5 -->
							<div style="width: 96%;margin:0 auto;position: relative;">
								<?php if($stocks): ?>
								<?php foreach ($stocks as $stock): ?>
							    <p style="width:100%;margin:0;" data-value="<?php echo '剩余: '.$stock['stock'].' '.$stock['unit_name']; ?>"><?php echo $stock['material_name']; ?></p>
								<progress style="width:100%;" max="<?php echo $stock['max_stock']; ?>" value="<?php echo $stock['stock']; ?>" class="html5">
									<div class="progress-bar">
										<!-- <span style="width: 80%"></span> -->
									</div>
								</progress>
								<span style="position:absolute;left:5px;font-size:14px;margin-top: -32px;color:gray;"><?php echo $stock['safe_stock']; ?></span>
								<span style="position:absolute;right:10px;font-size:14px;margin-top: -32px;color:gray;"><?php echo $stock['max_stock']; ?></span>
								<?php endforeach; ?>
								<?php endif; ?>
							</div>
						</div>

					</div>
				</div>
				<!-- 产品内容结束 -->
			</div>
		</div>
		<script src="<?php echo  Yii::app()->request->baseUrl; ?>/js/ymall/prefixfree.min.js"></script>
		<script>
		
			//采购订单生成对话框
			mui('#Main .mui-bar').on('tap','#mui-popover1',function(){
				var btnArray = ['否','是'];
				mui.confirm('根据您近一个月内的原料的消耗量来生成您店铺的采购订单 , 是否确定生成订单 ？','自动生成采购单',btnArray,function(e){
					if(e.index==1){
						//自己的逻辑
						mui.post('<?php echo $this->createUrl("autodownorder/index",array("companyId"=>$this->companyId)) ?>',{  //请求接口地址
							aa:1, // 参数  键 ：值
						},
						function(data){ //data为服务器端返回数据
							//自己的逻辑
							console.log(data);
							ss = data.split("-");
							if (ss[0] == '') {
								if (ss[1] == '') {
									location.href='<?php echo $this->createUrl("ymallcart/index",array("companyId"=>$this->companyId)) ?>';
								}else {
									mui.alert(ss[1]+'为自建原料, 无法总部购买!!!');
									setTimeout("location.href='<?php echo $this->createUrl("ymallcart/index",array("companyId"=>$this->companyId)) ?>'",2500);
									// location.href='<?php echo $this->createUrl("ymallcart/index",array("companyId"=>$this->companyId)) ?>';
								}
							}else {
								if (ss[1] == '') {
									mui.alert(ss[0]+'没有消耗信息需要手动添加');
									setTimeout("location.href='<?php echo $this->createUrl("ymallcart/index",array("companyId"=>$this->companyId)) ?>'",2500);
									// location.href='<?php echo $this->createUrl("ymallcart/index",array("companyId"=>$this->companyId)) ?>';
								}else {
									mui.alert(ss[0]+'没有消耗信息需要手动添加'+'----'+ss[1]+'为自建原料, 无法总部购买!!!');
									setTimeout("location.href='<?php echo $this->createUrl("ymallcart/index",array("companyId"=>$this->companyId)) ?>'",2500);
									// location.href='<?php echo $this->createUrl("ymallcart/index",array("companyId"=>$this->companyId)) ?>';
								}
							}
						},'json'
						);
					}else{
						// alert('点击了- 否');
					}
				});
			});

			//初始化单页的区域滚动
			mui('.mui-scroll-wrapper').scroll();

			//获得slider插件对象
			var gallery = mui('.mui-slider');
			gallery.slider({
			  interval:5000//自动轮播周期，若为0则不自动播放，默认为0；
			});

			$('.search-form').submit(function(event) {
				var content = $('#search').val();
				// alert(content);
				if (content == '') {
					event.preventDefault();
					mui.alert("请填写搜索内容 ! ! !");
				}
			});

			$('.companyId').change(function(event) {
				/* Act on the event */
				var companyId = $(this).val();
				var btnArray = ['是','否'];
				mui.confirm('是否改变当前店铺 ？','提示',btnArray,function(e){
					if(e.index==0){
						//自己的逻辑
						location.href="<?php echo $this->createUrl('product/index'); ?>/companyId/"+companyId;
					}else{
						location.reload();
					}
				});
			});
		</script>