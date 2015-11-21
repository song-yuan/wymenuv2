<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('Your Title Here');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/index.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/Adaptive.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<div class="nav-lf">
<ul id="nav">
  <?php foreach($categorys as $k=>$category):?>
  <li class="<?php if($k==0):?>current<?php endif;?>"><a href="#st<?php echo $category['lid'];?>"><?php echo $category['category_name'];?></a><b></b></li>
  <?php endforeach;?>
</ul>
</div>


<div id="container" class="container">

<?php foreach($models as $model):?>
  <div class="section" id="st<?php echo $model['lid'];?>">
    <div class="prt-title"><?php echo $model['category_name'];?></div>
    <?php foreach($model['product_list'] as $product):?>
  	<div class="prt-lt">
    	<div class="lt-lt"><img src="<?php echo $product['main_picture'];?>"></div>
        <div class="lt-ct">
        	<p><?php echo $product['product_name'];?></p>
            <p class="pr">¥<span class="price"><?php echo $product['price'];?></span></p>
        </div>
        <div class="lt-rt">
        	<input type="button" class="minus zero" value="-">
            <input type="text" class="result zero" product-id="<?php echo $product['lid'];?>" promote-id="-1" disabled value="0">
            <input type="button" class="add" value="+">
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
    	<p><a href="cart.html">选好了</a></p>
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
    $(".lt-rt").each(function(){ 
    s+=parseInt($(this).find('input[class*=result]').val())*parseFloat($(this).siblings().find('span[class*=price]').text()); 

    });

    <!--计算菜种-->
    var nIn = $("li.current a").attr("href");
    $(nIn+" input[type='text']").each(function() {
    	if($(this).val()!=0){
    		n++;
    	}
    });

    <!--计算总份数-->
    $("input[type='text']").each(function(){
    	v += parseInt($(this).val());
    });
    if(n>0){
    	$(".current b").html(n).show();		
    	}else{
    	$(".current b").hide();		
    		}	
    $(".share").html(v);
    $("#total").html(s.toFixed(2)); 
} 
setTotal(); 

}) 
</script> 