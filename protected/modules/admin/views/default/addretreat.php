                       		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                        <div class="modal-content">
                                        	<?php $form=$this->beginWidget('CActiveForm', array(
                                                        'id'=>'orderProduct',
                                                        'action' => $this->createUrl('default/addRetreat',array('companyId'=>$this->companyId,'typeId'=>$typeId)),
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
                                                        
                                                </div>
                                                <div class="modal-body">
                                                        <div class="form-actions fluid" id="product_panel">
                                                                <div class="form-group" <?php if($orderRetreat->hasErrors('retreat_id')) echo 'has-error';?>>
                                                                        <?php echo $form->label($orderRetreat, 'retreat_id',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">											
                                                                                <?php echo $form->dropDownList($orderRetreat, 'retreat_id', array('0' => '-- 请选择 --') +$retreats ,array('class' => 'form-control','placeholder'=>$orderRetreat->getAttributeLabel('retreat_id')));?>
                                                                                <?php echo $form->error($orderRetreat, 'retreat_id' )?>
                                                                        </div>
                                                                </div>                                                      
                                                                 
                                                                <div class="form-group">
                                                                        <?php echo $form->label($orderRetreat, 'retreat_memo',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">
                                                                                <?php echo $form->textField($orderRetreat, 'retreat_memo' ,array('class' => 'form-control','placeholder'=>$orderProduct->getAttributeLabel('price')));?>
                                                                                <?php echo $form->error($orderRetreat, 'retreat_memo' )?>
                                                                        </div>
                                                                </div>
                                                                
                                                        </div><!--订单明细中 退菜、勾挑、优惠、重新厨打///厨打、结单、整单优惠-->
                                                </div>
                                                
                                                <?php echo $form->hiddenField($orderRetreat,'order_detail_id',array('class'=>'form-control')); ?>
                                                
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
                    <script type="text/javascript">                           
                        $('#OrderProduct_product_id').change(function(){
                            var id = $(this).val();                            
                                $.ajax({
                                        url:'<?php echo $this->createUrl('default/currentprice',array('companyId'=>$this->companyId));?>/id/'+id,
                                        type:'GET',
                                        dataType:'json',
                                        success:function(result){                                                                                                                                       
                                                $('#OrderProduct_price').val(result.cp); 
                                        }
                                });                            
                        });
                        
                        
                    </script>