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
<form action="<?php echo Yii::app()->createUrl('/user/saveUserInfo',array('companyId'=>$this->companyId));?>" method="post" onsubmit="return validate()">

<div class="page cell">
	<div class="weui_cells_title">完善个人资料</div>
    <div class="weui_cells weui_cells_form">
   		<div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">姓名</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" id="name" name="user[user_name]" type="text" placeholder="请输入姓名" value="<?php echo $user['user_name'];?>"/>
            </div>
        </div>
       <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">手机</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" id="mobile" name="user[mobile_num]" type="tel" placeholder="请输入联系方式" value="<?php echo $user['mobile_num'];?>"/>
            </div>
            <div class="weui_cell_ft sentMessage" style="font-size:100%;padding-left:5px;border-left:1px solid #888;">获取验证码<span id="countdown"></span></div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">验证码</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" id="verifyCode" name="verifyCode" type="tel" placeholder="请输入验证码" value=""/>
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd"><label for="" class="weui_label">生日</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" id="birthday" name="user[user_birthday]" type="date" value="<?php echo date('Y-m-d',strtotime($user['user_birthday']));?>"/>
            </div>
        </div>
    </div>
</div>
<div class="bttnbar">
	<button class="bttn_black2 bttn_large backUrl" type="button">取消</button>
	<button class="bttn_black2 bttn_large" type="submit">保存</button>
</div>
<input type="hidden" name="user[lid]" value="<?php echo $user['lid'];?>"/>
</form>
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
</body>
<script type="text/javascript">
  function validate() {
        if($('#name').val() == ''){
        	$('#dialog2').find('.weui_dialog_bd').html('请填写姓名！');
            $('#dialog2').show();
            return false;}
        if($('#mobile').val() == ''){
            $('#dialog2').find('.weui_dialog_bd').html('请填写联系方式！');
            $('#dialog2').show();
            return false;}
        if($('#verifyCode').val() == ''){
            $('#dialog2').find('.weui_dialog_bd').html('请填写验证码！');
            $('#dialog2').show();
            return false;}
        if($('#birthday').val() == ''){
            $('#dialog2').find('.weui_dialog_bd').html('请填写生日！');
            $('#dialog2').show();
        return false;}
        
        var success = true;
        var verifyCode = $('#verifyCode').val();
        var mobile = $('#mobile').val()
		$.ajax({
			url:'<?php echo $this->createUrl('/user/ajaxVerifyCode',array('companyId'=>$this->companyId));?>',
			data:{mobile:mobile,code:verifyCode},
			async: false,
			success:function(msg){
				if(!parseInt(msg)){
					$('#dialog2').find('.weui_dialog_bd').html('验证码错误');
       	 			$('#dialog2').show();
       	 			success = false;
				}
			}
		});
		return success;
    }
    
   var countdown = 60;
   function setTime(){
    	var obj = $('#countdown');
    	if (countdown == 0) { 
			obj.removeClass("disable");    
			obj.html(''); 
			countdown = 60; 
			return;
		} else { 
			obj.html('('+countdown+')'); 
			countdown--; 
		} 
		setTimeout(function(){ 
			setTime();
		},1000);
    }
    $('document').ready(function(){
    	$('.sentMessage').click(function(){
    		if($(this).hasClass('disable')){
    			return;
    		}
    		var mobile = $('#mobile').val()
    		$.ajax({
    			url:'<?php echo $this->createUrl('/user/ajaxSentMessage',array('companyId'=>$this->companyId));?>',
    			data:{mobile:mobile},
    			success:function(msg){
    				$('#dialog2').find('.weui_dialog_bd').html(msg);
           	 		$('#dialog2').show();
           	 		$('.sentMessage').addClass('disable')
           	 		setTime();
    			}
    		});
    	});
    	
    	$('#confirm').click(function(){
    		$('#dialog2').hide();
    	});
    	$('.backUrl').click(function(){
    		history.go(-1);
    	});
    });
</script>
