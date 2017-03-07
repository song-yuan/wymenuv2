<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/wechat_css/weui.css');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/wechat_css/example.css');?>
<style>
.page{
 background-color: #EDEDED; 
}
.weui_cell{
    background-color: white; 
}
</style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
 
</head>
<body>
    <div class="page">
        <div class="bd"> 
            <div class="weui_cells weui_cells_access global_navs">
                <div class="empty1"></div>
                <a class="weui_cell js_cell" href="javascript:;" data-id="tel">
                    <span class="weui_cell_hd"></span>
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>手机</p>
                    </div>
                    <div class="weui_cell_ft">
                    </div>
                </a>
                <div class="empty1"></div>
                <a class="weui_cell js_cell" href="javascript:;" data-id="tel">
                    <span class="weui_cell_hd"></span>
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>姓名</p>
                    </div>
                    <div class="weui_cell_ft">
                    </div>
                </a>
                <a class="weui_cell js_cell" href="javascript:;" data-id="tel">
                    <span class="weui_cell_hd"></span>
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>性别</p>
                    </div>
                    <div class="weui_cell_ft">
                    </div>
                </a>
                <a class="weui_cell js_cell" href="javascript:;" data-id="tel">
                    <span class="weui_cell_hd"></span>
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>生日</p>
                    </div>
                    <div class="weui_cell_ft">
                    </div>
                </a>
            </div>
        </div>
    </div>   
</body>
<script>
</script>
