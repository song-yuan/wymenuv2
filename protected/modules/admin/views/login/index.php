		<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'login-form',
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					
				),
		)); ?>
			<h3 class="form-title"><?php echo yii::t('app','管理员系统')?></h3>
			<div class="alert alert-danger display-hide">
				<button class="close" data-close="alert"></button>
				<span><?php echo yii::t('app','输入用户名和密码');?></span>
			</div>
			<div class="form-group <?php if($model->hasErrors('username')) echo 'has-error';?>">
				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label class="control-label visible-ie8 visible-ie9"><?php echo $model->getAttributeLabel('username');?></label>
				<div class="input-icon">
					<i class="fa fa-user"></i>
					<?php echo $form->textField($model, 'username' , array('class' => 'form-control placeholder-no-fix' , 'autocomplete' => 'off' , 'placeholder' => $model->getAttributeLabel('username'))); ?>
				</div>
				<?php echo $form->error($model , 'username'); ?>
			</div>
			<div class="form-group  <?php if($model->hasErrors('password')) echo 'has-error';?>">
				<label class="control-label visible-ie8 visible-ie9"><?php echo $model->getAttributeLabel('password');?></label>
				<div class="input-icon">
					<i class="fa fa-lock"></i>
					<?php echo $form->passwordField($model, 'password' , array( 'class' => 'form-control placeholder-no-fix' , 'autocomplete' => 'off' , 'placeholder' => $model->getAttributeLabel('password'))); ?>
				</div>
				<?php echo $form->error($model , 'password'); ?>
			</div>
			<div class="form-actions">
				<label class="checkbox">
				<input type="checkbox" name="remember" value="1"/> <?php echo yii::t('app','记住用户名');?>
				</label>
				<button type="submit" class="btn blue pull-right">
				<?php echo yii::t('app','登录');?> <i class="m-icon-swapright m-icon-white"></i>
				</button>                             
			</div>
                        
			<div class="forget-password">
				<h4>忘记密码 ?</h4>
				<p>请联系我们帮忙找回</p>
			</div>
			<!--
			<div class="create-account">
				<p>
					Don't have an account yet ?&nbsp; 
					<a href="javascript:;" id="register-btn" >Create an account</a>
				</p>
			</div>
			 -->
		<?php $this->endWidget(); ?>

		<!-- END LOGIN FORM -->
                <script language="JavaScript" type="text/JavaScript">
                    $('#bindbutton').click(function(){
                        if (typeof Androidwymenuprinter == "undefined") {
                            alert("<?php echo yii::t('app','无法获取PAD设备信息，请在PAD中运行该程序！');?>");
                            return false;
                        }
                    });
                    $("#sqlitetest").click(function(){
                        var dbinfo="no";
                        if (typeof Androidwymenuprinter == "undefined") {
                            
                        }else{
                            dbinfo=Androidwymenuprinter.dbInfo();
                        }
                        alert(dbinfo);
                    });
                    $(document).on('keydown',function(e){
                    	var  keycode = e.which;
                    	if(keycode==13){
							$('form').submit();
                        }
                     });
                    
                    function IsPC()  
                    {  
                        var userAgentInfo = navigator.userAgent;  
                        var Agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod");  
                        var flag = true;  
                        for (var v = 0; v < Agents.length; v++) {  
                            if (userAgentInfo.indexOf(Agents[v]) > 0) { flag = false; break; }  
                        }  
                        return flag;  
                    } 
                    if(!IsPC()){
                    $('#LoginForm_username').focus( function(){
                		$(".logo").css("margin-top","0px");
                        $(".content").css("margin-top","-100px");
                    	});
                    $('#LoginForm_password').focus( function(){
                		$(".logo").css("margin-top","0px");
                        $(".content").css("margin-top","-100px");
                    	});
                    $('#LoginForm_password').blur( function(){
                		$(".logo").css("margin-top",null);
                        $(".content").css("margin-top",null);
                    	});
                    };
                </script>
                