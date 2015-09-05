	<!-- BEGIN PAGE -->  
        <input type="hidden" value="<?PHP echo count($orderPrintjobs); ?>" id="failprintjobnum"> 
        <ul>            
        <?php foreach ($orderPrintjobs as $orderPrintjob):
     //var_dump($orderPrintjob);exit;
            ?>
            <li>                                    
                任务<?php echo $orderPrintjob['jobid']; ?>打印失败，打印机IP(<?php echo $orderPrintjob['address']; ?>)
                <input style="float:right;padding: 5px;background-color:greenyellow; " jobid="<?php echo $orderPrintjob['jobid']; ?>" 
                       address="<?php echo $orderPrintjob['address']; ?>" type="button" class="reprintjob" value="重新打印">
            </li>
        <?php endforeach; ?>   
        </ul>
        	<!-- END PAGE -->                  
                    <script type="text/javascript">
//                        $(document).ready(function(){
                            $('#failprintjobs').text($('#failprintjobnum').val());
                            if($('#failprintjobnum').val()=="0")
                            {
                                layer.close(layer_index_printresult2);
                            }
//                        });
                        
                        $('.reprintjob').on("click",function(){
                            var jobid=$(this).attr("jobid");
                            var address=$(this).attr("address");
                            var dpid="<?php echo $dpid; ?>";
                            var orderid="<?php echo $orderid; ?>";
                            alert(jobid);alert(address);alert(dpid);alert(orderid);
                            var printresulttemp2=false;
                            printresulttemp2=Androidwymenuprinter.printNetJob(dpid,jobid,address);
                            if(!printresulttemp2)
                            {
                                alert("打印失败，请检查打印机和网络后重试！");
                            }else{
                                $('#printRsultListdetailsub').load('/wymenuv2/product/getFailPrintjobs/companyId/'+dpid+'/orderId/'+orderid+'/padtype/2/jobId/'+jobid);
                            }
                       });                      
                        
                    </script>