    
    <?php foreach ($models_category as $mc):?>
        <div class="client_category_title"><?php echo $mc->name; ?></div>
        <div class="client_sitelist_in">
            <ul>
            <?php foreach ($mc->site as $mcsl):?>
                <li class="siteaction <?php if($mcsl->status=='1') echo 'bg-yellow'; elseif($mcsl->status=='2') echo 'bg-blue'; elseif($mcsl->status=='3') echo 'bg-green';?>" istemp="1" status=<?php echo $mcsl->status;?> sid=<?php echo $mcsl->lid;?> ><span style="font-size: 25px;"><?php echo $mcsl->serial;?>&nbsp;</span><br><?php echo $mcsl->create_at;?></li>        
            <?php endforeach;?>
            </ul>
        </div>
    <?php endforeach;?>
    
     <div class="client_category_title">临时台</div>
    <div class="client_sitelist_in">
        <ul>
            <li class="siteaction bg_add" istemp="1" status="0" sid="0"></li>
            <?php foreach ($models_temp as $mt):?>
            <li class="siteaction <?php if($mt->status=='1') echo 'bg-yellow'; elseif($mt->status=='2') echo 'bg-blue'; elseif($mt->status=='3') echo 'bg-green';?>" istemp="1" status=<?php echo $mt->status;?> sid=<?php echo $mt->site_id;?> ><span style="font-size: 25px;"><?php echo $mt->site_id%1000;?>&nbsp;</span><br><?php echo $mt->create_at;?></li>
            <?php endforeach;?>    

        </ul>
    </div>
<script type="text/javascript">
	$('.siteaction').on(event_clicktouchstart, function(){
                //layer页面层
    	var str = '<a herf="javascript:;" class="pay-type cash-color" id="cashpay">柜台支付</a><a herf="javascript:;" class="pay-type wx-color" id="weixinpay">微信支付</a><a herf="javascript:;" class="pay-type zfb-color" id="zhifubaopay">支付宝支付</a>';
		layer.open({
		    type: 1,
		    skin: 'layui-layer-rim', //加上边框
		    area: ['420px', '240px'], //宽高
		    content: str
		});
            });      
        
       
</script>