							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'company_basic_fee-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
								<div class="form-body">
									<div class="form-group">
										<label class="col-md-3 control-label" ><?php echo yii::t('app','基础费用名称');?></label>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'fee_type', array('1' => yii::t('app','餐位费') , '2' => yii::t('app','打包费') , '3' => yii::t('app','送餐费') , '4' => yii::t('app','外卖起步价')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('fee_type')));?>
											<?php echo $form->error($model, 'fee_type' )?>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label" ><?php echo yii::t('app','费用价格');?></label>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'fee_price',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('fee_price')));?>
											<?php echo $form->error($model, 'fee_price' )?>
										</div>
									</div>									
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('basicFee/index',array('companyId'=>$this->companyId) );?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>						