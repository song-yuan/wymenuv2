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
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','线上活动'),'url'=>$this->createUrl('discount/list' , array('companyId'=>$this->companyId,'type'=>1,))),array('word'=>yii::t('app','微信红包设置'),'url'=>''),array('word'=>yii::t('app','微信红包查询'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('discount/list' , array('companyId' => $this->companyId,'type'=>1)))));?>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
		<div class="row">
		<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'wxredpacket-form',
				'action' => $this->createUrl('wxRedpacket/delete' , array('companyId' => $this->companyId,)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?>
		<div class="col-md-12">
		<div class="tabbable tabbable-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('wxRedpacket/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','微信红包设置');?></a></li>
				<!--  <li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('wxRedpacket/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','红包发送规则设置');?></a></li> -->
				<!-- <li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('/brand/gift',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','特价优惠');?></a></li>
				<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('/brand/wxcard',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','代金券');?></a></li>
			 --></ul>
		<div class="tab-content">
			<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','微信红包设置');?></div>
					<div class="actions">
					<!-- <p><input type="text" name="datetime" class="ui_timepicker" value=""></p> -->
						<a href="<?php echo $this->createUrl('wxRedpacket/create' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加微信红包');?></a>
						<div class="btn-group">
							<button type="submit"  class="btn red" ><i class="fa fa-ban"></i> <?php echo yii::t('app','删除红包');?></button>
						</div>
					</div>
					<!-- <div class="btn-group">
							 <input type="text" class="form-control" name="订单号" id="Did" placeholder="" value="<?php echo yii::t('app','店铺：');?><?php echo Helper::getCompanyName($this->companyId);?>"  onfocus=this.blur()> 
					</div>
                    <div class="btn-group">
				
						<div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
							<input type="text" class="form-control" name="begtime" id="begin_time" placeholder="<?php echo yii::t('app','起始时间');?>" value="<?php echo ""; ?>">  
							<span class="input-group-addon">~</span>
							   <input type="text" class="form-control" name="endtime" id="end_time" placeholder="<?php echo yii::t('app','终止时间');?>"  value="<?php echo "";?>">           
						</div>  
			         </div>	
					
					    <div class="btn-group">
							<button type="submit" id="btn_time_query" class="btn red" ><i class="fa fa-pencial"></i><?php echo yii::t('app','查 询');?></button>
							<!--  <a href="#" class="btn green" ><i class="fa fa-pencial"></i><?php echo yii::t('app','打 印');?></a>		  --
					    </div>		 -->
					
				</div>
				<div class="portlet-body" id="table-manage">
				<div class="dataTables_wrapper form-inline">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th><?php echo yii::t('app','红包名称');?></th>
								
								<!--  <th><?php echo yii::t('app','红包发送人ID');?></th>-->
								<th><?php echo yii::t('app','红包发送个数');?></th>
								<th><?php echo yii::t('app','红包领用截止日期');?></th>
                                <th><?php echo yii::t('app','编辑');?></th>                                                                
                                <th><?php echo yii::t('app','添加代金券');?></th>
                                <th><?php echo yii::t('app','发送规则设置');?></th>
                                <th><?php echo yii::t('app','备注');?></th>
								
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<!--foreach-->
					
						<?php foreach ($models as $model):?>
								<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="ids[]" /></td>
								<td><?php echo $model->redpacket_name; ?></td>
								<!--  <td><?php echo $model->send_userlid;?></td>-->
								<td><?php echo $model->total;?></td>
								<td><?php echo $model->end_time;?></td>
								<td class="center">
								<a href="<?php echo $this->createUrl('wxRedpacket/update',array('lid' => $model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','编辑');?></a></td>
								<td class="center">
								<a href="<?php echo $this->createUrl('wxRedpacket/detailindex',array('lid' => $model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','添加代金券营销品');?></a></td>
								
								 <td class="center">
								<a href="<?php echo $this->createUrl('wxRedpacket/detailrules',array('lid' => $model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','规则设置');?></a></td>
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
        </div>
		<?php $this->endWidget(); ?>
		</div>
					<!-- END EXAMPLE TABLE PORTLET-->
</div>	
</div>			
 <script type="text/javascript">
		$(document).ready(function(){
			$('#wxredpacket-form').submit(function(){
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