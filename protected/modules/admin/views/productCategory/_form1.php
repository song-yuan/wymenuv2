			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'GoodsCategory',
				'action'=>$action,
				'enableAjaxValidation'=>true,
				'enableClientValidation'=>true,
				'clientOptions'=>array(
					'validateOnSubmit'=>false,
				),
				'htmlOptions'=>array(
					'class'=>'form-horizontal'
				),
			)); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title">添加商品类目</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<?php echo $form->label($model,'category_name',array('class'=>'col-md-3 control-label')); ?>
					<div class="col-md-9">
						<?php echo $form->hiddenField($model,'pid'); ?>
						<?php echo $form->textField($model,'category_name',array('class'=>'form-control','placeholder'=>$model->getAttributeLabel('category_name'))); ?>
						<?php echo $form->error($model,'category_name',array('class'=>'errorMessage')); ?>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn default">取 消</button>
				<input type="submit" class="btn green" id="create_btn" value="确 定">
			</div>
			<?php $this->endWidget(); ?>