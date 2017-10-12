
		<style>
			.big-ul{margin-bottom: 100px;margin-top:44px!important;}
			.big-li{padding:1px 0 0 0;background-color: #EFEFF4!important;}
			.div-store{margin-bottom: 2px;height: 35px;}
			.a-store{font-size: 20px;color:#EC971F;height:35px;line-height: 35px;}
			.edit{margin-top:11px;z-index: 1;}
			.img-show{width: 98px;height:98px;margin-left: -14px;margin-right: 10px;}
			.nav-none{margin-bottom: 50px;display: none;}
			.nav-on{margin-bottom: 50px;}
			.ui-bar {
				position: fixed;
				z-index: 10;
				right: 0;
				left: 0;
				height: 44px;
				padding-right: 10px;
				padding-left: 10px;
				border-bottom: 0;
				background-color: #f7f7f7;
				-webkit-box-shadow: 0 0 1px rgba(0,0,0,.85);
				box-shadow: 0 0 1px rgba(0,0,0,.85);
				-webkit-backface-visibility: hidden;
				backface-visibility: hidden;
			}
			.ui-bar-tab {
				bottom: 0;
				display: table;
				width: 100%;
				height: 50px;
				padding: 0;
				table-layout: fixed;
				border-top: 0;
				border-bottom: 0;
				-webkit-touch-callout: none;
			}
			.ui-tab-item {
				display: table-cell;
				overflow: hidden;
				width: 1%;
				height: 50px;
				text-align: center;
				vertical-align: middle;
				white-space: nowrap;
				text-overflow: ellipsis;
				color: #929292;
			}
			.mui-numbox{width: 160px!important;}
			.ui-table-view-cell{padding:15px 0!important;border-bottom: 1px solid #f0f0e4;}
			.goods_product{top:34px!important;}
			.cblack{color:#2F4F4F;}
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
		<div id="cart1" class="mui-page">
			<!--页面标题栏开始-->
			<div class="mui-navbar-inner mui-bar mui-bar-nav">
				<button type="button" class="mui-left mui-action-back mui-btn  mui-btn-link mui-btn-nav mui-pull-left">
					<span class="mui-icon mui-icon-left-nav"></span>
				</button>
				<h1 class="mui-center mui-title">购物车</h1>
				<a class="mui-pull-right edit" id="edit">编辑</a>
			</div>
			<!--页面标题栏结束-->
			<!--页面主内容区开始-->
			<div class="mui-page-content">
			<ul class="mui-table-view big-ul">

			<?php if (!empty($materials)): ?>
			    <?php foreach ($materials as $key => $products): ?>
			    <li class="mui-table-view-cell big-li">
			    	<div class="mui-row" style="height: 40px;background: #FaFaFa;">
				    	<div class="mui-col-xs-2 mui-checkbox" style="height: 35px;">
				    		<input name="checkbox" type="checkbox" class="selectlist">
				    	</div>
				    	<div class="mui-col-xs-10 ">
				    		<a class="mui-navigate-right a-store"><?php echo $products[0]['company_name']; ?></a>
				    	</div>
			    	</div>
			        <ul class="mui-table-view" id="">
			        	<?php foreach ($products as $product):?>
					    <li class="mui-row  mui-media ui-table-view-cell">
					    	<div class="mui-col-xs-2 mui-checkbox">
					    		<input name="goods_cart_id" value=" <?php echo $product['lid']; ?>" type="checkbox" class="goods_product">
					    	</div>
					    	<div class="mui-col-xs-10" >
				    			<a href="<?php echo $this->createUrl('productdetail/productdetail',array('companyId' =>$this->companyId , )); ?>">
					            	<img class=" mui-pull-left img-show" src="<?php echo  'http://menu.wymenu.com/'.$product['main_picture']; ?>" >
						        </a>
					            <div class="mui-media-body">
					                <a href="<?php echo $this->createUrl('productdetail/productdetail',array('companyId' =>$this->companyId , )); ?>">
					                	<span class="cblack"><?php echo $product['goods_name']; ?></span>
					                </a>
					                <p class='mui-ellipsis'><?php echo $product['description']?$product['description']:'操作员偷懒,没有描述'; ?></p>
					                <span style="color:darkslategray; display: block;">单价
					                <span style="color: red;" class="price">
					                <?php
					                	//显示goods表的原始价格 , 会员价暂时未考虑
						                echo $product['now_price'];
					                ?>
					                </span>元
					                <?php if ($product['now_price'] < $product['price']) :
					                //如果当前价格低于加入购物车时的价格,显示向下的箭头
					                ?>
					                <span class="mui-icon mui-icon-pulldown" style="color:greenyellow;"></span>
					            	<?php endif; ?>
					                </span>
					                <div class="mui-numbox mui-right " data-numbox-step='1' data-numbox-min='0' data-numbox-max='<?php echo 10000;//$product['store_number']; ?>'>
									  <button class="mui-btn mui-numbox-btn-minus" type="button">-</button>
									  <input class="mui-numbox-input" type="number" value="<?php echo $product['num']; ?>" readonly = "readonly" />
									  <button class="mui-btn mui-numbox-btn-plus" type="button">+</button>
									</div>
									<span style="color:darkslategray;"><?php echo $product['goods_unit']; ?></span>
					            </div>
					    	</div>
					    </li>
						<?php endforeach; ?>
					</ul>
			    </li>
				<?php endforeach; ?>
			<?php else: ?>
			    <li class="mui-table-view-cell big-li">
			    	<div class="mui-row" >
				    	<div class="mui-col-xs-12 " style="height: 80px;line-height: 80px;text-align: center;">
				    		<a class="a-store" >您的购物车是空的 ! ! !</a>
				    	</div>
			    	</div>
			    </li>
			<?php endif; ?>
			</ul>
		    <nav class="mui-bar mui-bar-tab nav-on" id="gopay" >
		        <div class="ui-tab-item " style="width:25%;">
		            <div class="mui-input-row mui-checkbox mui-left">
					  <label style="padding:11px 15px;text-align: right;" for="selectall">全选</label>
					  <input id="selectall" type="checkbox">
					</div>
		        </div>
		        <div class="ui-tab-item cblack" style="width:40%;">
		            	合计:￥<span style="color: red;margin-right: 10px;padding-right: 10px;" class="all_price">0.00</span>
		        </div>
		        <div class="ui-tab-item " style="width:25%;">
		            <button type="button" id="suretopay" class="mui-btn mui-btn-blue mui-btn-block" style="margin-bottom: 10px;">去结算</button>
		        </div>
		    </nav>
		    <nav class="mui-bar mui-bar-tab nav-none" id="godelete">
		        <div class="ui-tab-item " style="width:25%;">
		            <div class="mui-input-row mui-checkbox mui-left">
					  <label style="padding:11px 15px;text-align: right;" for="selectall1">全选</label>
					  <input id="selectall1" type="checkbox">
					</div>
		        </div>
		        <div class="ui-tab-item " style="width:32.5%;">
		        	<button type="button" class="mui-btn mui-btn-blue mui-btn-block" id="save" style="margin-bottom: 10px;">保存</button>
		        </div>
		        <div class="ui-tab-item " style="width:32.5%;">
		            <button type="button" class="mui-btn mui-btn-red mui-btn-block"  id="delete" style="margin-bottom: 10px;">删除</button>
		        </div>
		    </nav>

		</div>
		</div>

		<script type="text/javascript">
			mui('.mui-numbox').numbox();
			// mui('.mui-page-content').scroll();

			$("document").ready(function(){
				$("input.mui-numbox-input,button.mui-numbox-btn-plus,button.mui-numbox-btn-minus").attr('disabled','disabled');
				$("#edit").click(function(){
//					alert($("#edit").text());
					if($("#edit").text()=='编辑'){
						$("#gopay").removeClass("nav-on").addClass("nav-none");
						$("#godelete").removeClass("nav-none").addClass("nav-on");
						$("input.mui-numbox-input,button.mui-numbox-btn-plus,button.mui-numbox-btn-minus").removeAttr('disabled');
						$("input.mui-numbox-input").removeAttr('readonly');
						$("#edit").text('完成');
						$("input[type='checkbox']").each(function(){this.checked=false;});
					}else if($("#edit").text()=='完成'){
						if ($("input[name='goods_cart_id']:checked").length > 0) {
							mui.alert('数据已改变 , 请点击保存 ! ! !')
						}else{
							$("#gopay").removeClass("nav-none").addClass("nav-on");
							$("#godelete").removeClass("nav-on").addClass("nav-none");
							$("button.mui-numbox-btn-plus,button.mui-numbox-btn-minus").attr('disabled','disabled');
							$("input.mui-numbox-input").attr('readonly','readonly');
							$("#edit").text('编辑');
							$("input[type='checkbox']").each(function(){this.checked=false;});
						}
					}
				});
				//编辑时,数量改变自动选中
				$("input[type='number']").change(function(){
					$(this).parent('.mui-numbox').parent('.mui-media-body').parent('.mui-col-xs-10').prev('.mui-col-xs-2').children('.goods_product').attr('checked','checked');
					// this.checked=true;
				});



				$("#selectall").click(function(){
					// alert('111');
				    if(this.checked){
				    	var all_price = new Number;
				    	$("input[type='checkbox']").each(function(){
				    		this.checked=true;
				    		var num = $(this).parent().next('.mui-col-xs-10').children('.mui-media-body').children('.mui-numbox').children("input[type='number']").val();
							var price = $(this).parent().next('.mui-col-xs-10').children('.mui-media-body').children('span').children('.price').text();
							// console.log('num'+num+'price'+price);
							if(num != undefined){
								all_price += parseInt(num)*parseFloat(price);
							}
				    	});
				    	$('.all_price').html(all_price.toFixed(2));
				    	// $("input[name='goods_cart_id']").each(function(){this.checked=true;});
				  	}else{
				   		$("input[type='checkbox']").each(function(){this.checked=false;});
				   		$('.all_price').html('0.00');
				   		// $("input[name='goods_cart_id']").each(function(){this.checked=false;});
				  	}
				});


				$("#selectall1").click(function(){
					// alert('222');
					if(this.checked){
				 		$("input[type='checkbox']").each(function(){this.checked=true;});
				 	}else{
				    	$("input[type='checkbox']").each(function(){this.checked=false;});
				  	}
				});

				$('.selectlist').click(function(){
					if(this.checked){
						var all_price = new Number;
				 		$(this).parent().parent().next('ul').children('li').children('.mui-checkbox').children('input').each(function(){
				 			this.checked=true;
				 			var num = $(this).parent().next('.mui-col-xs-10').children('.mui-media-body').children('.mui-numbox').children("input[type='number']").val();
							var price = $(this).parent().next('.mui-col-xs-10').children('.mui-media-body').children('span').children('.price').text();
							all_price += parseInt(num)*parseFloat(price);
				 		});
				 		$('.all_price').html(all_price.toFixed(2));
				 	}else{
				    	$(this).parent().parent().next('ul').children('li').children('.mui-checkbox').children('input').each(function(){this.checked=false;});
				    	var all_price = new Number;
						$("input[name='goods_cart_id']").each(function(){
							// alert(this.checked);
							if (this.checked) {
								var num = $(this).parent().next('.mui-col-xs-10').children('.mui-media-body').children('.mui-numbox').children("input[type='number']").val();
								var price = $(this).parent().next('.mui-col-xs-10').children('.mui-media-body').children('span').children('.price').text();
								all_price += parseInt(num)*parseFloat(price);
							}
						});
						$('.all_price').html(all_price.toFixed(2));
				  	}
				});
			 	$("input[name='goods_cart_id']").click(function(){
			 		var linum= $(this).parent().parent().parent().children('li').size();
			 		var checkednum = $(this).parent().parent().parent().children('li').children('.mui-checkbox').children('input:checked').size();

			 		if(linum==checkednum){
			 			$(this).parent().parent().parent().prev().children('.mui-checkbox').children('input').attr('checked','checked');
			 		}else if(linum!=checkednum){
			 			$(this).parent().parent().parent().prev().children('.mui-checkbox').children('input').removeAttr('checked');
			 		}
			 	});

			});
			//保存修改的原料数量
			$("#save").click(function(){
				// alert('222');
				// var goods_num_edit = [];
				var isempty = true;
				var goods_num_edit = new Array();
				var i = 0;
		 		$("input[name='goods_cart_id']").each(function(){
					if(this.checked){
						isempty = false;
						var goods_num = $(this).parent().next().children('.mui-media-body').children('.mui-numbox').children("input[type='number']").val();
						var goods_cart_id = parseInt($(this).val());
						// goods_num_edit[goods_cart_id] =goods_num;
						goods_num_edit[i] = goods_cart_id+'_'+goods_num;
						i++;
				 	}
		 		});
		 		var goods_num_edit = goods_num_edit.join(",");
				// console.log(goods_num_edit);
				if (isempty) {
					mui.alert('请选择要保存的商品 ! ! !');
				}else{
					mui.post('<?php echo $this->createUrl("ymallcart/editymallcart",array("companyId"=>$this->companyId)) ?>',{
						   goods_num_edit:goods_num_edit,
						},
						function(data){
							if (data == 1) {
								mui.alert('保存成功 ! ! !');
								$("input[type='checkbox']").each(function(){this.checked=false;});//取消选中
							}else if(data == 2) {
								mui.alert('因网络原因保存失败 , 请重新保存 ! ! !');
							}else if(data == 3) {
								mui.alert('未查寻到商品保存失败 ! ! !');
							}
						},'json'
					);
				}
			});

			//保存修改的原料数量
			$("#delete").click(function(){
				// alert('222');
				// var goods_num_edit = [];
				var isempty = true;
				var goods_num_edit = new Array();
				var i = 0;
		 		$("input[name='goods_cart_id']").each(function(){
					if(this.checked){
						isempty = false;
						var goods_num = $(this).parent().next().children('.mui-media-body').children('.mui-numbox').children("input[type='number']").val();
						var goods_cart_id = parseInt($(this).val());
						// goods_num_edit[goods_cart_id] =goods_num;
						goods_num_edit[i] = goods_cart_id+'_'+goods_num;
						i++;
					}
		 		});
		 		var goods_num_edit = goods_num_edit.join(",");
				// console.log(goods_num_edit);
				if (isempty) {
					mui.alert('请选择要删除的商品 ! ! !');
				}else{
					var btnArray = ['是','否'];
					mui.confirm('是否确定删除所选产品 ？','提示',btnArray,function(e){
						if(e.index==0){
							//自己的逻辑
							mui.post('<?php echo $this->createUrl("ymallcart/delete",array("companyId"=>$this->companyId)) ?>',{
								   goods_num_edit:goods_num_edit,
								   delete:1,
								},
								function(data){
									if (data == 1) {
										$("input[type='checkbox']:checked").each(function(){
												$(this).parent('.mui-col-xs-2').parent('.mui-row').fadeOut(1000).remove();
												// alert(x);
										});
										//将图标的数量减去
										var num = $('#car_num').html();
										$('#car_num').html(num-i);
										mui.alert('删除成功 ! ! !');
									}else if(data == 2) {
										mui.alert('因网络原因删除失败 , 请重新删除 ! ! !');
									}
								},'json'
							);
						}else{
							//否
						}
					});
					
				}
			});
			$("input[type='checkbox']").change(function(){
				var all_price = new Number;
				$("input[name='goods_cart_id']").each(function(){
					// alert(this.checked);
					if (this.checked) {
						var num = $(this).parent().next('.mui-col-xs-10').children('.mui-media-body').children('.mui-numbox').children("input[type='number']").val();
						var price = $(this).parent().next('.mui-col-xs-10').children('.mui-media-body').children('span').children('.price').text();
						all_price += parseInt(num)*parseFloat(price);
					}
				});
				$('.all_price').html(all_price.toFixed(2));
				// alert(all_price);
			});
			var button = document.getElementById('suretopay');
			button.addEventListener('tap',function(){
				if ($("input[name='goods_cart_id']:checked").length>0) {
					var lid = [];
					var x = 0;
					$("input[name='goods_cart_id']:checked").each(function() {
						lid[x] = $(this).val();
						x++;
					});
					console.log(lid);
					$('#suretopay').attr('disabled','disabled');//防止点击多次
					lid = lid.join(",");
					location.href="<?php echo $this->createUrl('ymallcart/addgoodsorder',array('companyId'=>$this->companyId));?>/lid/"+lid;
				} else{
					mui.alert('请选择需要结算的商品 !!!')
				}
		    });
		</script>


