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
	  	<?php if(Yii::app()->user->role <5):?>
			<?php echo $form->label($model, 'type',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropDownList($model, 'type',array('1'=>'壹点吃','2'=>'客户公司',),array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('type')));?>
				<?php echo $form->error($model, 'type' )?>
			</div>
		</div>
		<?php endif;?>
		<div class="form-group">
			<?php echo $form->label($model, 'use_type',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropDownList($model, 'use_type',array('1'=>'公告','2'=>'充值说明','3'=>'积分说明',),array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('use_type')));?>
				<?php echo $form->error($model, 'use_type' )?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'organization',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'organization',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('organization')));?>
				<?php echo $form->error($model, 'organization' )?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'publisher',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'publisher',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('publisher')));?>
				<?php echo $form->error($model, 'publisher' )?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'title',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textArea($model, 'title',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('title')));?>
				<?php echo $form->error($model, 'title' )?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'content',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textArea($model, 'content',array('class' => 'form-control','rows'=>'3','placeholder'=>$model->getAttributeLabel('content')));?>
				<?php echo $form->error($model, 'content' )?>
			</div>
		</div>				
		<div class="form-actions fluid">
			<div class="col-md-offset-3 col-md-9">
				<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
			</div>
		</div>
<?php $this->endWidget(); ?>
