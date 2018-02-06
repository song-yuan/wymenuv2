

		<style>
			.mui-scroll-wrapper{margin-bottom: 50px;}
			.mui-grid-view.mui-grid-9{background-color: white;}
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
			.mui-content{background-color: white!important;}
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
				width: 85%;
				position:absolute;
				bottom:4px;
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
		</style>
		<div class="mui-off-canvas-wrap mui-draggable">
			<div class="mui-inner-wrap">

				<header class="mui-bar mui-bar-nav mui-hbar">
				    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" style="color:white;"></a>
				    <h1 class="mui-title" style="color:white;">搜索 * <?php echo $content; ?> * 的结果</h1>
				</header>

				<!-- 产品内容开始 -->
				<div class="mui-content mui-scroll-wrapper">
					<div class="mui-scroll">

						<!-- 分类内容 -->
						<ul class="mui-table-view mui-grid-view mui-grid-9">
							<?php if (!empty($products)): ?>
							<?php foreach ($products as $key => $product):?>
							<li class="ui-table-view-cell mui-media mui-col-xs-6">
								<div class="">
									<div class="">
										<img src="<?php if($product['main_picture']){ echo $product['main_picture'];}else{ echo 'http://menu.wymenu.com/wymenuv2/img/product_default.png';} ?>" style="height: 130px;width: 100%;"/>
									</div>
									<div style="text-align: center;">
											<span style="font-size: 16px;line-height: 16px;color:black;margin-left: 10px;">
												<?php echo $product['goods_name'];?>
											</span>
										</div>
										<div class="cent_footer">
											<div style="text-align: center;height: 16px;overflow: hidden;">
												<span class="color-blue" style="display:inline-block;width:45%;font-size: 14px;line-height: 16px;height: 16px;overflow: hidden;">
													[<?php echo $product['company_name'];?>]
												</span>
												<span style="display:inline-block;width:50%;font-size: 14px;line-height: 16px;height: 16px;color:#666;overflow: hidden;">
													<?php echo $product['unit_name'];?>
												</span>
											</div>
									<div  class="bottom">
										<div class="float-l color-r">¥ <?php echo $product['price'];?></div>
										<div class="float-l " style="margin-left:10px;">/ <?php echo $product['goods_unit'];?></div>
									</div>
								</div>
								<div class="addicon" stock_dpid="<?php echo $product['dpid'];?>" goods_name="<?php echo $product['goods_name'];?>" goods_id="<?php echo $product['glid'];?>"  price="<?php echo $product['price'];?>"  goods_code="<?php echo $product['goods_code'];?>" material_code="<?php echo $product['material_code'];?>">加入购物车</div>
							</li>
							<?php endforeach; ?>
							<?php endif; ?>
						</ul>
					</div>
				</div>
				<!-- 产品内容结束 -->
				<!-- off-canvas backdrop -->
				<div class="mui-off-canvas-backdrop"></div>
			</div>
		</div>
		<script>

			mui.init();
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
		</script>