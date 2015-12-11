<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('购物车');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/cart.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/Adaptive.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl.'/js/layer/layer.js';?>"></script>

<?php if($this->type==1):?>
<div class="site_no">桌号:<input type="text" class="serial" value="<?php if($siteType){echo $siteType['name'];}?>><?php echo isset($site['serial'])?$site['serial']:'';?>" placeholder="输入座位号" />人数:<input type="number" class="number" value="" placeholder="输入人数" /></div>
<?php endif;?>
<div class="section" style="padding-top:0;color:#FF5151;">
    <div class="prt">
        <div class="prt-rt-del" id="clearCart" style="float:right;padding-right:30px;text-align:right; background-image: url(<?php echo $baseUrl;?>/img/icon_delete.png);background-size: auto 25px;background-repeat: no-repeat; background-position: right center;">清空全部</div>
        <div class="clear"></div>
    </div>
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


<script> 
$(document).ready(function(){ 
	<?php if(isset($msg)&&$msg):?>
	layer.msg('<?php echo $msg;?>');
	<?php endif;?>
	$('.checkOrder').click(function(){
		var serial = $('.serial').val();
		var number = $('.number').val();
		if(serial && number){
			if(isNaN(number)||(parseInt(number)!=number)||number < 0){
				layer.msg('输入人数为大于0的整数!');
				return;
			}
			location.href = '<?php echo $this->createUrl('/mall/generalOrder',array('companyId'=>$this->companyId,'type'=>$this->type));?>&serial='+serial+'&number='+number;
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
	});
	
    $(".add").click(function(){
        var t=$(this).parent().find('input[class*=result]');
        var productId = t.attr('product-id');
        var promoteId = t.attr('promote-id');
        $.ajax({
        	url:'<?php echo $this->createUrl('/mall/addCart',array('companyId'=>$this->companyId));?>',
        	data:{productId:productId,promoteId:promoteId},
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
        $.ajax({
        	url:'<?php echo $this->createUrl('/mall/deleteCart',array('companyId'=>$this->companyId));?>',
        	data:{productId:productId,promoteId:promoteId},
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