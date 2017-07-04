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
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'product-form',
				'action' => $this->createUrl('/eleme/updateproduct' , array('companyId' => $this->companyId)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?>
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet-body" id="table-manage">
						<table class="table table-striped table-bordered table-hover" id="sample_1">
							<thead>
								<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
	                            <th><?php echo yii::t('app','菜品ID');?></th>
	                            <th><?php echo yii::t('app','菜品名称');?></th>  
								</tr>
							</thead>
							<tbody>
								<?php foreach($model as $value):?>
									<tr class="odd gradeX">
									<td><input type="checkbox" class="checkboxes" value="<?php echo $value['lid'];?>" name="ids[]" /></td>
									<td><?php echo $value['lid'];?></td>
	                                <td><?php echo $value['product_name'];?></td>
								</tr>
							<?php endforeach;?>
							</tbody>
							<tr style="text-align: center;">
								<td colspan="4" style="width: 300px;"><input type="submit" value="批量更新"></td>
							</tr>
						</table>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
		<?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->	