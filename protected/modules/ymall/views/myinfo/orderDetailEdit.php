
<style>
	.back-color{background-color: #F0F0E1;}
	.left{float:left;}
	.right{float:right;}
	.padding-right{padding-right:35px;}
	.padding-right1{padding-right:40px;}
	.nav-on{margin-bottom: 50px;}
	.padding{padding:5px;}
	.font-small{font-size: 12px;}
	.color-l-gray{color:#323232;}
	.img-show{width: 98px;height:98px;margin-left: -14px;margin-right: 10px;}
	.banma{border-bottom:3px dashed red;}
	.big-ul{margin-top:2px!important;}
	.margin-b{margin:0;margin-bottom: 120px!important;}
	#suretopay{margin:0;height:50px;top:0;border-radius: 0;}
	.gotocheck{padding: 2px 12px;float:right;margin-left:20px;margin-right:10px;}
	.delete_nopay{padding: 2px 12px;float:right;}
	.mcard{margin-top:0px;margin-right:0px;margin-left: 0px;margin-bottom: 60px;background-color: #F2F2F2;}
	.card-head{background-color: #DDFFDD;}
	.ui-table-view-cell {
	    position: relative;
	    overflow: hidden;
	    /*padding: 11px 15px;*/
	    -webkit-touch-callout: none;
	}
	.mui-table-view .mui-media-object {
		line-height: 42px;
		max-width: 76px;
		height: 76px;
		border-radius: 10px;
	}
</style>


<header class="mui-bar mui-bar-nav mui-hbar">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" style="color:white;"></a>
    <h1 class="mui-title" style="color:white;">订单详情</h1>
</header>
<div class="mui-content mui-scroll-wrapper">
	<div class="mui-scroll">

		<div class="mui-row back-color padding banma" style="">
	    	<div class="mui-col-xs-1" style="height:50px;">
	    		<span class="mui-icon mui-icon-location" style="margin-top:5px;color:red;font-weight:900;"></span>
	    	</div>
	    	<div class="mui-col-xs-11 ">
	    	<?php if ($goods_orders):?>
	    		<span class="mui-navigate-right">
					<div class="mui-row back-color">
						<span class="left color-l-gray">收货人:<?php echo $goods_orders[0]['ganame'];?></span>
						<span class="right padding-right1 color-l-gray"><?php echo $goods_orders[0]['amobile'];?></span>
					</div>
					<div class="mui-row back-color ">
						<span class="left font-small color-l-gray" style="height: 23px;line-height: 23px;">收货地址 : </span>
						<span class=" font-small mui-ellipsis-2 padding-right"><?php echo $goods_orders[0]['pcc'].' '.$goods_orders[0]['street'];?></a>
					</div>
				</span>
			<?php endif;?>
	    	</div>
	    </div>
	    	<?php if ($goods_orders): ?>
			<div class="mui-card mcard">
				<div class="mui-card-header mui-card-media">
					<img src="<?php echo  Yii::app()->request->baseUrl; ?>/img/order_list.png" />
					<div class="mui-media-body">
						订单号:<?php echo $goods_orders[0]['account_no'];?>
						<p>下单日期: <?php echo $goods_orders[0]['create_at'];?></p>
					</div>
				</div>
				<div class="mui-card-content" >
				<?php if($type==1)://线上支付待付款 ?>
					<ul class="mui-table-view" id="OA_task_2">
						<?php foreach ($goods_orders as $key => $value):?>
						<li class="mui-table-view-cell"  account_no="<?php echo $goods_orders[0]['account_no']; ?>" dlid="<?php echo $value['lid']; ?>">
							<div class="mui-slider-right mui-disabled">
								<a class="mui-btn mui-btn-red delete" account_no="<?php echo $goods_orders[0]['account_no']; ?>" dlid="<?php echo $value['lid']; ?>">删除</a>
							</div>
							<div class="mui-slider-handle">
								<div class="ui-table-view-cell mui-media">
									<img class="mui-media-object mui-pull-left" src="<?php if($value['main_picture']){ echo $value['main_picture'];}else{ echo 'http://menu.wymenu.com/wymenuv2/img/product_default.png';}?>">
									<div class="mui-media-body">
									<span style="color:darkblue;">[<?php echo $value['company_name']; ?>] </span><?php echo $value['goods_name'];?>
									<p class='mui-ellipsis'>
										<span class="mui-pull-left">单价 : <span style="color:red;">¥<?php echo $value['price'];?></span></span>
										<span class="mui-pull-right"><?php echo $value['unit_name'];?> </span>
									</p>
									<div class="mui-numbox mui-right " data-numbox-step='1' data-numbox-min='1' data-numbox-max='<?php echo 10000;//$product['store_number']; ?>'>
										<button class="mui-btn mui-numbox-btn-minus" type="button">-</button>
										<input class="mui-numbox-input" type="number" price="<?php echo $value['price'];?>" lid="<?php echo $value['lid']; ?>" value="<?php echo $value['num']; ?>" />
										<button class="mui-btn mui-numbox-btn-plus" type="button">+</button>
									</div> / <?php echo $value['goods_unit'];?>
									</div>
								</div>
							</div>
						</li>
						<?php endforeach; ?>
						<li class="mui-table-view-cell">
							<span style="color:darkblue;">驳回理由 : </span><?php if($goods_orders[0]['back_reason']){ echo $goods_orders[0]['back_reason'];}else{ echo '没有理由!';} ?>
						</li>
					</ul>
				<?php endif; ?>
				</div>
				<div class="mui-card-footer">
				<?php if($type==1)://全部 ?>
					<a class="mui-card-link">合计 : ¥ <span id="zongjia"></span></a>
					<button type="button" class="mui-btn mui-btn-success mui-btn-outlined gotocheck" account_no="<?php echo $goods_orders[0]['account_no']; ?>">重新审核</button>
					<button type="button" class="mui-btn mui-btn-danger mui-btn-outlined delete_nopay" account_no="<?php echo $goods_orders[0]['account_no']; ?>">删除订单</button>
				<?php endif; ?>
				</div>
			</div>
			<?php endif; ?>

	</div>
</div>

<script type="text/javascript">
	mui.init();
	mui('.mui-scroll-wrapper').scroll();
	sum_price();
	function sum_price()
	{
		var zongjia = 0;
		$('.mui-numbox input.mui-numbox-input').each(function() {
			var price = $(this).attr('price');
			var num = $(this).val();
			zongjia += price*num;
		});
		$('#zongjia').text(zongjia.toFixed(2));
	}

	$('.mui-numbox input.mui-numbox-input').change(function() {
		sum_price();
	});

	$('.gotocheck').on('tap',function(){
		var account_no = $(this).attr('account_no');
		var arr = [];
		$('.mui-numbox input.mui-numbox-input').each(function() {
			var lid = $(this).attr('lid');
			var num = $(this).val();
			arr.push(lid+'_'+num);
		});
		var strs = arr.join(',');
		var all_price = $('#zongjia').text();
		var btnArray = ['否','是'];
		mui.confirm('是否重新提交该订单 ？','提示',btnArray,function(e){
			if(e.index==1){
			mui.post('<?php echo $this->createUrl("myinfo/put_order",array("companyId"=>$this->companyId)) ?>',{
				   account_no:account_no,
				   strs:strs,
				   all_price:all_price,
				},
				function(data){
					if (data == 1) {
						mui.toast('提交成功 ! ! !',{ duration:'long', type:'div' });
						location.href="<?php echo $this->createUrl('myinfo/goodsOrderCheck',array('companyId'=>$this->companyId));?>";
					}else if(data == 2) {
						mui.toast('因网络原因提交失败 , 请重新提交 ! ! !',{ duration:'long', type:'div' });
					}else if(data == 3) {
						mui.toast('未查寻到该订单提交失败 ! ! !',{ duration:'long', type:'div' });
					}
				},'json'
			);
		}
		});
	});

	$('.delete_nopay').on('tap',function(){
		var account_no = $(this).attr('account_no');
		var btnArray = ['否','是'];
		mui.confirm('是否确定删除该订单 ？','提示',btnArray,function(e){
			if(e.index==1){
			mui.post('<?php echo $this->createUrl("myinfo/delete_order",array("companyId"=>$this->companyId)) ?>',{
				   account_no:account_no,
				},
				function(data){
					if (data == 1) {
						mui.toast('删除成功 ! ! !',{ duration:'long', type:'div' });
						location.href="<?php echo $this->createUrl('myinfo/goodsOrderCheck',array('companyId'=>$this->companyId));?>";
					}else if(data == 2) {
						mui.toast('因网络原因删除失败 , 请重新删除 ! ! !',{ duration:'long', type:'div' });
					}else if(data == 3) {
						mui.toast('订单已删除, 但未查寻到要删除商品 ! ! !',{ duration:'long', type:'div' });
						location.href="<?php echo $this->createUrl('myinfo/goodsOrderCheck',array('companyId'=>$this->companyId));?>";
					}
				},'json'
			);
		}
		});
	});

	$('#OA_task_2').on('slideleft', '.mui-table-view-cell', function(event) {
		var elem = this;
		var account_no = $(this).attr('account_no');
		var lid = $(this).attr('dlid');
		$(this).attr('id', 'aa');
		var btnArray = ['确认','取消'];
		// console.log(elem);
		mui.confirm('确认删除该商品 ？', '提示', btnArray, function(e) {
			if (e.index == 0) {
				mui.post('<?php echo $this->createUrl("myinfo/delete_order_detail",array("companyId"=>$this->companyId)) ?>',{
					   account_no:account_no,
					   lid:lid,
					},
					function(data){
						if (data == 1) {
							mui.toast('删除成功 ! ! !',{ duration:'long', type:'div' });
							elem.parentNode.removeChild(elem);
							sum_price()
						}else if(data == 2) {
							mui.toast('因网络原因删除失败 , 请重新删除 ! ! !',{ duration:'long', type:'div' });
							$('#aa').removeAttr('id');
						}else if(data == 3) {
							mui.toast('未查寻到该商品, 删除失败, 请刷新页面 ! ! !',{ duration:'long', type:'div' });
							$('#aa').removeAttr('id');
						}
					},'json'
				);
			} else {
				setTimeout(function() {
					$('#aa').removeClass('mui-selected');
					$('#aa').children('.mui-slider-right').removeClass('mui-selected');
					$('#aa').children('.mui-slider-right').children('a').removeAttr('style');
					$('#aa').children('.mui-slider-handle').removeAttr('style');
					$('#aa').removeAttr('id');
				}, 0);
			}
		});
	});

	$('.delete').on('tap',function(){
		var account_no = $(this).attr('account_no');
		var lid = $(this).attr('dlid');
		// console.log(elem);
		$(this).attr('id', 'bb');
		var btnArray = ['否','是'];
		mui.confirm('是否确定删除该订单 ？','提示',btnArray,function(e){
			if(e.index==1){
				mui.post('<?php echo $this->createUrl("myinfo/delete_order_detail",array("companyId"=>$this->companyId)) ?>',{
					   account_no:account_no,
					   lid:lid,
					},
					function(data){
						if (data == 1) {
							mui.toast('删除成功 ! ! !',{ duration:'long', type:'div' });
							$('#bb').parent().parent().remove();
							sum_price()
						}else if(data == 2) {
							mui.toast('因网络原因删除失败 , 请重新删除 ! ! !',{ duration:'long', type:'div' });
							$('#bb').removeAttr('id');
						}else if(data == 3) {
							mui.toast('未查寻到该订单删除失败 ! ! !',{ duration:'long', type:'div' });
							$('#bb').removeAttr('id');
						}
					},'json'
				);
			}
		});
	});

</script>
