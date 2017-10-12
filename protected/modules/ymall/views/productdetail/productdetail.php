
		<link href="<?php echo  Yii::app()->request->baseUrl; ?>/css/ymall/mui.min.css" rel="stylesheet" />
		<link href="<?php echo  Yii::app()->request->baseUrl; ?>/css/ymall/iconfont.css" rel="stylesheet"/>
		<style>
			.back-color{background-color: #F7F7F7;}
			.bar-height{
				height: 44px!important;
				line-height: 44px!important;
				display: inline-block;
			}
			.head{
				width: 50%!important;
				margin-left: 25%;
			}
			.back{

				margin-left:10px;
			}
			.color-red{
				color:red;
			}
			.color-blue{
				color:darkblue;
			}
			.color-gray{
				color:darkslategray;
			}
			.color-black{
				color:#323232;
			}
			.p-top{
				padding-top: 3px;
			}
			.l-h{
				line-height: 25px;
			}
			.fontsize18{
				font-size: 18px;
			}
			.fontsize14{
				font-size: 14px!important;
			}
			.fontweight{
				font-weight: 900;
			}
			.mui-table-view.mui-grid-view .mui-table-view-cell{
				text-align:left;
			}
			.mui-bar .mui-segmented-control{
				top:0;
			}
			/*.mui-slider-group{
				margin-top:47px;
			}*/
			.mui-active{
				font-size:1.2em;
			}
		</style>

		<div class="mui-content">
			<div id="slider" class="mui-slider back-color">
				<!--
                	作者：979071732@qq.com
                	时间：2017-07-19
                	描述：头部
                -->
                <div class="mui-bar mui-bar-nav">

                	<a class="back mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>

                	<div class="head ">
                		<div id="sliderSegmentedControl" class="mui-slider-indicator mui-segmented-control mui-segmented-control-inverted">
                			<a class="mui-control-item bar-height mui-active" href="#item1mobile">商品</a>
                			<a class="mui-control-item bar-height" href="#item2mobile">详情</a>
                			<!-- <a class="mui-control-item bar-height" href="#item3mobile">评价</a> -->
                		</div>
                		<!-- <div id="sliderProgressBar" class="mui-slider-progress-bar mui-col-xs-4"></div> -->
                		<div id="sliderProgressBar" class="mui-slider-progress-bar mui-col-xs-6"></div>
                	</div>
                </div>

				<div class="mui-slider-group mui-content ">



					<!--
                    	作者：979071732@qq.com
                    	时间：2017-07-21
                    	描述：商品tab页
                    -->
					<div id="item1mobile" class="mui-slider-item mui-control-content mui-active p-top" style="background: white;height: 100%;">
						<!--
                        	作者：979071732@qq.com
                        	时间：2017-07-20
                        	描述：轮播图片
                        -->
						<div id="slider1" class="mui-slider">
							<div class="mui-slider-group mui-slider-loop">
								<div class="mui-slider-item mui-slider-item-duplicate">
									<a href="#"><img src="http://dcloudio.github.io/mui/assets/img/yuantiao.jpg"></a>
								</div>
								<div class="mui-slider-item">
									<a href="#"><img src="http://dcloudio.github.io/mui/assets/img/shuijiao.jpg"></a>
								</div>
								<div class="mui-slider-item">
									<a href="#"><img src="http://dcloudio.github.io/mui/assets/img/muwu.jpg"></a>
								</div>
								<div class="mui-slider-item">
									<a href="#"><img src="http://dcloudio.github.io/mui/assets/img/cbd.jpg"></a>
								</div>
								<div class="mui-slider-item">
									<a href="#"><img src="http://dcloudio.github.io/mui/assets/img/yuantiao.jpg"></a>
								</div>
								<div class="mui-slider-item mui-slider-item-duplicate">
									<a href="#"><img src="http://dcloudio.github.io/mui/assets/img/shuijiao.jpg"></a>
								</div>
							</div>
							<div class="mui-slider-indicator">
								<div class="mui-indicator mui-active"></div>
								<div class="mui-indicator"></div>
								<div class="mui-indicator"></div>
								<div class="mui-indicator"></div>
							</div>
						</div>

						<ul class="mui-table-view">
							<li class="mui-content-padded">
								<h4><span class="color-blue">[仓库简称]</span> <span class="color-black l-h">商品名称商品名称商品名称商品名称</span></h4>

							</li>
							<li class="mui-content-padded">
								<h3 class="color-red">￥&nbsp;998</h3>
							</li>
							<li class="mui-content-padded">
								<h4  class="color-gray"><span>剩余库存量</span> <span class="color-red">88888</span> <span>千克</span></h4>
							</li>

							<li class="mui-content-padded">
								<span class="color-gray fontsize18 fontweight">选择数量</span>&nbsp;
								<div class="mui-numbox mui-right " data-numbox-step='1' data-numbox-min='1' data-numbox-max='10000'style="width: 160px!important;">
								  	<button class="mui-btn mui-numbox-btn-minus" type="button">-</button>
								  	<input class="mui-numbox-input" id='a2' type="number" />
								  	<button class="mui-btn mui-numbox-btn-plus" type="button">+</button>
								</div>
								<span class="color-gray fontsize18 fontweight">千克</span>
							</li>

						</ul>
						<button type="button" class="mui-btn mui-btn-red mui-btn-block" style="margin-top:20px;height:50px;">加入购物车</button>
						
					</div>






					<!--
                    	作者：979071732@qq.com
                    	时间：2017-07-21
                    	描述：详情tab页
                    -->
					<div id="item2mobile" class="mui-slider-item mui-control-content p-top">

						<ul class="mui-table-view">
							<li class="mui-table-view-divider">规格参数</li>
								<li class="mui-table-view-cell">
									<ul class="mui-table-view mui-grid-view mui-grid-9">
										<li class="mui-table-view-cell mui-col-xs-4 fontsize14">
											商品编号
										</li>
										<li class="mui-table-view-cell mui-col-xs-8 fontsize14">
											222167888
										</li>
										<li class="mui-table-view-cell mui-col-xs-4 fontsize14">
											生产日期
										</li>
										<li class="mui-table-view-cell mui-col-xs-8 fontsize14">
											222167888
										</li>
										<li class="mui-table-view-cell mui-col-xs-4 fontsize14">
											保质期
										</li>
										<li class="mui-table-view-cell mui-col-xs-8 fontsize14">
											222167888
										</li>
									</ul>
								</li>

							<li class="mui-table-view-divider">商品介绍</li>
								<li class="mui-table-view-cell">列表第1项</li>
								<li class="mui-table-view-cell"><span class="mui-ellipsis-2">我的内容比较多，但也不会超过两行，因为加了.mui-ellipsis-2,mui会自动截断，变成省略号</span>
								</li>
							<li class="mui-table-view-divider">使用方法</li>
							<li class="mui-table-view-divider">包装售后</li>
						</ul>

					</div>





					<!--
                    	作者：979071732@qq.com
                    	时间：2017-07-21
                    	描述：客户评价tab页
                    -->
				<!-- 	<div id="item3mobile" class="mui-slider-item mui-control-content p-top">
						<ul class="mui-table-view">
							<li class="mui-table-view-divider color-red">客户1</li>
								<li class="mui-table-view-cell"><span class="mui-ellipsis-2">我的内容比较多，但也不会超过两行，因为加了.mui-ellipsis-2,mui会自动截断，变成省略号</span>
								</li>
							<li class="mui-table-view-divider color-red">客户2</li>
								<li class="mui-table-view-cell"><span class="mui-ellipsis-2">我的内容比较多，但也不会超过两行，因为加了.mui-ellipsis-2,mui会自动截断，变成省略号</span>
								</li>
							<li class="mui-table-view-divider color-red">客户3</li>
								<li class="mui-table-view-cell"><span class="mui-ellipsis-2">我的内容比较多，但也不会超过两行，因为加了.mui-ellipsis-2,mui会自动截断，变成省略号</span>
								</li>
						</ul>
					</div> -->
				</div>
			</div>

	    </div>

		<script type="text/javascript">
			mui.init();
			//获得slider插件对象
			var gallery = mui('#slider1');
			gallery.slider({
			  interval:5000//自动轮播周期，若为0则不自动播放，默认为0；
			});
			var bh = document.body.clientHeight;
			document.getElementById("slider").style.height=689+"px";
            document.getElementById('slider').addEventListener('slide', function(e) {
			//自适应方法就是获得当前的slide数据如：e.detail.slideNumber ，表示当前，再修改id=item里的高度即可
				var x = e.detail.slideNumber+1;
				var h = document.getElementById("item"+x+"mobile").offsetHeight;
				if(h<bh-100 && h>(bh/3)){
					h=h+175;
//					alert(h);
				}else if(h<bh-100 && h<(bh/3)){
					h=h+265;
				}
				document.getElementById("slider").style.height=(h+100)+"px";
			});
		</script>
