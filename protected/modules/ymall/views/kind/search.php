

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
			    height:229px;
			}
			.bottom{
				width: 85%;
				position:absolute;
				bottom:8px;
			}
			.addicon1{
				width: 25px!important;
				height: 25px!important;
				background-color: red;
				border-radius: 25px!important;
				color: #fff;
				vertical-align: middle;
				text-align: center;
				align-items: center;
				line-height: 25px!important;
				font-size: 25px!important;
				font-weight: 600;
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
									<div class="goods-pic">
										<img src="<?php if($product['main_picture']){ echo 'http://menu.wymenu.com/'.$product['main_picture'];}else{ echo 'http://menu.wymenu.com/wymenuv2/img/product_default.png';} ?>" style="height: 130px;"/>
									</div>
									<div><span class="color-blue">[<?php echo $product['company_name'];?>]</span><?php echo $product['goods_name'];?></div>
									<div  class="bottom">
										<div class="float-l color-r">￥ <?php echo $product['original_price'];?></div>
										<div class="float-l " style="margin-left:10px;"><?php echo $product['goods_unit'];?></div>
										<div class="float-r  color-r ">
												<div class="addicon" stock_dpid="<?php echo $product['dpid'];?>" goods_name="<?php echo $product['goods_name'];?>" goods_id="<?php echo $product['glid'];?>"  price="<?php echo $product['original_price'];?>"  goods_code="<?php echo $product['goods_code'];?>" material_code="<?php echo $product['material_code'];?>">+</div>
										</div>
									</div>
								</div>
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