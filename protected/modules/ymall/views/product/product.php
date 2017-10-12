		<style>
			.mui-scroll-wrapper{margin-bottom: 50px;}
			.mui-grid-view.mui-grid-9{background-color: white;}
			input[type=search]{background-color: white;}
			.search-form{
				width: 70%;
				margin-right:5%;
				float: right;
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
	margin: 0 0 1.5em;
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
	content: attr(data-value) '件';
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
		</style>

		<div class="mui-off-canvas-wrap mui-draggable">
			<div class="mui-inner-wrap"  id="Main">
				<header class="mui-bar mui-bar-nav">
					<!-- <a class="mui-icon mui-icon-left-nav mui-pull-left"></a> -->
					<a id="mui-popover1" class="mui-icon mui-icon-download mui-pull-right"></a>
					<!-- <h1 class="mui-title">首页</h1> -->
					<img src="../../../../../../img/ydclogo2.png" alt="" style="width: 15%;height: 36px;padding: 3px 0 2px 0;">
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
						<div id="slider" class="mui-slider">
							<div class="mui-slider-group mui-slider-loop">
							<!--支持循环，需要重复图片节点   最后一张图-->
								<div class="mui-slider-item mui-slider-item-duplicate">
									<a href="#">
										<img src="http://dcloudio.github.io/mui/assets/img/yuantiao.jpg">
									</a>
									<p class="mui-slider-title">
										静静看这世界
									</p>
								</div>

								<!-- 主体循环的图片开始 -->
								<div class="mui-slider-item">
									<a href="#">
										<img src="http://dcloudio.github.io/mui/assets/img/shuijiao.jpg">
									</a>
									<p class="mui-slider-title">
										幸福就是可以一起睡觉
									</p>
								</div>
								<div class="mui-slider-item">
									<a href="#">
										<img src="http://dcloudio.github.io/mui/assets/img/muwu.jpg">
									</a>
									<p class="mui-slider-title">
										想要一间这样的木屋，静静的喝咖啡
									</p>
								</div>
								<div class="mui-slider-item">
									<a href="#">
										<img src="http://dcloudio.github.io/mui/assets/img/cbd.jpg">
									</a>
									<p class="mui-slider-title">
										Color of SIP CBD
									</p>
								</div>
								<div class="mui-slider-item">
									<a href="#">
										<img src="http://dcloudio.github.io/mui/assets/img/yuantiao.jpg">
									</a>
									<p class="mui-slider-title">
										静静看这世界
									</p>
								</div>
								<!-- 主体循环的图片结束 -->


								<!--支持循环，需要重复图片节点     第一张图-->
								<div class="mui-slider-item mui-slider-item-duplicate">
									<a href="#">
										<img src="http://dcloudio.github.io/mui/assets/img/shuijiao.jpg">
									</a>
									<p class="mui-slider-title">
										幸福就是可以一起睡觉
									</p>
								</div>
							</div>
							<div class="mui-slider-indicator mui-text-right">
								<div class="mui-indicator mui-active"></div>
								<div class="mui-indicator"></div>
								<div class="mui-indicator"></div>
								<div class="mui-indicator"></div>
							</div>
						</div>
						<div >
							<h4>店铺原料库存</h4>
							<!-- HTML5 -->
							<?php if($stocks): ?>
							<?php foreach ($stocks as $stock): ?>
						    <p style="width:90%;margin:0;" data-value="100"><?php echo $stock['material_name'] ?></p>
							<progress max="1000" value="900" class="html5">
								<div class="progress-bar">
									<!-- <span style="width: 80%"></span> -->
								</div>
							</progress>
							<?php endforeach; ?>
							<?php endif; ?>
						</div>
						<?php if($materials):?>
						<?php foreach ($materials as $key => $products):?>
						<!-- 分类名称 -->
						<ul class="mui-table-view mui-table-view-chevron">
							<li class="mui-table-view-cell mui-cell" id="cate<?php echo $key; ?>">
								<?php echo $products[0]['category_name']; ?>
							</li>
						</ul>
						<!-- 分类内容 -->
						<ul class="mui-table-view mui-grid-view mui-grid-9">

							<?php foreach ($products as $m):?>
								<li class="ui-table-view-cell mui-media mui-col-xs-6">
									<div class="">
										<div class="goods-pic">
											<img src="<?php echo 'http://menu.wymenu.com/'.$m['main_picture']?>" style="height: 130px;"/>
										</div>
										<div><span class="color-blue">[<?php echo $m['company_name'];?>]</span><?php echo $m['goods_name'];?></div>
										<div class="bottom">
											<div class="float-l color-r">￥ <?php echo $m['original_price'];?></div>
											<div class="float-l "><?php echo $m['goods_unit'];?></div>
											<div class="float-r  color-r ">
												<div class="addicon" stock_dpid="<?php echo $m['dpid'];?>" goods_name="<?php echo $m['goods_name'];?>" goods_id="<?php echo $m['glid'];?>"  price="<?php echo $m['original_price'];?>"  goods_code="<?php echo $m['goods_code'];?>" material_code="<?php echo $m['material_code'];?>">+</div>
											</div>
										</div>
									</div>
								</li>
							<?php endforeach;?>
						</ul>
						<?php endforeach;?>
						<?php else: ?>
						<ul class="mui-table-view mui-grid-view mui-grid-9">
								<li class="ui-table-view-cell mui-media">
									没有查询到您店铺附近的仓库 , 或者仓库中没有货品 , 请到<我的>点击<联系总部客服> , 向品牌总部咨询 .
								</li>
						</ul>
						<?php endif;?>
					</div>
				</div>
				<!-- 产品内容结束 -->
			</div>
		</div>
		<script src="<?php echo  Yii::app()->request->baseUrl; ?>/js/ymall/prefixfree.min.js"></script>
		<script>
			//采购订单生成对话框
			mui('#Main .mui-bar').on('tap','#mui-popover1',function(){
				var btnArray = ['是','否'];
				mui.confirm('自动生成采购单是根据您设置的时间段内的销量 , 通过算法自动生产的符合您店铺的采购订单 ,是否确定生成订单 ？','自动生成采购单',btnArray,function(e){
					if(e.index==0){
						//自己的逻辑
						mui.alert('已经生成采购单 , 请到购物车再次确认采购单是否合适 ! ! !','提示',function(){});
					}else{
						alert('点击了- 否');
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
			$('.addicon').on('touchstart',function(){
				$(this).addClass('addicon1');
				$('.mui-badge').addClass('mui-badge1');
			});
			$('.addicon').on('touchend',function(){
				$(this).removeClass('addicon1');
				$('.mui-badge').removeClass('mui-badge1');

				
				var stock_dpid = $(this).attr('stock_dpid'); //仓库的dpid
				var goods_name = $(this).attr('goods_name'); //产品名称
				var goods_id = $(this).attr('goods_id'); //产品id
				var price = $(this).attr('price'); //产品原价
				var goods_code = $(this).attr('goods_code'); //产品代码
				var material_code = $(this).attr('material_code'); //原料代码
				// alert(price);
				var num = $('#car_num').text();
				//alert(num);
				var nums = parseInt(num) +1 ;
				$('#car_num').html(nums);
				mui.post('<?php echo $this->createUrl("ymallcart/addymallcart",array("companyId"=>$this->companyId)) ?>',{  //请求接口地址
					   stock_dpid:stock_dpid, // 参数  键 ：值
					   goods_name:goods_name,
					   goods_id:goods_id,
					   price:price,
					   goods_code:goods_code,
					   material_code:material_code
					},
					function(data){ //data为服务器端返回数据
						//自己的逻辑
						console.log(data);
						if (data != nums) {
							$('#car_num').html(data);
						}
					},'json'
				);
			});
			$('.goods-pic img').on('tap',function(){
				// $(this).addClass('addicon1');
				location.href="<?php echo $this->createUrl('productdetail/productdetail',array('companyId'=>$this->companyId))?>" ;
			});
			$('.search-form').submit(function(event) {
				var content = $('#search').val();
				// alert(content);
				if (content == '') {
					event.preventDefault();
					mui.alert("请填写搜索内容 ! ! !");
				}
			});
		</script>