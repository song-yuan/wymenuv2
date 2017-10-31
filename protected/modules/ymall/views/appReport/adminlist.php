<?php $basePath = Yii::app()->baseUrl;?>
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/app.css">
<header class="mui-bar mui-bar-nav">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <h1 class="mui-title">店铺分组</h1>
</header>
<div class="yy">
<?php foreach($fens as $fen):?>
    <ul class="mui-table-view"> 
        <li class="mui-table-view-cell <?php if(!empty($fen['group_name'])):?> mui-collapse<?php endif;?>">
        <?php if(!empty($fen['group_name'])):?>
            <a class="mui-navigate-right" href="#"><?php echo $fen['group_name'];?></a>
        <?php endif;?>   
            <?php if(!empty($fen['group_name'])){
                $sql = "select lid,group_name,area_group_id,company_id,dpid,company_name,logo,address from ((select lid,group_name from nb_area_group where type=3 and delete_flag=0) p left join (select area_group_id,company_id from nb_area_group_company where delete_flag=0) c on p.lid=c.area_group_id) left join (select dpid,company_name,logo,address from nb_company where delete_flag=0) y on c.company_id=y.dpid";
            $admindpids = Yii::app()->db->createCommand($sql)->queryAll();
                }else{
                    $sql = "select dpid,company_name,logo,address from nb_company where delete_flag=0";
                    $admindpids = Yii::app()->db->createCommand($sql)->queryRow();
                    }?>
           
    <?php if(!empty($fen['lid'])){?>
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
            <?php }else{?>
                <div class="mui-collapse-content">
                <a href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$admindpids['dpid']));?>">
                    <img class="mui-media-object mui-pull-left" src="<?php echo $admindpids['logo'];?>">
                    <div class="mui-media-body">
                        <?php echo $admindpids['company_name']?>
                        <p class='mui-ellipsis'><?php echo $admindpids['address'];?></p>
                    </div>
                </a>
            </div>
            <?php }?>
           
        </li>
    </ul>
    <?php if(!empty($fen['lid'])):?>
    <div style="text-align: right;margin-right: 10px;">
        <a href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$companyId,'type'=>$fen['lid']));?>">统计>></a>
    </div>
    <?php endif;?>
    <?php endforeach;?>
</div>