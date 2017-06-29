							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'goods-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data',
									),
							)); ?>
								<style>
								#category_container select {display:block;float:left;margin-right:3px;max-width:200px;overflow:hidden;}
								</style>
								<?php  if($istempp){ $a = true;}else{$a=false;}?>
								<?php  if(Yii::app()->user->role>=11&&$islock){ $b = true;}else{$b=false;}?>
								<div class="form-body">
									<div class="form-group  <?php if($model->hasErrors('category_id')) echo 'has-error';?>">
										<?php echo $form->label($model, 'category_id',array('class' => 'col-md-3 control-label'));?>
										<div id="category_container" class="col-md-9">
										<?php $this->widget('application.modules.admin.components.widgets.MaterialCategorySelecter',array('categoryId'=>$model->category_id,'companyId'=>$compid)); ?>
										<?php echo $form->error($model, 'category_id' )?>
										</div>
										<?php echo $form->hiddenField($model,'category_id',array('class'=>'form-control')); ?>
									</div>
								<?php if($istempp){ echo '<script>
															$(".category_selecter").each(function(){
																$(this).attr("disabled",true)
																//document.querySelector(".category_selecter").setAttribute("disabled",true);
															});
															//var btn=document.querySelector(".category_selecter");
															//for(var i;i<=btn.length;i++){
																//$("#test").attr("test","aaa")
															//	}
															//btn.disabled=true;
																				</script>';
									}?>
									<div class="form-group <?php if($model->hasErrors('goods_name')) echo 'has-error';?>">
										<?php echo $form->label($model, 'goods_name',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'goods_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('goods_name'),'disabled'=>$a,));?>
											<?php echo $form->error($model, 'goods_name' )?>
											<?php if($istempp):?><span style="color: red;">来自总部下发菜品，无法修改以上选项</span><?php endif;?>
										</div>
									</div>
						  			<div class="form-group <?php if($model->hasErrors('main_picture')) echo 'has-error';?>">
										<?php echo $form->label($model,'main_picture',array('class'=>'control-label col-md-3')); ?>
										<div class="col-md-9">
												<div class="fileupload fileupload-new" data-provides="fileupload">
													<div class="fileupload-new thumbnail"  style="max-width: 200px; max-height: 200px; line-height: 20px;">
														<img src="<?php echo $model->main_picture?$model->main_picture:'';?>" alt="" />
													</div>
													<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 200px; line-height: 20px;"></div>
													<div>
														<span class="btn default btn-file">
														<span class="fileupload-new"><i class="fa fa-paper-clip"></i> 上传产品图片 </span>
														<span class="fileupload-exists"><i class="fa fa-undo"></i> 更改 </span>
														<input type="file" accept="image/png,image/jpg,image/jpeg" name="file" class="default" />
														</span>
														<a href="#" class="btn red fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash-o"></i> 移除 </a>
													</div>
												</div>
												<span class="label label-danger">注意:</span>
												<span>大小：建议300px*300px且不超过2M 格式:jpg 、png、jpeg </span>
										</div>
										<?php echo $form->hiddenField($model,'main_picture'); ?>
									</div>
									<div class="form-group" <?php if($model->hasErrors('original_price')) echo 'has-error';?>>
										<?php echo $form->label($model, 'original_price',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'original_price',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('original_price'),'disabled'=>$b,));?>
											<?php echo $form->error($model, 'original_price' )?>
										</div>
									</div>
									<div class="form-group" <?php if($model->hasErrors('member_price')) echo 'has-error';?>>
										<?php echo $form->label($model, 'member_price',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'member_price',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('member_price'),'disabled'=>$b,));?>
											<?php echo $form->error($model, 'member_price' )?>
											<span style="color: red;"></span>
											<?php if($islock&&Yii::app()->user->role >=11):?><span style="color: red;">已开通微店，价格已被总部锁定，无法修改。</span><?php endif;?>
										</div>
									</div>
									<div class="form-group" <?php if($model->hasErrors('sort')) echo 'has-error';?>>
										<?php echo $form->label($model, 'sort',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'sort',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('sort')));?>
											<?php echo $form->error($model, 'sort' )?>
											<span style="color: red;">数字越小，显示越靠前。</span>
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'is_member_discount',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_member_discount', array('0' => yii::t('app','不参与') , '1' => yii::t('app','参与')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_member_discount')));?>
											<?php echo $form->error($model, 'is_member_discount' )?>
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
									
									<div class="form-group" <?php if($model->hasErrors('goods_unit')) echo 'has-error';?>>
										<?php echo $form->label($model, 'goods_unit',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'goods_unit',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('goods_unit')));?>
											<?php echo $form->error($model, 'goods_unit' )?>
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
											<!-- <a href="<?php echo $this->createUrl('product/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a> -->                              
										</div>
									</div>
							<?php $this->endWidget(); ?>
							<?php $this->widget('ext.kindeditor.KindEditorWidget',array(
								'id'=>'Goods_description',	//Textarea id
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
	  $('input[name="file"]').change(function(){
		  	$('form').ajaxSubmit(function(msg){
				$('#Goods_main_picture').val(msg);
			});
	   });
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
	   			url:'<?php echo $this->createUrl('goods/getChildren',array('companyId'=>$compid));?>/pid/'+id,
	   			type:'GET',
	   			dataType:'json',
	   			success:function(result){
	   				if(result.data.length){
	   					var str = '<select class="form-control category_selecter" tabindex="-1" name="category_id_selecter" ,<?php if ($a) echo 'disabled = true';else echo '';?>>'+
	   					'<option value="">--'+"<?php echo yii::t('app','请选择');?>"+'--</option>';
	   					$.each(result.data,function(index,value){
	   						str = str + '<option value="'+value.id+'">'+value.name+'</option>';
	   					});
	   					str = str + '</select>';
	   					$parent.append(str);
	   					$('#Goods_category_id').val('');
	   					
	   					$parent.find('span').remove();
	   				}else{
                        $('#Goods_category_id').val(sid);                                                
	   				}
	   			}
	   		});
	   		
	   });
		function a(){
			alert('wodaole');
			document.getElementByName("category_id_selecter").setAttribute("disabled","true");
			}
		function swfupload_callback(name,path,oldname)  {
			$("#Goods_main_picture").val(name);
			$("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
		}
	</script>