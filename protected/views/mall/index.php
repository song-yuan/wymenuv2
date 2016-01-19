<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('点单');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/index.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/Adaptive.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl.'/js/layer/layer.js';?>"></script>
<div class="nav-lf">
<ul id="nav">
  <?php if(!empty($promotions)):?>
  <li class="current"><a href="#st-1">特价菜</a><b></b></li>
  <?php endif;?>
  <?php foreach($categorys as $k=>$category):?>
  <li class="<?php if($k==0&&empty($promotions)):?>current<?php endif;?>"><a href="#st<?php echo $category['lid'];?>"><?php echo $category['category_name'];?></a><b></b></li>
  <?php endforeach;?>
</ul>
</div>


<div id="container" class="container">
<!-- 特价优惠  -->
<?php if(!empty($promotions)):?>
<?php foreach($promotions as $promotion):?>
<div class="section" id="st-1">
    <div class="prt-title"><?php echo $promotion['promotion_title'];?></div>
    <?php foreach($promotion['productList'] as $promotionProduct):?>
  	<div class="prt-lt">
    	<div class="lt-lt"><img src="<?php echo $promotionProduct['main_picture'];?>"></div>
        <div class="lt-ct">
        	<p><?php echo $promotionProduct['product_name'];?></p>
            <p class="pr">¥<span class="price"><?php echo $promotionProduct['price'];?></span> <?php if($promotionProduct['price']!=$promotionProduct['original_price']):?><span class="oprice"><strike>¥<?php echo $promotionProduct['original_price'];?></strike></span><?php endif;?></p>
        </div>
        <div class="lt-rt">
        	<div class="minus <?php if(!$promotionProduct['num']) echo 'zero';?>">-</div>
            <input type="text" class="result <?php if(!$promotionProduct['num']) echo 'zero';?>" product-id="<?php echo $promotionProduct['product_id'];?>" promote-id="<?php echo $promotion['private_promotion_id'];?>" to-group="<?php echo $promotion['to_group'];?>" disabled value="<?php echo $promotionProduct['num']?$promotionProduct['num']:0;?>">
            <div class="add">+</div>
            <div class="clear"></div>
        </div>
    </div>
    <?php endforeach;?>
  </div>
<?php endforeach;?>
<?php endif;?>
<!-- end特价优惠  -->

<?php foreach($models as $model):?>
  <div class="section" id="st<?php echo $model['lid'];?>">
    <div class="prt-title"><?php echo $model['category_name'];?></div>
    <?php foreach($model['product_list'] as $product):?>
  	<div class="prt-lt">
    	<div class="lt-lt"><img src="<?php echo $product['main_picture'];?>"></div>
        <div class="lt-ct">
        	<p><?php echo $product['product_name'];?></p>
            <p class="pr">¥<span class="price"><?php echo $product['price'];?></span><?php if($product['price']!=$product['original_price']):?><span class="oprice"><strike>¥<?php echo $product['original_price'];?></strike></span><?php endif;?></p>
        </div>
        <div class="lt-rt">
        	<div class="minus <?php if(!$product['num']) echo 'zero';?>">-</div>
            <input type="text" class="result <?php if(!$product['num']) echo 'zero';?>" product-id="<?php echo $product['lid'];?>" promote-id="-1" to-group="-1" disabled value="<?php echo $product['num']?$product['num']:0;?>">
            <div class="add">+</div>
            <div class="clear"></div>
        </div>
    </div>
    <?php endforeach;?>
  </div>
<?php endforeach;?> 
   
</div>

<footer>
	<div class="ft-lt">
        <p>合计:<span id="total" class="total">0.00元</span><span class="nm">(<label class="share"></label>份)</span></p>
    </div>
    <div class="ft-rt">
    	<p><a href="<?php echo $this->createUrl('/mall/checkOrder',array('companyId'=>$this->companyId,'type'=>$this->type));?>">选好了</a></p>
    </div>
    <div class="clear"></div>
</footer>


<script> 
$(document).ready(function(){ 
    $('#nav li').click(function(){
    	var _this = $(this);
        $('#nav').find('li').removeClass('current');
        _this.addClass('current');
        var href = _this.find('a').attr('href');
        $(href).scrollTop();
    });
    $('#container').scroll(function(){
        $('.section').each(function(){
        	var id = $(this).attr('id');
            var top = $(this).offset().top;
            var height = $(this).outerHeight();
            if(top < 0 && (parseInt(top) + parseInt(height)) > 5){
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
        var toGroup = t.attr('to-group');
        
        var timestamp=new Date().getTime()
        var random = ''+timestamp + parseInt(Math.random()*899+100)+'';
        $.ajax({
        	url:'<?php echo $this->createUrl('/mall/addCart',array('companyId'=>$this->companyId));?>',
        	data:{productId:productId,promoteId:promoteId,toGroup:toGroup,random:random},
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
        var toGroup = t.attr('to-group');
        
        var timestamp=new Date().getTime()
        var random = ''+timestamp + parseInt(Math.random()*899+100)+'';
        $.ajax({
        	url:'<?php echo $this->createUrl('/mall/deleteCart',array('companyId'=>$this->companyId));?>',
        	data:{productId:productId,promoteId:promoteId,toGroup:toGroup,random:random},
        	success:function(msg){
        		if(msg.status){
    			  if(parseInt(t.val())==1){
			          t.siblings(".minus").addClass('zero');
			          t.addClass('zero');
			       }
			       t.val(parseInt(t.val())-1);
			       if(parseInt(t.val()) < 0){ 
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
 
function setTotal(){ 
    var s=0;
    var v=0;
    var n=0;
    <!--计算总额--> 
    $(".lt-rt").each(function(){ 
    s+=parseInt($(this).find('input[class*=result]').val())*parseFloat($(this).siblings().find('span[class*=price]').text()); 

    });

    <!--计算菜种-->
    $('li').each(function(){
    	var nIn = $(this).find("a").attr("href");
	    $(nIn+" input[type='text']").each(function() {
	    	if($(this).val()!=0){
	    		n++;
	    	}
	    });
	    if(n>0){
    		$(this).find("b").html(n).show();		
	    }else{
	    	$(this).find("b").hide();		
	    }
	    n = 0;	
    });

    <!--计算总份数-->
    $("input[type='text']").each(function(){
    	v += parseInt($(this).val());
    });
    
    $(".share").html(v);
    $("#total").html(s.toFixed(2)); 
} 
setTotal(); 

}) 
</script> 