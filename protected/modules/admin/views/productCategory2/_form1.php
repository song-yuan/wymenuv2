			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'GoodsCategory',
				'action'=>$action,
				'enableAjaxValidation'=>true,
				'enableClientValidation'=>true,
				'clientOptions'=>array(
					'validateOnSubmit'=>false,
				),
				'htmlOptions'=>array(
					'class'=>'form-horizontal'
				),
			)); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><?php echo yii::t('app','添加商品类目');?></h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<?php echo $form->label($model,'category_name',array('class'=>'col-md-3 control-label')); ?>
					<div class="col-md-9">
						<?php echo $form->hiddenField($model,'parent_id'); ?>
						<?php echo $form->hiddenField($model,'dpid'); ?>
						<?php echo $form->textField($model,'category_name',array('class'=>'form-control','placeholder'=>$model->getAttributeLabel('category_name'))); ?>
						<?php echo $form->error($model,'category_name',array('class'=>'errorMessage')); ?>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn default"><?php echo yii::t('app','取 消');?></button>
				<input type="submit" class="btn green" id="create_btn" value="<?php echo yii::t('app','确 定');?>">
			</div>
			<?php $this->endWidget(); ?>
			<script>
			$('#GoodsCategory').on('submit',function(){
				$.ajax({
					'type':'POST',
					'dataType':'json',
					'data':$('#GoodsCategory').serialize(),
					'url':$('#GoodsCategory').attr('action'),
					'success':function(data){
						if(data.status == 0) {
							alert(data.message);
						} else {
							alert(data.message);
							location.href='<?php echo $this->createUrl('productCategory/index',array('companyId'=>$this->companyId));?>';
						}
					}
				});
				return false;
			});
			</script>
			
			