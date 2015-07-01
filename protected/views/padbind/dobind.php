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
							<div class="caption"><i class="fa fa-reorder"></i><?php echo yii::t('app','设置PAD'); ?></div>
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
                                                        <?php if(empty($model->lid)): ?>
								<div class="form-body">
									<div class="form-group  <?php if($model->hasErrors('dpid')) echo 'has-error';?>">
										<?php echo $form->label($model, 'dpid',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'dpid', array('0' => yii::t('app','-- 请选择 --')) + Helper::genCompanyOptions() ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('dpid')));?>
											<?php echo $form->error($model, 'dpid' )?>
										</div>
									</div>
									<div class="form-group <?php if($model->hasErrors('lid')) echo 'has-error';?>">
										<?php echo $form->label($model, 'lid',array('class' => 'col-md-3 control-label','id'=>'padId'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'lid', array('0'=>yii::t('app','-- 请选择 --')) ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('lid')));?>
											<?php echo $form->error($model, 'lid' )?>
										</div>
									</div>									
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
                                                                                    <button type="button" class="btn blue" id="btnPadSet"><?php echo yii::t('app','绑定'); ?></button>											                              
										</div>
									</div>
                                                                </div>
                                                        <?php else: ?>
                                                                <div class="form-body">
									<div class="form-group  <?php if($model->hasErrors('dpid')) echo 'has-error';?>">
										<?php echo $form->label($model, 'dpid',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'dpid', array('0' => yii::t('app','-- 请选择 --')) + Helper::genCompanyOptions() ,array('class' => 'form-control','disabled'=>'disabled','placeholder'=>$model->getAttributeLabel('dpid')));?>
											<?php echo $form->error($model, 'dpid' )?>
										</div>
									</div>
									<div class="form-group <?php if($model->hasErrors('lid')) echo 'has-error';?>">
										<?php echo $form->label($model, 'lid',array('class' => 'col-md-3 control-label','id'=>'padId'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'lid', array('0'=>yii::t('app','-- 请选择 --')) ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('lid')));?>
											<?php echo $form->error($model, 'lid' )?>
										</div>
									</div>									
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
                                                                                    <button type="button" class="btn blue" id="btnPadDisbind"><?php echo yii::t('app','解除绑定'); ?></button>											                              
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
                    var globaldomain="";
                    $(document).ready(function() {
                            var companyid = "<?php if(empty($model->dpid)) echo "0000000000"; else echo  $model->dpid;?>";
                            var padid = "<?php if(empty($model->lid)) echo "0000000000"; else echo  $model->lid;?>";
                            //alert('<?php echo $this->createUrl('padbind/domain');?>/companyid/'+companyid);
                            if(companyid=="0000000000")
                            {
                                return;
                            }
                            $.ajax({
                                    url:'<?php echo $this->createUrl('padbind/domain');?>/companyid/'+companyid,
                                    type:'GET',
                                    //async: false,
                                    //dataType:'json',
                                    success:function(result){
                                            //alert(result);
                                            globaldomain=result; 
                                           // alert(result+'padbind/getOnePad/companyid/'+companyid+'/padid/'+padid);
                                            $.ajax({
                                                    //async: false,
                                                    url:result+'padbind/getOnePad',
                                                    type: "GET", 
                                                    dataType: 'jsonp', 
                                                    jsonp: 'jsoncallback',
                                                    data: {"companyid":companyid,"padid":padid},
                                                    contentType: "application/json",
                                                    success:function(result){
                                                           // alert(result.data.length);
                                                            var str = '';                                                                                            
                                                            if(result.data.length){
                                                                    $.each(result.data,function(index,value){
                                                                        if(value.id==padid)
                                                                        {
                                                                            str = str + '<option value="'+value.id+' selected="selected">'+value.name+'</option>';
                                                                        }
                                                                    });                                                                                                                                                                                                       
                                                            }
                                                            $('#Pad_lid').html(str);
                                                            $('#Pad_lid').attr("disabled","disabled");
                                                    }
                                            });
                                    },
                                    error:function(){
                                        alert("<?php echo yii::t('app','获取服务地址错误'); ?>");
                                        return false;
                                    }
                            });
                            
                    });
                    
                    $('#Pad_dpid').change(function(){
                            var companyid = $(this).val();
                            var company_domain="";
                            $.ajax({
                                    url:'<?php echo $this->createUrl('padbind/domain');?>/companyid/'+companyid,
                                    type:'GET',
                                    //dataType:'json',
                                    success:function(result){
                                            //alert(result);
                                            globaldomain=result; 
                                            $.ajax({
                                                url:result+'padbind/getPadList',
                                                type: "GET", 
                                                dataType: 'jsonp', 
                                                jsonp: 'jsoncallback',
                                                data: {"companyid":companyid},
                                                contentType: "application/json",
                                                success:function(result){
                                                        //alert(result.data);
                                                        var str = '<option value="">'+"<?php echo yii::t('app','-- 请选择 --'); ?>"+'</option>';                                                                                            
                                                        if(result.data.length){
                                                                $.each(result.data,function(index,value){
                                                                        str = str + '<option value="'+value.id+'">'+value.name+'</option>';
                                                                });                                                                                                                                                                                                       
                                                        }
                                                        $('#Pad_lid').html(str); 
                                                }
                                        });
                                    },
                                    error:function(){
                                        alert("<?php echo yii::t('app','获取服务地址错误'); ?>");
                                        return false;
                                    }
                            });
                            
                            
                    });

                    $('#btnPadSet').click(function(){ 
                        var companyId=$('#Pad_dpid').val();
                        var padId=$('#Pad_lid').val();
                        if(companyId=="0000000000"||padId=="0000000000")
                        {
                            alert("<?php echo yii::t('app','请选择店铺和打印机！'); ?>");
                            return;
                        }
                        if (typeof Androidwymenuprinter == "undefined") {
                            alert("<?php echo yii::t('app','无法获取PAD设备信息，请在PAD中运行该程序！'); ?>");
                            return;
                        }
                        if(Androidwymenuprinter.padSet(companyId,padId))
                        {
                            $.ajax({
                                    url:globaldomain+'padbind/bind',
                                    type: "GET", 
                                    dataType: 'jsonp', 
                                    jsonp: 'jsoncallback',
                                    data: {"companyid":companyId,"padid":padId},
                                    contentType: "application/json",
                                    success:function(data){
                                        if(data.result)
                                        {
                                            alert("<?php echo yii::t('app','绑定成功，请重新打开应用程序！'); ?>");
                                            Androidwymenuprinter.appExitClear();
                                        }else{
                                            alert("<?php echo yii::t('app','绑定失败，请稍后再试！'); ?>"+"1");  
                                        }
                                    }
                            });                             
                            //local.href="";
                        }
                        else
                        {
                            alert("<?php echo yii::t('app','绑定失败，请稍后再试！'); ?>"+"2");                                                                        
                        }
                    });
                    
                    $('#btnPadDisbind').click(function(){ 
                        var companyId="<?php echo $model->dpid;?>";
                        var padId="<?php echo $model->lid;?>";
                        if (typeof Androidwymenuprinter == "undefined") {
                            alert("<?php echo yii::t('app','无法获取PAD设备信息，请在PAD中运行该程序！'); ?>");
                            return;
                        }
                        if(Androidwymenuprinter.padDisbind(companyId,padId))
                        {                                                                    
                            $.ajax({
                                    url:globaldomain+'padbind/disbind',
                                    type: "GET", 
                                    dataType: 'jsonp', 
                                    jsonp: 'jsoncallback',
                                    data: {"companyid":companyId,"padid":padId},
                                    contentType: "application/json",
                                    success:function(data){
                                        if(data.result)
                                        {
                                            alert("<?php echo yii::t('app','解除绑定成功，请重新打开应用程序！！'); ?>");
                                            Androidwymenuprinter.appExitClear();
                                        }else{
                                            alert("<?php echo yii::t('app','解除绑定失败，请稍后再试！'); ?>"+"1");    
                                        }
                                    }
                            });                           
                            //local.href="";//////
                        }
                        else
                        {
                            alert("<?php echo yii::t('app','解除绑定失败，请稍后再试！'); ?>"+"2");                                                                        
                        }
                    });                    
                </script>