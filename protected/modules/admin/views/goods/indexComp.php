<style>
	.shangjia{
		background-color: #00ffad;
		color: #fff;
		font-weight:600;
		border-radius: 5px;
	}
	.xiajia{
		background-color: #e02222;
		color: #fff;
		font-weight:600;
		border-radius: 5px;
	}
</style>
<div class="page-content">
	<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
	<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Modal title</h4>
				</div>
				<div class="modal-body">
					Widget settings form goes here
				</div>
				<div class="modal-footer">
					<button type="button" class="btn blue">Save changes</button>
					<button type="button" class="btn default" data-dismiss="modal">Close</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','仓库设置'),'url'=>$this->createUrl('tmall/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','商品列表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('tmall/list' , array('companyId' => $this->companyId,'type' => '0',)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-globe"></i>
						<?php echo yii::t('app','商品列表');?>
					</div>
					<div class="actions">
						<div class="btn-group">
							<select name="dpid" id="dpid" class="btn yellow">
								<option value=""> - 全部 - </option>
								<?php foreach ($dpids as $key => $dp_id): ?>
									<option value="<?php echo $dp_id->dpid; ?>" <?php if ($dpid == $dp_id->dpid) {echo 'selected';} ?> > - <?php echo $dp_id->company_name; ?> - </option>
								<?php endforeach ?>
						</select>
						</div>
						<div class="btn-group">
							<?php echo CHtml::dropDownList('selectCategory', $categoryId, $categories , array('class'=>'form-control'));?>
						</div>
						<div class="btn-group">
							<button type="button" id="saveAll"  class="btn yellow" ><i class="glyphicon glyphicon-floppy-saved"></i> <?php echo yii::t('app','批量保存');?></button>
						</div>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
				<div class="dataTables_wrapper form-inline">
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
                                <th><?php echo yii::t('app','排序号');?></th>
                                <th><?php echo yii::t('app','名称');?></th>
								<th><?php echo yii::t('app','图片');?></th>
								<th><?php echo yii::t('app','类别');?></th>
								<th><?php echo yii::t('app','仓库现价');?></th>
								<th><?php echo yii::t('app','仓库会员价');?></th>
								<th><?php echo yii::t('app','品牌定价');?></th>
                                <th><?php echo yii::t('app','单位');?></th>
                                <th><?php echo yii::t('app','会员折扣');?></th>
								<th><?php echo yii::t('app','可折');?></th>
                                <th><?php echo yii::t('app','可售');?></th>
                                <th><?php echo yii::t('app','操作');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><?php if(Yii::app()->user->role >11):?><?php else:?><input type="checkbox" class="checkboxes" value="<?php echo $model['lid'];?>" name="ids[]" /><?php endif;?></td>
								<td ><?php echo $model['sort'];?></td>
                                <td ><?php echo $model['goods_name'];?></td>
								<td ><img width="100" src="<?php echo $model['main_picture'];?>" /></td>
								<td ><?php echo $model['category_name'];?></td>
								<td ><?php echo $model['original_price'];?></td>
								<td ><?php echo $model['member_price'];?></td>
								<td class="center">
									<input type="number" name="price" value="<?php echo $model['price']; ?>">
								</td>
                                <td ><?php echo $model['goods_unit'];?></td>
                                <td ><?php echo $model['is_member_discount']=='0'?yii::t('app','否'):yii::t('app','是');?></td>
								<td ><?php echo $model['is_discount']=='0'?yii::t('app','否'):yii::t('app','是');?></td>
                                <td ><?php echo $model['is_show']=='0'?yii::t('app','否'):yii::t('app','是');?></td>
                                <td >
									<button type="button" class="btn green save" lid="<?php echo $model['lid']; ?>"><i class="glyphicon glyphicon-floppy-saved"></i> <?php echo yii::t('app','保存');?></button>
                                </td>
							</tr>
						<?php endforeach;?>
						<?php endif;?>
						</tbody>
					</table>
					</div>
						<?php if($pages->getItemCount()):?>
						<div class="row">
							<div class="col-md-5 col-sm-12">
								<div class="dataTables_info">
									<?php echo yii::t('app','共');?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?> , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> , <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页');?>
								</div>
							</div>
							<div class="col-md-7 col-sm-12">
								<div class="dataTables_paginate paging_bootstrap">
								<?php $this->widget('CLinkPager', array(
									'pages' => $pages,
									'header'=>'',
									'firstPageLabel' => '<<',
									'lastPageLabel' => '>>',
									'firstPageCssClass' => '',
									'lastPageCssClass' => '',
									'maxButtonCount' => 8,
									'nextPageCssClass' => '',
									'previousPageCssClass' => '',
									'prevPageLabel' => '<',
									'nextPageLabel' => '>',
									'selectedPageCssClass' => 'active',
									'internalPageCssClass' => '',
									'hiddenPageCssClass' => 'disabled',
									'htmlOptions'=>array('class'=>'pagination pull-right')
								));
								?>
								</div>
							</div>
						</div>
						<?php endif;?>
					</div>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
	</div>
	<!-- END PAGE CONTENT-->
	<script type="text/javascript">
	$(document).ready(function(){
		$('#selectCategory').change(function(){
			var cid = $(this).val();
			var dpid = $('#dpid').val();
			location.href="<?php echo $this->createUrl('goods/indexComp' , array('companyId'=>$this->companyId));?>/cid/"+cid+"/dpid/"+dpid;
		});
		$('#dpid').change(function(){
			var cid = $('#selectCategory').val();
			var dpid = $(this).val();
			location.href="<?php echo $this->createUrl('goods/indexComp' , array('companyId'=>$this->companyId));?>/dpid/"+dpid+"/cid/"+cid;
		});
		$('#saveAll').on('click',function(){
			if(confirm("确认保存勾选商品的品牌价格?")){
				if(!$('.checkboxes:checked').length){
					alert("<?php echo yii::t('app','请请对要保存的商品进行勾选!');?>");
				}else{
					var prices = [];
					$('.checkboxes:checked').each(function() {
						var lid = $(this).val();
						var price = $(this).parent().parent().parent().parent().children('.center').children('input').val();
						// alert(lid+'_'+price);
						prices.push(lid+'_'+price)
					});
					// console.log(prices);
					var str = prices.join(',');
					// alert(str);
					$.ajax({
						url: '<?php echo $this->createUrl('goods/indexComp',array('companyId'=>$this->companyId)) ?>',
						dataType: 'json',
						data: {str: str},
						success:function(data){
							if (data==1) {
								layer.msg('保存成功!!!');
							}else{
								layer.msg('保存失败!!!');
							}
						}
					});
				}
			}
		});
		$('.save').on('click',function(){
			if(confirm("确认保存商品的品牌定价?")){
				var prices = [];
				var lid = $(this).attr('lid');
				var price = $(this).parent().parent().children('.center').children('input').val();
				// alert(lid+'_'+price);
				prices.push(lid+'_'+price)
				var str = prices.join(',');
				$.ajax({
					url: '<?php echo $this->createUrl('goods/indexComp',array('companyId'=>$this->companyId)) ?>',
					dataType: 'json',
					data: {str: str},
					success:function(data){
						if (data==1) {
							layer.msg('保存成功!!!');
						}else{
							layer.msg('保存失败!!!');
						}
					}
				});
			}
		});
	});
	</script>	