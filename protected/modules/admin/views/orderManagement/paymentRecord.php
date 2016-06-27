    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>
   
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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','订单管理'),'subhead'=>yii::t('app','订单列表'),'breadcrumbs'=>array(array('word'=>yii::t('app','订单管理'),'url'=>$this->createUrl('orderManagement/list' , array('companyId'=>$this->companyId,))),array('word'=>yii::t('app','付款退款记录'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('orderManagement/list' , array('companyId' => $this->companyId,)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">

	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','付款退款记录表');?></div>
					<div class="actions">
                        <div class="btn-group">
							<!-- <input type="text" class="form-control" name="订单号" id="Did" placeholder="<?php echo yii::t('app','输入订单号进行查询');?>" value=""> -->
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
							<a href="#" class="btn green" ><i class="fa fa-pencial"></i><?php echo yii::t('app','打 印');?></a>		
					</div>			
			    
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								
								<th><?php echo yii::t('app','序号');?></th>
								<th><?php echo yii::t('app','店铺');?></th>
								<th><?php echo yii::t('app','订单号');?></th>
								<th><?php echo yii::t('app','账单号');?></th>
								<th><?php echo yii::t('app','明细');?></th>
								<th><?php echo yii::t('app','应收金额');?></th>
                                <th><?php echo yii::t('app','实付金额');?></th>
                                <th><?php echo yii::t('app','退款金额');?></th>
                                <th><?php echo yii::t('app','备注');?></th> 
                                <th></th> 
								</tr>
						</thead>
						<tbody>
						<?php if( $models) :?>
						<!--foreach-->
						<?php $a=1;?>
						<?php foreach ($models as $model):?>

								<tr class="odd gradeX">
								<td><?php echo ($pages->getCurrentPage())*10+$a;?></td>
								<td><?php echo $model->company->company_name; ?></td>
								<td><?php echo $model->order_id%10000;?></td>
								<td><?php echo $model->account_no;?></td>
								<td>
								 <div style='width:50px;overflow: hidden;height:18px;' title='<?php echo $this->getOrderDetails($model->order_id); ?>'  >
								 <?php echo $this->getOrderDetails($model->order_id); ?></div>
								
                                    </td>
                                <td><?php if(empty($model->order->should_total)) echo 0; else echo $model->order->should_total;?></td>
								<td><?php if ($model->pay_amount > 0) echo $model->pay_amount ;else echo '--/--';?></td>
								<td><?php if ($model->pay_amount < 0) echo -$model->pay_amount ;else echo '--/--';;?></td>
								<td><?php echo $model->update_at;?></td>
                                <td><?php if($model->pay_amount > 0):?><a href="<?php echo $this->createUrl('orderManagement/refund' , array('companyId' => $this->companyId));?>/orderID/<?php echo $model->order_id;?>/begin_time/<?php echo $begin_time;?>/end_time/<?php echo $end_time;?>" class="btn green"><i class="fa fa-pencil"></i> <?php echo yii::t('app','退款');?></a><?php endif;?></td>
							</tr>
					    <?php $a++;?>
						<?php endforeach;?>	
						<!-- end foreach-->
						<?php endif;?>
						</tbody>
					</table>
								<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											    <!--        <button type="submit" class="btn blue">确定</button>     -->   
											<a href="<?php echo $this->createUrl('orderManagement/notPay' , array('companyId' => $this->companyId));?>/begin_time/<?php echo $begin_time;?>/end_time/<?php echo $end_time;?>" class="btn default">返回今日订单</a>                              
										</div>
									</div>
						<?php if($pages->getItemCount()):?>
						<div class="row">
							<div class="col-md-5 col-sm-12">
								<div class="dataTables_info">
									<?php echo yii::t('app','共 ');?><?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?>  , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> , <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页');?>
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
<script type="text/javascript">
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
			   //var Did = $('#Did').var();
			  //var cid = $(this).val();
			   location.href="<?php echo $this->createUrl('orderManagement/paymentRecord' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/page/"    
			  
	        });

			
</script> 