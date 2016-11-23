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
			<?php echo $form->label($model, 'appid',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'appid',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('appid')));?>
				<?php echo $form->error($model, 'appid' )?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'partner',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'partner',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('partner')));?>
				<?php echo $form->error($model, 'partner' )?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'seller_id',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'seller_id',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('seller_id')));?>
				<?php echo $form->error($model, 'seller_id' )?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'alipay_public_key',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'alipay_public_key',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('alipay_public_key')));?>
				<?php echo $form->error($model, 'alipay_public_key' )?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'merchant_private_key',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'merchant_private_key',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('merchant_private_key')));?>
				<?php echo $form->error($model, 'merchant_private_key' )?>
			</div>
		</div>
		<div class="form-group unide">
			<?php echo $form->label($model, 'store_id',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'store_id',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('store_id')));?>
				<?php echo $form->error($model, 'store_id' )?>
			</div>
		</div>
		<div class="form-group unide">
			<?php echo $form->label($model, 'alipay_store_id',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'alipay_store_id',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('alipay_store_id')));?>
				<?php echo $form->error($model, 'alipay_store_id' )?>
			</div>
		</div>
		<div class="form-group <?php if($model->hasErrors('alipay_public_key_file')) echo 'has-error';?>">
			<?php echo $form->label($model,'alipay_public_key_file',array('class'=>'control-label col-md-3')); ?>
			<div class="col-md-9">
			<?php
			$this->widget('application.extensions.swfuploadali.SWFUpload',array(
				'callbackJS'=>'swfupload_callback',
				'fileTypes'=> '*.pem',
				'buttonText'=> yii::t('app','  上传文件'),
				'companyId' => $model->dpid,
				'imgUrlList' => array($model->alipay_public_key_file),
			));
			?>
			<?php echo $form->hiddenField($model,'alipay_public_key_file'); ?>
			<?php echo $form->error($model,'alipay_public_key_file'); ?>
			</div>
		</div>
		<div class="form-group <?php if($model->hasErrors('merchant_private_key_file')) echo 'has-error';?>">
			<?php echo $form->label($model,'merchant_private_key_file',array('class'=>'control-label col-md-3')); ?>
			<div class="col-md-9">
			<?php
			$this->widget('application.extensions.swfuploadali.SWFUpload',array(
				'callbackJS'=>'swfupload_callback2',
				'fileTypes'=> '*.pem',
				'buttonText'=> yii::t('app','  上传文件'),
				'companyId' => $model->dpid,
				'imgUrlList' => array($model->merchant_private_key_file),
			));
			?>
			<?php echo $form->hiddenField($model,'merchant_private_key_file'); ?>
			<?php echo $form->error($model,'merchant_private_key_file'); ?>
			</div>
		</div>
		<div class="form-group <?php if($model->hasErrors('merchant_public_key_file')) echo 'has-error';?>">
			<?php echo $form->label($model,'merchant_public_key_file',array('class'=>'control-label col-md-3')); ?>
			<div class="col-md-9">
			<?php
			$this->widget('application.extensions.swfuploadali.SWFUpload',array(
				'callbackJS'=>'swfupload_callback3',
				'fileTypes'=> '*.pem',
				'buttonText'=> yii::t('app','  上传文件'),
				'companyId' => $model->dpid,
				'imgUrlList' => array($model->merchant_public_key_file),
			));
			?>
			<?php echo $form->hiddenField($model,'merchant_public_key_file'); ?>
			<?php echo $form->error($model,'merchant_public_key_file'); ?>
			</div>
		</div>				
		<div class="form-actions fluid">
			<div class="col-md-offset-3 col-md-9">
				<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
			</div>
		</div>
<?php $this->endWidget(); ?>
<script>

function swfupload_callback(name,path,oldname)  {
	$("#AlipayServiceAccount_alipay_public_key_file").val(name);
	$("#thumbnails_1").html("<ipnut class='form-control' style='margin:1px;opacity:1;' disabled='true'>"+oldname+"</input>");
}
function swfupload_callback2(name,path,oldname)  {
	$("#AlipayServiceAccount_merchant_private_key_file").val(name);
	$("#thumbnails_2").html("<ipnut class='form-control' style='margin:1px;opacity:1;' disabled='true'>"+oldname+"</input>");
}
function swfupload_callback3(name,path,oldname)  {
	$("#AlipayServiceAccount_merchant_public_key_file").val(name);
	$("#thumbnails_3").html("<ipnut class='form-control' style='margin:1px;opacity:1;' disabled='true'>"+oldname+"</input>");
}
</script>					