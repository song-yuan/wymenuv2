							<?php $form=$this->beginWidget('CActiveForm', array(
                                                        'id'=>'orderAddition',
                                                        'action' => $this->createUrl('defaultOrder/addAddition',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$orderId,'productId'=>$productId)),
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
                                                            <table class="table table-striped table-bordered table-hover" id="sample_1">
                                                            <?php if($models):?>
                                                                    <thead>
                                                                            <tr>
                                                                                    <th><?php echo yii::t('app','单品名称');?></th>
                                                                                    <th><?php echo yii::t('app','加菜价格');?></th>
                                                                                    <th><?php echo yii::t('app','单次加菜数量');?></th>
                                                                                    <th><?php echo yii::t('app','总点单数量');?></th>
                                                                            </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                    <?php foreach ($models as $model):?>
                                                                            <tr class="odd gradeX">
                                                                                    <td ><?php echo $model->sproduct->product_name ;?></td>
                                                                                    <td><?php echo $model->price;?></td>
                                                                                    <td><?php echo $model->number;?></td>
                                                                                    <td><a class="btn green minus">&nbsp;<i class="fa fa-minus"></i>&nbsp;</a><input type="text" maxlength="5" size="5" class="additionnum" productid="<?php echo $model->sproduct_id;?>" productprice="<?php echo $model->price;?>" unitNumber="<?php echo $model->number;?>" value="0" readonly="true"/><a class="btn green plus">&nbsp;<i class="fa fa-plus"></i></a></td>                                                                                                                                                                                                 
                                                                            </tr>
                                                                    <?php endforeach;?>
                                                                            
                                                                    </tbody>
                                                                    <?php endif;?>
                                                            </table>
                                                        </div><<?php echo yii::t('app','!--订单明细中 退菜、勾挑、优惠、重新厨打///厨打、结单、整单优惠--');?>>
                                                </div>
                                                <input class="form-control" name="additionnames" id="additionids" type="hidden" value="">
                                                <div class="modal-footer">
                                                        <button type="button" data-dismiss="modal" class="btn default"><?php echo yii::t('app','取 消');?></button>
                                                        <input type="button" class="btn green" id="addition_create_btn" value="<?php echo yii::t('app','确 定');?>">
                                                </div>

                                                <?php $this->endWidget(); ?>
							<script>
                                                        $('#addition_create_btn').on(event_clicktouchstart,function(){
                                                            var numlist='';
                                                            var numstr=0;
                                                            $('.additionnum').each(function(){
                                                                numstr=$(this).val();
                                                                if(parseInt(numstr)>0)
                                                                {
                                                                    if(numlist.length===0)
                                                                    {
                                                                        numlist=$(this).attr('productid')+'|'+$(this).attr('productprice')+'|'+numstr;
                                                                    }else{
                                                                        numlist=numlist+','+$(this).attr('productid')+'|'+$(this).attr('productprice')+'|'+numstr;
                                                                    }
                                                                }
                                                            });
                                                            $('#additionids').val(numlist);
                                                            //alert(numlist);
                                                            $('#orderAddition').submit();
                                                        });
                                                        
                                                        $('.minus').on(event_clicktouchstart,function(){
                                                                var input = $(this).siblings('input');
                                                                var num = parseInt(input.val());
                                                                var unitnum = parseInt(input.attr('unitnumber'));
                                                                if(num-unitnum >= 0){
                                                                        num = num - unitnum;
                                                                }
                                                                input.val(num);			
                                                        });
                                                        $('.plus').on(event_clicktouchstart,function(){
                                                                var input = $(this).siblings('input');
                                                                var num = parseInt(input.val());
                                                                var unitnum = parseInt(input.attr('unitnumber'));
                                                                num = num + unitnum;
                                                                input.val(num);			
                                                        });	
							</script>