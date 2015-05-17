function totalPrice(){
	var totalPrice = 0;
	$('.input-product').each(function(){
		var price = $(this).attr('price');
		var num = $(this).val();
		totalPrice += parseInt(price)*parseInt(num);
	});
	totalPrice = totalPrice.toFixed(2);
	$('.total-price').html(totalPrice);
}
function totalNum(){
	var totalNum = 0;
	$('.input-product').each(function(){
		var num = $(this).val();
		totalNum += parseInt(num);
	});
	$('.total-num').html(totalNum);
}
function addToCart() {
	var reddot = document.querySelector('.aniele');
        var shcart = document.querySelector('.shoppingCart');
	reddot.style.visibility="visible";
	classie.add(reddot,'added');
        classie.add(shcart,'rotate');
	setTimeout(function(){
		reddot.style.visibility="hidden";
		classie.remove(reddot,'added');
                classie.remove(shcart,'rotate');
	}, 800); 

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
    		nums = parseInt($('.total-num').html());
 		$.ajax({
 			url:'/wymenuv2/product/createCart',
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
						total += price;
						total = total.toFixed(2);
						$('.total-price').html(total);
						$('.total-num').html(nums+1);
					}
 				}else{
                                    alert("已经添加到购物车");
                                }
 			}
 		});
    });
   
   //help
   $('.padsetting').click(function(){
            $(".setting-pad-mask").toggle();});
   
   $('#content').click(function(){
            $(".setting-pad-mask").css('display','none');
            $('.product-pad-mask').css('display','none');
        });
   
    //查看菜单
    $('.top-right').click(function(){
    	$('.product-pad-mask').toggle(function(){
    	  if(!$('.product-pad-mask').is(':hidden')) {
    		$.ajax({
     			url:'/wymenuv2/product/getOrderListJson',
     			type:'POST',
     			dataType:'json',
     			success:function(msg){
     				if(msg){
     					var str = '';
     					for(var o in msg){
     							str +='<div class="product-catory">'+msg[o].category_name+'</div>'	;
         						for(var i in msg[o]){
         							if(!isNaN(i)){
         								if(parseInt(msg[o][i].category_id)){
         									str +='<div class="product-catory-product">'+msg[o][i].product_name+'<div class="product-catory-product-right"><span class="minus" >-</span><input class="set-num input-product" type="text" name="'+msg[o][i].product_id+'" value="'+msg[o][i].amount+'" price="'+msg[o][i].price+'" readonly="true"/><span class="plus">+</span></div></div>';
         								}else{
         									str +='<div class="product-catory-product">'+msg[o][i].product_name+'<div class="product-catory-product-right"><span class="minus" >-</span><input class="set-num input-product" type="text" name="'+msg[o][i].set_id+','+msg[o][i].product_id+'" value="'+msg[o][i].amount+'" price="'+msg[o][i].price+'" readonly="true"/><span class="plus">+</span></div></div>';
         								}
         							}
         						}
     					}
     					$('.info').html(str);
     				}
     			},
     		});
     	  }
    	});
    });
    $('.product-pad-mask').on('click','.minus',function(){
                //alert('-');
		var input = $(this).siblings('input');
		var num = input.val();
		if(num > 0){
			num = num - 1;
		}
		input.val(num);	
		totalPrice();
		totalNum();		
	});
    $('.product-pad-mask').on('click','.plus',function(){
                //alert('+');
		var input = $(this).siblings('input');
		var num = parseInt(input.val());
		num = num + 1;
		input.val(num);	
		totalPrice();	
		totalNum();
	});
    $('#pad-disbind-menu').on('click',function(){
            location.href='../../../../../padbind/login';	
	});
    $('#pad-app-exit').on('click',function(){
            if (typeof Androidwymenuprinter == "undefined") {
                alert("无法获取PAD设备信息，请在PAD中运行该程序！");
                return false;
            }
            //var statu = confirm("清除完缓存后，应用程序会自动退出，请重新打开！确定清除吗？");
            //if(statu){
                Androidwymenuprinter.appExitClear();
                alert("应用程序无法完整清楚所有缓存数据，请到“设置”->“应用程序”->“WebWyMenu”中手动清除！");
            //}
	});
    $('#forum_list').on('click','.view-product-pic',function(){
    	//var lid = $(this).attr('lid');
        var lid = $(this).attr('product-id');
        //alert(lid);
    	$.ajax({
 			url:'/wymenuv2/product/getProductPicJson',
 			data:'id='+lid,
 			success:function(msg){
 				if(msg!='nopic'){
                                    //alert(msg);
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
 				}else{
                                    alert('没有大图！');
                                }
 			},
 		});
    });
    
    $('.large-pic').click(function(){
    	$(this).html('');
    	$(this).css('display','none');
    });
    
    $('#updatePadOrder').click(function(){
        if (typeof Androidwymenuprinter == "undefined") {
            alert("无法获取PAD设备信息，请在PAD中运行该程序！");
            return false;
        }
        var padinfo=Androidwymenuprinter.getPadInfo();
        var pad_id=padinfo.substr(10,10);
    	$('#padOrderForm').ajaxSubmit(function(msg){
    		if(msg.status){
                    var company_id = msg.dpid;
                    $.get('/wymenuv2/product/printPadList/companyId/'+msg.dpid+'/orderId/'+msg.orderId+'/padId/'+pad_id,function(data){
                    if(data.status) {
                         if(Androidwymenuprinter.printJob(company_id,data.jobid))
                         {
                             alert("打印成功");
                         }
                         else
                         {
                             alert("PAD打印失败！，请确认打印机连接好后再试！");                                                                        
                         }                                                
                     } else {
                             alert(data.msg);
                     }
                    },'json');
                }else{
                    alert('下单失败,请重新下单!')
                }
    	});
    	return false;
    });
 });