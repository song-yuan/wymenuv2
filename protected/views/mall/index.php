<?php
	$baseUrl = Yii::app()->baseUrl;
	if($this->type==2){
		$this->setPageTitle('外卖点单');
	}elseif($this->type==6){
		$this->setPageTitle('店内点单');
	}else{
		$this->setPageTitle('自助点单');
	}
	$closeShop = false;
	if($this->company['is_rest'] < 3){
		$closeShop = true;
	}else{
		$currentTime = date('H:i:s');
		if($currentTime >= $this->company['closing_time'] || $currentTime <= $this->company['shop_time']){
			$closeShop = true;
		}
	}
?>
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css?_=12323">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/index.css?_=12323">
<style type="text/css">
.layui-layer-content img {
	width: 100%;
	height: 100%;
}

.boll {
	width: 15px;
	height: 15px;
	background-color: #FF5151;
	position: absolute;
	-moz-border-radius: 15px;
	-webkit-border-radius: 15px;
	border-radius: 15px;
	z-index: 5;
	display: none;
}

.none {
	display: none;
}
</style>
<script type="text/javascript"
	src="<?php echo $baseUrl;?>/js/mall/Adaptive.js"></script>
<script type="text/javascript"
	src="<?php echo $baseUrl;?>/js/mall/parabola.js"></script>
<div class="header"><marquee>欢迎光临本店:<?php echo $this->company['company_name'];?></marquee></div>
<div class="content">
	<div class="nav-lf">
		<ul id="nav">
	
		</ul>
	</div>
	
	<div id="container" class="container">
		<div id="product-top" class="container-top">
			<div></div>
		</div>
	
	</div>
</div>
<footer>
	<div class="cart-img"><div><img alt="" src="../img/mall/shopcart_white.png"></div></div>
	<div class="ft-lt">
		<p>￥<span id="total" class="total">0.00</span><span class="nm">(<label class="share"></label>份)</span></p>
	</div>
    <?php if($this->type==2):?>
	    <?php if($start&&$start['fee_price']):?>
		    <div class="ft-rt start" start-price="<?php echo $start['fee_price'];?>">
				<p>
					<a href="<?php echo $this->createUrl('/mall/checkOrder',array('companyId'=>$this->companyId,'type'=>$this->type));?>">选好了</a>
				</p>
			</div>
			<div class="ft-rt no-start" style="background: #6A706E" start-price="<?php echo $start['fee_price'];?>">
				<p><?php echo (int)$start['fee_price'];?>元起送</p>
			</div>
	    <?php else:?>
		    <div class="ft-rt" start-price="0">
				<p>
					<a href="<?php echo $this->createUrl('/mall/checkOrder',array('companyId'=>$this->companyId,'type'=>$this->type));?>">选好了</a>
				</p>
			</div>
	    <?php endif;?>
     <?php else:?>
     <div class="ft-rt">
		<p>
			<a href="<?php echo $this->createUrl('/mall/checkOrder',array('companyId'=>$this->companyId,'type'=>$this->type));?>">选好了</a>
		</p>
	</div>
    <?php endif;?>
    <div class="clear"></div>
</footer>

<div id="boll" class="boll"></div>


<div class="j-mask mask cart-mask" style="display:none;"></div>
<div id="cart-dtl" class="cart-dtl" style="display:none;">
	<div class="cart-dtl-head">
		<span class="j-cart-dusbin cart-dusbin"><i></i>清空购物车</span>
	</div>
	<div class="j-cart-dtl-list cart-dtl-list">
		<div class="j-cart-dtl-list-inner" style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
		</div>
	</div>
	
</div>
<script> 
var orderType = '<?php echo $this->type;?>';
var hasclose = false;
<?php if($closeShop):?>
hasclose = true;
var resMsg = '<?php echo $this->company['rest_message']?$this->company['rest_message']:"店铺休息中....";?>';
<?php endif;?>
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
    if(orderType==2){
    	var startPrice = $('.ft-rt').attr('start-price');
        var total = $("#total").html();
        if(parseFloat(startPrice) > parseFloat(total)){
        	$('.no-start').removeClass('none');
        	$('.start').addClass('none');
        }else{
        	$('.no-start').addClass('none');
        	$('.start').removeClass('none');
        }
    }
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
			var current = false; //当前默认分类
			var hideCategoryArr = new Array();
			var categorys = data.categorys;
			var promotions = data.promotions;
			var products = data.products;
			var productSets = data.productSets;
			var navLi = '';
			var promotionStr = '';
			var productStr = '';
			var productSetStr = '';
			var cartStr = '';
			var defaultImg = '../img/product_default.png';
			// 种类
			for(var k in categorys){
				var category = categorys[k];
				if(category.show_type=='2'&&orderType=='2'||category.show_type=='3'&&orderType=='6'||category.show_type=='4'){
					hideCategoryArr.push(category.lid);
					continue;
				}else{
					if(current){
						navLi += '<li class=""><a href="#st' + category.lid + '">' + category.category_name + '</a><b></b></li>';
					}else{
						current = true;
						$('#product-top').find('div').html(category.category_name);
						$('#product-top').show();
						navLi += '<li class="current"><a href="#st' + category.lid + '">' + category.category_name + '</a><b></b></li>';
					}
				}
			}
			
			for(var p in products){
				var product = products[p];
				if(hideCategoryArr.indexOf(product.lid) > -1){
					continue;
				}
				if(product.cate_type!='2'){
					productStr +='<div class="section" id="st'+ product.lid  +'"><div class="prt-title">' + product.category_name + '</div>';
					for(var pp in product.product_list){
						var pProduct = product.product_list[pp];
						if(pProduct.main_picture==''){
							pProduct.main_picture = defaultImg;
						}
						productStr +='<div class="prt-lt"><div class="lt-lt"><img src="'+pProduct.main_picture+'"></div>';
						productStr +='<div class="lt-ct"><p><span class="name">'+ pProduct.product_name +'</span>';
						if(pProduct.spicy==1){
							productStr +='<span><img src="<?php echo $baseUrl;?>/img/mall/index/spicy1.png" style="width:15px;height:20px;"/></span>';
						}else if(pProduct.spicy==2){
							productStr +='<span><img src="<?php echo $baseUrl;?>/img/mall/index/spicy2.png" style="width:15px;height:20px;"/></span>';
						}else if(pProduct.spicy==3){
							productStr +='<span><img src="<?php echo $baseUrl;?>/img/mall/index/spicy3.png" style="width:15px;height:20px;"/></span>';
						}else{
							productStr +='</p>';
						}
						productStr +='<p class="pr">¥<span class="price">'+pProduct.member_price+'</span>';
						if(pProduct.member_price!= pProduct.original_price){
							productStr +='<span class="oprice"><strike>¥'+pProduct.original_price+'</strike></span>';
						}
						productStr +='</p>';
						if(!hasclose){
		         			if(parseInt(pProduct.num)){
		         				productStr +='<div class="lt-rt"><div class="minus">-</div><input type="text" class="result" is-set="0" product-id="'+pProduct.lid+'" promote-id="-1" to-group="-1" store-number="'+pProduct.store_number+'" readonly="readonly" value="'+pProduct.num+'">';
		        				productStr +='<div class="add">+</div><div class="clear"></div></div><div class="clear"></div>';
		
		        				cartStr +='<div class="j-fooditem cart-dtl-item" data-orderid="0_'+pProduct.lid+'_-1_-1">';
		        				cartStr +='<div class="cart-dtl-item-inner">';
		        				cartStr +='<i class="cart-dtl-dot"></i>';
		        				cartStr +='<p class="cart-goods-name">'+ pProduct.product_name +'</p>';
		        				cartStr +='<div class="j-item-console cart-dtl-oprt">';
		        				cartStr +='<a class="j-add-item add-food" href="javascript:void(0);"><span class="icon i-add-food">+</span></a>'; 
		        				cartStr +='<span class="j-item-num foodop-num">'+pProduct.num+'</span> ';
		        				cartStr +='<a class="j-remove-item remove-food" href="javascript:void(0);"><span class="icon i-remove-food">-</span></a>';
		        				cartStr +='</div>';
		        				cartStr +='<span class="cart-dtl-price">¥'+pProduct.member_price+'</span>';
		        				cartStr +='</div>';
		        				cartStr +='</div>';
		         			}else{
		         				if(parseInt(pProduct.store_number) != 0){
			         				productStr +='<div class="lt-rt"><div class="minus zero">-</div><input type="text" class="result zero" is-set="0" product-id="'+pProduct.lid+'" promote-id="-1" to-group="-1" store-number="'+pProduct.store_number+'" readonly="readonly" value="0">';
			        				productStr +='<div class="add">+</div><div class="clear"></div><div class="sale-out zero"> 已售罄  </div></div><div class="clear"></div>';
		         				}else{
		         					productStr +='<div class="lt-rt"><div class="minus zero">-</div><input type="text" class="result zero" is-set="0" product-id="'+pProduct.lid+'" promote-id="-1" to-group="-1" store-number="'+pProduct.store_number+'" readonly="readonly" value="0">';
			        				productStr +='<div class="add zero">+</div><div class="clear"></div><div class="sale-out"> 已售罄  </div></div><div class="clear"></div>';
		         				}
		         			}
						}
	         			productStr +='</div></div>';
					}
					productStr +='</div>';
				}else{
					// 套餐
					productSetStr +='<div class="section" id="st'+ product.lid +'"><div class="prt-title">' + product.category_name + '</div>';
					for(var q in product.product_list){
						var pProductSet = product.product_list[q];
						var pDetail = pProductSet['detail'];
						if(pProductSet.main_picture==''){
							pProductSet.main_picture = defaultImg;
						}
						productSetStr +='<div class="prt-lt"><div class="lt-lt"><img src="'+pProductSet.main_picture+'"></div>';
						productSetStr +='<div class="lt-ct"><p><span class="name">'+ pProductSet.set_name +'</span>';
						if(pProductSet.spicy==1){
							productSetStr +='<span><img src="<?php echo $baseUrl;?>/img/mall/index/spicy1.png" style="width:15px;height:20px;"/></span>';
						}else if(pProductSet.spicy==2){
							productSetStr +='<span><img src="<?php echo $baseUrl;?>/img/mall/index/spicy2.png" style="width:15px;height:20px;"/></span>';
						}else if(pProductSet.spicy==3){
							productSetStr +='<span><img src="<?php echo $baseUrl;?>/img/mall/index/spicy3.png" style="width:15px;height:20px;"/></span>';
						}else{
							productSetStr +='</p>';
						}
						productSetStr +='<p class="pr">¥<span class="price">'+pProductSet.member_price+'</span>';
						if(pProductSet.member_price!= pProductSet.set_price){
							productSetStr +='<span class="oprice"><strike>¥'+pProductSet.set_price+'</strike></span>';
						}
						productSetStr +='</p>';
						if(!hasclose){
		         			if(parseInt(pProductSet.num)){
		         				productSetStr +='<div class="lt-rt"><div class="minus">-</div><input type="text" class="result" is-set="1" product-id="'+pProductSet.lid+'" promote-id="-1" to-group="-1" store-number="'+pProductSet.store_number+'" readonly="readonly" value="'+pProductSet.num+'">';
		         				productSetStr +='<div class="add">+</div><div class="clear"></div></div>';
		
		         				cartStr +='<div class="j-fooditem cart-dtl-item" data-orderid="1_'+pProductSet.lid+'_-1_-1">';
		        				cartStr +='<div class="cart-dtl-item-inner">';
		        				cartStr +='<i class="cart-dtl-dot"></i>';
		        				cartStr +='<p class="cart-goods-name">'+ pProductSet.set_name +'</p>';
		        				cartStr +='<div class="j-item-console cart-dtl-oprt">';
		        				cartStr +='<a class="j-add-item add-food" href="javascript:void(0);"><span class="icon i-add-food">+</span></a>'; 
		        				cartStr +='<span class="j-item-num foodop-num">'+pProductSet.num+'</span> ';
		        				cartStr +='<a class="j-remove-item remove-food" href="javascript:void(0);"><span class="icon i-remove-food">-</span></a>';
		        				cartStr +='</div>';
		        				cartStr +='<span class="cart-dtl-price">¥'+pProductSet.member_price+'</span>';
		        				cartStr +='</div>';
		        				cartStr +='</div>';
		         			}else{
		         				if(parseInt(pProductSet.store_number) != 0){
		         					productSetStr +='<div class="lt-rt"><div class="minus zero">-</div><input type="text" class="result zero" is-set="1" product-id="'+pProductSet.lid+'" promote-id="-1" to-group="-1" store-number="'+pProductSet.store_number+'" readonly="readonly" value="0">';
		         					productSetStr +='<div class="add">+</div><div class="clear"></div><div class="sale-out zero"> 已售罄  </div></div>';
		         				}else{
		         					productSetStr +='<div class="lt-rt"><div class="minus zero">-</div><input type="text" class="result zero" is-set="1" product-id="'+pProductSet.lid+'" promote-id="-1" to-group="-1" store-number="'+pProductSet.store_number+'" readonly="readonly" value="0">';
		         					productSetStr +='<div class="add zero">+</div><div class="clear"></div><div class="sale-out"> 已售罄  </div></div>';
		         				}
		         			}
						}
						productSetStr +='</div><div class="clear"></div>';
	         			// 套餐详情
	         			productSetStr +='<div class="tips">';
	         			for(var ps=0; ps<pDetail.length; ps++){
	             			var detail = pDetail[ps]
	             			for(var ps1=0;ps1<detail.length;ps1++){
								var detailItem = detail[ps1];
								if(detailItem['is_select']=='1'){
									productSetStr +=detailItem['product_name']+'x'+detailItem['number']+' ';
								}
	                 		}
	             		}
	         			productSetStr +='</div></div>';
					}
					productSetStr +='</div>';
				}
			}
			// 活动
			if(orderType!=2){
				for(var key in promotions){
					navLi += '<li class=""><a href="#st-promotion'+key+'">'+promotions[key][0].promotion_title+'</a><b></b></li>';
					promotionStr +='<div class="section" id="st-promotion'+key+'"><div class="prt-title">'+promotions[key][0].promotion_title+'</div>';
					for(var i=0; i<promotions[key].length; i++){
						var promotion = promotions[key][i];
						var promotionProduct = promotion['product'];
						if(promotionProduct.main_picture==''){
							promotionProduct.main_picture = defaultImg;
						}
						promotionStr +='<div class="prt-lt"><div class="lt-lt"><img src="'+promotionProduct.main_picture+'"></div>';
						promotionStr +='<div class="lt-ct"><p><span class="name">'+ promotionProduct.product_name +'</span>';
						if(promotionProduct.spicy==1){
							promotionStr +='<span><img src="<?php echo $baseUrl;?>/img/mall/index/spicy1.png" style="width:15px;height:20px;"/></span>';
						}else if(promotionProduct.spicy==2){
							promotionStr +='<span><img src="<?php echo $baseUrl;?>/img/mall/index/spicy2.png" style="width:15px;height:20px;"/></span>';
						}else if(promotionProduct.spicy==3){
							promotionStr +='<span><img src="<?php echo $baseUrl;?>/img/mall/index/spicy3.png" style="width:15px;height:20px;"/></span>';
						}else{
							promotionStr +='</p>';
						}
						promotionStr +='<p class="pr">¥<span class="price">'+promotionProduct.price+'</span>';
						if(promotionProduct.price != promotionProduct.original_price){
							promotionStr +='<span class="oprice"><strike>¥'+promotionProduct.original_price+'</strike></span>';
						}
	             		promotionStr +='</p>';
	             		if(!hasclose){
		             		if(parseInt(promotionProduct.num)){
		             				promotionStr +='<div class="lt-rt"><div class="minus">-</div><input type="text" class="result" is-set="'+promotion.is_set+'" product-id="'+promotionProduct.lid+'" promote-id="'+promotion.normal_promotion_id+'" to-group="'+promotion.to_group+'" store-number="'+promotionProduct.store_number+'" readonly="readonly" value="'+promotionProduct.num+'">';
		            				promotionStr +='<div class="add">+</div><div class="clear"></div></div>';
		            				cartStr +='<div class="j-fooditem cart-dtl-item" data-orderid="'+promotion.is_set+'_'+promotionProduct.lid+'_'+promotion.normal_promotion_id+'_'+promotion.to_group+'">';
		            				cartStr +='<div class="cart-dtl-item-inner">';
		            				cartStr +='<i class="cart-dtl-dot"></i>';
		            				cartStr +='<p class="cart-goods-name">'+promotionProduct.product_name+'</p>';
		            				cartStr +='<div class="j-item-console cart-dtl-oprt">';
		            				cartStr +='<a class="j-add-item add-food" href="javascript:void(0);"><span class="icon i-add-food">+</span></a>'; 
		            				cartStr +='<span class="j-item-num foodop-num">'+promotionProduct.num+'</span> ';
		            				cartStr +='<a class="j-remove-item remove-food" href="javascript:void(0);"><span class="icon i-remove-food">-</span></a>';
		            				cartStr +='</div>';
		            				cartStr +='<span class="cart-dtl-price">¥'+promotionProduct.price+'</span>';
		            				cartStr +='</div>';
		            				cartStr +='</div>';
		             		}else{
		             			if(parseInt(promotionProduct.store_number) != 0){
		             				promotionStr +='<div class="lt-rt"><div class="minus zero">-</div><input type="text" class="result zero" is-set="'+promotion.is_set+'" product-id="'+promotionProduct.lid+'" promote-id="'+promotion.normal_promotion_id+'" to-group="'+promotion.to_group+'" store-number="'+promotionProduct.store_number+'" readonly="readonly" value="0">';
		            				promotionStr +='<div class="add">+</div><div class="clear"></div><div class="sale-out zero"> 已售罄  </div></div>';
		             			}else{
		             				promotionStr +='<div class="lt-rt"><div class="minus zero">-</div><input type="text" class="result zero" is-set="'+promotion.is_set+'" product-id="'+promotionProduct.lid+'" promote-id="'+promotion.normal_promotion_id+'" to-group="'+promotion.to_group+'" store-number="'+promotionProduct.store_number+'" readonly="readonly" value="0">';
		            				promotionStr +='<div class="add zero">+</div><div class="clear"></div><div class="sale-out"> 已售罄  </div></div>';
		             			}
		             		}
	             		}
	             		promotionStr +='</div></div>';
					}
					promotionStr +='</div>';
				}
			}
			$('#nav').append(navLi);
			$('#container').append(productStr + productSetStr + promotionStr);
			$('.j-cart-dtl-list-inner').html(cartStr);
			setTotal();
			layer.closeAll('loading');
		},
	});
}
$(document).ready(function(){ 
	var i = 0;
	var j = 0;
	var cHeight = $('body').height()-50-40;
	$(".content").height(cHeight+'px');
	window.load = getProduct(); 
	if(hasclose){
		$('footer').html('<p class="sh-close">'+resMsg+'</p>');
	}
    $('#nav').on('touchstart','li',function(){
    	var _this = $(this);
    	var href = _this.find('a').attr('href');
        $('#nav').find('li').removeClass('current');
        $(href).scrollTop();
        _this.addClass('current');
    });
    $('#container').scroll(function(){
        var ptHeight = $('.prt-title').outerHeight();
        $('.section').each(function(){
        	var id = $(this).attr('id');
            var top = $(this).offset().top;
            var height = $(this).outerHeight();
            if(top < ptHeight && (parseInt(top) + parseInt(height) - parseInt(ptHeight)) > 0){
                var pName = $(this).find('.prt-title').html();
                $('#product-top').find('div').html(pName);
	    		$('a[href=#'+id+']').parents('ul').find('li').removeClass('current');
	        	$('a[href=#'+id+']').parent('li').addClass('current');
	        	return false;
            }
        });
       
    });

    $('body').on('touchstart','.add',function(){
    	var height = $('body').height();
    	var top = $(this).offset().top;
    	var left = $(this).offset().left;

    	var parObj = $(this).parents('.prt-lt');
        var t = parObj.find('input[class*=result]');
        var productId = t.attr('product-id');
        var promoteId = t.attr('promote-id');
        var toGroup = t.attr('to-group');
        var isSet = t.attr('is-set');
        
        var timestamp=new Date().getTime()
        var random = ''+timestamp + parseInt(Math.random()*899+100)+'';
        $.ajax({
        	url:'<?php echo $this->createUrl('/mall/addCart',array('companyId'=>$this->companyId));?>',
        	data:{productId:productId,promoteId:promoteId,isSet:isSet,toGroup:toGroup,random:random},
        	success:function(msg){
        		if(msg.status){
        			 t.val(parseInt(t.val())+1);
			        if(parseInt(t.val()) > 0){
			            t.siblings(".minus").removeClass('zero');
			            t.removeClass('zero');
			        }
			        var cartObj = $('.cart-dtl-item[data-orderid="'+isSet+'_'+productId+'_'+promoteId+'_'+toGroup+'"]');
			        if(cartObj.length > 0){
			        	cartObj.find('.foodop-num').html(t.val());
			        }else{
				        var pName = parObj.find('.name').html();
				        var pPrice = parObj.find('.price').html();
				        var cartStr = '';
					    cartStr +='<div class="j-fooditem cart-dtl-item" data-orderid="'+isSet+'_'+productId+'_'+promoteId+'_'+toGroup+'">';
        				cartStr +='<div class="cart-dtl-item-inner">';
        				cartStr +='<i class="cart-dtl-dot"></i>';
        				cartStr +='<p class="cart-goods-name">'+ pName +'</p>';
        				cartStr +='<div class="j-item-console cart-dtl-oprt">';
        				cartStr +='<a class="j-add-item add-food" href="javascript:void(0);"><span class="icon i-add-food">+</span></a>'; 
        				cartStr +='<span class="j-item-num foodop-num">1</span> ';
        				cartStr +='<a class="j-remove-item remove-food" href="javascript:void(0);"><span class="icon i-remove-food">-</span></a>';
        				cartStr +='</div>';
        				cartStr +='<span class="cart-dtl-price">¥'+pPrice+'</span>';
        				cartStr +='</div>';
        				cartStr +='</div>';
        				$('.j-cart-dtl-list-inner').append(cartStr);
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
        	error:function(){
        		layer.msg('添加失败,请检查网络');
            },
        	dataType:'json'
        });
    });
     
    $('body').on('touchstart','.minus',function(){ 
    	var parObj = $(this).parents('.prt-lt');
        var t = parObj.find('input[class*=result]');
        var productId = t.attr('product-id');
        var promoteId = t.attr('promote-id');
        var toGroup = t.attr('to-group');
        var isSet = t.attr('is-set');
        var storeNum = t.attr('store-number');
        
        var timestamp=new Date().getTime()
        var random = ''+timestamp + parseInt(Math.random()*899+100)+'';
        $.ajax({
        	url:'<?php echo $this->createUrl('/mall/deleteCart',array('companyId'=>$this->companyId));?>',
        	data:{productId:productId,promoteId:promoteId,isSet:isSet,toGroup:toGroup,random:random},
        	success:function(msg){
        		if(msg.status){
    			  if(parseInt(t.val())==1){
			          t.siblings(".minus").addClass('zero');
			          t.addClass('zero');
			          if(parseInt(storeNum)==0){
			          	t.siblings(".add").addClass('zero');
			          	t.siblings(".sale-out").removeClass('zero');
			          }
			       }
			       t.val(parseInt(t.val())-1);
			       if(parseInt(t.val()) < 0){ 
			           t.val(0); 
			   	    }
			       	var cartObj = $('.cart-dtl-item[data-orderid="'+isSet+'_'+productId+'_'+promoteId+'_'+toGroup+'"]');
			        if(cartObj.length > 0){
				        if(parseInt(t.val()) == 0){
				        	cartObj.remove();
					    }else{
					    	cartObj.find('.foodop-num').html(t.val());
						}
			        }
			    	setTotal(); 
        		}else{
        			layer.msg(msg.msg);
        		}
        	},
        	error:function(){
        		layer.msg('移除失败,请检查网络');
            },
        	dataType:'json'
        });
   });
   $('.j-cart-dtl-list-inner').on('click','.add-food',function(){
        var parentObj = $(this).parents('.cart-dtl-item');
        var dataId = parentObj.attr('data-orderid');
        var dataArr = dataId.split('_');
        
        var isSet = dataArr[0];
        var productId = dataArr[1];
        var promoteId = dataArr[2];
        var toGroup = dataArr[3];
        
        var t = $('input[class*=result][is-set="'+isSet+'"][product-id="'+productId+'"][promote-id="'+promoteId+'"][to-group="'+toGroup+'"]');
        var timestamp=new Date().getTime()
        var random = ''+timestamp + parseInt(Math.random()*899+100)+'';
        $.ajax({
        	url:'<?php echo $this->createUrl('/mall/addCart',array('companyId'=>$this->companyId));?>',
        	data:{productId:productId,promoteId:promoteId,isSet:isSet,toGroup:toGroup,random:random},
        	success:function(msg){
        		if(msg.status){
        			 t.val(parseInt(t.val())+1);
			        if(parseInt(t.val()) > 0){
			            t.siblings(".minus").removeClass('zero');
			            t.removeClass('zero');
			        }
			        var cartObj = $('.cart-dtl-item[data-orderid="'+isSet+'_'+productId+'_'+promoteId+'_'+toGroup+'"]');
			        if(cartObj.length > 0){
			        	cartObj.find('.foodop-num').html(t.val());
			        }
			        setTotal();
        		}
        	},
        	error:function(){
        		layer.msg('添加失败,请检查网络');
            },
        	dataType:'json'
        });
    });
    $('.j-cart-dtl-list-inner').on('click','.remove-food',function(){
       var parentObj = $(this).parents('.cart-dtl-item');
       var dataId = parentObj.attr('data-orderid');
       var dataArr = dataId.split('_');
       
       var isSet = dataArr[0];
       var productId = dataArr[1];
       var promoteId = dataArr[2];
       var toGroup = dataArr[3];
       
       var t = $('input[class*=result][is-set="'+isSet+'"][product-id="'+productId+'"][promote-id="'+promoteId+'"][to-group="'+toGroup+'"]');
       var storeNum = t.attr('store-number');
       
       var timestamp=new Date().getTime()
       var random = ''+timestamp + parseInt(Math.random()*899+100)+'';
       $.ajax({
	       	url:'<?php echo $this->createUrl('/mall/deleteCart',array('companyId'=>$this->companyId));?>',
	       	data:{productId:productId,promoteId:promoteId,isSet:isSet,toGroup:toGroup,random:random},
	       	success:function(msg){
	       		if(msg.status){
	   			  if(parseInt(t.val())==1){
			          t.siblings(".minus").addClass('zero');
			          t.addClass('zero');
			          if(parseInt(storeNum)==0){
			          	t.siblings(".add").addClass('zero');
			          	t.siblings(".sale-out").removeClass('zero');
			          }
			       }
			       t.val(parseInt(t.val())-1);
			       if(parseInt(t.val()) < 0){ 
			           t.val(0); 
			   	    }
			       	var cartObj = $('.cart-dtl-item[data-orderid="'+isSet+'_'+productId+'_'+promoteId+'_'+toGroup+'"]');
			        if(cartObj.length > 0){
				        if(parseInt(t.val()) == 0){
				        	if($('.cart-dtl-item').length == 1){
				        		$('.ft-lt').trigger('click');
				        	}
				        	cartObj.remove();
					    }else{
					    	cartObj.find('.foodop-num').html(t.val());
						}
			        }
			    	setTotal(); 
	       		}else{
	       			layer.msg(msg.msg);
	       		}
	       	},
	       	error:function(){
	       		layer.msg('移除失败,请检查网络');
	        },
	       	dataType:'json'
       });
    });
    $('.j-cart-dusbin').on('click',function(){
    	var timestamp=new Date().getTime()
        var random = ''+timestamp + parseInt(Math.random()*899+100)+'';
        $.ajax({
        	url:'<?php echo $this->createUrl('/mall/deleteCart',array('companyId'=>$this->companyId,'all'=>1));?>',
        	success:function(msg){
        		if(msg){
            		$('input[class="result"]').each(function(){
                		$(this).addClass('zero');
                		$(this).parent().find('.minus').addClass('zero');
                		$(this).val(0);
                	});
            		$('.ft-lt').trigger('click');
        			$('.j-cart-dtl-list-inner').html('');
			        setTotal();
        		}else{
        			layer.msg('清空购物车失败,请重试');
        		}
        	},
        	error:function(){
        		layer.msg('清空购物车失败,请检查网络');
            }
        });
    });
    $('.j-mask').on('click',function(){
        $('.ft-lt').trigger('click');
    });
    $('footer').on('click','.ft-lt,.cart-img',function(){
        if($('.cart-dtl-item').length == 0){
            return;
        }
        if($('.j-mask').is(':visible')){
             var hight = $('#cart-dtl').outerHeight();
             $('#cart-dtl').animate({bottom:-hight},function(){
            	 $('.j-mask').hide();
             });
        }else{
             $('#cart-dtl').show();
             $('#cart-dtl').animate({bottom:50},function(){
            	 $('.j-mask').show();
             });
        }
    });
    $("body").on('click','.lt-lt',function(){
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
