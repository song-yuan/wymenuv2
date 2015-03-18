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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>'套餐管理','subhead'=>'套餐列表','breadcrumbs'=>array(array('word'=>'套餐管理','url'=>''),array('word'=>'套餐列表','url'=>''))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
            <?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'product-form',
				'action' => $this->createUrl('productSet/delete' , array('companyId' => $this->companyId)),
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
					<div class="caption"><i class="fa fa-globe"></i>打印方式列表</div>
					<div class="actions">
						<a href="<?php echo $this->createUrl('productSet/create' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> 添加</a>
						<!-- <div class="btn-group">
							<a class="btn green" href="#" data-toggle="dropdown">
							<i class="fa fa-cogs"></i> Tools
							<i class="fa fa-angle-down"></i>
							</a>
							<ul class="dropdown-menu pull-right">
								<li><a href="#"><i class="fa fa-ban"></i> 删除</a></li>
							</ul>
						</div> -->
                                                <div class="btn-group">
							<button type="submit"  class="btn red" ><i class="fa fa-ban"></i> 删除</button>
						</div>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
					<?php if($models):?>
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th>套餐名称</th>
                                                                <th>主图片</th>
                                                                <th>星级</th>
                                                                <th>会员打折</th>
								<th>特价</th>
								<th>优惠</th>
								<th>沽清</th>
								<th>下单数</th>
								<th>点赞数</th>
								<th>&nbsp;</th>
                                                                <th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
						
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="ids[]" /></td>
								<td ><?php echo $model->set_name ;?></td>
								<td ><img width="100" src="<?php echo $model->main_picture;?>" /></td>
                                                                <td><?php echo $model->rank;?></td>
								<td>
									<div class="s-btn make-switch switch-small" data-on="success" data-off="danger" data-on-label="是" data-off-label="否">
										<input pid="<?php echo $model->lid;?>" <?php if($model->is_member_discount) echo 'checked="checked"';?> type="checkbox"  class="toggle"/>
									</div>
								</td>
                                                                <td>
									<div class="s-btn make-switch switch-small" data-on="success" data-off="danger" data-on-label="是" data-off-label="否">
										<input pid="<?php echo $model->lid;?>" <?php if($model->is_special) echo 'checked="checked"';?> type="checkbox"  class="toggle"/>
									</div>
								</td>
                                                                <td>
									<div class="s-btn make-switch switch-small" data-on="success" data-off="danger" data-on-label="是" data-off-label="否">
										<input pid="<?php echo $model->lid;?>" <?php if($model->is_discount) echo 'checked="checked"';?> type="checkbox"  class="toggle"/>
									</div>
								</td>
                                                                <td>
									<div class="s-btn make-switch switch-small" data-on="success" data-off="danger" data-on-label="是" data-off-label="否">
										<input pid="<?php echo $model->lid;?>" <?php if($model->status) echo 'checked="checked"';?> type="checkbox"  class="toggle"/>
									</div>
								</td>
                                                                <td ><?php echo $model->order_number ;?></td>
								<td><?php echo $model->favourite_number;?></td>
								<td class="center">
								<a href="<?php echo $this->createUrl('productSet/update',array('lid' => $model->lid , 'companyId' => $model->dpid));?>">编辑</a>
								</td>
                                                                <td class="center">
								<a href="<?php echo $this->createUrl('productSet/detailindex',array('lid' => $model->lid , 'companyId' => $model->dpid));?>">编辑明细</a>
								</td>
							</tr>
						<?php endforeach;?>
						</tbody>
						<?php else:?>
						<tr><td>还没有添加套餐</td></tr>
						<?php endif;?>
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
            <?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->