	<link type="text/css" href="<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-1.8.17.custom.css');?>" rel="stylesheet" />
    <link type="text/css" href="<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-timepicker-addon.css');?>" rel="stylesheet" />
    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-1.7.1.min.js');?>"></script>
	<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-1.8.17.custom.min.js');?>"></script>
	<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-addon.js');?>"></script>
    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-zh-CN.js');?>"></script>
  <style>
        .tabbable .col-md-12{
            padding: 0px !important;
        }
    </style>

		<!-- BEGIN PAGE -->
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
			<!-- END BEGIN STYLE CUSTOMIZER -->            
			<!-- BEGIN PAGE HEADER-->
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','营销活动'),'url'=>$this->createUrl('entityMarket/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','满减优惠'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('entityMarket/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
		<div class="row">
		<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'fullSentPromotion-form',
				'action' => $this->createUrl('fullMinusPromotion/delete' , array('companyId' => $this->companyId,)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?>
		<div class="col-md-12">
		<div class="tabbable tabbable-custom">
			<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','满减优惠活动');?></div>
					<div class="actions">
						<?php if(Yii::app()->user->role <11):?>
						<a href="<?php echo $this->createUrl('copypromotion/copyfullminuspromotion' , array('companyId' => $this->companyId));?>" class="btn yellow"><i class="fa fa-pencil"></i> <?php echo yii::t('app','进入下发');?></a>
						<a href="<?php echo $this->createUrl('copypromotion/clearfullminuspromotion' , array('companyId' => $this->companyId));?>" class="btn red"><i class="fa fa-pencil"></i> <?php echo yii::t('app','清除下发');?></a>
						<?php endif;?>
						<a href="<?php echo $this->createUrl('fullMinusPromotion/create' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加满减优惠活动');?></a>
						<div class="btn-group">
							<button type="submit"  class="btn red" ><i class="fa fa-ban"></i> <?php echo yii::t('app','删除活动');?></button>
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
								<th><?php echo yii::t('app','活动名称');?></th>
								<th><?php echo yii::t('app','活动摘要');?></th>
								<th><?php echo yii::t('app','活动开始日期');?></th>
                                <th><?php echo yii::t('app','活动结束日期');?></th>
                                <th><?php echo yii::t('app','满足金额');?></th>
                                <th><?php echo yii::t('app','优惠减价金额');?></th>
                                <th><?php echo yii::t('app','编辑');?></th>
                                <th><?php echo yii::t('app','备注');?></th>
								
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<!--foreach-->
					
						<?php foreach ($models as $model):?>
								<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="ids[]" /></td>
								<td><?php echo $model->title; ?></td>
								<td><?php echo $model->infor;?></td>
								<td><?php echo $model->begin_time;?></td>
								<td><?php echo $model->end_time;?></td>
								<td><?php echo $model->full_cost;?></td>
								<td><?php echo $model->extra_cost;?></td>
								<td class="center">
								<a href="<?php echo $this->createUrl('fullMinusPromotion/update',array('lid' => $model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','编辑');?></a></td>
								<td><?php echo '';?></td>
								</tr>
						
						<?php endforeach;?>	
						<!-- end foreach-->
						<?php endif;?>
						</tbody>

					</table>
					</div>
						<?php if($pages->getItemCount()):?>
						<div class="row">
							<div class="col-md-5 col-sm-12">
								<div class="dataTables_info">
									<?php echo yii::t('app','共 ');?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?>  , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> ,  <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页');?>
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
       
		<?php $this->endWidget(); ?>
		</div>
		
</div>					<!-- END EXAMPLE TABLE PORTLET-->
</div>				
 <script type="text/javascript">
		$(document).ready(function(){
			$('#normalpromotion-form').submit(function(){
				if(!$('.checkboxes:checked').length){
					alert("<?php echo yii::t('app','请选择要删除的项');?>");
					return false;
				}
				return true;
		});
	    $(function () {
	        $(".ui_timepicker").datetimepicker({
	            //showOn: "button",
	            //buttonImage: "./css/images/icon_calendar.gif",
	            //buttonImageOnly: true,
	            showSecond: true,
	            timeFormat: 'hh:mm:ss',
	            stepHour: 1,
	            stepMinute: 1,
	            stepSecond: 1
	        })
	    });
	     </script>