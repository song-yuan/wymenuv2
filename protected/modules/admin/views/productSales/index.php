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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','产品管理'),'subhead'=>yii::t('app','产品列表'),'breadcrumbs'=>array(array('word'=>yii::t('app','产品管理'),'url'=>''))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','产品列表');?></div>
					<div class="actions">
						<div class="btn-group">
							<?php echo CHtml::dropDownList('selectCategory', $categoryId, $categories , array('class'=>'form-control'));?>
						</div>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
				<div class="dataTables_wrapper form-inline">
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th style="width:20%"><?php echo yii::t('app','名称');?></th>
								<th ><?php echo yii::t('app','图片');?></th>
								<th><?php echo yii::t('app','类别');?></th>
								<th><?php echo yii::t('app','原价');?></th>
                                <th><?php echo yii::t('app','单位');?></th>
                                <th><?php echo yii::t('app','是否优惠');?></th>
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
									<div class="r-btn make-switch switch-small" data-on="success" data-off="danger" data-on-label="<?php echo yii::t('app','是');?>" data-off-label="<?php echo yii::t('app','否');?>" is-special="<?php echo $model->is_special;?>">
										<input  pid="<?php echo $model->lid;?>" type="checkbox" <?php if($model->is_discount) echo 'checked="checked"';?> class="toggle"/>
									</div>
								</td>
								<td class="center">
								<a href="javascript:;" class="edit" data-id="<?php echo $model->lid;?>"><?php echo yii::t('app','编辑明细');?></a>
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
								<?php echo yii::t('app','共 ');?><?php echo $pages->getPageCount();?> <?php echo yii::t('app','页 ');?> , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> , <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页');?>
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
		$('.r-btn').on('switch-change', function () {
			var isSpecial = $(this).attr('is-special');
			if(parseInt(isSpecial)){
				alert("<?php echo yii::t('app','该单品正在特价,不能参与优惠!');?>");
				$(this).find('input[type="checked"]').checked = false;
				$(this).find('div').removeClass('switch-on').addClass('switch-off');
				return;
			}
			var id = $(this).find('input').attr('pid');
		    $.get('<?php echo $this->createUrl('productSales/recommend',array('companyId'=>$this->companyId));?>/id/'+id);
		});
		$('.edit').click(function(){
			var id = $(this).attr('data-id');
			if($(this).parents('.odd').find('.toggle').is(':checked')){
				location.href = '<?php echo $this->createUrl('productSales/updatedetail',array('companyId' => $this->companyId));?>/id/'+id;
			}else{
				alert("<?php echo yii::t('app','请开启该单品优惠');?>");
			}
			
		});
		$('#selectCategory').change(function(){
			var cid = $(this).val();
			location.href="<?php echo $this->createUrl('productSales/index' , array('companyId'=>$this->companyId));?>/cid/"+cid;
		});
	});
	</script>	