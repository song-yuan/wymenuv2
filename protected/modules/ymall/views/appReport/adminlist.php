<?php $basePath = Yii::app()->baseUrl;?>
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/app.css">
<header class="mui-bar mui-bar-nav">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <h1 class="mui-title">店铺分组</h1>
</header>
<div id="pullrefresh" class="mui-content mui-scroll-wrapper">
            <div class="mui-scroll">
    <ul class="mui-table-view"> 
    <?php foreach($fens as $fen):?>
        <li class="mui-table-view-cell <?php if(!empty($fen['group_name'])):?> mui-collapse<?php endif;?>">
        <?php if(!empty($fen['group_name'])):?>
            <a class="mui-navigate-right" href="#"><?php echo $fen['group_name'];?></a>
        <?php endif;?>   
            <?php if(!empty($fen['group_name'])){
                $sql = "select area_group_id,company_id,dpid,company_name,logo,address from (select area_group_id,company_id from nb_area_group_company where area_group_id=".$fen['lid']." and delete_flag=0) c inner join (select dpid,company_name,logo,address from nb_company where type=1 and delete_flag=0) y on c.company_id=y.dpid";
                // echo $sql;exit;
            $admindpids = Yii::app()->db->createCommand($sql)->queryAll();
             // var_dump($admindpids);exit;
                }else{
                    $sql = "select dpid,company_name,logo,address from nb_company where dpid=".$companyId." and delete_flag=0";
                    $admindpids = Yii::app()->db->createCommand($sql)->queryRow();
                    // var_dump($admindpids);exit;
                    }?>
            <div class="mui-collapse-content" style="overflow: auto;height: 200px;">
            <?php if(!empty($fen['lid'])):?>
            <div style="height: 20px;"><a style="text-align: right;position:fixed;left: 270px;" href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$companyId,'type'=>$fen['lid']));?>">多店统计>></a>
            </div>
            <?php endif;?>
            <?php if(!empty($fen['lid'])){?>
            <?php foreach ($admindpids as $dp):?>
                <?php if($fen['lid']==$dp['area_group_id']):?>
                <a style="display:block;height: 45px;margin-bottom: 10px;" href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$dp['dpid'],'type'=>'1'));?>">
                    <img class="mui-media-object mui-pull-left" src="<?php echo $dp['logo'];?>">
                    <div class="mui-media-body">
                        <?php echo $dp['company_name']?>
                        <p class='mui-ellipsis'><?php echo $dp['address'];?></p>
                    </div>
                </a>
                <?php endif;?>
            <?php endforeach;?>
            </div> 
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
    <?php endforeach;?>
    </ul>
    </div>
</div>
