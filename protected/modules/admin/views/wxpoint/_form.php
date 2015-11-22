							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'wxpoint-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
								<div class="form-body">
									<div class="form-group">
										<?php echo $form->label($model, 'cpp_name',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'cpp_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('cpp_name')));?>
											<?php echo $form->error($model, 'cpp_name' )?>
										</div>
									</div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'proportion_points',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'proportion_points',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('proportion_points')));?>
											<?php echo $form->error($model, 'proportion_points' )?>
										</div>
									</div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'is_available',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_available', array( '0' =>yii::t('app','是'),'1' => yii::t('app','否') ) , array('id'=>'is_available', 'class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_available')));?>
											<?php echo $form->error($model, 'is_available' )?>
                                                                                    如果选择“是”，其他项将被强制设置成“否”
										</div>
									</div>
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('wxpoint/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>