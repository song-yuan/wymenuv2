<?php
$dpid = Yii::app()->request->getParam('companyId');
$orderId = Yii::app()->request->getParam('orderId');
?>
<img src="" style="width:80%"/>
<a href="javascript:;">生成二维码</a>
<script type="text/javascript">
$('a').click(function(){
	$.ajax({
			url:'<?php echo $this->createUrl('sqbpay/precreate',array('companyId'=>$dpid,'orderId'=>$orderId));?>',
			success:function(data){
				if(data.status){
					var qrcodeUrl = data.result.qr_code_image_url;
					$('img').attr('src',qrcodeUrl);
				}
			},
			dataType:'json'
		});
});
</script>

