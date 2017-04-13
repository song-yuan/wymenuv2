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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','基础设置'),'url'=>$this->createUrl('product/list' , array('companyId'=>$this->companyId,'type'=>0))),array('word'=>yii::t('app','套餐设置'),'url'=>$this->createUrl('productSet/index' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','套餐明细管理'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('productSet/index' , array('companyId' => $this->companyId,'page'=>$papage)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
            <?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'product-form',
				'action' => $this->createUrl('productSet/detaildelete' , array('companyId' => $this->companyId,'psid'=>$psmodel->lid , 'papage'=>$papage)),
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo $psmodel->set_name ;?>-><?php echo yii::t('app','套餐明细列表');?></div>
					<div class="actions">
					<?php if((Yii::app()->user->role == User::POWER_ADMIN)||($status==0 && Yii::app()->user->role >= User::ADMIN && Yii::app()->user->role <= User::SHOPKEEPER)):?>
						<a href="<?php echo $this->createUrl('productSet/detailcreate' , array('companyId' => $this->companyId,'psid'=>$psmodel->lid,'type'=>0, 'papage'=>$papage));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加');?></a>
						<div class="btn-group">
							<button type="submit"  class="btn red" ><i class="fa fa-ban"></i> <?php echo yii::t('app','删除');?></button>
						</div>
					<?php endif;?>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
					<?php if($models):?>
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th><?php echo yii::t('app','单品名称');?></th>
                                <th><?php echo yii::t('app','图片');?></th>
                                <th><?php echo yii::t('app','套餐内价格');?></th>
                                <th><?php echo yii::t('app','分组号');?></th>
                                <th><?php echo yii::t('app','数量');?></th>
                                <th><?php echo yii::t('app','默认选择');?></th>
								<th>&nbsp;</th>                                                                
							</tr>
						</thead>
						<tbody>
						
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="ids[]" /></td>
								<td ><?php echo $model->product->product_name ;?></td>
								<td ><img width="100" src="<?php echo $model->product->main_picture ;?>" /></td>
                                <td><?php echo $model->price;?></td>
                                <td><?php echo $model->group_no;?></td>
                                <td><?php echo $model->number;?></td>
                                <td>
									<div class="s-btn make-switch switch-small" data-on="success" data-off="danger" data-on-label="<?php echo yii::t('app','是');?>" data-off-label="<?php echo yii::t('app','否');?>">
										<input pid="<?php echo $model->lid;?>" <?php if($model->is_select) echo 'checked="checked"';?> type="checkbox" disabled="disabled" class="toggle"/>
									</div>
								</td>
								<td class="center">
								<a href="<?php echo $this->createUrl('productSet/detailupdate',array('lid' => $model->lid , 'companyId' => $model->dpid, 'type'=>'1' , 'status'=>$status, 'papage'=>$papage));?>"><?php echo yii::t('app','编辑');?></a>
								</td>             
							</tr>
						<?php endforeach;?>
						</tbody>
						<?php else:?>
						<tr><td><?php echo yii::t('app','还没有添加详细产品');?></td></tr>
						<?php endif;?>
					</table>
						<?php if($pages->getItemCount()):?>
						<div class="row">
							<div class="col-md-5 col-sm-12">
								<div class="dataTables_info">
									<?php echo yii::t('app','共');?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?>  , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> , <?php echo yii::t('app','当前是第 ');?><?php echo $pages->getCurrentPage()+1;?><?php echo yii::t('app','页');?> 
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
        