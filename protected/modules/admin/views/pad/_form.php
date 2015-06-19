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
										<?php echo $form->label($model, 'printer_id',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'printer_id', array('0' => yii::t('app','-- 请选择 --')) +$printers ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('printer_id')));?>
											<?php echo $form->error($model, 'printer_id' )?>
										</div>
									</div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'server_address',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'server_address',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('server_address')));?>
											<?php echo $form->error($model, 'server_address' )?>
                                                                                    <?php echo yii::t('app','消息服务器，填写：IP和端口号，如：ws://192.168.100.100:3030');?>，<br><?php echo yii::t('app','所有pad都需要填写消息服务器');?>
										</div>
									</div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'pad_type',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'pad_type', array('0' => yii::t('app','收银台') , '1' => yii::t('app','日本点单PAD'),'2'=>yii::t('app','中国点单PAD')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('pad_type')));?>
											<?php echo $form->error($model, 'pad_type' )?>
										</div>
									</div>
									
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('pad/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>