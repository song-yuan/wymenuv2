                       			<?php $form=$this->beginWidget('CActiveForm', array(
                                                        'id'=>'orderProduct',
                                                        'action' => $this->createUrl('defaultOrder/editProduct',array('companyId'=>$this->companyId,'typeId'=>$typeId,'id'=>$orderProduct->lid)),
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
                                                                        <?php echo $form->label($orderProduct, 'amount',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-2">
                                                                                <?php echo $form->textField($orderProduct, 'amount' ,array('class' => 'form-control','placeholder'=>$orderProduct->getAttributeLabel('amount')));?>
                                                                                <?php echo $form->error($orderProduct, 'amount' )?>
                                                                        </div>
                                                                
                                                                        <?php echo $form->label($orderProduct, 'zhiamount',array('class' => 'col-md-2 control-label'));?>
                                                                        <div class="col-md-2">
                                                                                <?php echo $form->textField($orderProduct, 'zhiamount' ,array('class' => 'form-control','placeholder'=>$orderProduct->getAttributeLabel('zhiamount')));?>
                                                                                <?php echo $form->error($orderProduct, 'zhiamount' )?>
                                                                        </div>                                                  
                                                                        
                                                                </div> 
                                                                <div class="form-group">
                                                                        <?php echo $form->label($orderProduct, 'price',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-3">
                                                                                <?php echo $form->textField($orderProduct, 'price' ,array('class' => 'form-control','placeholder'=>$orderProduct->getAttributeLabel('price')));?>
                                                                                <?php echo $form->error($orderProduct, 'price' )?>
                                                                        </div>
                                                                        
                                                                        <div class="col-md-3">
                                                                                <?php echo $form->dropDownList($orderProduct, 'is_giving', array('0' => yii::t('app','不赠送') , '1' => yii::t('app','赠送')) , array('class' => 'form-control','placeholder'=>$orderProduct->getAttributeLabel('is_giving')));?>
                                                                                <?php echo $form->error($orderProduct, 'is_giving' )?>
                                                                        </div>                                                                        
                                                                </div>
                                                                
                                                        </div><<?php echo yii::t('app','!--订单明细中 退菜、勾挑、优惠、重新厨打///厨打、结单、整单优惠--');?>>
                                                </div>
                                                <div class="form-actions fluid hidden" id="set_panel">
                                                                <div class="col-md-10"><span class="label label-default center">'<?php if(!empty($orderProduct->productSet->set_name)) echo $orderProduct->productSet->set_name;?>'</span></div>                                                     
                                                                <div class="portlet-body" id="table-set-detail">
                                                                                                                                                        
                                                                </div>
                                                                <!--list-->                                                             
                                                </div>
                                                <?php echo $form->hiddenField($orderProduct,'order_id',array('class'=>'form-control')); ?>
                                                <?php echo $form->hiddenField($orderProduct,'set_id',array('class'=>'form-control')); ?>
                                                <input class="form-control" name="selsetlist" id="selsetlistid" type="hidden" value="">
                                                <input class="form-control" name="isset" id="isetid" type="hidden" value="0">
                                                <div class="modal-footer">
                                                        <button type="button" data-dismiss="modal" class="btn default"><?php echo yii::t('app','取 消');?></button>
                                                        <input type="submit" class="btn green" id="create_btn" value="<?php echo yii::t('app','确 定');?>">
                                                </div>

                                                <?php $this->endWidget(); ?>                
                    <script type="text/javascript">
                            var setlist = function(){
                                    var set_id = '<?php echo $orderProduct->set_id?>';
                                    if(set_id=='0000000000')
                                    {
                                        $('#set_panel').addClass('hidden');
                                        $('#product_panel').removeClass('hidden');
                                        $('#isetid').val('0');
                                    }else{
                                        $('#product_panel').addClass('hidden');
                                        $('#set_panel').removeClass('hidden');
                                        $('#isetid').val('1');
                                        $('#table-set-detail').load('<?php echo $this->createUrl('defaultOrder/setdetail',array('companyId'=>$this->companyId));?>/id/'+set_id);
                                    }
                                };
                              setlist();                    
                         
                    </script>