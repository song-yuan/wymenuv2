	<!-- BEGIN PAGE -->  
        <input type="hidden" value="<?PHP echo count($orderPrintjobs); ?>" id="failprintjobnum"> 
        <ul>
            
        <?php foreach ($orderPrintjobs as $orderPrintjob):
     //var_dump($orderPrintjob);exit;
            ?>
            <li>                                    
                任务<?php echo $orderPrintjob['create_at']; ?>打印失败，打印机位置(<?php if(!empty($orderPrintjob->printer->name)) echo $orderPrintjob->printer->name; ?>)
                <input style="float:right;" jobid="<?php echo $orderPrintjob->jobid; ?>" 
                       address="<?php echo $orderPrintjob->address; ?>" type="button" class="btn red reprintjob" value="重新打印">
            </li>
        <?php endforeach; ?>   
        </ul>
        	<!-- END PAGE -->                  
                    <script type="text/javascript">
                        var successjoblist="0";
                        var jobsuccess=0;
                        $(document).ready(function(){
                            $('#failprintjobs').text($('#failprintjobnum').val());
                            
                        });
                        
                        $('.reprintjob').on("click",function(){
                            var liobj=$(this).parent();
                            liobj.hide();
                            var jobid=$(this).attr("jobid");
                            var address=$(this).attr("address");
                            var dpid="<?php echo $dpid; ?>";
                            var orderid="<?php echo $orderid; ?>";
                            var jobnum=parseInt($('#failprintjobnum').val());
                            //alert(jobid);alert(address);alert(dpid);alert(orderid);
                            var printresulttemp2=false;
                            printresulttemp2=Androidwymenuprinter.printNetJob(dpid,jobid,address);
                            /////printresulttemp2=true;
                            if(printresulttemp2)
                            {
                                    successjoblist=successjoblist+","+jobid;
                                    jobsuccess=jobsuccess+1;
                            }
                            if(!printresulttemp2)
                            {
                                alert("再试一次！");
                                liobj.show();
                            }
                                if(jobnum==jobsuccess && printresulttemp2)
                                {
                                    //alert(successjoblist);
                                    //$('#printRsultListdetailsub').load('/wymenuv2/product/getFailPrintjobs/companyId/'+dpid+'/orderId/'+orderid+'/padtype/2/jobId/'+successjoblist);
                                    $.ajax({
                                        url:'/wymenuv2/product/saveFailPrintjobs/companyId/'+dpid+'/orderId/'+orderid+'/padtype/2/jobId/'+successjoblist,
                                        type:'GET',
                                        timeout:5000,
                                        cache:false,
                                        async:false,
                                        dataType: "json",
                                        success:function(data){
                                            //alert(msg);防止前台开台，但是后台结单或撤台了，就不能继续下单
                                            //if(!(msg.status == "1" || msg.status == "2" || msg.status == "3"))
                                            if(data.status)
                                            {
                                                layer.close(layer_index_printresult);
                                            }
                                        },
                                        error: function(msg){
                                            layer.close(layer_index_printresult);
                                            
                                        },
                                        complete : function(XMLHttpRequest,status){
                                            if(status=='timeout'){
                                                layer.close(layer_index_printresult);
                                            }
                                        }
                                    });
                                    layer.close(layer_index_printresult);
                                }
                            
//                            if(!printresulttemp2)
//                            {
//                                alert("打印失败，请检查打印机和网络后重试！");
//                            }else{
//                                $('#printRsultListdetailsub').load('<?php echo $this->createUrl('defaultOrder/getFailPrintjobs',array('companyId'=>$dpid));?>/orderId/'+orderid+'/jobId/'+jobid);
//                            }
                       });                      
                        
                    </script>