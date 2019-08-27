    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>
     <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/css/datepicker.css';?>" />
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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','数据中心'),'subhead'=>yii::t('app','营业数据'),'breadcrumbs'=>array(array('word'=>yii::t('app','营业数据'),'url'=>$this->createUrl('statements/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','代金券汇总'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statements/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','代金券发放情况报表');?></div>
				<div class="actions">
				  	<div class="btn-group">
					   <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
							<input type="text" class="form-control" name="begtime" id="begin_time" placeholder="<?php echo yii::t('app','起始时间');?>" value="<?php echo $begin_time; ?>">  
							<span class="input-group-addon">~</span>
						    <input type="text" class="form-control" name="endtime" id="end_time" placeholder="<?php echo yii::t('app','终止时间');?>"  value="<?php echo $end_time;?>">           
					  </div>  
			    	</div>	
					
					<div class="btn-group">
							<button type="submit" id="btn_time_query" class="btn red" ><i class="fa fa-pencial"></i><?php echo yii::t('app','查 询');?></button>
							<button type="submit" id="excel"  class="btn green" ><i class="fa fa-pencial"></i><?php echo yii::t('app','导出Excel');?></button>				
					</div>			
			    </div>
			 </div> 
			
				<div class="portlet-body" id="table-manage">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th><?php echo yii::t('app','姓名');?></th>
								<th><?php echo yii::t('app','手机号');?></th>
								<th><?php echo yii::t('app','领取时间');?></th>
								<th><?php echo yii::t('app','券名');?></th>
								<th><?php echo yii::t('app','编号');?></th>
								<th><?php echo yii::t('app','创建时间');?></th>
								<th><?php echo yii::t('app','是否使用');?></th>
								<th><?php echo yii::t('app','有效期限');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models):?>
							<?php $key=0; foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><?php echo $model['user_name'];?></td>
								<td><?php echo $model['mobile_num'];?></td>
								<td><?php echo $model['cucreate_at'];?></td>
								<td><?php echo $model['cupon_title'];?></td>
								<td><?php echo $model['sole_code'];?></td>
								<td><?php echo $model['create_at'];?></td>
								<td><?php if($model['is_used']==2){ echo '已使用';} else{ echo '未使用';}?></td>
								<td><?php echo $model['valid_day'].'~'.$model['close_day'];?></td>
							</tr>
							<?php endforeach;?>
						<?php else:?>
						<tr><td colspan="8">未查询到数据</td></tr>
						<?php endif;?>
						</tbody>
					</table>
					</div>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
	
	</div>
	<!-- END PAGE CONTENT-->
</div>
<!-- END PAGE -->

<script>
		jQuery(document).ready(function(){
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
		 
		       
	   $('#btn_time_query').click(function() {  
		   var begin_time = $('#begin_time').val();
		   var end_time = $('#end_time').val();
		   var selectDpid = $('select[name="selectDpid"]').val();
		   location.href="<?php echo $this->createUrl('statements/cuponReport' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+'/selectDpid/'+selectDpid;
		  
        });
		  $('#excel').click(function excel(){

	    	   var begin_time = $('#begin_time').val();
			   var end_time = $('#end_time').val();
			   var selectDpid = $('select[name="selectDpid"]').val();
		       if(confirm('确认导出并且下载Excel文件吗？')){
		    	   location.href="<?php echo $this->createUrl('statements/cuponReport' , array('companyId'=>$this->companyId,'d'=>1));?>/begin_time/"+begin_time+"/end_time/"+end_time+'/selectDpid/'+selectDpid;
		       }
		      
		   });

</script> 
