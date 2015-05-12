			
						<?php $form=$this->beginWidget('CActiveForm', array(
                                                        'id'=>'account-form',
                                                        'action' => $this->createUrl('defaultOrder/account',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$model->lid)),
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
                                                        <h4 class="modal-title">收银 && 结单 <?php switch($model->order_status) {case 2:{echo '未支付';break;} case 3:{echo '已支付';break;} }?></h4>
                                                        <?php if($callid!='0'): ?>
                                                        <span style="color:red;" id="timecount">20</span>...秒后自动结单，点击<input type="button" class="btn green" id="autopay_pause" value="此按钮">停止自动结单！
                                                        <?php endif;?>
                                                </div>
                                                <div class="modal-body">
                                                        <div class="form-actions fluid">
                                                                
                                                                <div class="form-group">
                                                                        <?php echo $form->label($model, 'reality_total',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">
                                                                                <?php echo $form->textField($model, 'reality_total' ,array('value'=>number_format($total,2),'class' => 'form-control','placeholder'=>$model->getAttributeLabel('reality_total')));?>
                                                                                应付：<?php echo number_format($total,2); ?><?php echo $form->error($model, 'reality_total' )?>
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
                                                                <?php echo $form->hiddenField($model , 'order_status' , array('value'=>1,'id'=>'account_orderstatus'));?>


                                                                </div><!--订单明细中 退菜、勾挑、优惠、重新厨打///厨打、结单、整单优惠-->
                                                </div>
                                                <div class="modal-footer">
                                                        <input type="button" class="btn green" id="pay-btn" value="收 银">
                                                        <input type="button" class="btn green" id="account-btn" value="结 单">
                                                        <button type="button" data-dismiss="modal" class="btn default">取 消</button>
                                                </div>

                                                <?php $this->endWidget(); ?>
					
			
			<script type="text/javascript">
                            var interval;
                            $('#pay-btn').click(function(){
                                 bootbox.confirm('你确定只收银不结单吗？', function(result) {
                                        if(result){
                                                //$('#account-form').attr('action','<?php $this->createUrl('defaultOrder/account',array('companyId'=>$this->companyId,'typeId'=>$typeId,'op'=>'pay','orderId'=>$model->lid)) ?>');
                                                $('#account_orderstatus').val('3');
                                                $('#account-form').submit();
                                        }
                                 });
                            });
                            $('#account-btn').click(function(){
                                 bootbox.confirm('确定结单吗？', function(result) {
                                        if(result){
                                                //$('#account-form').attr('action','<?php $this->createUrl('defaultOrder/account',array('companyId'=>$this->companyId,'typeId'=>$typeId,'op'=>'account','orderId'=>$model->lid)) ?>');
                                                $('#account_orderstatus').val('4');
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
                                    $('#account_orderstatus').val('4');
                                    $('#account-form').submit();
                                }else{
                                    $("#timecount").html(curtime);
                                }
                            }
                        </script>