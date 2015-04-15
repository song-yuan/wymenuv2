			
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
                                                                                <?php echo $form->textField($model, 'reality_total' ,array('value'=>number_format($total['total'],2),'class' => 'form-control','placeholder'=>$model->getAttributeLabel('reality_total')));?>
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
					
			
			<script type="text/javascript">
                            
                        </script>