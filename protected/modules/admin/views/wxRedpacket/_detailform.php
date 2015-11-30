							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'redpacketsendstrategy-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
								<style>
								#category_container select {display:block;float:left;margin-right:3px;max-width:200px;overflow:hidden;}
								</style>
								<div class="form-body">
														
								
									<div class="form-group" >
									<?php if($model->hasErrors('min_money')) echo 'has-error';?>
										<?php echo $form->label($model, yii::t('app','使用该红包的最低消费'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'min_money',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('min_money')));?>
											<?php echo $form->error($model, 'min_money' )?>
										</div>
									</div><!-- 活动摘要 -->
									<div class="form-group" >
									<?php if($model->hasErrors('max_money')) echo 'has-error';?>
										<?php echo $form->label($model, yii::t('app','使用该红包时的最高消费'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'max_money',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('max_money')));?>
											<?php echo $form->error($model, 'max_money' )?>
										</div>
									</div><!-- 活动摘要 -->
									<div class="form-group">
									<?php if($model->hasErrors('send_type')) echo 'has-error';?>
										<?php echo $form->label($model, yii::t('app','选择发送事件'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'send_type', array('0' => yii::t('app','结单时发送') , '1' => yii::t('app','关注时发送')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('send_type')));?>
											<?php echo $form->error($model, 'send_type' )?>
										</div>
									</div><!-- 是否可用代金券 -->
                                    <div class="form-group">
									<?php if($model->hasErrors('is_available')) echo 'has-error';?>
										<?php echo $form->label($model, yii::t('app','选择发送事件'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_available', array('0' => yii::t('app','生效') , '1' => yii::t('app','不生效')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_available')));?>
											<?php echo $form->error($model, 'is_available' )?>
										</div>
									</div><!-- 是否可用代金券 -->
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('wxRedpacket/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>
						
	