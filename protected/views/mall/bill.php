<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('优惠买单');
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
<form action="<?php echo Yii::app()->createUrl('/user/saveUserInfo',array('companyId'=>$this->companyId));?>" method="post" onsubmit="return validate()">
	<div class="weui_cells_title">输入消费金额</div>
    <div class="weui_cells weui_cells_form">
   		<div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">消费金额</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" id="price" name="order[price]" type="text" placeholder="询问服务员后输入" value=""/>
            </div>
        </div>
    </div>
    
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
    
    <div class="weui_cells_title">选择支付方式</div>
    <div class="weui_cells weui_cells_checkbox">
        <label class="weui_cell weui_check_label" for="x11">
        	<div class="weui_cell_hd"><img src="<?php echo $baseUrl;?>/img/mall/wxpay.png" alt="" style="width:20px;margin-right:5px;display:block"></div>
            <div class="weui_cell_bd weui_cell_primary">
                <p>微信支付</p>
            </div>
            <div class="weui_cell_ft">
                <input type="radio" class="weui_check" name="order[pay-type]" id="x11" checked="checked">
                <span class="weui_icon_checked"></span>
            </div>
        </label>
        <label class="weui_cell weui_check_label" for="x12">
        <div class="weui_cell_hd"><img src="<?php echo $baseUrl;?>/img/mall/zfbpay.png" alt="" style="width:20px;margin-right:5px;display:block"></div>
            <div class="weui_cell_bd weui_cell_primary">
                <p>支付宝支付</p>
            </div>
            <div class="weui_cell_ft">
                <input type="radio" name="order[pay-type]" class="weui_check" id="x12">
                <span class="weui_icon_checked"></span>
            </div>
        </label>
    </div>
</form>
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
	<div class="weui_dialog">
	    <div class="weui_dialog_hd"><strong class="weui_dialog_title">提示</strong></div>
	    <div class="weui_dialog_bd"></div>
	    <div class="weui_dialog_ft">
	        <a href="javascript:;" id="confirm" class="weui_btn_dialog primary">确定</a>
	    </div>
	</div>
</div>
</div>
<script type="text/javascript">
  function validate() {
        if($('#name').val() == ''){
	        	$('#dialog2').find('.weui_dialog_bd').html('请填写姓名！');
	            $('#dialog2').show();
	            return false;
           }
		return success;
    }
    
    $('document').ready(function(){

    	$('#confirm').click(function(){
    		$('#dialog2').hide();
    	});
    });
</script>
