$(document).ready(function(){
   $('.select-taste').click(function(){
	   var type = parseInt($(this).attr('type'));
	   var id = parseInt($(this).attr('data-id'));
	   $('.mask-type').val(type);
	   $('.mask-id').val(id);
	   if(type==1){
		   $.ajax({
			   url:'/wymenuv2/product/getTasteJson',
			   type:'GET',
			   data:{type:type,id:id,productId:productId},
			   success:function(data){
				   
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
			   success:function(data){
				   
			   }
		   });
	   }
	   $('.mask').css('display','block');
   });
   $('.taste').click(function(){
	   if($(this).hasClass('taste-active')){
		   $(this).removeClass('taste-active');
	   }else{
		   $(this).addClass('taste-active');
	   }
   });
   $('.submit').click(function(){
	   var type = $('.mask-type').val();
	   var id = $('.mask-id').val();
	   $('.mask').css('display','none');
   })
 });