                                             <?php $form=$this->beginWidget('CActiveForm', array(
                                                        'id'=>'productweight',
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
                                                        <h4 class="modal-title">口味选择</h4>
                                                </div>
                                                        <div class="modal-body">
                                                                <div class="form-group">
                                                                        <?php echo $form->label($orderProduct, 'weight',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">
                                                                                <?php echo $form->textField($orderProduct, 'weight' ,array('class' => 'form-control','placeholder'=>$orderProduct->getAttributeLabel('weight')));?>
                                                                                <?php echo $form->error($orderProduct, 'weight' )?>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                   <div class="modal-footer">
                                                           <button type="button" data-dismiss="modal" class="btn default">取 消</button>
                                                           <input type="submit" class="btn green" id="create_btn" value="确 定">
                                                   </div>

                                        <?php $this->endWidget(); ?>
							<script>
                                                         
							</script>