<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('订单');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link href='<?php echo $baseUrl;?>/css/mall/common.css' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl.'/js/layer/layer.js';?>"></script>
<body class="gift_exchange bg_lgrey2">
	<div id="topnav">
		<ul>
			<li class="all <?php if($type==0) echo 'current';?>"><a href="<?php echo $this->createUrl('/user/orderList',array('companyId'=>$this->companyId));?>"><span>全部</span></a></li>
			<li class="for_delivery <?php if($type==1) echo 'current';?>"><a href="<?php echo $this->createUrl('/user/orderList',array('companyId'=>$this->companyId,'t'=>1));?>"><span>待付款</span></a></li>
			<li class="for_confirm <?php if($type==2) echo 'current';?>"><a href="<?php echo $this->createUrl('/user/orderList',array('companyId'=>$this->companyId,'t'=>2));?>"><span>已付款</span></a></li>
		</ul>
	</div>
	<div class="orderlist with_topbar">
		<!-- 全部 -->
		<ul id="all">
			<?php foreach($models as $model):?>
			<li class="bg_white">
				<a href="<?php echo $this->createUrl('/user/orderInfo',array('companyId'=>$this->companyId,'orderId'=>$model['lid']));?>">
				<div class="headinfo colclear bottom_dash pad_10">
					<div class="left small font_l"><?php echo $model['create_at'];?></div>
					<?php if($model['order_status']< 3):?><div class="right small font_red">待付款</div><?php else:?> <?php if($model['takeout_status']==0):?><div class="right small font_org">已支付</div><?php elseif($model['takeout_status']==1):?><div class="right small font_org">商家已接单</div><?php elseif($model['takeout_status']==2):?><div class="right small font_org">商家已取消订单</div><?php elseif($model['takeout_status']==3):?><div class="right small font_org">商品配送中</div><?php elseif($model['takeout_status']==4):?><div class="right small font_org">订单已完成</div><?php endif;?><?php endif;?>
				</div>
					<!-- 商品简要情况 -->
					<div class="shortinfo2 noborder bottom_dash">
						<div class="maininfo">
						<div class="left">
							<img src="<?php echo $baseUrl;?>/img/house.jpg" class="normal">
						</div>
						<div class="right">
						<h2>类型 : <?php if($model['order_type']==1) echo '堂吃';elseif($model['order_type']==2) echo '外卖';else echo '预约';?></h2>
						<div class="nooverflow">
							<span class="pts left">合计 ：￥<?php echo $model['should_total'];?></span>
							<span class="num small right"><?php echo $model['order_num'];?>份</span>
						</div>
						</div>
						</div>
					</div>	
					<!-- 商品简要情况 -->
					</a>
					<div class="order_bttnbar pad_10">
						<?php if($model['order_status']< 3):?>
						<button class="bttn_large bttn_orange cancel" order-id="<?php echo $model['lid'];?>">取消订单</button>
						<?php else:?>
						<button class="bttn_large bttn_black2">已完成</button>
						<?php endif;?>
					</div>
			</li>
			<?php endforeach;?>
			<div class="bttnbar-top"></div>
		</ul>
		<!-- 全部 -->
	</div>
	<script type="text/javascript">
	$(document).ready(function(){
		$('.cancel').click(function(){
			var orderId = $(this).attr('order-id');
			$.ajax({
				url:'<?php echo $this->createUrl('/user/ajaxCancelOrder',array('companyId'=>$this->companyId));?>',
				data:{orderId:orderId},
				success:function(data){
					if(parseInt(data)){
						history.go(0);
					}else{
						layer.msg('取消失败,重新操作');
					}
				}
			});
		});
	});
	</script>
	<?php 
	include_once(Yii::app()->basePath.'/views/layouts/footernav.php');
	?>
</body>
