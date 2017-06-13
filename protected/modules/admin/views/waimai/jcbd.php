<div class="page-content">
<?php if(empty($tokenmodel['appAuthToken'])){?>
	<?php echo "<h1>缺少参数：appAuthToken</h1>";?>
<?php }else{?>
		<iframe id="cc" name="cc" frameborder="0" width= 100% height= 700px src="https://open-erp.meituan.com/releasebinding?signKey=8isnqx6h2xewfmiu&businessId=2&appAuthToken=<?php echo $tokenmodel['appAuthToken'];?>"></iframe>
<?php }?>
</div>