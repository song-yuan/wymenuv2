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
	width:33.33%;
	text-align:center;
}
</style>
<div class="page">
    <div class="item1">
        <div class="money1 clearfix"><span class="left">我的储值余额(元)</span><a href="<?php echo $this->createUrl('/mall/reCharge',array('companyId'=>$this->companyId));?>"><span class="right">去充值 ></span></a></div>
        <div class="money2"><?php echo number_format($remainMoey,2);?></div>
     </div>
     <?php if(!empty($comments)):?>
     <div class="empty2">储值说明</div>
     <div class="bd"> 
        <div class="weui_cells weui_cells_access global_navs">
           	 <?php foreach ($comments as $comment):?>
             <a class="weui_cell js_cell" href="javascript:;" style="font-size:14px;padding:5px 15px;">
                 <div class="weui_cell_bd weui_cell_primary">
                     <p><?php echo $comment['content'];?></p>
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
                 <div class="weui_cell_bd weui_cell_primary record clearfix">
                 	<span class="left">充值时间</span><span class="left">充值金额</span><span class="left">返现金额</span>
                  </div>
              </a>
           	 <?php foreach ($records as $record):?>
             <a class="weui_cell js_cell" href="javascript:;" >
                 <div class="weui_cell_bd weui_cell_primary record clearfix">
                 	<span class="left"><?php echo date('Y-m-d',strtotime($record['create_at']));?></span><span class="left"><?php echo $record['recharge_money'];?></span><span class="left"><?php echo $record['cashback_num'];?></span>
                  </div>
              </a>
              <?php endforeach;?>
         </div>
      </div>
</div>   
