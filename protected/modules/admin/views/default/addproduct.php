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
                                                        <div class="form-actions fluid" id="product_panel">
                                                                <div class="form-group">
                                                                        <?php echo $form->label($orderProduct, 'category_id',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">
                                                                                <?php echo CHtml::dropDownList('selectCategory', '0', $categories , array('class'=>'form-control'));?>
                                                                        </div>
                                                                </div>

                                                                <div class="form-group" <?php if($orderProduct->hasErrors('product_id')) echo 'has-error';?>>
                                                                        <?php echo $form->label($orderProduct, 'product_id',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">											
                                                                                <?php echo $form->dropDownList($orderProduct, 'product_id', array('0' => '-- 请选择 --') +$products ,array('class' => 'form-control','placeholder'=>$orderProduct->getAttributeLabel('dpid')));?>
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
                                                                                <?php echo $form->dropDownList($orderProduct, 'is_giving', array('0' => '不赠送' , '1' => '赠送') , array('class' => 'form-control','placeholder'=>$orderProduct->getAttributeLabel('is_giving')));?>
                                                                                <?php echo $form->error($orderProduct, 'is_giving' )?>
                                                                        </div>
                                                                        <div class="col-md-4"></div>
                                                                        <div class="col-md-4"><span class="label label-default center">原价</span></div>
                                                                </div>
                                                                
                                                        </div><!--订单明细中 退菜、勾挑、优惠、重新厨打///厨打、结单、整单优惠-->
                                                </div>
                                                <div class="form-actions fluid hidden" id="set_panel">
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
                                                <input class="form-control" name="isset" id="isetid" type="hidden" value="0">
                                                <div class="modal-footer">
                                                        <button type="button" data-dismiss="modal" class="btn default">取 消</button>
                                                        <input type="submit" class="btn green" id="create_btn" value="确 定">
                                                </div>

                                                <?php $this->endWidget(); ?>                
                    <script type="text/javascript">
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
                        $('#btn_product').click(function(){
                                $('#btn_product').removeClass('grey');
                                $('#btn_product').addClass('purple');
                                $('#btn_set').removeClass('purple');
                                $('#btn_set').addClass('grey');
                                $('#set_panel').addClass('hidden');
                                $('#product_panel').removeClass('hidden');
                                $('#isetid').val('0');
                        });
                        $('#btn_set').click(function(){
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
                            $('#table-set-detail').load('<?php echo $this->createUrl('default/setdetail',array('companyId'=>$this->companyId));?>/id/'+id);
                            //alert('<?php echo $this->createUrl('default/setdetail',array('companyId'=>$this->companyId));?>/id/'+id);
                        });
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