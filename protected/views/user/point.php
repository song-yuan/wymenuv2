<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('我的积分');
?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/wechat_css/weui.css');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/wechat_css/example.css');?>
<style>
.point_style{
    margin-top: 0px;
    background-color: #04BE02;
    color:#FFFFFF;
}
.point1{
    font-size: 17px;
    padding:20px;    
}
.point2{
    font-size: 50px;
    padding-right: 15px;
    text-align: center;
}
</style>
     <div class="page">
           <div class="point_style">
                <div class="point1">
                    <span> 我的积分(个)</span>
                </div>
                <div class="point2">
                    <span><?php echo $remain_points ?></span>
                </div>
           </div>
           <div class="bd"> 
               <div class="weui_cells weui_cells_access global_navs">
                   
                    <a class="weui_cell js_cell" href="<?php echo $this->createUrl('user/pointRecord',array('companyId'=>$this->companyId));?>" >
                        <span class="weui_cell_hd"></span>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>积分记录</p>
                        </div>
                        <div class="weui_cell_ft">
                        </div>
                    </a>
                     <a class="weui_cell js_cell" href="javascript:;" >
                        <span class="weui_cell_hd "></span>
                       
                            <div class="weui_cell_bd weui_cell_primary">
                                <p>积分换礼(敬请期待)</p>
                            </div>
                            <div class="weui_cell_ft">
                            </div> 
                    </a>
                 </div>
           </div> 
        </div>   
