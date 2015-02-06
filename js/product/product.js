$(document).ready(function(){
    $('#forum_list').on('click','#addCart',function(){
    	var _this = $(this);
    	var isAddOrder = 1;
    	var productId = _this.attr('product-id');
    	var type = _this.attr('type');
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
 				if(msg==1){
					if(isAddOrder){
						_this.addClass('hasorder');
					}else{
						_this.removeClass('hasorder');
					}
 				}
 			}
 		});
    });
    var prevTop = 0,
    	currTop = 0;
	$(window).scroll(function() {
	    currTop = $(window).scrollTop();
	    if (currTop < prevTop) { //判断小于则为向上滚动
	    	$(".promptumenu_window").show();
	        $(".promptumenu_window").addClass('float');
	        $('#page_0').css('margin-top',200);
	    } else {
	        $(".promptumenu_window").hide();
	        $(".promptumenu_window").removeClass('float');
	        $('#page_0').css('margin-top',0);
	    }
	    //prevTop = currTop; //IE下有BUG，所以用以下方式
	    setTimeout(function(){prevTop = currTop},0);
	});
 });