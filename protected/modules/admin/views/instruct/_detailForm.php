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
			<?php echo $form->label($model, 'number',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'number',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('number')));?>
				<?php echo $form->error($model, 'number' )?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'instruct_name',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'instruct_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('instruct_name')));?>
				<?php echo $form->error($model, 'instruct_name' )?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'time',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'time',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('time')));?>
				<?php echo $form->error($model, 'time' )?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'instruct',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'instruct',array('class' => 'form-control' ,'placeholder'=>$model->getAttributeLabel('instruct')));?>
				<?php echo $form->error($model, 'instruct' )?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'sort',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'sort', array( 'class' => 'form-control','placeholder'=>$model->getAttributeLabel('sort')));?>
				<?php echo $form->error($model, 'sort' )?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'is_waiting',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'is_waiting', array( 'class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_waiting')));?>
				<?php echo $form->error($model, 'is_waiting' )?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'is_enquire',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'is_enquire', array( 'class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_enquire')));?>
				<?php echo $form->error($model, 'is_enquire' )?>
			</div>
		</div>
		<div class="form-actions fluid">
			<div class="col-md-offset-3 col-md-9">
				<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
				<a href="<?php echo $this->createUrl('instruct/detailIndex' , array('companyId' => $model->dpid,'groupid'=>$groupid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
			</div>
		</div>
	</div>
<?php $this->endWidget(); ?>							