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
			<!-- /.modal -->
			<!-- END BEGIN STYLE CUSTOMIZER -->            
			<!-- BEGIN PAGE HEADER-->
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array( 'head'=>yii::t('app','基础信息'),'subhead'=>'同步设定','breadcrumbs'=>array(array('word'=>'基础信息','url'=>''),array('word'=>'基础数据同步设定','url'=>''),array('word'=>'同步设定','url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('company/list' , array('companyId' => $this->companyId,)))));?>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
		<div class="row">
		
		<div class="col-md-12">
		<div class="tabbable tabbable-custom">
			<ul class="nav nav-tabs">
				<li class="<?php if($type == "manul") echo "active";?>"><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('synchronous/index',array('companyId'=>$this->companyId ,'type'=>"manul"));?>'" data-toggle="tab"><?php echo yii::t('app','手动同步');?></a></li>
				<li class="<?php if($type == "force") echo "active";?>"><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('synchronous/index',array('companyId'=>$this->companyId ,'type'=>"force"));?>'" data-toggle="tab"><?php echo yii::t('app','强制同步');?></a></li>
			</ul>
		

			<div class="tab-content">
				<div class="col-md-12">
				
				
					<?php if($type=="manul") {?>
					<div class="portlet purple box">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-reorder"></i><?php echo yii::t('app','手动同步');?></div>
						</div>
						<div class="portlet-body form">
						<div class="form-body">		
						<div class="form-group">
							
						</div>
							<div class="form-actions fluid">
								<div class="col-md-offset-3 col-md-9">
                                                                    同步前的准备条件：<br>
                                                                    1：从云端将相应的公司信息copy到本地，公司信息编号部分云端本地，从1开始递增<br>
                                                                    2：请先确认本地建立了company_<?php echo $this->companyId;?>文件夹，并且是可读写；<br>                                                                    
									<button type="button" id="manulsync" class="btn blue"><?php echo yii::t('app','手动同步');?></button>
								</div>
							</div>
						</div>
						</div>
						</div>
					<?php }elseif ($type=="force"){?>
					<div class="portlet purple box">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-reorder"></i><?php echo yii::t('app','强制同步设定');?></div>
						</div>
						<div class="portlet-body form">
						<div class="form-body">		
							<div class="form-group">
								
								<label class="control-label col-md-3 "><?php echo yii::t('app','同步起始时间');?></label>
								
							<div class="col-md-4">
												
								<div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
								<input type="text" class="form-control ui_timepicker " name="begtime" id="begin_time" placeholder="<?php echo yii::t('app','起始时间');?>" value="">  
								</div>
												
							</div>
						</div>
						<div style="height:10px;">
							
						</div>
							<div class="form-actions fluid">
								<div class="col-md-offset-3 col-md-9">
									<button type="button" id="forcesync" class="btn blue"><?php echo yii::t('app','强制同步');?></button>
								</div>
							</div>
						</div>
						</div>
						</div>
					<?php }?>
				
				</div>
			</div>
		</div>
		</div>
		</div>
</div>	<!-- END EXAMPLE TABLE PORTLET-->
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
        
        $("#manulsync").on("click",function(){
            location.href="<?php echo $this->createUrl('synchronous/index',array('companyId'=>$this->companyId ,'type'=>"manul",'action'=>'1'));?>";
        })
        
        $("#forcesync").on("click",function(){
            var dt=$("#begin_time").val();
            location.href="<?php echo $this->createUrl('synchronous/index',array('companyId'=>$this->companyId ,'type'=>"force",'action'=>'1'));?>/dt/"+dt;
        })

	</script>			


