<iframe id="cc" name="cc" frameborder="0" width= 100% height= 500px src="https://open-erp.meituan.com/waimai-dish-mapping?signKey=8isnqx6h2xewfmiu&appAuthToken=<?php echo $tokenmodel['appAuthToken'];?>&ePoiId=<?php echo $companyId;?>"></iframe>
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
			 "eDishSkuCode":"<?php echo $value->category_id?>",
			 "dishNameWithSpec":"<?php echo $value->set_name?>"
				},
			<?php }?>
			]
		}
	}, '*');
  }
},false);

</script>
