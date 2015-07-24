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
	//var reddot = document.querySelector('.aniele');
        var shcart = document.querySelector('.shoppingCart');
	//reddot.style.visibility="visible";
	//classie.add(reddot,'added');
        classie.add(shcart,'rotate');
	setTimeout(function(){
		//reddot.style.visibility="hidden";
		//classie.remove(reddot,'added');
                classie.remove(shcart,'rotate');
	}, 800); 
}
$(document).ready(function(){
	var language = $('input[name="language"]').val();
    $('#forum_list').on(event_clicktouchstart,'.addCart',function(){
    	var _this = $(this);
    	var store = _this.parents('.blockCategory').attr('store');
    	if(parseInt(store)==0){
    		layer.msg('库存不足');
    		return;
    	}else if(parseInt(store) > 0){
    		store -= 1;
    		_this.parents('.blockCategory').attr('store',store);
    	}
    	var type = _this.attr('type');
    	var parentsBlockCategory = _this.parents('.blockCategory');
    	var category = parentsBlockCategory.attr('category');//分类id
    	var categoryName = parentsBlockCategory.attr('category-name');//分类 名称
    	var productId = parentsBlockCategory.find('a.product-pic').attr('lid');//产品 ID
    	var productName = parentsBlockCategory.find('.inmiddle').html();//产品 名称
    	var productPrice = _this.attr('price');//产品 价格
    	
    	var singleNumObj = parentsBlockCategory.find('.single-num-circel');
		var singleNums = 0;
			singleNums = parseInt(singleNumObj.html());
		singleNumObj.html(singleNums+1);
		
		var str = '';
		str +='<div class="order-product catory'+category+'">';
		str +='<div class="product-catory">'+categoryName+'</div>';
		if(parseInt(type)){
			str +='<div class="product-catory-product">'+productName+'<div class="product-catory-product-right"><input class="set-num input-product" type="text" name="'+productId+',1" value="1" price="'+productPrice+'" readonly="true"/> X '+productPrice+'</div></div>';
		}else{
			str +='<div class="product-catory-product">'+productName+'<div class="product-catory-product-right"><input class="set-num input-product" type="text" name="'+productId+'" value="1" price="'+productPrice+'" readonly="true"/> X '+productPrice+'</div></div>';
		}
		str +='</div>';
		
		var substr = '';
		if(parseInt(type)){
			substr +='<div class="product-catory-product">'+productName+'<div class="product-catory-product-right"><input class="set-num input-product" type="text" name="'+productId+',1" value="1" price="'+productPrice+'" readonly="true"/> X '+productPrice+'</div></div>';
		}else{
			substr +='<div class="product-catory-product">'+productName+'<div class="product-catory-product-right"><input class="set-num input-product" type="text" name="'+productId+'" value="1" price="'+productPrice+'" readonly="true"/> X '+productPrice+'</div></div>';
		}
		if($('.catory'+category).length > 0){
			var inputNumObj = $('.catory'+category).find('input[name="'+productId+'"]');
			if(inputNumObj.length > 0){
				var val = inputNumObj.val();
				inputNumObj.val(parseInt(val)+1);
			}else{
				$('.catory'+category).append(substr);
				parentsBlockCategory.find('.subject-order').css('display','block');
			}
		}else{
			$('.product-pad-mask .info').append(str);
			parentsBlockCategory.find('.subject-order').css('display','block');
		}
		
    	var price = parseFloat(_this.attr('price'));
    	var total = 0;
    		total = parseFloat($('.total-price').html());
    	var nums = 0;
    		nums = parseInt($('.total-num').html());
 		
		total += price;
		if(!parseInt(language)){
			total = total.toFixed(2);
		}
		$('.total-price').html(total);
		$('.total-num').html(nums+1);
                
                //alert(padprinterping);
	if (typeof Androidwymenuprinter != "undefined") {
                    if(padprinterping!="local")
                    {
                        Androidwymenuprinter.printNetPing(padprinterping,10);
                    }
                 }	
    });
   
    $('#forum_list').on(event_clicktouchstart,'.delCart',function(){
    	var _this = $(this);
    	var store = _this.parents('.blockCategory').attr('store');
    	if(parseInt(store) >= 0){
    		store =parseInt(store) + 1;
    		 _this.parents('.blockCategory').attr('store',store);
    	}
    	var parentsBlockCategory = _this.parents('.blockCategory');
    	var category = parentsBlockCategory.attr('category');//分类id
    	var productId = parentsBlockCategory.find('a.product-pic').attr('lid');//产品 ID
    	var singleNumObj = parentsBlockCategory.find('.single-num-circel');
    	var singleNums = singleNumObj.html();
    	var inputNumObj = $('.catory'+category).find('input[name="'+productId+'"]');
    	
    	if(parseInt(singleNums) > 1){
    		singleNumObj.html(parseInt(singleNums) - 1);
    		var val = inputNumObj.val();
			inputNumObj.val(parseInt(val)-1);
    	}else{
    		singleNumObj.html(parseInt(singleNums) - 1);
    		inputNumObj.parents('.product-catory-product').remove();
    		if(!$('.catory'+category).find('.product-catory-product').length){
    			$('.catory'+category).remove();
    		}
    		parentsBlockCategory.find('.subject-order').css('display','none');
    	}
    	
    	var productId = _this.attr('product-id');
    	var type = _this.attr('type');
    	var price = parseFloat(_this.attr('price'));
    	var total = 0;
    		total = parseFloat($('.total-price').html());
    	var nums = 0;
    		nums = parseInt($('.total-num').html());
 		if(nums > 0){
	 		total -= price;
	 		if(!parseInt(language)){
				total = total.toFixed(2);
			}
			$('.total-price').html(total);
			$('.total-num').html(nums-1);
 		}
               // alert(padprinterping);
        if (typeof Androidwymenuprinter != "undefined") {
                    if(padprinterping!="local")
                    {
                        Androidwymenuprinter.printNetPing(padprinterping,10);
                    }
                 }        
    });
    $('#forum_list').on(event_clicktouchend,'.product-pic',function(){
    	$('.blockCategory').each(function(){
    		$(this).find('.icon-hover-1').css('left','-150px');
    	 	$(this).find('.icon-hover-2').css('right','-150px');
    	});
    	$(this).find('.icon-hover-1').css('left','20%');
	 	$(this).find('.icon-hover-2').css('right','20%');
	 });
    
    $('#cancelPadOrder').on(event_clicktouchend,function(){
    	$('.product-pad-mask').css('display','none');
    	$('.product-pad-mask').siblings().remove();
    	$('#padOrderForm').find('.input-product').each(function(){
 		 	var _this = $(this);
             var productId = _this.attr('name');
             var num = _this.val(); //获取下单数量
             var productIdArr = productId.split(","); //字符分割 
             productId = productIdArr[0];
             var parents = $('.blockCategory a[lid="'+productId+'"]').parents('.blockCategory');
             //获取库存
             var store = parents.attr('store');
             if(parseInt(store) >= 0){
            	 parents.attr('store',parseInt(num)+parseInt(store));
             }
             var category = parents.attr('category');//分类id
             parents.find('.subject-order').css('display','none');
             parents.find('.single-num-circel').html(0);
             _this.parents('.product-catory-product').remove();
             if(!$('.catory'+category).find('.product-catory-product').length){
	 			$('.catory'+category).remove();
	 			parents.find('.product-taste').removeClass('hasclick'); //去掉口味点击类
	 			parents.find('.taste-list').each(function(eq){
	 				if(eq > 0){
	 					$(this).remove();
	 				}else{
	 					$(this).find('.item').removeClass('active'); //去掉第一个口味选中
	 				}
	 			});
 		    }
        });
    	
    	var total = 0;
    	if(!parseInt(language)){
			total = total.toFixed(2);
		}
    	$('.total-price').html(total);
		$('.total-num').html(0);
    });
   //help
   $('.padsetting').on(event_clicktouchstart,function(){
            $(".setting-pad-mask").toggle();});
   
   $('#content').on(event_clicktouchend,function(){
            $(".setting-pad-mask").css('display','none');
            $('.product-pad-mask').css('display','none');
        });
   
    //查看菜单
    $('body').on(event_clicktouchstart,'.top-right',function(){
    	  if($('.product-pad-mask').is(':hidden')) {
              $('.product-pad-mask').show();
     	  }else{
              $('.product-pad-mask').hide();
          }
          if (typeof Androidwymenuprinter != "undefined") {
            if(padprinterping!="local")
            {
                Androidwymenuprinter.printNetPing(padprinterping,10);
            }
         }
    });
//    $('.product-pad-mask').on(event_clicktouchstart,'.minus',function(){
//		var input = $(this).siblings('input');
//		var num = input.val();
//		if(num > 0){
//			num = num - 1;
//		}
//		input.val(num);	
//		totalPrice();
//		totalNum();		
//	});
//    $('.product-pad-mask').on(event_clicktouchstart,'.plus',function(){
//                //alert('+');
//		var input = $(this).siblings('input');
//		var num = parseInt(input.val());
//		num = num + 1;
//		input.val(num);	
//		totalPrice();	
//		totalNum();
//	});
    $('#pad-disbind-menu').on(event_clicktouchstart,function(){
            location.href='../../../../../../../padbind/login';
            //绑定和解绑必须到我们的服务器。
            //location.href='http://menu.wymenu.com/wymenuv2/padbind/login';
	});
        
    
     //打印测试关闭
    $('#printerClose').on(event_clicktouchstart,function(){
        $('#print_check').hide();
    });
    //打印测试关闭
    $('#printerShow').on(event_clicktouchstart,function(){
        $('#print_check').show();
    });
    //打印校正
    $('#printerCheck').on(event_clicktouchstart,function(){
        if (typeof Androidwymenuprinter == "undefined") {
                alert(language_notget_padinfo);
                return false;
         }
        var padinfo=Androidwymenuprinter.getPadInfo();
        var pad_id=padinfo.substr(10,10); //also can get from session
       	var company_id=padinfo.substr(0,10);
         $.ajax({
 			url:'/wymenuv2/product/printCheck',
 			async: false,
 			data:"companyId="+company_id+'&padId='+pad_id,
 			success:function(msg){
                            var data = eval('(' + msg + ')');
                            if(data.status)
                            {
 				if(Androidwymenuprinter.printJob(data.dpid,data.jobid))
                                {
                                    alert(language_printer_check_success);
                                    isPrintChecked=true;
                                    $('#print_check').hide();
                                }else{
                                    alert(language_printer_check_falil+"1");
                                }
                            }else{
                                alert(language_printer_check_falil+"2");
                            }
 			},
                        error:function(){
 				alert(language_printer_check_falil+"3");
 			},
 		});
                 
    });
    
    $('#pad-app-exit').on(event_clicktouchstart,function(){
            if (typeof Androidwymenuprinter == "undefined") {
                alert(language_notget_padinfo);
                return false;
            }
            var statu = confirm(language_clean_exit);
            if(statu){
                Androidwymenuprinter.appExitClear();
            }
	});
	 
    $('#forum_list').on(event_clicktouchstart,'.view-product-pic',function(){
        var lid = $(this).attr('product-id');
        //alert(lid);//($('.large-pic').width() - $("#gallery").outerWidth())/2,//($('.large-pic').height() - $("#gallery").outerHeight())/2
    	$.ajax({
 			url:'/wymenuv2/product/getProductPicJson',
 			async: false,
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
                        position:'absolute',
                                top: '15%'
                        });
                }else{
                        alert(language_no_bigpic);
                }
},
 		});
    });
    
    $('.large-pic').on(event_clicktouchstart,function(){
    	$(this).html('');
    	$(this).css('display','none');
    });
    
    
    //点产品口味  按照订单数量增加对应数量的口味
    $('#forum_list').on(event_clicktouchstart,'.product-taste',function(){
    	//第一次点击 同步订单数量  点击后增加 hasclick 类
    	var blockCategory = $(this).parents('.blockCategory');
	   	var category = blockCategory.attr('category');//分类id
	   	var productId = blockCategory.find('a.product-pic').attr('lid');//产品 ID
	   	
	   	var inputNumObj = $('.catory'+category).find('input[name="'+productId+'"]');//订单中数量
    	var inputVal = inputNumObj.val();
    	
    	if(!$(this).hasClass('hasClick')){
    		$(this).addClass('hasClick');
    		var inputstr = '<input type="hidden" name="'+productId+'[1-0][0]" value="1"/>';
    		$('#padOrderForm').append(inputstr);
    	}
    	var length = blockCategory.find('.taste-list').length;
    	for(var i=0;i<(inputVal-length);i++){
    		var str = '<div class="taste-list" eq="'+(i+length)+'">';
    			str +='<div class="taste-title"><div class="taste-title-l">第'+(i+length+1)+'道菜口味</div><div class="taste-title-r">';
    			str +='<div class="taste-select">选择</div><div class="taste-same">同上</div><div class="taste-none">无</div><div class="clear"></div><input class="input-product " type="hidden" name="taste-num" value="1" />';
    			str +='</div><div class="clear"></div></div>';
    			str +='<div class="taste-item">';
				str +='</div></div>';
				blockCategory.find('.tastepad').append(str);
				var inputstr = '<input type="hidden" name="'+productId+'[1-'+(i+length)+'][0]" value="1"/>';
    			$('#padOrderForm').append(inputstr);
    	}
    	$('.taste-layer').show();
    	$('.tastepad').hide();
    	$(this).parents('.blockCategory').find('.tastepad').show();
    });
    //点击选择
     $('#forum_list').on(event_clicktouchstart,'.taste-select',function(){
     	var blockCategory = $(this).parents('.blockCategory');
	   	var productId = blockCategory.find('a.product-pic').attr('lid');//产品 ID
     	var tasteList = $(this).parents('.taste-list');
     	var eq = tasteList.attr('eq');
     	tasteList.find('.taste-item').show();
     	if(eq!=0){
     		var preTasteList = tasteList.prev('.taste-list');
     		var item = preTasteList.find('.taste-item').html();
     		tasteList.find('.taste-item').html(item);
     		tasteList.find('.taste-item .item').removeClass('active');
     	}
     	//订单里删除 已存在的口味
     	$('input[name^="'+productId+'['+num+'-'+eq+']"]').each(function(e){
     		if(e>0){
     			$(this).remove();
     		}
     	});
     });
     //点击同上
     $('#forum_list').on(event_clicktouchstart,'.taste-same',function(){
    	var blockCategory = $(this).parents('.blockCategory');
	   	var productId = blockCategory.find('a.product-pic').attr('lid');//产品 ID
     	var tasteList = $(this).parents('.taste-list');
     	var eq = tasteList.attr('eq');
     	var num = tasteList.find('input.input-product').val();
     	tasteList.find('.taste-item').hide();
     	
     	var preTasteList = tasteList.prev('.taste-list');
     	var preEq = preTasteList.attr('eq');
 		var item = preTasteList.find('.taste-item').html();
 		tasteList.find('.taste-item').html(item);
     		
     	//订单里删除 已存在的口味
     	$('input[name^="'+productId+'['+num+'-'+eq+']"]').each(function(e){
     		if(e>0){
     			$(this).remove();
     		}
     	});
     	//订单里 按照上一口味 重新添加
     	$('input[name^="'+productId+'['+num+'-'+preEq+']"]').each(function(e){
     		if(e>0){
     			var name = $(this).attr('name');
     			var o = num+'-'+preEq;
     			var re = num+'-'+eq;
     			alert(o);alert(re);
     			name = name.replace(o,re);
     			alert(name);
     			var str = '<input type="hidden" name="'+name+'" value="1"/>';
    			$('#padOrderForm').append(str);
     		}
     	});
     });
      //点击 无
     $('#forum_list').on(event_clicktouchstart,'.taste-none',function(){
    	var blockCategory = $(this).parents('.blockCategory');
	   	var productId = blockCategory.find('a.product-pic').attr('lid');//产品 ID
     	var tasteList = $(this).parents('.taste-list');
     	var eq = tasteList.attr('eq');
     	var num = tasteList.find('input.input-product').val();
     	tasteList.find('.taste-item').hide();
     	tasteList.find('.taste-item .item').removeClass('active');
     	//订单里删除
     	$('input[name^="'+productId+'['+num+'-'+eq+']"]').each(function(e){
     		if(e>0){
     			$(this).remove();
     		}
     	});
     });
    //选择产品口味
    $('#forum_list').on(event_clicktouchstart,'.tastepad .item',function(){
    	var blockCategory = $(this).parents('.blockCategory');
	   	var productId = blockCategory.find('a.product-pic').attr('lid');//产品 ID
    	var tasteList = $(this).parents('.taste-list');
    	var eq = tasteList.attr('eq');
    	
    	var num = tasteList.find('input.input-product').val();
    	
    	if($(this).hasClass('active')){
    		var tasteId = $(this).attr('taste-id');
    		$('input[name="'+productId+'['+num+'-'+eq+']['+tasteId+']'+'"]').remove();
    		$(this).removeClass('active');
    	}else{
    		var tasteId = $(this).attr('taste-id');
    		var str = '<input type="hidden" name="'+productId+'['+num+'-'+eq+']['+tasteId+']'+'" value="1"/>';
    		$('#padOrderForm').append(str);
    		$(this).addClass('active');
    	}
    });
	
    $('.taste-layer').on('click',function(){
    	$('.tastepad').hide();
    	$(this).hide();
    });
    $('#updatePadOrder').on(event_clicktouchstart,function(){
    	//layer页面层
//    	var str = '<a herf="javascript:;" class="pay-type cash-color" id="cashpay">柜台支付</a><a herf="javascript:;" class="pay-type wx-color" id="weixinpay">微信支付</a><a herf="javascript:;" class="pay-type zfb-color" id="zhifubaopay">支付宝支付</a>';
//		layer.open({
//		    type: 1,
//		    skin: 'layui-layer-rim', //加上边框
//		    area: ['420px', '240px'], //宽高
//		    content: str
//		});
        if (typeof Androidwymenuprinter == "undefined") {
            alert(language_notget_padinfo);
            return false;
        }
    	$('#padOrderForm').ajaxSubmit({
            async:false,
            dataType: "json",
            success:function(msg){
                var data=msg;
                var printresult;
    		if(data.status){
                 if(data.type=='local')
                 {
                     printresult=Androidwymenuprinter.printJob(data.dpid,data.jobid);
                 }else{
                     printresult=Androidwymenuprinter.printNetJob(data.dpid,data.jobid,data.address);
                 }
                 if(printresult)
                 {
                	 $('#padOrderForm').find('.input-product').each(function(){
                		 	var _this = $(this);
                            var productId = _this.attr('name');
                            var productIdArr = productId.split(","); //字符分割 
                            productId = productIdArr[0];
                            var parents = $('.blockCategory a[lid="'+productId+'"]').parents('.blockCategory');
                            var category = parents.attr('category');//分类id
                            parents.find('.subject-order').css('display','none');
                            parents.find('.single-num-circel').html(0);
                            _this.parents('.product-catory-product').remove();
                            if(!$('.catory'+category).find('.product-catory-product').length){
			    			$('.catory'+category).remove();
			    			parents.find('.product-taste').removeClass('hasclick'); //去掉口味点击类
			    			parents.find('.taste-list').each(function(eq){
			    				if(eq > 0){
			    					$(this).remove();
			    				}else{
			    					$(this).find('.item').removeClass('active'); //去掉第一个口味选中
			    				}
			    			});
			    		}
                     });
                     $('.product-pad-mask').hide();
                     var total = 0;
                     if(!parseInt(language)){
             			total = total.toFixed(2);
             		}
                     $('.total-price').html(total);
                        $('.total-num').html(0);
                 }else{
                     alert(language_print_pad_fail);
                 }                                                
                }else{
                    alert(data.msg);
                }
    		}
     	});
    });
    $('body').on(event_clicktouchstart,'#cashpay',function(){
     	alert('现金支付');
     });
     $('body').on(event_clicktouchstart,'#weixinpay',function(){
     	alert('微信支付');
     });
     $('body').on(event_clicktouchstart,'#zhifubaopay',function(){
     	alert('支付宝支付');
     });
    $('#padOrderForm').on('click','.product-catory-product',function(){
    	var input = $(this).find('input');
    	var productId = input.attr('name');
    	var productIdArr = productId.split(","); //字符分割 
        productId = productIdArr[0];
        var parents = $('.blockCategory a[lid="'+productId+'"]').parents('.blockCategory');
        var category = parents.attr('category');//分类id
        $('#pad_category_select').val(category);
        var height = parents.offset().top;
		$('body').scrollTop(parseInt(height)-70);
        $(".setting-pad-mask").css('display','none');
        $('.product-pad-mask').css('display','none');
    });
 });