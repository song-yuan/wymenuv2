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
		<div class="form-group" <?php if($model->hasErrors('material_id')) echo 'has-error';?>>
			<?php echo $form->label($model, 'material_id',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropdownlist($model, 'material_id', array('0'=>'0','1'=>'1'),array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('material_id')));?>
				<?php echo $form->error($model, 'material_id' )?>
			</div>
		</div>
        <div class="form-group" <?php if($model->hasErrors('material_name')) echo 'has-error';?>>
			<?php echo $form->label($model, 'material_name',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropdownlist($model, 'material_name', array('0'=>'0','1'=>'1'),array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('material_name')));?>
				<?php echo $form->error($model, 'material_name' )?>
			</div>
		</div>
        <div class="form-group <?php if($model->hasErrors('price')) echo 'has-error';?>">
			<?php echo $form->label($model, 'price',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'price',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('price')));?>
				<?php echo $form->error($model, 'price' )?>
			</div>
		</div>
		<div class="form-group <?php if($model->hasErrors('stock')) echo 'has-error';?>">
			<?php echo $form->label($model, 'stock',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'stock',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('stock')));?>
				<?php echo $form->error($model, 'stock' )?>
			</div>
		</div>
		<div class="form-group <?php if($model->hasErrors('free_stock')) echo 'has-error';?>">
			<?php echo $form->label($model, 'free_stock',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'free_stock',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('free_stock')));?>
				<?php echo $form->error($model, 'free_stock' )?>
			</div>
		</div>
		<div class="form-actions fluid">
			<div class="col-md-offset-3 col-md-9">
				<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
				<a href="<?php echo $this->createUrl('storageOrderDetail/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
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