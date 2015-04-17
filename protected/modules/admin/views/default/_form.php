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
									<div class="col-md-12">
											<div class="portlet-body">
												<table class="table table-striped table-bordered table-advance table-hover">
													<thead>
														<tr>
															<th><i class="fa fa-edit"></i>产品名称</th>
															<th class="hidden-xs">类别</th>
															<th>原价</th>
															<th>售价</th>
															<th class="hidden-xs">数量</th>
                                                                                                                        <th class="hidden-xs">重量</th>
                                                                                                                        <th class="hidden-xs">赠菜</th>
                                                                                                                        <th class="hidden-xs">厨打</th>
															<th class="hidden-xs">退菜</th>
                                                                                                                        <th class="hidden-xs">上菜</th>
                                                                                                                        <th class="hidden-xs">口味</th>
                                                                                                                        <th>总价<span id="total">(<?php echo number_format($productTotal,2);?>)</span></th>
                                                                                                                        <th ><a class="btn green add_btn" id="addproduct" data-toggle="modal"><i class="fa fa-plus"></i>&nbsp;&nbsp;加菜</a></th>
														</tr>
													</thead>
													<tbody>
													<?php foreach ($orderProducts as $orderProduct):?>
														<tr>
															<td><?php echo $orderProduct['product_name'];?></td>
                                                                                                                        <td class="hidden-xs"><?php if(empty($orderProduct['set_name'])) echo $orderProduct['category_name']; else echo $orderProduct['set_name'];?></td>
															<td><?php echo $orderProduct['original_price'];?></td>
															<td><?php echo $orderProduct['price'];?></td>
															<td><?php echo $orderProduct['amount'];?></td>
                                                                                                                        <td><?php echo $orderProduct['weight'];?></td>
                                                                                                                        <td><?php echo $orderProduct['is_giving']==1?'<span class="label label-sm label-warning">是</span>':'否';?></td>
															<td><?php echo $orderProduct['is_print']==1?'<span class="label label-sm label-info">是</span>':'否';?></td>
															<td><?php echo $orderProduct['is_retreat']==1?'<span class="label label-sm label-danger">是</span>':'否';?></td>
                                                                                                                        <td class="red"><?php switch($orderProduct['is_waiting']){case '0': {echo '不等叫'; break;} case '1': {echo '等叫'; break;} case '2': { echo '<span class="label label-sm label-success">已上菜</span>'; break;}};?></td>
															<td><?php echo $orderProduct['taste_memo'];?></td>
                                                                                                                        <td><?php echo $orderProduct['amount']*$orderProduct['price'];?></td>
                                                                                                                        <td class="center">
                                                                                                                            <div class="btn-group dropup">
                                                                                                                                    <a class="btn green" href="#" data-toggle="dropdown">
                                                                                                                                    操作
                                                                                                                                    <i class="fa fa-angle-up"></i>
                                                                                                                                    </a>
                                                                                                                                    <ul class="dropdown-menu pull-right"><!--已厨打不能编辑-->
                                                                                                                                            <li><a href="javascript:;" class='btn-edit' setid="<?php echo $orderProduct['set_id'];?>" lid="<?php echo $orderProduct['lid'];?>" >编辑（未下单）</a></li>
                                                                                                                                            <li><a href="javascript:;" class="btn-del"  setid="<?php echo $orderProduct['set_id'];?>" lid="<?php echo $orderProduct['lid'];?>" >删除（未下单）</a></li>
                                                                                                                                            <li><a href="javascript:;" class='btn-taste' setid="<?php echo $orderProduct['set_id'];?>" lid="<?php echo $orderProduct['lid'];?>" >口味设定</a></li>
                                                                                                                                            <li><a href="javascript:;" class='btn-retreat' setid="<?php echo $orderProduct['set_id'];?>"  lid="<?php echo $orderProduct['lid'];?>">退菜（已下单）</a></li>
                                                                                                                                            <li><a href="javascript:;" class='btn-weight' setid="<?php echo $orderProduct['set_id'];?>" lid="<?php echo $orderProduct['lid'];?>">称重（已下单）</a></li>
                                                                                                                                            <li><a href="javascript:;" class='btn-reprint' setid="<?php echo $orderProduct['set_id'];?>" lid="<?php echo $orderProduct['lid'];?>">重新厨打（已下单）</a></li>
                                                                                                                                    </ul>
                                                                                                                            </div>
                                                                                                                            <a href="<?php echo $this->createUrl('default/over' , array('companyId' => $this->companyId,'lid'=>$orderProduct['lid'],'orderId'=>$orderProduct['order_id'],'typeId'=>$typeId)); ?>" class="btn-over btn green add_btn" ><i class="fa fa-check"></i>勾挑</a>                                                                                                                                            
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
                                                            
                                                            
                                                            $('#addproduct').click(function(){
                                                                var $modalconfig = $('#portlet-config');
                                                                $modalconfig.find('.modal-content').load('<?php echo $this->createUrl('default/addProduct',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$model->lid));?>', '', function(){
                                                                            $modalconfig.modal();
                                                                          });                                
                                                            });

                                                            $('.btn-edit').click(function(){
                                                                   var id = $(this).attr('lid');
                                                                   var setid = $(this).attr('setid');
                                                                   var $modalconfig = $('#portlet-config');
                                                                   $modalconfig.find('.modal-content').load('<?php echo $this->createUrl('default/editProduct',array('companyId'=>$this->companyId));?>/id/'+id+'/setid/'+setid+'/orderId/'+"<?php echo $model->lid; ?>"+'/typeId/'+"<?php echo $typeId; ?>"
                                                                   ,'', function(){
                                                                     $modalconfig.modal();
                                                                   });
                                                            });

                                                            $('.btn-retreat').click(function(){
                                                                   var id = $(this).attr('lid');
                                                                   var $modal=$('#portlet-config');
                                                                   $modal.find('.modal-content').load('<?php echo $this->createUrl('default/retreatProduct',array('companyId'=>$this->companyId));?>/id/'+id+'/typeId/'+"<?php echo $typeId; ?>"
                                                                   ,'', function(){
                                                                     $modal.modal();
                                                                   });
                                                            });

                                                            $('.btn-taste').click(function(){
                                                                   var id = $(this).attr('lid');
                                                                   var setid = $(this).attr('setid');
                                                                   var $modal=$('#portlet-config');
                                                                   $modal.find('.modal-content').load('<?php echo $this->createUrl('default/tasteProduct',array('companyId'=>$this->companyId,'typeId'=>$typeId,'isall'=>'0','orderId'=>$model->lid));?>/id/'+id+'/setid/'+setid
                                                                   ,'', function(){
                                                                     $modal.modal();
                                                                   });
                                                            });

                                                            $('.btn-weight').click(function(){
                                                                   var id = $(this).attr('lid');
                                                                   var $modal=$('#portlet-config');
                                                                   $modal.find('.modal-content').load('<?php echo $this->createUrl('default/weightProduct',array('companyId'=>$this->companyId));?>/id/'+id+'/orderId/'+"<?php echo $model->lid; ?>"+'/typeId/'+"<?php echo $typeId; ?>"
                                                                   ,'', function(){
                                                                     $modal.modal();
                                                                   });
                                                            });

                                                            $('.btn-del').click(function(){
                                                               var that = $(this);
                                                               var id = $(this).attr('lid');
                                                               var setid = $(this).attr('setid');
                                                               var orderstatus="<?php echo $model->order_status;?>";
                                                               //alert(orderstatus);
                                                               if(orderstatus!='1')
                                                               {
                                                                   alert('不是未下单状态，不能删除！');
                                                                   return;
                                                               }
                                                               //alert(setid);
                                                               var tip='';
                                                               if(setid!='0000000000')
                                                               {
                                                                   tip='你确定要删除整个套餐吗？';
                                                               }else{
                                                                   tip='你确定要删除该单品吗？';
                                                               }
                                                               bootbox.confirm(tip, function(result) {
                                                               if(result){
                                                                       location.href="<?php echo $this->createUrl('default/delproduct',array('companyId'=>$this->companyId));?>/id/"+id+'/setid/'+setid+'/orderId/'+"<?php echo $model->lid; ?>"+'/typeId/'+"<?php echo $typeId; ?>";
                                                               }});
                                                           });                                                                  
                                                           
                                                           $('.reprint-btn').click(function(){
                                                                    $.get('<?php echo $this->createUrl('default/printProducts',array('companyId'=>$this->companyId,'id'=>$model->lid,'reprint'=>1));?>',function(data){
                                                                            if(data.status) {
                                                                                    alert('操作成功');
                                                                            } else {
                                                                                    alert(data.msg);
                                                                            }
                                                                    },'json');
                                                            });
                        
							</script>