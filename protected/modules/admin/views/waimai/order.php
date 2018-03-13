<div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
    <div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                    <div class="modal-content">
                            <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h4 class="modal-title">Modal title</h4>
                            </div>
                            <div class="modal-body">
                                    Widget settings form goes here
                            </div>
                            <div class="modal-footer">
                                    <button type="button" class="btn blue">Save changes</button>
                                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                            </div>
                    </div>
                    <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
    </div>
 <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','实体卡'),'url'=>$this->createUrl('entityCard/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','卡查询'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('entityCard/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
<div class="row">   
    <div class="col-md-12">
	<div class="row">
        <div class="col-md-12 col-sm-12">
           <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'Promote',
                    'clientOptions'=>array(
                            'validateOnSubmit'=>true,
                    ),
                    'htmlOptions'=>array(
                            'class'=>'form-inline'
                    ),
            )); ?>
            <div class="form-group more-condition" style="float:left;width:200px;">
                 <div class="input-group" style="width:95%;">
                 <span class="input-group-addon">订单类型</span>
                       <select class="form-control" name="orderType">
                           <option value="1">美团</option>
                           <option value="2">饿了么</option>
                       </select>
                </div>
            </div>
            <div class="input-group" style="float:left;width:700px;margin-bottom:15px;">
                  <span class="input-group-addon">外卖订单号</span><input type="text" name="orderId" class="form-control" style="width:200px;" value=""/>
                  <button type="submit" class="btn green">
                         <i class="fa fa-search">查找 &nbsp;</i>
                  </button>
              </div>
             <?php $this->endWidget(); ?>
         </div>
    <div class="portlet purple box">
    	<div class="portlet-title">
             <div class="caption"><i class="fa fa-group"></i>订单信息</div>
             <div class="actions"></div>
        </div>
        <div class="portlet-body" >
            <div class="row">
                <?php if($hasOrder):?>
                <p>该订单已经存在</p>
                <?php else:?>
                <?php echo $data;?>
                <?php endif;?>
         	</div>
        </div>
        </div> 
    </div>
	</div>
</div>

<script>
    $(function(){
        $("input[name=num]").focus();
        
        $(".info_item").hide();
        $(".info_item").eq(0).show();
        $("#attr_info li a").click(function(){
            $(this).parent("li").siblings("li").removeClass("active");
            $(this).parent("li").addClass("active");
            //全部隐藏
            $(".info_item").hide();
            //当前对应的显示
           $("#"+$(this).attr("data-target")).show();
        });
 
    });
    
$('.accountno').click(function() {
          //alert(111);
        $('#orderdetaildiv').remove();
        var orderid = $(this).attr('orderid');
        var accountno = $(this).attr('accountno');
        var originalp = $(this).attr('originalp');
        var shouldp = $(this).attr('shouldp');
        var youhuip = $(this).attr('youhuip');
         //alert(originalp); alert(shouldp);
        var url = "<?php echo $this->createUrl('entityCard/accountDetail',array('companyId'=>$this->companyId));?>/orderid/"+orderid;
        $.ajax({
                   url:url,
                   type:'POST',
                   data:orderid,//CF
                   //async:false,
                   dataType: "json",
                   success:function(msg){
                       var data=msg;
                       if(data.status){
                    //alert(data.msg);
                            var model = data.msg;
                            var change = data.change;
                            var money = data.money;
                            var prodDetailDivAll = '<div id="orderdetaildiv"><div class="contentheadtip font20">账单号：'+accountno+'</div><div class="contenthead font20"><div class="contentdiv"><span>菜品名称</span></div><div class="contentdiv"><span>数量</span></div><div class="clear"></div></div>';
                            var prodDetailEnd = '</div>';
                            var proDetailpayAll = '';
                            for (var i in model){
                                    prodName = model[i].product_name;
                                    prodNum = model[i].all_amount;
                                    setName = model[i].set_name;
                                    var sets = '';
                                    if(setName){
                                            sets = '('+setName+')';
                                            }
                                        //alert(prodName);alert(prodNum);
                                        var prodDetailDivBody = '<div class="contenthead font18"><div class="contentdiv"><span>'+prodName+sets+'</span></div><div class="contentdiv"><span>'+prodNum+'</span></div><div class="clear"></div></div>' 
                                        prodDetailDivAll = prodDetailDivAll + prodDetailDivBody;
                                        }
                                    var proDetailBodyEnd = '<div class="font20 detaildivtip">账单详情</div>'
                                                                                    +'<div class="detailcontent font18"><div class="detaildiv">原价:<span>'+originalp+'</span></div><div class="detaildiv">折后价:<span>'+shouldp+'</span></div><div class="detaildiv">优惠:<span>'+youhuip+'</span></div><div class="clear"></div></div>'
                                                                                    +'<div class="detailcontent font18"><div class="detaildiv">收款现金:<span>'+money+'</span></div><div class="detaildiv">找零:<span>'+change+'</span></div><div class="clear"></div></div>';
                                    //var proDetailDiv = prodDetailDivAll+proDetailBodyEnd;
                                    var proDetailDiv = prodDetailDivAll;//去掉账单收支详情
                                    if(data.allpayment){
                                            var proDetailpayHead = '<div class="font20 detaildivtip">其他支付方式:</div>'
                                            var allpayment = data.allpayment;
                                            var proDetailpaymentall = '';
                                            for (var a in allpayment){
                                                    var name = allpayment[a].name; 
                                                    var nameprice = allpayment[a].pay_amount;
                                                    var paytype = allpayment[a].paytype;
                                                    if(name){
                                                            //alert(name);
                                                            var proDetailpayment = '<div class="detailcontent font18"><div class="detaildiv">'+name+':<span>'+nameprice+'</span></div><div class="clear"></div></div>';

                                                            }else if(paytype){
                                                                    //alert(paytype);
                                                                    var paytypename = '';
                                                                    if (paytype==1){
                                                                            paytypename = '微信支付';
                                                                    }else if(paytype==2){
                                                                            paytypename = '支付宝支付';
                                                                    }else if(paytype==4){
                                                                            paytypename = '会员卡支付';
                                                                    }else if(paytype==5){
                                                                            paytypename = '银联支付';
                                                                    }else if(paytype==9){
                                                                            paytypename = '微信代金券';
                                                                    }else if(paytype==10){
                                                                            paytypename = '微信余额支付';
                                                                    }
                                                                    var proDetailpayment = '<div class="detailcontent font18"><div class="detaildiv">'+paytypename+':<span>'+nameprice+'</span></div><div class="clear"></div></div>';
                                                                    }
                                                    var proDetailpaymentall = proDetailpaymentall + proDetailpayment;
                                                    }
                                            var proDetailpayAll =  proDetailpayHead + proDetailpaymentall + prodDetailEnd;
                                            }
                                    var proDetail = proDetailDiv + proDetailpayAll;
                                    $("#main2").append(proDetail);
            			   layer_zhexiantu=layer.open({
            				     type: 1,
            				     //shift:5,
            				     shade: [0.5,'#fff'],
            				     move:'#main2',
            				     moveOut:true,
            				     offset:['10px','350px'],
            				     shade: false,
            				     title: false, //不显示标题
            				     area: ['auto', 'auto'],
            				     content: $('#main2'),//$('#productInfo'), //捕获的元素
            				     cancel: function(index){
            				         layer.close(index);
            				         layer_zhexiantu=0;
            				     }
            				 });
            			   layer.style(layer_zhexiantu, {
            				   backgroundColor: 'rgba(255,255,255,0.2)',
            				 });  
                          
                       }else{
                           
                       }
                   },
                   error: function(msg){
                       layer.msg('网络错误！！！');
                   }
               });
			   

	        });
</script>