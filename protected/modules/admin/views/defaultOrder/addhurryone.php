                       	
                                        	<?php $form=$this->beginWidget('CActiveForm', array(
                                                        'id'=>'hurry_form',
                                                        'action' => $this->createUrl('defaultOrder/addHurryOne',array('companyId'=>$this->companyId,'orderDetailId'=>$orderRetreat->order_detail_id)),
                                                        'enableAjaxValidation'=>true,
                                                        'enableClientValidation'=>true,
                                                        'clientOptions'=>array(
                                                                'validateOnSubmit'=>false,
                                                        ),
                                                        'htmlOptions'=>array(
                                                                'class'=>'form-horizontal'
                                                        ),
                                                )); ?>
                                                <div class="modal-header">
                                                    <h4> <?php echo $productname;?> </h4>
                                                    
                                                </div>
                                                <div class="modal-body">
                                                                <div class="btn-group" data-toggle="buttons" style="margin: 5px;border: 1px solid red;background: rgb(245,230,230);">
                                                                 确认进行催菜操作！！！
                                                                </div>
                                                                
                                                    
                                                </div>
                                                
                                                <?php echo $form->hiddenField($orderRetreat,'order_detail_id',array('class'=>'form-control')); ?>
                                                
                                                <div class="modal-footer">
                                                        <button type="button" class="btn default" id="create_btn_close_retreat"><?php echo yii::t('app','取 消');?></button>
                                                        <input type="button" class="btn green" id="create_btn_add_retreat" value="<?php echo yii::t('app','确 定');?>">
                                                </div>

                                                <?php $this->endWidget(); ?>
                                        
                    <script type="text/javascript">
                        var layer_retreat_printresult=0;
                        $(document).ready(function(){                            
                            $("#site_number").text($("#selectproductnumforhurry").val());
                        });
                        $('#OrderRetreat_retreat_id').change(function(){
                            var id = $(this).val();                            
                                $.ajax({
                                        url:'<?php echo $this->createUrl('defaultOrder/retreatTip',array('companyId'=>$this->companyId));?>/id/'+id,
                                        type:'GET',
                                        dataType:'json',
                                        success:function(result){                                                                                                                                       
                                                $('#OrderRetreat_retreat_memo').val(result.cp); 
                                        }
                                });                            
                        });
                        
//                        $('#create_btn_add_retreat').on(event_clicktouchstart,function(){                            
//                           // var id = $(this).val();                            
//                                $.ajax({
//                                        'type':'POST',
//					'dataType':'json',
//					'data':$('#retreat_form').serialize(),
//					'url':$('#retreat_form').attr('action'),
//                                        success:function(result){                                                                                                                                       
//                                                alert(result.msg);
//                                                var $modal=$('#portlet-config');
//                                                $modal.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/retreatProduct',array('companyId'=>$this->companyId,'id'=>$orderRetreat->order_detail_id));?>'
//                                                ,'', function(){
//                                                  //$modal.modal();
//                                                  $('#portlet-config2').modal('hide');
//                                                });                                                
//                                        }
//                                });                            
//                        });
                        
//                        $('#create_btn_add_retreat').on(event_clicktouchstart,function(){                            
//                           var orderid=$(".selectProduct").attr("orderid");                            
//                                $.ajax({
//                                        'type':'POST',
//					'dataType':'json',
//					'data':$('#retreat_form').serialize(),
//					'url':$('#retreat_form').attr('action'),
//                                        success:function(result){
//                                            alert(result.msg);
//                                            if(result.status=="1")
//                                            {
//                                                $('#orderdetailauto').load('<?php echo $this->createUrl('defaultOrder/orderPartial',array('companyId'=>$this->companyId));?>/orderId/'+orderid);
////                                              //$('#portlet-config').hide();
//                                                layer.close(layer_index_retreatbox);
//                                                layer_index_retreatbox=0;
//                                                
//                                            }
//                                                                                                
//                                        }
//                                });                            
//                        });
                        $('#create_btn_add_retreat').on(event_clicktouchstart,function(){                            
                           var orderid=$(".selectProduct").attr("orderid");
                            var padid="0000000046";
                            if (typeof Androidwymenuprinter == "undefined") {
                                alert("找不到PAD设备");
                                //return false;
                            }else{
                                var padinfo=Androidwymenuprinter.getPadInfo();
                                padid=padinfo.substr(10,10);
                            }
                            var retreatnum="2";
                            var othermemo="长时间没上菜！！";
                            var isall="0";
                           //var retreatnum=parseInt($("#site_number").text());
                           //var allnum=parseInt($("#selectproductnumforhurry").val());
                           //lert(retreatnum);alert(allnum);
//                            var isall=0;
//                            if(retreatnum<1)
//                            {
//                                alert("数量不对");
//                                return;
//                            }
//                            if(allnum <= retreatnum)
//                            {
//                                isall=1;
//                                retreatnum=allnum;
//                            }
                           
                           //alert(isall);
                           var orderdetailid="<?php echo $orderRetreat->order_detail_id; ?>";
                           var retreatid=$("label[class='selectRetreat btn btn-default active']").attr("retreatid");
                           var url="<?php echo $this->createUrl('defaultOrder/addHurryOne',array('companyId'=>$this->companyId,'orderDetailId'=>$orderRetreat->order_detail_id)); ?>";
                           //var othermemo=$.trim($("label[class='selectRetreat btn btn-default active']").text())+$("#OrderRetreat_other").val();
                           //alert(othermemo);return;
                           //alert(url);alert(othermemo);alert(retreatnum);alert(paymethodid);
                                $.ajax({
                                        'type':'POST',
					'dataType':'json',
					'data':{"retreatnum":retreatnum,"othermemo":othermemo,"retreatid":retreatid,"isall":isall,"padid":padid},
					'url':url,
                                        success:function(result){
                                            var printresultfail=false;
                                            var printresulttemp;
                                            var successjobids="0";
                                            //alert(result.msg);
                                            data=result;//alert("数量不对");
                                            if(data.status){
                                                    //$('#orderdetailauto').load('<?php echo $this->createUrl('defaultOrder/orderPartial',array('companyId'=>$this->companyId));?>/orderId/'+orderid);                                
                                                    var layer_flash_index = layer.load(0, {shade: [0.3,'#fff']});
                                                    $.each(data.jobs,function(skey,svalue){                                        
                                                        detaildata=svalue.split("_");
                                                        if(detaildata[0]=="0")//继续打印
                                                        {
                                                            printresulttemp=Androidwymenuprinter.printNetJob(data.dpid,detaildata[1],detaildata[2]);                                                            
                                                            ////printresulttemp=false;
                                                            if(printresulttemp)
                                                            {
                                                                data.jobs[skey]="1_"+svalue.substring(2);
                                                            }else{
                                                                printresultfail=true;                                                
                                                            }
                                                        }
                                                     }); 
                                                     layer.close(layer_flash_index); 
                                                    if(!printresultfail)
                                                    {
                                                        alert("打印成功！");
                                                    }   
                                                            //alert("可能有打印失败，请去打印机处确认，如果失败，请去收银台查看并重打！");
                                                    $.each(data.jobs,function(skey,svalue){                                        
                                                        detaildata=svalue.split("_");
                                                        if(detaildata[0]=="1")
                                                        {
                                                            successjobids=successjobids+","+detaildata[1];                                                    
                                                        }
                                                    });
                                                    //如果有失败任务就打开对话框
                                                    if(printresultfail)
                                                    {
                                                        $('#printRsultListdetailsub').load('<?php echo $this->createUrl('defaultOrder/getFailPrintjobs',array('companyId'=>$this->companyId));?>/orderId/'+data.orderid+"/jobId/"+successjobids);
                                                        if(layer_index_printresult!=0)
                                                        {
                                                            layer.close(layer_index_printresult);
                                                            layer_index_printresult=0;
                                                           //return;
                                                        }
                                                        layer_index_printresult=layer.open({
                                                            type: 1,
                                                            shade: false,
                                                            title: false, //不显示标题
                                                            area: ['30%', '70%'],
                                                            content: $('#printRsultListdetail'),//$('#productInfo'), //捕获的元素
                                                            cancel: function(index){
                                                                layer.close(index);
                                                                layer_index_printresult=0;                                                                                                     
                                                            }
                                                        });
                                                    }else{
                                                        $.ajax({
                                                            url:'<?php echo $this->createUrl('defaultOrder/saveFailPrintjobs',array('companyId'=>$this->companyId));?>/orderId/'+data.orderid+'/jobId/'+successjobids,
                                                            type:'GET',
                                                            timeout:5000,
                                                            cache:false,
                                                            async:false,
                                                            dataType: "json",
                                                            success:function(data){
                                                                //alert(msg);防止前台开台，但是后台结单或撤台了，就不能继续下单
                                                                //if(!(msg.status == "1" || msg.status == "2" || msg.status == "3"))

                                                            },
                                                            error: function(msg){

                                                            },
                                                            complete : function(XMLHttpRequest,status){
                                                                if(status=='timeout'){

                                                                }
                                                            }
                                                        });
                                                    }
                                                }//alert("数量不对11");
                                        },
                                        error: function(msg){
                                            var data=msg;
                                            alert("保存失败2");
                                            //alert(data.msg);
                                        }
                                });
                                layer.close(layer_index_hurrybox);
                                layer_index_hurrybox=0;
                                layer.close(layer_index1);
                                layer_index1=0;
                        });
                        //create_btn_close_retreat
                        $('#create_btn_close_retreat').on(event_clicktouchstart,function(){   
                            layer.close(layer_index_hurrybox);
                            layer_index_hurrybox=0;
                        });
                        
                        $('#open_site_plus').on(event_clicktouchend,function(){
                            var num = parseInt($("#site_number").text());
                                 num = num + 1;
                                 $("#site_number").text(num);                          
                         });

                         $('#open_site_minus').on(event_clicktouchend,function(){
                            var num = parseInt($("#site_number").text());
                                 num = num - 1;
                                 if(num < 0)
                                     num=0;
                                 $("#site_number").text(num);                          
                         });
                    </script>