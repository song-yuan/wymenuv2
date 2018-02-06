		<style>
			.wrapper{margin-bottom: 50px;margin-top:79px;}
			.mui-grid-view.mui-grid-9{background-color: white;}
			input[type=search]{background-color: white;}
			.search-form{
				height: 34px;
				/*margin:5px 0px;*/
			}
			.color-blue{
				color:darkblue;
			}
			.mui-content{background-color: white!important;}
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
			    height:212px;
			}
			.bottom{
				
				position:absolute;
				bottom:4px;
				font-size:0.9em;
			}
			.addicon{
				position:absolute;
				right:6px;
				bottom:4px;
				width: 64px;
				height: 20px;
				line-height: 20px;
				font-size: 12px;
				border-radius: 5px;
			    background-color: red;
			    color: #fff;
			    vertical-align: middle;
			    text-align: center;
			    align-items: center;
			    font-weight: 600;
			}
			.addicon1{
				background-color: darkred!important;
			}
			.mui-badge{
				font-size: 12px!important;
			}
			.mui-badge1{
				font-size: 16px!important;
			}
			.mui-cell{background-color: #EFEFF4!important;}
			.ui-table-view-cell {
				position: relative;
				overflow: hidden;
				padding: 6px 15px;
				-webkit-touch-callout: none;
			}
			.mui-off-canvas-left, .mui-off-canvas-right {
			    width: 55%;
			}
			.mui-toast-container{bottom: 50%!important;}
			.cent_footer{background-color: #FBF3F6; height: 60px;width: 120%;margin-left:-14px;}
		</style>
		<div class="mui-off-canvas-wrap mui-draggable">
			<div class="mui-inner-wrap" id="Main">
				<header class="mui-bar mui-bar-nav mui-hbar">
					<!-- <a class="mui-icon mui-icon-left-nav mui-pull-left"></a> -->
					<button type="button" class="mui-left mui-action-back mui-btn  mui-btn-link mui-btn-nav mui-pull-left">
					<span class="mui-icon mui-icon-left-nav" style="color:white;"></span>
				</button>
					<a href="#offCanvas" class="mui-icon mui-icon-bars mui-pull-right" style="color:white;"></a>
					<h1 class="mui-title" style="color:white;">分类</h1>
				</header>
				<!-- 侧滑导航开始 -->
				<aside id="offCanvas" class="mui-off-canvas-left ">
					<div class=" mui-scroll-wrapper">
						<ul class="mui-table-view mui-table-view-chevron mui-table-view-inverted mui-scroll ">
							<?php if($materials):?>
							<?php foreach ($materials as $key => $products):?>
								<li class="mui-table-view-cell mui-cell1" href1="#cate<?php echo $key; ?>">
									<a class="mui-navigate-right" >
										<?php echo $products[0]['category_name'];;?>
									</a>
								</li>
							<?php endforeach;?>
							<?php endif;?>
						</ul>
					</div>
				</aside>
				<!-- 侧滑导航结束 -->
				<!-- 产品内容开始 -->
				<div class="mui-content " >
					<form action=" <?php echo  $this->createUrl('kind/search',array('companyId'=>$this->companyId)); ?>" class="search-form" method="POST">
						<div class="mui-input-row mui-search">
							<input type="search" id="search" class="mui-input-clear" name="content" placeholder="搜索产品">
						</div>
					</form>
				<div class="mui-scroll-wrapper wrapper" id="kinds">
					<div class="mui-scroll">
						<?php if($materials):?>
						<?php foreach ($materials as $key => $products):?>
						<!-- 分类名称 -->
						<ul class="mui-table-view mui-table-view-chevron">
							<li class="ui-table-view-cell mui-cell" id="cate<?php echo $key; ?>">
								<span><?php echo $products[0]['category_name']; ?></span>
							</li>
						</ul>
						<!-- 分类内容 -->
						<ul class="mui-table-view mui-grid-view mui-grid-9">

							<?php foreach ($products as $m):?>
								<li class="ui-table-view-cell mui-media mui-col-xs-6">
									<div class="">
										<div class="">
											<img src="<?php if($m['main_picture']){ echo $m['main_picture'];}else{ echo 'http://menu.wymenu.com/wymenuv2/img/product_default.png';} ?>" style="height:130px; width: 100%;"/>
										</div>
										<div style="text-align: center;">
											<span style="font-size: 16px;line-height: 16px;color:black;margin-left: 10px;">
												<?php echo $m['goods_name'];?>
											</span>
										</div>
										<div class="cent_footer">
											<div style="text-align: center;height: 16px;overflow: hidden;">
												<span class="color-blue" style="display:inline-block;width:45%;font-size: 14px;line-height: 16px;height: 16px;overflow: hidden;">
													[<?php echo $m['company_name'];?>]
												</span>
												<span style="display:inline-block;width:50%;font-size: 14px;line-height: 16px;height: 16px;color:#666;overflow: hidden;">
													<?php echo $m['unit_name'];?>
												</span>
											</div>
											<div class="bottom" >
												<div class="float-l color-r" style="margin-left: 14px;">￥<?php echo $m['price'];?></div>
												<div class="float-l " style="">/ <?php echo $m['goods_unit'];?></div>
											</div>
										</div>
									</div>
									<div class="addicon" stock_dpid="<?php echo $m['dpid'];?>" goods_name="<?php echo $m['goods_name'];?>" goods_id="<?php echo $m['glid'];?>"  price="<?php echo $m['price'];?>"  goods_code="<?php echo $m['goods_code'];?>" material_code="<?php echo $m['material_code'];?>">加入购物车</div>
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
				</div>
				<!-- 产品内容结束 -->
				<!-- off-canvas backdrop -->
				<div class="mui-off-canvas-backdrop"></div>
			</div>
		</div>
		<script>


			//初始化单页的区域滚动
			mui('.mui-scroll-wrapper').scroll();
			mui('#offCanvas .mui-scroll-wrapper .mui-table-view').on('tap','li.mui-cell1',function(){
				var Main = mui('#Main');//侧滑容器父节点
				var href1 = $(this).attr('href1');
				var top = $(href1).offset().top - 44 - 34;
				// alert(top);
				var current_top = mui('#kinds').scroll().y;
				top = current_top - top;
				mui('#kinds').scroll().scrollTo(0,top,300);
				Main.offCanvas('close');
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
				// mui.alert(num);
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

			//查看商品详情
			// $('.goods-pic img').on('tap',function(){
			// 	location.href="<?php echo $this->createUrl('productdetail/productdetail',array('companyId'=>$this->companyId))?>" ;
			// });

			//搜索为空阻止提交
			$('.search-form').submit(function(event) {
				var content = $('#search').val();
				if (content == '') {
					event.preventDefault();
					mui.toast("请填写搜索内容 ! ! !",{ duration:'long', type:'div' });
				}
			});

		</script>