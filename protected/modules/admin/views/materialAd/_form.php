<?php $form=$this->beginWidget('CActiveForm', array(
		'id' => 'materialAd-form',
		'errorMessageCssClass' => 'help-block',
		'htmlOptions' => array(
			'class' => 'form-horizontal',
			'enctype' => 'multipart/form-data',
		),
)); ?>
	<style>
	#category_container select {display:block;float:left;margin-right:3px;max-width:200px;overflow:hidden;}
	</style>
	<div class="form-body">
		<div class="form-group <?php if($model->hasErrors('name')) echo 'has-error';?>">
			<?php echo $form->label($model, 'name',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('name'),));?>
				<?php echo $form->error($model, 'name' )?>
			</div>
		</div>
			<div class="form-group <?php if($model->hasErrors('main_picture')) echo 'has-error';?>">
			<?php echo $form->label($model,'main_picture',array('class'=>'control-label col-md-3')); ?>
			<div class="col-md-9">
					<div class="fileupload fileupload-new" data-provides="fileupload">
						<div class="fileupload-new thumbnail"  style="max-width: 200px; max-height: 200px; line-height: 20px;">
							<img src="<?php echo $model->main_picture?$model->main_picture:'';?>" alt="" />
						</div>
						<div class="fileupload-preview fileupload-exists thumbnail" id="img1" style="max-width: 200px; max-height: 200px; line-height: 20px;"></div>
						<div>
							<span class="btn default btn-file">
							<span class="fileupload-new"><i class="fa fa-paper-clip"></i> 上传产品图片 </span>
							<span class="fileupload-exists"><i class="fa fa-undo"></i> 更改 </span>
							<input type="file" accept="image/png,image/jpg,image/jpeg" name="file" class="default" />
							</span>
							<a href="#" class="btn red fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash-o"></i> 移除 </a>
						</div>
					</div>
					<span class="label label-danger">注意:</span>
					<span>大小：建议300px*300px且不超过40kb 格式:jpg 、png、jpeg </span>
			</div>
			<?php echo $form->hiddenField($model,'main_picture'); ?>
		</div>

		<div class="form-group" <?php if($model->hasErrors('sort')) echo 'has-error';?>>
			<?php echo $form->label($model, 'sort',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'sort',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('sort'),));?>
				<?php echo $form->error($model, 'sort' )?>
				<span style="color: red;">数字越小，显示越靠前。</span>
			</div>
		</div>

        <div class="form-group">
			<?php echo $form->label($model, 'is_show',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropDownList($model, 'is_show', array('0' => yii::t('app','否') , '1' => yii::t('app','是')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_show'),));?>
				<?php echo $form->error($model, 'is_show' )?>
			</div>
		</div>


		<div class="form-group">
			<?php echo $form->label($model, 'description',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-8">
				<?php echo $form->textArea($model, 'description' , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('description'),));?>
				<?php echo $form->error($model, 'description' )?>
			</div>
		</div>
		<div class="form-actions fluid">
			<div class="col-md-offset-3 col-md-9">
				<button type="submit"  class="btn blue"><?php echo yii::t('app','确定');?></button>
			</div>
		</div>
	<?php $this->endWidget(); ?>
	<?php $this->widget('ext.kindeditor.KindEditorWidget',array(
		'id'=>'MaterialAd_description',	//Textarea id
		'language'=>'zh_CN',
		'items' => array(
			'height'=>'200px',
			'width'=>'100%',
			'themeType'=>'simple',
			'resizeType'=>1,
			'allowImageUpload'=>true,
			'allowFileManager'=>true,
		),
	)); ?>

	<script>
	  $('input[name="file"]').change(function(){
		  	$('form').ajaxSubmit(function(msg){
		  		var str = msg.substr(0,1);
		  		// alert(str);
		  		if (str=='/') {
					$('#MaterialAd_main_picture').val(msg);
					layer.msg('图片选择成功!!!');
		  		}else{
					layer.msg(msg);
		  			$('#img1 img').attr({
						src: '',
						width: '2px',
						height: '2px',
					});
		  		}
			});
	   });

	</script>