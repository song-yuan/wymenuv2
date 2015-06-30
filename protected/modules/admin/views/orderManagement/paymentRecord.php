    <script type="text/javascript" src="../../../../../../../../../../plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="../../../../../../../../../../plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js"></script>
   
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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>'订单管理','subhead'=>'订单列表','breadcrumbs'=>array(array('word'=>'付款退款记录','url'=>''))));?>
	<div class="portlet box purple">
	    <div class="caption">
					<div class="btn-group">
							
					</div>
	    </div>
	</div>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">

	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i>付款退款记录表</div>
					<div class="actions">
                        
				<div class="btn-group">
				
						   <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
								<input type="text" class="form-control" name="begtime" id="begin_time" placeholder="起始时间" value="<?php echo $begin_time; ?>">  
								<span class="input-group-addon">~</span>
							    <input type="text" class="form-control" name="endtime" id="end_time" placeholder="终止时间"  value="<?php echo $end_time;?>">           
						  </div>  
			    </div>	
					
					<div class="btn-group">
							<button type="submit" id="btn_time_query" class="btn red" ><i class="fa fa-pencial"></i> 查 询</button>
							<a href="#" class="btn green" ><i class="fa fa-pencial"></i>打印</a>		
					</div>			
			    
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								
								<th>序号</th>
								<th>店铺</th>
								<th>订单号</th>
								<th>明细</th>
								<th>应收金额</th>
                                <th>实付金额</th>
                                <th>找零金额</th>
                                <th>备注</th>                                                                
								</tr>
						</thead>
						<tbody>
						<?php if( $models) :?>
						<!--foreach-->
						<?php $a=1;?>
						<?php foreach ($models as $model):?>

								<tr class="odd gradeX">
								<td><?php echo $a;?></td>
								<td><?php echo $model['company_name']; ?></td>
								<td><?php echo $model['lid']%10000;?></td>
								<td>
								 <div style='width:50px;overflow: hidden;height:18px;' title='<?php echo $this->getOrderDetails($model['lid']); ?>'  >
								 <?php echo $this->getOrderDetails($model['lid']); ?></div>
								
                                    </td>
								<td><?php echo $model['should_total'];?></td>
								<td><?php echo $model['reality_total'];?></td>
								<td><?php echo $model['reality_total']-$model['should_total'];?></td>
								<td></td>
							</tr>
					    <?php $a++;?>
						<?php endforeach;?>	
						<!-- end foreach-->
						<?php endif;?>
						</tbody>
					</table>
						<?php if($pages->getItemCount()):?>
						<div class="row">
							<div class="col-md-5 col-sm-12">
								<div class="dataTables_info">
									共 <?php echo $pages->getPageCount();?> 页  , <?php echo $pages->getItemCount();?> 条数据 , 当前是第 <?php echo $pages->getCurrentPage()+1;?> 页
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
			  // var cid = $(this).val();
			   location.href="<?php echo $this->createUrl('orderManagement/paymentRecord' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/page/"    
			  
	        });
</script> 