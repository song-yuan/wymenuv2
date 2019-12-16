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
										<?php echo $form->label($model, 'description',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'description',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('description')));?>
											<?php echo $form->error($model, 'description' )?>
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'is_able',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_able', array('0' => yii::t('app','不显示'), '1' => yii::t('app','pos机显示'), '2' => yii::t('app','微信端显示')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_able')));?>
											<?php echo $form->error($model, 'is_able' )?>
										</div>
									</div>
									<!-- 
									<div class="form-group">
										<?php echo $form->label($model, 'is_able',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_able',array('0' => yii::t('app','无效') , '1' => yii::t('app','有效')) ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_able')));?>
											<?php echo $form->error($model, 'is_able' )?>
										</div>
									</div>
									 -->
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('doubleScreen/index' , array('companyId' => $model->dpid,'type'=>$type));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>