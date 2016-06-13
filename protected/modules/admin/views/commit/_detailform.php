<?php $form=$this->beginWidget('CActiveForm', array(
		'id' => 'material-form',
		'errorMessageCssClass' => 'help-block',
		'htmlOptions' => array(
			'class' => 'form-horizontal',
			'enctype' => 'multipart/form-data'
		),
)); ?>
	<style>
	/*#category_container select {display:block;float:left;margin-right:3px;max-width:200px;overflow:hidden;}*/
	</style>
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
				<select class="form-control" name="CommitDetail[material_id]" id="CommitDetail_material_id">
					<option value="0">-- 请选择 --</option>
					<?php foreach ($materials as $material):?>
					<option value="<?php echo $material['lid'];?>" salse-unit="<?php echo $material['stock_unit_id'];?>" <?php if($model->material_id==$material['lid']){echo 'selected';}?>><?php echo $material['material_name'];?></option>
					<?php endforeach;?>
				</select>
				<?php echo $form->error($model, 'material_id' )?>
			</div>
		</div>
        <div class="form-group <?php if($model->hasErrors('unit_name')) echo 'has-error';?>">
			<?php echo $form->label($model, 'unit_name',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropdownlist($model, 'unit_name', array('0' => yii::t('app','-- 请选择 --')) +Helper::genStockUnit() ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('unit_name')));?>
				<?php echo $form->error($model, 'unit_name' )?>
			</div>
		</div>
        <div class="form-group <?php if($model->hasErrors('stock')) echo 'has-error';?>">
			<?php echo $form->label($model, 'stock',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'stock',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('stock')));?>
				<?php echo $form->error($model, 'stock' )?>
			</div>
		</div>
		<div class="form-actions fluid">
			<div class="col-md-offset-3 col-md-9">
				<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
				<a href="<?php echo $this->createUrl('commit/detailindex' , array('companyId' => $model->dpid,'lid'=>$model->commit_id,));?>" class="btn default"><?php echo yii::t('app','返回');?></a>
			</div>
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
	$(document).ready(function() {
		$('#selectCategory').change(function(){
			var cid = $(this).val();
			$.ajax({
				url:'<?php echo $this->createUrl('productBom/getChildren',array('companyId'=>$this->companyId,));?>/pid/'+cid,
				type:'GET',
				dataType:'json',
				success:function(result){
					//alert(result.data);
					var str = '<?php echo yii::t('app','<option value="">--请选择--</option>');?>';
					if(result.data.length){
						$.each(result.data,function(index,value){
							str = str + '<option value="'+value.id+'" salse-unit="'+value.unit_id+'">'+value.name+'</option>';
						});
					}
					$('#CommitDetail_material_id').html(str);
				}
			});
		});
		$('#CommitDetail_material_id').change(function(){
			var salseUnit = $(this).find('option:selected').attr('salse-unit');
			$('#CommitDetail_unit_name').find('option').each(function(){
				var unitId = $(this).val();
				if(unitId!=salseUnit){
					$(this).remove();
				}
			});
		});
	});
</script>