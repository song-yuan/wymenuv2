	<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-1.8.17.custom.css');?>
    <?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-timepicker-addon.css');?>
    <?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-1.7.1.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-1.8.17.custom.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-addon.js');?>
    <?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-zh-CN.js');?>
  

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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','数据中心'),'subhead'=>yii::t('app','营业数据'),'breadcrumbs'=>array(array('word'=>yii::t('app','营业数据'),'url'=>$this->createUrl('statements/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','账单详情查询'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statements/list' , array('companyId' => $this->companyId,'type'=>0)))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','账单详情报表');?></div>
					<div class="actions">
					<div class="btn-group">
							 <input type="text" class="form-control" name="订单号" id="Did" placeholder="" value="<?php echo yii::t('app','店铺：');?><?php echo Helper::getCompanyName($this->companyId);?>"  onfocus=this.blur()> 
						</div>
                        <div class="btn-group">
				
						   <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
								<input type="text" class="form-control ui_timepicker" name="begtime" id="begin_time" placeholder="<?php echo yii::t('app','起始时间');?>" value="<?php echo $begin_time; ?>">  
								<span class="input-group-addon">~</span>
							    <input type="text" class="form-control ui_timepicker" name="endtime" id="end_time" placeholder="<?php echo yii::t('app','终止时间');?>"  value="<?php echo $end_time;?>">           
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
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								
								<th><?php echo yii::t('app','账单号');?></th>
								<th><?php echo yii::t('app','下单时间');?></th>
								<th><?php echo yii::t('app','账单更新时间');?></th>
								
								<th><?php echo yii::t('app','座位');?></th>
                                <th><?php echo yii::t('app','人数');?></th>
                                <!-- <th><?php echo yii::t('app','状态');?></th> -->
                                <th><?php echo yii::t('app','原价');?></th>
                                <th><?php echo yii::t('app','优惠');?></th>                                                                
                                <th><?php echo yii::t('app','实收');?></th>
								
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<!--foreach-->
					
						<?php foreach ($models as $model):?>
								<tr class="odd gradeX">
								<td><?php echo $model->account_no; ?></td>
								<td><?php echo $model->create_at;?></td>
								<td><?php echo $model->update_at;?></td>
								
								<td><?php if($model->is_temp=='1') echo yii::t('app','临时坐').$model->site_id%1000; else echo $this->getSiteName($model->lid);?></td>
								<td><?php echo $model->all_number;?></td>
								<!-- <td><?php switch($model->order_status) {case 1: echo yii::t('app','未下单'); break; case 2: echo yii::t('app','已下单未支付') ; break; case 3: echo yii::t('app','已支付'); break; case 4: echo yii::t('app','已结单'); break; case 5: echo yii::t('app','被并台'); break; case 6: echo yii::t('app','被换台'); break; case 7: echo yii::t('app','被撤台'); break; case 8: echo yii::t('app','日结'); break;default :echo '';}?></td>
								 -->
								<td><?php echo sprintf("%.2f",$model->should_total);?></td>
								<td><?php echo sprintf("%.2f",$model->reality_total-$model->should_total);?></td>
								<td><?php echo sprintf("%.2f",$model->reality_total);?></td>
								<!-- <td><php echo sprintf("%.2f",$this->getOriginalMoney($model->account_no));?></td>
								<td><php echo  sprintf("%.2f",$this->getOriginalMoney($model->account_no)-$this->getAccountMoney($model->account_no));?></td>
								<td><php echo sprintf("%.2f",$this->getAccountMoney($model->account_no));?></td> -->
								</tr>
						
						<?php endforeach;?>	
						<!-- end foreach-->
						<?php endif;?>
						</tbody>
					</table>
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
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
	
	</div>
	<!-- END PAGE CONTENT-->

</div>

<script>
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
			  // alert($('#begin_time').val()); 
			  // alert($('#end_time').val()); 
			  // alert(111);
			   var begin_time = $('#begin_time').val();
			   var end_time = $('#end_time').val();
			   //var Did = $('#Did').var();
			  //var cid = $(this).val();
			   location.href="<?php echo $this->createUrl('statements/orderdetail' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/page/"    
			  
	        });
		   $('#excel').click(function excel(){
//				  var obj=$('#checkedCNid');
//				    alert(obj);
//				    var str=new Array();
//						obj.each(function(){
//							alert(1);
//							if($(this).attr("checked")=="checked")
//							{
//								alert(str);
//								str += $(this).val()+","
								
//							}								
//						});
//					str = str.substr(0,str.length-1);//除去最后一个“，”
				   
		    	   var begin_time = $('#begin_time').val();
				   var end_time = $('#end_time').val();
				   var text = $('#text').val();
				  
				   //alert(str);
			       if(confirm('确认导出并且下载Excel文件吗？')){
							//alert("<?php echo "sorry,您目前暂无权限！！！";?>")
							//return false;
			    	   location.href="<?php echo $this->createUrl('statements/orderdetailExport' , array('companyId'=>$this->companyId,'d'=>1 ));?>/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text;
			       }
			       else{
			    	  // location.href="<?php echo $this->createUrl('statements/export' , array('companyId'=>$this->companyId ));?>/str/"+str+"/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text;
			       }
			      
			   });
</script> 