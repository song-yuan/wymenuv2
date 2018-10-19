<style>
    body{
        font-size: 15px;
    }

.portlet-body>.row{
    margin:15px 0 30px 0;
}
.item-header{
    text-align: right;
    padding:0px;
}
input[type='button']{
   
  
}
@media (max-width: 768px) {
    .item-header{
        text-align: left;
        font-size:15px;
        margin-bottom: 10px;
        background-color:#f9f9f9;
        padding:10px;
        
    }
    .form-group{
        width:66.666%!important;
}
}
@media (min-width: 768px) {
.find{
    margin-top: 20px;
    margin-left: 250px;
    margin-bottom: 20px;
} 
.find_item1{
    padding-right: 0px !important;
}
.find_item2{
    padding-left: 5px !important;
}
}
ul {
    padding:0;
    margin:0
}
li{
   
   list-style-type :none;
}
.person_info{
        font-size: 16px;
        padding-left: 8px;
        margin-bottom: 30px;
        font-weight: bold;
    }

.small{
    font-size: 14px;
    color: #696969;
}
.person_info li{
    
    margin-right: 40px;
}
.base_info{
    margin-bottom: 30px;
}
.info_header{
    padding-left: 8px;
    font-weight: bold;
    margin-bottom: 3px;
    
}

.nav-tabs>li.active>a, .nav-tabs>li.active>a:hover, .nav-tabs>li.active>a:focus {
  
    border: 0px;
    color:#2d78f4;
    border-bottom:1px solid #2d78f4; 
}
.nav a{
   color:#000;
   font-weight: bold;
}
.contentheadtip{
		width: 96%;
		margin-left: 2%;
		padding: 4px;
		border-bottom: 1px solid white;
	}

.contenthead{
		width: 96%;
		margin-left: 2%;
		padding: 4px;
		border-bottom: 1px solid red;
	}
.contentdiv{
        text-align: center;
        width: 50%;
        float: left;
}
.clear{
        clear: both;
}
.font20{
        font-size: 20px;
}
.font18{
        font-size: 18px;
}
.detaildivtip{
        color: blue;		
        width: 96%;
        margin-left: 2%;
        padding: 6px;
        border-bottom: 1px solid blue;
}
.detailcontent{
        width: 96%;
        margin-left: 2%;
        padding: 4px;		
        border-bottom: 1px solid blue;
}
.detaildiv{
        text-align: center;
        width: 33%;
        float: left;
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
    <div id="main2" name="main2" 
         style="min-width: 500px;min-height:300px;display:none;" 
         onMouseOver="this.style.backgroundColor='rgba(255,222,212,1)'" 
         onmouseout="this.style.backgroundColor=''">
    </div>
 <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','会员查询'),'url'=>$this->createUrl('wechatMember/search' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','会员详情'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('wechatMember/search' , array('companyId' => $this->companyId,'type'=>0)))));?>
<div class="row">   
    <div class="col-md-12">
    <div class="portlet purple box">
    <div class="portlet-title">
        <div class="caption"><i class="fa fa-user"></i>会员信息</div>
             <div class="actions"></div>
        </div>            
        <div class="portlet-body" >
            <div class="info">
                <div class="person_info">
                      <?php if($brandUser) :?>
                    <ul>
                        <li class="pull-left">
                            <span><?php echo $brandUser['user_name']?$brandUser['user_name'].'|'.$brandUser['nickname']:$brandUser['nickname'];?></span>
                            
                        </li>
                        <li class="pull-left">
                            <span> 性别：
                                <?php if($brandUser['sex']=="1")  echo "男";elseif($brandUser['sex']=="2") echo "女";else echo "未知";?>
                            </span>
                        </li>
                        <li class="pull-left">
                          <?php echo $brandUser['level_name'];?>
                        </li>
                        <li class="pull-left">
                            <span><?php echo $brandUser['mobile_num'];?></span>
                            <span>（卡号：<span><?php echo $brandUser['card_id'];?></span>）</span>     
                        </li>
                        <li class="pull-left">
                            <span>生日：</span>
                            <span><?php echo $brandUser['user_birthday'];?></span>
                        </li>
                        <div style="clear:both;"></div> 
                    </ul>
                    <br>
                    <ul class="pull-left"><li><span>会员openid：<?php echo $brandUser['openid'];?></span></li></ul>
                </div>                
                <div class="base_info">
                    <div class="info_header"></div>
                    <div class="info_content"> 
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                     <th>卡内余额</th>
                                     <th>返现余额</th>
                                     <th>累计积分额</th>
                                    <th>开卡时间</th>
                                    <th>省市</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $brandUser['remain_money'];?></td>
                                    <td><?php echo $brandUser['remain_back_money'];?></td>
                                    <td><?php echo $brandUser['consume_point_history'];?></td>
                                    <td><?php echo $brandUser['create_at'];?></td>
                                    <td><?php echo $brandUser['province'];?>&nbsp;&nbsp;<?php echo $brandUser['city'];?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>                       
            <div class="base_info">
                    <div class="info_header">未使用优惠券</div>
                    <div class="info_content"> 
                        <table class="table table-striped table-bordered table-hover">  
                            <thead>
                                <tr>                                  
                                <th>券名称</th>
                                <th>面额</th>
                                <th>最低消费</th>
                                <th>领取时间</th>
                                <th>过期时间</th>
                                </tr>
                            </thead>
                            <tbody>                               
                                <?php 
                                	foreach ($userCupons as $userCupon):
                                		if($userCupon['is_used']=="2"||$userCupon['close_day'] < date('Y-m-d H:i:s')){
                                			continue;
                                		}
                                ?>
                                <tr>
                                    <td><?php echo $userCupon['cupon_title'];?></td>
                                    <td><?php echo $userCupon['cupon_money'];?></td>
                                    <td><?php echo $userCupon['min_consumer'];?></td>
                                    <td><?php echo $userCupon['create_at'];?></td>
                                    <td><?php echo $userCupon['close_day'];?></td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>            
            <div class="detail_info">                
                <div class="info_header">历史账单明细</div>
                <ul class="nav nav-tabs"id="attr_info" role="tablist">
                    <li role="presentation" class="active"><a href="javascript:void(0)" data-target ='zhangdantable'>账单</a></li>
                    <li role="presentation" ><a href="javascript:void(0)" data-target ='jifentable'>积分</a></li>
                    <li role="presentation"><a href="javascript:void(0)" data-target ='cupontable'>优惠券</a></li>                   
                </ul>
                <div class="info_content"> 
                          <table class="info_item table table-striped table-bordered table-hover" id="zhangdantable">
                             <thead>
                                <tr>
                                    <th>账单号</th>    
                                    <th>时间</th>
                                    <th>金额</th>
                                     <th>消费门店</th>                         
                                </tr>
                            </thead>
                            <?php 
                                 foreach ($orderPays as $orderPay):                                     
                             ?>                           
                            <tbody>
                                 <tr>
                                    <td class="accountno" 
                                         accountno="<?php echo $orderPay['account_no'];?>" 
                                         orderid="<?php echo $orderPay['order_id']?>" 
                                         originalp="<?php echo sprintf("%.2f",$orderPay['reality_total']);?>" 
                                         shouldp="<?php echo sprintf("%.2f",$orderPay['should_total']);?>" 
                                         youhuip="<?php echo sprintf("%.2f",($orderPay['reality_total'])-($orderPay['should_total']));?>"
                                         >
                                         <?php echo $orderPay['account_no'];?>
                                    </td>
                                    <td><?php echo $orderPay['create_at'];?></td> 
                                    <td><?php echo $orderPay['should_total'];?></td> 
                                    <td><?php echo $companys[$orderPay['dpid']]['company_name'];?></td> 
                                </tr>
                            </tbody>
                            <?php endforeach;?>
                        </table>
                        <table class="info_item table table-striped table-bordered table-hover" id="jifentable">
                             <thead>
                                <tr>
                                    <th>来源</th>    
                                    <th>积分</th>
                                    <th>时间</th>
                                                      
                                </tr>
                            </thead>
                        </table>
                        <table class="info_item table table-striped table-bordered table-hover" id="cupontable" style="display: none">
                             <thead>
                                <tr>                                  
                                <th>券名称</th>
                                <th>面额</th>
                                <th>最低消费</th>
                                <th>领取时间</th>
                                <th>过期时间</th>
                                <th>状态</th>
                                </tr>
                            </thead>
                            <tbody>                               
                                <?php 
                                	foreach ($userCupons as $userCupon):
                                ?>
                                <tr>
                                    <td><?php echo $userCupon['cupon_title'];?></td>
                                    <td><?php echo $userCupon['cupon_money'];?></td>
                                    <td><?php echo $userCupon['min_consumer'];?></td>
                                    <td><?php echo $userCupon['create_at'];?></td>
                                    <td><?php echo $userCupon['close_day'];?></td>
                                    <td><?php 
                                       
                                        if($userCupon['is_used']=="1"){
                                            if(date('Y-m-d H:i:s',time()) > $userCupon['close_day']){
                                                echo "已过期";
                                            }else{
                                            	echo "未使用";
                                            }
                                        }else{
                                        	echo "已使用";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                </div>
            </div>
          <?php endif;?>  
        </div> 
    </div>        
</div>
</div>
</div>
<script>
    $(function(){

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
          layer.load();
        $('#orderdetaildiv').remove();
        var orderid = $(this).attr('orderid');
        var accountno = $(this).attr('accountno');
        var originalp = $(this).attr('originalp');
        var shouldp = $(this).attr('shouldp');
        var youhuip = $(this).attr('youhuip');
         //alert(originalp); alert(shouldp);
        var url = "<?php echo $this->createUrl('wechatMember/accountDetail',array('companyId'=>$this->companyId));?>/orderid/"+orderid;
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
                    												paytypename = '微信储值';
                    											}else if(paytype==12){
                    												paytypename = '微信支付~';
                    											}else if(paytype==13){
                    												paytypename = '微信支付·';
                    											}
                    											else if(paytype==14){
                    												paytypename = '美团·外卖';
                    											}
                    											else if(paytype==15){
                    												paytypename = '饿了么·外卖';
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
                       layer.closeAll('loading')
                   },
                   error: function(msg){
                       layer.msg('网络错误！！！');
                       layer.closeAll('loading')
                   }
               });
			   

	        });
</script>

