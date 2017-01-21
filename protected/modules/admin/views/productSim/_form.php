							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'productSim-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
								<style>
								#category_container select {display:block;float:left;margin-right:3px;max-width:200px;overflow:hidden;}
								</style>
								<!-- <div class="form-body">
									<div class="form-group  <?php if($model->hasErrors('category_id')) echo 'has-error';?>">
										<?php echo $form->label($model, 'category_id',array('class' => 'col-md-3 control-label'));?>
										<div id="category_container" class="col-md-9">
										<?php $this->widget('application.modules.admin.components.widgets.ProductCategorySelecter',array('categoryId'=>$model->category_id,'companyId'=>$this->companyId)); ?>
										<?php echo $form->error($model, 'category_id' )?>
										</div>
										<?php echo $form->hiddenField($model,'category_id',array('class'=>'form-control')); ?>
									</div>
								-->
									<div class="form-group <?php if($model->hasErrors('product_name')) echo 'has-error';?>">
										<?php echo $form->label($model, 'product_name',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'product_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('product_name')));?>
											<?php echo $form->error($model, 'product_name' )?>
										</div>
									</div>

						 
									<div class="form-group" <?php if($model->hasErrors('simple_code')) echo 'has-error';?>>
										<?php echo $form->label($model, '拼音简称',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'simple_code',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('simple_code')));?>
											<?php echo $form->error($model, 'simple_code' )?>
										</div>
									</div>
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<!-- <a href="<?php echo $this->createUrl('productSim/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a> -->
										</div>
									</div>
							<?php $this->endWidget(); ?>
							<?php $this->widget('ext.kindeditor.KindEditorWidget',array(
								'id'=>'Product_description',	//Textarea id
								'language'=>'zh_CN',
								// Additional Parameters (Check http://www.kindsoft.net/docs/option.html)
								'items' => array(
									'height'=>'200px',
									'width'=>'100%',
									'themeType'=>'simple',
									'resizeType'=>1,
									//'allowImageUpload'=>true,
									//'allowFileManager'=>true,
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
	   		
	   });
	
		function swfupload_callback(name,path,oldname)  {
			$("#Product_main_picture").val(name);
			$("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
		}
	</script>