<style>
.none{display: none;}
</style>
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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','供应链'),'url'=>$this->createUrl('comgoodsorder/list' , array('companyId'=>$this->companyId,'type'=>0))),array('word'=>yii::t('app','销售订单列表'),'url'=>$this->createUrl('goodsorder/index' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','查看出货单'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('goodsorder/seeinvoice' , array('companyId' => $this->companyId,'account_no'=>$account_no,'lid'=>$lid)))));?>

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
					<div class="caption"><i class="fa fa-globe"></i><?php echo $model['company_name'];?> => <?php echo yii::t('app','出货单明细列表');?></div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr style="background: lightblue;">
								<th><?php echo yii::t('app','商品名称');?></th>
								<th><?php echo yii::t('app','商品单价');?></th>
                                <th><?php echo yii::t('app','商品数量');?></th>
                                <th><?php echo yii::t('app','所属仓库');?></th>
                                <th><?php echo yii::t('app','是否签收');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models):?>
							<?php foreach($models as $model):?>
							<tr class="odd gradeX">
								<td><?php echo $model['goods_name'];?></td>
								<td><?php echo $model['price'];?></td>
								<td><?php echo $model['num'];?></td>
								<td><?php echo $model['company_name'];?></td>
								<td><?php if($account['status']==2){ echo "已签收";}else{ echo "运输中";}?></td>
							</tr>
							<?php endforeach;?>
						<?php endif;?>
						</tbody>

					</table>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
            <?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->