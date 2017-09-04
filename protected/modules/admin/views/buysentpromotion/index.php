	<link type="text/css" href="<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-1.8.17.custom.css');?>" rel="stylesheet" />
    <link type="text/css" href="<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-timepicker-addon.css');?>" rel="stylesheet" />
    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-1.7.1.min.js');?>"></script>
	<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-1.8.17.custom.min.js');?>"></script>
	<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-addon.js');?>"></script>
    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-zh-CN.js');?>"></script>




<!-- 		<script type="text/javascript" src="metronic/plugins/select2/select2.min.js"></script>
		<link rel="stylesheet" type="text/css" href="metronic/plugins/select2/select2_metro.css" />
		<link rel="stylesheet" type="text/css" href="metronic/plugins/select2/inserthtml.com.radios.css" />
		<script src="metronic/plugins/bootbox/bootbox.min.js" type="text/javascript" ></script>
		 --><!-- END SIDEBAR -->
		<!-- BEGIN PAGE -->
<style>
	.modal-dialog{
		width: 80%;
		height: 70%;
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
			<!-- END BEGIN STYLE CUSTOMIZER -->            
			<!-- BEGIN PAGE HEADER-->
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','营销活动'),'url'=>$this->createUrl('entityMarket/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','买送'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('entityMarket/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
		<div class="row">
		<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'privatepromotion-form',
				'action' => $this->createUrl('buysentpromotion/delete' , array('companyId' => $this->companyId,)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?>
		<div class="col-md-12">
		<div class="tabbable tabbable-custom">
			
		<div class="tab-content">
			<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','买送活动');?></div>
					<div class="actions">
						<a href="<?php echo $this->createUrl('buysentpromotion/create' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加买送活动');?></a>
						<div class="btn-group">
							<button type="submit"  class="btn red" ><i class="fa fa-ban"></i> <?php echo yii::t('app','删除活动');?></button>
						</div>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th><?php echo yii::t('app','活动名称');?></th>
								<th><?php echo yii::t('app','活动摘要');?></th>
								<th><?php echo yii::t('app','活动类型');?></th>
								<th><?php echo yii::t('app','活动开始日期');?></th>
                                <th><?php echo yii::t('app','活动结束日期');?></th>
                                <th><?php echo yii::t('app','是否生效');?></th>
                                <th><?php echo yii::t('app','编辑');?></th>                                                                
                                <th><?php echo yii::t('app','编辑明细');?></th>
                                <th><?php echo yii::t('app','备注');?></th>
                                <th></th>
								
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<!--foreach-->
					
						<?php foreach ($models as $model):?>
								<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="ids[]" /></td>
								<td><?php echo $model->promotion_title; ?></td>
								<td><?php echo $model->promotion_abstract;?></td>
								<td><?php switch ($model->promotion_type){case 0:echo yii::t('app','独享');break;case 1:echo yii::t('app','共享');break;default:echo '';break;} ?></td>
								
								<td><?php echo $model->begin_time;?></td>
								<td><?php echo $model->end_time;?></td>
								<td><?php switch ($model->is_available){
										case 0:echo yii::t('app','不生效');break;
										case 1:echo yii::t('app','生效只显示在POS端');break;
										case 2:echo yii::t('app','生效且显示在微信端');break;
										case 3:echo yii::t('app','生效且微信及POS都显示');break;
										case 4:echo yii::t('app','生效只显示在微信堂食');break;
										case 5:echo yii::t('app','生效只显示在微信外卖');break;
										default:echo '';break;
									} ?>
								</td>
								<td class="center">
									<a href="<?php echo $this->createUrl('buysentpromotion/update',array('lid' => $model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','编辑');?></a>
								</td>
								
								<td style="width:10%" class="add_btn" pid="<?php echo $model->lid;?>" compid="<?php echo $model->dpid;?>" prodname="<?php echo $model->promotion_title;?>" phscode="<?php echo $model->sole_code;?>" data-toggle="modal"> <a href="" ><?php echo yii::t('app','批量添加活动产品');?></a>
								</td>
								<td><a href="<?php echo $this->createUrl('buysentpromotion/detailindex',array('lid' => $model->lid , 'companyId' => $model->dpid ,'typeId'=>'product' , 'prodname'=>$model->promotion_title));?>"><?php echo yii::t('app','修改活动产品');?></a></td>
								<td></td>
								</tr>
						
						<?php endforeach;?>	
						<!-- end foreach-->
						<?php endif;?>
						</tbody>

					</table>
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
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
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
		var $modal = $('.modal');
	    $('.add_btn').on('click', function(){
	    	pid = $(this).attr('pid');
	    	compid = $(this).attr('compid');
	    	prodname = $(this).attr('prodname');
	    	phscode = $(this).attr('phscode');
	        $modal.find('.modal-content').load('<?php echo $this->createUrl('buysentpromotion/addprod',array('companyId'=>$this->companyId));?>/pid/'+pid+'/prodname/'+prodname+'/phscode/'+phscode, '', function(){
	          $modal.modal();
	        });
	    });
});
</script>