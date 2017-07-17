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
				'action' => $this->createUrl('/meituan/jiedan' , array('companyId' => $this->companyId)),
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
						<tbody>
							<tr class="odd gradeX">
							<td><?php echo yii::t('app','店铺ID');?></td>
							<td><input type="text" name="companyId" disabled="disabled" value="<?php echo $this->companyId;?>" name="ids[]" /></td>
						</tr>
						<tr>
							<td><?php echo yii::t('app','是否自动接单');?></td>
							<td>
								<select name="jiedan">
									<option value="0">否</option>
									<option value="1">是</option>
								</select>
							</td>
						</tr>
						<tr style="text-align: center;"><td colspan="2"><input type="submit" value="提交"></td></tr>
						</tbody>
						</table>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
		<?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->	