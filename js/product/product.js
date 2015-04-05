$(document).ready(function(){
    $('#forum_list').on('click','#addCart',function(){
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
//    var prevTop = 0,
//    	currTop = 0;
//	$(window).scroll(function() {
//	    currTop = $(window).scrollTop();
//	    if (currTop < prevTop) { //判断小于则为向上滚动
//	    	if($(".promptume.nu_window").is(':hidden')){
//	    		$(".promptumenu_window").slideDown(800);
//		        $(".promptumenu_window").addClass('float');
//		        $('#page_0').css('margin-top',140);
//                       // $(".bottom").show(1500);
//	    	}
//	    } else {
//	    	if($(".promptumenu_window").is(':visible')){
//	    		$(".promptumenu_window").slideUp(800);
//	 	        $(".promptumenu_window").removeClass('float');
//	 	        $('#page_0').css('margin-top',0);
//                        //$(".bottom").hide(1500);
//	    	}
//	    }
//	    //prevTop = currTop; //IE下有BUG，所以用以下方式
//	    setTimeout(function(){prevTop = currTop},0);
//	});
    $('.category-top').click(function(){
    	$('.promptumenu_window').slideToggle(function(){
    		if($(this).is(":hidden")){
    			$('#page_0').css('margin-top','70px');
    		}else{
    			$('#page_0').css('margin-top','220px');
    		}
    	});
    });
 });