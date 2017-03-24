<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('绑定微信会员卡');  
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/common.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/members.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/wechat_css/mobiscroll.min.css">

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/weui.min.css">

<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/wechat_js/mobiscroll.min.js"></script>


<body class="add_address bg_lgrey2">
<form id="user-info" action="<?php echo Yii::app()->createUrl('/user/saveBindMemberCard',array('companyId'=>$this->companyId));?>" method="post" >

<div class="page cell">
	<div class="weui_cells_title">绑定微信会员卡</div>
    <div class="weui_cells weui_cells_form">
       <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">手机</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" id="mobile"  name="user[mobile_num]" type="tel" placeholder="请输入联系方式" value=""/>
            </div>
           <div class="weui_cell_ft sentMessage disable" style="font-size:100%;padding-left:5px;border-left:1px solid #888;">
                <span id="countSpan">获取验证码</span>
                <span id="countdown"></span>
            </div>
        </div>
        <div class="weui_cell code_box">
            <div class="weui_cell_hd" ><label class="weui_label">验证码</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" code_type = "2" id="verifyCode"  show=""  name="verifyCode" type="tel" placeholder="请输入验证码" value=""/>
            </div>
        </div>
        <input type="hidden" id="user_id" name="user[lid]" value="<?php echo $user['lid'];?>">
        <input type="hidden" id="user_dpid" name="user[dpid]" value="<?php echo $user['dpid'];?>">
    </div>
    <div class="weui_cells_tips member_content">
    </div>
</div>
<div class="bttnbar">
	<button class="bttn_black2 bttn_large backUrl" type="button">取消</button>
	<button class="bttn_black2 bttn_large" type="submit" onclick="return validate()">绑定</button>
</div>
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
  var msg = "<?php echo isset($msg)?$msg:'';?>";
  if(msg!=''){
	  layer.msg(msg);
  }
  function validate() {
        if($('#mobile').val() == ''){
            $('#dialog2').find('.weui_dialog_bd').html('请填写联系方式！');
            $('#dialog2').show();
            return false;}
        
        var verify_flag = false;
        if( $(".code_box").css("display")=='flex' ){
            verify_flag = true;
            if( $("#verifyCode").val() == '' ){
                $('#dialog2').find('.weui_dialog_bd').html('请填写验证码！');
                $('#dialog2').show();
                return false;
            }
        }
        if(verify_flag){
            var success = true;
            var verifyCode = $('#verifyCode').val();
            var mobile = $('#mobile').val();
            
        
            $.ajax({
                    url:'<?php echo $this->createUrl('/user/ajaxVerifyCode',array('companyId'=>$this->companyId));?>',
                    data:{mobile:mobile, code:verifyCode},
                    async: false,
                    success:function(msg){
                            if(!parseInt(msg)){
                                $('#dialog2').find('.weui_dialog_bd').html('验证码错误');
	                            $('#dialog2').show();
	                            success = false;
                            }
                    }
            });
           if(success == false){
              return false; 
           }
            
        }
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
	$('#mobile').change(function(){
		var mobile = $('#mobile').val();
		var user_id = $('#user_id').val();
		var user_dpid = $('#user_dpid').val();

		var myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/; 
        if(!myreg.test(mobile)){ 
            alert('请输入有效的手机号码！'); 
            return false; 
        }
		$.ajax({
			url:'<?php echo $this->createUrl('/user/ajaxGetMemberCard',array('companyId'=>$this->companyId));?>',
            data:{mobile:mobile,user_id:user_id,user_dpid:user_dpid},
            success:function(msg){
                if(msg.status){
                	 $('.sentMessage').removeClass('disable');
                     var member = msg.member;
                     var branduser = msg.branduser; 
                     var str = '';
                     str +='实体卡<br>';
                     str +='等级:'+member.name+'  折扣:'+member.level_discount+' 生日折扣:'+member.birthday_discount+'<br>';
                     str +=' 所绑定微信会员卡<br>';
                     str +='等级:'+branduser.name+' 折扣:'+branduser.level_discount+' 生日折扣:'+branduser.birthday_discount;
					 $('.member_content').html(str);	
                  }else{
                	  $('#dialog2').find('.weui_dialog_bd').html(msg.msg);
                      $('#dialog2').show();
                  }
             },
             dataType:'json'
		});
	});
    $('.sentMessage').click(function(){
       if($(this).hasClass('disable')){
           return;
        }
        var mobile = $('#mobile').val();

        var myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/; 
        if(!myreg.test(mobile)){ 
            alert('请输入有效的手机号码！'); 
            return false; 
        }
        $('.sentMessage').addClass('disable');
        var type = $('#verifyCode').attr('code_type');
        var user_id = $('#user_id').val();
        
        $.ajax({
                url:'<?php echo $this->createUrl('/user/ajaxSentMessage',array('companyId'=>$this->companyId));?>',
                data:{mobile:mobile,type:type,user_id:user_id},
                success:function(msg){
                        if(!parseInt(msg)){
                                $('#dialog2').find('.weui_dialog_bd').html('发送失败!'+msg);
                                $('#dialog2').show();
                        }else{
                                setTime();
                        }
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
