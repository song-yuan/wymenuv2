							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'wxlevel-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
								<div class="form-body">
									<div class="form-group">
										<?php echo $form->label($model, 'ccp_name',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'ccp_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('ccp_name')));?>
											<?php echo $form->error($model, 'ccp_name' )?>
										</div>
									</div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'point_type',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'point_type', array( '0' =>yii::t('app','历史积分'),'1' => yii::t('app','有效积分') ) , array('id'=>'point_type', 'class' => 'form-control','placeholder'=>$model->getAttributeLabel('point_type')));?>
											<?php echo $form->error($model, 'point_type' )?>
                                                                                    
										</div>
									</div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'min_available_point',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'min_available_point',array('class' => 'form-control','maxLength'=>9,'placeholder'=>$model->getAttributeLabel('min_available_point')));?>
											<?php echo $form->error($model, 'min_available_point' )?>
										</div>
									</div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'max_available_point',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'max_available_point',array('class' => 'form-control','maxLength'=>9,'placeholder'=>$model->getAttributeLabel('max_available_point')));?>
											<?php echo $form->error($model, 'max_available_point' )?>
										</div>
									</div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'proportion_points',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'proportion_points',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('proportion_points')));?>
											<?php echo $form->error($model, 'proportion_points' )?>
                                                                                    消费一元钱，获得多少返现的比例；如：消费一元获得0.01元，此处就填0.01
										</div>
									</div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'is_available',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_available', array( '0' =>yii::t('app','是'),'1' => yii::t('app','否') ) , array('id'=>'is_available', 'class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_available')));?>
											<?php echo $form->error($model, 'is_available' )?>
                                                                                    
										</div>
									</div>
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('wxcashback/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>