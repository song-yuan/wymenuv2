<?php //Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/wechat_js/zepto.min.js');?>
<?php //Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/wechat_js/example.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-1.7.1.min.js');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/wechat_css/weui.css');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/wechat_css/example.css');?>

<style>
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
        height:150px;
        border-top: 1px solid #D9D9D9;
        border-bottom: 1px solid #D9D9D9;
        background-color: #EDEDED;
    }
</style>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">   
</head>
<body>
    <div class="container js_container">
        <div class="page">
            <div class="hd">
                <h1 class="page_title">会员卡</h1>
                <p class="page_desc">会员卡设计</p>
            </div>
            <div class="bd">
                <div class="weui_cells weui_cells_access global_navs">
                    <a class="weui_cell js_cell" href="<?php echo $this->createUrl('wechat/money');?>" >
                        <span class="weui_cell_hd "><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat_img/icon-wdcz.png" class="icon_nav" alt=""/></span>
                        
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>我的储值</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>         
                    </a>
                    
                    <a class="weui_cell js_cell" href="<?php echo $this->createUrl('wechat/point');?>" >
                        <span class="weui_cell_hd"><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat_img/icon-wdjf.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>我的积分</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <a class="weui_cell js_cell" href="javascript:;" >
                        <span class="weui_cell_hd"><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat_img/icon-wdq.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>我的券</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <a class="weui_cell js_cell" href="javascript:;" data-id="code">
                        <span class="weui_cell_hd"><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat_img/icon-wdewm.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>我的二维码</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
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
                        <div style="height:200px">
                            
                        </div>  
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
                        <div></div>  
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
                        <div></div>  
                    </div>
                    <div class="empty1"></div>
                    <a class="weui_cell js_cell" href="javascript:;" >
                        <span class="weui_cell_hd"><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat_img/icon-grxx.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>个人信息</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <a class="weui_cell js_cell" href="javascript:;" >
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
    
</script>


    
 
