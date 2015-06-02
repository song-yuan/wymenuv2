							
                                                <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <h4 class="modal-title"><?php echo yii::t('app','正在厨打...');?></h4>
                                                </div>
                                                <div class="modal-body">
                                                        <div class="form-actions fluid" id="product_panel">
                                                            <span style="color:red;" id="printresult"><?php echo yii::t('app','检测打印状态...');?></span>
                                                        </div><<?php echo yii::t('app','!--订单明细中 退菜、勾挑、优惠、重新厨打///厨打、结单、整单优惠--');?>>
                                                </div>
                                                <input class="form-control" name="additionnames" id="additionids" type="hidden" value="">
                                                <div class="modal-footer">
                                                        <input type="button" class="btn green" id="btn-print-roll" value="<?php echo yii::t('app','确 定');?>">
                                                </div>

                                            <script>
                                            var interval;
                                            var waitingsecond=10;
                                            $(document).ready(function() {
                                                clearTimeout(interval);
                                                var jobstatus="<?php if($ret['status']) echo '1'; else echo '0';?>";
                                                var jobid="<?php echo $ret['jobid'];?>";
                                                var jobmsg="<?php echo $ret['msg'];?>";
                                                if(jobstatus=='1')
                                                {
                                                    interval = setInterval(printStatus,"1000");
                                                }else{
                                                    //alert(jobmsg);
                                                    $("#printresult").html(jobmsg);
                                                }
                                            });
                                            //全部成功自动刷新，有失败任务，停止刷新页面， 
                                            //点击确定，回滚失败的任务单品打印状态，提示逐个重新厨打。
                                            //在刷新页面，用local.href="";  
                                             $('#btn-print-roll').click(function(){
                                                 location.href="<?php echo $this->createUrl('defaultOrder/order',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$orderId));?>";
                                             });
                                             
                                             function printStatus()
                                             {
                                                //get print result
                                                //if has error stop
                                                $.get('<?php echo $this->createUrl('defaultOrder/printKitchenResultOne',array('companyId'=>$this->companyId,'jobid'=>$ret['jobid'],'orderProductId'=>$orderProductId));?>',function(data){
                                                    if(!data.status) {
                                                         $("#printresult").html(data.msg);                                                         
                                                    } else {                                                        
                                                        clearTimeout(interval);
                                                        location.href="<?php echo $this->createUrl('defaultOrder/order',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$orderId));?>";                                                        
                                                    }
                                                },'json');
                                                waitingsecond--;
                                                //10s后还有任务没有返回，就作为失败处理。
                                                if(waitingsecond<0)
                                                {
                                                    clearTimeout(interval);
                                                    //stop;
                                                }                                                
                                            }
                                            </script>