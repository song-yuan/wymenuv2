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
															<th><i class="fa fa-edit"></i><?php echo yii::t('app','产品名称');?></th>
															<th class="hidden-xs"><?php echo yii::t('app','类别');?></th>
															<th><?php echo yii::t('app','原价');?></th>
															<th><?php echo yii::t('app','售价');?></th>
															<th class="hidden-xs"><?php echo yii::t('app','数量');?></th>
                                                                                                                        <th class="hidden-xs"><?php echo yii::t('app','重量');?></th>
                                                                                                                        <th class="hidden-xs"><?php echo yii::t('app','赠菜');?></th>
                                                                                                                        <th class="hidden-xs"><?php echo yii::t('app','厨打');?></th>
															<th class="hidden-xs"><?php echo yii::t('app','退菜');?></th>
                                                                                                                        <th class="hidden-xs"><?php echo yii::t('app','上菜');?></th>
                                                                                                                        <th class="hidden-xs"><?php echo yii::t('app','口味');?></th>
                                                                                                                        <th><?php echo yii::t('app','应付合计');?><br><span id="total"><?php echo number_format($productTotal,2);?></span></th>
                                                                                                                        <th >
                                                                                                                            <!--<a class="btn green add_btn" id="btn-addproduct" data-toggle="modal"><i class="fa fa-plus"></i>&nbsp;<?php echo yii::t('app','单品');?></a>-->
                                                                                                                            <a class="btn green add_btn" id="btn-addset" data-toggle="modal"><i class="fa fa-plus"></i>&nbsp;<?php echo yii::t('app','选菜');?></a>
                                                                                                                        </th>
														</tr>
													</thead>
													<tbody>
													<?php foreach ($orderProducts as $orderProduct):?>
														<tr>
															<td><?php echo $orderProduct['product_name'];?></td>
                                                                                                                        <td class="hidden-xs"><?php if(!empty($orderProduct['set_name'])) echo $orderProduct['set_name']; elseif($orderProduct['main_id']!="0000000000" && $orderProduct['main_id']!=$orderProduct['product_id']) echo yii::t('app','加菜'); else echo $orderProduct['category_name'];?></td>
															<td><?php echo $orderProduct['original_price'];?></td>
															<td><?php echo number_format($orderProduct['price'],2);?></td>
															<td><?php echo $orderProduct['amount'];?></td>
                                                                                                                        <td><?php echo $orderProduct['weight'];?></td>
                                                                                                                        <td><?php echo $orderProduct['is_giving']==1?'<span class="label label-sm label-warning">'.yii::t('app','是').'</span>':yii::t('app','否');?></td>
															<td><?php echo $orderProduct['is_print']==1?'<span class="label label-sm label-info">'.yii::t('app','是').'</span>':yii::t('app','否'); ?></td>
															<td><?php echo $orderProduct['is_retreat']==1?'<span class="label label-sm label-danger">'.yii::t('app','是').'</span>':yii::t('app','否');?></td>
                                                                                                                        <td class="red"><?php switch($orderProduct['is_waiting']){case '0': {echo yii::t('app','不等叫'); break;} case '1': {echo yii::t('app','等叫'); break;} case '2': { echo '<span class="label label-sm label-success">'.yii::t('app','已上菜').'</span>'; break;}};?></td>
															<td>
                                                                                                                            <?php foreach($allOrderProductTastes as $taste){if($taste['id']==$orderProduct['lid']) echo $taste['name'].' ';} ?>
                                                                                                                            <?php echo $orderProduct['taste_memo'];?>
                                                                                                                        </td>
                                                                                                                        <td><?php if($orderProduct['weight']>0) echo number_format($orderProduct['weight']*$orderProduct['price'],2); else echo number_format($orderProduct['amount']*$orderProduct['price'],2);?></td>
                                                                                                                        <td class="center">
                                                                                                                            <div class="btn-group dropup">
                                                                                                                                    <a class="btn green" href="#" data-toggle="dropdown">
                                                                                                                                    <?php echo yii::t('app','操作');?>
                                                                                                                                    <i class="fa fa-angle-up"></i>
                                                                                                                                    </a>
                                                                                                                                    <ul class="dropdown-menu pull-right"> <!--已厨打不能编辑-->
                                                                                                                                    <?php if($model->order_status!='4'): ?>
                                                                                                                                        <?php if($orderProduct['product_order_status']=='0') :?>
                                                                                                                                            <li><a href="javascript:;" class='btn-edit' setid="<?php echo $orderProduct['set_id'];?>" lid="<?php echo $orderProduct['lid'];?>" ><?php echo yii::t('app','编辑（未下单）');?></a></li>
                                                                                                                                            <li><a href="javascript:;" class="btn-del"  setid="<?php echo $orderProduct['set_id'];?>" lid="<?php echo $orderProduct['lid'];?>" ><?php echo yii::t('app','删除（未下单）');?></a></li>
                                                                                                                                            <li><a href="javascript:;" class='btn-taste' setid="<?php echo $orderProduct['set_id'];?>" lid="<?php echo $orderProduct['lid'];?>" ><?php echo yii::t('app','口味设定（未下单）');?></a></li> 
                                                                                                                                        <?php endif; ?>
                                                                                                                                            <li><a href="javascript:;" class='btn-addition' setid="<?php echo $orderProduct['set_id'];?>"  productid="<?php echo $orderProduct['product_id'];?>"  lid="<?php echo $orderProduct['lid'];?>"><?php echo yii::t('app','附加菜');?></a></li>
                                                                                                                                        <?php if($orderProduct['product_order_status']=='1') :?>
                                                                                                                                            <li><a href="javascript:;" class='btn-retreat' setid="<?php echo $orderProduct['set_id'];?>"  lid="<?php echo $orderProduct['lid'];?>"><?php echo yii::t('app','退菜（已下单）');?></a></li>
                                                                                                                                            <li><a href="javascript:;" class='btn-weight' isweight="<?php echo $orderProduct['is_weight_confirm'];?>" setid="<?php echo $orderProduct['set_id'];?>" lid="<?php echo $orderProduct['lid'];?>"><?php echo yii::t('app','称重（已下单）');?></a></li>
                                                                                                                                            <li><a href="javascript:;" class='btn-reprint' setid="<?php echo $orderProduct['set_id'];?>" lid="<?php echo $orderProduct['lid'];?>"><?php echo yii::t('app','单品厨打（已下单）');?></a></li>
                                                                                                                                        <?php endif; ?>
                                                                                                                                    <?php endif; ?>
                                                                                                                                    </ul>
                                                                                                                            </div>
                                                                                                                            <a href="<?php echo $this->createUrl('defaultOrder/over' , array('companyId' => $this->companyId,'lid'=>$orderProduct['lid'],'orderId'=>$orderProduct['order_id'],'typeId'=>$typeId)); ?>" class="btn-over btn green add_btn" ><i class="fa fa-check"></i><?php echo yii::t('app','勾挑');?></a>                                                                                                                                            
                                                                                                                        </td>
														</tr>
													<?php endforeach;?>
													</tbody>
												</table>
											</div>
											<div><?php echo yii::t('app','全单口味');?>：
                                                                                            <?php foreach($allOrderTastes as $taste){echo $taste['name'].' ';} ?>
                                                                                            <?php echo $model->taste_memo; ?>
                                                                                        </div>
										</div>
									</div>
									
							<?php $this->endWidget(); ?>
							<script>                                                      
                                                            
                                                            $('#btn-addproduct').on(event_clicktouchstart,function(){
                                                                var $modalconfig = $('#portlet-config');
                                                                $modalconfig.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/addProduct',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$model->lid,'isset'=>'0'));?>', '', function(){
                                                                            $modalconfig.modal();
                                                                          });                                
                                                            });
                                                            
                                                            $('#btn-addset').on(event_clicktouchstart,function(){
                                                                var $modalconfig = $('#modal-fullwide');
                                                                $modalconfig.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/addProductAll',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$model->lid,'isset'=>'1'));?>', '', function(){
                                                                            $modalconfig.modal();
                                                                          });                                
                                                            });

                                                            $('.btn-edit').on(event_clicktouchstart,function(){
                                                                   var id = $(this).attr('lid');
                                                                   var setid = $(this).attr('setid');
                                                                   var $modalconfig = $('#portlet-config');
                                                                   $modalconfig.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/editProduct',array('companyId'=>$this->companyId));?>/id/'+id+'/setid/'+setid+'/orderId/'+"<?php echo $model->lid; ?>"+'/typeId/'+"<?php echo $typeId; ?>"
                                                                   ,'', function(){
                                                                     $modalconfig.modal();
                                                                   });
                                                            });

                                                            $('.btn-retreat').on(event_clicktouchstart,function(){
                                                                   var id = $(this).attr('lid');
                                                                   var $modal=$('#portlet-config');
                                                                   $modal.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/retreatProduct',array('companyId'=>$this->companyId));?>/id/'+id+'/typeId/'+"<?php echo $typeId; ?>"
                                                                   ,'', function(){
                                                                     $modal.modal();
                                                                   });
                                                            });
                                                            
                                                            $('.btn-addition').on(event_clicktouchstart,function(){
                                                                   var productid = $(this).attr('productid');
                                                                   var $modal=$('#portlet-config');
                                                                   $modal.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/addAddition',array('companyId'=>$this->companyId,'orderId'=>$model->lid));?>/productId/'+productid+'/typeId/'+"<?php echo $typeId; ?>"
                                                                   ,'', function(){
                                                                     $modal.modal();
                                                                   });
                                                            });

                                                            $('.btn-taste').on(event_clicktouchstart,function(){
                                                                   var lid = $(this).attr('lid');
                                                                   var $modal=$('#portlet-config');
                                                                   $modal.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/productTaste',array('companyId'=>$this->companyId,'typeId'=>$typeId,'isall'=>'0'));?>/lid/'+lid
                                                                   ,'', function(){
                                                                     $modal.modal();
                                                                   });
                                                            });

                                                            $('.btn-weight').on(event_clicktouchstart,function(){
                                                                   var id = $(this).attr('lid');
                                                                   var isweight=$(this).attr('isweight');
                                                                   if(isweight=='0')
                                                                   {
                                                                       alert("<?php echo yii::t('app','非称重菜！');?>");
                                                                       return;
                                                                   }
                                                                   var $modal=$('#portlet-config');
                                                                   $modal.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/weightProduct',array('companyId'=>$this->companyId));?>/id/'+id+'/orderId/'+"<?php echo $model->lid; ?>"+'/typeId/'+"<?php echo $typeId; ?>"
                                                                   ,'', function(){
                                                                     $modal.modal();
                                                                   });
                                                            });

                                                            $('.btn-del').on(event_clicktouchstart,function(){
                                                               var id = $(this).attr('lid');
                                                               var setid = $(this).attr('setid');
                                                               //var orderstatus="<?php echo $model->order_status;?>";
                                                               //alert(orderstatus);
                                                               //if(orderstatus!='1')
                                                               //{
                                                               //    alert("<?php echo yii::t('app','不是未下单状态，不能删除！');?>");
                                                               //    return;
                                                               //}
                                                               //alert(setid);
                                                               var tip='';
                                                               if(setid!='0000000000')
                                                               {
                                                                   tip="<?php echo yii::t('app','你确定要删除整个套餐吗？');?>";
                                                               }else{
                                                                   tip="<?php echo yii::t('app','你确定要删除该单品吗？');?>";
                                                               }
                                                               bootbox.confirm(tip, function(result) {
                                                               if(result){
                                                                       location.href="<?php echo $this->createUrl('defaultOrder/delproduct',array('companyId'=>$this->companyId));?>/id/"+id+'/setid/'+setid+'/orderId/'+"<?php echo $model->lid; ?>"+'/typeId/'+"<?php echo $typeId; ?>";
                                                               }});
                                                           });                                                                  
                                                           
                                                           $('.btn-reprint').on(event_clicktouchstart,function(){
                                                                var id = $(this).attr('lid');
                                                                //alert(id);
                                                                var $modal=$('#portlet-config');
                                                                $modal.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/printOneKitchen',array('companyId'=>$this->companyId));?>/orderProductId/'+id+'/orderId/'+"<?php echo $model->lid; ?>"+'/typeId/'+"<?php echo $typeId; ?>"
                                                                        ,'', function(){
                                                                                    $modal.modal();
                                                                            });                                                                    
                                                            });
                        
							</script>