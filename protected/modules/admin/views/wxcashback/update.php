		<?php 
			$baseUrl = Yii::app()->baseUrl;
		?>
	<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-1.8.17.custom.css');?>
    <?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-timepicker-addon.css');?>
    <?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-1.7.1.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-1.8.17.custom.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-addon.js');?>
    <?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-zh-CN.js');?>
		<!-- <link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/plugins/bootstrap-fileupload/bootstrap-fileupload.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/wxcard.css" />
		<script type="text/javascript" src="<?php echo $baseUrl;?>/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script type="text/javascript" src="<?php echo $baseUrl;?>/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/plugins/jquery-multi-select/css/multi-select.css" />
		<script type="text/javascript" src="<?php echo $baseUrl;?>/plugins/jquery-multi-select/js/jquery.multi-select.js"></script>
		<script type="text/javascript" src="<?php echo $baseUrl;?>/plugins/jquery-multi-select/js/jquery.quicksearch.js"></script>   
		 -->
		<link href="<?php echo $baseUrl;?>/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
		<script src="<?php echo $baseUrl;?>/plugins/bootstrap-modal/js/bootstrap-modalmanager.js" type="text/javascript" ></script>
		<script src="<?php echo $baseUrl;?>/plugins/bootstrap-modal/js/bootstrap-modal.js" type="text/javascript" ></script>
			<!-- BEGIN PAGE -->  
		<div class="page-content">
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
			<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title">Modal title</h4>
						</div>
						<div class="modal-body">
							Widget settings form goes here
						</div>
						<div class="modal-footer">
							<button type="button" class="btn blue">Save changes</button>
							<button type="button" class="btn default" data-dismiss="modal">Close</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			<!-- /.modal -->
			<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<!-- BEGIN PAGE HEADER-->   
			<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','微信会员设置'),'subhead'=>yii::t('app','修改消费返现比例'),'breadcrumbs'=>array(array('word'=>yii::t('app','微信会员设置'),'url'=>$this->createUrl('wxcashback/index' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','修改消费返现比例'),'url'=>''))));?>
			
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-reorder"></i>修改消费返现比例</div>
							<div class="tools">
								<a href="javascript:;" class="collapse"></a>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- BEGIN FORM-->
							<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
							<!-- END FORM--> 
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->    
		</div>
		<!-- END PAGE -->  
		
			
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
// 		function formatDate(now) { 
// 			var year=now.getFullYear(); 
// 			var month=now.getMonth()+1; 
// 			var date=now.getDate(); 
// 			return year+"."+month+"."+date; 
// 		} 
		function dateStr() { 
			var date = new Date();
			var timeStamp = date.getTime();
			var fixedBeginTerm = $('select[name="fixed_begin_term"]').val();
			var fixedTerm = $('select[name="fixed_term"]').val();
			
			var beginTime = parseInt(timeStamp) + parseInt(fixedBeginTerm)*24*3600*1000;
			var endTime = beginTime + parseInt(fixedTerm)*24*3600*1000;
			
			var beginDate = new Date(parseInt(beginTime));
			var endDate = new Date(parseInt(endTime));
			
			var timeStr = '有效期 : '+ formatDate(beginDate) +' - '+ formatDate(endDate);
			$('#js_validtime_preview').html(timeStr); 
		} 
		jQuery(document).ready(function() {       
		   // initiate layout and plugins
			jQuery(document).ready(function(){
				if( jQuery("#Gift_gift_pic_large").val()){
			           jQuery("#thumbnails_1").html("<img src='"+jQuery("#Gift_gift_pic_large").val()+"?"+(new Date()).getTime()+"' />"); 
				}
			});
// 	        if (jQuery().datepicker) {
// 	            $('.date-picker').datepicker({
// 	            	format: 'yyyy.mm.dd',
// 	            	language: 'zh-CN',
// 	                rtl: App.isRTL(),
// 	                autoclose: true
// 	            });
// 	            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
// 	        }
// 	        window.onload = function(){$('.edit_oper').eq(0).trigger('click');};
// 	        $('.edit_oper').click(function(){
// 	        	$('.js_edit_content').css('display','none');
// 	        	var actionId = $(this).attr('data-actionid');
// 	        	if(parseInt(actionId)==9){
// 	        		$('.js_edit_content').eq(0).css('display','block');
// 	        		$('#js_edit_area').css('margin-top','0px');
// 	        	}else if(parseInt(actionId)==10){
// 	        		$('.js_edit_content').eq(1).css('display','block');
// 	        		$('#js_edit_area').css('margin-top','126px');
// 	        	}else if(parseInt(actionId)==11){
// 	        		$('.js_edit_content').eq(2).css('display','block');
// 	        		$('#js_edit_area').css('margin-top','199px');
// 	        	}else if(parseInt(actionId)==12){
// 	        		$('.js_edit_content').eq(3).css('display','block');
// 	        		$('#js_edit_area').css('margin-top','244px');
// 	        	}
// 	        });
			
			//有效期
// 			$('input[name="begin_timestamp"]').change(function(){
// 				var beginTime = $('input[name="begin_timestamp"]').val();
// 				var endTime = $('input[name="end_timestamp"]').val();
// 				var timeStr = '有效期 : '+ beginTime +' - '+ endTime;
// 				$('#js_validtime_preview').html(timeStr);
// 			});
// 			$('input[name="end_timestamp"]').change(function(){
// 				var beginTime = $('input[name="begin_timestamp"]').val();
// 				var endTime = $('input[name="end_timestamp"]').val();
// 				var timeStr = '有效期 : '+ beginTime +' - '+ endTime;
// 				$('#js_validtime_preview').html(timeStr);
// 			});
			
// 			$('select[name="fixed_begin_term"]').change(function(){
// 				dateStr();
// 			});
			
// 			$('select[name="fixed_term"]').change(function(){
// 				dateStr();
// 			});
			
			
			
			$('input[name="date_info_type"]').change(function(){
				if(parseInt($(this).val())==1){
					$('select[name="fixed_begin_term"]').attr('disabled','disabled');
					$('select[name="fixed_term"]').attr('disabled','disabled');
					$('input[name="begin_timestamp"]').removeAttr('disabled');
					$('input[name="end_timestamp"]').removeAttr('disabled');
					var beginTime = $('input[name="begin_timestamp"]').val();
					var endTime = $('input[name="end_timestamp"]').val();
					var timeStr = '有效期 : '+ beginTime +' - '+ endTime;
					$('#js_validtime_preview').html(timeStr);
				}else if(parseInt($(this).val())==2){
					$('input[name="begin_timestamp"]').attr('disabled','disabled');
					$('input[name="end_timestamp"]').attr('disabled','disabled');
					$('select[name="fixed_begin_term"]').removeAttr('disabled');
					$('select[name="fixed_term"]').removeAttr('disabled');
					dateStr();
				}
			});
			$('form').submit(function(){
				
				var type = $('input[name="type"]').val();
				var brandName = $('input[name="brand_name"]').val();
				
			});	
		});
		function swfupload_callback1(name,path,oldname)  {
			jQuery("#Gift_gift_pic_large").val(name);
			jQuery("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
		}
		</script> 