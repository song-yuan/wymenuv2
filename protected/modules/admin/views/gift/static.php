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
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array( 'head'=>yii::t('app','营销管理'),'subhead'=>yii::t('app','礼品券查询'),'breadcrumbs'=>array(array('word'=>yii::t('app','营销管理'),'url'=>''),array('word'=>yii::t('app','营销品设置'),'url'=>''),array('word'=>yii::t('app','礼品券查询'),'url'=>''))));?>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
			<?php $form=$this->beginWidget('CActiveForm', array(
						'id'=>'Order',
						'clientOptions'=>array(
							'validateOnSubmit'=>true,
						),
						'htmlOptions'=>array(
							'class'=>'form-inline pull-right'
						),
					)); ?>
					<div class="col-md-12">
						<div class="table-responsive">
							<style>
							#search-form tr,#search-form tr td{border:none !important;}
							</style>
							<table id="search-form" class="table">
								<tr>
									<td width="20%"><label class="control-label">按照兑换码查找</label></td>
									<td width="50%">
									<div class="input-group">
									<span class="input-group-addon">兑换码</span><input type="text" name="code" class="form-control input-medium" value="<?php echo $code?$code:'';?>"/>
									</div>
									</td>
									<td width="20%">
										<button type="submit" class="btn green">
											查找 &nbsp; 
											<i class="m-icon-swapright m-icon-white"></i>
										</button>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<?php $this->endWidget(); ?>
			</div>
		<div class="row">
			<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','领取详情');?> 总数:<?php echo $pages->getItemCount();?></div>
					<div class="actions">
						
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th><?php echo yii::t('app','会员头像');?></th>
								<th><?php echo yii::t('app','会员名');?></th>
								<th><?php echo yii::t('app','会员卡号');?></th>
								<th><?php echo yii::t('app','兑换码');?></th>
								<th><?php echo yii::t('app','是否兑换');?></th>
								<th><?php echo yii::t('app','是否过期');?></th>
                                <th><?php echo yii::t('app','操作');?></th>                                                                
						</thead>
						<tbody>
						<?php if($models) :?>
						<!--foreach-->
					
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><img src="<?php echo $model->branduser->head_icon;?>" width="100" height="100"/></td>
								<td><?php echo $model->branduser->nickname; ?></td>
								<td><?php echo substr($model->branduser->card_id,5); ?></td>
								<td><?php echo $model->code;?></td>
								<td><?php if($model->is_used){ echo '已兑换';}else{ echo '未兑换';};?></td>
								<td><?php if($model->gift->begin_time < date('Y-m-d H:i:s',time())&&date('Y-m-d H:i:s',time()) < $model->gift->end_time){ $expire=0; echo '未过期';}else{ $expire=1; echo '已过期';}?></td>
								<td class="center">
									<?php if(!$model->is_used&&!$expire):?>
									<a href="<?php echo $this->createUrl('gift/exchange',array('lid'=>$model->lid, 'companyId'=>$model->dpid));?>"><?php echo yii::t('app','核销');?></a>
									<?php endif;?>
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
		
</div>					<!-- END EXAMPLE TABLE PORTLET-->
</div>				
 <script type="text/javascript">
		$(document).ready(function(){
			
		});
</script>