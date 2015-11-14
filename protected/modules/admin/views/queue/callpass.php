                        <tr class="queueinfolist">                                
                                <td colspan="6" style="text-align:right;">                                                                                
                                    <a id="queue_call_btn" class="btn blue" style="margin-right: 9%;"><i class="fa fa-archive"></i>排队叫号>></a>
                                </td>
                        </tr> 
                    <?php 
                    if(empty($queueModels))
                    {   return; }
                        $rowi=0;
                        $rowd=0;
                        foreach ($queueModels as $model):
                            $rowd=$rowi%3;
                            if($rowd==0):
                            ?>                                                       
                            <tr class="queueinfolist">
                                <td style="width:23%;font-size:15px;"><?php echo $model["queue_no"];?></td>
                                <td style="width:10%;">
                                    <div class="imgeat2" lid="<?php echo $model["lid"]; ?>" style="width:30%;float:left;">
                                        <img src="/wymenuv2/img/queue/eat.png" style="width:60px;padding:5px;margin:0 5px 0 5px;">
                                    </div>
                                </td>
                            <?php elseif($rowd==1): ?>
                                <td style="width:23%;font-size:15px;"><?php echo $model["queue_no"];?></td>
                                <td style="width:10%;">
                                    <div class="imgeat2" lid="<?php echo $model["lid"]; ?>" style="width:30%;float:left;">
                                        <img src="/wymenuv2/img/queue/eat.png" style="width:60px;padding:5px;margin:0 5px 0 5px;">
                                    </div>
                                </td>
                            <?php elseif($rowd==2): ?>
                                <td style="width:23%;font-size:15px;"><?php echo $model["queue_no"];?></td>
                                <td style="width:10%;">
                                    <div class="imgeat2" lid="<?php echo $model["lid"]; ?>" style="width:30%;float:left;">
                                        <img src="/wymenuv2/img/queue/eat.png" style="width:60px;padding:5px;margin:0 5px 0 5px;">
                                    </div>
                                </td>                                
                            </tr>
                        <?php 
                        endif;
                        $rowi++;
                        endforeach;
                        if($rowd==0):?>
                                <td style="width:23%;font-size:15px;">00000</td>
                                <td style="width:10%;">
                                    <div class="imgeat2" lid="0000000000" style="width:30%;float:left;">
                                        <img src="/wymenuv2/img/queue/eat.png" style="width:60px;padding:5px;margin:0 5px 0 5px;">
                                    </div>
                                </td>
                                <td style="width:23%;font-size:15px;">00000</td>
                                <td style="width:10%;">
                                    <div class="imgeat2" lid="0000000000" style="width:30%;float:left;">
                                        <img src="/wymenuv2/img/queue/eat.png" style="width:60px;padding:5px;margin:0 5px 0 5px;">
                                    </div>
                                </td>                                
                            </tr>
                        <?php elseif($rowd==1): ?>
                                <td style="width:23%;font-size:15px;">00000</td>
                                <td style="width:10%;">
                                    <div class="imgeat2" lid="0000000000" style="width:30%;float:left;">
                                        <img src="/wymenuv2/img/queue/eat.png" style="width:60px;padding:5px;margin:0 5px 0 5px;">
                                    </div>
                                </td>                                
                            </tr>
                        <?php endif; ?>

                        <script language="JavaScript" type="text/JavaScript">
                        $('.imgeat2').live(event_clicktouchstart,function(){
                            var lid=$(this).attr("lid");
                            var dpid="<?php echo $companyId; ?>";
                            //alert(lid);
                            if(lid=="0000000000")
                            {
                                return;
                            }
                            $.ajax({
                                url:"/wymenuv2/admin/queue/setQueueStatus/companyId/"+dpid+"/stlid/0000000000/splid/0000000000/lid/"+lid+"/status/2",
                                type:'GET',
                                timeout:5000,
                                cache:false,
                                async:false,
                                dataType: "json",
                                success:function(msg){
                                    $('#queue_pass_list').hide();
                                    $('#queue_call_list').show();
                                },
                                error: function(msg){
                                    alert("网络可能有问题，再试一次！");
                                    //btnlock=false;
                                },
                                complete : function(XMLHttpRequest,status){
                                    if(status=='timeout'){
                                        alert("网络可能有问题，再试一次！");                                            
                                    }
                                    //btnlock=false;
                                }
                            });                            
                        });
                        </script>
                