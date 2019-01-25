<?php
	$baseUrl = Yii::app()->baseUrl;
    $title = $user['user_name']? "个人信息": "会员注册";
	$this->setPageTitle($title);  
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/common.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/members.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/wechat_css/mobiscroll.min.css">

<script type="text/javascript" src="<?php echo $baseUrl;?>/js/wechat_js/mobiscroll.min.js"></script>

<body class="add_address bg_lgrey2">
<form id="user-info" action="<?php echo Yii::app()->createUrl('/user/saveUserInfo',array('companyId'=>$this->companyId,'type'=>$type,'back'=>$back));?>" method="post" >

<div class="page cell">
	<div class="weui-cells__title">
            <?php  echo $title;?>
        </div>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">姓名</label></div>
            <div class="weui-cell__hd weui-cell_primary">
                <input class="weui-input" id="name" name="user[user_name]" type="text" placeholder="请输入姓名" value="<?php echo $user['user_name'];?>"/>
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">性别</label></div>
            <div id='sex-val-box' class="weui-cell__bd weui-cell_primary " >
            
            <select class="weui-select" id="sex" name="user[sex]" >
                <option value="0">保密</option>
                <option value="1" <?php if($user['sex']==1){ echo 'selected="selected"';}?>>男</option>
                <option value="2" <?php if($user['sex']==2){ echo 'selected="selected"';}?>>女</option>
            </select>
            </div>
        </div>
       <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">手机</label></div>
            <div class="weui-cell__bd weui-cell_primary">
                <input class="weui-input" <?php echo $user['mobile_num']?'readonly="readonly"':'' ?>  id="mobile"  name="user[mobile_num]" type="tel" placeholder="请输入联系方式" value="<?php echo $user['mobile_num'];?>"/>
                <input type='hidden' id='old_phone' value='<?php echo $user['mobile_num'];?>'/>
            </div>
            <!-- 
            <div class="weui-cell__ft sentMessage" style="display: <?php echo $user['mobile_num']?'none':'block';?>;">
                <button class="weui-vcode-btn" type="button">获取验证码<span id="countdown"></span></button>
            </div>
            -->
            <div class="weui-cell__ft revise" style="display: <?php echo $user['mobile_num']?'block':'none';?>; font-size:100%;padding-left:5px;">
                <button class="weui-vcode-btn bttn_small" type="button">修改</button>
            </div>
        </div>
        <!--
        <div class="weui-cell code_box" style="display:<?php echo $user['mobile_num']?'none':'flex'?>">
            <div class="weui-cell__hd" ><label class="weui-label">验证码</label></div>
            <div class="weui-cell__bd weui-cell_primary">
                <input class="weui-input" code_type = "<?php echo $user['mobile_num']?'1':'0'?>" id="verifyCode"  show=""  name="verifyCode" type="tel" placeholder="请输入验证码" value=""/>
            </div>
        </div>
        -->
        <div class="weui-cell">
            <div class="weui-cell__hd"><label for="" class="weui-label">生日</label></div>
            <div class="weui-cell__bd weui-cell_primary">
                <?php if($user['user_birthday']):?>
                <span id="birthday" class="weui-input" data="true"><?php echo date('Y-m-d',strtotime($user['user_birthday']));?></span>
            	<input type="hidden" name="user[user_birthday]" value="<?php echo date('Y-m-d',strtotime($user['user_birthday']));?>">
            	<?php else:?>
            	 <input class="weui-input"  data="false" id="birthday" name="user[user_birthday]" type="text" value="" placeholder="请填写生日" />
            	<?php endif;?>
            </div>
        </div>
    </div>
</div>
<div class="bttnbar">
        <a  href="<?php echo $this->createUrl('user/index',array('companyId'=>$this->companyId));?>" >
            <button class="bttn_black2 bttn_large backUrl" type="button">取消</button>
        </a>
	<button class="bttn_black2 bttn_large" type="submit" onclick="return validate()">保存</button>
</div>
<input type="hidden" id="user_id" name="user[lid]" value="<?php echo $user['lid'];?>"/>
<input type="hidden" id="user_id" name="user[dpid]" value="<?php echo $user['dpid'];?>"/>
</form>
<div id="dialogs">
	<div class="js_dialog" id="dialogaddbirth" style="display: none;">
            <div class="weui-mask"></div>
            <div class="weui-dialog">
                <div class="weui-dialog__hd"><strong class="weui-dialog__title">提示</strong></div>
                <div class="weui-dialog__bd">亲，生日提交后不能修改哦！</div>
                <div class="weui-dialog__ft">
                    <a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_default">修改</a>
                    <a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_primary btn-confirm">提交</a>
                </div>
            </div>
    </div>
          
	<div class="js_dialog" id="dialogbirth" style="display: none;">
		<div class="weui-mask"></div>
		<div class="weui-dialog">
			<div class="weui-dialog__bd">亲，生日不能修改哦</div>
			<div class="weui-dialog__ft">
			   <a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_default weui-dialog__btn_primary btn_birth">算你狠</a>
			</div>
		</div>                                                                                        
	</div>
	<div class="js_dialog" id=dialog2 style="display: none;">
		<div class="weui-mask"></div>
		<div class="weui-dialog">
			<div class="weui-dialog__bd"></div>
			<div class="weui-dialog__ft">
			   <a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_default weui-dialog__btn_primary btn_birth">确定</a>
			</div>
		</div>                                                                                        
	</div>
</div>
</body>
<script type="text/javascript">
	var date = new Date();
	var currYear = date.getFullYear(); 
	var currMonth = date.getMonth()+1; 
	var currDate = date.getDate(); 
  function validate() {
        if($('#name').val() == ''){
            $('#dialog2').find('.weui-dialog__bd').html('请填写姓名！');
            $('#dialog2').fadeIn(200);
            return false;
        }
        var mobile = $('#mobile').val();
        if(mobile == ''){
            $('#dialog2').find('.weui-dialog__bd').html('请填写联系方式！');
            $('#dialog2').fadeIn(200);
            return false;
        }else{
        	var myreg = /^(((13[0-9]{1})|(14[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/; 
            if(!myreg.test(mobile)){ 
                alert('请输入正确的手机号码！'); 
                return false; 
            }
        }
        
//         var verify_flag = false;
//         if( $(".code_box").css("display")=='flex' ){
//             verify_flag = true;
//             if( $("#verifyCode").val() == '' ){
//                 $('#dialog2').find('.weui-dialog__bd').html('请填写验证码！');
//                 $('#dialog2').fadeIn(200);
//                 return false;
//             }
//         }
        if($("#birthday").attr("data")=='true'){
            return true;
         }else{
        	 if($('#birthday').val() == ''){
                 $('#dialog2').find('.weui-dialog__bd').html('请填写生日！');
                 $('#dialog2').fadeIn(200);
                 return false;
             }
             if(new Date(Date.parse($('#birthday').val())) > new Date(Date.parse(currYear+'/'+currMonth+'/'+currDate))){
            	 $('#dialog2').find('.weui-dialog__bd').html('生日日期不能大于今天日期！');
            	 $('#dialog2').fadeIn(200);
                 return false
             }
              $("#dialogaddbirth").fadeIn(200);
              return false;           
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
                                $('#dialog2').find('.weui-dialog__bd').html('验证码错误');
                                $('#dialog2').fadeIn(200);
                            	success = false;
                             
                            }
                    }
            });
           if(success == false){
              return false; 
           }
        }
        return true;
    }
    
   var countdown = 60;
   function setTime(){
    	var obj = $('#countdown');
    	if (countdown == 0) { 
    		$('.sentMessage').removeClass("disable");
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

        var mobile = $('#mobile').val();

        var myreg = /^(((13[0-9]{1})|(14[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/; 
        if(!myreg.test(mobile)){ 
            alert('请输入有效的手机号码！'); 
            return false; 
        }
        if(mobile == $("#old_phone").val()){
            alert('该手机号码已存在！'); 
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
                                $('#dialog2').find('.weui-dialog__bd').html('发送失败!'+msg);
                                $('#dialog2').fadeIn(200);
                        }else{
                                setTime();
                        }
                }
        });
    });
       
    $('.bttn_small').click(function(){
         $('#mobile').removeAttr('readonly');
         $('.revise').hide();
//          $('.sentMessage').css('display','block');
//          $('.code_box').css('display','flex');              
    });
    $("#birthday[data = 'false']").mobiscroll().date({
        theme: 'android-holo-light',
        lang: 'zh',
        display: 'center',
        startYear: 1940, //开始年份
        endYear: currYear, //开始年份
    });
    $("#birthday[data = 'true']").click(function(){
        $('#dialogbirth').fadeIn(200);
    });
    $('#dialogs').on('click', '.weui-dialog__btn_default', function(){
        $(this).parents('.js_dialog').fadeOut(200);
    });	  
    $('.btn-confirm').on('click', function () {
        $('#user-info').submit();
    });  
});
</script>
