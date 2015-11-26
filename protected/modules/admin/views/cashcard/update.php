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
			<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array( 'head'=>yii::t('app','营销管理'),'subhead'=>'整体设置','breadcrumbs'=>array(array('word'=>'营销管理','url'=>''),array('word'=>'营销品设置','url'=>''),array('word'=>'整体设置','url'=>''))));?>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
		<div class="row">
		<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'cupon-form',
				'action' => $this->createUrl('cupon/index' , array('companyId' => $this->companyId)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?>
		<div class="col-md-12">
		<div class="tabbable tabbable-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('cashcard/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab">整体设置</a></li>
				<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('normalpromotion/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab">普通优惠</a></li>
				<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('privatepromotion/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab">特价优惠</a></li>
				<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('cupon/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab">代金券</a></li>
			</ul>
			<div class="tab-content">		
					  <div class="col-md-12">
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption"><i class="fa fa-reorder"></i><?php echo yii::t('app','活动整体设置');?></div>
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
				</div>
			</div>
			<!-- END PAGE CONTENT-->    
		</div>
		<!-- END PAGE -->  
		<script>
// 		jQuery(document).ready(function() {       
// 		   // initiate layout and plugins
// 		    App.init();
		    
// 		    $('#shopId').multiSelect();
// 			$('#select-all').click(function(){
// 				$('#shopId').multiSelect('select_all');
// 				return false;
// 			});
// 			$('#deselect-all').click(function(){
// 				$('#shopId').multiSelect('deselect_all');
// 				return false;
// 			});
// 			jQuery(document).ready(function(){
//				<php if(empty($selectedShopIds)):?>
// 			    $('#select-all').click();
//			    <php endif;?>
// 				if( jQuery("#Cashcard_pic").val()){
// 			           jQuery("#thumbnails_1").html("<img src='"+jQuery("#Cashcard_pic").val()+"?"+(new Date()).getTime()+"' />"); 
// 				}
// 			});
// 	        if (jQuery().datepicker) {
// 	            $('.date-picker').datepicker({
// 	            	format: 'yyyy-mm-dd',
// 	            	language: 'zh-CN',
// 	                rtl: App.isRTL(),
// 	                autoclose: true
// 	            });
// 	            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
// 	        }
// 			$('.cashcard-info').click(function(){
// 				var hide = $(this).parents('.form-group').find('.col-md-9');
// 				if(hide.is(":hidden")){
// 					hide.removeClass('hidden');
// 				}else{
// 					hide.addClass('hidden');
// 				}
// 			});
// 		});
// 		function swfupload_callback1(name,path,oldname)  {
// 			jQuery("#Cashcard_pic").val(name);
// 			jQuery("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
// 		}
		</script>