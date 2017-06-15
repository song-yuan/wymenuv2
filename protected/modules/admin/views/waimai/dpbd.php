<div class="page-content">
	<iframe frameborder="0" width= 100% height= 700px src="https://open-erp.meituan.com/storemap?developerId=<?php echo $developerId;?>&businessId=2&ePoiId=<?php echo $companyId;?>&signKey=8isnqx6h2xewfmiu&netStore=1"></iframe>
</div>
<script type="text/javascript">
window.addEventListener('message',function(e){
	console.log(e);
	if(e.data.event=="msg-token"){
		alert("绑定成功");
	}
},false);
</script>