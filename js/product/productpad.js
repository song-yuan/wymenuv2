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
    $('#forum_list').on('touchstart','.addCart',function(){
    	var _this = $(this);
    	var store = _this.parents('.blockCategory').attr('store');
    	if(parseInt(store)==0){
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
   
    $('#forum_list').on('touchstart','.delCart',function(){
    	var _this = $(this);
    	var store = _this.parents('.blockCategory').attr('store');
    	if(parseInt(store) >= 0){
    		store += 1;
    		 _this.attr('store',store);
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
    $('#forum_list').on('touchend','.product-pic',function(){
    	$('.blockCategory').each(function(){
    		$(this).find('.icon-hover-1').css('left','-150px');
    	 	$(this).find('.icon-hover-2').css('right','-150px');
    	});
    	$(this).find('.icon-hover-1').css('left','20%');
	 	$(this).find('.icon-hover-2').css('right','20%');
	 });
    $('#cancelPadOrder').on('touchend',function(){
    	$('.product-pad-mask').find('.info').html('');
    	$('.product-pad-mask').css('display','none');
    	$('.blockCategory').each(function(){
    		$(this).find('.subject-order').css('display','none');
    		$(this).find('.single-num-circel').html(0);
    	});
    	var total = 0;
    	if(!parseInt(language)){
			total = total.toFixed(2);
		}
    	$('.total-price').html(total);
		$('.total-num').html(0);
    });
   //help
   $('.padsetting').on('touchstart',function(){
            $(".setting-pad-mask").toggle();});
   
   $('#content').on('touchend',function(){
            $(".setting-pad-mask").css('display','none');
            $('.product-pad-mask').css('display','none');
        });
   
    //查看菜单
    $('body').on('touchstart','.top-right',function(){
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
    $('.product-pad-mask').on('touchstart','.minus',function(){
		var input = $(this).siblings('input');
		var num = input.val();
		if(num > 0){
			num = num - 1;
		}
		input.val(num);	
		totalPrice();
		totalNum();		
	});
    $('.product-pad-mask').on('touchstart','.plus',function(){
                //alert('+');
		var input = $(this).siblings('input');
		var num = parseInt(input.val());
		num = num + 1;
		input.val(num);	
		totalPrice();	
		totalNum();
	});
    $('#pad-disbind-menu').on('touchstart',function(){
            location.href='../../../../../../../padbind/login';
            //绑定和解绑必须到我们的服务器。
            //location.href='http://menu.wymenu.com/wymenuv2/padbind/login';
	});
     //打印测试关闭
    $('#printerClose').on('touchstart',function(){
        $('#print_check').hide();
    });
    //打印测试关闭
    $('#printerShow').on('touchstart',function(){
        $('#print_check').show();
    });
    //打印校正
    $('#printerCheck').on('touchstart',function(){
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
    
    $('#pad-app-exit').on('touchstart',function(){
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
    
    $('.large-pic').on('click',function(){
    	$(this).html('');
    	$(this).css('display','none');
    });
    
    $('#updatePadOrder').on('touchstart',function(){
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
     $('#padOrderForm').submit(function(){
    	$(this).ajaxSubmit({
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
     	return false;
      });
    });
    $('body').on('touchstart','#cashpay',function(){
     	alert('现金支付');
     });
     $('body').on('touchstart','#weixinpay',function(){
     	alert('微信支付');
     });
     $('body').on('touchstart','#zhifubaopay',function(){
     	alert('支付宝支付');
     });
    $('#padOrderForm').on('touchstart','.product-catory-product',function(){
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