<?php
$dpid = Yii::app()->request->getParam('companyId');
$orderId = Yii::app()->request->getParam('orderId');
?>
<img src=""/>
<a href="">生成二维码</a>
<script type="text/javascript">
$('a').click(function(){
	$.ajax({
			url:'<?php echo $this->createUrl('sqbpay/precreate',array('companyId'=>$dpid,'orderId'=>$orderId));?>',
			sucess:function(data){
				alert(data);
			},
			dataType:'json'
		});
});
</script>

