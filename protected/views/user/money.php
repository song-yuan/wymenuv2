<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('我的储值');
?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/wechat_css/weui.css');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/wechat_css/example.css');?>
<style>
.money1{
    font-size: 17px;
    margin:20px 0px 10px 20px;
}
.money2{
    font-size: 50px;
    text-align: center;
}
</style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">

</head>
<body>
     
       <div class="page">
           <div class="money1">
               我的储值余额(元)
           </div>
           <div class="money2">
               0.00
           </div>
           <div class="empty2">储值有优惠</div>
           <div class="bd"> 
               <div class="weui_cells weui_cells_access global_navs">
                    <a class="weui_cell js_cell" href="javascript:;" >
                        <span class="weui_cell_hd "><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat_img/icon-wdcz.png" class="icon_nav" alt=""/></span>
                        
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>充值600元，送50元</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>         
                    </a>
                    <a class="weui_cell js_cell" href="javascript:;" >
                        <span class="weui_cell_hd"><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat_img/icon-wdcz.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>充值1000元，送100元</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <a class="weui_cell js_cell" href="javascript:;" >
                        <span class="weui_cell_hd"><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat_img/icon-wdcz.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>充值2000元，送240元</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                    <a class="weui_cell js_cell" href="javascript:;" >
                        <span class="weui_cell_hd"><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat_img/icon-wdcz.png" class="icon_nav" alt=""></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>充值5000元，送750元</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                   
                 </div>
           </div> 
        </div>   
  
   </body>