<?php $form=$this->beginWidget('CActiveForm', array(
		'id' => 'material-form',
		'errorMessageCssClass' => 'help-block',
		'htmlOptions' => array(
			'class' => 'form-horizontal',
			'enctype' => 'multipart/form-data'
		),
)); ?>
	<style>
	#category_container select {display:block;float:left;margin-right:3px;max-width:200px;overflow:hidden;}
	</style>
	<div class="form-body">
		<div class="form-group" <?php if($model->hasErrors('manufacturer_id')) echo 'has-error';?>>
			<?php echo $form->label($model, 'manufacturer_id',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropdownlist($model, 'manufacturer_id', array('0'=>'0','1'=>'1'),array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('manufacturer_id')));?>
				<?php echo $form->error($model, 'manufacturer_id' )?>
			</div>
		</div>
		<div class="form-group <?php if($model->hasErrors('organization_id')) echo 'has-error';?>">
			<?php echo $form->label($model, 'organization_id',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropdownlist($model, 'organization_id',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('organization_id')));?>
				<?php echo $form->error($model, 'organization_id' )?>
			</div>
		</div>
		<div class="form-group <?php if($model->hasErrors('refund_account_no')) echo 'has-error';?>">
			<?php echo $form->label($model, 'refund_account_no',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'refund_account_no',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('refund_account_no')));?>
				<?php echo $form->error($model, 'refund_account_no' )?>
			</div>
		</div>
        <div class="form-group" <?php if($model->hasErrors('admin_id')) echo 'has-error';?>>
			<?php echo $form->label($model, 'admin_id',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropdownlist($model, 'admin_id', array('0'=>'0','1'=>'1'),array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('admin_id')));?>
				<?php echo $form->error($model, 'admin_id' )?>
			</div>
		</div>
        <div class="form-group <?php if($model->hasErrors('storage_account_no')) echo 'has-error';?>">
			<?php echo $form->label($model, 'storage_account_no',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'storage_account_no',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('storage_account_no')));?>
				<?php echo $form->error($model, 'storage_account_no' )?>
			</div>
		</div>
		
		<div class="form-group <?php if($model->hasErrors('refund_date')) echo 'has-error';?>">
			<?php echo $form->label($model, 'refund_date',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'refund_date',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('refund_date')));?>
				<?php echo $form->error($model, 'refund_date' )?>
			</div>
		</div>
		
		<div class="form-group" <?php if($model->hasErrors('remark')) echo 'has-error';?>>
			<?php echo $form->label($model, 'remark',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'remark', array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('remark')));?>
				<?php echo $form->error($model, 'remark' )?>
			</div>
		</div>
		<div class="form-group <?php if($model->hasErrors('status')) echo 'has-error';?>">
			<?php echo $form->label($model, 'status',array('class' => 'col-md-3 control-label'));?>
			<div class="radio-list">
				<label class="radio-inline">
				<input type="radio" name="optionsRadios<?php echo $model->lid;?>" id="optionsRadios<?php echo $model->lid;?>1" value="0" <?php if($model->status==0) echo "checked";?>> <?php echo yii::t('app','未审核');?>
				</label>
				<label class="radio-inline">
				<input type="radio" name="optionsRadios<?php echo $model->lid;?>" id="optionsRadios<?php echo $model->lid;?>2" value="1" <?php if($model->status==1) echo "checked";?>> <?php echo yii::t('app','已审核');?>
				</label>
			</div>
		</div>
		<div class="form-actions fluid">
			<div class="col-md-offset-3 col-md-9">
				<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
				<a href="<?php echo $this->createUrl('refundOrder/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
			</div>
		</div>
<?php $this->endWidget(); ?>
<?php $this->widget('ext.kindeditor.KindEditorWidget',array(
	'id'=>'',	//Textarea id
	'language'=>'zh_CN',
	// Additional Parameters (Check http://www.kindsoft.net/docs/option.html)
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
	   $('#category_container').on('change','.category_selecter',function(){
	   		var id = $(this).val();
	   		var $parent = $(this).parent();
                        var sid ='0000000000';
                        var len=$('.category_selecter').eq(1).length;
                        if(len > 0)
                        {
                            sid=$('.category_selecter').eq(1).val();
                            //alert(sid);
                        }
	   });
</script>