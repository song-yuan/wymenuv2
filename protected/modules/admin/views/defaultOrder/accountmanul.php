<style type="text/css">
    .calc_num {
        width: 56%;
        display: inline-block;
        margin: 2%;
    }
    .calc_button {
        width: 33%;
        display: inline-block;
        margin: 2%;
    }
    .calc_num ul li {
        float: left;
        width: 20%;
        height: 100px;
        border: 1px solid #add;
        margin: 5px;
        font-size: 20px;
        font-weight: 700;
        background-color: #add;
        list-style: none;
        text-align: center;
        vertical-align: middle;
      }
      .calc_button ul li {
        float: left;
        width: 40%;
        height: 100px;
        border: 1px solid #add;
        margin: 5px;
        font-size: 15px;
        font-weight: 700;        
        list-style: none;
        text-align: center;
        vertical-align: middle;
      }
</style>
						<?php $form=$this->beginWidget('CActiveForm', array(
                                                        'id'=>'account-form',
                                                        'action' => $this->createUrl('defaultOrder/accountManul',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$order->lid,'padId'=>$padId)),
                                                        'enableAjaxValidation'=>true,
                                                        //'method'=>'POST',
                                                        'enableClientValidation'=>true,
                                                        'clientOptions'=>array(
                                                                'validateOnSubmit'=>false,
                                                        ),
                                                        'htmlOptions'=>array(
                                                                'class'=>'form-horizontal'
                                                        ),
                                                )); ?>
                                                <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <h4 class="modal-title"><span style="color:red;"><?php echo yii::t('app','现金收款');?></span><br>
                                                            <?php echo yii::t('app','总额：');?><?php echo number_format($order->should_total,2); ?>
                                                                <?php echo yii::t('app','，已付：');?><?php echo number_format($order->reality_total,2); ?>
                                                                    <?php echo yii::t('app','，应付：');?><?php echo number_format($order->should_total-$order->reality_total,2); ?><br>
                                                                    <span style="width:90px; text-align:right; display: inline-block"><?php echo yii::t('app','收款：');?></span>
                                                                    <span id="cash_in" pointat="0" style="color:blue;width:190px; text-align:right; display: inline-block">0</span>
                                                                    <span style="width:90px; text-align:right; display: inline-block"><?php echo yii::t('app','找零：');?></span>
                                                                    <span id="cash_out" style="color:red;width:190px; text-align:right; display: inline-block">0</span></h4>
                                                        
                                                </div>
                                                <div class="">
                                                    <div class="calc_num">
                                                        <ul>
                                                            <li>1</li>
                                                            <li>2</li>
                                                            <li>3</li>
                                                            <li>4</li>
                                                            <li>5</li>
                                                            <li>6</li>
                                                            <li>7</li>
                                                            <li>8</li>
                                                            <li>9</li>
                                                            <li>0</li>
                                                            <li>00</li>
                                                            <li>.</li>
                                                        </ul>
                                                    </div>
                                                    <div class="calc_button">
                                                        <ul>
                                                            <li id="clearall" style="background-color: #add"><?php echo yii::t('app','清空');?></li>
                                                            <li id="clearone" style="background-color: #add"><?php echo yii::t('app','退格');?></li>
                                                            <li id="pay-btn" style="background-color: #0099FF"><?php echo yii::t('app','收银');?></li>
                                                            <li id="account-btn" style="background-color: #0099FF"><?php echo yii::t('app','结单');?></li>
                                                            <li id="other-btn" style="background-color: #009f95"><?php echo yii::t('app','其他付款方式');?></li>
                                                            <li data-dismiss="modal" class="default" style="background-color: #00FFFFFF"><?php echo yii::t('app','取消');?></li>
                                                        </ul>
                                                    </div>                                                            
                                                </div>
                                                <?php echo $form->hiddenField($orderpay , 'pay_amount' , array('id'=>'order_pay_amount'));?>
                                                <?php echo $form->hiddenField($order , 'order_status' , array('id'=>'account_orderstatus'));?>
                                                <?php echo $form->hiddenField($order , 'should_total' , array('id'=>'order_should_total'));?>
                                                <?php $this->endWidget(); ?>
					
			
			<script type="text/javascript">
                            var now_should_pay=parseFloat("<?php echo $order->should_total-$order->reality_total; ?>");
                            $('.calc_num').on(event_clicktouchstart,'li',function(){
                                var inval=$("#cash_in").html();
                                //alert(inval);
                                if(inval=="0" || inval=="00")
                                {
                                    if($(this).html()!=".")
                                    {
                                        $("#cash_in").html($(this).html());
                                    }
                                }else{
                                    if(inval.indexOf(".")>0 && $(this).html()==".")
                                    {
                                        
                                    }else{
                                        $("#cash_in").html(inval+$(this).html());
                                    }
                                }
//                                var inval=$(this).html();
//                                var cashin="0";
//                                var cashint=0;
//                                var pointat=0;
//                                if(inval!='.')
//                                {
//                                    cashin=$("#cash_in").html();
//                                     pointat=$("#cash_in").attr("pointat");
//                                     if(pointat=='0')
//                                     {
//                                        cashint=parseInt(cashin);
//                                        if(inval=="00")
//                                        {
//                                            $("#cash_in").html(cashint*100);
//                                        }else{
//                                            //alert(cashint);alert(inval)
//                                            $("#cash_in").html(cashint*10+parseInt(inval));
//                                        }
//                                     }else if(pointat=='1'){
//                                        if(inval!="00")
//                                        {
//                                            $("#cash_in").html(cashin.substr(0,cashin.length-2)+inval+"0");
//                                            $("#cash_in").attr("pointat","10");
//                                        }
//                                     }else if(pointat=='10'){
//                                        $("#cash_in").html(cashin.substr(0,cashin.length-1)+inval);
//                                        $("#cash_in").attr("pointat","100");
//                                     }
//                                }else{
//                                    $("#cash_in").attr("pointat","1");
//                                    $("#cash_in").html($("#cash_in").html()+".00");
//                                }
                                var cashinf=parseFloat($("#cash_in").html());
                                //alert($("#cash_in").html());alert(parseFloat($("#cash_in").html()));
                                //alert(now_should_pay);
                                if(cashinf-now_should_pay>0)
                                {
                                    $("#cash_out").html(Math.round((cashinf-now_should_pay)*100)/100);//little than 0 not show
                                }else{
                                    $("#cash_out").html("0");
                                }
                            });
                            
                            $('#clearall').on(event_clicktouchstart,function(){
                                $("#cash_in").html("0");
                                //$("#cash_in").attr("pointat","0");
                                //cash_out
                                $("#cash_out").html("0");
                            });
                            
                            $('#clearone').on(event_clicktouchstart,function(){
                                var cashin=$("#cash_in").html();
                                if(cashin.length>1)
                                {
                                    $("#cash_in").html(cashin.substr(0,cashin.length-1));
                                }else{
                                    $("#cash_in").html("0");
                                }
                                //var pointat=$("#cash_in").attr("pointat");
//                                var cashin=$("#cash_in").html();
//                                
//                                if(pointat=="100")
//                                {
//                                    //xxx.x0
//                                    $("#cash_in").html(cashin.substr(0,cashin.length-1)+"0");
//                                    $("#cash_in").attr("pointat","10");
//                                }else if(pointat=="10"){
//                                    //xxx.00
//                                    $("#cash_in").html(cashin.substr(0,cashin.length-2)+"00");
//                                    $("#cash_in").attr("pointat","1");
//                                }else if(pointat=="1"){
//                                    //xxx
//                                    $("#cash_in").html(cashin.substr(0,cashin.length-3));
//                                    $("#cash_in").attr("pointat","0");
//                                }else if(pointat=="0"){
//                                    if(cashin.length>1)
//                                    {
//                                        $("#cash_in").html(Math.round((cashinf-now_should_pay)*100)/100);
//                                    }else{
//                                        $("#cash_in").html("0");
//                                    }
//                                    //xx
//                                }
                                var cashinf=parseFloat($("#cash_in").html());
                                if(cashinf-now_should_pay>0)
                                {
                                    $("#cash_out").html(cashinf-now_should_pay);//little than 0 not show
                                }else{
                                    $("#cash_out").html("0");
                                }
                            });
                            $('#other-btn').on(event_clicktouchstart,function(){
                                 bootbox.confirm("<?php echo yii::t('app','你确定切换到其他支付方式吗？');?>", function(result) {
                                        if(result){
                                                openaccount('0');
                                        }
                                 });
                            });
                            $('#pay-btn').on(event_clicktouchstart,function(){
                                 bootbox.confirm("<?php echo yii::t('app','你确定只收银不结单吗？');?>", function(result) {
                                        if(result){
                                                //$('#account-form').attr('action','<?php $this->createUrl('defaultOrder/account',array('companyId'=>$this->companyId,'typeId'=>$typeId,'op'=>'pay','orderId'=>$order->lid)) ?>');
                                                if($("#cash_out").html()>"0")
                                                {
                                                    $('#order_pay_amount').val(now_should_pay);
                                                }else{
                                                    $('#order_pay_amount').val($("#cash_in").html());
                                                }
                                                //alert($('#order_pay_amount').val());
                                                $('#account_orderstatus').val('3');
                                                $('#account-form').submit();
                                        }
                                 });
                            });
                            $('#account-btn').on(event_clicktouchstart,function(){
                                 bootbox.confirm("<?php echo yii::t('app','确定结单吗？');?>", function(result) {
                                        if(result){
                                                //$('#account-form').attr('action','<?php $this->createUrl('defaultOrder/account',array('companyId'=>$this->companyId,'typeId'=>$typeId,'op'=>'account','orderId'=>$order->lid)) ?>');
                                                //$('#order_pay_amount').val($("#cash_in").html());
                                                if($("#cash_out").html()>"0")
                                                {
                                                    $('#order_pay_amount').val(now_should_pay);
                                                }else{
                                                    $('#order_pay_amount').val($("#cash_in").html());
                                                }
                                                $('#account_orderstatus').val('4');
                                                $('#account-form').submit();
                                        }
                                 });
                            });
                            $(document).ready(function() {
                                clearTimeout(interval);
                                var isauto="<?php if($callid=='0'){ echo '0';} else{ echo '1';} ?>";
                                //var isauto='1';
                                if(isauto=='1')
                                {
                                    interval = setInterval(autopaytimer,"2000");
                                }
                            });
                            $('#autopay_pause').on(event_clicktouchstart,function(){
                                //alert(11);
                                clearTimeout(interval);
                            });
                            function autopaytimer(){
                                //alert($("#timecount").html());
                                var curtime=parseInt($("#timecount").html());
                                curtime-=2;
                                if(curtime==0)
                                {
                                    clearTimeout(interval);
                                    //auto pay
                                    $('#account_orderstatus').val('4');
                                    $('#account-form').submit();
                                }else{
                                    $("#timecount").html(curtime);
                                }
                            }
                            $('#btn-account-cancle').on(event_clicktouchstart,function(){
                                 clearTimeout(interval);
                                 scanon=false;
                                 location.href="<?php echo $this->createUrl('defaultOrder/order',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$order->lid,'syscallId'=>$callid));?>";
                            });
                        </script>