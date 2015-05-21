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
    $('#forum_list').on('click','.addCart',function(){
    	var _this = $(this);
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
		total = total.toFixed(2);
		$('.total-price').html(total);
		$('.total-num').html(nums+1);
		
    });
   
    $('#forum_list').on('click','.delCart',function(){
    	var _this = $(this);
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
			total = total.toFixed(2);
			$('.total-price').html(total);
			$('.total-num').html(nums-1);
 		}
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
    	  if($('.product-pad-mask').is(':hidden')) {
              $('.product-pad-mask').show();
     	  }else{
              $('.product-pad-mask').hide();
          }
    	//});
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
            var statu = confirm("清除完缓存后，应用程序会自动退出，请重新打开！确定清除吗？");
            if(statu){
                Androidwymenuprinter.appExitClear();
                //alert("应用程序无法完整清楚所有缓存数据，请到“设置”->“应用程序”->“WebWyMenu”中手动清除！");
            }
	});
    $('#forum_list').on('click','.view-product-pic',function(){
    	//var lid = $(this).attr('lid');
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
                    alert('没有大图！');
                }
 			},
 		});
 		var left = 0;
		if(($('.large-pic').width() - $("#gallery").outerWidth()) > 0){
			left = ($('.large-pic').width() - $("#gallery").outerWidth())/2;
		}
		var top = 0;
		if( ($('.large-pic').height() - $("#gallery").outerHeight()) > 0){
			top = ($('.large-pic').height() - $("#gallery").outerHeight())/2 ;
		}
        $("#gallery").css({
			position: "absolute",
			left: left,
			top: top
		});
		$('#gallery').slick({
			  dots: true,
			  infinite: true,
			  speed: 1000,
			  slidesToShow: 1,
	  		  slidesToScroll: 1,
	  		  autoplay: true,
			  arrows: false
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
        var pad_id=padinfo.substr(10,10); //also can get from session
       	//var pad_id=0000000008;
    	$('#padOrderForm').ajaxSubmit(function(msg){
    		var data = eval('(' + msg + ')');
    		if(data.status){
                 if(Androidwymenuprinter.printJob(data.dpid,data.jobid))
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
                     	_this.parents('.product-catory-product')remove();
                     	if(!$('.catory'+category).find('.product-catory-product').length){
			    			$('.catory'+category).remove();
			    		}
                     });
                     var total = 0;
                     total.toFixed(2);
                     $('.total-price').html(total);
					 $('.total-num').html(0);
                     alert("打印成功");
                 }
                 else
                 {
                     alert("PAD打印失败！，请确认打印机连接好后再试！");                                                                        
                 }                                                
                }else{
                    alert(data.msg);
                }
    	});
    	return false;
    });
    $('#padOrderForm').on('click','.product-catory-product',function(){
    	var input = $(this).find('input');
    	var productId = input.attr('name');
    	var productIdArr = productId.split(","); //字符分割 
        productId = productIdArr[0];
        var parents = $('.blockCategory a[lid="'+productId+'"]').parents('.blockCategory');
        var category = parents.attr('category');//分类id
        $('#pad_category_select').val(category);
    });
 });