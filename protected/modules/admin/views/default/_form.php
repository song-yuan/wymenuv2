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
									<label class="col-md-2 control-label">订单商品</label>
									<div class="col-md-10">
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
															<th><a class="btn btn-xs green add_btn" id="addproduct" data-toggle="modal"><i class="fa fa-plus"></i>&nbsp;&nbsp;加菜</a></th>
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
                                                                                                                                    操作
                                                                                                                                    <i class="fa fa-angle-down"></i>
                                                                                                                                    </a>
                                                                                                                                    <ul class="dropdown-menu pull-right">
                                                                                                                                            <li><a href="javascript:;" class="del-btn"  item="<?php echo $orderProduct['lid'];?>">勾挑</a></li>
                                                                                                                                            <li><a href="javascript:;" class="del-btn"  item="<?php echo $orderProduct['lid'];?>">退菜</a></li>
                                                                                                                                            <li><a href="javascript:;" class="del-btn"  item="<?php echo $orderProduct['lid'];?>">称重</a></li>
                                                                                                                                            <li><a href="javascript:;" class="del-btn"  item="<?php echo $orderProduct['lid'];?>">重新厨打</a></li>
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
									
							<?php $this->endWidget(); ?>
							<script>
                                                                                                                           
								
							</script>