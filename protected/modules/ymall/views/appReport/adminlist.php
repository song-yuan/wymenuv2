<?php $basePath = Yii::app()->baseUrl;?>
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/app.css">
<header class="mui-bar mui-bar-nav">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <h1 class="mui-title">店铺分组</h1>
</header>
<div class="yy">
<?php foreach($fens as $fen):?>
    <ul class="mui-table-view"> 
        <li class="mui-table-view-cell mui-collapse">
            <a class="mui-navigate-right" href="#"><?php echo $fen['group_name'];?></a>
            <?php foreach ($admindpids as $dp):?>
            <?php if($fen['lid']==$dp['area_group_id']):?>
                <div class="mui-collapse-content">
                <a href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$dp['dpid']));?>">
                    <img class="mui-media-object mui-pull-left" src="<?php echo $dp['logo'];?>">
                    <div class="mui-media-body">
                        <?php echo $dp['company_name']?>
                        <p class='mui-ellipsis'><?php echo $dp['address'];?></p>
                    </div>
                </a>
            </div>
            <?php endif;?>
            <?php endforeach;?>
        </li>
    </ul>
    <div style="text-align: right;margin-right: 10px;">
        <a href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$companyId,'type'=>$fen['lid']));?>">统计>></a>
    </div>
    <?php endforeach;?>
</div>