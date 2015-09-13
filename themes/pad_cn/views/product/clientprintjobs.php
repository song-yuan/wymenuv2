	<!-- BEGIN PAGE -->  
        <input type="hidden" value="<?PHP echo count($orderPrintjobs); ?>" id="failprintjobnum"> 
        <ul>            
        <?php foreach ($orderPrintjobs as $orderPrintjob):
     //var_dump($orderPrintjob);exit;
            ?>
            <li style="height:40px;">                                    
                任务<?php echo $orderPrintjob->jobid; ?>打印失败，打印机位置(<?php if(!empty($orderPrintjob->printer->name)) echo $orderPrintjob->printer->name; ?>)
                <input style="float:right;padding: 10px 25px;background-color:greenyellow; " jobid="<?php echo $orderPrintjob->jobid; ?>" 
                       address="<?php echo $orderPrintjob->address; ?>" type="button" class="reprintfailjobbutton" value="重新打印">
            </li>
        <?php endforeach; ?>   
        </ul>
        	<!-- END PAGE -->                  
                    <script type="text/javascript">
                        var joblock=false;
//                        $(document).ready(function(){
                            $('#failprintjobs').text($('#failprintjobnum').val());
                            if($('#failprintjobnum').val()=="0")
                            {
                                layer.close(layer_index_printresult2);
                            }
//                        });
                        
                        $('.reprintfailjobbutton').on("click",function(){
                            if(joblock)
                            {
                                return;
                            }else{
                                joblock=true;
                            }
                            var jobid=$(this).attr("jobid");
//                            alert(jobid);
                            var address=$(this).attr("address");
                            var dpid="<?php echo $dpid; ?>";
                            var orderid="<?php echo $orderid; ?>";
                            var printresulttemp2=false;
                            var index = layer.load(0, {shade: [0.3,'#fff']});
                            var jobnum=parseInt($('#failprintjobnum').val());
//                            for(var itemp=1;itemp<4;itemp++)
//                            {
                            Androidwymenuprinter.printNetPing(address,10);
                            setTimeout(Androidwymenuprinter.printNetPing(address,10)
                                    ,400);
//                            setTimeout(alert(1)
//                                    ,400);
                            var printfun=function(dpid,jobid,orderid)
                            {
                                printresulttemp2=Androidwymenuprinter.printNetJob(dpid,jobid,address); 
                                //printresulttemp2=true;
                                if(printresulttemp2)
                                {
                                    if(jobnum>1)
                                    {
                                        $('#printRsultListdetailsub').load('/wymenuv2/product/getFailPrintjobs/companyId/'+dpid+'/orderId/'+orderid+'/padtype/2/jobId/'+jobid);
//                                        break;
                                    }                                    
                                }
                                
                                layer.close(index);
                                joblock=false;
                                if(!printresulttemp2)
                                {
                                    alert("再试一次！");
                                }
                                if(jobnum==1 && printresulttemp2)
                                {
    //                                alert(1);
                                    $('#printRsultListdetailsub').load('/wymenuv2/product/getFailPrintjobs/companyId/'+dpid+'/orderId/'+orderid+'/padtype/2/jobId/'+jobid);
                                    layer.close(layer_index_printresult2);
                                }
                            }
                            setTimeout(printfun(dpid,jobid,orderid),1000);                            
                       });                      
                        
                    </script>