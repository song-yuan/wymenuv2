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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','商城设置'),'url'=>$this->createUrl('tmall/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','发货单列表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('tmall/list' , array('companyId' => $this->companyId,'type' => 0)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
            <?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'product-form',
				'action' => '',
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','发货单列表');?></div>
					<div class="actions">
						
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
					<?php if($models):?>
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th><?php echo yii::t('app','订单号');?></th>
                                <th><?php echo yii::t('app','配货单号');?></th>
                                <th><?php echo yii::t('app','订单总额');?></th>
                                <th><?php echo yii::t('app','支付状态');?></th>
                                <th><?php echo yii::t('app','订单状态');?></th>
                                <th><?php echo yii::t('app','配送人或单位');?></th>
                                <th><?php echo yii::t('app','联系方式或单号');?></th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
						
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><?php echo $model['lid']/1;?></td>
								<td><?php echo $model['goods_order_accountno'];?></td>
								<td><?php echo $model['invoice_accountno'];?></td>
								<td><?php echo $model['invoice_amount'];?></td>
								<td><?php switch ($model['pay_status']){
									case 0 : echo '未支付';break;
									case 1 : echo '已支付';break;
									default: echo '未知';break;
								}?></td>
								<td><?php switch ($model['status']){
									case 0 : echo '未出库';break;
									case 1 : echo '已出库';break;
									case 2 : echo '已确认收货';break;
									default: echo '未知';break;
								}?></td>
								<td><?php echo $model['sent_personnel'];?></td>
								<td><?php echo $model['mobile'];?></td>
                                <td class="center">
									<a href="<?php echo $this->createUrl('goodsinvoice/detailindex',array('lid' => $model['lid'] ,'companyId' => $this->companyId, 'dpid' => $model['compid'], 'papage' => $pages->getCurrentPage()+1));?>"><?php echo yii::t('app','查看明细');?></a>
									<input gid="<?php echo $model['lid'];?>" type="button" class="btn green add_btn" value="编辑" />
								</td>
							</tr>
						<?php endforeach;?>
						</tbody>
						<?php else:?>
						<tr><td><?php echo yii::t('app','未查询到订单');?></td></tr>
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
	<SCRIPT type="text/javascript">
	$(document).ready(function(){
	
	var $modal = $('.modal');
    $('.add_btn').on('click', function(){
    	gid = $(this).attr('gid');
        $modal.find('.modal-content').load('<?php echo $this->createUrl('goodsinvoice/addp',array('companyId'=>$this->companyId));?>/gid/'+gid, '', function(){
          $modal.modal();
        });
    });
});
	</SCRIPT>