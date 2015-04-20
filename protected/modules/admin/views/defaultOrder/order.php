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
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
                        <div class="modal fade" id="portlet-config2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                        <div class="modal-content">
                                                <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <h4 class="modal-title">Modal title2</h4>
                                                </div>
                                                <div class="modal-body">
                                                        Widget settings form goes here2
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
                        <!-- BEGIN PAGE CONTENT-->
			<div class="row">
                                <div class="col-md-4">
                                    <h3 class="page-title"><?php switch($model->order_status) {case 1:{echo '未下单';break;} case 2:{echo '下单未支付';break;} case 3:{echo '已支付';break;} }?></h3>
                                </div>
                                <div class="col-md-8">
                                    <h4>
                                       下单时间：<?php echo $model->create_at;?> 
                                       &nbsp;&nbsp;&nbsp;&nbsp; 应付金额（元）：<?php echo number_format($total['total'], 2);?>
                                       &nbsp;&nbsp;&nbsp;&nbsp; 实付金额（元）：<?php echo $model->reality_total;?>
                                    </h4>    
                                </div>
				<div class="col-md-12">
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-reorder"></i>
                                                            <?php echo $total['remark'] ;?>
                                                        </div>
                                                        <div class="actions">
                                                            <a class="btn purple" id="btn_account"><i class="fa fa-pencil"></i> 结单&收银</a>
                                                            <a id="kitchen-btn" class="btn purple"><i class="fa fa-cogs"></i> 下单&厨打</a>
                                                            <a id="print-btn" class="btn purple"><i class="fa fa-print"></i> 打印清单</a>
                                                            <a id="alltaste-btn" class="btn purple"><i class="fa fa-pencil"></i> 全单口味</a>
                                                            <a href="<?php echo $this->createUrl('default/index' , array('companyId' => $model->dpid,'typeId'=>$typeId));?>" class="btn red"><i class="fa fa-times"></i> 返回</a>
                                                        </div>
						</div>
						<div class="portlet-body form">
							<!-- BEGIN FORM-->
							<?php echo $this->renderPartial('_form', array('model'=>$model,'orderProducts' => $orderProducts,'productTotal' => $productTotal,'total' => $total,'typeId'=>$typeId)); ?>
							<!-- END FORM--> 
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->    
		</div>
		<!-- END PAGE -->  
                
                    <script type="text/javascript">
                        $(document).ready(function(){
                                $('body').addClass('page-sidebar-closed');
                                
                        });
                        
                        $('#btn_account').click(function(){
                                var $modalconfig = $('#portlet-config');
                                $modalconfig.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/account',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$model->lid,'total'=>$total['total']));?>', '', function(){
                                            $modalconfig.modal();
                                          }); 
                        });
                        
                        $('#btn_pay').click(function(){
                                var $modalconfig = $('#portlet-config');
                                $modalconfig.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/pay',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$model->lid,'total'=>$total['total']));?>', '', function(){
                                            $modalconfig.modal();
                                          }); 
                        });
                        
                        $('#print-btn').click(function(){
                                $.get('<?php echo $this->createUrl('defaultOrder/printList',array('companyId'=>$this->companyId,'id'=>$model->lid));?>',function(data){
                                        if(data.status) {
                                                alert('操作成功');
                                                //alert(data.msg);
                                        } else {
                                                alert(data.msg);
                                        }
                                },'json');
                        });
                        $('#kitchen-btn').click(function(){
                            var statu = confirm("下单，并厨打，确定吗？");
                                if(!statu){
                                    return false;
                                } 
                                location.href="<?php echo $this->createUrl('defaultOrder/printKitchen',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$model->lid));?>";
                        });
                        
                        $('#alltaste-btn').click(function(){
                                var $modalconfig = $('#portlet-config');
                                $modalconfig.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/productTaste',array('companyId'=>$this->companyId,'typeId'=>$typeId,'lid'=>$model->lid,'isall'=>'1'));?>', '', function(){
                                            $modalconfig.modal();
                                          }); 
                        });
                        
                    </script>