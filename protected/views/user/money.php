<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('我的储值');
?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/wechat_css/weui.css');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/wechat_css/example.css');?>
<style>
.item1{
    margin-top: 0px;
    background-color: #04BE02;
    color:#FFFFFF;
}
.item1 a{
 	color:#FFFFFF;
}
.money1{
    font-size: 17px;
    padding:20px;    
}
.money2{
    font-size: 50px;
    padding-right: 15px;
    text-align: center;
}
.record span{
	width:25%;
	text-align:center;
}
</style>
<div class="page">
    <div class="item1">
        <div class="money1 clearfix"><span class="left">我的储值余额(元)</span><a href="<?php echo $this->createUrl('/mall/recharge',array('companyId'=>$this->companyId));?>"><span class="right">去充值 ></span></a></div>
        <div class="money2">0.00</div>
     </div>
     <?php if(!empty($recharges)):?>
     <div class="empty2">充值有优惠</div>
     <div class="bd"> 
        <div class="weui_cells weui_cells_access global_navs">
           	 <?php foreach ($recharges as $recharge):?>
             <a class="weui_cell js_cell" href="javascript:;" >
                 <span class="weui_cell_hd "><img src="<?php echo Yii::app()->request->baseUrl;?>/img/wechat_img/icon-wdcz.png" class="icon_nav" alt=""/></span>
                 <div class="weui_cell_bd weui_cell_primary">
                     <p>充值<?php echo $recharge['recharge_money'];?>元，送<?php echo $recharge['recharge_cashback'];?>元 <?php echo $recharge['recharge_pointback'];?>积分</p>
                  </div>
              </a>
              <?php endforeach;?>
         </div>
      </div>
      <?php endif;?>
      <div class="empty2">充值记录</div>
      <div class="bd"> 
        <div class="weui_cells weui_cells_access global_navs">
        	<a class="weui_cell js_cell" href="javascript:;" >
                 <div class="weui_cell_bd weui_cell_primary record">
                 	<span class="left">充值时间</span><span class="left">充值金额</span><span class="left">返现金额</span><span class="left">赠送积分</span>
                  </div>
              </a>
           	 <?php foreach ($records as $record):?>
             <a class="weui_cell js_cell" href="javascript:;" >
                 <div class="weui_cell_bd weui_cell_primary record clearfix">
                 	<span><?php echo $record['create_at'];?></span><span><?php echo $record['recharge_money'];?></span><span><?php echo $record['cashback_num'];?></span><span><?php echo $record['point_num'];?></span>
                  </div>
              </a>
              <?php endforeach;?>
         </div>
      </div>
</div>   
