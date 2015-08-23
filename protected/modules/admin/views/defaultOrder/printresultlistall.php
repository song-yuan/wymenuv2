							
                                                <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <h4 class="modal-title"><?php echo yii::t('app','共');?><?php echo $ret['allnum'] ?><?php echo yii::t('app','个任务在厨打');?></h4>
                                                </div>
                                                <?php if($ret['status']):?>
                                                <div class="modal-body">
                                                        <div class="form-actions fluid" id="product_panel">
                                                            <span style="color:red;" id="minustimes">30</span><?php echo yii::t('app','30秒倒计时...');?></br></br>
                                                            <span style="color:red;" id="successnumid">0</span><?php echo yii::t('app','...个菜品厨打已经成功');?></br></br>
                                                            <span style="color:red;" id="notsurenumid">0</span><?php echo yii::t('app','...个菜品正在打印');?></br></br>
                                                            <span style="color:red;" id="errornumid">0</span><?php echo yii::t('app','...个菜品厨打失败，');?></br>
                                                            <?php echo yii::t('app','请点击“确定”按钮，去订单页面查看厨打失败产品，');?></br>
                                                            <?php echo yii::t('app','确认打印机无故障后，点击打印失败菜品后面的“操作”按钮下的“重新厨打”按钮。');?>
                                                        </div>
                                                </div>
                                                <?php else:?>
                                                <div class="modal-body">
                                                        <div class="form-actions fluid" id="product_panel">
                                                            
                                                            <?php echo $ret['msg'];?></br>
                                                            
                                                        </div>
                                                </div>
                                                <?php endif;?>
                                                <input class="form-control" name="additionnames" id="additionids" type="hidden" value="">
                                                <div class="modal-footer">
                                                        <input type="button" class="btn green" id="btn-print-roll" value="<?php echo yii::t('app','确 定');?>">
                                                </div>

                                            <script>
                                            var interval;
                                            var waitingsecond=30;
                                            var retstatus="<?php echo $ret['status'];?>";                                  
                                            
                                            $(document).ready(function() {
                                                clearTimeout(interval);
                                                if(retstatus)
                                                {
                                                    interval = setInterval(printStatus,"1000"); 
                                                }                                                
                                            });
                                            //全部成功自动刷新，有失败任务，停止刷新页面， 
                                            //点击确定，回滚失败的任务单品打印状态，提示逐个重新厨打。
                                            //在刷新页面，用local.href="";  
                                             $('#btn-print-roll').click(function(){
                                                 location.href="<?php echo $this->createUrl('defaultOrder/order',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$orderId));?>";
                                             });
                                             function printStatus(){
                                                                                                
                                                $.get('<?php echo $this->createUrl('defaultOrder/printKitchenResultAll',array('companyId'=>$this->companyId,'orderId'=>$orderId));?>/timenum/'+waitingsecond,
                                                    function(data){
                                                        //alert(data.notsurenum);
                                                        $("#minustimes").html(waitingsecond);
                                                        $("#successnumid").html(data.successnum);
                                                        $("#errornumid").html(data.errornum);
                                                        $("#notsurenumid").html(data.notsurenum);

                                                        if(data.finished && data.errornum==0 && data.notsurenum==0)
                                                        {
                                                            //all success
                                                            //location.href= order
                                                            clearTimeout(interval);
                                                            location.href="<?php echo $this->createUrl('defaultOrder/order',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$orderId,'syscallId'=>$callId));?>";
                                                        }

                                                        if(waitingsecond<0)
                                                        {                                                       
                                                            $("#notsurenumid").html(0);
                                                            $("#errornumid").html(data.errornum+data.notsurenum);
                                                        }

                                                    },'json');
                                                waitingsecond--;
                                                //alert(waitingsecond);
                                                //30s后还有任务没有返回，就作为失败处理。
                                                if(waitingsecond<0)
                                                {
                                                    clearTimeout(interval);                                                    
                                                }                                                
                                            }
                                            </script>