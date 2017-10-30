		<style>
			.area {
				margin: 20px auto 0px auto;
			}
			
			.mui-input-group {
				margin-top: 10px;
			}
			
			.mui-input-group:first-child {
				margin-top: 20px;
			}
			
			.mui-input-group label {
				width: 22%;
			}
			
			.mui-input-row label~input,
			.mui-input-row label~select,
			.mui-input-row label~textarea {
				width: 78%;
			}
			
			.mui-checkbox input[type=checkbox],
			.mui-radio input[type=radio] {
				top: 6px;
			}
			
			.mui-content-padded {
				margin-top: 25px;
			}
			
			.mui-btn {
				padding: 10px;
			}
			
			.link-area {
				display: block;
				margin-top: 25px;
				text-align: center;
			}
			
			.spliter {
				color: #bbb;
				padding: 0px 8px;
			}
			
			.oauth-area {
				position: absolute;
				bottom: 20px;
				left: 0px;
				text-align: center;
				width: 100%;
				padding: 0px;
				margin: 0px;
			}
			
			.oauth-area .oauth-btn {
				display: inline-block;
				width: 50px;
				height: 50px;
				background-size: 30px 30px;
				background-position: center center;
				background-repeat: no-repeat;
				margin: 0px 20px;
				/*-webkit-filter: grayscale(100%); */
				border: solid 1px #ddd;
				border-radius: 25px;
			}
			
			.oauth-area .oauth-btn:active {
				border: solid 1px #aaa;
			}
			
			.oauth-area .oauth-btn.disabled {
				background-color: #ddd;
			}
			.error{color:red;}
		</style>


		<header class="mui-bar mui-bar-nav mui-hbar">
			<h1 class="mui-title" style="color:white;font-weight: 900;">商城登陆</h1>
		</header>
		<div class="mui-content" style="height: 100%;">
		<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'login-form',
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
				),
		)); ?>
		<div class="mui-input-group">

				<div class="mui-input-row <?php if($model->hasErrors('username')) echo 'has-error';?>">
					<label>账号</label>
					<?php echo $form->textField($model, 'username' , array('id'=>'account','class' => 'mui-input-clear mui-input' , 'autocomplete' => 'off' , 'placeholder' =>'请输入账号')); ?>
				</div>


				<div class="mui-input-row  <?php if($model->hasErrors('password')) echo 'has-error';?>">
					<label>密码</label>
					<?php echo $form->passwordField($model, 'password' , array('id'=>'password', 'class' => 'mui-input-clear mui-input' , 'autocomplete' => 'off' , 'placeholder' => '请输入密码')); ?>
				</div>
		</div>
			<div class="mui-content-padded">
				<button id='login' type="submit" class="mui-btn mui-btn-block mui-btn-primary">登录</button>
				<div class="link-area"><a id='forgetPassword' href="tel:13918912474" >忘记密码</a></div>
			</div>
			<div class="mui-content-padded oauth-area">

			</div>
		<?php $this->endWidget(); ?>
		</div>
		<script>
		jQuery(document).ready(function() {
			$('input').change(function(event) {
				$('.error').removeClass('.error');
			});
		});
		</script>