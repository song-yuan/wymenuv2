		<link rel="stylesheet" type="text/css" href="metronic/plugins/bootstrap-fileupload/bootstrap-fileupload.css" />
		<script type="text/javascript" src="metronic/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script type="text/javascript" src="metronic/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js"></script>
		<link rel="stylesheet" type="text/css" href="metronic/plugins/jquery-multi-select/css/multi-select.css" />
		<script type="text/javascript" src="metronic/plugins/jquery-multi-select/js/jquery.multi-select.js"></script>
		<script type="text/javascript" src="metronic/plugins/jquery-multi-select/js/jquery.quicksearch.js"></script>   
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
			<!-- BEGIN STYLE CUSTOMIZER -->
			<?php $this->beginContent('//layouts/admin/styleCustomizer');?>
			<?php $this->endContent();?>
			<!-- END BEGIN STYLE CUSTOMIZER -->            
			<!-- BEGIN PAGE HEADER-->   
			<?php $this->widget('application.modules.brand.components.widgets.PageHeader', array('head'=>'营销中心','subhead'=>'添加商城抵用券','breadcrumbs'=>array(array('word'=>'营销中心','url'=>''),array('word'=>'商城抵用券','url'=>$this->createUrl('/brand/cashcard',array('cid'=>$this->companyId))),array('word'=>'添加商城抵用券','url'=>'')),'back'=>array('word'=>'返回','url'=>array('cashcard/index','cid'=>$this->companyId))));?>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<div class="tabbable tabbable-custom boxless">
						<div class="portlet box purple">
							<div class="portlet-title">
								<div class="caption"><i class="fa fa-money"></i>添加商城抵用券</div>
							</div>
							<div class="portlet-body form">
								<!-- BEGIN FORM-->
								<?php echo $this->renderPartial('_form', array('model'=>$model,'objects'=>$objects,'selectedShopIds'=>array())); ?>
								<!-- END FORM--> 
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->    
		</div>
		<!-- END PAGE -->  
		<script>
		jQuery(document).ready(function() {       
		   // initiate layout and plugins
		    App.init();
		    $('#shopId').multiSelect();
			$('#select-all').click(function(){
				$('#shopId').multiSelect('select_all');
				return false;
			});
			$('#deselect-all').click(function(){
				$('#shopId').multiSelect('deselect_all');
				return false;
			});
			if( jQuery("#Cashcard_pic").val()){
		           jQuery("#thumbnails_1").html("<img src='"+jQuery("#Cashcard_pic").val()+"?"+(new Date()).getTime()+"' />"); 
			}
			
			$('.cashcard-info').click(function(){
				var hide = $(this).parents('.form-group').find('.col-md-9');
				if(hide.is(":hidden")){
					hide.removeClass('hidden');
				}else{
					hide.addClass('hidden');
				}
			});
	        if (jQuery().datepicker) {
	            $('.date-picker').datepicker({
	            	format: 'yyyy-mm-dd',
	            	language: 'zh-CN',
	                rtl: App.isRTL(),
	                autoclose: true
	            });
	            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
	        }

		});
		function swfupload_callback1(name,path,oldname)  {
			jQuery("#Cashcard_pic").val(name);
			jQuery("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
		}
		</script>