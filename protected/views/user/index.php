<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('会员中心');
?>


<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/wechat_js/zepto.min.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/wechat_js/example.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-1.7.1.min.js');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/wechat_css/weui.css');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/wechat_css/example.css');?>
<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/plugins/font-awesome/css/font-awesome.min.css');?>

<style>
    .hd {
    padding:15px 0px 8px 0px;
    text-align: center;
}
    .hd img{
       
        height:180px;
       
           
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
        height:120px;
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
</style>


<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>一点吃会员卡</title>
</head>
<body>
  
    <div class="container js_container">
        <div class="page">
            <div class="hd">
                <img  src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat_img/hyk.png" class="icon_nav" alt=""/>
            </div>
            <div class="bd">
                <div class="weui_cells weui_cells_access global_navs">
                    <a class="weui_cell js_cell" href="<?php echo $this->createUrl('user/money',array('companyId'=>$this->companyId));?>" >
                        <span class="weui_cell_hd "><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat_img/icon-wdcz.png" class="icon_nav" alt=""/></span>
                        
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>我的储值</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>         
                    </a>
                    
                    <a class="weui_cell js_cell" href="<?php echo $this->createUrl('user/point',array('companyId'=>$this->companyId));?>" >
                        <span class="weui_cell_hd"><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat_img/icon-wdjf.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>我的积分</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <a class="weui_cell js_cell" href="<?php echo $this->createUrl('user/ticket',array('companyId'=>$this->companyId));?>" >
                        <span class="weui_cell_hd"><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat_img/icon-wdq.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>我的券</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <a class="weui_cell js_cell" href="javascript:;"  id="showDialog2" >
                        <span class="weui_cell_hd">
                            <img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat_img/icon-wdewm.png" class="icon_nav" alt="">
                        </span>
                        <div  id='qrcode-btn'class="weui_cell_bd weui_cell_primary">
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
                        <span class="weui_cell_hd"><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat_img/icon-wdzxtq.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>我的专享特权</p>
                        </div>
                        <div class="up_down1" ></div>
                    </a>
                    <div class="collapse pri_style" id="chanel_demo1">
                        <!--  add some code here -->
                        
                        <div class="introduce">详情说明</div>
                            <ul>
                                <li>到店即可享受&nbsp;<?php  echo $userLevel['level_discount']*10;?>&nbsp;折优惠</li>
                                <li>生日可享受&nbsp;<?php echo $userLevel['birthday_discount']*10;?>&nbsp;折优惠</li>
                            </ul>
                      
                    </div>
  
                    
                    
                    
                    
                    <div class="empty2">商家优惠活动</div>
                    <a class="weui_cell js_cell" href="javascript:;" data-id="sale1" data_target="#chanel_demo2">
                        <span class="weui_cell_hd"><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat_img/icon-wdyh.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>满减</p>
                        </div>
                        <div class="up_down1">
                        </div>
                    </a>
                     <div class="collapse sale1_style" id="chanel_demo2">
                        <!--  add some code here -->
                       <div class="introduce">  详情说明</div>
                            <ul>
                                <li>满30元减2元</li>
                                <li>满50元减5元</li>
                            </ul> 
                    </div>
                    
                    <a class="weui_cell js_cell" href="javascript:;" data-id="sale2" data_target="#chanel_demo3">
                        <span class="weui_cell_hd"><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat_img/icon-wdyh.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>满送</p>
                        </div>
                        <div class="up_down1">
                        </div>
                    </a>
                    <div class="collapse sale2_style" id="chanel_demo3">
                        <!--  add some code here -->
                        <div class="introduce">  详情说明</div>
                            <ul>
                                <li>满30元送甜筒一个</li>
                                <li>满50元送薯条(小)一份</li>
                            </ul>
                    </div>
                    <div class="empty1"></div>
                    <a class="weui_cell js_cell" href="<?php echo $this->createUrl('user/setUserInfo',array('companyId'=>$this->companyId));?>">
                        <span class="weui_cell_hd"><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat_img/icon-grxx.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>个人信息</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <a class="weui_cell js_cell" href="<?php echo $this->createUrl('user/bill',array('companyId'=>$this->companyId));?>" >
                        <span class="weui_cell_hd"><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat_img/icon-zd.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>账单</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <div class="empty1"></div>
                     <a class="weui_cell js_cell" href="javascript:;" data-id="tel">
                        <span class="weui_cell_hd"></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>门店及电话</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <div class="empty3"></div>
                     <div class="put_into_bg">
                    <div class="weui_btn weui_btn_primary put_into">放入微信卡包</div> 
                    </div>
                </div>
               
            </div>
           
        </div>
    </div>
    

 </body>    

<script type="text/javascript">
 
    $('.weui_cell ').on('click',function(){
      
        var up_down=(!$(this).find(".up_down1").hasClass('up_down2'))?true:false;
       $(this).find(".up_down1").toggleClass('up_down2',up_down);     
       var display_em= $(this).attr("data_target");
       $(display_em).toggleClass("collapse",!up_down);
        
    });  
    $('#qrcode-btn').click(function(){
        $.ajax({
            url:'<?php echo $this->createUrl('/user/ajaxGetUserCard',array('companyId'=>$this->companyId));?>',
            data:{userId:<?php echo $user['lid'];?>},
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

</script>


    
 
