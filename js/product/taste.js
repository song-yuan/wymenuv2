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
$(document).ready(function(){
	var feedback = 0;
   $('.select-taste').click(function(){
	   var type = parseInt($(this).attr('type'));
	   var id = parseInt($(this).attr('data-id'));
	   $('.mask-type').val(type);
	   $('.mask-id').val(id);
	   if(type==1){
		   $.ajax({
			   url:'/wymenuv2/product/getTasteJson',
			   type:'GET',
			   data:{type:type,id:id},
			   dataType:'JSON',
			   success:function(data){
				   $('.mask-taste').html('');
				   if(data){
					   for(var o in data.taste){ 
						   if(parseInt(data.taste[o].has)){
							   $('.mask-taste').append('<div class="taste taste-active" taste-id="'+data.taste[o].lid+'">'+data.taste[o].name+'</div>');
						   }else{
							   $('.mask-taste').append('<div class="taste" taste-id="'+data.taste[o].lid+'">'+data.taste[o].name+'</div>'); 
						   }
					   }
					   $('textarea[name="taste-memo"]').val(data.taste_memo);
					   $('.mask-taste').append('<div class="clear"></div>');
				   }
			   }
		   });
	   }
	   if(type==2){
		   var productId = parseInt($(this).attr('product-id'));
		   $('.product-id').val(productId);
		   $.ajax({
			   url:'/wymenuv2/product/getTasteJson',
			   type:'GET',
			   data:{type:type,id:id,productId:productId},
			   dataType:'JSON',
			   success:function(data){
				   $('.mask-taste').html('');
				   if(data){
					   for(var o in data.taste){ 
						   if(parseInt(data.taste[o].has)){
							   $('.mask-taste').append('<div class="taste taste-active" taste-id="'+data.taste[o].lid+'">'+data.taste[o].name+'</div>');
						   }else{
							   $('.mask-taste').append('<div class="taste" taste-id="'+data.taste[o].lid+'">'+data.taste[o].name+'</div>'); 
						   }
					   }
					   $('textarea[name="taste-memo"]').val(data.taste_memo);
					   $('.mask-taste').append('<div class="clear"></div>');
				   }
			   }
		   });
	   }
	   $('.mask-bottom .area-top').html(language_taste_select);
	   if(type==3){
		   var productId = parseInt($(this).attr('product-id'));
		   $('.product-id').val(productId);
		   $.ajax({
			   url:'/wymenuv2/product/getFeebackJson',
			   type:'GET',
			   data:{type:type,id:id,productId:productId},
			   dataType:'JSON',
			   success:function(data){
				   $('.mask-taste').html('');
				   if(data){
					   feedback = data;
					   for(var o in data){ 
						  $('.mask-taste').append('<div class="feeback" feeback-id="'+data[o].lid+'">'+data[o].name+'</div>'); 
					   }
					   $('.mask-taste').append('<div class="clear"></div>');
				   }
			   }
		   });
		   $('.mask-bottom .area-top').html(language_call_service);
	   }
	   $('.mask').css('display','block');
   });
   
   $('.mask-taste').on('click','.taste',function(){
	   if($(this).hasClass('taste-active')){
		   $(this).removeClass('taste-active');
	   }else{
		   $(this).addClass('taste-active');
		   $
	   }
   });
   
   $('.mask-taste').on('click','.feeback',function(){
	   $('.feeback').removeClass('feeback-active');
	   $(this).addClass('feeback-active');
	   var id = $(this).attr('feeback-id');
	   if(feedback){
		   for(var o in feedback){
			   if(feedback[o].lid==id){
				   $('textarea[name="taste-memo"]').attr('placeholder',feedback[o].tip);
				   $('textarea[name="taste-memo"]').val(feedback[o].feedback_memo);
			   }
		   }
	   }
   });
   
   $('.mask-bottom').on('click','.submit',function(){
	   var type = $('.mask-type').val();
	   var id = $('.mask-id').val();
	   var productId = $('.product-id').val();
	   var tasteMemo = $('textarea[name="taste-memo"]').val();
	   
	   if(parseInt(type)==3){
		   var tasteIds = 0;
		   $('.feeback').each(function(){
			   if($(this).hasClass('feeback-active')){
				   tasteIds = $(this).attr('feeback-id');
			   }
		   });
		   if(!tasteIds){
			   alert(language_calltype_select);
			   return ;
		   }
	   }else{
		   var tasteIds = new Array();
		   $('.taste').each(function(){
			   if($(this).hasClass('taste-active')){
				   tasteIds.push($(this).attr('taste-id'));
			   }
		   });
	   }
	  
	   $.ajax({
		   url:'/wymenuv2/product/setOrderTaste',
		   type:'POST',
		   data:{type:type,id:id,tasteIds:tasteIds,tasteMemo:tasteMemo},
		   success:function(data){
			   if(parseInt(data)){
				 $('.mask').css('display','none');  
			   }
		   }
	   });
   });
    $('.mask-bottom').on('click','.cancel',function(){
    	$('.mask').css('display','none');  
    });
    
    $('.edit-num').click(function(){
    	 $('.mask-taste').html('');
    	 var setId = $(this).attr('set-id');
    	 if(setId==undefined){
    		 setId = 0;
    	 }
    	 var productId = $(this).attr('product-id');
    	 $('.set-id').val(setId);
    	 $('.product-id').val(productId);
    	 
    	 var num = $('input[name="'+productId+'"]').val();
    	 $('input[name="order-product-num"]').val(num);
    	 $('textarea[name="taste-memo"]').css('display','none');
    	 $('.order-num').css('display','block');
    	 
    	 $('.submit').css('display','none');
    	 $('.submit-order-num').css('display','block');
    	 
    	 $('.cancel').css('display','none');
    	 $('.cancel-order-num').css('display','block');
    	 $('.area-top').html(language_productnum_modify);
    	 $('.mask').css('display','block');
    });
    $('.cancel-order-num').click(function(){
    	$('textarea[name="taste-memo"]').css('display','block');
   	    $('.order-num').css('display','none');
   	 
    	 $('.submit').css('display','block');
    	 $('.submit-order-num').css('display','none');
    	 
    	 $('.cancel').css('display','block');
    	 $('.cancel-order-num').css('display','none');
    	 
    	 $('.area-top').html(language_taste_select);
    	 $('.mask').css('display','none');
    });
   
	$('.submit-order-num').click(function(){
		var setId = $('.set-id').val();
		var productId = $('.product-id').val();
		var num = $('input[name="order-product-num"]').val();
		var inputObj = $('input[name="'+productId+'"]');
		inputObj.val(num);
		inputObj.parents('.product').find('.num').html(num);
		if(parseInt(setId)>0){
			$('input[set-id="'+setId+'"]').each(function(){
				$(this).val(num);
				$(this).parents('.product').find('.num').html(num);
			});
		}
		totalPrice();
		$('.cancel-order-num').click();
	});
});