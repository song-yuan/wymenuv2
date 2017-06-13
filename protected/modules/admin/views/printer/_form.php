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
									<?php echo $form->label($model, 'name',array('class' => 'col-md-3 control-label'));?>
									<div class="col-md-4">
										<?php echo $form->textField($model, 'name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('name')));?>
										<?php echo $form->error($model, 'name' )?>
									</div>
								</div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'address',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'address',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('address')));?>
											<?php echo $form->error($model, 'address' )?>
										</div>
									</div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'language',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'language', array('1' => yii::t('app','中文') , '2' => yii::t('app','日文')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('language')));?>
											<?php echo $form->error($model, 'language' )?>
										</div>
									</div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'printer_type',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'printer_type', array('0' => yii::t('app','网络') , '1' => yii::t('app','本地'), '2' => yii::t('app','标签')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('printer_type')));?>
											<?php echo $form->error($model, 'printer_type' )?>
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'brand',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'brand',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('brand')));?>
											<?php echo $form->error($model, 'brand' )?>
										</div>
									</div>
                                                                      
									<div class="form-group">
										<?php echo $form->label($model, 'remark',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textArea($model, 'remark',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('remark')));?>
											<?php echo $form->error($model, 'remark' )?>
										</div>
									</div>
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('printer/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>