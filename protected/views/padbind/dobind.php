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
							<div class="caption"><i class="fa fa-reorder"></i>设置PAD</div>
							<div class="tools">
								<a href="javascript:;" class="collapse"></a>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- BEGIN FORM-->
                                                    <?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'pad-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
                                                        <?php if(empty($model)): ?>
								<div class="form-body">
									<div class="form-group  <?php if($model->hasErrors('dpid')) echo 'has-error';?>">
										<?php echo $form->label($model, 'dpid',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'dpid', array('0' => '-- 请选择 --') + Helper::genCompanyOptions() ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('dpid')));?>
											<?php echo $form->error($model, 'dpid' )?>
										</div>
									</div>
									<div class="form-group <?php if($model->hasErrors('lid')) echo 'has-error';?>">
										<?php echo $form->label($model, 'lid',array('class' => 'col-md-3 control-label','id'=>'padId'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'lid', array('0'=>'-- 请选择 --') ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('lid')));?>
											<?php echo $form->error($model, 'lid' )?>
										</div>
									</div>									
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
                                                                                    <button type="button" class="btn blue" id="btnPadSet">绑 定</button>											                              
										</div>
									</div>
                                                                </div>
                                                        <?php else: ?>
                                                                <div class="form-body">
									<div class="form-group  <?php if($model->hasErrors('dpid')) echo 'has-error';?>">
										<?php echo $form->label($model, 'dpid',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'dpid', array('0' => '-- 请选择 --') + Helper::genCompanyOptions() ,array('class' => 'form-control','readonly'=>'readonly','placeholder'=>$model->getAttributeLabel('dpid')));?>
											<?php echo $form->error($model, 'dpid' )?>
										</div>
									</div>
									<div class="form-group <?php if($model->hasErrors('lid')) echo 'has-error';?>">
										<?php echo $form->label($model, 'lid',array('class' => 'col-md-3 control-label','id'=>'padId'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'lid', array('0'=>'-- 请选择 --') ,array('class' => 'form-control','disabled'=>'disabled','placeholder'=>$model->getAttributeLabel('lid')));?>
											<?php echo $form->error($model, 'lid' )?>
										</div>
									</div>									
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
                                                                                    <button type="button" class="btn blue" id="btnPadDisbind">解 除 绑 定</button>											                              
										</div>
									</div>
                                                                </div>
                                                        <?php endif;?>
							<?php $this->endWidget(); ?>                                                               
							<!-- END FORM--> 
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->    
		
		<!-- END PAGE -->  
                <script language="JavaScript" type="text/JavaScript">
                    $('#Pad_dpid').change(function(){
                            var companyid = $(this).val();
                            $.ajax({
                                    url:'<?php echo $this->createUrl('padbind/getPadList');?>/companyid/'+companyid,
                                    type:'GET',
                                    dataType:'json',
                                    success:function(result){
                                            //alert(result.data);
                                            var str = '<option value="">--请选择--</option>';                                                                                            
                                            if(result.data.length){
                                                    $.each(result.data,function(index,value){
                                                            str = str + '<option value="'+value.id+'">'+value.name+'</option>';
                                                    });                                                                                                                                                                                                       
                                            }
                                            $('#Pad_lid').html(str); 
                                    }
                            });
                    });

                    $('#btnPadSet').click(function(){ 
                        var companyId=$('#Pad_dpid').val();
                        var padId=$('#Pad_lid').val();
                        if(companyId=="0000000000"||padId=="0000000000")
                        {
                            alert("请选择店铺和打印机！");
                            return;
                        }
                        if (typeof Androidwymenuprinter == "undefined") {
                            alert("无法获取PAD设备信息，请在PAD中运行该程序！");
                            return;
                        }
                        if(Androidwymenuprinter.padSet(companyId,padId))
                        {
                            alert("绑定成功，请重新打开应用程序！！");
                            //local.href="";
                        }
                        else
                        {
                            alert("绑定失败，请稍后再试！");                                                                        
                        }
                    });
                    
                    $('#btnPadDisbind').click(function(){ 
                        var companyId="<?php echo $model->dpid;?>";
                        var padId="<?php echo $model->lid;?>";
                        if (typeof Androidwymenuprinter == "undefined") {
                            alert("无法获取PAD设备信息，请在PAD中运行该程序！");
                            return;
                        }
                        if(Androidwymenuprinter.padDisbind(companyId,padId))
                        {                                                                    
                            alert("解除绑定成功，请重新打开应用程序！！");
                            //local.href="";
                        }
                        else
                        {
                            alert("解除绑定失败，请稍后再试！");                                                                        
                        }
                    });                    
                </script>