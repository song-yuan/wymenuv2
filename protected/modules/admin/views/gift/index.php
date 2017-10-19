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
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array( 'breadcrumbs'=>array(array('word'=>yii::t('app','线上活动'),'url'=>$this->createUrl('discount/list' , array('companyId'=>$this->companyId,'type'=>1,))),array('word'=>yii::t('app','营销品设置'),'url'=>''),array('word'=>yii::t('app','礼品券查询'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('discount/list' , array('companyId' => $this->companyId,'type'=>1)))));?>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
		<div class="row">
		<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'cupon-form',
				'action' => $this->createUrl('gift/delete' , array('companyId' => $this->companyId,)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?>
		<div class="col-md-12">
		<div class="tabbable tabbable-custom">
			<ul class="nav nav-tabs">
				<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('cashcard/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','整体设置');?></a></li>
				<!-- <li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('normalpromotion/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','普通优惠');?></a></li> -->
				<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('privatepromotion/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','特价优惠');?></a></li>
				<!-- <li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('fullSentPromotion/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','满送优惠');?></a></li>
				<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('fullMinusPromotion/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','满减优惠');?></a></li> -->
				<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('cupon/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','代金券');?></a></li>
				<li class="active"><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('gift/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','礼品券');?></a></li>
				<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('wxcard/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','微信卡券');?></a></li>
			</ul>
		<div class="tab-content">
			<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','礼品券设置');?></div>
					<div class="actions">
					<!-- <p><input type="text" name="datetime" class="ui_timepicker" value=""></p> -->
						<a href="<?php echo $this->createUrl('gift/create' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加礼品券');?></a>
						<div class="btn-group">
							<button type="submit"  class="btn red" ><i class="fa fa-ban"></i> <?php echo yii::t('app','删除礼品券');?></button>
						</div>
						<a href="<?php echo $this->createUrl('gift/code' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','核销礼品券');?></a>
					</div>
					
					
				</div>
				<div class="portlet-body" id="table-manage">
				<div class="dataTables_wrapper form-inline">
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th><?php echo yii::t('app','礼品券名称');?></th>
								<th><?php echo yii::t('app','图片');?></th>
								<th><?php echo yii::t('app','摘要');?></th>
								<th><?php echo yii::t('app','礼品券金额');?></th>
								<th><?php echo yii::t('app','库存');?></th>
								<th><?php echo yii::t('app','会员领取次数');?></th>
								<th><?php echo yii::t('app','有效期');?></th>
                                <th><?php echo yii::t('app','操作');?></th>                                                                
						</thead>
						<tbody>
						<?php if($models) :?>
						<!--foreach-->
					
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="ids[]" /></td>
								<td><?php echo $model->title; ?></td>
								<td><img src='<?php echo $model->gift_pic;?>' width="100" height="100"/></td>
								<td><?php echo $model->intro;?></td>
								<td><?php echo $model->price;?></td>
								<td><?php echo $model->stock;?></td>
								<td><?php echo $model->count;?></td>
								<td><?php echo $model->begin_time.'至'.$model->end_time;?></td>
								<td class="center">
									<a href="<?php echo $this->createUrl('gift/update',array('lid' => $model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','编辑');?></a>
									<a href="<?php echo $this->createUrl('gift/static',array('lid' => $model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','统计');?></a>
								</td>
							</tr>
						
						<?php endforeach;?>	
						<!-- end foreach-->
						<?php else:?>
						<tr>
						  <td colspan="9">没有查询到数据</td>
						</tr>
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