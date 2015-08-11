	<?php $form=$this->beginWidget('CActiveForm', array(
			'id' => 'taste-form',
			'errorMessageCssClass' => 'help-block',
			'htmlOptions' => array(
				'class' => 'form-horizontal',
				'enctype' => 'multipart/form-data'
			),
	)); ?>
		<div class="form-body">
		<div class="form-group">
				<?php echo $form->label($model, 'selfcode',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'selfcode',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('selfcode')));?>
					<?php echo $form->error($model, 'selfcode' )?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->label($model, 'name',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('name')));?>
					<?php echo $form->error($model, 'name' )?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->label($model, 'mobile',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'mobile',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('mobile')));?>
					<?php echo $form->error($model, 'mobile' )?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->label($model, 'email',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'email',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('email')));?>
					<?php echo $form->error($model, 'email' )?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->label($model, 'sex',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'sex',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('sex')));?>
					<?php echo $form->error($model, 'sex' )?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->label($model, 'ages',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'ages',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('ages')));?>
					<?php echo $form->error($model, 'ages' )?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->label($model, 'password_hash',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'password_hash',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('password_hash')));?>
					<?php echo $form->error($model, 'password_hash' )?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->label($model, 'password_hash1',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'password_hash1',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('password_hash1')));?>
					<?php echo $form->error($model, 'password_hash1' )?>
				</div>
			</div>
			<div class="form-actions fluid">
				<div class="col-md-offset-3 col-md-9">
					<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
					<a href="<?php echo $this->createUrl('member/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
				</div>
			</div>
	<?php $this->endWidget(); ?>