							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'DoubleScreenDetail-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
								<div class="form-body">
									<div class="form-group">
										<?php echo $form->label($model, 'type',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'type',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('type')));?>
											<?php echo $form->error($model, 'type' )?>
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'url',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'url',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('url')));?>
											<?php echo $form->error($model, 'url' )?>
										</div>
									</div>
									
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('doubleScreen/detailIndex' , array('companyId' => $model->dpid,'groupname'=>$groupname ,'groupid'=>$groupid,'type'=>$type));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>