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
        var istemp=$('#id_client_is_temp').val();
        if(istemp=="1")
        {
            alert("请先选择座位！");
            return false;
        }
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
    	//套餐显示详情
    	if(parseInt(type)){
    		if(!$(this).hasClass('hasClick')){
    			parentsBlockCategory.find('.productset-group').each(function(){
    				$(this).find('.item').each(function(){
            			var isSelect = $(this).attr('is-select');
            			if(parseInt(isSelect)==1){
            				$(this).click();
            			}
            		});
    			});
    			$(this).addClass('hasClick')
    		}
    		$('.taste-layer').hide();
	    	$('.productsetpad').hide();
	    	parentsBlockCategory.find('.taste-layer').show();
	    	parentsBlockCategory.find('.productsetpad').show();
	    	return;
    	}
    	
    	var category = parentsBlockCategory.attr('category');//分类id
    	var categoryName = parentsBlockCategory.attr('category-name');//分类 名称
    	var productId = parentsBlockCategory.find('a.product-pic').attr('lid');//产品 ID
    	var productName = parentsBlockCategory.find('.inmiddle').html();//产品 名称
    	var productPrice = _this.attr('price');//产品 价格
    	
    	var singleNumObj = parentsBlockCategory.find('.single-num-circel');
		var singleNums = 0;
			singleNums = parseInt(singleNumObj.html());
		
		singleNumObj.html(singleNums+1);
		//数量显示
		singleNumObj.css('display','block');
		
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
			}
		}else{
			$('.product-pad-mask .info').append(str);
		}
		
		//把以前选择的口味清除
		if(parentsBlockCategory.find('.product-taste').hasClass('hasClick')){
			var eq = singleNums;
			
			var str = '<div class="taste-list" eq="'+eq+'">';
				str +='<div class="taste-title"><div class="taste-title-l">第'+(eq+1)+'道菜口味</div><div class="taste-title-r">';
				str +='<div class="taste-select">选择</div><div class="taste-none">无</div><div class="clear"></div><input class="input-product " type="hidden" name="taste-num" value="1" />';
				str +='</div><div class="clear"></div></div>';
				str +='<div class="taste-item">';
				str +='</div></div>';
			parentsBlockCategory.find('.tastepad').append(str);
		
			var inputstr = '<input type="hidden" name="'+productId+'[1-'+eq+'][0]" value="1"/>';
    		$('#padOrderForm').append(inputstr);
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
			
			if(parseInt(store) >= 0){
	    		store =parseInt(store) + 1;
	    		 _this.parents('.blockCategory').attr('store',store);
    		}
			if(parentsBlockCategory.find('.product-taste').hasClass('hasClick')){
				var eq = singleNums - 1;
				$('input[name^="'+productId+'[1-'+eq+']"]').remove();
				
				//减菜时如果有口味了 去掉最后一个口味
				parentsBlockCategory.find('.tastepad .taste-list[eq="'+eq+'"]').remove();
			}
    	}else{
    		if(parseInt(singleNums)==0){
    			return;
    		}
    		
    		if(parseInt(store) >= 0){
	    		store =parseInt(store) + 1;
	    		 _this.parents('.blockCategory').attr('store',store);
    		}
    		//数量为1的情况
    		singleNumObj.html(parseInt(singleNums) - 1);//到达数量0
    		//数量0时 隐藏
			singleNumObj.css('display','none');
			//数量0时点击口味 类 移除
			if(parentsBlockCategory.find('.product-taste').hasClass('hasClick')){
				parentsBlockCategory.find('.product-taste').removeClass('hasClick');
				var eq = singleNums - 1;
				$('input[name^="'+productId+'[1-'+eq+']"]').remove();
				
				//减菜时如果有口味了 去掉最后一个口味
				parentsBlockCategory.find('.tastepad .taste-list[eq="'+eq+'"]').find('.item').removeClass('active');
			}
			
    		inputNumObj.parents('.product-catory-product').remove();
    		if(!$('.catory'+category).find('.product-catory-product').length){
    			$('.catory'+category).remove();
    		}
    	}
    	
    	var productId = _this.attr('product-id');
    	var type = _this.attr('type');
    	if(parseInt(type)){
    		parentsBlockCategory.find('.addCart').removeClass('hasClick'); //去掉点击
    		parentsBlockCategory.find('.productset-group').each(function(){
    			$(this).find('.active').removeClass('active');
    		});
    		$('input[name^="'+productId+'"]').remove();
    	}
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
    	 	$(this).find('.icon-hover-3').css('left','-150px');
    	 	$(this).find('.icon-hover-4').css('right','-150px');
    	});
    	$(this).find('.icon-hover-1').css('left','20%');
	 	$(this).find('.icon-hover-2').css('right','20%');
	 	$(this).find('.icon-hover-3').css('left','20%');
	 	$(this).find('.icon-hover-4').css('right','20%');
	 });
    
    $('#cancelPadOrder').on(event_clicktouchend,function(){
    	$('.product-pad-mask').css('display','none');
    	$('.product-pad-mask').siblings().remove();
    	$('#padOrderForm').find('.input-product').each(function(){
 		 	var _this = $(this);
             var productId = _this.attr('name');
             var num = _this.val(); //获取下单数量
             var parents = $('.blockCategory a[lid="'+productId+'"]').parents('.blockCategory');
             //获取库存
             var store = parents.attr('store');
             if(parseInt(store) >= 0){
            	 parents.attr('store',parseInt(num)+parseInt(store));
             }
             var category = parents.attr('category');//分类id
             parents.find('.single-num-circel').css('display','none').html(0);
             
             if(parents.find('.product-taste').hasClass('hasClick')){
                 parents.find('.product-taste').removeClass('hasClick'); //去掉口味点击类
                 parents.find('.taste-list').each(function(eq){
                   if(eq > 0){
                           $(this).remove();
                   }else{
                           $(this).find('.item').removeClass('active'); //去掉第一个口味选中
                   }
           	     });
             }
             
            //清空选中套餐
            parents.find('.productset-group').each(function(){
    			$(this).find('.active').removeClass('active');
    		});
    		
 		    $('input[name^="'+productId+'"]').remove();
        });
        //清空订单
    	$('#padOrderForm').find('.info').html('');
    	
    	var total = 0;
    	if(!parseInt(language)){
			total = total.toFixed(2);
		}
    	$('.total-price').html(total);
		$('.total-num').html(0);
    });
   //help
   $('.padsetting').on(event_clicktouchstart,function(){
            $(".setting-pad-mask").toggle();
            $('.product-pad-mask').hide();         
   });
   
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
              //alert(padprinterping);
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
        //alert(1);
            var statu = confirm(language_sure_bind);
            if(statu){
                location.href='../../../../../../../padbind/login';
            }
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
	 
    $('#forum_list').on('click','.view-product-pic',function(){
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
                }else{
                        alert(language_no_bigpic);
                }
            }
 		});
    	 var left = ($(window).width() - $("#gallery").width())/2;
         var top = ($(window).height() - $("#gallery").height())/2;
         if(left < 0){
         	left = 0;
         }
         if(top < 0){
         	top = 0;
         }
         $('#gallery').css({'top':top,'left':left});
    });
    
    $('.large-pic').on("click",function(){
    	$(this).html('');
    	$(this).css('display','none');
    });
    
  //全单口味
    $('#order-tastes-btn').on(event_clicktouchend,function(){
    	var tasteItem = $('.order-tastes').find('.taste-item .item');
    	if(!tasteItem.length > 0){
    		layer.msg('没有订单口味!');
    		return;
    	}
    	$('.taste-all-layer').show();
    	$('.tastepad').hide();
    	$('.order-tastes').show();
    });
    
    //点产品口味  按照订单数量增加对应数量的口味
    $('#forum_list').on(event_clicktouchend,'.product-taste',function(){
    	//第一次点击 同步订单数量  点击后增加 hasclick 类
    	var blockCategory = $(this).parents('.blockCategory');
	   	var category = blockCategory.attr('category');//分类id
	   	var productId = blockCategory.find('a.product-pic').attr('lid');//产品 ID
	   	
	   	var inputNumObj = $('.catory'+category).find('input[name="'+productId+'"]');//订单中数量
    	var inputVal = inputNumObj.val();
    	
    	var tasteItem = blockCategory.find('.tastepad .item');
    	if(tasteItem.length == 0){
    		layer.msg('该产品无口味!');
    		return;
    	}
    	
    	var singleNum = blockCategory.find('.single-num-circel').html(); //查看该产品下单数量
    	if(parseInt(singleNum)==0){
    		layer.msg('请添加该产品到订单!');
    		return;
    	}
    	if(!$(this).hasClass('hasClick')){
    		$(this).addClass('hasClick');
    		var inputstr = '<input type="hidden" name="'+productId+'[1-0][0]" value="1"/>';
    		$('#padOrderForm').append(inputstr);
    		
    		var length = blockCategory.find('.taste-list').length;
        	for(var i=0;i<(inputVal-length);i++){
        		var str = '<div class="taste-list" eq="'+(i+length)+'">';
        			str +='<div class="taste-title"><div class="taste-title-l">第'+(i+length+1)+'道菜口味</div><div class="taste-title-r">';
        			str +='<div class="taste-select">选择</div><div class="taste-none">无</div><div class="clear"></div><input class="input-product " type="hidden" name="taste-num" value="1" />';
        			str +='</div><div class="clear"></div></div>';
        			str +='<div class="taste-item">';
    				str +='</div></div>';
    				blockCategory.find('.tastepad').append(str);
    				var inputstr = '<input type="hidden" name="'+productId+'[1-'+(i+length)+'][0]" value="1"/>';
        			$('#padOrderForm').append(inputstr);
        	}
    	}
    	
    	
    	$('.taste-layer').hide();
    	$('.tastepad').hide();
    	blockCategory.find('.taste-layer').show();
    	blockCategory.find('.tastepad').show();
    });
    //点击选择
     $('#forum_list').on(event_clicktouchstart,'.tastepad .taste-select',function(){
     	var blockCategory = $(this).parents('.blockCategory');
	   	var productId = blockCategory.find('a.product-pic').attr('lid');//产品 ID
     	var tasteList = $(this).parents('.taste-list');
     	var eq = tasteList.attr('eq');
     	var num = tasteList.find('input.input-product').val();
     	tasteList.find('.taste-item').show();
     	if(eq!=0){
     		var preTasteList = tasteList.prev('.taste-list');
     		var item = preTasteList.find('.taste-item').html();
     		tasteList.find('.taste-item').html(item);
     		tasteList.find('.taste-item .item').removeClass('active');
     	}else{
     		tasteList.find('.taste-item .item').removeClass('active');
     	}
     	//订单里删除 已存在的口味
     	$('input[name^="'+productId+'['+num+'-'+eq+']"]').each(function(e){
     		if(e>0){
     			$(this).remove();
     		}
     	});
     });
     //点击全单选择
     $('body').on(event_clicktouchstart,'.order-tastes .taste-select',function(){
     	  var tasteList = $(this).parents('.taste-list');
     	  tasteList.find('.taste-item').show();
     });
     //点击同上
     $('#forum_list').on(event_clicktouchstart,'.tastepad .taste-same',function(){
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
     			name = name.replace(o,re);
     			var str = '<input type="hidden" name="'+name+'" value="1"/>';
    			$('#padOrderForm').append(str);
     		}
     	});
     });
      //点击 无
     $('#forum_list').on(event_clicktouchstart,'.tastepad .taste-none',function(){
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
     //点击全单 无
     $('body').on(event_clicktouchstart,'.order-tastes .taste-none',function(){

    	var tasteList = $(this).parents('.taste-list');
     	tasteList.find('.taste-item .item').removeClass('active');
     	//订单里删除
     	$('input[name^="quandan"]').each(function(e){
     		$(this).remove();
     	});
     });
    //选择产品口味
    $('#forum_list').on(event_clicktouchstart,'.tastepad .item',function(){
    	var blockCategory = $(this).parents('.blockCategory');
	   	var productId = blockCategory.find('a.product-pic').attr('lid');//产品 ID
    	var tasteList = $(this).parents('.taste-list');
    	var tasteGroup = $(this).parents('.taste-group');
    	var eq = tasteList.attr('eq');
    	
    	var num = tasteList.find('input.input-product').val();
    	
    	if(!$(this).hasClass('active')){
    		var tasteId = $(this).attr('taste-id');
    		var str = '<input type="hidden" name="'+productId+'['+num+'-'+eq+']['+tasteId+']'+'" value="1"/>';
    		$('#padOrderForm').append(str);
    		$(this).addClass('active');
    		$(this).siblings().each(function(){
    			if($(this).hasClass('active')){
		    		var tasteId = $(this).attr('taste-id');
		    		$('input[name="'+productId+'['+num+'-'+eq+']['+tasteId+']'+'"]').remove();
		    		$(this).removeClass('active');
		    	}
    		});
    	}
    });
   
     $('body').on(event_clicktouchstart,'.productset-confirm',function(){
     	var _this = $(this);
    	var parentsBlockCategory = _this.parents('.blockCategory');
    	var productsetGroup = parentsBlockCategory.find('.productset-group');
    	var flag = true;
    	productsetGroup.each(function(){
    		if(!$(this).find('.active').length){
    			flag = false;
    			return false;
    		}
    	});
    	if(!flag){
    		layer.msg('请选择套餐');
    		return;
    	}
    	
    	$('.productsetpad').hide();
    	$('.taste-layer').hide();
    	
    	var category = parentsBlockCategory.attr('category');//分类id
    	var categoryName = parentsBlockCategory.attr('category-name');//分类 名称
    	var productId = parentsBlockCategory.find('a.product-pic').attr('lid');//产品 ID
    	var productName = parentsBlockCategory.find('.inmiddle').html();//产品 名称
    	var productPrice = parentsBlockCategory.find('.addCart').attr('price');//产品 价格
    	
    	var singleNumObj = parentsBlockCategory.find('.single-num-circel');
		var singleNums = 0;
			singleNums = parseInt(singleNumObj.html());
			
		if(!singleNums){
			singleNumObj.html(singleNums+1);
		}
		//数量显示
		singleNumObj.css('display','block');
		
		var str = '';
		str +='<div class="order-product catory'+category+'">';
		str +='<div class="product-catory">'+categoryName+'</div>';
		str +='<div class="product-catory-product">'+productName+'<div class="product-catory-product-right"><input class="set-num input-product" type="text" name="'+productId+'" value="1" price="'+productPrice+'" readonly="true"/> X '+productPrice+'</div></div>';
		str +='</div>';
		
		var substr = '';
		substr +='<div class="product-catory-product">'+productName+'<div class="product-catory-product-right"><input class="set-num input-product" type="text" name="'+productId+'" value="1" price="'+productPrice+'" readonly="true"/> X '+productPrice+'</div></div>';
		if($('.catory'+category).length > 0){
			var inputNumObj = $('.catory'+category).find('input[name="'+productId+'"]');
			if(inputNumObj.length > 0){
				var val = inputNumObj.val();
				if(!singleNums){
					inputNumObj.val(parseInt(val)+1);
				}
			}else{
				$('.catory'+category).append(substr);
			}
		}else{
			$('.product-pad-mask .info').append(str);
		}
		
		//把以前选择的口味清除
		if(parentsBlockCategory.find('.product-taste').hasClass('hasClick')){
			var eq = singleNums;
			
			var str = '<div class="taste-list" eq="'+eq+'">';
				str +='<div class="taste-title"><div class="taste-title-l">第'+(eq+1)+'道菜口味</div><div class="taste-title-r">';
				str +='<div class="taste-select">选择</div><div class="taste-none">无</div><div class="clear"></div><input class="input-product " type="hidden" name="taste-num" value="1" />';
				str +='</div><div class="clear"></div></div>';
				str +='<div class="taste-item">';
				str +='</div></div>';
			parentsBlockCategory.find('.tastepad').append(str);
		
			var inputstr = '<input type="hidden" name="'+productId+'[1-'+eq+'][0]" value="1"/>';
    		$('#padOrderForm').append(inputstr);
		}
		
		
    	var price = parseFloat(productPrice);
    	var total = 0;
    		total = parseFloat($('.total-price').html());
    	var nums = 0;
    		nums = parseInt($('.total-num').html());
 		
 		if(!singleNums){
			total += price;
			if(!parseInt(language)){
				total = total.toFixed(2);
			}
			$('.total-price').html(total);
			$('.total-num').html(nums+1);
		}
                
                //alert(padprinterping);
	if (typeof Androidwymenuprinter != "undefined") {
                    if(padprinterping!="local")
                    {
                        Androidwymenuprinter.printNetPing(padprinterping,10);
                    }
                 }	
     });
     //更换套餐明细
	    $('#forum_list').on('click','.productsetpad .item',function(){
		   	var blockCategory = $(this).parents('.blockCategory');
			   	var productId = blockCategory.find('a.product-pic').attr('lid');//套餐 ID
			   	
			   	var productsetDetailLid = $(this).attr('productset-detail-id');
		   	var productsetGroup = $(this).parents('.productset-group');
		   	var groupNo = productsetGroup.attr('group-no');
		   	
		   	if(!$(this).hasClass('active')){
		   		var str = '<input type="hidden" name="'+productId+'['+groupNo+']['+productsetDetailLid+']'+'" value="1"/>';
		   		$('#padOrderForm').append(str);
		   		$(this).addClass('active');
		   		$(this).siblings().each(function(){
		   			if($(this).hasClass('active')){
				    		var detailId = $(this).attr('productset-detail-id');
				    		$('input[name="'+productId+'['+groupNo+']['+detailId+']'+'"]').remove();
				    		$(this).removeClass('active');
				    	}
		   		});
		   	}
	   });
     //套餐取消
      $('body').on(event_clicktouchstart,'.productset-cancel',function(){
	      	var _this = $(this);
	    	var parentsBlockCategory = _this.parents('.blockCategory');
	    	var productId = parentsBlockCategory.find('a.product-pic').attr('lid');//产品 ID
	    	var price = parentsBlockCategory.find('.addCart').attr('price');//产品 价格
	    	var store = parentsBlockCategory.attr('store');
	    	
	    	var productsetGroup = parentsBlockCategory.find('.productset-group');
	    	productsetGroup.each(function(){
	    		$(this).find('.active').removeClass('active');
	    	});
	    	parentsBlockCategory.find('.addCart').removeClass('hasClick'); //去掉点击
	    	
	    	var singleNumObj = parentsBlockCategory.find('.single-num-circel');
	    	var singleNums = 0;
	    	
			singleNums = parseInt(singleNumObj.html());
			singleNumObj.html(0);
			//数量显示
			singleNumObj.css('display','none');
	    	$('input[name^="'+productId+'"]').remove();
	    	if(parseInt(singleNums)){
	    		if(parseInt(store) >= 0){
		    		store =parseInt(store) + 1;
		    		parentsBlockCategory.attr('store',store);
	    		}
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
	    	}
 		
	    	$('.productsetpad').hide();
    		$('.taste-layer').hide();
      });
    //选择全单口味
    $('body').on(event_clicktouchstart,'.order-tastes .item',function(){

    	if(!$(this).hasClass('active')){
    		var tasteId = $(this).attr('taste-id');
    		var str = '<input type="hidden" name="quandan['+tasteId+']'+'" value="1"/>';
    		$('#padOrderForm').append(str);
    		$(this).addClass('active');
    		$(this).siblings().each(function(){
    			if($(this).hasClass('active')){
		    		var tasteId = $(this).attr('taste-id');
		    		$('input[name="quandan['+tasteId+']'+'"]').remove();
		    		$(this).removeClass('active');
		    	}
    		});
    	}
    });
	  //点击  确定
     $('body').on(event_clicktouchstart,'.taste-confirm',function(){
    	$('.tastepad').hide();
    	$('.order-tastes').hide();
    	$('.taste-layer').hide();
    	$('.taste-all-layer').hide();
     });
   
  //  $('.taste-layer').on(event_clicktouchstart,function(){
    //	$('.tastepad').hide();
    //  $(this).hide();
    //});
     $('#updatePadOrder').on(event_clicktouchstart,function(){
     	alert('点完单,请联系服务员,谢谢!');
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