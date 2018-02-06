							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'product-form',
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
										<?php $this->widget('application.modules.admin.components.widgets.ProductCategorySelecter',array('categoryId'=>$model->category_id,'companyId'=>$this->companyId)); ?>
										<?php echo $form->error($model, 'category_id' )?>
										</div>
										<?php echo $form->hiddenField($model,'category_id',array('class'=>'form-control')); ?>
									</div>
								
									<div class="form-group <?php if($model->hasErrors('product_name')) echo 'has-error';?>">
										<?php echo $form->label($model, 'product_name',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'product_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('product_name')));?>
											<?php echo $form->error($model, 'product_name' )?>
										</div>
									</div>
									<div class="form-group <?php if($model->hasErrors('main_picture')) echo 'has-error';?>">
										<?php echo $form->label($model,'main_picture',array('class'=>'control-label col-md-3')); ?>
										<div class="col-md-9">
										<?php
										$this->widget('application.extensions.swfupload.SWFUpload',array(
											'callbackJS'=>'swfupload_callback',
											'fileTypes'=> '*.jpg',
											'buttonText'=> yii::t('app','上传产品图片'),
											'companyId' => $model->dpid,
											'imgUrlList' => array($model->main_picture),
										));
										?>
										<?php echo $form->hiddenField($model,'main_picture'); ?>
										<?php echo $form->error($model,'main_picture'); ?>
										</div>
									</div>
						
									<div class="form-group" <?php if($model->hasErrors('original_price')) echo 'has-error';?>>
										<?php echo $form->label($model, 'original_price',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'original_price',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('original_price')));?>
											<?php echo $form->error($model, 'original_price' )?>
										</div>
									</div>
									<div class="form-group" <?php if($model->hasErrors('dabao_fee')) echo 'has-error';?>>
										<?php echo $form->label($model, 'dabao_fee',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'dabao_fee',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('dabao_fee')));?>
											<?php echo $form->error($model, 'dabao_fee' )?>
										</div>
									</div>
									<div class="form-group" <?php if($model->hasErrors('rank')) echo 'has-error';?>>
										<?php echo $form->label($model, 'rank',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'rank', array('1' => '1' , '2' => '2' , '3' => '3' , '4' => '4' , '5' => '5') , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('rank')));?>
											<?php echo $form->error($model, 'rank' )?>
										</div>
									</div>
									<div class="form-group" <?php if($model->hasErrors('spicy')) echo 'has-error';?>>
										<?php echo $form->label($model, 'spicy',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'spicy', array('0' => '0' , '1' => '1' , '2' => '2' , '3' => '3' ) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('spicy')));?>
											<?php echo $form->error($model, 'spicy' )?>
										</div>
									</div>
                                    <div class="form-group">
										<?php echo $form->label($model, 'is_discount',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_discount', array('0' => yii::t('app','否') , '1' => yii::t('app','是')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_discount')));?>
											<?php echo $form->error($model, 'is_discount' )?>
										</div>
									</div>
                                    <div class="form-group">
										<?php echo $form->label($model, 'is_show',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_show', array('0' => yii::t('app','否') , '1' => yii::t('app','是')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_show')));?>
											<?php echo $form->error($model, 'is_show' )?>
										</div>
									</div>
									<div class="form-group" <?php if($model->hasErrors('product_unit')) echo 'has-error';?>>
										<?php echo $form->label($model, 'product_unit',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'product_unit',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('product_unit')));?>
											<?php echo $form->error($model, 'product_unit' )?>
										</div>
									</div>
                                    <div class="form-group">
										<?php echo $form->label($model, 'is_weight_confirm',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_weight_confirm', array('0' =>yii::t('app','否') , '1' => yii::t('app','是')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_weight_confirm')));?>
											<?php echo $form->error($model, 'is_weight_confirm' )?>
										</div>
									</div>
                                    <div class="form-group" <?php if($model->hasErrors('weight_unit')) echo 'has-error';?>>
										<?php echo $form->label($model, 'weight_unit',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'weight_unit',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('weight_unit')));?>
											<?php echo $form->error($model, 'weight_unit' )?>
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'description',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-8">
											<?php echo $form->textArea($model, 'description' , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('description')));?>
											<?php echo $form->error($model, 'description' )?>
										</div>
									</div>
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('product/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
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
	   			url:'<?php echo $this->createUrl('product/getChildren',array('companyId'=>$this->companyId));?>/pid/'+id,
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
	   					$('#Product_category_id').val('');
	   					$parent.find('span').remove();
	   				}else{
                                                //if(selname == 'category_id_selecter2')
                                                    $('#Product_category_id').val(sid);                                                
	   				}
	   			}
	   		});
	   });
	
		function swfupload_callback(name,path,oldname)  {
			$("#Product_main_picture").val(name);
			$("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
		}
	</script>