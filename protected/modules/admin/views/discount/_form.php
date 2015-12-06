							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'discount-form',
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
														
									<div class="form-group ">
									<?php if($model->hasErrors('discount_name')) echo 'has-error';?>
										<?php echo $form->label($model, yii::t('app','折扣模板名称'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'discount_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('discount_name')));?>
											<?php echo $form->error($model, 'discount_name' )?>
										</div>
									</div><!-- 活动标题 -->
						
									<div class="form-group" >
									<?php if($model->hasErrors('discount_abstract')) echo 'has-error';?>
										<?php echo $form->label($model, yii::t('app','摘要'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'discount_abstract',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('discount_abstract')));?>
											<?php echo $form->error($model, 'discount_abstract' )?>
										</div>
									</div><!-- 活动摘要 -->
									<div class="form-group" >
									<?php if($model->hasErrors('discount_num')) echo 'has-error';?>
										<?php echo $form->label($model, yii::t('app','折扣比例'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'discount_num',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('discount_num')));?>
											<?php echo $form->error($model, 'discount_num' )?>
										</div>
									</div><!-- 活动摘要 -->
									<div class="form-group">
									<?php if($model->hasErrors('is_available')) echo 'has-error';?>
										<?php echo $form->label($model, yii::t('app','设置折扣是否生效'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_available', array( '0' => yii::t('app','生效'), '2' => yii::t('app','无效')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_available')));?>
											<?php echo $form->error($model, 'is_available' )?>
										</div>
									</div>
                                    
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('discount/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>
							
							
