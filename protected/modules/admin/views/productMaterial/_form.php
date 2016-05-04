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
		<div class="form-group  <?php if($model->hasErrors('category_id')) echo 'has-error';?>">
			<?php echo $form->label($model, 'category_id',array('class' => 'col-md-3 control-label'));?>
			<div id="category_container" class="col-md-9">
				<?php $this->widget('application.modules.admin.components.widgets.MaterialCategorySelecter',array('categoryId'=>$model->category_id,'companyId'=>$this->companyId)); ?>
				<?php echo $form->error($model, 'category_id' )?>
			</div>
			<?php echo $form->hiddenField($model,'category_id',array('class'=>'form-control')); ?>
		</div>
		<div class="form-group <?php if($model->hasErrors('material_name')) echo 'has-error';?>">
			<?php echo $form->label($model, 'material_name',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'material_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('material_name')));?>
				<?php echo $form->error($model, 'material_name' )?>
			</div>
		</div>
		<div class="form-group" <?php if($model->hasErrors('material_identifier')) echo 'has-error';?>>
			<?php echo $form->label($model, 'material_identifier',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'material_identifier',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('material_identifier')));?>
				<?php echo $form->error($model, 'material_identifier' )?>
			</div>
		</div>
		<div class="form-group" <?php if($model->hasErrors('material_private_identifier')) echo 'has-error';?>>
			<?php echo $form->label($model, 'material_private_identifier',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'material_private_identifier',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('material_private_identifier')));?>
				<?php echo $form->error($model, 'material_private_identifier' )?>
			</div>
		</div>
		<div class="form-group" <?php if($model->hasErrors('stock_unit_id')) echo 'has-error';?>>
			<?php echo $form->label($model, 'stock_unit_id',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropDownList($model, 'stock_unit_id', array('0' => yii::t('app','-- 请选择 --')) +Helper::genStockUnit() ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('stock_unit_id')));?>
				<?php echo $form->error($model, 'stock_unit_id' )?>
			</div>
		</div>
		<div class="form-group" <?php if($model->hasErrors('sales_unit_id')) echo 'has-error';?>>
			<?php echo $form->label($model, 'sales_unit_id',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropDownList($model, 'sales_unit_id',array('0' => yii::t('app','-- 请选择 --')) +Helper::genSalesUnit() ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('sales_unit_id')));?>
				<?php echo $form->error($model, 'sales_unit_id' )?>
			</div>
		</div>
		<!--<div class="form-group" <?php if($model->hasErrors('stock')) echo 'has-error';?>>
			<?php echo $form->label($model, 'stock',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'stock',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('stock')));?>
				<?php echo $form->error($model, 'stock' )?>
			</div>
		</div>
        <div class="form-group" <?php if($model->hasErrors('stock_cost')) echo 'has-error';?>>
			<?php echo $form->label($model, 'stock_cost',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'stock_cost',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('stock_cost')));?>
				<?php echo $form->error($model, 'stock_cost' )?>
			</div>
		</div>-->
		<div class="form-actions fluid">
			<div class="col-md-offset-3 col-md-9">
				<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
				<a href="<?php echo $this->createUrl('productMaterial/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
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
           
	$(this).nextAll().remove();
	$.ajax({
		url:'<?php echo $this->createUrl('productMaterial/getChildren',array('companyId'=>$this->companyId));?>/pid/'+id,
	type:'GET',
	dataType:'json',
	success:function(result){
		if(result.data.length){
			var str = '<select class="form-control category_selecter" tabindex="-1" name="category_id_selecter">'+
			'<option value="">--'+"<?php echo yii::t('app','请选择');?>"+'--</option>';
				$.each(result.data,function(index,value){
					str = str + '<option value="'+value.id+'">'+value.name+'</option>';
				});
				str = str + '</select>';
				$parent.append(str);
				$('#ProductMaterial_category_id').val('');
				$parent.find('span').remove();
			}else{
                  //if(selname == 'category_id_selecter2')
                    $('#ProductMaterial_category_id').val(sid);
			}
		}
	});
});
</script>