	<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-1.8.17.custom.css');?>
    <?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-timepicker-addon.css');?>
    <?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-1.7.1.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-1.8.17.custom.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-addon.js');?>
    <?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-zh-CN.js');?>
<style>
	.ui-datepicker{
		left: 700px !important;
		top: 100px !important;
	}
</style>
<div class="page-content">
	<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               

	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	
	<!-- BEGIN PAGE CONTENT-->
	
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','报表中心'),'url'=>$this->createUrl('statementmember/list' , array('companyId'=>$this->companyId,'type'=>3,))),array('word'=>yii::t('app','清除数据'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statementmember/list' , array('companyId' => $this->companyId,'type'=>3,)))));?>
	
	<div class="row">
		<div class="col-md-12">
			<div class="portlet purple box">
				<div class="portlet-title">
					<div class="caption"><i class=" fa fa-edit"></i><a href=""><span class="tab tab-active"><?php echo yii::t('app','清除测试数据');?></span></a></div>
					<div class="actions">
						
					</div>
				</div>
				
				<div style="min-height: 30px;" class="form-group">
	                <lable style="font-size: 16px;margin-top: 10px;text-align: right; " class="col-md-3 control-label">起始时间:</lable>
					<div style="margin-top: 5px;" class="col-md-4">
						<input id="begin_time" class="form-control ui_timepicker" value="<?php echo date('Y-m-d H:i:s',strtotime('-1 day'));?>">
					</div>
				</div>
				<div style="min-height: 30px;" class="form-group">
	                <lable style="font-size: 16px;margin-top: 10px;text-align: right; " class="col-md-3 control-label">结束时间:</lable>
					<div style="margin-top: 5px;" class="col-md-4">
						<input id="end_time" class="form-control ui_timepicker" value="<?php echo date('Y-m-d H:i:s',time());?>">
					</div>
				</div>
				
				<div class="form-actions fluid">
						<div class="col-md-offset-9 col-md-3">  
							<button type="button" class="btn green stockClear" id="stockClear" cleartype="1">清除</button>  
							<button type="button" class="btn green stockClear" id="stockClearall" cleartype="2">清除所有</button>                       
						</div>
					</div>
			</div>
		</div>
	</div>
	<!-- END PAGE CONTENT-->
	<script>
	$(function () {
		$(".ui_timepicker").datetimepicker({
	 		//showOn: "button",
	  		//buttonImage: "./css/images/icon_calendar.gif",
	   		//buttonImageOnly: true,
	    	showSecond: true,
	    	timeFormat: 'hh:mm:ss',
	    	stepHour: 1,
	   		stepMinute: 1,
	    	stepSecond: 1
		})
	});

	$(".stockClear").on("click",function(){
		var begin_time = $('#begin_time').val();
		var end_time = $('#end_time').val();
		var cleartype = $(this).attr('cleartype');
		layer.msg(begin_time);
	    return false;
	    $.ajax({
	        type:'POST',
			url:"<?php echo $this->createUrl('cfceshi/sqbprecreate',array('companyId'=>$this->companyId,));?>/pad_code/"+device_id,
			async: false,
			data: {device_id: device_id},
	        cache:false,
	        dataType:'json',
			success:function(msg){
	            //alert(msg.status);
	            if(msg.status=="success")
	            {            
		            
			        layer.msg("盘点成功！");
			          
		            location.reload();
	            }else{
		            layer.mag("11");
		            location.reload();
	            }
			},
	        error:function(){
				layer.msg("<?php echo yii::t('app','失败'); ?>"+"2");                                
			},
		});
	    
	});

	$("#stockWappay").on("click",function(){
		
	    var device_id = $("#device_id").val();
	    $.ajax({
	        type:'POST',
			url:"<?php echo $this->createUrl('cfceshi/sqbprecreate',array('companyId'=>$this->companyId,));?>/pad_code/"+device_id,
			async: false,
			data: {device_id: device_id},
	        //cache:false,
	        dataType:'json',
			success:function(msg){
	            //alert(msg.status);
	            if(msg.status=="success")
	            {            
		            
			        layer.msg("成功！");
			          
		            //location.reload();
	            }else{
		            layer.mag("11");
		            //location.reload();
	            }
			},
	        error:function(){
				layer.msg("<?php echo yii::t('app','失败'); ?>"+"2");                                
			},
		});
	    
	});

	</script>