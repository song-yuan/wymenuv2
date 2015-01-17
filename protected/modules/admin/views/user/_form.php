							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'user-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
								<div class="form-body">
								<?php if(Yii::app()->user->role == User::POWER_ADMIN):?>
									<div class="form-group  <?php if($model->hasErrors('company_id')) echo 'has-error';?>">
										<?php echo $form->label($model, 'company_id',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'company_id', array('0' => '-- 请选择 --') + Helper::genCompanyOptions() ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('company_id')));?>
											<?php echo $form->error($model, 'company_id' )?>
										</div>
									</div>
								<?php endif;?>
									<div class="form-group <?php if($model->hasErrors('role')) echo 'has-error';?>">
										<?php echo $form->label($model, 'role',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'role', $this->roles ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('company_id')));?>
											<?php echo $form->error($model, 'role' )?>
										</div>
									</div>
									<div class="form-group <?php if($model->hasErrors('username')) echo 'has-error';?>">
										<?php echo $form->label($model, 'username',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'username',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('username')));?>
											<?php echo $form->error($model, 'username' )?>
										</div>
									</div>
									<div class="form-group <?php if($model->hasErrors('mobile')) echo 'has-error';?>">
										<?php echo $form->label($model, 'mobile',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'mobile',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('mobile')));?>
											<?php echo $form->error($model, 'mobile' )?>
										</div>
									</div>
									<div class="form-group <?php if($model->hasErrors('password')) echo 'has-error';?>">
										<?php echo $form->label($model, 'password',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->passwordField($model, 'password',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('password')));?>
											<?php echo $form->error($model, 'password' )?>
										</div>
									</div>
									<div class="form-group <?php if($model->hasErrors('staff_no')) echo 'has-error';?>">
										<?php echo $form->label($model, 'staff_no',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'staff_no',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('staff_no')));?>
											<?php echo $form->error($model, 'staff_no' )?>
										</div>
									</div>
									<div class="form-group <?php if($model->hasErrors('email')) echo 'has-error';?>">
										<?php echo $form->label($model, 'email',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'email',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('email')));?>
											<?php echo $form->error($model, 'email' )?>
										</div>
									</div>
									
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue">确定</button>
											<a href="<?php echo $this->createUrl('user/index');?>" class="btn default">返回</a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>