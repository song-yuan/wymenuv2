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
			<?php echo $form->label($model, '商品分类',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo CHtml::dropDownList('selectCategory', $categoryId, $categories , array('class'=>'form-control'));?>
			</div>
		</div>
		<div class="form-group <?php if($model->hasErrors('material_id')) echo 'has-error';?>">
			<?php echo $form->label($model, '商品名称',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropdownlist($model, 'material_id', array('0' => yii::t('app','-- 请选择 --')) +$materials ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('material_id')));?>
				<?php echo $form->error($model, 'material_id' )?>
			</div>
		</div>
        <div class="form-group <?php if($model->hasErrors('price')) echo 'has-error';?>">
			<?php echo $form->label($model, '采购价格',array('class' => 'col-md-3 control-label'));?>
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
		<div class="form-group <?php if($model->hasErrors('stock_day')) echo 'has-error';?>">
			<?php echo $form->label($model, 'stock_day',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'stock_day',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('stock_day')));?>
				<?php echo $form->error($model, 'stock_day' )?>
			</div>
		</div>
		<div class="form-actions fluid">
			<div class="col-md-offset-3 col-md-9">
				<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
				<a href="<?php echo $this->createUrl('purchaseOrder/ckdetailindex' , array('companyId' => $model->dpid,'lid'=>$model->purchase_id,));?>" class="btn default"><?php echo yii::t('app','返回');?></a>
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
       $('#selectCategory').change(function(){
           var cid = $(this).val();
           $.ajax({
               url:'<?php echo $this->createUrl('purchaseOrder/getGoodsChildren',array('companyId'=>$this->companyId,));?>/pid/'+cid,
               type:'GET',
               dataType:'json',
               success:function(result){
                   var str = '<?php echo yii::t('app','<option value="">--请选择--</option>');?>';
                   if(result.data.length){
                       $.each(result.data,function(index,value){
                           str = str + '<option value="'+value.id+'">'+value.name+'</option>';
                       });
                   }
                   //alert(str);
                   $('#PurchaseOrderDetail_material_id').html(str);
               }
           });
       });
</script>