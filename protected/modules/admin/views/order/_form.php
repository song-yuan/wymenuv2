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
									<label class="col-md-3 control-label"><?php echo yii::t('app','订单商品');?></label>
									<div class="col-md-9">
											<div class="portlet-body">
												<table class="table table-striped table-bordered table-advance table-hover">
													<thead>
														<tr>
															<th><i class="fa fa-briefcase"></i><?php echo yii::t('app','产品名称');?></th>
															<th class="hidden-xs"><?php echo yii::t('app','类别');?></th>
															<th><?php echo yii::t('app','原价');?></th>
															<th><?php echo yii::t('app','售价');?></th>
															<th class="hidden-xs"><?php echo yii::t('app','数量');?></th>
															<th><?php echo yii::t('app','总价');?><span id="total">(<?php echo $productTotal;?>)</span></th>
															<th>&nbsp;</th>
														</tr>
													</thead>
													<tbody>
													<?php foreach ($orderProducts as $orderProduct):?>
														<tr>
															<td><a href="<?php echo $this->createUrl('product/update' , array('id'=>$orderProduct['product_id'],'companyId'=>$this->companyId));?>"><?php echo $orderProduct['product_name'];?></a></td>
															<td class="hidden-xs"><?php echo $orderProduct['category_name'];?></td>
															<td><?php echo $orderProduct['original_price'];?></td>
															<td><?php echo $orderProduct['price'];?></td>
															<td><?php echo $orderProduct['amount'];?></td>
															<td><?php echo $orderProduct['amount']*$orderProduct['price'];?></td>
                                                                                                                        <td class="center">
                                                                                                                            <div class="btn-group">
                                                                                                                                    <a class="btn green" href="#" data-toggle="dropdown">
                                                                                                                                    <?php echo yii::t('app','操作');?>
                                                                                                                                    <i class="fa fa-angle-down"></i>
                                                                                                                                    </a>
                                                                                                                                    <ul class="dropdown-menu pull-right">
                                                                                                                                            <li><a href="javascript:;" class="del-btn"  item="<?php echo $orderProduct['item_id'];?>"><?php echo yii::t('app','勾挑');?></a></li>
                                                                                                                                            <li><a href="javascript:;" class="del-btn"  item="<?php echo $orderProduct['item_id'];?>"><?php echo yii::t('app','优惠');?></a></li>
                                                                                                                                            <li><a href="javascript:;" class="del-btn"  item="<?php echo $orderProduct['item_id'];?>"><?php echo yii::t('app','退菜');?></a></li>
                                                                                                                                            <li><a href="javascript:;" class="del-btn"  item="<?php echo $orderProduct['item_id'];?>"><?php echo yii::t('app','称重');?></a></li>
                                                                                                                                            <li><a href="javascript:;" class="del-btn"  item="<?php echo $orderProduct['item_id'];?>"><?php echo yii::t('app','重新厨打');?></a></li>
                                                                                                                                    </ul>
                                                                                                                            </div>
                                                                                                                        </td>
														</tr>
													<?php endforeach;?>
													</tbody>
												</table>
											</div>
											<div><?php echo $total['remark'] ;?></div>
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'create_at',array('class' => 'col-md-3 control-label'));?>
										<label class="col-md-4 control-label" style="text-align:left;"><?php echo date('Y-m-d H:i:s' , $model->create_at);?></label>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'pay_time',array('class' => 'col-md-3 control-label'));?>
										<label class="col-md-4 control-label" style="text-align:left;" ><?php echo $model->pay_time ? date('Y-m-d H:i:s' , $model->pay_time) : '' ;?></label>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label"><?php echo yii::t('app','应支付（元）');?></label>
										<label class="col-md-4 control-label" style="text-align: left;"><?php echo $total['total'];?></label>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'reality_total',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'reality_total' ,array('value'=>$total['total'],'class' => 'form-control','placeholder'=>$model->getAttributeLabel('reality_total')));?>
											<?php echo $form->error($model, 'reality_total' )?>
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'payment_method_id',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'payment_method_id' ,$paymentMethods ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('payment_method_id')));?>
											<?php echo $form->error($model, 'payment_method_id' )?>
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'remark',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textArea($model, 'remark' ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('remark')));?>
											<?php echo $form->error($model, 'remark' )?>
										</div>
									</div>
									<?php echo $form->hiddenField($model , 'order_status' , array('value'=>1));?>
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<a href="javascript:;"  id="submit-btn" class="btn blue"><?php echo yii::t('app','结单');?></a>
											<a href="javascript:;"  id="reprint-btn" class="btn blue"><?php echo yii::t('app','厨打');?></a>
											<a href="<?php echo $this->createUrl('order/index' , array('companyId' => $model->company_id));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>
							<script>
								$('.del-btn').click(function(){
									var that = $(this);
									var id = $(this).attr('item');
							        bootbox.confirm("<?php echo yii::t('app','你确定要删除该商品吗？');?>", function(result) {
							            if(result){
											$.get('<?php echo $this->createUrl('order/deleteProduct',array('companyId'=>$this->companyId));?>&id='+id,function(data){
												if(data.status){
													alert("<?php echo yii::t('app','删除成功');?>");
													if(data.amount) {
														that.parent().prev().html(data.amount*data.price);
														that.parent().prev().prev().html(data.amount);
														$('#total').html(data.total);
														$('#Order_reality_total').val(data.total);
													} else {
														that.parents('tr').remove();
													}
												} else {
													alert("<?php echo yii::t('app','删除失败');?>");
												}
											},'json');
							            }});
								});
								$('#submit-btn').click(function(){
									 bootbox.confirm("<?php echo yii::t('app','你确定要结单吗？');?>", function(result) {
										if(result){
											$('#order-form').submit();
										}
									 });
								});
								$('#print-btn').click(function(){
									$.get('<?php echo $this->createUrl('order/printList',array('companyId'=>$this->companyId,'id'=>$model->order_id));?>',function(data){
										if(data.status) {
											alert("<?php echo yii::t('app','操作成功');?>");
										} else {
											alert(data.msg);
										}
									},'json');
								});
								$('#reprint-btn').click(function(){
									$.get('<?php echo $this->createUrl('order/printProducts',array('companyId'=>$this->companyId,'id'=>$model->order_id,'reprint'=>1));?>',function(data){
										if(data.status) {
											alert("<?php echo yii::t('app','操作成功');?>");
										} else {
											alert(data.msg);
										}
									},'json');
								});
							</script>