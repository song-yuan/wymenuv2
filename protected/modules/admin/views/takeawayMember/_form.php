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
										<label class="col-md-3 control-label" ><?php if($types) echo yii::t('app','配送员姓名');else echo yii::t('app','送餐员姓名');?></label>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'member_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('member_name')));?>
											<?php echo $form->error($model, 'member_name' )?>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label" ><?php if($types) echo yii::t('app','配送员编号');else yii::t('app','送餐员编号');?></label>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'cardId',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('cardId')));?>
											<?php echo $form->error($model, 'cardId' )?>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label" ><?php echo yii::t('app','手机号');?></label>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'phone_number',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('phone_number')));?>
											<?php echo $form->error($model, 'phone_number' )?>
										</div>
									</div>									
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('takeawayMember/index',array('companyId'=>$this->companyId,'types'=>$types) );?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>						