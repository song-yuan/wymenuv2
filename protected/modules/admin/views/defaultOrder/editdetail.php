						<?php $form=$this->beginWidget('CActiveForm', array(
                                                        'id'=>'orderProduct',
                                                        'action' => $this->createUrl('defaultOrder/addProduct',array('companyId'=>$this->companyId,'typeId'=>$typeId)),
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
                                                        <div class="form-actions fluid <?php if($setid!='0000000000') echo 'hidden'; ?>" id="product_panel">
                                                                <div class="form-group">
                                                                        <?php echo $form->label($orderProduct, 'category_id',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">
                                                                                <?php echo CHtml::dropDownList('selectCategory', '0', $categories , array('class'=>'form-control'));?>
                                                                        </div>
                                                                </div>

                                                                <div class="form-group" <?php if($orderProduct->hasErrors('product_id')) echo 'has-error';?>>
                                                                        <?php echo $form->label($orderProduct, 'product_id',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">											
                                                                                <?php echo $form->dropDownList($orderProduct, 'product_id', array('0' => yii::t('app','-- 请选择 --')) +$products ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('dpid')));?>
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
                                                                                <?php echo $form->dropDownList($orderProduct, 'is_giving', array('0' => yii::t('app','否') , '1' => yii::t('app','是')) , array('class' => 'form-control','placeholder'=>$orderProduct->getAttributeLabel('is_giving')));?>
                                                                                <?php echo $form->error($orderProduct, 'is_giving' )?>
                                                                        </div>
                                                                </div>                              
                                                                
                                                        </div><<?php echo yii::t('app','!--订单明细中 退菜、勾挑、优惠、重新厨打///厨打、结单、整单优惠--');?>>
                                                </div>
                                                <div class="form-actions fluid <?php if($setid=='0000000000') echo 'hidden'; ?>" id="set_panel">
                                                        <div class="portlet-body" id="table-set-detail">
                                                             <table class="table table-striped table-bordered table-hover" id="sample_1">
                                                            <?php if($models):?>
                                                                    <thead>
                                                                            <tr>
                                                                                    <th><?php echo yii::t('app','单品名称');?></th>
                                                                                    <th><?php echo yii::t('app','套餐价格');?></th>
                                                                                    <th><?php echo yii::t('app','数量');?></th>
                                                                                    <th><?php echo yii::t('app','选择');?></th>
                                                                            </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                    <?php foreach ($models as $model):?>
                                                                            <tr class="<?php echo $model->group_no%2==0?'active':'success';?>">
                                                                                    <td ><?php echo $model->product->product_name ;?></td>
                                                                                    <td><?php echo $model->price;?></td>
                                                                                    <td><?php echo $model->number;?></td>
                                                                                    <td>
                                                                                        <?php echo yii::t('app','分组');?><?php echo $model->group_no;?><input name="group<?php echo $model->group_no;?>" value="<?php echo $model->product_id.'|'.$model->number.'|'.$model->price;?>" <?php if($model->is_select) echo 'checked'; ?> type="radio"  class="toggle groupradio"/> 

                                                                                    </td>                                                                                                             
                                                                            </tr>
                                                                    <?php endforeach;?>
                                                                    </tbody>
                                                                    <?php endif;?>
                                                            </table>
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
                                                <script>
                                                            var setlistid=function(){
                                                             var objs=$('.groupradio');
                                                             var setsel='';
                                                             objs.each(function(){
                                                                    //这里是处理 obj 的函数
                                                                    var chk=$(this).val();
                                                                    
                                                                    if($(this).is(':checked'))
                                                                    {
                                                                        if(setsel.length===0)
                                                                        {
                                                                            setsel=chk;
                                                                        }else{
                                                                            setsel=setsel+','+chk;
                                                                        }
                                                                    }                                                                    
                                                                });  
                                                                $('#selsetlistid').val(setsel);
                                                         };
                                                         setlistid();
                                                         $('.groupradio').on(event_clicktouchstart,function(){
                                                            setlistid();
                                                         });
                                                </script>