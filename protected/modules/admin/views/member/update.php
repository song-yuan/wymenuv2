	<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/mobiscroll_002.css');?>
    <?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/mobiscroll.css');?>
    <?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/mobiscroll_003.css');?>
    <?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/mobiscroll_002.js');?>
	<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/mobiscroll_004.js');?>
	<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/mobiscroll.js');?>
    <?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/mobiscroll_003.js');?>
    <?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/mobiscroll_005.js');?>
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
			<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','会员管理'),'subhead'=>yii::t('app','修改会员'),'breadcrumbs'=>array(array('word'=>yii::t('app','会员管理'),'url'=>$this->createUrl('member/index' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','修改会员'),'url'=>''))));?>
			
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-reorder"></i><?php echo yii::t('app','修改会员');?></div>
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

    
    $(function() {
            //var cHeight = Zepto('#blank').offset().height;
            var currYear = (new Date()).getFullYear();
            var opt = {};
            opt.date = {
                preset : 'birthday'
            };
            opt.datetime = {
                preset : 'birthday'
            };
            opt.time = {
                preset : 'birthday'
            };
            opt.default = {
                theme : 'android-ics light', //皮肤样式
                display : 'modal', //显示方式
                mode : 'scroller', //日期选择模式
                dateFormat : 'mm.dd',
                //width : cHeight / 1.2,
                //height : cHeight / 1.6,
                width:100,
                height:40,
                circular:true,
                showScrollArrows:true,
                lang : 'zh',
                showNow : true,
                nowText : "今天",
                startYear : currYear , //开始年份
                endYear : currYear  //结束年份
            };

            var optDateTime = $.extend(opt['datetime'], opt['default']);
            // var optTime = $.extend(opt['time'], opt['default']);
            $("#MemberCard_birthday").mobiscroll(optDateTime).date(optDateTime);

        });	
	</script>