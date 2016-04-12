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
		<div class="form-group" <?php if($model->hasErrors('stock_unit_id')) echo 'has-error';?>>
			<?php echo $form->label($model, 'stock_unit_id',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'stock_unit_id',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('stock_unit_id')));?>
				<?php echo $form->error($model, 'stock_unit_id' )?>
			</div>
		</div>
		<div class="form-group" <?php if($model->hasErrors('sales_unit_id')) echo 'has-error';?>>
			<?php echo $form->label($model, 'sales_unit_id',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'sales_unit_id',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('sales_unit_id')));?>
				<?php echo $form->error($model, 'sales_unit_id' )?>
			</div>
		</div>
		<div class="form-group" <?php if($model->hasErrors('unit_ratio')) echo 'has-error';?>>
			<?php echo $form->label($model, 'unit_ratio',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'unit_ratio', array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('unit_ratio')));?>
				<?php echo $form->error($model, 'unit_ratio' )?>
			</div>
		</div>
		<div class="form-actions fluid">
			<div class="col-md-offset-3 col-md-9">
				<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
				<a href="<?php echo $this->createUrl('materialUnitRatio/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
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