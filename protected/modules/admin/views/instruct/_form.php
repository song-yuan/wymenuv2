<?php $form=$this->beginWidget('CActiveForm', array(
		'id' => 'instruct-form',
		'errorMessageCssClass' => 'help-block',
		'htmlOptions' => array(
			'class' => 'form-horizontal',
			'enctype' => 'multipart/form-data'
		),
)); ?>
<div class="form-body">
	<div class="form-group">
		<?php echo $form->label($model, 'instruct_name',array('class' => 'col-md-3 control-label'));?>
		<div class="col-md-4">
			<?php echo $form->textField($model, 'instruct_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('instruct_name')));?>
			<?php echo $form->error($model, 'instruct_name' )?>
		</div>
	</div>
	<div class="form-actions fluid">
		<div class="col-md-offset-3 col-md-9">
			<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
			<a href="<?php echo $this->createUrl('instruct/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
		</div>
	</div>
</div>
<?php $this->endWidget(); ?>							