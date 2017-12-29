<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('我的储值');
?>
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/wechat_css/weui.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/wechat_css/example.css">
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
.empty2 .tab{
	padding:10px;
	margin:10px;
	float:left;
}
.empty2 .tab-active{
	border-bottom:1px solid #04BE02;
}
.record span{
	width:33.33%;
	text-align:center;
}
.circle{
	width:6px;
	height:6px;
	border-radius:3px;
	background:#787878;
	margin-right:5px;
}
</style>
<div class="page">
    <div class="item1">
        <div class="money1 clearfix">
	        <span class="left">我的储值余额(元)</span>
	        <?php if($user['weixin_group']!=50):?>
	        <a href="<?php echo $this->createUrl('/mall/reCharge',array('companyId'=>$this->companyId));?>"><span class="right">去充值 ></span></a>
	        <?php endif;?>
        </div>
        <div class="money2"><?php echo number_format($remainMoey,2);?></div>
     </div>
     <?php if(!empty($comments)):?>
     <div class="empty2">储值说明</div>
     <div class="bd"> 
        <div class="weui_cells weui_cells_access global_navs">
           	 <?php foreach ($comments as $comment):?>
             <a class="weui_cell js_cell" href="javascript:;" style="font-size:14px;padding:5px 15px;">
                 <div class="weui_cell_hd "><div class="circle"></div></div>
                 <div class="weui_cell_bd weui_cell_primary">
                     <p><?php echo $comment['content'];?></p>
                  </div>
              </a>
              <?php endforeach;?>
         </div>
      </div>
      <?php endif;?>
      <div class="empty2 clearfix"><div class="tab tab-active" for="recharge">充值记录</div><div class="tab" for="consumer">消费记录</div></div>
      <div class="tab-content recharge"> 
        <div class="weui_cells weui_cells_access global_navs">
        	<a class="weui_cell js_cell" href="javascript:;" >
                 <div class="weui_cell_bd weui_cell_primary record clearfix">
                 	<span class="left">充值时间</span><span class="left">充值金额</span><span class="left">返现金额</span>
                  </div>
              </a>
           	 <?php foreach ($records as $record):?>
             <a class="weui_cell js_cell" href="javascript:;" style="font-size:14px;">
                 <div class="weui_cell_bd weui_cell_primary record clearfix">
                 	<span class="left"><?php echo date('Y-m-d',strtotime($record['create_at']));?></span><span class="left"><?php echo $record['recharge_money'];?></span><span class="left"><?php echo $record['cashback_num'];?></span>
                  </div>
              </a>
              <?php endforeach;?>
         </div>
         <?php if(count($records) < 10):?>
          <div class="more" style="text-align:center;margin-bottom:20px;display:none;">点击查看更多</div>
          <?php else:?>
          <div class="more" style="text-align:center;margin-bottom:20px;">点击查看更多</div>
          <?php endif;?>
      </div>
      <div class="tab-content consumer" style="display:none;"> 
        <div class="weui_cells weui_cells_access global_navs">
        	<a class="weui_cell js_cell" href="javascript:;" >
                 <div class="weui_cell_bd weui_cell_primary record clearfix">
                 	<span class="left">消费时间</span><span class="left">消费金额</span><span class="left">类型</span>
                  </div>
              </a>
           	 <?php foreach ($consumes as $consume):?>
             <a class="weui_cell js_cell" href="javascript:;" style="font-size:14px;">
                 <div class="weui_cell_bd weui_cell_primary record clearfix">
                 	<span class="left"><?php echo date('Y-m-d',strtotime($consume['create_at']));?></span><span class="left"><?php echo $consume['consume_amount'];?></span><span class="left"><?php if($consume['consume_type']=='3'){ echo '退款';}else{ echo '消费';}?></span>
                  </div>
              </a>
              <?php endforeach;?>
         </div>
         <?php if(count($consumes) < 10):?>
          <div class="more" style="text-align:center;margin-bottom:20px;display:none;">点击查看更多</div>
          <?php else:?>
          <div class="more" style="text-align:center;margin-bottom:20px;">点击查看更多</div>
          <?php endif;?>
      </div>
</div>   
<script>
$(document).ready(function(){
	var page = 1;
	$('.tab').click(function(){
		var typeTigger = $(this).attr('for');
		$('.tab-active').removeClass('tab-active');
		$(this).addClass('tab-active');
		$('.tab-content').hide();
		$('.'+typeTigger).show();
	});
	$('.more').click(function(){
		var typeTigger = $('.tab-active').attr('for');
		if(typeTigger=='recharge'){
			$.ajax({
				url:"<?php echo $this->createUrl('/user/ajaxMemberRecharge',array('companyId'=>$this->companyId,'userId'=>$user['lid']));?>",
				data:{page:page},
				success:function(msg){
					if(msg.length > 0){
						var str = '';
						for(var i=0;i<msg.length;i++){
							var obj = msg[i];
							str += '<a class="weui_cell js_cell" href="javascript:;" style="font-size:14px;">'
				                 +'<div class="weui_cell_bd weui_cell_primary record clearfix">'
				                 +'<span class="left">'+obj.create_at.substr(0,10)+'</span><span class="left">'+obj.recharge_money+'</span><span class="left">'+obj.cashback_num+'</span>'
				                 +'</div>'
				                 +'</a>';
						}
						$('.recharge').find('.weui_cells').append(str);
						page++;
					}
				},
				dataType:'json'
			});
		}else if(typeTigger=='consumer'){
			$.ajax({
					url:"<?php echo $this->createUrl('/user/ajaxMemberConsume',array('companyId'=>$this->companyId,'userId'=>$user['lid']));?>",
					data:{page:page},
					success:function(msg){
						if(msg.length > 0){
							var str = '';
							for(var i=0;i<msg.length;i++){
								var obj = msg[i];
								if(obj.consume_type=='3'){
									var typeStr = '退款';
								}else{
									var typeStr = '消费';
								}
								str += '<a class="weui_cell js_cell" href="javascript:;" style="font-size:14px;">'
					                 +'<div class="weui_cell_bd weui_cell_primary record clearfix">'
					                 +'<span class="left">'+obj.create_at.substr(0,10)+'</span><span class="left">'+obj.consume_amount+'</span><span class="left">'+typeStr+'</span>'
					                 +'</div>'
					                 +'</a>';
							}
							$('.consumer').find('.weui_cells').append(str);
							page++;
						}
					},
					dataType:'json'
			});
		}
	});	
});
</script>