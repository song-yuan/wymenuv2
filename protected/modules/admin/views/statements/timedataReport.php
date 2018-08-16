 <style>
 .layui-layer{
	 background-color: rgba(128,238,280,0.4);
 }
 </style>
    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>
    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/echarts.min.js');?>"></script>
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
	<div id="main2" style="width: 600px;height:400px;" onMouseOver="this.style.background='#fff'" onmouseout="this.style.background=''"></div>
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','数据中心'),'subhead'=>yii::t('app','营业数据'),'breadcrumbs'=>array(array('word'=>yii::t('app','营业数据'),'url'=>$this->createUrl('statements/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','时段报表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statements/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','时段报表');?></div>
				<div class="actions">
				  <div class="btn-group">
				  		<div class="btn-group">
							<?php $this->widget('application.modules.admin.components.widgets.CompanySelect2', array('companyType'=>$this->comptype,'companyId'=>$this->companyId,'selectCompanyId'=>$selectDpid));?>
						</div>
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
								<th><?php echo yii::t('app','序号');?></th>
                                <th><?php echo yii::t('app','时段');?></th>
                                <th><?php echo yii::t('app','单数');?></th>
                                <th><?php echo yii::t('app','营业额');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if( $models) :?>
							<?php foreach($models as $k=>$model):?>
							<tr class="odd gradeX">
								<td><?php echo $k+1;?></td>
								<td><?php echo $model['h_all'];?></td>
								<td><?php echo $model['all_account']?$model['all_account']:0;?></td>
								<td><?php echo $model['pay_amount']?$model['pay_amount']:0;?></td>
							</tr>
							<?php endforeach;?>
						<?php else:?>
						<tr>
							<td colspan="4">没有查询到数据</td>
						</tr>
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
//app.title = '多 Y 轴示例';
var myChart = echarts.init(document.getElementById('main2'));
var colors = ['#d14a61','#5793f3',  '#675bba'];
var timeprice = <?php echo $timeprice;?>;
var timesum = <?php echo $timesum;?>;
var maxp = <?php echo $maxp;?>;
var maxs = <?php echo $maxs;?>;
var option2 = {
    color: colors,
    backgroundColor:'rgba(255,255, 255, 0.1)',
    title: {
        text: '    时段报表'
    },
    tooltip: {
        trigger: 'axis'
    },
    grid: {
        right: '15%',
        left: '15%',
    },
    toolbox: {
        feature: {
            dataView: {show: true, readOnly: true},
            restore: {show: true},
            saveAsImage: {show: true}
        }
    
    },
    legend: {
        data:['销售额','单数']
    },
    xAxis: [
        {
			name: '       (时段)',
			nameLocation:'end',
			//boundaryGap: ['20%', '20%'],
            type: 'category',
            axisTick: {
                alignWithLabel: true
            },
            data: ['00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23']
        }
    ],
    yAxis: [
        {
            type: 'value',
            name: '销售额',
            min: 0,
            max: maxp,
            position: 'left',
            axisLine: {
                lineStyle: {
                    color: colors[0]
                }
            },
            axisLabel: {
                formatter: '{value} 元'
            }
        },
        
        {
            type: 'value',
            name: '单数',
            min: 0,
            max: maxs,
            position: 'right',
            axisLine: {
                lineStyle: {
                    color: colors[1]
                }
            },
            axisLabel: {
                formatter: '{value} 单'
            }
        }
    ],
    series: [

             {
                 name:'销售额',
                 type:'line',
                 yAxisIndex: 0,
                 data:timeprice
             },
	        {
	            name:'单数',
	            type:'bar',
	            yAxisIndex: 1,
	            data:timesum
	        }
        
    ]
};
 myChart.setOption(option2);

 layer_zhexiantu=layer.open({
     type: 1,
     //shift:5,
     shade: [0.1,'#fff'],
     move:'#main2',
     moveOut:true,
     offset:['20%','20%'],
     shade: false,
     title: false, //不显示标题
     area: ['auto', 'auto'],
     content: $('#main2'),//$('#productInfo'), //捕获的元素
     cancel: function(index){
         layer.close(index);
         layer_zhexiantu=0;
     }
 });


 

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
		   location.href="<?php echo $this->createUrl('statements/timedataReport' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+'/selectDpid/'+selectDpid;
		  
        });
		$('#excel').click(function excel(){
	    	   var begin_time = $('#begin_time').val();
			   var end_time = $('#end_time').val();
			   var selectDpid = $('select[name="selectDpid"]').val();
		       if(confirm('确认导出并且下载Excel文件吗？')){
		    	   location.href="<?php echo $this->createUrl('statements/timedataReportExport' , array('companyId'=>$this->companyId,'d'=>1));?>/begin_time/"+begin_time+"/end_time/"+end_time+'/selectDpid/'+selectDpid;
		       }
		      
		 });

</script> 
