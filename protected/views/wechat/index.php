<?php// Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/wechat/zepto.min.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/wechat/example.js');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/wechat/weui.css');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/wechat/example.css');?>

<html>
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
                    <a class="weui_cell js_cell" href="javascript:;" data-id="button">
                        <span class="weui_cell_hd "><img src="img/icon_nav_button.png" class="icon_nav" alt=""></span>
                        
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>我的储值</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                           
                    </a>
                    <a class="weui_cell js_cell" href="javascript:;" data-id="cell">
                        <span class="weui_cell_hd"><img src="img/icon_nav_cell.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>我的积分</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <a class="weui_cell js_cell" href="javascript:;" data-id="toast">
                        <span class="weui_cell_hd"><img src="img/icon_nav_toast.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>我的券</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <a class="weui_cell js_cell" href="javascript:;" data-id="dialog">
                        <span class="weui_cell_hd"><img src="img/icon_nav_dialog.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>我的二维码</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <div class="empty1"></div>
                    <a class="weui_cell js_cell" href="javascript:;" data-id="progress">
                        <span class="weui_cell_hd"><img src="img/icon_nav_button.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>我的专享特权</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <div class="empty2">商家优惠活动</div>
                    <a class="weui_cell js_cell" href="javascript:;" data-id="msg">
                        <span class="weui_cell_hd"><img src="img/icon_nav_msg.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>满减</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <a class="weui_cell js_cell" href="javascript:;" data-id="article">
                        <span class="weui_cell_hd"><img src="img/icon_nav_article.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>满送</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <div class="empty1"></div>
                    <a class="weui_cell js_cell" href="javascript:;" data-id="actionSheet">
                        <span class="weui_cell_hd"><img src="img/icon_nav_actionSheet.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>个人信息</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <a class="weui_cell js_cell" href="javascript:;" data-id="icons">
                        <span class="weui_cell_hd"><img src="img/icon_nav_icons.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>账单</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <div class="empty1"></div>
                     <a class="weui_cell js_cell" href="javascript:;" data-id="icons">
                        <span class="weui_cell_hd"><img src="img/icon_nav_icons.png" class="icon_nav" alt=""></span>
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
 
</body>>