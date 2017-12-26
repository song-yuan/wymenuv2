
		<style>
			.big-ul{margin-bottom: 100px;margin-top:44px!important;}
			.big-li{padding:1px 0 0 0;background-color: #EFEFF4!important;}
			.div-store{margin-bottom: 2px;height: 35px;}
			.a-store{
				font-size: 20px;
				font-weight:900;
				color:darkblue;
				height:35px;
				line-height: 35px;
			}
			.edit{margin-top:11px;z-index: 1;}
			.img-show{width: 98px;height:98px;margin-left: -14px;margin-right: 10px;border-radius: 10px;}
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
				color: #666;
			}
			.mui-numbox{
				width: 140px!important;
				border: solid 1px #fff;
			}
			.mui-numbox .mui-input-numbox, .mui-numbox .mui-numbox-input {
				 border-right: solid 1px #fff!important; 
				 border-left: solid 1px #fff!important; 
			}
			.ui-table-view-cell{padding:15px 0!important;border-bottom: 1px solid #f0f0e4;}
			.goods_product{top:34px!important;}
			.cblack{color:#2F4F4F;}
			/*.mui-content{background-color: white!important;}*/
			.mui-table-view .mui-media, .mui-table-view .mui-media-body {
				overflow: visible;
				position: relative;
			}
			.mui-toast-container{bottom: 50%!important;}

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
			<div class="mui-navbar-inner mui-bar mui-bar-nav mui-hbar">
				<button type="button" class="mui-left mui-action-back mui-btn  mui-btn-link mui-btn-nav mui-pull-left">
					<span class="mui-icon mui-icon-left-nav" style="color:white;"></span>
				</button>
				<h1 class="mui-center mui-title" style="color:white;">购物车</h1>
				<a class="mui-pull-right edit" id="edit" style="color:white;">编辑</a>
			</div>
			<!--页面标题栏结束-->
			<!--页面主内容区开始-->
			<div class="mui-page-content">
			<div class="mui-content mui-scroll-wrapper" style="background-color: #FBF3F0!important;">
			<div class="mui-scroll">
			<ul class="mui-table-view big-ul" >
				<?php if (!empty($materials)): ?>
				    <?php foreach ($materials as $key => $products): ?>
				    <li class="mui-table-view-cell big-li">
				    	<div class="mui-row" style="height: 40px;background: #FaFaFa;">
					    	<div class="mui-col-xs-2 mui-checkbox" style="height: 35px;">
					    		<input name="checkbox" type="checkbox" class="selectlist">
					    	</div>
					    	<div class="mui-col-xs-10 ">
					    		<a class="mui-navigate-right a-store">
					    		<img style="width: 25px;height: 25px;vertical-align: text-bottom;" src="<?php echo  Yii::app()->request->baseUrl; ?>/img/cangku.png" /> 
					    		<?php echo $products[0]['company_name']; ?></a>
					    	</div>
				    	</div>
				        <ul class="mui-table-view" id="">
				        	<?php foreach ($products as $product):?>
						    <li class="mui-row  mui-media ui-table-view-cell" style="background-color: #FBF3F0;">
						    	<div class="mui-col-xs-2 mui-checkbox">
						    		<input name="goods_cart_id" value="<?php echo $product['lid']; ?>" type="checkbox" class="goods_product">
						    	</div>
						    	<div class="mui-col-xs-10" >
					    			<a href1="<?php echo $this->createUrl('productdetail/productdetail',array('companyId' =>$this->companyId , )); ?>">
						            	<img class=" mui-pull-left img-show" src="<?php if($product['main_picture']){ echo $product['main_picture'];}else{ echo 'http://menu.wymenu.com/wymenuv2/img/product_default.png';} ?>" >
							        </a>
						            <div class="mui-media-body">
						                <a href1="<?php echo $this->createUrl('productdetail/productdetail',array('companyId' =>$this->companyId , )); ?>">
						                	<span class="cblack"><?php echo $product['goods_name']; ?></span>
						                	<span class="mui-icon mui-icon-compose mui-pull-right edited" style="color:#666;margin-right: 10px;z-index: 1;"></span>
						                </a>
						                <p class='mui-ellipsis' style="color: purple;"><?php echo $product['unit_name']; ?></p>
						                <span style="color:darkslategray; display: block;"><b>￥</b>
						                <span style="color: red;" class="price">
						                <?php
						                	//显示goods表的原始价格 , 会员价暂时未考虑
							                echo $product['now_price'];
						                ?>
						                </span> / <span style="color:darkslategray;"><?php echo $product['goods_unit']; ?></span>
						                </span>
						                <div class="mui-numbox mui-right " data-numbox-step='1' data-numbox-min='1' data-numbox-max='<?php echo 10000;//$product['store_number']; ?>'>
										  <button class="mui-btn mui-numbox-btn-minus" type="button">-</button>
										  <input class="mui-numbox-input" type="number" value="<?php echo $product['num']; ?>" readonly = "readonly" />
										  <button class="mui-btn mui-numbox-btn-plus" type="button">+</button>
										</div>
										<button class="saved" style="height: 100px;border:0;position: absolute;right: 0;top: 0px;color:#fff;background: orange;display: none;" value="<?php echo $product['lid']; ?>">完成</button>
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
					    	<div class="mui-col-xs-12 " style="height: 80px;line-height: 80px;text-align: center;background-color: white;">
					    		<a class="a-store" >您的购物车是空的 ! ! !</a>
					    	</div>
				    	</div>
				    </li>
				<?php endif; ?>
			</ul>
			</div>
			</div>
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
			mui('.mui-scroll-wrapper').scroll();


			//单个编辑
			mui('.mui-media-body a').on('tap','.edited',function(){
				$(this).parent().parent().children(".mui-numbox.mui-right").children("input.mui-numbox-input,button.mui-numbox-btn-plus,button.mui-numbox-btn-minus").removeAttr('disabled');
				$(this).parent().parent().children(".saved").css('display', 'block');
				$(this).parent().parent().children(".mui-numbox.mui-right").children("input.mui-numbox-input").removeAttr('readonly');
			});
			//单个保存
			$(".saved").click(function(){
				// alert('222');
				var goods_num_edit=[];
				var goods_num = $(this).prev('.mui-numbox').children("input[type='number']").val();
				var goods_cart_id = parseInt($(this).attr('value'));
				goods_num_edit[0] = goods_cart_id+'_'+goods_num;
		 		var goods_num_edit = goods_num_edit.join(",");
		 		$(this).attr('id', 'aa');
				var ischange = $(this).prev().children('input').attr('gb');
				// console.log(ischecked);
				if (ischange) {
					mui.post(
						'<?php echo $this->createUrl("ymallcart/editymallcart",array("companyId"=>$this->companyId)) ?>',
						{goods_num_edit:goods_num_edit},
						function(data){
							if (data == 1) {
								mui.toast('保存成功 ! ! !',{ duration:'long', type:'div' });
								// $("input[type='checkbox']").each(function(){this.checked=false;});//取消选中
								$('#aa').css('display', 'none');
								$('#aa').prev().children("button.mui-numbox-btn-plus,button.mui-numbox-btn-minus").attr('disabled','disabled');
								$('#aa').prev().children("input.mui-numbox-input").attr('readonly','readonly');
								$('#aa').removeAttr('id');
							}else if(data == 2) {
								mui.toast('因网络原因保存失败 , 请重新保存 ! ! !',{ duration:'long', type:'div' });
							}else if(data == 3) {
								mui.toast('未查寻到商品保存失败 ! ! !',{ duration:'long', type:'div' });
							}
						},'json'
					);
				} else{
					$('#aa').css('display', 'none');
					$('#aa').prev().children("button.mui-numbox-btn-plus,button.mui-numbox-btn-minus").attr('disabled','disabled');
					$('#aa').prev().children("input.mui-numbox-input").attr('readonly','readonly');
					$('#aa').removeAttr('id');
				}
			});



			$("document").ready(function(){
				$("input.mui-numbox-input,button.mui-numbox-btn-plus,button.mui-numbox-btn-minus").attr('disabled','disabled');
				$("#edit").click(function(){
					//alert($("#edit").text());
					if($("#edit").text()=='编辑'){
						$("#gopay").removeClass("nav-on").addClass("nav-none");
						$("#godelete").removeClass("nav-none").addClass("nav-on");
						$("input.mui-numbox-input,button.mui-numbox-btn-plus,button.mui-numbox-btn-minus").removeAttr('disabled');
						$("input.mui-numbox-input").removeAttr('readonly');
						$(".edited").css('display', 'none');
						$("#edit").text('完成');
						$("input[type='checkbox']").each(function(){this.checked=false;});
						$('.all_price').html('0.00');
					}else if($("#edit").text()=='完成'){
						if ($("input[name='goods_cart_id']:checked").length > 0) {
							mui.toast(' 数据已改变 , 请点击保存 ! ! !',{ duration:'long', type:'div' })
						}else{
							$("#gopay").removeClass("nav-none").addClass("nav-on");
							$("#godelete").removeClass("nav-on").addClass("nav-none");
							$("button.mui-numbox-btn-plus,button.mui-numbox-btn-minus").attr('disabled','disabled');
							$("input.mui-numbox-input").attr('readonly','readonly');
							$(".edited").css('display', 'block');
							$("#edit").text('编辑');
							$("input[type='checkbox']").each(function(){this.checked=false;});
						}
						$('.all_price').html('0.00');
					}
				});

					//编辑时,数量改变自动选中
				$("input[type='number']").change(function(){
					$(this).parent('.mui-numbox').parent('.mui-media-body').parent('.mui-col-xs-10').prev('.mui-col-xs-2').children('.goods_product').attr('checked','checked');
					$(this).attr('gb', 'true');
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
					mui.toast('请选择要保存的商品 ! ! !',{ duration:'long', type:'div' });
				}else{
					mui.post('<?php echo $this->createUrl("ymallcart/editymallcart",array("companyId"=>$this->companyId)) ?>',{
						   goods_num_edit:goods_num_edit,
						},
						function(data){
							if (data == 1) {
								mui.toast('保存成功 ! ! !',{ duration:'long', type:'div' });
								$("input[type='checkbox']").each(function(){this.checked=false;});//取消选中
							}else if(data == 2) {
								mui.toast('因网络原因保存失败 , 请重新保存 ! ! !',{ duration:'long', type:'div' });
							}else if(data == 3) {
								mui.toast('未查寻到商品保存失败 ! ! !',{ duration:'long', type:'div' });
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
					var btnArray = ['取消', '确定'];
					mui.confirm('确定删除所选产品 ？','提示',btnArray,function(e){
						if(e.index==1){
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
										mui.toast('删除成功 ! ! !',{ duration:'long', type:'div' });
									}else if(data == 2) {
										mui.toast('因网络原因删除失败 , 请重新删除 ! ! !',{ duration:'long', type:'div' });
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
					mui.toast('请选择需要结算的商品 !!!',{ duration:'long', type:'div' })
				}
		    });
		</script>


