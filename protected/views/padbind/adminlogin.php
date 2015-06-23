<!-- BEGIN GLOBAL MANDATORY STYLES -->
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/plugins/font-awesome/css/font-awesome.min.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/plugins/bootstrap/css/bootstrap.min.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/plugins/uniform/css/uniform.default.css');?>
	<!-- END GLOBAL MANDATORY STYLES -->
	<!-- BEGIN PAGE LEVEL STYLES --> 
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/plugins/select2/select2_metro.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/plugins/jquery-treegrid/css/jquery.treegrid.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/plugins/bootstrap-switch/static/stylesheets/bootstrap-switch-metro.css');?>
		
	<!-- END PAGE LEVEL SCRIPTS -->
	<!-- BEGIN THEME STYLES --> 
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/style-metronic.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/style.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/style-responsive.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/themes/default.css');?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/custom.css');?>
	
        <?php Yii::app()->clientScript->registerCoreScript('jquery');?>
	<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/jquery-migrate-1.2.1.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/bootstrap/js/bootstrap.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/jquery-slimscroll/jquery.slimscroll.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/jquery.blockui.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/jquery.cookie.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/uniform/jquery.uniform.min.js');?>
	<!-- END CORE PLUGINS -->
	<!-- BEGIN PAGE LEVEL PLUGINS -->
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/select2/select2.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/data-tables/jquery.dataTables.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/data-tables/DT_bootstrap.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/jquery-treegrid/js/jquery.treegrid.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/bootbox/bootbox.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/bootstrap-switch/static/js/bootstrap-switch.min.js');?>
	<!-- END PAGE LEVEL PLUGINS -->
	<!-- BEGIN PAGE LEVEL SCRIPTS -->
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/scripts/app.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/scripts/table-managed.js');?>
	
<!-- BEGIN PAGE -->  
		<!-- BEGIN PAGE HEADER-->   
			
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-reorder"></i><?php echo yii::t('app','绑定PAD登录界面'); ?></div>
							<div class="tools">
								<a href="javascript:;" class="collapse"></a>
							</div>
						</div>
						<div class="portlet-body form">								<div class="form-body">
									<div class="form-group ">
                                                                            <form id="login-form" action="/wymenuv2/padbind/login" method="post">			
                                                                                <h3 class="form-title"><?php if($model->username!="") echo yii::t('app','用户名或密码错误'); else echo yii::t('app','输入管理员用户名和密码'); ?></h3>
                                                                                <div class="alert alert-danger display-hide">
                                                                                        <button class="close" data-close="alert"></button>
                                                                                        <span><?php if($model->username!="") echo yii::t('app','用户名或密码错误'); else echo yii::t('app','输入管理员用户名和密码'); ?></span>
                                                                                </div>
                                                                                <div class="form-group ">
                                                                                        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                                                                                        <label class="control-label visible-ie8 visible-ie9">Username</label>
                                                                                        <div class="input-icon">
                                                                                                <i class="fa fa-user"></i>
                                                                                                <input class="form-control placeholder-no-fix" autocomplete="off" placeholder=<?php echo yii::t('app','用户名'); ?> name="LoginForm[username]" id="LoginForm_username" type="text">				
                                                                                        </div>
                                                                                </div>
                                                                                <div class="form-group  ">
                                                                                        <label class="control-label visible-ie8 visible-ie9">Password</label>
                                                                                        <div class="input-icon">
                                                                                                <i class="fa fa-lock"></i>
                                                                                                <input class="form-control placeholder-no-fix" autocomplete="off" placeholder=<?php echo yii::t('app','密码'); ?> name="LoginForm[password]" id="LoginForm_password" type="password">				
                                                                                        </div>
                                                                                </div>
                                                                                <div class="form-actions">
                                                                                    <input class="hide" name="LoginForm[pad_info]" id="pad_info_id" type="text" value="00000000000000000000">
                                                                                        <button type="button" id="btnAdminLogin" class="btn blue pull-right">
                                                                                           <?php echo yii::t('app','登录'); ?><i class="m-icon-swapright m-icon-white"></i>
                                                                                        </button>            
                                                                                </div>
                                                                        </form>
                                                                        </div>									
                                                                </div>
                                                    </div>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->    
		</div>
		<!-- END PAGE -->  
                <script language="JavaScript" type="text/JavaScript">
                    $('#btnAdminLogin').click(function(){
                        if (typeof Androidwymenuprinter == "undefined") {
                            alert("<?php echo yii::t('app','无法获取PAD设备信息，请在PAD中运行该程序！'); ?>");
                            return;
                        } 
                        var padInfo=Androidwymenuprinter.getPadInfo();
                        $('#pad_info_id').val(padInfo);
                        //alert($('#pad_info_id').val());
                        //$('#pad_info_id').val("00000000000000000000");
                        $('#login-form').submit();
                    });
                </script>