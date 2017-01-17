<?php $form=$this->beginWidget('CActiveForm', array(
		'id' => 'connectUs-form',
		'errorMessageCssClass' => 'help-block',
		'htmlOptions' => array(
			'class' => 'form-horizontal',
			'enctype' => 'multipart/form-data'
		),
)); ?>
<style>
	.unide{display:none;}
</style>
	<div class="form-body">
	  
		<div class="form-group">
			<?php echo $form->label($model, 'type',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropDownList($model, 'type',array('0'=>'QQ','1'=>'手机','2'=>'email','3'=>'固话','4'=>'微信'),array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('type')));?>
				<?php echo $form->error($model, 'type' )?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'content',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textArea($model, 'content',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('content')));?>
				<?php echo $form->error($model, 'content' )?>
			</div>
		</div>
						
		<div class="form-actions fluid">
			<div class="col-md-offset-3 col-md-9">
				<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
			</div>
		</div>
<?php $this->endWidget(); ?>
