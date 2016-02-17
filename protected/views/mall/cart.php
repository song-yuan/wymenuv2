<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('购物车');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/cart.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/Adaptive.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl.'/js/layer/layer.js';?>"></script>

<form action="<?php echo $this->createUrl('/mall/checkOrder',array('companyId'=>$this->companyId,'type'=>$this->type));?>" method="post">
<?php if($this->type==1):?>
<div class="site_no">桌号:<input type="text" class="serial" name="serial" value="<?php if($siteType){echo $siteType['name'];}?>><?php echo isset($site['serial'])?$site['serial']:'';?>" placeholder="输入座位号" />人数: <input type="button" class="num-minus"  value="-"><input type="text" class="number" name="number" value="<?php if($siteNum){ echo (int)(($siteNum['min_persons'] + $siteNum['max_persons'])/2);}else{echo '3';}?>" readonly="readonly"/> <input type="button" class="num-add"  value="+"></div>
<?php endif;?>
<div class="section" style="padding-top:10px;">
    <div class="prt">
        <div class="prt-rt-del" id="clearCart" style="float:right;padding-right:30px;text-align:right;color:#FF5151; background-image: url(<?php echo $baseUrl;?>/img/icon_delete.png);background-size: auto 25px;background-repeat: no-repeat; background-position: right center;">清空全部</div>
        <div class="clear"></div>
    </div>
    <?php if(!empty($orderTastes)):?>
    <div class="taste">整单口味</div><div class="taste-desc"></div>
    <div class="taste-items" product-id="0">
    	<?php foreach($orderTastes as $k=>$groups):?>
    	<div class="item-group">
    		<div class="item group"><?php echo $groups['name'];?></div>
    		<?php foreach($groups['tastes'] as $taste):?>
    			<div class="item t-item" group="<?php echo $k;?>" taste-id="<?php echo $taste['lid'];?>"><?php echo $taste['name'];?></div>
    		<?php endforeach;?>
    		<input type="hidden" name="taste[]" value="0" />
    		<div class="clear"></div>
    	</div>
    	<?php endforeach;?>
    </div>
    <?php endif;?>
</div>
<?php foreach($models as $model):?>
<div class="section">
	<!--
    <div class="prt-cat">/div>
    -->
    <div class="prt">
        <div class="prt-lt"><?php echo $model['product_name']?></div>
        <div class="prt-mt">￥<span class="price"><?php echo $model['price']?></span></div>
        <div class="prt-rt">
            <input type="button" class="minus"  value="-">
            <input type="text" class="result" product-id="<?php echo $model['product_id'];?>" promote-id="<?php echo $model['privation_promotion_id']?>" disabled value="<?php echo $model['num']?>">
            <input type="button" class="add" value="+">
        </div>
        <div class="clear"></div>
    </div>
    <?php if(!empty($model['taste_groups'])):?>
    <div class="taste">可选口味</div><div class="taste-desc"></div>
    <div class="taste-items" product-id="<?php echo $model['product_id'];?>">
    	<?php foreach($model['taste_groups'] as $k=>$groups):?>
    	<div class="item-group">
    		<div class="item group"><?php echo $groups['name'];?></div>
    		<?php foreach($groups['tastes'] as $taste):?>
    			<div class="item t-item" group="<?php echo $k;?>" taste-id="<?php echo $taste['lid'];?>"><?php echo $taste['name'];?></div>
    		<?php endforeach;?>
    		<input type="hidden" name="taste[]" value="0" />
    		<div class="clear"></div>
    	</div>
    	<?php endforeach;?>
    </div>
    <?php endif;?>
</div>
<?php endforeach;?>
<div class="bottom"></div>
<footer>
    <div class="ft-llt">
        <p><a href="<?php echo $this->createUrl('/mall/index',array('companyId'=>$this->companyId,'type'=>$this->type));?>">返回</a></p>
    </div>
    <div class="ft-lmt">
        <p>￥<span id="total" class="total">0.00</span></span></p>
    </div>
    <div class="ft-rrt">
        <p><a class="checkOrder" href="javascript:;">确认下单</a></p>
    </div>
    <div class="clear"></div>
</footer>
</form>

<script> 
$(document).ready(function(){ 
	$('.checkOrder').click(function(){
		<?php if($this->type==1):?>
		var serial = $('.serial').val();
		var number = $('.number').val();
		if(serial && number){
			if(isNaN(number)||(parseInt(number)!=number)||number < 0){
				layer.msg('输入人数为大于0的整数!');
				return;
			}
			$('form').submit();
		}else{
			if(!serial){
				layer.msg('请输入座位号!');
				return;
			}
			if(!number){
				layer.msg('请输入人数!');
				return;
			}
			
		}
		<?php else:?>
		$('form').submit();
		<?php endif;?>
	});
	
	$('.num-minus').click(function(){
		var number = $('.number').val();
		if(parseInt(number) > 1 ){
			$('.number').val(parseInt(number)-1);
		}else{
			$('.number').val(1);
		}
	});
	
	$('.num-add').click(function(){
		var number = $('.number').val();
		$('.number').val(parseInt(number)+1);
	});
	
    $(".add").click(function(){
        var t=$(this).parent().find('input[class*=result]');
        var productId = t.attr('product-id');
        var promoteId = t.attr('promote-id');
        
        var timestamp=new Date().getTime()
        var random = ''+timestamp + parseInt(Math.random()*899+100)+'';
        
        $.ajax({
        	url:'<?php echo $this->createUrl('/mall/addCart',array('companyId'=>$this->companyId));?>',
        	data:{productId:productId,promoteId:promoteId,random:random},
        	success:function(msg){
        		if(msg.status){
        			 t.val(parseInt(t.val())+1);
			        if(parseInt(t.val()) > 0){
			            t.siblings(".minus").removeClass('zero');
			            t.removeClass('zero');
			        }
			        setTotal();
        		}else{
        			layer.msg(msg.msg);
        		}
        	},
        	dataType:'json'
        });
    });
     
    $(".minus").click(function(){ 
        var t=$(this).parent().find('input[class*=result]');
        var productId = t.attr('product-id');
        var promoteId = t.attr('promote-id');
        
        var timestamp=new Date().getTime()
        var random = ''+timestamp + parseInt(Math.random()*899+100)+'';
        
        $.ajax({
        	url:'<?php echo $this->createUrl('/mall/deleteCart',array('companyId'=>$this->companyId));?>',
        	data:{productId:productId,promoteId:promoteId,random:random},
        	success:function(msg){
        		if(msg.status){
    			  if(parseInt(t.val())==1){
			          t.parents('.section').remove();
			       }
			       t.val(parseInt(t.val())-1);
			       if(parseInt(t.val())<0){ 
			           t.val(0); 
			   	    } 
			    	setTotal(); 
        		}else{
        			layer.msg(msg.msg);
        		}
        	},
        	dataType:'json'
        });
   });
  
  $('#clearCart').click(function(){
  		  $.ajax({
        	url:'<?php echo $this->createUrl('/mall/deleteCart',array('companyId'=>$this->companyId,'all'=>1));?>',
        	success:function(msg){
        		if(msg.status){
			        location.href = '<?php echo $this->createUrl('/mall/index',array('companyId'=>$this->companyId));?>';
        		}else{
        			layer.msg(msg.msg);
        		}
        	},
        	dataType:'json'
        });
  });
  $('.taste').click(function(){
  	var _this = $(this);
  	layer.open({
	    type: 1,
	    title: false,
	    shadeClose: true,
	    closeBtn: 0,
	    area: ['80%'],
	    content:_this.siblings('.taste-items'),
	    btn: '确定',
	    yes: function(index, layero){ 
         layer.close(index);
    	}
	});
  });
  $('.t-item').click(function(){
  	var tasteItems = $(this).parents('.taste-items');
  	var tasteDesc = $(this).parents('.section').find('.taste-desc');
  	var productId = tasteItems.attr('product-id');
  	var tasteId = $(this).attr('taste-id');
  	var group =  $(this).attr('group');
  	var tastName = $(this).html();
  	
  	if($(this).hasClass('on')){
  		$(this).removeClass('on');
  		$(this).siblings('input').val(0);
  		tasteDesc.find('#'+group+'-'+tasteId).remove();
  	}else{
  		$(this).siblings().removeClass('on');
	  	$(this).addClass('on');
	  	$(this).siblings('input').val(productId+'-'+tasteId);
	  	tasteDesc.find('span[id^='+group+'-]').remove();
	  	var str = '<span id="'+group+'-'+tasteId+'">'+tastName+'</span>';
	  	tasteDesc.append(str);
  	}
  });
function setTotal(){ 
    var s=0;
    var v=0;
    var n=0;
    <!--计算总额--> 
    $(".prt-rt").each(function(){ 
   		 s+=parseInt($(this).find('input[class*=result]').val())*parseFloat($(this).siblings().find('span[class*=price]').text()); 
    });
    $("#total").html(s.toFixed(2)); 
} 
setTotal(); 

}) 
</script> 