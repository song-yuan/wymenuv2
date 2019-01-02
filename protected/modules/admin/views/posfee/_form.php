<?php $form=$this->beginWidget('CActiveForm', array(
		'id' => 'mtpayConfig',
		'errorMessageCssClass' => 'help-block',
		'htmlOptions' => array(
			'class' => 'form-horizontal',
			'enctype' => 'multipart/form-data'
		),
)); ?>
	<div class="form-body">
	<?php if($this->comptype=='0'):?>
	  <div class="form-group">
			<?php echo $form->label($model, 'years',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'years',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('years'),'disabled'=>$a,));?>
				<?php echo $form->error($model, 'years' )?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'price',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'price',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('price')));?>
				<?php echo $form->error($model, 'price' )?>
			</div>
		</div>
		<div class="form-actions fluid">
		<span style="color: red;">&nbsp;提示：请慎重填写，加盟商会按照设置的金额进行续费延期。</span>
			<div class="col-md-offset-3 col-md-9">
				<button type="submit" class="btn blue" <?php if($a)echo 'disabled';?>><?php echo yii::t('app','确定');?></button>
			</div>
		</div>
	<?php endif;?>
<?php $this->endWidget(); ?>

					