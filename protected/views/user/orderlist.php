<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('我的订单');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link href='<?php echo $baseUrl;?>/css/mall/common.css' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/weui.min.css">
<style>
.more-info{
	text-align:center;
	padding:5px;
	background:white;
}
</style>
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
				<a href="<?php echo $this->createUrl('/user/orderInfo',array('companyId'=>$this->companyId,'orderId'=>$model['lid'],'orderDpid'=>$model['dpid']));?>">
				<div class="headinfo colclear bottom_dash pad_10">
					<div class="left small font_l"><?php echo $model['create_at'];?></div>
					<?php if($model['order_status']< 3):?><div class="right small font_red">待付款</div><?php else:?> <?php if($model['takeout_status']==0):?><div class="right small font_org">已支付</div><?php elseif($model['takeout_status']==1):?><div class="right small font_org">商家已接单</div><?php elseif($model['takeout_status']==2):?><div class="right small font_org">商家已取消订单</div><?php elseif($model['takeout_status']==3):?><div class="right small font_org">商品配送中</div><?php elseif($model['takeout_status']==4):?><div class="right small font_org">订单已完成</div><?php endif;?><?php endif;?>
				</div>
					<!-- 商品简要情况 -->
					<div class="shortinfo2 noborder bottom_dash">
						<div class="maininfo">
						<div class="left">
							<img src="<?php echo $model['logo'];?>" class="normal">
						</div>
						<div class="right">
						<h2 style="margin-left:2%;font-size:1.2em !important;"><?php echo $model['company_name'];?></h2>
						<h2 style="margin-left:2%;">类型 : <?php if($model['order_type']==1) echo '堂吃';elseif($model['order_type']==2) echo '外卖';elseif($model['order_type']==3) echo '预约';elseif($model['order_type']==6) echo '手机自助点单';else echo '收银台点单';?></h2>
						<div class="nooverflow" style="margin-left:2%;">
							<span class="pts left">合计 ：￥<?php echo $model['should_total'];?></span>
							<span class="num small right"></span>
						</div>
						</div>
						</div>
					</div>	
					<!-- 商品简要情况 -->
					</a>
					<?php if($model['order_status']< 3):?>
					<div class="order_bttnbar pad_10">
						<button class="bttn_large bttn_orange cancel" order-id="<?php echo $model['lid'];?>" order-dpid="<?php echo $model['dpid'];?>">取消订单</button>
					</div>
					<?php endif;?>
			</li>
			<?php endforeach;?>
			<?php if(count($models)==10):?>
			<div id="more" class="more-info">点击查看更多</div>
			<?php endif;?>
		</ul>
		<!-- 全部 -->
	</div>
	 <!--BEGIN dialog1-->
    <div class="weui_dialog_confirm" id="dialog1" order-id="0" order-dpid="0" style="display: none;">
        <div class="weui_mask" style="z-index:1005;"></div>
        <div class="weui_dialog" style="z-index:1006;">
            <div class="weui_dialog_hd"><strong class="weui_dialog_title">提示</strong></div>
            <div class="weui_dialog_bd" style="text-align:center;">是否要取消订单？</div>
            <div class="weui_dialog_ft">
                <a href="javascript:;" class="weui_btn_dialog default">取消</a>
                <a href="javascript:;" class="weui_btn_dialog primary">确定</a>
            </div>
        </div>
    </div>
    <!--END dialog1-->
     <!--BEGIN dialog2-->
    <div class="weui_dialog_alert" id="dialog2" style="display: none;">
        <div class="weui_mask" style="z-index:1005;"></div>
        <div class="weui_dialog" style="z-index:1006;">
            <div class="weui_dialog_hd"><strong class="weui_dialog_title">提示</strong></div>
            <div class="weui_dialog_bd">订单取消失败</div>
            <div class="weui_dialog_ft">
                <a href="javascript:;" class="weui_btn_dialog primary">确定</a>
            </div>
        </div>
    </div>
    <!--END dialog2-->
	<script type="text/javascript">
	$(document).ready(function(){
		var orderId = 0;
		var orderDpid = 0;
		var page = 2;
		$('.cancel').click(function(){
			$(this).removeClass('bttn_orange').addClass('bttn_grey');
			var orderId = $(this).attr('order-id');
			var orderDpid = $(this).attr('order-dpid');
			$('#dialog1').attr('order-id',orderId);
			$('#dialog1').attr('order-dpid',orderDpid);
			$('#dialog1').show();
		});
		$('#dialog1 .primary').click(function(){
			$('#dialog1').hide();
			var orderId = $('#dialog1').attr('order-id');
			var orderDpid = $('#dialog1').attr('order-dpid');
			$.ajax({
				url:'<?php echo $this->createUrl('/user/ajaxCancelOrder',array('companyId'=>$this->companyId));?>',
				data:{orderId:orderId,orderDpid:orderDpid},
				success:function(data){
					if(parseInt(data)){
						location.href = '<?php echo $this->createUrl('user/orderList',array('companyId'=>$this->companyId));?>';
					}else{
						$('#dialog2').show();
					}
				}
			});
		});
		$('#dialog1 .default').click(function(){
			$('.bttn_grey').removeClass('bttn_grey').addClass('bttn_orange');
			$('#dialog1').hide();
		});	
		$('#dialog2 .primary').click(function(){
			$('.bttn_grey').removeClass('bttn_grey').addClass('bttn_orange');
			$('#dialog2').hide();
		});	
		$('#more').click(function(){
			layer.load(2);
			$.ajax({
				url:'<?php echo $this->createUrl('/user/ajaxOrderList',array('companyId'=>$this->companyId,'t'=>$type,'userId'=>$userId,'cardId'=>$cardId));?>',
				data:{p:page},
				dataType:'json',
				success:function(data){
					layer.closeAll('loading');
					if(data.length==10){
						page++;
					}else{
						$('#more').hide();
					}
					var str = '';
					for(var i=0;i<data.length;i++){
						var model = data[i];
						str +='<li class="bg_white">'
							+'<a href="<?php echo $this->createUrl('/user/orderInfo',array('companyId'=>$this->companyId));?>&orderId='+model.lid+'&orderDpid='+model.dpid+'">'
							+'<div class="headinfo colclear bottom_dash pad_10">'
							+'<div class="left small font_l">'+model.create_at+'</div>';
							if(model.order_status < '3'){
								str += '<div class="right small font_red">待付款</div>';
							}else{
								if(model.takeout_status == '0'){
									str += '<div class="right small font_org">已支付</div>';
								}else if(model.takeout_status == '1'){
									str += '<div class="right small font_org">商家已接单</div>';	
								}else if(model.takeout_status == '2'){
									str += '<div class="right small font_org">商家已取消订单</div>';	
								}else if(model.takeout_status == '3'){
									str += '<div class="right small font_org">商品配送中</div>';	
								}else if(model.takeout_status == '4'){
									str += '<div class="right small font_org">订单已完成</div>';	
								}
							}
							str += '</div>';
							str += '<div class="shortinfo2 noborder bottom_dash">'
								+'<div class="maininfo">'
								+'<div class="left"><img src="'+model.logo+'" class="normal"></div>';
							str += '<div class="right">'
								+'<h2 style="margin-left:2%;font-size:1.2em !important;">'+model.company_name+'</h2>'
								+'<h2 style="margin-left:2%;">类型 :';
								if(model.order_type=='1'){
									str +='堂吃';
								}else if(model.order_type=='2'){
									str +='外卖';
								}else if(model.order_type=='3'){
									str +='预约';
								}else if(model.order_type=='6'){
									str +='手机自助点单';
								}else{
									str +='收银台点单';
								}
							str += '</h2>'
								+'<div class="nooverflow" style="margin-left:2%;">'
								+'<span class="pts left">合计 ：￥'+model.should_total+'</span>'
								+'<span class="num small right"></span>'
								+'</div></div></div></div></a>';
							
						if(model.order_status < 3){
							str +='<div class="order_bttnbar pad_10">'
								+'<button class="bttn_large bttn_orange cancel" order-id="'+model.lid+'" order-dpid="'+model.dpid+'">取消订单</button>'
								+'</div>';
						}
						str +='</li>';
					}
					$('#more').before(str);
				}
			});
		});	
	});
	</script>
</body>
