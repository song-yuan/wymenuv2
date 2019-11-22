<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('我的订单');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link href='<?php echo $baseUrl;?>/css/mall/common.css' rel='stylesheet' type='text/css'>
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
			<li class="all <?php if($type==0) echo 'current';?>" style="width:24%;"><a href="<?php echo $this->createUrl('/user/orderList',array('companyId'=>$this->companyId));?>"><span>全部</span></a></li>
			<li class="for_delivery <?php if($type==1) echo 'current';?>" style="width:24%;"><a href="<?php echo $this->createUrl('/user/orderList',array('companyId'=>$this->companyId,'t'=>1));?>"><span>待付款</span></a></li>
			<li class="for_confirm <?php if($type==2) echo 'current';?>" style="width:24%;"><a href="<?php echo $this->createUrl('/user/orderList',array('companyId'=>$this->companyId,'t'=>2));?>"><span>已付款</span></a></li>
			<li class="for_confirm <?php if($type==3) echo 'current';?>" style="width:24%;"><a href="<?php echo $this->createUrl('/user/orderList',array('companyId'=>$this->companyId,'t'=>3));?>"><span>已完成</span></a></li>
		</ul>
	</div>
	<div class="orderlist with_topbar">
		<!-- 全部 -->
		<ul id="all">
			<div id="more" class="more-info" style="display:none;">点击查看更多</div>
		</ul>
		<!-- 全部 -->
	</div>
<div id="dialogs">
	<!--BEGIN dialog1-->
	<div class="js_dialog" id="dialog1" style="display: none;">
	    <div class="weui-mask"></div>
	    <div class="weui-dialog">
	         <div class="weui-dialog__hd"><strong class="weui-dialog__title">提示</strong></div>
	         <div class="weui-dialog__bd">是否要取消订单？</div>
	         <div class="weui-dialog__ft">
	         	<a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_default">取消</a>
	         	<a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_primary">确定</a>
	         </div>
	     </div>
	</div>
	<!--END dialog1-->
	<!--BEGIN dialog2-->
	<div class="js_dialog" id="dialog2" style="display: none;">
     	<div class="weui-mask"></div>
        <div class="weui-dialog">
                <div class="weui-dialog__bd">订单取消失败,请重新操作</div>
                <div class="weui-dialog__ft">
                    <a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_primary">确定</a>
                </div>
         </div>
    </div>       
	<!--END dialog2-->
</div>
<!-- loading toast -->
<div id="loadingToast" style="display:none;">
	<div class="weui-mask_transparent"></div>
 	<div class="weui-toast">
    	<i class="weui-loading weui-icon_toast"></i>
    	<p class="weui-toast__content">订单取消中</p>
    </div>
</div> 
<div id="loadingMoreToast" style="display:none;">
	<div class="weui-mask_transparent"></div>
 	<div class="weui-toast">
    	<i class="weui-loading weui-icon_toast"></i>
    	<p class="weui-toast__content">订单加载中</p>
    </div>
</div> 
<script type="text/javascript">
var page = 1;
function getOrderList(){
	$('#loadingMoreToast').fadeIn(200);
	$.ajax({
		url:'<?php echo $this->createUrl('/user/ajaxOrderList',array('companyId'=>$this->companyId,'t'=>$type,'userId'=>$userId,'cardId'=>$cardId));?>',
		data:{p:page},
		dataType:'json',
		success:function(data){
			$('#loadingMoreToast').fadeOut(200);
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
}
$(document).ready(function(){
	var orderId = 0;
	var orderDpid = 0;
	getOrderList();
	$('.cancel').click(function(){
		$(this).removeClass('bttn_orange').addClass('bttn_grey');
		var orderId = $(this).attr('order-id');
		var orderDpid = $(this).attr('order-dpid');
		$('#dialog1').attr('order-id',orderId);
		$('#dialog1').attr('order-dpid',orderDpid);
		$('#dialog1').fadeIn(200);
	});
	$('.payorder').click(function(){
		var href = $(this).attr('href');
		location.href = href;
	});
	$('#dialog1 .weui-dialog__btn_primary').click(function(){
		var orderId = $('#dialog1').attr('order-id');
		var orderDpid = $('#dialog1').attr('order-dpid');
		$('#dialog1').fadeOut(200);
		$('#loadingToast').fadeIn(200);
		$.ajax({
			url:'<?php echo $this->createUrl('/user/ajaxCancelOrder',array('companyId'=>$this->companyId));?>',
			data:{orderId:orderId,orderDpid:orderDpid},
			success:function(data){
				$('#loadingToast').fadeOut(200);
				if(parseInt(data)){
					$('.cancel[order-id="'+orderId+'"][order-dpid="'+orderDpid+'"]').parents('li').remove();
				}
			}
		});
	});
	$('#dialog1 .weui-dialog__btn_default').click(function(){
		$('.bttn_grey').removeClass('bttn_grey').addClass('bttn_orange');
		$('#dialog1').fadeOut(200);
	});	
	$('#dialog2 .weui-dialog__btn_primary').click(function(){
		$('.bttn_grey').removeClass('bttn_grey').addClass('bttn_orange');
		$('#dialog2').fadeOut(200);
	});	
	$('#more').click(function(){
		getOrderList();
	});	
});
</script>
</body>
