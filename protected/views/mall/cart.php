<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('确认订单');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/cart.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/Adaptive.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<?php foreach($models as $model):?>
<div class="section">
	<!--
    <div class="prt-cat">/div>
    -->
    <div class="prt">
        <div class="prt-lt"><?php echo $model['product_name']?></div>
        <div class="prt-mt">￥<span class="price"><?php echo $model['price']?></span></div>
        <div class="prt-rt">
            <div class="minus">-</div>
            <input type="text" class="result" product-id="<?php echo $model['product_id'];?>" promote-id="<?php echo $model['privation_promotion_id']?>" disabled value="<?php echo $model['num']?>">
            <div class="add">+</div>
        </div>
        <div class="clear"></div>
    </div>
</div>
<?php endforeach;?>

<footer>
    <div class="ft-llt">
        <p><a href="<?php echo $this->createUrl('/mall/index',array('companyId'=>$this->companyId,'type'=>$this->type));?>">返回</a></p>
    </div>
    <div class="ft-lmt">
        <p>￥<span id="total" class="total">0.00</span></span></p>
    </div>
    <div class="ft-rrt">
        <p><a href="<?php echo $this->createUrl('/mall/generalOrder',array('companyId'=>$this->companyId,'type'=>$this->type));?>">确认下单</a></p>
    </div>
    <div class="clear"></div>
</footer>


<script> 
$(function(){ 
    $('#nav li').click(function(){
        $('#nav').find('li').removeClass('current');
        $(this).addClass('current');
        var href = $(this).find('a').attr('href');
        $(href).scrollTop();
    });
    $('#container').scroll(function(){
        $('.section').each(function(){
            var height = $(this).height();
            var top = $(this).offset().top;
            if(top <= 0 && (top+height) > 0){
                var id = $(this).attr('id');
                $('a[href=#'+id+']').parents('ul').find('li').removeClass('current');
                $('a[href=#'+id+']').parent('li').addClass('current');
                return false;
            }
        });
       
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
        			alert(msg.msg);
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
			          t.siblings(".minus").addClass('zero');
			          t.addClass('zero');
			       }
			       t.val(parseInt(t.val())-1);
			       if(parseInt(t.val())<0){ 
			           t.val(0); 
			   	    } 
			    	setTotal(); 
        		}else{
        			alert(msg.msg);
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