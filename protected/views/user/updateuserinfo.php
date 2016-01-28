<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('完善个人资料');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/common.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/members.css">

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/weui.min.css">

<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<body class="add_address bg_lgrey2">
<div class="page cell">
	<div class="weui_cells_title">完善个人资料</div>
    <div class="weui_cells weui_cells_form">
   		<div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">姓名</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="tel" placeholder="请输入姓名"/>
            </div>
        </div>
       <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">手机</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="tel" placeholder="请输入联系方式"/>
            </div>
        </div>
        
        <div class="weui_cell">
            <div class="weui_cell_hd"><label for="" class="weui_label">日期</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="date" value=""/>
            </div>
        </div>
    </div>
</div>
<div class="bttnbar">
	<button class="bttn_black2 bttn_large" type="button"><a href="<?php echo $this->createUrl('/user/address',array('companyId'=>$this->companyId));?>">取消</a></button>
	<button class="bttn_black2 bttn_large" type="submit">保存</button>
</div>
</body>
<script type="text/javascript">

  function validate() {
        if($('#name').val() == ''){
            alert('请填写收货人名字！');
            return false;}
        if($('#mobile').val() == ''){
            alert('请填写收货人手机！');
            return false;}
        if($('#province').val() == '请选择..' ||$('#province').val() == '' || $('#city').val() == '' || $('#area').val() == ''){
            alert('请选择地区！');
            return false;}
        if($('#street').val() == ''){
            alert('请填写详细地址！');
        return false;}
    }
</script>
