<?php
/* @var $this ProductController */
	Yii::app()->clientScript->registerCssFile('../css/product/ui-btn.css');
	Yii::app()->clientScript->registerCssFile('../css/product/ui-img.css');
	Yii::app()->clientScript->registerCssFile('../css/product/ui-list.css');
	Yii::app()->clientScript->registerCssFile('../css/product/ui-base.css');
	Yii::app()->clientScript->registerCssFile('../css/product/ui-box.css');
	Yii::app()->clientScript->registerCssFile('../css/product/ui-color.css');
	Yii::app()->clientScript->registerCssFile('../css/product/pic.css');
	Yii::app()->clientScript->registerCssFile('../css/product/ui-media.css'); 
	Yii::app()->clientScript->registerCssFile('../css/product.css');
	Yii::app()->clientScript->registerScriptFile('../js/product/zepto.js');
	Yii::app()->clientScript->registerScriptFile('../js/product/base64.js'); 
	Yii::app()->clientScript->registerScriptFile('../js/product/pic.js');  		 	
?>
	<?php $this->renderPartial('parentcategory',array('categoryId'=>$categoryId));?>
	<div id="page_0" class="up ub ub-ver" tabindex="0">
	<!--content开始-->
    <div id="content" class="ub-f1 tx-l t-bla ub-img6 res10">
		<div id="forum_list">
			<div class="outDiv" id="leftPic">
			</div>
			<div class="outDiv" id="rightPic">
			</div>
			
		</div>
		<!--列表结束-->
		<!--<button class="foot" id="nextpage" ontouchstart="zy_touch('btn-newact')" onclick="getMorePic(1,<?php echo $categoryId;?>);">查看下8条</button>
		<div style="text-align:center;height:0.5em;">&nbsp;</div>-->

    </div>
    <!--content结束-->
</div>
<script type="text/javascript">
	var cat =<?php $cat = $categoryId?$categoryId:0; echo $cat;?>;
	
	window.onload=function(type,catgory)
	{
		type = 1;
		catgory = cat;
		getPicList(type,catgory);
	}	
 $(document).ready(function(){
    $('.moreCate').click(function(){
    	if($('.category').is(":hidden")){
    		$('.category').css('display','block');
    		$(this).css('background','url(img/product/up.png) no-repeat 55px 10px');
    	}else{
    		$('.category').css('display','none');
    		$(this).css('background','url(img/product/down.png) no-repeat 52px 10px');
    	}
    });
    $('#forum_list').on('click','#addCart',function(){
    	var _this = $(this);
    	var isAddOrder = 1;
    	var productId = _this.attr('product-id');
    	var type = _this.attr('type');
    	if(_this.hasClass('hasorder')){
    		isAddOrder = 0;
    	}
 		$.ajax({
 			url:'<?php echo $this->createUrl('/product/createCart');?>',
 			data:{
 					isAddOrder:isAddOrder,
					productId:productId,
					type:type
				},
 			type:'POST',
 			success:function(msg){
 				if(msg){
					if(isAddOrder){
						_this.addClass('hasorder');
					}else{
						_this.removeClass('hasorder');
					}
 				}
 			}
 		});
    });
     $('#forum_list').on('click','#favorite',function(){
     	var _this = $(this);
     	var productId = _this.attr('product-id');
     	var lebalObj = _this.find('.favorite-num-right');
     	$.ajax({
 			url:'<?php echo $this->createUrl('/product/favorite');?>/id/'+productId,
 			success:function(msg){
 				if(msg){
						var num = parseInt(lebalObj.html());
						lebalObj.html(num + 1);
 				}
 			}
       });
     });
 });
</script>