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
		<div class="form-group">
			<?php echo $form->label($model, '品项分类',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo CHtml::dropDownList('selectCategory', $categoryId, $categories , array('class'=>'form-control'));?>
			</div>
		</div>
		<div class="form-group" <?php if($model->hasErrors('material_id')) echo 'has-error';?>>
			<?php echo $form->label($model, 'material_id',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropDownList($model, 'material_id', array('0' => yii::t('app','-- 请选择 --')) +$materials ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('material_id')));?>
				<?php echo $form->error($model, 'material_id' )?>
			</div>
		</div>
		<div class="form-group <?php if($model->hasErrors('type')) echo 'has-error';?>">
			<?php echo $form->label($model, 'type',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropDownList($model, 'type', array('0' => yii::t('app','入库') , '1' => yii::t('app','出库')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('type')));?>
				<?php echo $form->error($model, 'type' )?>
			</div>
		</div>
        <div class="form-group <?php if($model->hasErrors('stock_num')) echo 'has-error';?>">
			<?php echo $form->label($model, 'stock_num',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'stock_num',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('stock_num')));?>
				<?php echo $form->error($model, 'stock_num' )?>
			</div>
		</div>
		<div class="form-group" <?php if($model->hasErrors('resean')) echo 'has-error';?>>
			<?php echo $form->label($model, 'resean',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'resean', array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('resean')));?>
				<?php echo $form->error($model, 'resean' )?>
			</div>
		</div>
		<div class="form-actions fluid">
			<div class="col-md-offset-3 col-md-9">
				<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
				<a href="<?php echo $this->createUrl('materialStockLog/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
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
	   $('#selectCategory').change(function(){
		   var cid = $(this).val();
		   //alert($('#ProductSetDetail_product_id').html());
		   $.ajax({
			   url:'<?php echo $this->createUrl('materialStockLog/getChildren',array('companyId'=>$this->companyId,));?>/pid/'+cid,
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
				   $('#MaterialStockLog_material_id').html(str);
			   }
		   });
	   });
</script>