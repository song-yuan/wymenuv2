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
<form action="<?php echo Yii::app()->createUrl('/user/setUserInfo',array('companyId'=>$this->companyId));?>" method="post" onsubmit="return validate()">

<div class="page cell">
	<div class="weui_cells_title">完善个人资料</div>
    <div class="weui_cells weui_cells_form">
   		<div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">姓名</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" id="name" name="user[user_name]" type="tel" placeholder="请输入姓名"/>
            </div>
        </div>
       <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">手机</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" id="mobile" name="user[mobile_num]" type="tel" placeholder="请输入联系方式"/>
            </div>
        </div>
        
        <div class="weui_cell">
            <div class="weui_cell_hd"><label for="" class="weui_label">生日</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" id="birthday" name="user[user_birthday]" type="date" value=""/>
            </div>
        </div>
    </div>
</div>
<div class="bttnbar">
	<button class="bttn_black2 bttn_large" type="button"><a href="<?php echo $this->createUrl('/user/index',array('companyId'=>$this->companyId));?>">取消</a></button>
	<button class="bttn_black2 bttn_large" type="submit">保存</button>
</div>

</form>
</body>
<script type="text/javascript">
  function validate() {
        if($('#name').val() == ''){
            alert('请填写姓名！');
            return false;}
        if($('#mobile').val() == ''){
            alert('请填写联系方式！');
            return false;}
        if($('#birthday').val() == ''){
            alert('请填写生日！');
        return false;}
        
        return true;
    }
</script>
