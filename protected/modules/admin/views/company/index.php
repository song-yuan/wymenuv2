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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','店铺管理'),'subhead'=>yii::t('app','店铺列表'),'breadcrumbs'=>array(array('word'=>yii::t('app','店铺管理'),'url'=>''))));?>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
            <?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'company-form',
				'action' => $this->createUrl('company/delete', array('companyId' => $this->companyId)),
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','店铺列表');?></div>
					<div class="actions">
                                            <?php if(Yii::app()->params->master_slave=='m') : ?>
						<?php if(Yii::app()->user->role == User::POWER_ADMIN||Yii::app()->user->role == User::ADMIN):?>
							<a href="<?php echo $this->createUrl('company/create', array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加');?></a>
							<div class="btn-group">
	                            <button type="submit"  class="btn red" ><i class="fa fa-ban"></i> <?php echo yii::t('app','删除');?></button>
	                        </div>
						<?php endif;?>
						<!-- <div class="btn-group">
							<a class="btn green" href="#" data-toggle="dropdown">
							<i class="fa fa-cogs"></i> Tools
							<i class="fa fa-angle-down"></i>
							</a>
							<ul class="dropdown-menu pull-right">
								<li><a href="#"><i class="fa fa-ban"></i> <?php echo yii::t('app','冻结');?></a></li>
							</ul>
						</div> -->
                                                
                                            <?php endif; ?>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th>ID</th>
								<th><?php echo yii::t('app','店铺名称');?></th>
								<th >logo</th>
								<th><?php echo yii::t('app','联系人');?></th>
								<th ><?php echo yii::t('app','手机');?></th>
								<th><?php echo yii::t('app','电话');?></th>
								<th >email</th>
								<th ><?php echo yii::t('app','创建时间');?></th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><?php if(Yii::app()->user->role == User::ADMIN&&$model->type=="0"):?><?php else:?><input type="checkbox" class="checkboxes" value="<?php echo $model->dpid;?>" name="companyIds[]" /><?php endif;?></td>
								<td ><?php echo $model->dpid;?></td>
								<td><a href="<?php echo $this->createUrl('company/update',array('dpid' => $model->dpid,'companyId' => $this->companyId));?>" ><?php echo $model->company_name;?></a></td>
								<td ><img width="100" src="<?php echo $model->logo;?>" /></td>
								<td ><?php echo $model->contact_name;?></td>
								<td ><?php echo $model->mobile;?></td>
								<td ><?php echo $model->telephone;?></td>
								<td ><?php echo $model->email;?></td>
								<td><?php echo $model->create_at;?></td>
								<td class="center">
									<div class="actions">
                                        <?php if(Yii::app()->user->role < User::USER) : ?><!-- Yii::app()->params->master_slave=='m' -->
                                            <a  class='btn green' style="margin-top: 5px;" href="<?php echo $this->createUrl('company/update',array('dpid' => $model->dpid,'companyId' => $this->companyId));?>"><?php echo yii::t('app','编辑');?></a>
                                         <?php endif; ?>
                                            <a  class='btn green' style="margin-top: 5px;"  href="<?php echo $this->createUrl('company/index' , array('companyId' => $model->dpid));?>"><?php echo yii::t('app','选择');?></a>
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
									<?php echo yii::t('app','共');?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?>  , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> , <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页');?>
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