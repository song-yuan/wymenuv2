<div class="page-content">
<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">关系图</h4>
					</div>
					<div class="modal-body">
						<img alt="" src="">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn default" data-dismiss="modal">确定</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
	<!-- BEGIN PAGE CONTENT-->
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','外卖设置'),'url'=>$this->createUrl('waimai/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','美团外卖'),'url'=>$this->createUrl('waimai/index' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','菜品映射'),'url'=>$this->createUrl('waimai/caipinyingshe' , array('companyId'=>$this->companyId,'type'=>0)))),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('waimai/index' , array('companyId' => $this->companyId,'type' =>'0')))));?>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet purple box">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-cogs"></i><?php echo yii::t('app','菜品映射');?></div>
				</div>
				<iframe id="cc" name="cc" frameborder="0" width= 100% height= 700px src="<?php echo 'https://open-erp.meituan.com/waimai-dish-mapping?sign='.$sign.'&appAuthToken='.$tokenmodel['appAuthToken'].'&ePoiId='.$companyId.'&timestamp='.$timestamp;?>"></iframe>
			</div>
		</div>
	</div>
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
