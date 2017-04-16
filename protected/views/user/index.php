<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('会员中心');
?>


<?php Yii::app()->clientScript->registerScriptFile( $baseUrl.'/js/wechat_js/zepto.min.js');?>
<?php Yii::app()->clientScript->registerScriptFile( $baseUrl.'/js/wechat_js/example.js');?>
<?php Yii::app()->clientScript->registerCssFile( $baseUrl.'/css/wechat_css/weui.css');?>
<?php Yii::app()->clientScript->registerCssFile( $baseUrl.'/css/wechat_css/example.css');?>
<?php Yii::app()->clientScript->registerCssFile($baseUrl.'/plugins/font-awesome/css/font-awesome.min.css');?>

<style>
    .hd {
    padding:15px 0px 8px 0px;
   
}
    .img-box{
            background-repeat: no-repeat;
            height: 180px;
            width: 300px;
            margin: 0 auto;  
            position: relative;
            border-radius: 7px;
        
            
    }
    .img-box .level_num{
        position: absolute;
        bottom:0px;
        left:0;
        margin:0px 10px 0px 10px;
        color:#fff;
    }
    .up_down1:after{
         transition:All 0.3s ease-in-out;

    -webkit-transition:All 0.3s ease-in-out;

    -moz-transition:All 0.3s ease-in-out;

    -o-transition:All 0.3s ease-in-out;
         content: " ";
  display: inline-block;
 -webkit-transform: rotate(135deg,135deg);
        -ms-transform: rotate(135deg);
            transform: rotate(135deg);
  height: 6px;
  width: 6px;
  border-width: 2px 2px 0 0;
  border-color: #C8C8CD;
  border-style: solid;
  position: relative;
  top: -2px;
  top: -1px;
  margin-left: .3em;
         
    } 
     .up_down2:after{
         transition:All 0.3s ease-in-out;

    -webkit-transition:All 0.3s ease-in-out;

    -moz-transition:All 0.3s ease-in-out;

    -o-transition:All 0.3s ease-in-out;

         
          content: " ";
  display: inline-block;
 -webkit-transform: rotate(-45deg);
        -ms-transform: rotate(-45deg);
            transform: rotate(-45deg);
  height: 6px;
  width: 6px;
  border-width: 2px 2px 0 0;
  border-color: #C8C8CD;
  border-style: solid;
  position: relative;
  top: -2px;
  top: -1px;
  margin-left: .3em;
    } 
    .collapse{
        display: none;
    }
    .pri_style,.sale1_style,.sale2_style{
        min-height:120px;
        border-top: 1px solid #D9D9D9;
        border-bottom: 1px solid #D9D9D9;
        background-color: #EDEDED;
        font-size: 15px;
        
    }
    .introduce{
       margin: 20px 0px 10px 55px; 
       color:#787878;
    }
    ul{
        margin: 0px 0px 10px 75px; 
        color:#787878;
    }
    .txm{
       width: 240px;
       height:90px;
       padding-bottom: 10px;
       
    }
    .txw_out{
     border-bottom: 1px dashed #CFCFCF;   
    }
    .ewm{
         width: 200px;
       height:200px;
    }
    .btn_ewm{
     
        font-size: 30px;
        color:#636363;
    }
    .btn_ewm_out{
       text-align: right;  
       margin-bottom: 20px;
    }
    .txw_out,.ewm_out,.des{
       text-align: center; 
    }
    .des{
        border-top: 1px solid #CFCFCF;
        color: #787878;
        background-color: #EDEDED;
        font-size: 15px;
        height:40px;
        line-height: 40px;
    }
    .ewm_out{
        margin-top: 30px;
        margin-bottom: 30px;
    }
  
    .uplevel_box{
        text-align: center;
        margin-top: 25px;
        margin-bottom: 40px;
        font-size: 22px;
    }
</style>
    <div class="container js_container">
        <div class="page">
           <div class="hd" style="position: relative;">
               <div class="img-box" style="background-image:url(<?php echo isset($img['bg_img'])?$img['bg_img']:$baseUrl.'/img/wechat_img/hyk22.jpg'?>)">
                   <div class="level_num">
                       <span class="num">卡号：<?php echo substr($user['card_id'],5);?></span>
                   </div>
               </div>
            </div>
            <div class="bd">
                <div class="weui_cells weui_cells_access global_navs">
                    <a class="weui_cell js_cell" href="<?php echo $this->createUrl('user/money',array('companyId'=>$this->companyId));?>" >
                        <span class="weui_cell_hd "><img src="<?php echo $baseUrl;?>/img/wechat_img/icon-wdcz.png" class="icon_nav" alt=""/></span>
                        
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>我的储值<span class="small font_org right"><?php echo number_format($remainMoney,2);?></span></p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>         
                    </a>
                    
                    <a class="weui_cell js_cell" href="<?php echo $this->createUrl('user/point',array('companyId'=>$this->companyId));?>" >
                        <span class="weui_cell_hd"><img src="<?php echo $baseUrl;?>/img/wechat_img/icon-wdjf.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>我的积分</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <a class="weui_cell js_cell" href="<?php echo $this->createUrl('user/ticket',array('companyId'=>$this->companyId));?>" >
                        <span class="weui_cell_hd"><img src="<?php echo $baseUrl;?>/img/wechat_img/icon-wdq.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>我的券</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <a class="weui_cell js_cell" href="javascript:;"  id="showDialog2" >
                        <span class="weui_cell_hd">
                            <img src="<?php echo $baseUrl;?>/img/wechat_img/icon-wdewm.png" class="icon_nav" alt="">
                        </span>
                        <div  id='qrcode-btn' class="weui_cell_bd weui_cell_primary" user_id="<?php echo $user['lid'];?>" user_dpid="<?php echo $user['dpid'];?>">
                            <p>我的二维码</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>       
                    <!--BEGIN dialog2-->
                    <div id="dialog2" style="z-index:90;position: fixed; display: none;">                     
                        <div class="weui_dialog" >                           
                            <div class="btn_ewm_out">    
                                <a href="javascript:;" class="fa fa-times-circle btn_ewm"></a>
                            </div>
                            <div id='qrcode-box' class="ewm_out"> 
                            </div>
                            <div class="des">到店出示给服务员即可使用</div>
                        </div>
                    </div>  
                    <!--BEGIN dialog2--> 
                    <!--END dialog2-->
                    <div class="empty1"></div>                     
                    <a class="weui_cell js_cell" href="javascript:;" data-id="privilege" data_target="#chanel_demo1">
                        <span class="weui_cell_hd"><img src="<?php echo $baseUrl;?>/img/wechat_img/icon-wdzxtq.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>我的专享特权</p>
                        </div>
                        <div class="up_down1" ></div>
                    </a>
                    <div class="collapse pri_style" id="chanel_demo1">            
                        <?php   if($userLevel): ?>
                        <div class="introduce"><?php  echo $userLevel['level_name']; ?>：</div>
                        <ul>
                            <li>到店即可享受&nbsp;<?php  echo $userLevel['level_discount']*10;?>&nbsp;折优惠</li>
                            <li>生日可享受&nbsp;<?php echo $userLevel['birthday_discount']*10;?>&nbsp;折优惠</li>
                        </ul>
                        <?php  else: ?>
                        <div class="introduce">暂无等级特权</div>
                        <?php endif; ?>
                    </div>
  
                    
                    
                    
                    
                    <div class="empty2">商家优惠活动</div>
                    <a class="weui_cell js_cell" href="javascript:;" data-id="sale1" data_target="#chanel_demo2">
                        <span class="weui_cell_hd"><img src="<?php echo $baseUrl;?>/img/wechat_img/icon-wdyh.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>满减</p>
                        </div>
                        <div class="up_down1">
                        </div>
                    </a>
                    <div class="collapse sale1_style" id="chanel_demo2">
                        <!--  add some code here -->
                       <div class="introduce">详情说明</div>
                      <?php 
                            if($minus):
                            foreach ($minus as $v):
                        ?>                        
                            <ul>
                                <li><?php echo $v['title'];?>
                                   （使用时间：<?php echo date('Y.m.d',strtotime($v['begin_time']));?> -
                                    <?php echo date('Y.m.d',strtotime($v['end_time']));?>）
                                </li>
                            </ul> 
                        <?php 
                            endforeach; 
                            else :
                        ?>
                           <ul>
                               <li>暂无满减优惠活动</li> 
                           </ul> 
                        <?php endif; ?>
                    </div>
                    
                    <a class="weui_cell js_cell" href="javascript:;" data-id="sale2" data_target="#chanel_demo3">
                        <span class="weui_cell_hd"><img src="<?php echo $baseUrl?>/img/wechat_img/icon-wdyh.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>满送</p>
                        </div>
                        <div class="up_down1">
                        </div>
                    </a>
                    <div class="collapse sale2_style" id="chanel_demo3">
                        <!--  add some code here -->
                        <div class="introduce">  详情说明</div>
                            <?php 
                            if($give):
                            foreach ($give as $v):
                        ?>                        
                            <ul>
                                <li><?php echo $v['title'];?>
                             
                                   （使用时间：<?php echo date('Y.m.d',strtotime($v['begin_time']));?> -
                                   <?php echo date('Y.m.d',strtotime($v['end_time']));?>）
                                </li>
                            </ul> 
                        <?php 
                            endforeach; 
                            else :
                        ?>
                           <ul>
                               <li>暂无满送优惠活动</li> 
                           </ul> 
                        <?php endif; ?>
                    </div>
                    <div class="empty1"></div>
                    <a class="weui_cell js_cell" href="<?php echo $this->createUrl('user/setUserInfo',array('companyId'=>$this->companyId));?>">
                        <span class="weui_cell_hd"><img src="<?php echo $baseUrl;?>/img/wechat_img/icon-grxx.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>个人信息</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <a class="weui_cell js_cell" href="<?php echo $this->createUrl('user/address',array('companyId'=>$this->companyId));?>">
                        <span class="weui_cell_hd"><img src="<?php echo $baseUrl;?>/img/wechat_img/icon_location.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>收货地址</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <a class="weui_cell js_cell" href="<?php echo $this->createUrl('user/orderList',array('companyId'=>$this->companyId));?>" >
                        <span class="weui_cell_hd"><img src="<?php echo $baseUrl;?>/img/wechat_img/icon-zd.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>我的订单</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <div class="empty1"></div>
                    <!-- 
                    <div class="empty3"></div>
                     <div class="put_into_bg">
                    <div class="weui_btn weui_btn_primary put_into">放入微信卡包</div> 
                    </div>
                   -->
                    
                </div>
               
            </div>
              <div id="uplevel" style="z-index:90;position: absolute; display: <?php echo $upLev ? 'block':'none';?>;">                     
                    <div class="weui_dialog" >                           
                        <div class="btn_ewm_out">    
                            <a href="javascript:;" class="fa fa-times-circle btn_ewm"></a>
                        </div>
                        <div  class="uplevel_box">
                            恭喜你升级为<?php  echo $userLevel['level_name']; ?>
                        </div> 
                    </div>
                </div>  
           <div class="sp-lightbox1"  style="z-index:50;position: absolute;top:0; left: 0; height: 100%;width: 100%;background: rgba(0, 0, 0, .6);cursor: -webkit-zoom-out;cursor: -moz-zoom-out;cursor: zoom-out;display: <?php echo $upLev?'block':'none';?>"></div>
        </div>
    </div>
    
<script type="text/javascript">
    $('.weui_cell ').on('click',function(){
      
        var up_down=(!$(this).find(".up_down1").hasClass('up_down2'))?true:false;
       $(this).find(".up_down1").toggleClass('up_down2',up_down);     
       var display_em= $(this).attr("data_target");
       $(display_em).toggleClass("collapse",!up_down);
        
    });  
    $('#qrcode-btn').click(function(){
        var userId = $(this).attr('user_id');
        var userDpid = $(this).attr('user_dpid');
        $.ajax({
            url:'<?php echo $this->createUrl('/user/ajaxGetUserCard',array('companyId'=>$this->companyId));?>',
            data:{user_id:userId,user_dpid:userDpid},
            success:function(msg){
                if(msg.status){
                    var content = '<img src="<?php echo $baseUrl;?>/'+msg.url+'" style="width:100%;height:100%;"/>';
                    $("#qrcode-box").empty().append(content);
                }else{
                           $("#qrcode-box").empty().append('不存在该会员'); 
                    }
            },
            dataType:'json'
            });
    });

            $('#uplevel').find('.btn_ewm').on('click', function () {
                $('#uplevel').hide();
                $(".sp-lightbox1").hide();
            });
</script>


    
 
