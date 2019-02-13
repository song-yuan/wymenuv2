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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','微信会员'),'url'=>$this->createUrl('wechatMember/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','储值'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('wechatMember/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
            <div style="display: none;" class="col-md-12 col-sm-12 ">
                    <ul class="nav nav-tabs">
                            <li><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('/admin/wxlevel/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab">会员等级</a></li>
                            <li><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('/admin/wxpoint/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab">消费积分比例模板</a></li>
                            <li><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('/admin/wxpointvalid/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab">积分有效期模板</a></li>
                            <li><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('/admin/wxcashback/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab">消费返现比例模板</a></li>
                            <li class="active"><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('/admin/wxrecharge/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab">充值模板</a></li>
                    </ul>
            </div>
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'branduserlevel-form',
				'action' => $this->createUrl('wxrecharge/delete' , array('companyId' => $this->companyId)),
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
					<div class="caption"><i class="fa fa-globe"></i>充值模板</div>
					<div class="actions">
						<a href="<?php echo $this->createUrl('wxrecharge/create' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加');?></a>
                                                <a href="javascript:void(0)" class="btn red" onclick="document.getElementById('branduserlevel-form').submit();"><i class="fa fa-times"></i> <?php echo yii::t('app','删除');?></a>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
				<div class="dataTables_wrapper form-inline">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
                        <tr>
	                        <th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
	                        <th><?php echo yii::t('app','名称');?></th>
	                        <th><?php echo yii::t('app','充值金额');?></th>
	                        <th><?php echo yii::t('app','返积分');?></th>
	                        <th><?php echo yii::t('app','返现');?></th>
	                        <th><?php echo yii::t('app','返现金券');?></th>
	                        <th><?php echo yii::t('app','限制次数');?></th>
	                        <th><?php echo yii::t('app','限制店铺');?></th>
	                        <th><?php echo yii::t('app','是否有效');?></th>
	                        <th>&nbsp;</th>
                        </tr>
						</thead>
						<tbody>
						<?php if($models):?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="lid[]" /></td>
								<td ><?php echo $model->wr_name;?></td>
								<td ><?php echo $model->recharge_money;?></td>
                                <td ><?php echo $model->recharge_pointback;?></td>
                                <td ><?php echo $model->recharge_cashback;?></td>
                                <td>
                                	<?php if($model->recharge_cashcard) {echo '是';} else {echo '否';} ?>
								</td>
								<td ><?php echo $model->recharge_number;?></td>
								<td>
                                	<?php if($model->recharge_dpid) {echo '是';} else {echo '否';} ?>
								</td>
                                <td>
                                	<?php if($model->is_available) {echo '否';} else {echo '是';} ?>
								</td>
								<td class="center">
								<a href="<?php echo $this->createUrl('wxrecharge/update',array('lid' => $model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','编辑');?></a>
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
									<?php echo yii::t('app','共');?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?>  , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> , <?php echo yii::t('app','当前是第');?><?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页');?>
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