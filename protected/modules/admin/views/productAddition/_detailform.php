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
											<?php echo $form->dropDownList($model, 'dpid', array('0' => yii::t('app','-- 请选择 --')) +Helper::genCompanyOptions() ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('dpid')));?>
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
                                                                        
                                                                        <div class="form-group" <?php if($model->hasErrors('sproduct_id')) echo 'has-error';?>>
										<?php echo $form->label($model, 'sproduct_id',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">											
                                                                                        <?php echo $form->dropDownList($model, 'sproduct_id', array('0' => yii::t('app','-- 请选择 --')) +$products ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('sproduct_id')));?>
											<?php echo $form->error($model, 'sproduct_id' )?>
										</div>
									</div>
                                                                        <div class="form-group" <?php if($model->hasErrors('price')) echo 'has-error';?>>
										<?php echo $form->label($model, 'price',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'price',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('price')));?>
											<?php echo $form->error($model, 'price' )?>
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
											<a href="<?php echo $this->createUrl('productAddition/detail' , array('companyId' => $model->dpid,'lid' => $model->mproduct_id));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
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
                                                                                    url:'<?php echo $this->createUrl('productAddition/getChildren',array('companyId'=>$this->companyId));?>/pid/'+cid,
                                                                                    type:'GET',
                                                                                    dataType:'json',
                                                                                    success:function(result){
                                                                                            //alert(result.data);
                                                                                            var str = '<option value="">--<?php echo yii::t('app','请选择');?>--</option>';                                                                                            
                                                                                            if(result.data.length){
                                                                                                    //alert(1);
                                                                                                    $.each(result.data,function(index,value){
                                                                                                            str = str + '<option value="'+value.id+'">'+value.name+'</option>';
                                                                                                    });                                                                                                                                                                                                       
                                                                                            }
                                                                                            $('#ProductAddition_sproduct_id').html(str); 
                                                                                    }
                                                                            });
                                                                    });
                                                            });
                                                        </script>