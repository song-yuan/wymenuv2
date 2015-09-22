	<!-- BEGIN PAGE -->  
        <input type="hidden" value="<?PHP echo count($orderPrintjobs); ?>" id="failprintjobnum"> 
        <ul>            
        <?php foreach ($orderPrintjobs as $orderPrintjob):
     //var_dump($orderPrintjob);exit;
            ?>
            <li style="height:40px;">                                    
                任务<?php echo $orderPrintjob->create_at; ?>打印失败，打印机位置(<?php if(!empty($orderPrintjob->printer->name)) echo $orderPrintjob->printer->name; ?>)
                <input style="float:right;padding: 10px 25px;background-color:greenyellow; " jobid="<?php echo $orderPrintjob->jobid; ?>" 
                       address="<?php echo $orderPrintjob->address; ?>" type="button" class="reprintfailjobbutton" value="重新打印">
            </li>
        <?php endforeach; ?>   
        </ul>
        	<!-- END PAGE -->                  
                    <script type="text/javascript">
                        var joblock=false;
                        var successjoblist="0";
                        var jobsuccess=0;
//                        $(document).ready(function(){
                            $('#failprintjobs').text($('#failprintjobnum').val());
                            if($('#failprintjobnum').val()=="0")
                            {
                                layer.close(layer_index_printresult2);
                            }
//                        });
                        
                        $('.reprintfailjobbutton').on("click",function(){
                            var liobj=$(this).parent();
                            liobj.hide();
//                            return;
//                            if(joblock)
//                            {
//                                return;
//                            }else{
//                                joblock=true;
//                            }
                            var jobid=$(this).attr("jobid");
//                            alert(jobid);
                            var address=$(this).attr("address");
                            var dpid="<?php echo $dpid; ?>";
                            var orderid="<?php echo $orderid; ?>";
                            var printresulttemp2=false;
                            var index = layer.load(0, {shade: [0.3,'#fff']});
                            var jobnum=parseInt($('#failprintjobnum').val());                            
                            Androidwymenuprinter.printNetPing(address,10);
                            setTimeout(Androidwymenuprinter.printNetPing(address,10)
                                    ,400);
                            var printfun=function(dpid,jobid,orderid)
                            {
                                printresulttemp2=Androidwymenuprinter.printNetJob(dpid,jobid,address); 
                                /////printresulttemp2=true;
                                if(printresulttemp2)
                                {
//                                    if(jobnum>1)
//                                    {
                                        //$('#printRsultListdetailsub').load('/wymenuv2/product/getFailPrintjobs/companyId/'+dpid+'/orderId/'+orderid+'/padtype/2/jobId/'+jobid);
                                        successjoblist=successjoblist+","+jobid;
                                        jobsuccess=jobsuccess+1;
//                                    }                                    
                                }
                                
                                layer.close(index);
//                                joblock=false;
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
                                            //layer.close(layer_index_printresult2);
                                            if(!data.status)
                                            {
                                                alert("保存失败44");
                                            }
                                        },
                                        error: function(msg){
                                            //layer.close(layer_index_printresult2);
                                            alert("网络错误44");
                                        },
                                        complete : function(XMLHttpRequest,status){
                                            if(status=='timeout'){
                                                //layer.close(layer_index_printresult2);
                                                alert("超时44");
                                            }
                                        }
                                    });
                                    layer.close(layer_index_printresult2);
                                }
                            }
                            setTimeout(printfun(dpid,jobid,orderid),1000);                            
                       });                      
                        
                    </script>