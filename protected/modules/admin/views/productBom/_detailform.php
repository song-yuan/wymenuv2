<?php $form=$this->beginWidget('CActiveForm', array(
		'id' => 'printer-form',
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
	<div class="form-group" <?php if($model->hasErrors('material_id')) echo 'has-error';?>>
		<?php echo $form->label($model, 'material_id',array('class' => 'col-md-3 control-label'));?>
		<div class="col-md-4">
			<?php echo $form->dropDownList($model, 'material_id', array('0' => yii::t('app','-- 请选择 --')) +$materials ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('material_id')));?>
			<?php echo $form->error($model, 'material_id' )?>
		</div>
	</div>
	<div class="form-group" <?php if($model->hasErrors('sales_unit_id')) echo 'has-error';?>>
		<?php echo $form->label($model, 'sales_unit_id',array('class' => 'col-md-3 control-label'));?>
		<div class="col-md-4">
			<?php echo $form->dropDownList($model, 'sales_unit_id',array('0' => yii::t('app','-- 请选择 --')) +Helper::genSalesUnit() ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('sales_unit_id')));?>
			<?php echo $form->error($model, 'sales_unit_id' )?>
		</div>
	</div>
	<div class="form-group" <?php if($model->hasErrors('number')) echo 'has-error';?>>
		<?php echo $form->label($model, 'number',array('class' => 'col-md-3 control-label'));?>
		<div class="col-md-4">
			<?php echo $form->textField($model, 'number',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('number')));?>
			<?php echo $form->error($model, 'number' )?>
		</div>
	</div>
	<div class="form-actions fluid">
		<div class="col-md-offset-3 col-md-9">
			<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
			<a href="<?php echo $this->createUrl('productBom/detailindex' , array('companyId' => $model->dpid,'pblid'=>$pblid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>
		</div>
	</div>
	<?php $this->endWidget(); ?>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#selectCategory').change(function(){
				var cid = $(this).val();
				$.ajax({
					url:'<?php echo $this->createUrl('productBom/getChildren',array('companyId'=>$this->companyId,));?>/pid/'+cid,
					type:'GET',
					dataType:'json',
					success:function(result){
						var str = '<?php echo yii::t('app','<option value="">--请选择--</option>');?>';
						if(result.data.length){
							$.each(result.data,function(index,value){
								str = str + '<option value="'+value.id+'">'+value.name+'</option>';
							});
						}
						$('#ProductBom_material_id').html(str);
					}
				});
			});
			/*var productVal=$('#ProductBom_material_id').val();
			 $('#ProductBom_material_id').change(function(){
			 var productid = $(this).val();
			 //alert(productid);
			 $.ajax({
			 url:'<php echo $this->createUrl('productBom/isDoubleBomDetail',array('companyId'=>$this->companyId,'productBomId'=>$model->lid));?>/materialid/'+productid,
			 type:'GET',
			 dataType:'json',
			 success:function(result){
			 if(result.data){
			 alert("<php echo yii::t('app','改单品套餐内已经存在！');?>");
			 $('#ProductBom_material_id').val(productVal);
			 }else{
			 //alert(2);
			 productVal=$('#ProductBom_material_id').val();
			 }
			 }
			 });
			 });*/
		});
	</script>