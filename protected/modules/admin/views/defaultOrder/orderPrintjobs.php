	<!-- BEGIN PAGE -->  
        <input type="hidden" value="<?PHP echo count($orderPrintjobs); ?>" id="failprintjobnum"> 
        <ul>
            
        <?php foreach ($orderPrintjobs as $orderPrintjob):
     //var_dump($orderPrintjob);exit;
            ?>
            <li>                                    
                任务<?php echo $orderPrintjob['jobid']; ?>打印失败，打印机IP(<?php echo $orderPrintjob['address']; ?>)
                <input style="float:right;" jobid="<?php echo $orderPrintjob['jobid']; ?>" 
                       address="<?php echo $orderPrintjob['address']; ?>" type="button" class="btn red reprintjob" value="重新打印">
            </li>
        <?php endforeach; ?>   
        </ul>
        	<!-- END PAGE -->                  
                    <script type="text/javascript">
                        $(document).ready(function(){
                            $('#failprintjobs').text($('#failprintjobnum').val());
                        });
                        
                        $('.reprintjob').click(function(){
                            var jobid=$(this).attr("jobid");
                            var address=$(this).attr("address");
                            var dpid="<?php echo $dpid; ?>";
                            
                            var printresulttemp=Androidwymenuprinter.printNetJob(dpid,jobid,address);
                            if(!printresulttemp)
                            {
                                alert("打印失败，请检查网络和打印机后重试！");
                            }else{
                                $('#printRsultListdetailsub').load('<?php echo $this->createUrl('defaultOrder/getFailPrintjobs',array('companyId'=>$dpid));?>/orderId/'+data.orderid+'/jobId/'+jobid);
                            }
                       });                      
                        
                    </script>