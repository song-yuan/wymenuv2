
                                                <div class="modal-header">
                                                    <h4> 请选择折扣 </h4>                                                    
                                                </div>
                                                <div class="modal-body">
                                                                <div class="btn-group" data-toggle="buttons" style="margin: 5px;border: 1px solid red;background: rgb(245,230,230);">
                                                                    <?php 
                                                                    
                                                                    foreach ($alldiscounts as $discount):
                                                                        
                                                                        ?> 
                                                                    
                                                                    <label discountid="<?php echo $discount->lid; ?>" discountnum="<?php echo $discount->discount_num; ?>" group="tastegroup_1" class="discountList btn btn-default">
                                                                        <input type="checkbox" class="toggle"> <?php echo $discount->discount_name;?>
                                                                    </label>                                                                    
                                                                    <?php endforeach;?> 
                                                                </div>
                                                                
                                                    
                                                </div>                                               
                                                
                                                <div class="modal-footer">
                                                        <button type="button" class="btn default" id="create_btn_close_retreat"><?php echo yii::t('app','取 消');?></button>
                                                        <input type="button" class="btn green" id="create_btn_add_retreat" value="<?php echo yii::t('app','确 定');?>">
                                                </div>

                    <script type="text/javascript">
                        
                        $('#create_btn_add_retreat').on(event_clicktouchstart,function(){                            
                           //var orderid=$(".selectProduct").attr("orderid");
                           var discountid=$("label[class='discountList btn btn-default active']").attr("discountid");
                           if($("label[class='discountList btn btn-default active']").length < 1)
                           {
                               alert("请选择折扣");
                               return;
                           }
                           var discountnum=$("label[class='discountList btn btn-default active']").attr("discountnum");                           
                           //$('#payDiscountAccount').text(discountnum);
                           $("#payDiscountAccount").text((discountnum*100)+"%");
                           $("#payDiscountAccount").attr("disid",discountid);
                           $("#payDiscountAccount").attr("disnum",discountnum);
                           
                            //$("#payMinusAccount").text("0.00");
                            var payOriginAccount=parseFloat($("#payOriginAccount").text().replace(",",""));
                            var productDisTotal=parseFloat($("#productDisTotal").val().replace(",",""));
                            var payDiscountAccount=parseFloat($("#payDiscountAccount").text().replace(",",""));
                            var payMinusAccount=parseFloat($("#payMinusAccount").text().replace(",",""));
                            var payHasAccount=parseFloat($("#order_has_pay").text().replace(",",""));
                            var payRealityAccount=$("#payRealityAccount").text();
                            var cancel_zero=$("#cancel_zero").hasClass("edit_span_select_zero");
                            
                            $("#payDiscountAccount").attr("dismoney",(productDisTotal*(1-discountnum)).toFixed(2));
                            //payOriginAccount*payDiscountAccount/100 productDisTotal*payDiscountAccount/100
                            $("#payShouldAccount").text(((payOriginAccount-productDisTotal)+productDisTotal*payDiscountAccount/100 - payMinusAccount-payHasAccount).toFixed(2));
                            if(cancel_zero)
                            {
                                
                                payShouldAccount=$("#payShouldAccount").text();
                                $("#payCancelZero").text("0"+payShouldAccount.substr(payShouldAccount.indexOf("."),3));
                                payShouldAccount=payShouldAccount.substr(0,payShouldAccount.indexOf("."))+".00";
                                $("#payShouldAccount").text(payShouldAccount);
                            }
                            var changeaccount=parseFloat(payRealityAccount.replace(",",""))-parseFloat($("#payShouldAccount").text().replace(",",""));
                            if(changeaccount>0)
                            {
                                $("#payChangeAccount").text(changeaccount.toFixed(2));
                            }else{
                                $("#payChangeAccount").text("0.00");
                            }
                           
                            layer.close(layer_index_selectalldiscount);
                            layer_index_selectalldiscount=0;
                        });
                        //create_btn_close_retreat
                        $('#create_btn_close_retreat').on(event_clicktouchstart,function(){   
                            layer.close(layer_index_selectalldiscount);
                            layer_index_selectalldiscount=0;
                        });                        
                        
                    </script>