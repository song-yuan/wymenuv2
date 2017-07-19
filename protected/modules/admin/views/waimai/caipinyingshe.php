<div class="page-content">
<iframe id="cc" name="cc" frameborder="0" width= 100% height= 700px src="https://open-erp.meituan.com/waimai-dish-mapping?signKey=<?php echo $this->signkey;?>&appAuthToken=<?php echo $tokenmodel['appAuthToken'];?>&ePoiId=<?php echo $companyId;?>"></iframe>
</div>
<script type="text/javascript">
window.addEventListener('message',function(e){
	console.log(e);
	if(e.data.event=="getErpDishData"){
	  window.frames["cc"].postMessage({
	   event: 'erpDishData', // postMessage消息名
	   value: {
		"dishes":
			[
			<?php foreach ($productmodels as $value) {?>
				{
			 "categoryName":"单品",
			 "eDishCode":"<?php echo $value->phs_code?>",
			 "eDishSkuCode":"<?php echo $value->phs_code?>",
			 "dishNameWithSpec":"<?php echo $value->product_name?>"
				},
			<?php }?>
			<?php foreach ($setmodels as $value) {?>
				{
			 "categoryName":"套餐",
			 "eDishCode":"<?php echo $value->pshs_code?>",
			 "eDishSkuCode":"<?php echo $value->pshs_code?>",
			 "dishNameWithSpec":"<?php echo $value->set_name?>"
				},
			<?php }?>
			]
		}
	}, '*');
  }
},false);

</script>
