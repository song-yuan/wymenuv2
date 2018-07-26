<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('等待支付结果');
?>
<!-- loading toast -->
    <div id="loadingToast">
        <div class="weui-mask_transparent"></div>
        <div class="weui-toast">
            <i class="weui-loading weui-icon_toast"></i>
            <p class="weui-toast__content">请稍等...</p>
        </div>
    </div>
<script>
	var queryCount = 60;
	function queryOrder(){
		if(queryCount < 1){
			goToOrder();
			return;
		}
		queryCount--;
		var url = '<?php echo $this->createUrl('/mall/ajaxGetOrder',array('companyId'=>$companyId,'orderId'=>$orderId,'orderDpid'=>$orderDpid));?>';
		$.ajax({
			url:url,
			success:function(msg){
				if(msg.status){
					var order = msg.data;
					if(order.order_status==3 || order.order_status==4){
						goToOrder();
					}else{
						setTimeout(queryOrder,2000);
					}
				}
			},
			dataType:'json'
		});
	}
	function goToOrder(){
		location.href = '<?php echo $this->createUrl('/user/orderInfo',array('companyId'=>$companyId,'orderId'=>$orderId,'orderDpid'=>$orderDpid));?>';
	}
	$(document).ready(function(){
		queryOrder();
	});
</script>
