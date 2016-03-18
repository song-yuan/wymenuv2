<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('点单');
?>
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/index.css">
<style type="text/css">
.layui-layer-content img{
	width:100%;
	height:100%;
}
.boll {
	width: 15px;
	height: 15px;
	background-color: #FF5151;
	position: absolute;
	-moz-border-radius: 15px;
	-webkit-border-radius: 15px;
	border-radius: 15px;
	display:none;
}

</style>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/Adaptive.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/parabola.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl.'/js/layer/layer.js';?>"></script>
<div class="nav-lf">
<ul id="nav">
  
</ul>
</div>


<div id="container" class="container">
<!-- 特价优惠  -->

<!-- end特价优惠  -->


   
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

<div id="boll" class="boll"></div>

<script> 
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
	    $(nIn).find("input[type='text']").each(function() {
	    	if(parseInt($(this).val()) > 0){
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
function getProduct(){
	layer.load(2);
	var timestamp=new Date().getTime()
    var random = ''+timestamp + parseInt(Math.random()*899+100)+'';
	$.ajax({
		url:'<?php echo $this->createUrl('/mall/getProduct',array('companyId'=>$this->companyId,'userId'=>$userId));?>',
		data:{random:random},
		dataType:'json',
		timeout:'30000',
		success:function(data){
			var categorys = data.categorys;
			var promotions = data.promotions;
			var products = data.products;
			var navLi = '';
			var promotionStr = '';
			var productStr = '';
			
			if(promotions.length > 0){
				navLi += '<li class="current"><a href="#st-1">特价菜</a><b></b></li>';
				
				for(var i in promotions){
					var promotion = promotions[i];
					promotionStr +='<div class="section" id="st-1"><div class="prt-title">' + promotion.promotion_title + '</div>';
					for(var ip in promotion.productList){
						var promotionProduct = promotion.productList[ip];
						promotionStr +='<div class="prt-lt"><div class="lt-lt"><img src="'+promotionProduct.main_picture+'"></div>';
						promotionStr +='<div class="lt-ct"><p><span>'+ promotionProduct.product_name +'</span> <span>';
						if(promotionProduct.spicy==1){
							promotionStr +='<img src="<?php echo $baseUrl;?>/img/mall/index/spicy1.png" style="width:15px;height:20px;"/>';
						}else if(promotionProduct.spicy==2){
							promotionStr +='<img src="<?php echo $baseUrl;?>/img/mall/index/spicy2.png" style="width:15px;height:20px;"/>';
						}else if(promotionProduct.spicy==3){
							promotionStr +='<img src="<?php echo $baseUrl;?>/img/mall/index/spicy3.png" style="width:15px;height:20px;"/></span></p>';
						}
						promotionStr +='<p class="pr">¥<span class="price">'+promotionProduct.price+'</span>';
						if(promotionProduct.price != promotionProduct.original_price){
							promotionStr +='<span class="oprice"><strike>¥'+promotionProduct.original_price+'</strike></span>';
						}
             			promotionStr +='</p></div>';
             			if(parseInt(promotionProduct.num)){
             				promotionStr +='<div class="lt-rt"><div class="minus">-</div><input type="text" class="result" product-id="'+promotionProduct.product_id+'" promote-id="'+promotion.private_promotion_id+'" to-group="'+promotion.to_group+'" readonly value="'+promotionProduct.num+'">';
            				promotionStr +='<div class="add">+</div><div class="clear"></div></div></div>';
             			}else{
             				promotionStr +='<div class="lt-rt"><div class="minus zero">-</div><input type="text" class="result zero" product-id="'+promotionProduct.product_id+'" promote-id="'+promotion.private_promotion_id+'" to-group="'+promotion.to_group+'" readonly value="0">';
            				promotionStr +='<div class="add">+</div><div class="clear"></div></div></div>';
             			}
             		
					}
					promotionStr +='</div>';
				}
			}
			
			for(var k in categorys){
				var category = categorys[k];
				if((k==0) && (promotions.length==0)){
					navLi += '<li class="current"><a href="#st' + category.lid + '">' + category.category_name + '</a><b></b></li>';
				}else{
					navLi += '<li class=""><a href="#st' + category.lid + '">' + category.category_name + '</a><b></b></li>';
				}
			}
			
			for(var p in products){
				var product = products[p];
				productStr +='<div class="section" id="st'+ product.lid  +'"><div class="prt-title">' + product.category_name + '</div>';
				for(var pp in product.product_list){
					var pProduct = product.product_list[pp];
					productStr +='<div class="prt-lt"><div class="lt-lt"><img src="'+pProduct.main_picture+'"></div>';
					productStr +='<div class="lt-ct"><p><span>'+ pProduct.product_name +'</span> <span>';
					if(pProduct.spicy==1){
						productStr +='<img src="<?php echo $baseUrl;?>/img/mall/index/spicy1.png" style="width:15px;height:20px;"/>';
					}else if(pProduct.spicy==2){
						productStr +='<img src="<?php echo $baseUrl;?>/img/mall/index/spicy2.png" style="width:15px;height:20px;"/>';
					}else if(pProduct.spicy==3){
						productStr +='<img src="<?php echo $baseUrl;?>/img/mall/index/spicy3.png" style="width:15px;height:20px;"/></span></p>';
					}
					productStr +='<p class="pr">¥<span class="price">'+pProduct.price+'</span>';
					if(pProduct.price != pProduct.original_price){
						productStr +='<span class="oprice"><strike>¥'+pProduct.original_price+'</strike></span>';
					}
         			productStr +='</p></div>';
         			if(pProduct.num < 1){
         				productStr +='<div class="lt-rt"><div class="minus zero">-</div><input type="text" class="result zero" product-id="'+pProduct.lid+'" promote-id="-1" to-group="-1" readonly value="0">';
        				productStr +='<div class="add">+</div><div class="clear"></div></div></div>';
         			}else{
         				productStr +='<div class="lt-rt"><div class="minus">-</div><input type="text" class="result" product-id="'+pProduct.lid+'" promote-id="-1" to-group="-1" readonly value="'+pProduct.num+'">';
        				productStr +='<div class="add">+</div><div class="clear"></div></div></div>';
         			}
         		
				}
				productStr +='</div>';
			}
			$('#nav').append(navLi);
			$('#container').append(promotionStr + productStr);
			setTotal();
			layer.closeAll('loading');
		},
	});
}
$(document).ready(function(){ 
	var i = 0;
	var j = 0;
	window.load = getProduct(); 
	
    $('#nav').on('click','li',function(){
    	var _this = $(this);
    	var href = _this.find('a').attr('href');
        $('#nav').find('li').removeClass('current');
        $(href).scrollTop();
        _this.addClass('current');
    });
    $('#container').scroll(function(){
    	$('.prt-title').removeClass('top');
        $('.section').each(function(){
        	var id = $(this).attr('id');
            var top = $(this).offset().top;
            var height = $(this).outerHeight();
            if(top < 0 && (parseInt(top) + parseInt(height)) > 5){
            	$(this).find('.prt-title').addClass('top');
	    		$('a[href=#'+id+']').parents('ul').find('li').removeClass('current');
	        	$('a[href=#'+id+']').parent('li').addClass('current');
	        	return false;
            }
        });
       
    });

    $("#container").on('touchstart','.add',function(){
    	var height = $('body').height();
    	var top = $(this).offset().top;
    	var left = $(this).offset().left;
    	
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
			        //动画
			        var str = '<div id="boll'+i+'" class="boll"></div>';
			    	$('body').append(str);
			    	$('#boll'+i).css({top:top,left:left,display:"block"});
			    	var bool = new Parabola({
						el: "#boll"+i,
						offset: [-left+10, height-top-25],
						curvature: 0.005,
						duration: 1000,
						callback:function(){
							$('#boll'+j).css('display','none');
							j++;
						},
						stepCallback:function(x,y){
						}
					});
					bool.start();
					i++;
        		}else{
        			$('#boll'+(i-1)).css('display','none');
        			layer.msg(msg.msg);
        		}
        	},
        	dataType:'json'
        });
    });
     
    $("#container").on('touchstart','.minus',function(){ 
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
    $("#container").on('click','.lt-lt',function(){
    	var str = $(this).html();
    	layer.open({
		    type: 1,
		    title: false,
		    closeBtn: 0,
		    area: ['100%', 'auto'],
		    skin: 'layui-layer-nobg', //没有背景色
		    shadeClose: true,
		    content: str
		});
		$('.layui-layer-content').css('overflow','hidden');
    });
});
</script> 