							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'companywifi-form',
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
											<?php echo $form->dropDownList($model, 'dpid', Helper::genCompanyOptions() ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('dpid')));?>
											<?php echo $form->error($model, 'dpid' )?>
										</div>
									</div>
								<?php endif;?>
								   <div class="form-group">
										<?php echo $form->label($model, 'wifi_name',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'wifi_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('wifi_name')));?>
											<?php echo $form->error($model, 'wifi_name' )?>
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'macid',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'macid',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('macid')));?>
											<?php echo $form->error($model, 'macid' )?>
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'max_number',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'max_number',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('max_number')));?>
											<?php echo $form->error($model, 'max_number' )?>
										</div>
									</div>
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('companyWifi/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>