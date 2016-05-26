<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('优惠买单');
    $company = WxCompany::get($this->companyId);
?>
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/weui.min.css">

<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<style type="text/css">
footer{
	font-family: 'Heiti SC', 'Microsoft YaHei';
}
.total {
   color: #FF5151;
}
.page {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
}
.page, body {
    background-color: #FBF9FE;
}
.page .logo{
    width:2em;
    height: 2em;
    margin:0 auto;
    border-radius:1em;
}
.page .logo img{
    width:100%;
    height:100%;
}
.weui_label{
	width:5em;
}
.weui_input{
	text-align:right;
}
.weui_select{
	direction: rtl;
	color: #FF5151
}
</style>
<div class="page cell" >
    <div class="weui_cells_title">输入消费金额</div>
    <div class="weui_cells weui_cells_form">
    	<div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">消费金额</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" id="price" name="order[price]" type="number" placeholder="询问服务员后输入" value=""/>
            </div>
        </div>
    </div>
    <!--
    <div class="weui_cells_title">选择优惠券</div>
    <div class="weui_cells">
        <div class="weui_cell weui_cell_select weui_select_after">
            <div class="weui_cell_hd">优惠券</div>
            <div class="weui_cell_bd weui_cell_primary">
                <select class="weui_select" name="select2">
                    <option value="1">10元</option>
                    <option value="2">20元</option>
                    <option value="3">30元</option>
                </select>
            </div>
        </div>
    </div>
    -->
    <div class="weui_cells_title">选择支付方式</div>
    <div class="weui_cells weui_cells_checkbox">
        <label class="weui_cell weui_check_label" for="x11">
        	<div class="weui_cell_hd"><img src="<?php echo $baseUrl;?>/img/mall/wxpay.png" alt="" style="width:20px;margin-right:5px;display:block"></div>
            <div class="weui_cell_bd weui_cell_primary">
                <p>微信支付</p>
            </div>
            <div class="weui_cell_ft">
                <input type="radio" class="weui_check" name="order[pay-type]" id="x11" checked="checked" value="0">
                <span class="weui_icon_checked"></span>
            </div>
        </label>
        <!--
        <label class="weui_cell weui_check_label" for="x12">
        <div class="weui_cell_hd"><img src="<?php echo $baseUrl;?>/img/mall/zfbpay.png" alt="" style="width:20px;margin-right:5px;display:block"></div>
            <div class="weui_cell_bd weui_cell_primary">
                <p>支付宝支付</p>
            </div>
            <div class="weui_cell_ft">
                <input type="radio" name="order[pay-type]" class="weui_check" id="x12" value="1">
                <span class="weui_icon_checked"></span>
            </div>
        </label>
        -->
    </div>
    <div class="logo">
        <img src="<?php echo $company['logo']?$company['logo']:'img/logo.png';?>" />
    </div>
    
    <footer>
        <div class="ft-lt">
            <p>￥<span id="total" class="total">0.00</span></p>
        </div>
        <div class="ft-rt">
            <p><a id="payorder" href="javascript:;">确认买单</a></p>
        </div>
        <div class="clear"></div>
    </footer>
    <div class="weui_dialog_alert" id="dialog2" style="display: none;">
    	<div class="weui_mask"></div>
    	<div class="weui_dialog" style="font-size:15px;">
    	    <div class="weui_dialog_hd"><strong class="weui_dialog_title">提示</strong></div>
    	    <div class="weui_dialog_bd"></div>
    	    <div class="weui_dialog_ft">
    	        <a href="javascript:;" id="confirm" class="weui_btn_dialog primary">确定</a>
    	    </div>
    	</div>
    </div>
    <input type="hidden" id="user-id" value="<?php echo $userId;?>"/>
</div>
<script type="text/javascript">
    $('document').ready(function(){
        $('#price').keyup(function(){
            var price = parseFloat($(this).val());
            $('#total').html(price.toFixed(2));
        });
    	$('#confirm').click(function(){
    		$('#dialog2').hide();
    	});
        $('#payorder').click(function(){
            if($('#price').val() == ''){
	        	$('#dialog2').find('.weui_dialog_bd').html('请填写金额');
	            $('#dialog2').show();
	            return;
           }
           var userId = $('#user-id').val();
           var orderPrice = $('#total').html();
           var type = $('input[name="order[pay-type]"]:checked').val();
           $.ajax({
                url:'<?php echo $this->createUrl('/mall/createBillOrder',array('companyId'=>$this->companyId));?>',
                type:'POST',
                data:{userId:userId,orderPrice:orderPrice},
                success:function(msg){
                    if(msg.status){
                        location.href = "<?php echo $this->createUrl('/mall/payBillOrder',array('companyId'=>$this->companyId));?>&oid="+msg.order_id+"&uid="+userId+"&type="+type;
                    }else{
                        $('#dialog2').find('.weui_dialog_bd').html('请请重新支付！');
	                    $('#dialog2').show();
                    }
                },
                dataType:'json'
           });
        });
    });
</script>
