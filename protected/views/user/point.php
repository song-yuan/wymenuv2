<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('我的积分');
?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/wechat_css/weui.css');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/wechat_css/example.css');?>

     <div class="page">
           
           <div class="point_style">
                <div class="point1">
                    <span> 我的积分(元)</span>
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
                                <p>积分换礼</p>
                            </div>
                            <div class="weui_cell_ft">
                            </div> 
                       
                    </a>
                 </div>
           </div> 
        </div>   
