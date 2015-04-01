	<!-- BEGIN PAGE -->  
		<div class="page-content">
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
			<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<?php $form=$this->beginWidget('CActiveForm', array(
                                                        'id'=>'order',
                                                        'action' => $this->createUrl('default/account',array('companyId'=>$this->companyId,'typeId'=>$typeId)),
                                                        'enableAjaxValidation'=>true,
                                                        'enableClientValidation'=>true,
                                                        'clientOptions'=>array(
                                                                'validateOnSubmit'=>false,
                                                        ),
                                                        'htmlOptions'=>array(
                                                                'class'=>'form-horizontal'
                                                        ),
                                                )); ?>
                                                <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <h4 class="modal-title">结单</h4>
                                                </div>
                                                <div class="modal-body">
                                                        <div class="form-actions fluid">
                                                                <div class="form-group">
                                                                        <?php echo $form->label($model, 'reality_total',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">
                                                                                <?php echo $form->textField($model, 'reality_total' ,array('value'=>$total['total'],'class' => 'form-control','placeholder'=>$model->getAttributeLabel('reality_total')));?>
                                                                                <?php echo $form->error($model, 'reality_total' )?>
                                                                        </div>
                                                                </div>
                                                                <div class="form-group">
                                                                        <?php echo $form->label($model, 'payment_method_id',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">
                                                                                <?php echo $form->dropDownList($model, 'payment_method_id' ,$paymentMethods ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('payment_method_id')));?>
                                                                                <?php echo $form->error($model, 'payment_method_id' )?>
                                                                        </div>
                                                                </div>
                                                                <div class="form-group">
                                                                        <?php echo $form->label($model, 'remark',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">
                                                                                <?php echo $form->textArea($model, 'remark' ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('remark')));?>
                                                                                <?php echo $form->error($model, 'remark' )?>
                                                                        </div>
                                                                </div>
                                                                <?php echo $form->hiddenField($model , 'order_status' , array('value'=>1));?>


                                                                </div><!--订单明细中 退菜、勾挑、优惠、重新厨打///厨打、结单、整单优惠-->
                                                </div>
                                                <div class="modal-footer">
                                                        <button type="button" data-dismiss="modal" class="btn default">取 消</button>
                                                        <input type="submit" class="btn green" id="create_btn" value="确 定">
                                                </div>

                                                <?php $this->endWidget(); ?>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			<!-- /.modal -->
                        <div class="modal fade" id="portlet-product" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<?php $form=$this->beginWidget('CActiveForm', array(
                                                        'id'=>'orderProduct',
                                                        'action' => $this->createUrl('default/addProduct',array('companyId'=>$this->companyId,'typeId'=>$typeId)),
                                                        'enableAjaxValidation'=>true,
                                                        'enableClientValidation'=>true,
                                                        'clientOptions'=>array(
                                                                'validateOnSubmit'=>false,
                                                        ),
                                                        'htmlOptions'=>array(
                                                                'class'=>'form-horizontal'
                                                        ),
                                                )); ?>
                                                <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <div class="actions">
                                                                <a class="btn purple" id="btn_product"><i class="fa fa-pencil"></i> 单品</a> 
                                                                <a class="btn grey" id="btn_set"><i class="fa fa-pencil"></i> 套餐</a>
                                                        </div>
                                                </div>
                                                <div class="modal-body">
                                                        <div class="form-actions fluid hidden" id="product_panel">
                                                                <div class="form-group">
                                                                        <?php echo $form->label($orderProduct, 'category_id',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">
                                                                                <?php echo CHtml::dropDownList('selectCategory', '0', $categories , array('class'=>'form-control'));?>
                                                                        </div>
                                                                </div>

                                                                <div class="form-group" <?php if($orderProduct->hasErrors('product_id')) echo 'has-error';?>>
                                                                        <?php echo $form->label($orderProduct, 'product_id',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">											
                                                                                <?php echo $form->dropDownList($orderProduct, 'product_id', array('0' => '-- 请选择 --') +$products ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('dpid')));?>
                                                                                <?php echo $form->error($orderProduct, 'product_id' )?>
                                                                        </div>
                                                                </div>                                                      
                                                                <div class="form-group">
                                                                        <?php echo $form->label($orderProduct, 'amount',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">
                                                                                <?php echo $form->textField($orderProduct, 'amount' ,array('class' => 'form-control','placeholder'=>$orderProduct->getAttributeLabel('amount')));?>
                                                                                <?php echo $form->error($orderProduct, 'amount' )?>
                                                                        </div>
                                                                </div>
                                                                <div class="form-group">
                                                                        <?php echo $form->label($orderProduct, 'zhiamount',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">
                                                                                <?php echo $form->textField($orderProduct, 'zhiamount' ,array('class' => 'form-control','placeholder'=>$orderProduct->getAttributeLabel('zhiamount')));?>
                                                                                <?php echo $form->error($orderProduct, 'zhiamount' )?>
                                                                        </div>
                                                                </div>
                                                                <div class="form-group">
                                                                        <?php echo $form->label($orderProduct, 'is_giving',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">
                                                                                <?php echo $form->dropDownList($orderProduct, 'is_giving', array('0' => '否' , '1' => '是') , array('class' => 'form-control','placeholder'=>$orderProduct->getAttributeLabel('is_giving')));?>
                                                                                <?php echo $form->error($orderProduct, 'is_giving' )?>
                                                                        </div>
                                                                </div>
                                                                
                                                        </div><!--订单明细中 退菜、勾挑、优惠、重新厨打///厨打、结单、整单优惠-->
                                                </div>
                                                <div class="form-actions fluid" id="set_panel">
                                                                <div class="form-group">
                                                                        <?php echo $form->label($orderProduct, 'set_id',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">
                                                                                <?php echo CHtml::dropDownList('setlist', '0', $setlist , array('class'=>'form-control'));?>
                                                                        </div>
                                                                </div>
                                                                <!--list-->                                                             
                                                </div>
                                                <?php echo $form->hiddenField($orderProduct,'order_id',array('class'=>'form-control')); ?>
                                                <div class="modal-footer">
                                                        <button type="button" data-dismiss="modal" class="btn default">取 消</button>
                                                        <input type="submit" class="btn green" id="create_btn" value="确 定">
                                                </div>

                                                <?php $this->endWidget(); ?>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			<!-- /.modal -->
			<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<!-- BEGIN PAGE HEADER-->   
			<h3 class="page-title">收银台</h3>

			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-reorder"></i>下单时间：<?php echo $model->create_at;?> 
                                                            &nbsp;&nbsp;&nbsp;&nbsp; 应付金额（元）：<?php echo $total['total'];?>
                                                            &nbsp;&nbsp;&nbsp;&nbsp; 实付金额（元）：<?php echo $model->reality_total;?>
                                                        </div>
                                                        <div class="actions">
                                                                <a class="btn purple" id="btn_account"><i class="fa fa-pencil"></i> 结单</a>
                                                                <a href="<?php echo $this->createUrl('default/print' , array('companyId' => $this->companyId));?>" class="btn purple"><i class="fa fa-pencil"></i> 厨打</a>
                                                                <a href="<?php echo $this->createUrl('default/index' , array('companyId' => $model->dpid,'typeId'=>$typeId));?>" class="btn red"><i class="fa fa-times"></i> 返回</a>
                                                        </div>
						</div>
						<div class="portlet-body form">
							<!-- BEGIN FORM-->
							<?php echo $this->renderPartial('_form', array('model'=>$model,'orderProducts' => $orderProducts,'productTotal' => $productTotal,'total' => $total,'paymentMethods'=>$paymentMethods)); ?>
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
                                $('#selectCategory').change(function(){
                                        var cid = $(this).val();
                                        //alert('<?php echo $this->createUrl('productSet/getChildren',array('companyId'=>$this->companyId));?>/pid/'+cid);
                                        //alert($('#ProductSetDetail_product_id').html());
                                        $.ajax({
                                                url:'<?php echo $this->createUrl('productSet/getChildren',array('companyId'=>$this->companyId));?>/pid/'+cid,
                                                type:'GET',
                                                dataType:'json',
                                                success:function(result){
                                                        //alert(result.data);
                                                        var str = '<option value="">--请选择--</option>';                                                                                            
                                                        if(result.data.length){
                                                                //alert(1);
                                                                $.each(result.data,function(index,value){
                                                                        str = str + '<option value="'+value.id+'">'+value.name+'</option>';
                                                                });                                                                                                                                                                                                       
                                                        }
                                                        $('#OrderProduct_product_id').html(str); 
                                                }
                                        });
                                });
                        });
                         var $modalaccount = $('#portlet-config');
                         $('#btn_account').click(function(){
                                $modalaccount.modal();
                                 
                         });
                         var $modalproduct = $('#portlet-product');
                         $('#addproduct').click(function(){
                                $modalproduct.modal();
                                 
                         });
                         $('.del-btn').click(function(){
                            var that = $(this);
                            var id = $(this).attr('item');
                            bootbox.confirm('你确定要删除该商品吗？', function(result) {
                            if(result){
                                                $.get('<?php echo $this->createUrl('order/deleteProduct',array('companyId'=>$this->companyId));?>&id='+id,function(data){
                                                        if(data.status){
                                                                alert('删除成功');
                                                                if(data.amount) {
                                                                        that.parent().prev().html(data.amount*data.price);
                                                                        that.parent().prev().prev().html(data.amount);
                                                                        $('#total').html(data.total);
                                                                        $('#Order_reality_total').val(data.total);
                                                                } else {
                                                                        that.parents('tr').remove();
                                                                }
                                                        } else {
                                                                alert('删除失败');
                                                        }
                                                },'json');
                            }});
                        });
                        $('#submit-btn').click(function(){
                                 bootbox.confirm('你确定要结单吗？', function(result) {
                                        if(result){
                                                $('#order-form').submit();
                                        }
                                 });
                        });
                        $('#print-btn').click(function(){
                                $.get('<?php echo $this->createUrl('order/printList',array('companyId'=>$this->companyId,'id'=>$model->lid));?>',function(data){
                                        if(data.status) {
                                                alert('操作成功');
                                        } else {
                                                alert(data.msg);
                                        }
                                },'json');
                        });
                        $('#reprint-btn').click(function(){
                                $.get('<?php echo $this->createUrl('order/printProducts',array('companyId'=>$this->companyId,'id'=>$model->lid,'reprint'=>1));?>',function(data){
                                        if(data.status) {
                                                alert('操作成功');
                                        } else {
                                                alert(data.msg);
                                        }
                                },'json');
                        });
                    </script>