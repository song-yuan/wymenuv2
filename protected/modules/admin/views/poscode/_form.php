							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'PadSetting-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
								<div class="form-body">
								
                                    
                                   <div class="form-group">
										<?php echo $form->label($model, 'pad_sales_type',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'pad_sales_type', array('0' => yii::t('app','西餐模式'),'1' => yii::t('app','中餐模式'),),array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('pad_sales_type')));?>
											<?php echo $form->error($model, 'pad_sales_type' )?>
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'screen_type',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'screen_type', array('0' => yii::t('app','单屏收款机'),'1' => yii::t('app','双屏收款机'),),array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('screen_type')));?>
											<?php echo $form->error($model, 'pad_sales_type' )?>
										</div>
									</div>
                                    
									
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('poscode/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>