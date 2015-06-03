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
                                                                                <?php echo $form->label($model, 'floor_id',array('class' => 'col-md-3 control-label'));?>
                                                                                <div class="col-md-4">
                                                                                        <?php echo $form->dropDownList($model, 'floor_id', array('0' => yii::t('app','-- 请选择 默认是临时区域 --')) +$floors ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('floor_id')));?>
                                                                                        <?php echo $form->error($model, 'floor_id' )?>
                                                                                </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                                <?php echo $form->label($model, 'printer_id',array('class' => 'col-md-3 control-label'));?>
                                                                                <div class="col-md-4">
                                                                                        <?php echo $form->dropDownList($model, 'printer_id', array('0' => yii::t('app','-- 请选择 --')) +$printers ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('printer_id')));?>
                                                                                        <?php echo $form->error($model, 'printer_id' )?>
                                                                                </div>
                                                                        </div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'list_no',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textArea($model, 'list_no',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('list_no')));?>
											<?php echo $form->error($model, 'list_no' )?><?php echo yii::t('app','>小于127');?>
										</div>
									</div>
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('printerWay/detailindex' , array('companyId' => $model->dpid,'lid' => $model->print_way_id));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>