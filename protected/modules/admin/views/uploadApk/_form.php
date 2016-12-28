<?php $form=$this->beginWidget('CActiveForm', array(
		'id' => 'AlipayServiceAccount-form',
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
				<?php echo $form->dropDownList($model, 'type',array('0'=>'手动更新','1'=>'强制更新'),array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('type')));?>
				<?php echo $form->error($model, 'type' )?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'app_type',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropDownList($model, 'app_type',array('1'=>'收银APP','2'=>'后台APP'),array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('app_type')));?>
				<?php echo $form->error($model, 'app_type' )?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'app_version',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'app_version',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('app_version')));?>
				<?php echo $form->error($model, 'app_version' )?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'apk_url',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'apk_url',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('apk_url')));?>
				<?php echo $form->error($model, 'apk_url' )?>
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
