		<script type="text/javascript" src="metronic/plugins/select2/select2.min.js"></script>
		<script type="text/javascript" src="metronic/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script type="text/javascript" src="metronic/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js"></script>
		<link rel="stylesheet" type="text/css" href="metronic/plugins/select2/select2_metro.css" />
		<!-- END SIDEBAR -->
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
			<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<!-- BEGIN STYLE CUSTOMIZER -->
			<?php $this->beginContent('//layouts/admin/styleCustomizer');?>
			<?php $this->endContent();?>
			<!-- END BEGIN STYLE CUSTOMIZER -->            
			<!-- BEGIN PAGE HEADER-->
			<?php $this->widget('application.modules.brand.components.widgets.PageHeader', array('head'=>'未关注会员列表','subhead'=>'会员列表','breadcrumbs'=>array(array('word'=>'会员管理','url'=>''),array('word'=>'未关注会员列表','url'=>''),)));?>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12 col-sm-12">
					<ul class="nav nav-tabs">
						<li><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('/brand/member/index',array('cid'=>$this->companyId));?>'" data-toggle="tab">已关注会员</a></li>
						<li class="active"><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('/brand/member/unSubList',array('cid'=>$this->companyId));?>'" data-toggle="tab">未关注会员</a></li>
					</ul>
					<?php $form=$this->beginWidget('CActiveForm', array(
						'id'=>'Unbranduser',
						'clientOptions'=>array(
							'validateOnSubmit'=>true,
						),
						'htmlOptions'=>array(
							'class'=>'form-inline'
						),
					)); ?>
					<div>
						<div class="table-responsive">
							<style>
							#search-form tr,#search-form tr td{border:none !important;}
							</style>
							<table id="search-form" class="table">
								<tr>
									<td width="20%"><label class="control-label">按<strong style="color:rgb(133,43,153">会员卡号</strong>或<strong style="color:rgb(133,43,153)">电话号码</strong>查找</label></td>
									<td width="50%">
									<div class="input-group">
									<span class="input-group-addon">号码</span><input type="text" name="id" class="form-control input-medium" value="<?php echo isset($id) && $id ?$id:'';?>"/>
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
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet box purple">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-group"></i>会员列表</div>
							<div class="actions">
								<a href="<?php echo $this->createUrl('/brand/member/createUnSbuUser',array('cid'=>$this->companyId));?>" class="btn blue">
									<i class="fa fa-plus"></i> 添加会员
								</a>
								<a href="javascript:;" class="btn blue" onclick="exportFile();">
									<i class="fa fa-pencil"></i> 导出Excel文件
								</a>						
							</div>
						</div>					
						<div class="portlet-body">
							<div class="table-responsive">
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<th width="10%">会员卡号</th>
										<th width="10%">添加时间</th>
										<th width="10%">电话号码</th>
										<th width="10%">消费积分</th>
										<th width="5%">操作</th>
									</tr>
								</thead>
								<tbody>
									<?php if($models):?>
									<?php foreach($models as $model):?>
										<tr>
											<td><?php echo $model['card_id'];?></td>
											<td><?php echo date('Y-m-d H:i:s',$model['create_time']);?></td>
											<td><?php echo $model['mobile_num'];?></td>
											<td><?php echo $model['consume_point'];?></td>
										    <td><a class="btn default btn-xs blue" title="编辑" href="<?php echo $this->createUrl('/brand/member/updateUnSbuUser',array('cid'=>$this->companyId,'id'=>$model['id']));?>"><i class="fa fa-edit"></i> 编 辑</a></td>
										</tr>
									<?php endforeach;?>	
									<?php else:?>
									<tr>
										<td colspan="10">没有找到数据</td>
									</tr>
									<?php endif;?>
								</tbody>
							</table>
							</div>
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
						<div>
						<?php endif;?>
					</div>
					<?php $this->endWidget(); ?>
					<!-- END EXAMPLE TABLE PORTLET-->
				</div>
			</div>
			<!-- END PAGE CONTENT-->
		</div>
		<!-- END PAGE -->
	</div>
	<script>
		function exportFile(){
			var url = $('#Unbranduser').attr('action');
			dIndex = url.indexOf('&d=1');
			if(dIndex <0){
				url += '&d=1';
			}
			url += $('#Unbranduser').serialize();
			location.href=url;
		}
		jQuery(document).ready(function() {       
	       if (jQuery().datepicker) {
	           $('.date-picker').datepicker({
	           		format: 'yyyy-mm-dd',
	            	language: 'zh-CN',
	                rtl: App.isRTL(),
	                autoclose: true
	            });
	            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
	       }
	       $('.consume_point').change(function(){
	       	  var id = $(this).attr('data-id');
	       	  var point = $(this).val();
	       	  $.ajax({
	       	  	url:'<?php echo $this->createUrl('/brand/member/updateConsumePoint',array('cid'=>$this->companyId))?>&id='+id+'&point='+point,
	       	  	type:'POST',
	       	  	success:function(msg){
	       	  		location.href='<?php echo $this->createUrl('/brand/member/index',array('cid'=>$this->companyId))?>';
	       	  	}
	       	  });
	       });
		});
	</script>	