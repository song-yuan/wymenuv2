		<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'login-form',
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					
				),
		)); ?>
			<h3 class="form-title">商城登陆</h3>
			<div class="alert alert-danger display-hide">
				<button class="close" data-close="alert"></button>
				<span>输入用户名和密码</span>
			</div>
			<div class="form-group <?php if($model->hasErrors('username')) echo 'has-error';?>">
				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label class="control-label visible-ie8 visible-ie9">Username</label>
				<div class="input-icon">
					<i class="fa fa-user"></i>
					<?php echo $form->textField($model, 'username' , array('class' => 'form-control placeholder-no-fix' , 'autocomplete' => 'off' , 'placeholder' => $model->getAttributeLabel('username'))); ?>
				</div>
				<?php echo $form->error($model , 'username'); ?>
			</div>
			<div class="form-group  <?php if($model->hasErrors('password')) echo 'has-error';?>">
				<label class="control-label visible-ie8 visible-ie9">Password</label>
				<div class="input-icon">
					<i class="fa fa-lock"></i>
					<?php echo $form->passwordField($model, 'password' , array( 'class' => 'form-control placeholder-no-fix' , 'autocomplete' => 'off' , 'placeholder' => $model->getAttributeLabel('password'))); ?>
				</div>
				<?php echo $form->error($model , 'password'); ?>
			</div>
			<div class="form-actions">
				<label class="checkbox">
<!-- 				<input type="checkbox" name="remember" value="1"/> 记住用户名 -->
				</label>
				<button type="submit" class="btn blue pull-right">
				登录 <i class="m-icon-swapright m-icon-white"></i>
				</button>            
			</div>

		<?php $this->endWidget(); ?>
		<!-- END LOGIN FORM -->        