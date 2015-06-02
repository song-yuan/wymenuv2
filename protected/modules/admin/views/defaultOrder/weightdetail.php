                                                <?php $form=$this->beginWidget('CActiveForm', array(
                                                        'id'=>'orderProduct',
                                                        'action' => $this->createUrl('defaultOrder/weightProduct',array('companyId'=>$this->companyId,'typeId'=>$typeId,'id'=>$orderProduct->lid)),
                                                        'enableAjaxValidation'=>true,
                                                        'enableClientValidation'=>true,
                                                        'clientOptions'=>array(
                                                                'validateOnSubmit'=>false,
                                                        ),
                                                        'htmlOptions'=>array(
                                                                'class'=>'form-horizontal'
                                                        ),
                                                )); ?>
                                                
                                                <div class="modal-body">
                                                        <div class="form-actions fluid" id="product_panel">
                                                                
                                                                <div class="col-md-10"><span class="label label-default center">'<?php if(!empty($orderProduct->product->product_name)) echo $orderProduct->product->product_name;?>'</span></div>                                                     
                                                                
                                                                <div class="form-group">
                                                                        <?php echo $form->label($orderProduct, 'weight',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-3">
                                                                                <?php echo $form->textField($orderProduct, 'weight' ,array('class' => 'form-control','placeholder'=>$orderProduct->getAttributeLabel('weight')));?>
                                                                                <?php echo $form->error($orderProduct, 'weight' )?>
                                                                        </div>
                                                                                                                                                
                                                                </div>
                                                                
                                                        </div><<?php echo yii::t('app','!--订单明细中 退菜、勾挑、优惠、重新厨打///厨打、结单、整单优惠--');?>>
                                                </div>
                                                
                                                <?php echo $form->hiddenField($orderProduct,'order_id',array('class'=>'form-control')); ?>
                                                <?php echo $form->hiddenField($orderProduct,'set_id',array('class'=>'form-control')); ?>
                                                
                                                <div class="modal-footer">
                                                        <button type="button" data-dismiss="modal" class="btn default"><?php echo yii::t('app','取 消');?></button>
                                                        <input type="submit" class="btn green" id="create_btn" value="<?php echo yii::t('app','确 定');?>">
                                                </div>

                                                <?php $this->endWidget(); ?>                
                    <script type="text/javascript">
                                               
                         
                    </script>