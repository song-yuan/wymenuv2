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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','订单管理'),'subhead'=>yii::t('app','订单列表'),'breadcrumbs'=>array(array('word'=>yii::t('app','历史订单查询'),'url'=>''))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','订单列表');?></div>
					<div class="actions">

					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								
								<th><?php echo yii::t('app','订单编号');?></th>
								<th><?php echo yii::t('app','订单更新时间');?></th>
								<th><?php echo yii::t('app','订单明细');?></th>
								<th><?php echo yii::t('app','座位');?></th>
                                <th><?php echo yii::t('app','人数');?></th>
                                <th><?php echo yii::t('app','状态');?></th>
                                <th><?php echo yii::t('app','支付方式');?></th>
                                <th><?php echo yii::t('app','应付');?></th>                                                                
                                <th><?php echo yii::t('app','实付');?></th>
								
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<!--foreach-->
					
						<?php foreach ($models as $model):?>
								<tr class="odd gradeX">
								<td><?php echo $model->lid%10000; ?></td>
								<td><?php echo $model->update_at;?></td>
								<td><?php echo $this->getOrderDetails($model->lid); ?></td>
								<td><?php if($model->is_temp=='1') echo yii::t('app','临时坐').$model->site_id%1000; else echo $this->getSiteName($model->lid);?></td>
								<td><?php echo $model->number;?></td>
								<td><?php switch($model->order_status) {case 1: echo yii::t('app','未下单'); break; case 2: echo yii::t('app','已下单未支付') ; break; case 3: echo yii::t('app','已支付'); break; case 4: echo yii::t('app','已结单'); break; case 5: echo yii::t('app','被并台'); break; case 6: echo yii::t('app','被换台'); break; case 7: echo yii::t('app','被撤台'); break; case 8: echo yii::t('app','日结'); break;default :echo '';}?></td>
								<td><?php if($model->payment_method_id!='0000000000') echo $model->paymentMethod->name; else switch($model->paytype) {case 0: echo  yii::t('app','现金支付');break; case 1: echo  yii::t('app','微信支付');break; case 2: echo  yii::t('app','支付宝支付');break; case 3: echo  yii::t('app','后台手动支付');break;  default :echo ''; }?></td>
								<td><?php echo $model->should_total;?></td>
								<td><?php echo $model->reality_total;?></td>
								</tr>
						
						<?php endforeach;?>	
						<!-- end foreach-->
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
	<!-- END PAGE CONTENT-->

</div>