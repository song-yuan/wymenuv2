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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>'时价产品管理','subhead'=>'产品列表','breadcrumbs'=>array(array('word'=>'时价产品管理','url'=>''))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i>产品列表</div>
					<div class="actions">
						<div class="btn-group">
							<?php echo CHtml::dropDownList('selectCategory', $categoryId, $categories , array('class'=>'form-control'));?>
						</div>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th style="width:20%">名称</th>
								<th >图片</th>
								<th>类别</th>
								<th>现价</th>
                                <th>单位</th>
                                <th>是否时价</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td style="width:20%"><?php echo $model->product_name;?></td>
								<td ><img width="100" src="<?php echo $model->main_picture;?>" /></td>
								<td><?php echo $model->category->category_name;?></td>
								<td ><?php echo $model->original_price;?></td>
                                <td ><?php echo $model->product_unit;?></td>
                                <td >
									<div class="r-btn make-switch switch-small" data-on="success" data-off="danger" data-on-label="是" data-off-label="否" is-discount="<?php echo $model->is_discount;?>">
										<input  pid="<?php echo $model->lid;?>" type="checkbox" <?php if($model->is_temp_price) echo 'checked="checked"';?> class="toggle"/>
									</div>
								</td>
								<td class="center">
								<a href="javascript:;" class="edit" data-id="<?php echo $model->lid;?>">编辑明细</a>
								</td>
							</tr>
						<?php endforeach;?>
						<?php endif;?>
						</tbody>
					</table>
						<?php if($pages->getItemCount()):?>
						<div class="row">
							<div class="col-md-5 col-sm-12">
								<div class="dataTables_info">
									共 <?php echo $pages->getPageCount();?> 页  , <?php echo $pages->getItemCount();?> 条数据 , 当前是第 <?php echo $pages->getCurrentPage()+1;?> 页
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
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
	</div>
	<!-- END PAGE CONTENT-->
	<script type="text/javascript">
	$(document).ready(function(){
		$('.r-btn').on('switch-change', function () {
			var isDiscount = $(this).attr('is-discount');
			if(parseInt(isDiscount)){
				alert('该单品正在优惠,不能参与特价!');
				$(this).find('div').removeClass('switch-on').addClass('switch-off');
				return;
			}
			var id = $(this).find('input').attr('pid');
		    $.get('<?php echo $this->createUrl('productTempprice/recommend',array('companyId'=>$this->companyId));?>/id/'+id);
		});
		$('.edit').click(function(){
			var id = $(this).attr('data-id');
			if($(this).parents('.odd').find('.toggle').is(':checked')){
				location.href = '<?php echo $this->createUrl('productTempprice/updatedetail',array('companyId' => $this->companyId));?>/id/'+id;
			}else{
				alert('请开启该单品时价');
			}
			
		});
		$('#selectCategory').change(function(){
			var cid = $(this).val();
			location.href="<?php echo $this->createUrl('productTempprice/index' , array('companyId'=>$this->companyId));?>/cid/"+cid;
		});
	});
	</script>	