<?php $form=$this->beginWidget('CActiveForm', array(
		'id' => 'site-form',
		'errorMessageCssClass' => 'help-block',
		'htmlOptions' => array(
			'class' => 'form-horizontal',
			'enctype' => 'multipart/form-data'
		),
)); ?>
	<div class="form-body">
	  <div class="form-group">
			<?php echo $form->label($model, 'token',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'token',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('token')));?>
				<?php echo $form->error($model, 'token' )?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'appid',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'appid',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('appid')));?>
				<?php echo $form->error($model, 'appid' )?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'appsecret',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'appsecret',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('appsecret')));?>
				<?php echo $form->error($model, 'appsecret' )?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'key',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'key',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('key')));?>
				<?php echo $form->error($model, 'key' )?>
			</div>
		</div>

		<div class="form-actions fluid">
			<div class="col-md-offset-3 col-md-9">
				<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
			</div>
		</div>
<?php $this->endWidget(); ?>
					