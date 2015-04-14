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
 		addToCart();
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
 			dataType:'json',
 			success:function(msg){
 				if(msg!=''){
 					$('.large-pic').css('display','block');
 					var dotstr = '';
 					var str = '';
 	 				for(var o in msg){
 	 					dotstr += '<a href="javascript:;">'+(o+1)+'</a>';
 	 					str += '<li><img src="'+msg[o].pic_path+'" /></li>'
 	 				}
 	 				$('.flicking_con .flicking_inner').html(dotstr);
 	 				$('.main_image ul').html(str);
 	 				$(".main_image").touchSlider({
 	 					flexible : true,
 	 					speed : 200,
 	 					btn_prev : $("#btn_prev"),
 	 					btn_next : $("#btn_next"),
 	 					paging : $(".flicking_con a"),
 	 					counter : function (e) {
 	 						$(".flicking_con a").removeClass("on").eq(e.current-1).addClass("on");
 	 					}
 	 				});
 				}
 			},
 		});
    });
    
    $('.large-pic').click(function(){
    	$(this).css('display','none');
    });
    $(".main_visual").hover(function(){
		$("#btn_prev,#btn_next").fadeIn()
		},function(){
		$("#btn_prev,#btn_next").fadeOut()
		})
	$dragBln = false;
	$(".main_image").bind("mousedown", function() {
		$dragBln = false;
	})
	$(".main_image").bind("dragstart", function() {
		$dragBln = true;
	})
	$(".main_image a").click(function() {
		if($dragBln) {
			return false;
		}
	})
	timer = setInterval(function() { $("#btn_next").click();}, 5000);
	$(".main_visual").hover(function() {
		clearInterval(timer);
	}, function() {
		timer = setInterval(function() { $("#btn_next").click();}, 5000);
	});
	$(".main_image").bind("touchstart", function() {
		clearInterval(timer);
	}).bind("touchend", function() {
		timer = setInterval(function() { $("#btn_next").click();}, 5000);
	});
	
 });