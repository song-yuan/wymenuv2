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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','进销存管理'),'subhead'=>yii::t('app','退货单列表'),'breadcrumbs'=>array(array('word'=>yii::t('app','库存管理'),'url'=>$this->createUrl('bom/bom' , array('companyId'=>$this->companyId,'type'=>2,))),array('word'=>yii::t('app','退货单管理'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('bom/bom' , array('companyId' => $this->companyId,'type' => '2',)))));?>
	<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-1.8.17.custom.css');?>
	<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-timepicker-addon.css');?>
	<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-1.7.1.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-1.8.17.custom.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-addon.js');?>
	<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-zh-CN.js');?>

	<!-- END PAGE HEADER-->
	<style>
		.find form input{display: inline;width:180px;}
	</style>
	<div class="find">
		<form action="" method="post">
			<input type="text" name="mid" class="form-control" placeholder="厂商名称" value="<?php echo isset($mid) && $mid ?$mid:'';?>" />
			<input type="text" name="refund" class="form-control" placeholder="退货单号" value="<?php echo isset($refund) && $refund ?$refund:'';?>" />
			<input type="text" name="storage" class="form-control" placeholder="入库单号" value="<?php echo isset($storage) && $storage ?$storage:'';?>" />
			<input type="text" name="begintime" class="ui_timepicker form-control" placeholder="起始日期" value="<?php echo isset($begintime) && $begintime ?$begintime:'';?>" />
			<input type="text" name="endtime" class="ui_timepicker form-control" placeholder="结束日期" value="<?php echo isset($endtime) && $endtime ?$endtime:'';?>" />
			<button type="submit" class="btn green">
				查找 &nbsp;
				<i class="m-icon-swapright m-icon-white"></i>
			</button>
		</form>
	</div>
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'material-form',
				'action' => $this->createUrl('refundOrder/delete' , array('companyId' => $this->companyId)),
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','退货单列表');?></div>
					<div class="actions">
						<a href="<?php echo $this->createUrl('refundOrder/create' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加');?></a>
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
								<th style="width:16%"><?php echo yii::t('app','供应厂商');?></th>
								<th><?php echo yii::t('app','退货组织');?></th>
								<th><?php echo yii::t('app','退货单号');?></th>
								<th><?php echo yii::t('app','经办人员');?></th>
								<th><?php echo yii::t('app','入库编号');?></th>
								<th><?php echo yii::t('app','退货日期');?></th>
								<th><?php echo yii::t('app','备注');?></th>
								<th><?php echo yii::t('app','审核状态');?></th>
								<th><?php echo yii::t('app','退货详情');?></th>
								<th><?php echo yii::t('app','操作');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="ids[]" /></td>
								<td style="width:16%"><?php echo Common::getmfrName($model->manufacturer_id);?></td>
								<td ><?php echo Helper::getCompanyName($model->organization_id);?></td>
								<td><?php echo $model->refund_account_no;?></td>
								<td><?php echo Common::getuserName($model->admin_id);?></td>
								<td><?php echo $model->storage_account_no;?></td>
								<td><?php echo $model->refund_date;?></td>
								<td><?php echo $model->remark;?></td>
								<td style="color: red;"><?php if ($model->status==0) echo "编辑中..."; elseif ($model->status==1) echo "已审核";elseif ($model->status==3) echo "审核不通过";elseif ($model->status==2) echo "待审核";elseif ($model->status==4) echo "已退货";?></td>
								<td class="center">
									<a href="<?php echo $this->createUrl('refundOrder/detailindex',array('lid' => $model->lid , 'companyId' => $model->dpid, 'status'=>$model->status,));?>"><?php echo yii::t('app','订单详情');?></a>
								</td>
								<td class="center">
								<?php if($model->status == 0 || $model->status == 3):?>
									<a href="<?php echo $this->createUrl('refundOrder/update',array('lid' => $model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','编辑');?></a>
								<?php endif;?>
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
		    $.get('<?php echo $this->createUrl('refundOrder/status',array('companyId'=>$this->companyId));?>/id/'+id);
		});
		$('.r-btn').on('switch-change', function () {
			var id = $(this).find('input').attr('pid');
		    $.get('<?php echo $this->createUrl('refundOrder/recommend',array('companyId'=>$this->companyId));?>/id/'+id);
		});
		$('#selectCategory').change(function(){
			var cid = $(this).val();
			location.href="<?php echo $this->createUrl('refundOrder/index' , array('companyId'=>$this->companyId));?>/cid/"+cid;
		});
	});
	$(function () {
		$(".ui_timepicker").datetimepicker({
			showSecond: true,
			timeFormat: 'hh:mm:ss',
			stepHour: 1,
			stepMinute: 1,
			stepSecond: 1
		})
	});
	</script>	