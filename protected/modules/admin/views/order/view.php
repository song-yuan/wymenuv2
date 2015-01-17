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
			<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>'订单管理','subhead'=>'修改订单','breadcrumbs'=>array(array('word'=>'订单管理','url'=>$this->createUrl('order/index' , array('companyId'=>$this->companyId))),array('word'=>'修改订单','url'=>''))));?>
			
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-reorder"></i>订单详情</div>
							<div class="tools">
								<a href="javascript:;" class="collapse"></a>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- BEGIN FORM-->
							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'order-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
								<div class="form-body">
									<div class="form-group">
									<label class="col-md-3 control-label">订单商品</label>
									<div class="col-md-9">
											<div class="portlet-body">
												<table class="table table-striped table-bordered table-advance table-hover">
													<thead>
														<tr>
															<th><i class="fa fa-briefcase"></i>产品名称</th>
															<th class="hidden-xs">类别</th>
															<th>原价</th>
															<th>售价</th>
															<th class="hidden-xs">数量</th>
															<th>总价<span id="total">(<?php echo $productTotal;?>)</span></th>
														</tr>
													</thead>
													<tbody>
													<?php foreach ($orderProducts as $orderProduct):?>
														<tr>
															<td><a href="<?php echo $this->createUrl('product/update' , array('id'=>$orderProduct['product_id'],'companyId'=>$this->companyId));?>"><?php echo $orderProduct['product_name'];?></a></td>
															<td class="hidden-xs"><?php echo $orderProduct['category_name'];?></td>
															<td><?php echo $orderProduct['origin_price'];?></td>
															<td><?php echo $orderProduct['price'];?></td>
															<td><?php echo $orderProduct['amount'];?></td>
															<td><?php echo $orderProduct['amount']*$orderProduct['price'];?></td>
														</tr>
													<?php endforeach;?>
													</tbody>
												</table>
											</div>
											<div><?php echo $total['remark'] ;?></div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">开台时间</label>
										<label class="col-md-4 control-label" style="text-align:left;"><?php echo date('Y-m-d H:i:s' , $model->create_time);?></label>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">付款时间</label>
										<label class="col-md-4 control-label" style="text-align:left;" ><?php echo $model->pay_time ? date('Y-m-d H:i:s' , $model->pay_time) : '' ;?></label>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">应支付（元）</label>
										<label class="col-md-4 control-label" style="text-align: left;"><?php echo $total['total'];?></label>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">实际支付（元）</label>
										<label class="col-md-4 control-label" style="text-align: left;"><?php echo $model['reality_total'];?></label>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">支付方式</label>
										<label class="col-md-4 control-label" style="text-align: left;"><?php echo $paymentMethod;?></label>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">备注</label>
										<label class="col-md-4 control-label" style="text-align: left;"><?php echo $model['remark'];?></label>
									</div>
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<a href="javascript:;"  id="reprint-btn" class="btn blue">重新打印清单</a>
											<a href="<?php echo $this->createUrl('order/historyList' , array('companyId' => $model->company_id));?>" class="btn default">返回</a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>
							<script>
								$('#reprint-btn').click(function(){
									$.get('<?php echo $this->createUrl('order/printList',array('companyId'=>$this->companyId,'id'=>$model->order_id,'reprint'=>1));?>',function(data){
										alert('操作成功');
									},'json');
								});
							</script>
						<!-- END FORM--> 
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->    
		</div>
		<!-- END PAGE -->  