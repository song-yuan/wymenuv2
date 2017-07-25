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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','菜品设置'),'url'=>$this->createUrl('product/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','套餐列表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('product/list' , array('companyId' => $this->companyId,'type' => '0',)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
            <?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'product-form',
				'action' => $this->createUrl('productSet/delete' , array('companyId' => $this->companyId,'papage' => $pages->getCurrentPage()+1)),
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','套餐列表');?></div>
					<div class="actions">
						<a href="<?php echo $this->createUrl('productSet/create' , array('companyId' => $this->companyId,));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加');?></a>
						<!-- <div class="btn-group">
							<a class="btn green" href="#" data-toggle="dropdown">
							<i class="fa fa-cogs"></i> Tools
							<i class="fa fa-angle-down"></i>
							</a>
							<ul class="dropdown-menu pull-right">
								<li><a href="#"><i class="fa fa-ban"></i> <?php echo yii::t('app','删除');?></a></li>
							</ul>
						</div> -->
                        <div class="btn-group">
							<button type="submit"  class="btn red" ><i class="fa fa-ban"></i> <?php echo yii::t('app','删除');?></button>
						</div>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
					<?php if($models):?>
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th><?php echo yii::t('app','套餐名称');?></th>
                                <th><?php echo yii::t('app','主图片');?></th>
                                <th><?php echo yii::t('app','套餐价格');?></th>
                                <th><?php echo yii::t('app','套餐会员价');?></th>
                                <!-- <th><?php echo yii::t('app','下单数');?></th>
								<th><?php echo yii::t('app','点赞数');?></th> -->
								<th><?php echo yii::t('app','售卖限制');?></th>
								<th><?php echo yii::t('app','来源');?></th>
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
								<td><?php echo $model->set_price;?></td>
                                <td><?php echo $model->member_price;?></td>
								<!-- <td ><?php echo $model->order_number ;?></td>
								<td><?php echo $model->favourite_number;?></td> -->
								<td><?php if($model->is_show)echo '都可售';else echo '仅活动可售';?>&nbsp;<?php if($model->is_discount)echo '可整折';else echo '不可整折';?>&nbsp;<?php if($model->is_member_discount)echo '会员可折';else echo '会员不可折';?>&nbsp;<?php if($model->is_show_wx == "1")echo '显示在微信端';else echo '不显示在微信端';?></td>
								<td><?php switch ($model->source){case 0: echo yii::t('app','自建');break;case 1: echo yii::t('app','总部下发');break;default: echo '';break;}?></td>
								<td class="center">
									<a href="<?php echo $this->createUrl('productSet/update',array('lid' => $model->lid , 'companyId' => $model->dpid , 'status' => $model->source, 'papage' => $pages->getCurrentPage()+1));?>"><?php echo yii::t('app','编辑');?></a>
								</td>
                                <td class="center">
									<a href="<?php echo $this->createUrl('productSet/detailindex',array('lid' => $model->lid , 'companyId' => $model->dpid , 'status' => $model->source , 'papage' => $pages->getCurrentPage()+1));?>"><?php echo yii::t('app','编辑明细');?></a>
								</td>
							</tr>
						<?php endforeach;?>
						</tbody>
						<?php else:?>
						<tr><td><?php echo yii::t('app','还没有添加套餐');?></td></tr>
						<?php endif;?>
					</table>
						<?php if($pages->getItemCount()):?>
						<div class="row">
							<div class="col-md-5 col-sm-12">
								<div class="dataTables_info">
									<?php echo yii::t('app','共 ');?><?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?>  , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> , <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?><?php echo yii::t('app','页');?> 
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