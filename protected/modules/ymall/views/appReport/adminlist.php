<?php $basePath = Yii::app()->baseUrl;?>
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/app.css">
<header class="mui-bar mui-bar-nav">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <h1 class="mui-title">店铺分组</h1>
</header>
<div class="yy">
    <ul class="mui-table-view"> 
    <?php foreach($array as $key=>$fens):?>
        <li class="mui-table-view-cell <?php if(isset($key)):?> mui-collapse<?php endif;?>">
        <?php if(isset($key)):?>
            <a class="mui-navigate-right" href="#"><?php echo $key;?></a>
        <?php endif;?>
            <div class="mui-collapse-content" style="overflow: auto;">
            <?php if(count($fens)>1):?>
                <?php foreach($fens as $fen):?>
             <div style="height: 20px;"><a style="text-align: right;position:fixed;left: 270px;" href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$companyId,'type'=>$fen['lid']));?>">多店统计>></a>
                </div>
            <?php break; endforeach;?>
            <?php endif;?>
            <?php foreach($fens as $fen):?>
                <a style="display:block;height: 50px;margin-bottom: 10px;" href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$fen['dpid']));?>">
                    <img class="mui-media-object mui-pull-left" src="<?php echo $fen['logo'];?>">
                    <div class="mui-media-body">
                        <?php echo $fen['company_name']?>
                        <p class='mui-ellipsis'><?php echo $fen['address'];?></p>
                    </div>
                </a>
                 <?php endforeach;?>
                
            </div> 

             
        </li>
    <?php endforeach;?>
    </ul>
</div>
