							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'printer-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
								<div class="form-body">
								<?php if(!$model->dpid):?>
									<div class="form-group">
										<?php echo $form->label($model, 'dpid',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'dpid', array('0' => '-- 请选择 --') +Helper::genCompanyOptions() ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('dpid')));?>
											<?php echo $form->error($model, 'dpid' )?>
										</div>
									</div>
								<?php endif;?>
                                                                        
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'category_id',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo CHtml::dropDownList('selectCategory', $categoryId, $categories , array('class'=>'form-control'));?>
                                                                                </div>
                                                                        </div>
                                                                        
                                                                        <div class="form-group" <?php if($model->hasErrors('product_id')) echo 'has-error';?>>
										<?php echo $form->label($model, 'product_id',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">											
                                                                                        <?php echo $form->dropDownList($model, 'product_id', array('0' => '-- 请选择 --') +$products ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('dpid')));?>
											<?php echo $form->error($model, 'product_id' )?>
										</div>
									</div>
                                                                        <div class="form-group" <?php if($model->hasErrors('price')) echo 'has-error';?>>
										<?php echo $form->label($model, 'price',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'price',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('price')));?>
											<?php echo $form->error($model, 'price' )?>
										</div>
									</div>
                                                                        <div class="form-group" <?php if($model->hasErrors('group_no')) echo 'has-error';?>>
										<?php echo $form->label($model, 'group_no',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'group_no',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('group_no')));?>
											<?php echo $form->error($model, 'group_no' )?>
										</div>
									</div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'is_select',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_select', array('0' => '否' , '1' => '是') , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_select')));?>
											<?php echo $form->error($model, 'is_select' )?>
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
											<button type="submit" class="btn blue">确定</button>
											<a href="<?php echo $this->createUrl('productSet/detailindex' , array('companyId' => $model->dpid,'lid' => $model->set_id));?>" class="btn default">返回</a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>
                                                        <script type="text/javascript">
                                                            $(document).ready(function(){
                                                                    $('#selectCategory').change(function(){
                                                                            var cid = $(this).val();
                                                                            //alert('<?php echo $this->createUrl('productSet/getChildren',array('companyId'=>$this->companyId));?>/pid/'+cid);
                                                                            //alert($('#ProductSetDetail_product_id').html());
                                                                            $.ajax({
                                                                                    url:'<?php echo $this->createUrl('productSet/getChildren',array('companyId'=>$this->companyId));?>/pid/'+cid,
                                                                                    type:'GET',
                                                                                    dataType:'json',
                                                                                    success:function(result){
                                                                                            //alert(result.data);
                                                                                            var str = '<option value="">--请选择--</option>';                                                                                            
                                                                                            if(result.data.length){
                                                                                                    //alert(1);
                                                                                                    $.each(result.data,function(index,value){
                                                                                                            str = str + '<option value="'+value.id+'">'+value.name+'</option>';
                                                                                                    });                                                                                                                                                                                                       
                                                                                            }
                                                                                            $('#ProductSetDetail_product_id').html(str); 
                                                                                    }
                                                                            });
                                                                    });
                                                            });
                                                        </script>