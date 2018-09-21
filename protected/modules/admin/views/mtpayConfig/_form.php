<?php $form=$this->beginWidget('CActiveForm', array(
		'id' => 'mtpayConfig',
		'errorMessageCssClass' => 'help-block',
		'htmlOptions' => array(
			'class' => 'form-horizontal',
			'enctype' => 'multipart/form-data'
		),
)); ?>
	<div class="form-body">
	<?php if($ty==1&&(Yii::app()->user->role>=5))?>
	<?php if($this->comptype=='0'):?>
	  <div class="form-group">
			<?php echo $form->label($model, 'mt_appId',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'mt_appId',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('mt_appId'),'disabled'=>$a,));?>
				<?php echo $form->error($model, 'mt_appId' )?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'mt_key',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'mt_key',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('mt_key'),'disabled'=>$a,));?>
				<?php echo $form->error($model, 'mt_key' )?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'mt_merchantId',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'mt_merchantId',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('mt_merchantId')));?>
				<?php echo $form->error($model, 'mt_merchantId' )?>
			</div>
		</div>
	<?php else:?>
		<div class="form-group">
			<?php echo $form->label($model, 'mt_merchantId',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'mt_merchantId',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('mt_merchantId'),'disabled'=>$a,));?>
				<?php echo $form->error($model, 'mt_merchantId' )?>
			</div>
		</div>
	<?php endif;?>
		<div class="form-actions fluid">
		<span style="color: red;">&nbsp;注意：请慎重填写，只能填写一次。填写之后不可修改。</span>
			<div class="col-md-offset-3 col-md-9">
				<button type="submit" class="btn blue" <?php if($a)echo 'disabled';?>><?php echo yii::t('app','确定');?></button>
			</div>
		</div>
<?php $this->endWidget(); ?>
<SCRIPT type="text/javascript">
	$('#getopenId').on('click',function(){
		var mid = $('#MtpayConfig_mt_merchantId').val();
		layer.msg(mid);
        $.ajax({
            type:'GET',
			url:"<?php echo $this->createUrl('../mtpay/mtopenid',array('dpid'=>$this->companyId,));?>/mid/"+mid,
			async: false,
            cache:false,
            dataType:'json',
			success:function(msg){
	            layer.msg(msg);
			},
            error:function(){
				layer.msg("<?php echo yii::t('app','失败'); ?>"+"2");                                
			},
		});
	});
</SCRIPT>
					