	<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-1.8.17.custom.css');?>
    <?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-timepicker-addon.css');?>
    <?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-1.7.1.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-1.8.17.custom.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-addon.js');?>
    <?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-zh-CN.js');?>


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
			<!-- END BEGIN STYLE CUSTOMIZER -->            
			<!-- BEGIN PAGE HEADER-->   
			<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array( 'breadcrumbs'=>array(array('word'=>yii::t('app','营销活动'),'url'=>$this->createUrl('entityMarket/list' , array('companyId' => $this->companyId,'type'=>0,))),array('word'=>yii::t('app','普通优惠'),'url'=>$this->createUrl('normalpromotion/index' , array('companyId' => $this->companyId,))),array('word'=>yii::t('app','修改普通优惠'),'url'=>'')),'back'=>array('word'=>'返回','url'=>$this->createUrl('normalpromotion/index' , array('companyId' => $this->companyId,)))));?>
		<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-reorder"></i><?php echo yii::t('app','修改普通优惠活动');?></div>
							<div class="tools">
								<a href="javascript:;" class="collapse"></a>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- BEGIN FORM-->
							<?php echo $this->renderPartial('_form', array('model'=>$model,'brdulvs'=>$brdulvs,'userlvs'=>$userlvs,'source'=>$source)); ?>
							<!-- END FORM--> 
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->    
		</div>
		<!-- END PAGE -->
		
	<script type="text/javascript">
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
   
// 		jQuery(document).ready(function(){
// 		    if (jQuery().datepicker) {
// 	            $('.date-picker').datepicker({
// 	            	format: 'yyyy-mm-dd',
// 	            	language: 'zh-CN',
// 	                rtl: App.isRTL(),
// 	                autoclose: true
// 	            });
// 	            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
	            
//            }
// 		});

    	
// 		jQuery(document).ready(function() {       
// 		   // initiate layout and plugins
// 		    App.init();
// 	        if (jQuery().datepicker) {
// 	            $('.date-picker').datepicker({
// 	            	format: 'yyyy-mm-dd',
// 	            	language: 'zh-CN',
// 	                rtl: App.isRTL(),
// 	                autoclose: true
// 	            });
// 	            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
// 	        }

// 		});
	
		
// 		jQuery(document).ready(function() {       
// 		   // initiate layout and plugins
// 		   App.init();
// 		   FormComponents.init();
// 		});

	    </script>

	 
		