<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/wechat/zepto.min.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/wechat/example.js');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/wechat/weui.css');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/wechat/example.css');?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">

   
</head>
<body ontouchstart>
    <div class="container js_container">
        <div class="page">
            <div class="hd">
                <h1 class="page_title">会员卡</h1>
                <p class="page_desc">会员卡设计</p>
            </div>
            <div class="bd">
                <div class="weui_cells weui_cells_access global_navs">
                    <a class="weui_cell js_cell" href="javascript:;" data-id="money">
                        <span class="weui_cell_hd "><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat/icon-wdcz.png" class="icon_nav" alt=""/></span>
                        
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>我的储值</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>         
                    </a>
                    <a class="weui_cell js_cell" href="javascript:;" data-id="point">
                        <span class="weui_cell_hd"><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat/icon-wdjf.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>我的积分</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <a class="weui_cell js_cell" href="javascript:;" data-id="ticket">
                        <span class="weui_cell_hd"><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat/icon-wdq.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>我的券</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <a class="weui_cell js_cell" href="javascript:;" data-id="code">
                        <span class="weui_cell_hd"><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat/icon-wdewm.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>我的二维码</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <div class="empty1"></div>
                    <a class="weui_cell js_cell" href="javascript:;" data-id="privilege">
                        <span class="weui_cell_hd"><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat/icon-wdzxtq.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>我的专享特权</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <div class="empty2">商家优惠活动</div>
                    <a class="weui_cell js_cell" href="javascript:;" data-id="sale1">
                        <span class="weui_cell_hd"><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat/icon-wdyh.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>满减</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <a class="weui_cell js_cell" href="javascript:;" data-id="sale2">
                        <span class="weui_cell_hd"><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat/icon-wdyh.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>满送</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <div class="empty1"></div>
                    <a class="weui_cell js_cell" href="javascript:;" data-id="info">
                        <span class="weui_cell_hd"><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat/icon-grxx.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>个人信息</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <a class="weui_cell js_cell" href="javascript:;" data-id="bill">
                        <span class="weui_cell_hd"><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat/icon-zd.png" class="icon_nav" alt=""></span>
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
    
     <script type="text/html" id="tpl_money">
       <div class="page">
           <div class="money1">
               我的储值余额(元)
           </div>
           <div class="money2">
               0.00
           </div>
           <div class="empty2">储值有优惠<span>(暂不支持网上支付，请店内充值)</span></div>
           <div class="bd"> 
               <div class="weui_cells weui_cells_access global_navs">
                    <a class="weui_cell js_cell" href="javascript:;" data-id="money">
                        <span class="weui_cell_hd "><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat/icon-wdcz.png" class="icon_nav" alt=""/></span>
                        
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>充值600元，送50元</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>         
                    </a>
                    <a class="weui_cell js_cell" href="javascript:;" data-id="point">
                        <span class="weui_cell_hd"><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat/icon-wdcz.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>充值1000元，送100元</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <a class="weui_cell js_cell" href="javascript:;" data-id="ticket">
                        <span class="weui_cell_hd"><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat/icon-wdcz.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>充值2000元，送240元</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <a class="weui_cell js_cell" href="javascript:;" data-id="code">
                        <span class="weui_cell_hd"><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat/icon-wdcz.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>充值5000元，送750元</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                   
                 </div>
           </div> 
        </div>   
    </script>
    <script type="text/html" id="tpl_point">
       <div class="page">
           
           <div class="point_style">
                <div class="point1">
                    <span> 我的积分(元)</span>
                </div>
                <div class="point2">
                    <span>0</span>
                </div>
           </div>
           <div class="bd"> 
               <div class="weui_cells weui_cells_access global_navs">
                    <a class="weui_cell js_cell" href="javascript:;" data-id="money">
                        <span class="weui_cell_hd "></span>
                       
                            <div class="weui_cell_bd weui_cell_primary">
                                <p>积分换礼</p>
                            </div>
                            <div class="weui_cell_ft">
                            </div> 
                       
                    </a>
                    <a class="weui_cell js_cell" href="javascript:;" data-id="point">
                        <span class="weui_cell_hd"></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>积分记录</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
   
                 </div>
           </div> 
        </div>   
    </script>
 </body>        
    

    
 
