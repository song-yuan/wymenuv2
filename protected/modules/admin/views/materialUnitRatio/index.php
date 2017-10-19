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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','基础设置'),'subhead'=>yii::t('app','单位系数列表'),'breadcrumbs'=>array(array('word'=>yii::t('app','原料信息'),'url'=>$this->createUrl('product/list' , array('companyId'=>$this->companyId,'type'=>4,))),array('word'=>yii::t('app','原料单位系数'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('product/list' , array('companyId' => $this->companyId,'type' =>'4',)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'material-form',
				'action' => $this->createUrl('materialUnitRatio/delete' , array('companyId' => $this->companyId, 'papage'=>$pages->getCurrentPage()+1)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?>
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','单位系数列表');?></div>
					<div class="actions">
						<a href="<?php echo $this->createUrl('materialUnitRatio/create' , array('companyId' => $this->companyId,'papage'=>$pages->getCurrentPage()+1));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加');?></a>
						<div class="btn-group">
							<button type="submit"  class="btn red" ><i class="fa fa-ban"></i> <?php echo yii::t('app','删除');?></button>
						</div>
						<!-- <a href="<?php echo $this->createUrl('bom/bom' , array('companyId' => $this->companyId));?>" class="btn blue"> <?php echo yii::t('app','返回');?></a> -->
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
				<div class="dataTables_wrapper form-inline">
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th><?php echo yii::t('app','库存单位序号');?></th>
								<th><?php echo yii::t('app','库存单位名称');?></th>
								<th><?php echo yii::t('app','零售单位序号');?></th>
								<th><?php echo yii::t('app','零售单位名称');?></th>
								<th><?php echo yii::t('app','对应系数');?></th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="ids[]" /></td>
								<td><?php echo Common::getStockSortCode($model->stock_unit_id);?></td>
								<td><?php echo Common::getStockName($model->stock_unit_id);?></td>
								<td><?php echo Common::getStockSortCode($model->sales_unit_id);?></td>
								<td><?php echo Common::getSalesName($model->sales_unit_id);?></td>
								<td><?php echo $model->unit_ratio;?></td>
								<td class="center">
								<a href="<?php echo $this->createUrl('materialUnitRatio/update',array('id' => $model->lid , 'companyId' => $model->dpid, 'papage'=>$pages->getCurrentPage()+1));?>"><?php echo yii::t('app','编辑');?></a>
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
									<?php echo yii::t('app','共 ');?><?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?>  , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> , <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?><?php echo yii::t('app','页');?>
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
		<?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->
	<script type="text/javascript">
	$(document).ready(function(){
		$('#material-form').submit(function(){
			if(!$('.checkboxes:checked').length){
				alert("<?php echo yii::t('app','请选择要删除的项');?>");
				return false;
			}
			return true;
		});
	$('.s-btn').on('switch-change', function () {
			var id = $(this).find('input').attr('pid');
		    $.get('<?php echo $this->createUrl('materialUnit/status',array('companyId'=>$this->companyId));?>/id/'+id);
		});
		$('.r-btn').on('switch-change', function () {
			var id = $(this).find('input').attr('pid');
		    $.get('<?php echo $this->createUrl('materialUnit/recommend',array('companyId'=>$this->companyId));?>/id/'+id);
		});
		$('#selectCategory').change(function(){
			var cid = $(this).val();
			location.href="<?php echo $this->createUrl('materialUnit/index' , array('companyId'=>$this->companyId));?>/cid/"+cid;
		});
	});
	</script>	