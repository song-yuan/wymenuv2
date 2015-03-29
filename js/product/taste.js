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
	   $('.mask-bottom .area-top').html('做法口味选择:');
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
		   $('.mask-bottom .area-top').html('全单有话要说:');
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
 });