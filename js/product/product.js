function addToCart() {
	var reddot = document.querySelector('.aniele');
	reddot.style.visibility="visible";
	classie.add(reddot,'added');
	setTimeout(function(){
		reddot.style.visibility="hidden";
		classie.remove(reddot,'added');	
	}, 500); 

}
$(document).ready(function(){
    $('#forum_list').on('click','.addCart',function(){
    	var _this = $(this);
    	var isAddOrder = 1;
    	var productId = _this.attr('product-id');
    	var type = _this.attr('type');
    	var price = parseFloat(_this.attr('price'));
    	var total = 0;
    		total = parseFloat($('.total-price').html());
    	var nums = 0;
    		nums = parseInt($('.product-nums').html());
    	if(_this.hasClass('hasorder')){
    		isAddOrder = 0;
    	}
 		$.ajax({
 			url:'createCart',
 			data:{
 					isAddOrder:isAddOrder,
					productId:productId,
					type:type
				},
 			type:'POST',
 			success:function(msg){
 				if(parseInt(msg)){
 					addToCart();
					if(isAddOrder){
						_this.addClass('hasorder');
						total += price;
						total = total.toFixed(2);
						$('.total-price').html(total);
						$('.product-nums').html(nums+1);
					}else{
						_this.removeClass('hasorder');
						total -= price;
						total = total.toFixed(2);
						$('.total-price').html(total);
						$('.product-nums').html(nums-1);
					}
 				}
 			}
 		});
    });
    
    //全部分类
    $('.category-top').click(function(){
    	$('.promptumenu_window').slideToggle(function(){
    		if($(this).is(":hidden")){
    			$('#page_0').css('margin-top','35px');
    		}else{
    			$('#page_0').css('margin-top','185px');
    		}
    	});
    });
    //查看菜单
    $('.product-nums').click(function(){
    	$('.product-mask').toggle(function(){
    		$.ajax({
     			url:'getOrderListJson',
     			type:'POST',
     			dataType:'json',
     			success:function(msg){
     				if(msg){
     					var str = '';
     					for(var o in msg){
     							str +='<div class="product-catory">'+msg[o].category_name+'</div>'	;
         						for(var i in msg[o]){
         							if(!isNaN(i)){
         								str +='<div class="product-catory-product">'+msg[o][i].product_name+'</div>';
         							}
         						}
     					}
     					$('.info').html(str);
     				}
     			},
     		});
    	});
    });
        
    
    $('#forum_list').on('click','.product-pic',function(){
    	var lid = $(this).attr('lid');
    	$.ajax({
 			url:'getProductPicJson',
 			data:'id='+lid,
 			success:function(msg){
 				if(msg){
 					$('.large-pic').css('display','block');
 					$('.large-pic').html(msg);
 					$('#gallery').slick({
 						  dots: true,
 						  infinite: true,
 						  speed: 1000,
 						  slidesToShow: 1,
 				  		  slidesToScroll: 1,
 				  		  autoplay: true,
 						  arrows: false
 					});
 					$("#gallery").css({
 						position: "absolute",
 						left: ($('.large-pic').width() - $("#gallery").outerWidth())/2,
 						top: ($('.large-pic').height() - $("#gallery").outerHeight())/2
 					});	
 				}
 			},
 		});
    });
    
    $('.large-pic').click(function(){
    	$(this).html('');
    	$(this).css('display','none');
    });
 });