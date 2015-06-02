	
	<script type="text/javascript" src="../../../../../../plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script type="text/javascript" src="../../../../../../plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js"></script>
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
			<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','产品管理'),'subhead'=>yii::t('app','添加优惠活动'),'breadcrumbs'=>array(array('word'=>yii::t('app','产品管理'),'url'=>$this->createUrl('productSales/index' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','添加优惠活动'),'url'=>''))));?>
			
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-reorder"></i><?php echo yii::t('app','添加优惠活动');?></div>
							<div class="tools">
								<a href="javascript:;" class="collapse"></a>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- BEGIN FORM-->
							<?php echo $this->renderPartial('_form', array('model'=>$model,'product'=>$product)); ?>
							<!-- END FORM--> 
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->    
		</div>
		<!-- END PAGE -->  
		<script>
		jQuery(document).ready(function(){
		    if(parseInt($('input:radio[name="ProductDiscount[is_discount]"]:checked').val())) {
				$('.discount').find('label').html("<?php echo yii::t('app','折扣比例');?>");
				$('.discount').find('.input-group-addon').html("<?php echo yii::t('app','折');?>");
				$('.discount').find('.input-group').after('<span class="help-block">70<?php echo yii::t('app','即七折');?></span>');
			} else {
				$('.discount').find('label').html("<?php echo yii::t('app','优惠价格');?>");
				$('.discount').find('.input-group-addon').html('元');
				$('.discount').find('.input-group').after("<?php echo yii::t('app','<span class="help-block">实际价格=原价-优惠价格</span>');?>");
			}
			$('input:radio[name="ProductDiscount[is_discount]"]').change(function(){
				if(parseInt($(this).val())) {
					$('.discount').find('label').html('折扣比例');
					$('.discount').find('.input-group-addon').html('折');
					$('.discount').find('.help-block').remove();
					$('.discount').find('.input-group').after("<?php echo yii::t('app','<span class="help-block">70即七折</span>');?>");
				} else {
				  $('.discount').find('label').html('优惠价格');
				  $('.discount').find('.input-group-addon').html('元');
				  $('.discount').find('.help-block').remove();
				  $('.discount').find('.input-group').after("<?php echo yii::t('app','<span class="help-block">实际价格=原价-优惠价格</span>');?>");
				}
		    })
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
		</script>