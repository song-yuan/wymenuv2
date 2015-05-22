			
						<?php $form=$this->beginWidget('CActiveForm', array(
                                                        'id'=>'account-form',
                                                        'action' => $this->createUrl('defaultOrder/accountAuto',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$order->lid)),
                                                        'enableAjaxValidation'=>true,
                                                        //'method'=>'POST',
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
                                                        <h4 class="modal-title"><?php echo '自动下单、厨打、收银'; ?>呼叫器号：<?php echo $callid;?></h4>                                                        
                                                        <span style="color:red;" id="timecount">20</span>...秒后自动完成厨打收银，点击<input type="button" class="btn green" id="autopay_pause" value="此按钮">停止自动厨打收银！
                                                        
                                                            <span style="color:red;" id="successnumid">0</span>...个菜品厨打已经成功</br>
                                                            <span style="color:red;" id="notsurenumid">0</span>...个菜品正在打印</br>
                                                            <span style="color:red;" id="errornumid">0</span>...个菜品厨打失败，</br>
                                                            请点击“确定”按钮，去订单页面查看厨打失败产品，</br>
                                                            确认打印机无故障后，点击打印失败菜品后面的“操作”按钮下的“重新厨打”按钮。
                                                                                                              
                                                </div>
                                                <div class="modal-body">
                                                        <div class="form-actions fluid">
                                                            
                                                                整单应付金额：<?php echo number_format($order->should_total,2); ?>，整单已付金额：<?php echo number_format($order->reality_total,2); ?>，本次应付：
                                                            
                                                                <div class="form-group">
                                                                        <?php echo $form->label($orderpay, 'pay_amount',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">
                                                                                <?php echo $form->textField($orderpay, 'pay_amount' ,array('value'=>number_format($order->should_total-$order->reality_total,2),'class' => 'form-control','placeholder'=>$orderpay->getAttributeLabel('pay_amount')));?>
                                                                                <?php echo $form->error($orderpay, 'pay_amount' )?>
                                                                        </div>
                                                                </div>
                                                                <div class="form-group">
                                                                        <?php echo $form->label($orderpay, 'payment_method_id',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">
                                                                                <?php echo $form->dropDownList($orderpay, 'payment_method_id' ,$paymentMethods ,array('class' => 'form-control','placeholder'=>$orderpay->getAttributeLabel('payment_method_id')));?>
                                                                                <?php echo $form->error($orderpay, 'payment_method_id' )?>
                                                                        </div>
                                                                </div>
                                                                <div class="form-group">
                                                                        <?php echo $form->label($orderpay, 'remark',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">
                                                                                <?php echo $form->textArea($orderpay, 'remark' ,array('class' => 'form-control','placeholder'=>$orderpay->getAttributeLabel('remark')));?>
                                                                                <?php echo $form->error($orderpay, 'remark' )?>
                                                                        </div>
                                                                </div>
                                                                <?php echo $form->hiddenField($order , 'order_status' , array('id'=>'account_orderstatus'));?>
                                                                <?php echo $form->hiddenField($order , 'should_total' , array('id'=>'order_should_total'));?>

                                                                </div><!--订单明细中 退菜、勾挑、优惠、重新厨打///厨打、结单、整单优惠-->
                                                </div>
                                                <div class="modal-footer">                                                   
                                                        <input type="button" class="btn green" id="pay-btn" value="下单&厨打&收银">
                                                        <button type="button" data-dismiss="modal" class="btn default">取 消</button>
                                                </div>

                                                <?php $this->endWidget(); ?>
					
			
			<script type="text/javascript">
                            var interval;
                            var hasprint=false;
                            $('#pay-btn').click(function(){
                                 bootbox.confirm('确定收银结束吗？', function(result) {
                                        if(result){
                                                //$('#account-form').attr('action','<?php $this->createUrl('defaultOrder/account',array('companyId'=>$this->companyId,'typeId'=>$typeId,'op'=>'pay','orderId'=>$order->lid)) ?>');
                                                $('#account_orderstatus').val('3');
                                                $('#account-form').submit();
                                        }
                                 });
                            });
                            
                            $(document).ready(function() {
                                clearTimeout(interval);
                                var isauto="<?php if($callid=='0'){ echo '0';} else{ echo '1';} ?>";
                                //var isauto='1';
                                if(isauto=='1')
                                {
                                    interval = setInterval(autopaytimer,"2000");
                                }
                            });
                            $('#autopay_pause').click(function(){
                                //alert(11);
                                clearTimeout(interval);
                            });
                            function autopaytimer(){
                                //alert($("#timecount").html());
                                var curtime=parseInt($("#timecount").html());
                                curtime-=2;
                                if(curtime==0)
                                {
                                    clearTimeout(interval);
                                    //auto pay
                                    $('#account_orderstatus').val('3');
                                    //$('#account-form').submit();
                                    //$post()
                                    
                                }else if(curtime>0){
                                    $("#timecount").html(curtime);
                                }else if(curtime>-12){
                                    if(hasprint)
                                    {
                                        $.get('<?php echo $this->createUrl('defaultOrder/printKitchenResult',array('companyId'=>$this->companyId,'orderId'=>$orderId));?>/timenum/'+waitingsecond,function(data){
                                                    //alert(data.notsurenum);
                                                    $("#successnumid").html(data.successnum);
                                                    $("#errornumid").html(data.errornum);
                                                    $("#notsurenumid").html(data.notsurenum);
                                                    
                                                    if(data.finished && data.errornum==0 && data.notsurenum==0)
                                                    {
                                                        //all success
                                                        //location.href= order
                                                        clearTimeout(interval);
                                                        location.href="<?php echo $this->createUrl('defaultOrder/order',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$orderId));?>";
                                                    }
                                                    
                                                },'json');
                                    }
                                }else{
                                    clearTimeout(interval);
                                }
                            }
                        </script>