                <div class="modal-header">
                    <h4 id="orderaccountprintresult" style="color:red;"> 正在打印结算单... </h4>                                                    
                </div>                
                <div class="modal-footer">
                    <button type="button" class="btn default" id="btn_orderaccount_cancel" style="width:10em;">取消结单</button>
                    <button type="button" class="btn green-stripe" id="btn_orderaccount_reprint" style="width:10em;">重新打印</button>  
                    <button type="button" class="btn green" id="btn_orderaccount_sure" style="width:10em;">确定结单</button>    
                </div>
        <script type="text/javascript">
            var data=eval('(<?php echo json_encode($printList); ?>)');
            $(document).ready(function(){
                orderaccountprint();
            });
            
            $('#btn_orderaccount_sure').on(event_clicktouchstart,function(){                            
               if(ispaybuttonclicked)
                {
                    return;
                }else{
                    ispaybuttonclicked=true;
                }
                //accountManul
                //判断找零是否大于现金
                //要将payDiscountAccount的几个id、折扣、金额、payMinusAccount的金额、cancel_zero的金额传递过去
//                var notpaydetail="";
//                var payCashAccount= parseFloat($("#payCashAccount").text().replace(",","")) - parseFloat($("#payChangeAccount").text().replace(",",""));
//                if(payCashAccount<0)
//                {
//                    alert("金额有误");
//                    ispaybuttonclicked=false;
//                    return false;
//                }
//                 //改变order实收，打折等注释
//                var ordermemo="";
//                notpaydetail=$("#payDiscountAccount").attr("disid")+"|"+
//                                $("#payDiscountAccount").attr("disnum")+"|"+
//                                $("#payDiscountAccount").attr("dismoney")+"|";
//                var payDiscountAccount=$("#payDiscountAccount").text()
//                if(payDiscountAccount!="100%")
//                {
//                    ordermemo=ordermemo+" 折扣"+payDiscountAccount;
//                }
//                var payMinusAccount=$("#payMinusAccount").text()
//                if(payMinusAccount!="0.00")
//                {
//                    ordermemo=ordermemo+" 优惠"+payMinusAccount;
//                }
//                notpaydetail=notpaydetail+payMinusAccount+"|";
//                if($("#cancel_zero").hasClass("edit_span_select_zero"))
//                {
//                    ordermemo=ordermemo+" 抹零";
//                }
//                notpaydetail=notpaydetail+$("#payCancelZero").text();
//                //alert(notpaydetail);return;
//                 //存数order order_pay 0现金，4会员卡，5银联                         
//                 //写入会员卡消费记录，会员卡总额减少
                var orderid=$(".selectProduct").attr("orderid");
//                //var payCashAccount=$("#payCashAccount").text();
//                //var payChangeAccount=$("#payChangeAccount").text();
//                var payShouldAccount=$("#payShouldAccount").text();
//                var payOriginAccount=$("#payOriginAccount").text();
//                var payHasAccount=parseFloat($("#order_has_pay").text().replace(",",""));
//                var payRealityAccount=$("#payRealityAccount").text();
//                var payMemberAccount=$("#payMemberAccount").text();
                var cardno=$("#payMemberAccount").attr("cardno");
//                var payUnionAccount=$("#payUnionAccount").text();
//                var payOthers=$("#payOthers").text();
//                var otherdetail=$("#payOthers").attr("detail");
//                if(parseFloat(payRealityAccount.replace(",","")) < parseFloat(payShouldAccount.replace(",","")))
//                {
//                    alert("收款不够");
//                    ispaybuttonclicked=false;
//                    return false;
//                }
//                var typeId=$('li[class="tabSite slectliclass"]').attr('typeid');
                //var isaccount=false;
//                layer.close(layer_index2);
//                layer_index2=0;
//                bootbox.confirm("<?php echo yii::t('app','确定结单吗？');?>", function(result) {                    
//                    if(result){
                        var url="<?php echo $this->createUrl('defaultOrder/orderAccount',array('companyId'=>$this->companyId));?>/orderid/"+orderid+"/orderstatus/4/cardno/"+cardno;
//                        var sendjson='paycashaccount='+payCashAccount+
//                                    '&paymemberaccount='+payMemberAccount+
//                                    '&payunionaccount='+payUnionAccount+
//                                    '&ordermemo='+ordermemo+
//                                    '&payshouldaccount='+payShouldAccount+
//                                    '&payothers='+payOthers+
//                                    '&payoriginaccount='+payOriginAccount+
//                                    '&payotherdetail='+otherdetail+
//                                    '&notpaydetail='+notpaydetail; 
                            //alert(sendjson);
//                            return;
                        $.ajax({
                            url:url,
                            type:'POST',
                            data:public_account_sendjson,//CF
                            //async:false,
                            dataType: "json",
                            success:function(msg){
                                var data=msg;
                                if(data.status){
                                    
                                    //alert(data.msg);
                                    //刷新座位页面                                    
                                    //alert(typeId);
//                                    tabcurrenturl='<?php echo $this->createUrl('defaultSite/showSite',array('companyId'=>$this->companyId));?>/typeId/'+typeId;
//                                    $('#tabsiteindex').load(tabcurrenturl);
//                                    //手动改变座位的状态和颜色
                                    $(".modalaction[sid="+gsid+"][istemp="+gistemp+"]").removeClass("bg-yellow");
                                    $(".modalaction[sid="+gsid+"][istemp="+gistemp+"]").removeClass("bg-blue");
                                    $(".modalaction[sid="+gsid+"][istemp="+gistemp+"]").removeClass("bg-green");
                                    $(".modalaction[sid="+gsid+"][istemp="+gistemp+"]").attr("status","4");
                                    $(".modalaction[sid="+gsid+"][istemp="+gistemp+"]").find("div").hide();
                                    //排队的状态定时刷新//////////////////
//                                    $('#site_row').show();
//                                    $('#tabsiteindex').show();
//                                    $('#order_row').hide();
                                    sitevisible();
                                    ispaybuttonclicked=false;
                                    layer.close(layer_index_orderaccountsure);
                                    layer_index_orderaccountsure=0;
                                    layer.close(layer_index2);
                                    layer_index2=0;
                                }else{
                                    //layer.close(layer_index2);
                                    alert(data.msg);
                                    ispaybuttonclicked=false;
//                                    if(layer_index2!=0)
//                                    {
//                                        return;
//                                    }
//                                    layer_index2=layer.open({
//                                        type: 1,
//                                        shade: false,
//                                        title: false, //不显示标题
//                                        area: ['65%', '60%'],
//                                        content: $('#accountbox'),//$('#productInfo'), //捕获的元素
//                                        cancel: function(index){
//                                            layer.close(index);
//                                            layer_index2=0;
//                                        }
//                                    });
                                }
                            },
                            error: function(msg){
                                //layer.close(index);
                                alert("结算失败2！");
                                ispaybuttonclicked=false;
//                                if(layer_index2!=0)
//                                {
//                                    return;
//                                }
//                                layer_index2=layer.open({
//                                    type: 1,
//                                    shade: false,
//                                    title: false, //不显示标题
//                                    area: ['65%', '60%'],
//                                    content: $('#accountbox'),//$('#productInfo'), //捕获的元素
//                                    cancel: function(index){
//                                        layer.close(index);
//                                        layer_index2=0;
//                                    }
//                                });
                            }
                        });

//                    }else{
//                        ispaybuttonclicked=false;
//                        if(layer_index2!=0)
//                        {
//                            return;
//                        }
//                        layer_index2=layer.open({
//                            type: 1,
//                            shade: false,
//                            title: false, //不显示标题
//                            area: ['65%', '60%'],
//                            content: $('#accountbox'),//$('#productInfo'), //捕获的元素
//                            cancel: function(index){
//                                layer.close(index);
//                                layer_index2=0;
//                            }
//                        });
//                    }
//                });
                //返回座位列表页面
//                ispaybuttonclicked=false;
//                layer.close(layer_index_orderaccountsure);
//                layer_index_orderaccountsure=0;
            });
            
            $('#btn_orderaccount_reprint').on(event_clicktouchstart,function(){                            
               orderaccountprint();
            });
            //create_btn_close_retreat
            $('#btn_orderaccount_cancel').on(event_clicktouchstart,function(){   
                layer.close(layer_index_orderaccountsure);
                layer_index_orderaccountsure=0;
            });                        

            function orderaccountprint()
            {
                var printresult=false;
                //data=eval('(<?php echo json_encode($printList); ?>)');
                //alert(data.address);
                if(data.status){
                    //var index = layer.load(0, {shade: [0.3,'#fff']});
                    for(var itemp=1;itemp<4;itemp++)
                    {
                        if(printresult)
                        {
                            break;
                        }
                        var addressdetail=data.address.split(".");
                        if(addressdetail[0]=="com")
                        {
                            var baudrate=parseInt(addressdetail[2]);
                            printresult=Androidwymenuprinter.printComJob(data.dpid,data.jobid,addressdetail[1],baudrate);
                        }else{
                            printresult=Androidwymenuprinter.printNetJob(data.dpid,data.jobid,data.address);
                            //printresult=true;
                        }                                                                        
                    }
                    if(!printresult)
                    {
                        $("#orderaccountprintresult").text("结算单打印失败，请重试.....");
                        $("#orderaccountprintresult").css("color","red");
                    }else{
                        $("#orderaccountprintresult").text("结算单打印成功");
                        $("#orderaccountprintresult").css("color","green");
                    }
                }else{
                    $("#orderaccountprintresult").text("网络可能有问题，请检查网络.....");
                    $("#orderaccountprintresult").css("color","red");
                }                
            }
        </script>