<?php $form=$this->beginWidget('CActiveForm', array(
		'id' => 'material-form',
		'errorMessageCssClass' => 'help-block',
		'htmlOptions' => array(
			'class' => 'form-horizontal',
			'enctype' => 'multipart/form-data'
		),
)); ?>
	<div class="form-body">
		<div class="form-group">
			<?php echo $form->label($model, '品项分类',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo CHtml::dropDownList('selectCategory', $categoryId, $categories , array('class'=>'form-control'));?>
			</div>
		</div>
		<div class="form-group <?php if($model->hasErrors('material_id')) echo 'has-error';?>">
			<?php echo $form->label($model, 'material_id',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropdownlist($model, 'material_id', array('0' => yii::t('app','-- 请选择 --')) +$materials ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('material_id')));?>
				<?php echo $form->error($model, 'material_id' )?>
			</div>
		</div>
		
		<div class="form-group <?php if($model->hasErrors('inventory_stock')) echo 'has-error';?>">
			<?php echo $form->label($model, 'inventory_stock',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'inventory_stock',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('inventory_stock').'(盘损为零售单位)'));?>
				<?php echo $form->error($model, 'inventory_stock' )?>
			</div>
		</div>
		<div class="form-group <?php if($model->hasErrors('retreat_id')) echo 'has-error';?>">
			<?php echo $form->label($model, 'retreat_id',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo CHtml::dropdownlist('retreat_id' ,$retreatId,$retreats ,array('class' => 'form-control', ));?>
				<?php echo $form->error($model, 'retreat_id' )?>
				<input class="form-control" name="InventoryDetail_retreat_id" id="InventoryDetail_retreat_id" type="hidden" value="<?php echo $model->retreat_id;?>"></input>
			</div>
		</div>
		
		<div class="form-group" <?php if($model->hasErrors('remark')) echo 'has-error';?>>
			<?php echo $form->label($model, 'remark',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-5">
				<?php echo $form->textArea($model, 'remark', array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('remark')));?>
				<?php echo $form->error($model, 'remark' )?>
			</div>
		</div>
		<div class="form-actions fluid">
			<div class="col-md-offset-3 col-md-9">
				<button type=submit class="btn blue add_save"><?php echo yii::t('app','确定');?></button>
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
		$('#retreat_id').change(function(){
			var rid = $(this).val();
			$('#InventoryDetail_retreat_id').val(rid);
		});
	   $('#selectCategory').change(function(){
		   var cid = $(this).val();
		   //alert($('#ProductSetDetail_product_id').html());
		   $.ajax({
			   url:'<?php echo $this->createUrl('inventory/getChildren',array('companyId'=>$this->companyId,));?>/pid/'+cid,
			   type:'GET',
			   dataType:'json',
			   success:function(result){
				   //alert(result.data);
				   var str = '<?php echo yii::t('app','<option value="">--请选择--</option>');?>';
				   if(result.data.length){
					   //alert(1);
					   $.each(result.data,function(index,value){
						   str = str + '<option value="'+value.id+'">'+value.name+'</option>';
					   });
				   }
				   //alert(str);
				   $('#InventoryDetail_material_id').html(str);
			   }
		   });
	   });
</script>