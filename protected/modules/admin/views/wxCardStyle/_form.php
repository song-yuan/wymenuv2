<style>
.form-horizontal .radio{
     padding-top: 0px!important;
   
}
.form-group .col-md-4{
    padding-top: 7px!important;
}
</style>

<?php $form=$this->beginWidget('CActiveForm', array(
                'id' => 'MemberWxcardStyle-form',
                'errorMessageCssClass' => 'help-block',
                'htmlOptions' => array(
                'class' => 'form-horizontal',
                'enctype' => 'multipart/form-data'
                ),
)); ?>
<div class="form-body">
        <div class="form-group <?php if($model->hasErrors('bg_img')) echo 'has-error';?>">
			<?php echo $form->label($model,'bg_img',array('class'=>'control-label col-md-3')); ?>
			<div class="col-md-9">
					<div class="fileupload fileupload-new" data-provides="fileupload">
						<div class="fileupload-new thumbnail"  style="max-width: 200px; max-height: 200px; line-height: 20px;">
							<img src="<?php echo $model->bg_img?$model->bg_img:'';?>" alt="" />
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
					<span>大小：建议300px*180px且不超过50kb 格式:jpg 、png、jpeg </span>
			</div>
			<?php echo $form->hiddenField($model,'bg_img'); ?>
		</div>       
        <div class="form-actions fluid">
             <div class="col-md-offset-3 col-md-9">
                  <button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
                  <a href="<?php echo $this->createUrl('wxCardStyle/index', array('companyId' => $this->companyId));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
             </div>
        </div>
</div>
    <?php $this->endWidget(); ?>

<script>
$('input[name="file"]').change(function(){
  	$('form').ajaxSubmit(function(msg){
  		var str = msg.substr(0,1);
  		// alert(str);
  		if (str=='/') {
			$('#MemberWxcardStyle_bg_img').val(msg);
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