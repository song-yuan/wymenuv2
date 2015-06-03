							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'company-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
								<div class="form-body">
									<div class="form-group">
										<label class="col-md-3 control-label" ><?php echo yii::t('app','支付方式名称');?></label>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('name')));?>
											<?php echo $form->error($model, 'name' )?>
										</div>
									</div>
																		
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('payMethod/index',array('companyId'=>$this->companyId) );?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>						