<?php $form=$this->beginWidget('CActiveForm', array(
		'id' => 'materialCategory-form',
		'errorMessageCssClass' => 'help-block',
		'htmlOptions' => array(
			'class' => 'form-horizontal',
			'enctype' => 'multipart/form-data'
		),
)); ?>
	<div class="form-body">
		<div class="form-group">
			<?php echo $form->label($model, 'category_name',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'category_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('category_name')));?>
				<?php echo $form->error($model, 'category_name' )?>
			</div>
		</div>
											<div class="form-group">
			<?php echo $form->label($model, 'order_num',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'order_num',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('order_num')));?>
				<?php echo $form->error($model, 'order_num' )?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'type',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropDownList($model, 'type', array('0' => yii::t('app','是') , '1' => yii::t('app','否')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('type')));?>
				<?php echo $form->error($model, 'type' )?>
			</div>
		</div>
		<div class="form-actions fluid">
			<div class="col-md-offset-3 col-md-9">
				<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
				<a href="<?php echo $this->createUrl('materialCategory/index' , array('companyId' => $model->company_id));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
			</div>
		</div>
<?php $this->endWidget(); ?>