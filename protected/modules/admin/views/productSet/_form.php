							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'printer-form',
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
								<?php  if($istempp){ $a = true;}else{$a=false;}?>
								<?php if($status):?>
								<?php $status=true;?>
								<?php else: $status=false;?>
								<?php endif;?>
								<?php if(!$model->dpid):?>
									<div class="form-group">
										<?php echo $form->label($model, 'dpid',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'dpid', array('0' => yii::t('app','-- 请选择 --')) +Helper::genCompanyOptions() ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('dpid')));?>
											<?php echo $form->error($model, 'dpid' )?>
										</div>
									</div>
								<?php endif;?>
								<div class="form-group  <?php if($model->hasErrors('category_id')) echo 'has-error';?>">
										<?php echo $form->label($model, 'category_id',array('class' => 'col-md-3 control-label'));?>
										<div id="category_container" class="col-md-9">
										<?php $this->widget('application.modules.admin.components.widgets.ProductSetCategorySelecter',array('categoryId'=>$model->category_id,'companyId'=>$this->companyId)); ?>
										<?php echo $form->error($model, 'category_id' )?>
										</div>
										<?php echo $form->hiddenField($model,'category_id',array('class'=>'form-control')); ?>
									</div>
								<?php if($istempp){ echo '<script>
															$(".category_selecter").each(function(){
																$(this).attr("disabled",true)
															});
															</script>';
									}?>
									<div class="form-group">
										<?php echo $form->label($model, 'type',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'type', array('0' => '套餐' , '1' => '自由组合') , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('type'),'disabled'=>$status));?>
											<?php echo $form->error($model, 'type' )?>
										</div>
									</div>
                                    <div class="form-group">
                                            <?php echo $form->label($model, 'set_name',array('class' => 'col-md-3 control-label'));?>
                                            <div class="col-md-4">
                                                    <?php echo $form->textField($model, 'set_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('set_name'),'disabled'=>$status));?>
                                                    <?php echo $form->error($model, 'set_name' )?>
                                            </div>
                                    </div>
                                    <div class="form-group">
										<?php echo $form->label($model, 'set_price',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'set_price', array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('set_price')));?>
											<?php echo $form->error($model, 'set_price' )?>
											<span style="color: red;">套餐总价格 = 套餐基础价格 + 套餐明细里的各个菜品价格</span>
										
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'member_price',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'member_price', array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('member_price')));?>
											<?php echo $form->error($model, 'member_price' )?>
											<span style="color: red;">设置该价格只针对会员进行优惠</span>
										
										</div>
									</div>
                                   <div class="form-group">
										<?php echo $form->label($model, 'rank',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'rank', array('1' => '1' , '2' => '2', '3' => '3', '4' => '4', '5' => '5') , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('rank')));?>
											<?php echo $form->error($model, 'rank' )?>
										</div>
									</div>
                                    <div class="form-group">
										<?php echo $form->label($model, 'is_member_discount',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_member_discount', array('0' => yii::t('app','否') , '1' => yii::t('app','是')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_member_discount')));?>
											<?php echo $form->error($model, 'is_member_discount' )?>
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'is_discount',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_discount', array('1' => yii::t('app','否') , '0' => yii::t('app','是')) , array('class' => 'form-control',));?>
											<?php echo $form->error($model, 'is_discount' )?>
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'is_show',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_show', array('1' => yii::t('app','否') , '0' => yii::t('app','是')) , array('class' => 'form-control',));?>
											<?php echo $form->error($model, 'is_show' )?>
										</div>
									</div>
									<?php if(Yii::app()->user->role <11):?>
									<div class="form-group">
										<?php echo $form->label($model, 'is_show_wx',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_show_wx', array('1' => yii::t('app','是') , '2' => yii::t('app','否')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_show_wx')));?>
											<?php echo $form->error($model, 'is_show_wx' )?>
										</div>
									</div>
									<?php else:?>
									<div style="display: none;" class="form-group">
										<?php echo $form->label($model, 'is_show_wx',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_show_wx', array( '2' => yii::t('app','否')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_show_wx')));?>
											<?php echo $form->error($model, 'is_show_wx' )?>
										</div>
									</div>
									<?php endif;?>
									
                                                                        <!--<div class="form-group">
										<?php echo $form->label($model, 'is_special',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_special', array('0' =>yii::t('app','否')  , '1' => yii::t('app','是')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_special')));?>
											<?php echo $form->error($model, 'is_special' )?>
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
										<?php echo $form->label($model, 'status',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'status', array('0' => yii::t('app','否') , '1' => yii::t('app','是')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('status')));?>
											<?php echo $form->error($model, 'status' )?>
										</div>
									</div>-->
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
											<!-- <a href="<?php echo $this->createUrl('productSet/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a> -->
										</div>
									</div>
							<?php $this->endWidget(); ?>
                                                        <?php $this->widget('ext.kindeditor.KindEditorWidget',array(
								'id'=>'ProductSet_description',	//Textarea id
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
                           }
                          
   	   		$(this).nextAll().remove();
   	   		$.ajax({
   	   			url:'<?php echo $this->createUrl('productSet/getSetChildren',array('companyId'=>$this->companyId));?>/pid/'+id,
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
   	   					$('#ProductSet_category_id').val('');
   	   					
   	   					$parent.find('span').remove();
   	   				}else{                        
                         $('#ProductSet_category_id').val(sid);                                                
   	   				}
   	   			}
   	   		});
   	   		
   	   });
       	$('input[name="file"]').change(function(){
   		  	$('form').ajaxSubmit(function(msg){
   				$('#ProductSet_main_picture').val(msg);
   			});
   	   	});
		function swfupload_callback(name,path,oldname)  {
			$("#ProductSet_main_picture").val(name);
			$("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
		}
	</script>