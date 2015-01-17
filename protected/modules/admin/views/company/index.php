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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>'企业管理','subhead'=>'企业列表','breadcrumbs'=>array(array('word'=>'企业管理','url'=>''))));?>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
		<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i>公司列表</div>
					<div class="actions">
						<?php if(Yii::app()->user->role == User::POWER_ADMIN):?>
						<a href="<?php echo $this->createUrl('company/create');?>" class="btn blue"><i class="fa fa-pencil"></i> 添加</a>
						<?php endif;?>
						<!-- <div class="btn-group">
							<a class="btn green" href="#" data-toggle="dropdown">
							<i class="fa fa-cogs"></i> Tools
							<i class="fa fa-angle-down"></i>
							</a>
							<ul class="dropdown-menu pull-right">
								<li><a href="#"><i class="fa fa-ban"></i> 冻结</a></li>
							</ul>
						</div> -->
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th>ID</th>
								<th>公司名称</th>
								<th >logo</th>
								<th>联系人</th>
								<th >手机</th>
								<th>电话</th>
								<th >email</th>
								<th >创建时间</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model->company_id;?>" name="companyIds[]" /></td>
								<td ><?php echo $model->company_id;?></td>
								<td><a href="<?php echo $this->createUrl('company/update',array('companyId' => $model->company_id));?>" ><?php echo $model->company_name;?></a></td>
								<td ><img width="100" src="<?php echo $model->logo;?>" /></td>
								<td ><?php echo $model->contact_name;?></td>
								<td ><?php echo $model->mobile;?></td>
								<td ><?php echo $model->telephone;?></td>
								<td ><?php echo $model->email;?></td>
								<td><?php echo date('Y-m-d H:i:s',$model->create_time);?></td>
								<td class="center">
									<div class="btn-group">
										<a class="btn green" href="#" data-toggle="dropdown">
										操作
										<i class="fa fa-angle-down"></i>
										</a>
										<ul class="dropdown-menu pull-right">
											<li><a href="<?php echo $this->createUrl('company/update',array('companyId' => $model->company_id));?>">编辑</a></li>
											<li><a href="<?php echo $this->createUrl('companyWifi/index' , array('companyId' => $model->company_id));?>">WIFI</a></li>
											<li><a href="<?php echo $this->createUrl('site/index',array('companyId' => $model->company_id));?>">位置</a></li>
											<li><a href="<?php echo $this->createUrl('order/index',array('companyId' => $model->company_id));?>">订单</a></li>
											<li><a href="<?php echo $this->createUrl('product/index',array('companyId' => $model->company_id));?>">产品</a></li>
											<li><a href="<?php echo $this->createUrl('user/index',array('companyId' => $model->company_id));?>">管理员</a></li>
										</ul>
									</div>
									
								</td>
							</tr>
						<?php endforeach;?>
						<?php endif;?>
						</tbody>
					</table>
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
	</div>
	<!-- END PAGE CONTENT-->