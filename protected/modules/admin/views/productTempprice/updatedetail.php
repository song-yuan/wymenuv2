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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>'时价活动管理','subhead'=>'时价活动列表','breadcrumbs'=>array(array('word'=>'时价活动管理','url'=>''))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
            <?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'product-form',
				'action' => $this->createUrl('productTempprice/Detaildelete' , array('companyId' => $this->companyId,'psid'=>$productId)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?><!-- 添加 -->
	    <div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i>产品时价活动列表</div>
					<div class="actions">
						<a href="<?php echo $this->createUrl('productTempprice/create',array('companyId'=>$this->companyId,'productId'=>$productId));?>" class="btn blue"><i class="fa fa-pencil"></i> 添加</a>
					    <div class="btn-group">
							<button type="submit"  class="btn red"><i class="fa fa-ban"></i> 删除</button>
						</div><!-- 添加 -->
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
							    <th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th><!-修改添加--->
								<th style="width:30%">活动时间</th>
								<th>特价价格</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
						
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
							    <td><input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="ids[]" /></td><!--修改添加-->
								<td><?php echo $model->begin_time.' - '.$model->end_time;?></td>
								<td><?php  echo $model->price;?></td>
								<td class="center">
								<a href="<?php echo $this->createUrl('productTempprice/update',array('id' => $model->lid , 'companyId' => $model->dpid));?>">编辑</a>
								</td>
							</tr>
						<?php endforeach;?>
						<?php endif;?>
						</tbody>
					</table>
					<!--修改-->
					    <div class="form-actions fluid">
						        <div class="col-md-offset-3 col-md-9">
                                                                        <a href="<?php echo $this->createUrl('productTempprice/index' , array('companyId' => $this->companyId));?>" class="btn default">返回</a>                              
								</div>
						</div>
					<!--修改（确定返回按钮没有）-->
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
        <?php $this->endWidget(); ?>
	</div>
</div>
	<!-- END PAGE CONTENT-->

	<script type="text/javascript">
	$(document).ready(function(){
		$('.r-btn').on('switch-change', function () {
			var id = $(this).find('input').attr('pid');
		    $.get('<?php echo $this->createUrl('productSales/recommend',array('companyId'=>$this->companyId));?>/id/'+id);
		});
		$('#selectCategory').change(function(){
			var cid = $(this).val();
			location.href="<?php echo $this->createUrl('productSales/index' , array('companyId'=>$this->companyId));?>/cid/"+cid;
		});
	});
	</script>	