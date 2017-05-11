<?php $form=$this->beginWidget('CActiveForm', array(
		'id' => 'companysettting-form',
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
			<?php echo $form->label($model, 'show_name',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'show_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('show_name')));?>
				<?php echo $form->error($model, 'show_name' )?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'slogan',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'slogan',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('slogan')));?>
				<?php echo $form->error($model, 'slogan' )?>
			</div>
		</div>
		<div class="form-group <?php if($model->hasErrors('logo')) echo 'has-error';?>">
			<?php echo $form->label($model,'logo',array('class'=>'control-label col-md-3')); ?>
			<div class="col-md-9">
					<div class="fileupload fileupload-new" data-provides="fileupload">
						<div class="fileupload-new thumbnail"  style="max-width: 200px; max-height: 200px; line-height: 20px;">
							<img src="<?php echo $model->logo?$model->logo:'';?>" alt="" />
						</div>
						<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 200px; line-height: 20px;"></div>
						<div>
							<span class="btn default btn-file">
							<span class="fileupload-new"><i class="fa fa-paper-clip"></i> 上传公司logo</span>
							<span class="fileupload-exists"><i class="fa fa-undo"></i> 更改 </span>
							<input type="file" accept="image/png,image/jpg,image/jpeg" name="file" class="default" />
							</span>
							<a href="#" class="btn red fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash-o"></i> 移除 </a>
						</div>
					</div>
					<span class="label label-danger">注意:</span>
					<span>大小：建议300px*300px且不超过2M 格式:jpg 、png、jpeg </span>
			</div>
			<?php echo $form->hiddenField($model,'logo'); ?>
		</div>				
		<div class="form-actions fluid">
			<div class="col-md-offset-3 col-md-9">
				<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
			</div>
		</div>
<?php $this->endWidget(); ?>
<script>
$('input[name="file"]').change(function(){
  	$('form').ajaxSubmit(function(msg){
		$('#CompanySetting_logo').val(msg);
	});
});

</script>					