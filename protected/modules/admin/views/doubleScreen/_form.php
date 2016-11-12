							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'taste-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
								<div class="form-body">
									<div class="form-group">
										<?php echo $form->label($model, 'title',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'title',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('title')));?>
											<?php echo $form->error($model, 'title' )?>
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'desc',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'desc',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('desc')));?>
											<?php echo $form->error($model, 'desc' )?>
										</div>
									</div>
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('doubleScreen/index' , array('companyId' => $model->dpid,'type'=>$type));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>