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

			/*@import url(http://fonts.googleapis.com/css?family=Expletus+Sans);*/
			*{
				margin:0; padding:0;
				box-sizing: border-box;
			}
			/*body {
			font-family: "Expletus Sans", sans-serif;
			}*/
			.m-title {
				color:gray;
				border-bottom: 1px solid #ccc;
				padding: 9px;
				background-color: honeydew;
				margin:0;
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
			/*@keyframes animate-stripes { 100% { background-position: -100px 0; } }*/
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
			  color:purple;
			}
			p[data-value]:after {
				content: attr(data-value) '';
				position: absolute; right:0;
				color:#f44;
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
			    -webkit-linear-gradient( left, #09C, #ff0);
			}
			.html5::-moz-progress-bar {
				/* Gradient background with Stripes */
				background-image:
				-moz-linear-gradient( 135deg,
									 transparent,
									 transparent 33%,
									 rgba(0,0,0,.1) 33%,
									 rgba(0,0,0,.1) 66%,
									 transparent 66%),
				-moz-linear-gradient( top,
										rgba(255, 255, 255, .25),
										rgba(0,0,0,.2)),
				-moz-linear-gradient( left, #09C, #ff0);
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
			.mui-toast-container{bottom: 50%!important;}
			.buy1{
				display: inline-block;
				height: 40.8px;
				width: 40px;
				background-image: url('../../../../../../img/ymall/buy1.png');
	    		background-size: 102%;
			}
			.buy2{
				display: inline-block;
				height: 40.8px;
				width: 40px;
				background-image: url('../../../../../../img/ymall/buy2.png');
	    		background-size: 102%;
			}
			.buy3{
				display: inline-block;
				height: 40.8px;
				width: 40px;
				background-image: url('../../../../../../img/ymall/buy3.png');
	    		background-size: 102%;
			}
			.mui-media-body{
				color: orange!important;
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
					<a iid="mui-popover1" class="mui-icon mui-icon-download mui-pull-right" style="color:white;"></a>
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
						<?php if ($marquee): ?>
						<div class="top">
							<marquee>滚动条公告</marquee>
						</div>
						<?php endif; ?>
						<!-- 活动轮播图 -->
						<?php if($ads): $x=count($ads); ?>
						<div id="slider" class="mui-slider">
							<div class="mui-slider-group mui-slider-loop">
							<!--支持循环，需要重复图片节点   最后一张图-->
								<div class="mui-slider-item mui-slider-item-duplicate">
									<a href="#">
										<img src="<?php echo $ads[$x-1]['main_picture']; ?>" class="img-lunbo">
									</a>
									<p class="mui-slider-title">
										<?php echo $ads[$x-1]['name']; ?>
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
										<img src="<?php echo $ads[0]['main_picture']; ?>" class="img-lunbo">
									</a>
									<p class="mui-slider-title">
										<?php echo $ads[0]['name']; ?>
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

						<ul class="mui-table-view mui-grid-view mui-grid-9">
							<li class="mui-table-view-cell mui-col-xs-12" style="padding:0;">
								<h4  class=" m-title" style="text-align: left;padding:7px;">常用功能</h4>
							</li>
							<li class="mui-table-view-cell mui-media mui-col-xs-4" style="color:red;" id="buy3">
								<a href="#">
									<span class="buy3"></span>
									<div class="mui-media-body">按天采购</div>
								</a>
							</li>
							<li class="mui-table-view-cell mui-media mui-col-xs-4" style="color:red;" id="buy2">
								<a href="#">
									<span class="buy2"></span>
									<div class="mui-media-body">安全库存采购</div>
								</a>
							</li>
							<li class="mui-table-view-cell mui-media mui-col-xs-4" style="color:red;" id="buy1">
								<a href="#">
									<span class="buy1"></span>
									<div class="mui-media-body">预估额采购</div>
								</a>
							</li>

						</ul>



						<div>
							<h4 class="m-title">现有原料库存</h4>
							<!-- HTML5 -->
							<div style="width: 96%;margin:0 auto;position: relative;">
								<?php if($stocks):
									$arr = array();
									foreach ($stocks as $key => $value) {
										if(!isset($arr[$value['category_id']])){
											$arr[$value['category_id']] = array();
										}
										if(isset($stocks_arr['lid'.$value['lid']])){
											// p($stocks_arr['lid'.$value['lid']]);
											$value['safe_stock'] = $stocks_arr['lid'.$value['lid']]['safe_stock'];
											$value['max_stock'] = $stocks_arr['lid'.$value['lid']]['max_stock'];
										}else{
											$value['safe_stock'] = $value['stock'];
											$value['max_stock'] = $value['stock'];
										}
										array_push($arr[$value['category_id']], $value);
									}
									// p($arr);
								?>
								<?php
								foreach ($arr as $ar):

								?>
								<fieldset>
									<legend><?php echo $ar[0]['category_name']; ?></legend>
									<?php foreach ($ar as  $stock): ?>
							    	<p style="width:100%;margin:0;" data-value="<?php if ($stock['stock']<1 ||$stock['stock']<$stock['safe_stock']) {echo '危险库存 ! 剩余: '.$stock['stock'].' '.$stock['unit_name'];} elseif ( $stock['stock']>$stock['max_stock']) {echo '充足库存 ! 剩余: '.$stock['stock'].' '.$stock['unit_name'];} else {echo '安全库存 ! 剩余: '.$stock['stock'].' '.$stock['unit_name'];} ?>"><?php echo $stock['material_name']; ?></p>
									<progress style="width:100%;" max="<?php echo $stock['max_stock']; ?>" value="<?php echo $stock['stock']; ?>" class="html5"></progress>
									<?php endforeach; ?>
								</fieldset>
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
			mui('.mui-grid-9').on('tap','#buy2',function(){
				var btnArray = ['取消', '确定'];
				mui.confirm('按照安全库存模式生成购物车清单 ？','安全库存采购',btnArray,function(e){
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
									mui.alert(ss[1]+'为自建原料,或者总部无货, 无法总部购买!!!');
									setTimeout("location.href='<?php echo $this->createUrl("ymallcart/index",array("companyId"=>$this->companyId)) ?>'",2500);
								}
							}else {
								if (ss[1] == '') {
									mui.alert(ss[0]+'没有消耗信息需要手动添加');
									setTimeout("location.href='<?php echo $this->createUrl("ymallcart/index",array("companyId"=>$this->companyId)) ?>'",2500);
								}else {
									mui.alert(ss[0]+'没有消耗信息需要手动添加'+'----'+ss[1]+'为自建原料,或者总部无货, 无法总部购买!!!');
									setTimeout("location.href='<?php echo $this->createUrl("ymallcart/index",array("companyId"=>$this->companyId)) ?>'",2500);
								}
							}
						},'json'
						);
					}else{
						// alert('点击了- 否');
					}
				});
			});

			//采购订单生成对话框
			mui('.mui-grid-9').on('tap','#buy3',function(){

				// e.detail.gesture.preventDefault(); //修复iOS 8.x平台存在的bug，使用plus.nativeUI.prompt会造成输入法闪一下又没了
				var btnArray = ['取消', '确定'];
				mui.prompt('按照预估天数生成购物车清单 ？', '7', '按天采购', btnArray, function(e) {
					if(e.index==1){
						//自己的逻辑
						mui.post('<?php echo $this->createUrl("autodownorder/index2",array("companyId"=>$this->companyId)) ?>',{  //请求接口地址
							aa:1, // 参数  键 ：值
							day_nums:e.value, // 参数  键 ：值
						},
						function(data){ //data为服务器端返回数据
							//自己的逻辑
							console.log(data);
							ss = data.split("-");
							if (ss[0] == '') {
								if (ss[1] == '') {
									location.href='<?php echo $this->createUrl("ymallcart/index",array("companyId"=>$this->companyId)) ?>';
								}else {
									mui.alert(ss[1]+'为自建原料,或者总部无货, 无法总部购买!!!');
									setTimeout("location.href='<?php echo $this->createUrl("ymallcart/index",array("companyId"=>$this->companyId)) ?>'",2500);
								}
							}else {
								if (ss[1] == '') {
									mui.alert(ss[0]+'没有消耗信息需要手动添加');
									setTimeout("location.href='<?php echo $this->createUrl("ymallcart/index",array("companyId"=>$this->companyId)) ?>'",2500);
								}else {
									mui.alert(ss[0]+'没有消耗信息需要手动添加'+'----'+ss[1]+'为自建原料,或者总部无货, 无法总部购买!!!');
									setTimeout("location.href='<?php echo $this->createUrl("ymallcart/index",array("companyId"=>$this->companyId)) ?>'",2500);
								}
							}
						},'json'
						);
					}else{
						// alert('点击了- 否');
					}
					
				});
				document.querySelector('.mui-popup-input input').type='number';
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
					mui.toast("请填写搜索内容 ! ! !",{ duration:'long', type:'div' });
				}
			});

			// $('.companyId').change(function(event) {
			// 	/* Act on the event */
			// 	var companyId = $(this).val();
			// 	var btnArray = ['是','否'];
			// 	mui.confirm('是否改变当前店铺 ？','提示',btnArray,function(e){
			// 		if(e.index==0){
			// 			//自己的逻辑
			// 			location.href="<?php echo $this->createUrl('product/index'); ?>/companyId/"+companyId;
			// 		}else{
			// 			location.reload();
			// 		}
			// 	});
			// });
		</script>