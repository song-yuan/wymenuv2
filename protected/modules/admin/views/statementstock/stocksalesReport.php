    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>
    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/echarts.min.js');?>"></script>
    <!-- BEGIN PAGE -->
    <div class="page-content">
	<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               

	<div id="main2" style="width: 600px;height: 400px;display: none;" onMouseOver="this.style.background='#fff'" onmouseout="this.style.background=''"></div>
	
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','报表中心'),'url'=>$this->createUrl('statementstock/list' , array('companyId'=>$this->companyId,'type'=>1,))),array('word'=>yii::t('app','进销存日报 '),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statementstock/list' , array('companyId' => $this->companyId,'type'=>1,)))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','进销存日报');?></div>
					<div class="actions">
						<select id="text" class="btn yellow" >
						<option value="3" <?php if ($text==3){?> selected="selected" <?php }?> ><?php echo yii::t('app','日报');?></option>
						</select>
						
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
			<div style="overflow-x: auto;" class="portlet-body" class="portlet-body" >
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th><?php echo yii::t('app','原料名称');?></th>
								<th><?php echo yii::t('app','单位');?></th>
								<th><?php echo yii::t('app','数量');?></th>
								
							</tr>
						</thead>
						<tbody>
							<?php if( $sqlmodels) :?>
							<!--foreach-->
							<?php $a=1;?>
							<?php foreach ($sqlmodels as $model):?>
							<?php $laststock = 0;
								$allstoragestock = 0;
								$takingstock = 0;
								$salestock = 0;
							?>
								<tr class="odd gradeX">
								<td><?php echo $model['material_name'];?></td>
								<td><?php echo $model['unit_name'];?></td>
								<td><?php echo $model['material_num'];?></td>
							<?php $a++;?>
							<?php endforeach;?>	
							<!-- end foreach-->
							<?php else:?>
							<tr>
							<td colspan='15'>未查询到数据。</td>
							</tr>
							<?php endif;?>
						</tbody>
					</table>
					
				</div></div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
	
	</div>
	<!-- END PAGE CONTENT-->
</div>
<!-- END PAGE -->

<script>

		//var str=new array();						
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
  
	$('#btn_time_query').click(function time() {
		var begin_time = $('#begin_time').val();
		var end_time = $('#end_time').val();
		var text = $('#text').val();
		location.href="<?php echo $this->createUrl('statementstock/stocksalesReport' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time;    
	});
	$('#excel').click(function excel(){
		layer.msg('此项功能暂未开放！！',{icon: 5});return false;
		var begin_time = $('#begin_time').val();
		var end_time = $('#begin_time').val();
		var text = $('#text').val();
		var cid = $('#selectCategory').val();
		var codename = $('#codename').val();
		var matename = $('#matename').val();
		if(confirm('确认导出并且下载Excel文件吗？')){
			location.href="<?php echo $this->createUrl('statementstock/wxmemberExport' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text+"/sex/"+sex+"/sub/"+sub;
		}
		else{
			location.href="<?php echo $this->createUrl('statementstock/wxmemberReport' , array('companyId'=>$this->companyId ));?>/str/"+str+"/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text+"/sex/"+sex+"/sub/"+sub;
		}
	});
</script> 
