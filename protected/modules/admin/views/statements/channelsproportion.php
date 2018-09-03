 <style>
 .layui-layer{
	 background-color: rgba(128,238,280,0.4);
 }
 </style>
    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/echarts.min.js');?>"></script>
	<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>
  	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/css/datepicker.css';?>" />

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
	<div id="main2" style="width: 700px;height:400px;display:none;" onMouseOver="this.style.background='#fff'" onmouseout="this.style.background=''"></div>
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','数据中心'),'subhead'=>yii::t('app','营业数据'),'breadcrumbs'=>array(array('word'=>yii::t('app','营业数据'),'url'=>$this->createUrl('statements/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','渠道占比列表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statements/list' , array('companyId' => $this->companyId,'type'=>0)))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','渠道占比报表');?></div>
					<div class="actions">
						<div class="btn-group">
							<?php $this->widget('application.modules.admin.components.widgets.CompanySelect2', array('companyType'=>$this->comptype,'companyId'=>$this->companyId,'selectCompanyId'=>$selectDpid));?>
						</div>
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
							<!--  <a href="#" class="btn green" ><i class="fa fa-pencial"></i><?php echo yii::t('app','打 印');?></a>		  -->
					    </div>		
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
				<div class="dataTables_wrapper form-inline">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								
								<th><?php echo yii::t('app','类型');?></th>
								<th><?php echo yii::t('app','单数');?></th>
								<th><?php echo yii::t('app','单均');?></th>
                                <th><?php echo yii::t('app','金额');?></th>
                                <th><?php echo yii::t('app','占比');?></th>                                                               
								
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<!--foreach-->
						<?php if($allpay) :?>
						<?php foreach ($models as $model):?>
								<tr class="odd gradeX">
								<td><?php switch($model['order_type']){
									case 0: echo yii::t('app','堂食');break;
									case 1: echo yii::t('app','微信堂食');break;
									case 2: echo yii::t('app','微信外卖');break;
									case 3: echo yii::t('app','微信预约');break;
									case 4: echo yii::t('app','后台外卖');break;
									case 6: echo yii::t('app','微信点单');break;
									case 7: echo yii::t('app','美团外卖');break;
									case 8: echo yii::t('app','饿了么');break;
									default: echo "其他";break;}?>
								</td>
								<td><?php echo $model['all_account']; ?></td>
								<td><?php echo sprintf("%.2f",$model['all_amount']/$model['all_account']);?></td>
								<td><?php echo $model['all_amount'];?></td>
								<td><?php echo sprintf("%.2f",$model['all_amount']*100/$allpay['all_payall']).'%';?></td>
								</tr>
						
						<?php endforeach;?>	
						<?php endif;?>
						<!-- end foreach-->
						<?php endif;?>
						</tbody>
					</table>
					</div>
					<?php if($pages->getItemCount()):?>
					<div class="row">
						<div class="col-md-5 col-sm-12">
							<div class="dataTables_info">
								<?php echo yii::t('app','共 ');?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?>  , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> ,  <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页');?>
							</div>
						</div>
						<div class="col-md-7 col-sm-12">
							<div class="dataTables_paginate paging_bootstrap">
							<?php $this->widget('CLinkPager', array(
								'pages' => $pages,
								'header'=>'',
								'firstPageLabel' => '<<',
								'lastPageLabel' => '>>',
								'firstPageCssClass' => '',
								'lastPageCssClass' => '',
								'maxButtonCount' => 8,
								'nextPageCssClass' => '',
								'previousPageCssClass' => '',
								'prevPageLabel' => '<',
								'nextPageLabel' => '>',
								'selectedPageCssClass' => 'active',
								'internalPageCssClass' => '',
								'hiddenPageCssClass' => 'disabled',
								'htmlOptions'=>array('class'=>'pagination pull-right')
							));
							?>
							</div>
						</div>
					</div>
					<?php endif;?>					
					</div>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
	
	</div>
	<!-- END PAGE CONTENT-->

</div>

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
		var myChart = echarts.init(document.getElementById('main2'));
		var option2 = {
			    title : {
			        text: '渠道占比图',
			        subtext: '',
			        x:'center'
			    },
			    tooltip : {
			        trigger: 'item',
			        formatter: "{a} <br/>{b} : {c} ({d}%)"
			    },
			    legend: {
			        orient: 'vertical',
			        left: 'left',
			        data: ['直接访问','邮件营销']
			    },
			    series : [
			        {
			            name: '访问来源',
			            type: 'pie',
			            radius : '55%',
			            center: ['50%', '60%'],
			            data:[
			                {value:31, name:'直接访问'},
			                {value:442, name:'邮件营销'}
			            ],
			            itemStyle: {
			                emphasis: {
			                    shadowBlur: 10,
			                    shadowOffsetX: 0,
			                    shadowColor: 'rgba(0, 0, 0, 0.5)'
			                }
			            }
			        }
			    ]
			};
			 //myChart.setOption(option2);

// 			 layer_zhexiantu=layer.open({
// 			     type: 1,
// 			     //shift:5,
// 			     shade: [0.1,'#fff'],
// 			     move:'#main2',
// 			     moveOut:true,
// 			     offset:['50px','50px'],
// 			     shade: false,
// 			     title: false, //不显示标题
// 			     area: ['auto', 'auto'],
// 			     content: $('#main2'),//$('#productInfo'), //捕获的元素
// 			     cancel: function(index){
// 			         layer.close(index);
// 			         layer_zhexiantu=0;
// //			                        this.content.show();
// //			                        layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
// 			     }
// 			 });
		


		
		function MM_over(mmObj) {
			var mSubObj = mmObj.getElementsByTagName("div")[0];
			mSubObj.style.display = "block";
			mSubObj.style.backgroundColor = "pink";
			mSubObj.style.opacity="100";
		}
		function MM_out(mmObj) {
			var mSubObj = mmObj.getElementsByTagName("div")[0];
			mSubObj.style.display = "none";
			
		}
		
		       
		   $('#btn_time_query').click(function() {  
			   var begin_time = $('#begin_time').val();
			   var end_time = $('#end_time').val();
			   var selectDpid = $('select[name="selectDpid"]').val();
			   location.href="<?php echo $this->createUrl('statements/channelsproportion' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+'/end_time/'+end_time+'/selectDpid/'+selectDpid;   
			  
	        });
		   $('#excel').click(function excel(){
	    	   var begin_time = $('#begin_time').val();
			   var end_time = $('#end_time').val();
			   var selectDpid = $('select[name="selectDpid"]').val();
		       if(confirm('确认导出并且下载Excel文件吗？')){
		    	   location.href="<?php echo $this->createUrl('statements/ChannelsproportionExport' , array('companyId'=>$this->companyId,'d'=>1 ));?>/begin_time/"+begin_time+"/end_time/"+end_time+'/selectDpid/'+selectDpid;
		       }
		   });
</script> 