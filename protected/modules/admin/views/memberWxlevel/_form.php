						 <?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'memberWxlevel-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
                         
								<div class="form-body">
                                    <div class="form-group">
										<?php echo $form->label($model, 'level_type',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<div class="radio-list">
    											<label class="radio-inline">
    											<input type="radio" name="BrandUserLevel[level_type]" id="optionsRadios0" value="0" checked> 传统会员卡
<!--     											</label> -->
<!--     											<label class="radio-inline"> -->
<!--     											<input type="radio" name="BrandUserLevel[level_type]" id="optionsRadios1" value="1" > 微信会员卡 -->
<!--     											</label>   -->
    										</div>
											<?php echo $form->error($model, 'level_type' )?>
										</div>
									</div>
                                    
									<div class="form-group">
										<?php echo $form->label($model, 'level_name',array('class' => 'col-md-3 control-label'));?>
									    <div class="col-md-4">
											<?php echo $form->textField($model, 'level_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('level_name')));?>
											<?php echo $form->error($model, 'level_name' )?>
										</div>
									</div>
                                    	
                                     <div class="form-group">
										<?php echo $form->label($model, 'level_discount',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'level_discount',array('class' => 'form-control','maxLength'=>9,'placeholder'=>$model->getAttributeLabel('level_discount')));?>
											<?php echo $form->error($model, 'level_discount' )?>
											<span style="color: red;">例：88折（或8.8折）在此处填写为0.88</span>
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'birthday_discount',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'birthday_discount',array('class' => 'form-control','maxLength'=>9,'placeholder'=>$model->getAttributeLabel('birthday_discount')));?>
											<?php echo $form->error($model, 'birthday_discount' )?>
											<span style="color: red;">例：88折（或8.8折）在此处填写为0.88</span>
										</div>
									</div>
                                    <div class="form-group">
										<?php echo $form->label($model, 'min_charge_money',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'min_charge_money',array('class' => 'form-control','maxLength'=>9,'placeholder'=>$model->getAttributeLabel('min_charge_money')));?>
											<?php echo $form->error($model, 'min_charge_money' )?>
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'card_cost',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'card_cost',array('class' => 'form-control','maxLength'=>9,'placeholder'=>$model->getAttributeLabel('card_cost')));?>
											<?php echo $form->error($model, 'card_cost' )?>
										</div>
									</div>
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('memberWxlevel/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>