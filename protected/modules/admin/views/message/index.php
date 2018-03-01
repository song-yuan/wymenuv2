<style>
    .page-content{
        padding-top:0!important;
    }
    .portlet.box > .portlet-body{
        min-height:auto!important;
    }
    .pay-message{
        margin:10px;
    }
    .pay-message div span{
        color:red;
        font-weight: 900;
    }


</style>
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
    <!-- /.modal -->
    <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <!-- BEGIN PAGE HEADER-->
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','短信套餐购买'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('product/list' , array('companyId' => $this->companyId,'type' => '2',)))));?>

    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box purple">
                <div class="portlet-title">
                    <div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','短信套餐剩余详情');?></div>
                    <div class="actions">
                    	<a href="<?php echo $this->createUrl('message/setcreate' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加');?></a>   
                    </div>	
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                        <?php if ($infos==null): ?>
                        <tr>
                            <td><h3><?php echo yii::t('app','您还没有购买过短信套餐 , 或者 , 您购买的短信套餐已经过期 ! ! !');?></h3></td>
                        </tr>
                        <?php else: ?>
                        <thead>
                            <tr>
                                <th><?php echo yii::t('app','剩余数量/条');?></th>
                                <th ><?php echo yii::t('app','到期时间');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($infos as $key => $info): ?>
                            <tr class="odd gradeX">
                                <td><?php echo yii::t('app',$info['odd_message_no']);?></td>
                                <td><?php echo yii::t('app',$info['downdate_at']);?></td>
                            </tr>
                            <?php endforeach ?>
                            
                        </tbody>
                        <?php endif; ?>
                    </table>
                </div>
                <div class="portlet-title">
                    <div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','短信套餐选择');?></div>
                </div>
                <div class="portlet-body" id="table-manage" style="overflow: hidden;">
                    <div class="row">
                        <?php if ($models==null): ?>
                        <div>
                            <h3>请通知壹点吃公司,设置短信套餐</h3>
                        </div>
                        <?php else: ?>
                        <?php foreach ($models as $key => $model): ?>
                        <div class="col-md-3 pay-message">
                            <div>数量/条 : <span><?php echo $model['all_message_no'] ?></span></div>
                            <div>赠送数量/条 :  <span><?php echo $model['send_message_no'] ?></span></div>
                            <div>使用年限/年 : <span> <?php echo $model['downdate'] ?></span></div>
                            <div>价格/元 : <span> <?php echo $model['money'] ?></span></div><br>

                            <a class="btn green buymessage" msid = "<?php echo $model['lid'];?>" >立即购买</a>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
			<!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>

	<!-- END PAGE CONTENT-->
</div>
            <!--**支付窗口...**-->
        <div id="pay_layer" class="translucent hide" style="width: 98%;">
            <div class="pay" style="background-color: #ffcad3; width: 100%;height: 100%;">
                <p style="font-size: 20px;color: red;text-align: center;">点击图标扫码，支付更方便</p>
                <p style="text-align: center;">（请在两分钟内完成支付！！！）</p>
                <div class="qrCode wxpayclass" isclick="0" id="weixinpay" style="width: 50%;float: left;">
                    <p style="text-align:center; "><img id="wxpayimg" style="width: 50%;" src="../../../../img/waiter/weixin.png"/></p>
                    <span style="text-align: center;display:block;">微信支付</span>
                </div>
                <div class="qrCode" isclick="0" id="zhifubaopay" style="width: 50%;float: left;">
                    <p style="text-align:center; "><img id="alipayimg" style="width: 50%;" src="../../../../img/waiter/zhifubao.png"/></p>
                    <span style="text-align: center;display:block;">支付宝支付</span>
                </div>
                <div style="clear: both;"></div>
            </div>
        <input id="msid" type="hidden"/>
        </div> 
<script type="text/javascript">
var ordertime = 1;
	$(".buymessage").on('click',function(){
		var msid = $(this).attr('msid');
		$('#msid').val(msid);
		layer.msg(msid);
		$('#pay_layer').removeClass('hide');
		layer_pay=layer.open({
            type: 1,
            shade: 0.5,
            title: false, //不显示标题
            area: ['60%', '50%'],
            //time: 3000,
            closeBtn: 1,//关闭按钮
            content: $('#pay_layer'),
            cancel: function(index){
                layer.close(index);
                layer_pay=0;
                $('#pay_layer').addClass("uhide");
           }
       })
	})
	        
        //微信支付
        $("#weixinpay").on('click',function(){
            isprinter=0;
            var msid = $("#msid").val();
            layer.msg(msid);
            var isclick = $(this).attr('isclick');
            var paytype = 1;
            //防止二次点击。。。（点击出现二维码则isclick置1，禁止二次点击再生成二维码。。。）
            if(isclick=="0"){
                $.ajax({
                    url : '<?php echo $this->createUrl('message/createOrder');?>',
                    type : 'POST',
                    data : {
                        msid: msid,
                        username: '<?php echo Yii::app()->user->username;?>',
                        dpid: '<?php echo $this->companyId;?>',
                        paytype:3,
                    },
                    success:function(msg){
                        if(msg.status){
                            var imgurl = msg.msg;
                            var orderid = msg.orderid;
                            var did = msg.did;
                            $("#wxpayimg").attr('src',imgurl);
                            $("#weixinpay").attr('isclick','1');
                            $("#zhifubaopay").attr('isclick','1');
                            setInt = setInterval(orderstatus_time,1000,did,orderid);
                        }else{
                            alert("失败！");
                        }
                    },
                    error:function(){
                       
                        alert('error：网络错误');  
                      },
                    dataType:'json'
                });
                
            }else{
                //alert("请扫码支付！");
                //$("#cancel").trigger(vartouchstart);
            }
            //alert(orderproductId,orderpayId);//alert(dpid);
            //alert(orderid);
            
        })//微信支付、、、

        function orderstatus_time(dpid,orderid){
            //定时请求任务，如果支付成功，则清空购物车，关闭支付窗口。
               $.ajax({
            	   url : '<?php echo $this->createUrl('message/checkOrder');?>',
                   type : 'POST',
                   //timeout:60000,
                   data : {
                       dpid: dpid,
                       orderid: orderid,
                   },
                   success:function(msg){
                       if(msg.status){
                    	   
                               var orderStatus = msg.msg;
                               if(orderStatus=="1"){
                                   //支付成功的弹窗。。。
                                   layer.msg('购买成功！');
                                   //关闭定时向云端取值的任务、、、
                                   clearInterval(setInt);
                                   clearTimeout();
                                   //微信图标
                                   $("#wxpayimg").attr('src','../../../../img/waiter/weixin.png');
                                   $("#alipayimg").attr('src','../../../../img/waiter/zhifubao.png');
                                   //点击事件置0
                                   $("#weixinpay").attr('isclick','0');
                                   $("#zhifubaopay").attr('isclick','0');
                                   //关闭支付窗口、、
                                   
                                   layer.close(layer_pay);
                                   layer_pay=0;
                               }
                              
                       }else{
                           layer.msg(msg.msg);
                       }
                   },
                   error:function(){
                       //alert('请检查网络...');  
                     },
                   dataType:'json'
               });  
               
              ordertime++;//alert(ordertime)
              //if(ordertime==70){alert("70");}
              if(ordertime ==120){
            	  clearInterval(setInt);
                  //关闭定时向云端取订单状态的方法；
                  setTimeout(clearTimeout,5000);
                  layer.msg('支付失败！');
                  $("#wxpayimg").attr('src','../../../../img/waiter/weixin.png');
                  $("#alipayimg").attr('src','../../../../img/waiter/zhifubao.png');
                  //点击事件置0
                  $("#weixinpay").attr('isclick','0');
                  $("#zhifubaopay").attr('isclick','0');
                  //关闭支付窗口、、
                  
                  layer.close(layer_pay);
                  layer_pay=0;
                   
              }
       }
</script>

