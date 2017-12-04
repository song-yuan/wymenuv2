<style>
.mui-scroll-wrapper{
	top:150px;

	/*margin-bottom: 10px;*/
}
.mui-scroll{
	padding-bottom: 10px;
}
.mui-btn-block {
     padding: 5px 0; 
}
.mui-card:first-child{
	margin-top:0;
}
.mui-content-padded {
    margin: 0 9px;
}
</style>
<header class="mui-bar mui-bar-nav  mui-hbar">
	<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" style="color:white;"></a>
	<h1 class="mui-title" style="color:white;">全部订单</h1>
</header>
<div class="mui-content " style="margin-bottom: 50px;">

	<div style="padding: 10px 10px;">
		<div id="segmentedControl" class="mui-segmented-control">
			<a class="mui-control-item" href="<?php echo $this->createUrl('myinfo/goodsOrderAll',array('companyId'=>$this->companyId));?>">全部</a>
			<a class="mui-control-item" href="<?php echo $this->createUrl('myinfo/goodsOrderNopay',array('companyId'=>$this->companyId));?>">待付款</a>
			<a class="mui-control-item  mui-active" href="<?php echo $this->createUrl('myinfo/goodsOrderNosent',array('companyId'=>$this->companyId));?>">待发货</a>
			<a class="mui-control-item" href="<?php echo $this->createUrl('myinfo/goodsOrderNoget',array('companyId'=>$this->companyId));?>">待收货</a>
			<a class="mui-control-item" href="<?php echo $this->createUrl('myinfo/goodsOrderGetted',array('companyId'=>$this->companyId));?>">已收货</a>
		</div>
	</div>

<div class="mui-content-padded">
	<button id='demo4' data-options='{"type":"date"}' class="btn mui-btn mui-btn-block">按日期查询</button>
	<input type="hidden" id="result" value="<?php echo $date; ?>">
</div>
	<div class="mui-scroll-wrapper">
	<div class=" mui-scroll">
		<?php if($goods_orders): ?>
		<div  id="content">
			<?php foreach($goods_orders as  $goods_order): ?>
			<div class="mui-card">
				<div class="mui-card-header mui-card-media">
					<img src="<?php echo  Yii::app()->request->baseUrl; ?>/img/order_list.png" />
					<div class="mui-media-body">
						订单号:<?php echo $goods_order['account_no'];?>
						<p>下单日期: <?php echo $goods_order['create_at'];?></p>
					</div>
				</div>
				<div class="mui-card-content" >
				</div>
				<div class="mui-card-footer">
					<a class="mui-card-link">合计 : ¥ <?php echo $goods_order['reality_total']; ?></a>
					<a class="mui-card-link"><?php if($goods_order['paytype']==1){echo '<span style="color:green">线上支付</span>';}else if($goods_order['paytype']==2){echo '<span style="color:red">货到付款</span>';} ?></a>
					<a class="mui-card-link"><?php if($goods_order['pay_status']==1){echo '<span style="color:green">已付款</span>';}else if($goods_order['pay_status']==0){echo '<span style="color:red">未付款</span>';} ?></a>
					<a class="mui-card-link" href="<?php echo $this->createUrl('myinfo/orderDetail',array('companyId'=>$this->companyId,'account_no'=>$goods_order['account_no'],'type'=>2));?>">查看详情</a>
				</div>
			</div>
			<?php endforeach;?>
		</div>
		<?php endif;?>
		<div class="mui-pull-bottom-pocket" style="visibility: visible;">
			<div class="mui-pull">
				<div class="mui-pull-loading "></div>
				<div class="mui-pull-caption">上拉显示更多</div>
			</div>
		</div>
	</div>
</div>
</div>
<script>
mui('body').on('tap','a',function(){document.location.href=this.href;});
// mui('.mui-scroll-wrapper').scroll();
mui.init({
  pullRefresh : {
    container:".mui-scroll-wrapper",//待刷新区域标识，querySelector能定位的css选择器均可，比如：id、.class等
    up : {
      height:100,//可选.默认50.触发上拉加载拖动距离
      auto:false,//可选,默认false.自动上拉加载一次
      contentrefresh : "正在加载...",//可选，正在加载状态时，上拉加载控件上显示的标题内容
      contentnomore:'没有更多数据了',//可选，请求完毕若没有更多数据时显示的提醒内容；
      callback :upFn //必选，刷新函数，根据具体业务来编写，比如通过ajax从服务器获取新数据；
    }
  }
});
var y =0;
function upFn() {
	var date = $('#result').val();
	// alert(date);
	var list_length = $('.mui-card').length;
	if (list_length<10) {
		mui('.mui-scroll-wrapper').pullRefresh().endPullupToRefresh(true);
	}else{
	    mui.ajax("<?php echo $this->createUrl('myinfo/goodsOrderNosent',array('companyId'=>$this->companyId)); ?>",{
			data:{up:y++,date:date},
			dataType:'json',//服务器返回json格式数据
			type:'post',//HTTP请求类型
			timeout:10000,//超时时间设置为10秒；

			success:function(data){
				data = eval(data);
				// console.log(data);
				// var content = document.getElementById('content');
				var y_str = $("#content").html();
				var con ='';
				for (var i=0;i<data.length;i++) {
					if(data[i].paytype==1){
						var paytype_str = '<span style="color:green">线上支付</span>';
					}else if(data[i].paytype==2){
						var paytype_str = '<span style="color:red">货到付款</span>';
					}
					if(data[i].pay_status==1){
						var pay_status_str = '<span style="color:green">已付款</span>';
					}else if(data[i].pay_status==0){
						var pay_status_str = '<span style="color:red">未付款</span>';
					}
					var str ='<div class="mui-card"><div class="mui-card-header mui-card-media"><img src="<?php echo  Yii::app()->request->baseUrl; ?>/img/order_list.png" /><div class="mui-media-body">订单号:'+data[i].account_no+'<p>下单日期:'+data[i].create_at+'</p></div></div>'
						+'<div class="mui-card-content" ></div>'
						+'<div class="mui-card-footer"><a class="mui-card-link">合计 : ¥ '+data[i].reality_total+'</a><a class="mui-card-link">'+paytype_str+'</a><a class="mui-card-link">'+pay_status_str+'</a><a class="mui-card-link" href="<?php echo $this->createUrl('myinfo/orderDetail',array('companyId'=>$this->companyId));?>/account_no/'+data[i].account_no+'">查看详情</a></div></div>';
						con+=str;
				}
				$("#content").html(y_str+con);
				if(data.length==0){
					mui('.mui-scroll-wrapper').pullRefresh().endPullupToRefresh(true);
				}else{
					mui('.mui-scroll-wrapper').pullRefresh().endPullupToRefresh(false);
				}
			},
			error:function(xhr,type,errorThrown){
				//异常处理；
				console.log(type);
			}
		});
	}
}

(function($) {
	// $.init();
	var result = $('#result');
	var btns = $('.btn');
	btns.each(function(i, btn) {
		btn.addEventListener('tap', function() {
			var _self = this;
			if(_self.picker) {
				_self.picker.show(function (rs) {
					// result.innerText = '选择结果1: ' + rs.text;
					// result.value = rs.text;
					_self.picker.dispose();
					_self.picker = null;
				});
			} else {
				var optionsJson = this.getAttribute('data-options') || '{}';
				var options = JSON.parse(optionsJson);
				var id = this.getAttribute('id');
				/*
				 * 首次显示时实例化组件
				 * 示例为了简洁，将 options 放在了按钮的 dom 上
				 * 也可以直接通过代码声明 optinos 用于实例化 DtPicker
				 */
				_self.picker = new $.DtPicker(options);
				_self.picker.show(function(rs) {
					/*
					 * rs.value 拼合后的 value
					 * rs.text 拼合后的 text
					 * rs.y 年，可以通过 rs.y.vaue 和 rs.y.text 获取值和文本
					 * rs.m 月，用法同年
					 * rs.d 日，用法同年
					 * rs.h 时，用法同年
					 * rs.i 分（minutes 的第二个字母），用法同年
					 */
					// result.innerText = '选择结果2: ' + rs.text;
					// result.value = rs.text;
					location.href='<?php echo $this->createUrl('myinfo/goodsOrderNosent',array('companyId'=>$this->companyId)); ?>/date/'+rs.text;
					/* 
					 * 返回 false 可以阻止选择框的关闭
					 * return false;
					 */
					/*
					 * 释放组件资源，释放后将将不能再操作组件
					 * 通常情况下，不需要示放组件，new DtPicker(options) 后，可以一直使用。
					 * 当前示例，因为内容较多，如不进行资原释放，在某些设备上会较慢。
					 * 所以每次用完便立即调用 dispose 进行释放，下次用时再创建新实例。
					 */
					_self.picker.dispose();
					_self.picker = null;
				});
			}
			
		}, false);
	});
})(mui);
<?php if ($success==1): ?>
mui.toast('下单成功 , 仓库正在加紧备货 ! ! !');
<?php endif; ?>

</script>