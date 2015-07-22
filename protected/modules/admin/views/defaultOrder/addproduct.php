                       			<?php $form=$this->beginWidget('CActiveForm', array(
                                                        'id'=>'orderProduct',
                                                        'action' => $this->createUrl('defaultOrder/addProduct',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$orderId,'isset'=>$isset)),
                                                        'enableAjaxValidation'=>true,
                                                        'enableClientValidation'=>true,
                                                        'clientOptions'=>array(
                                                                'validateOnSubmit'=>false,
                                                        ),
                                                        'htmlOptions'=>array(
                                                                'class'=>'form-horizontal'
                                                        ),
                                                )); ?>                                                
                                                <div class="modal-footer">
                                                        <button type="button" data-dismiss="modal" class="btn default"><?php echo yii::t('app','取 消');?></button>
                                                        <input type="submit" class="btn green" id="create_btn" value="<?php echo yii::t('app','确 定');?>">
                                                </div>
                                                <div class="modal-body">
                                                        <div class="form-actions fluid <?php if($isset=='1') echo 'hidden';?>" id="product_panel">
                                                                <div class="form-group">
                                                                        <?php echo $form->label($orderProduct, 'category_id',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">
                                                                                <?php echo CHtml::dropDownList('selectCategory', '0', $categories , array('class'=>'form-control'));?>
                                                                        </div>
                                                                </div>

                                                                <div class="form-group" <?php if($orderProduct->hasErrors('product_id')) echo 'has-error';?>>
                                                                        <?php echo $form->label($orderProduct, 'product_id',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">											
                                                                                <?php echo $form->dropDownList($orderProduct, 'product_id', array('0' => yii::t('app','-- 请选择 --')) +$products ,array('class' => 'form-control','placeholder'=>$orderProduct->getAttributeLabel('dpid')));?>
                                                                                <?php echo $form->error($orderProduct, 'product_id' )?>
                                                                        </div>
                                                                </div>                                                      
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
                                                                                <?php echo $form->dropDownList($orderProduct, 'is_giving', array('0' => yii::t('app','不赠送' ), '1' => yii::t('app','赠送')) , array('class' => 'form-control','placeholder'=>$orderProduct->getAttributeLabel('is_giving')));?>
                                                                                <?php echo $form->error($orderProduct, 'is_giving' )?>
                                                                        </div>
                                                                        <div class="col-md-4"></div>
                                                                        <div class="col-md-4"><span class="label label-default center"><?php echo yii::t('app','原价');?></span></div>
                                                                </div>
                                                                
                                                        </div><!--订单明细中 退菜、勾挑、优惠、重新厨打///厨打、结单、整单优惠-->
                                                </div>
                                                <div class="form-actions fluid <?php if($isset=='0') echo 'hidden';?>" id="set_panel">
                                                                <div class="form-group">
                                                                        <?php echo $form->label($orderProduct, 'set_id',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">
                                                                                <?php echo CHtml::dropDownList('setlist', '0', $setlist , array('class'=>'form-control'));?>
                                                                        </div>
                                                                </div>
                                                                <div class="portlet-body" id="table-set-detail">
                                                                                                                                                        
                                                                </div>
                                                                <!--list-->                                                             
                                                </div>
                                                <?php echo $form->hiddenField($orderProduct,'order_id',array('class'=>'form-control')); ?>
                                                <?php echo $form->hiddenField($orderProduct,'set_id',array('class'=>'form-control')); ?>
                                                <input class="form-control" name="selsetlist" id="selsetlistid" type="hidden" value="">
                                                

                                                <?php $this->endWidget(); ?>                
                    <script type="text/javascript">
                            var isset='<?php echo $isset; ?>';
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
                                                        var str = '<option value=""><?php echo yii::t('app','-- 请选择 --');?></option>';                                                                                            
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
                        $('#create_btn').on(event_clicktouchstart,function(){
                            //alert($('#setlist').val());
                            if(isset=='0' && $('#OrderProduct_product_id').val()=='0')
                            {
                                alert("<?php echo yii::t('app','请选择产品！');?>");
                                return false;
                            }
                            if(isset=='1' && $('#setlist').val()=='0')
                            {
                                alert("<?php echo yii::t('app','请选择套餐！');?>");
                                return false;
                            }
                        });
                        $('#btn_product').on(event_clicktouchstart,function(){
                                $('#btn_product').removeClass('grey');
                                $('#btn_product').addClass('purple');
                                $('#btn_set').removeClass('purple');
                                $('#btn_set').addClass('grey');
                                $('#set_panel').addClass('hidden');
                                $('#product_panel').removeClass('hidden');
                                $('#isetid').val('0');
                        });
                        $('#btn_set').on(event_clicktouchstart,function(){
                                $('#btn_set').removeClass('grey');
                                $('#btn_set').addClass('purple');
                                $('#btn_product').removeClass('purple');
                                $('#btn_product').addClass('grey');
                                $('#product_panel').addClass('hidden');
                                $('#set_panel').removeClass('hidden');
                                $('#isetid').val('1');
                        });
                        $('#setlist').change(function(){
                            id = $(this).val();
                            $('#OrderProduct_set_id').val(id);
                            $('#table-set-detail').load('<?php echo $this->createUrl('defaultOrder/setdetail',array('companyId'=>$this->companyId));?>/id/'+id);
                            //alert('<?php echo $this->createUrl('defaultOrder/setdetail',array('companyId'=>$this->companyId));?>/id/'+id);
                        });
                        $('#OrderProduct_product_id').change(function(){
                            var id = $(this).val();                            
                                $.ajax({
                                        url:'<?php echo $this->createUrl('defaultOrder/currentprice',array('companyId'=>$this->companyId));?>/id/'+id,
                                        type:'GET',
                                        dataType:'json',
                                        success:function(result){                                                                                                                                       
                                                $('#OrderProduct_price').val(result.cp); 
                                        }
                                });                            
                        });
                        
                        
                    </script>