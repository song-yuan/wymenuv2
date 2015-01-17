<?php
/* @var $this ProductController */
	Yii::app()->clientScript->registerCssFile('css/product.css');
	Yii::app()->clientScript->registerCssFile('css/product/ui-btn.css');
	Yii::app()->clientScript->registerCssFile('css/product/ui-img.css');
	Yii::app()->clientScript->registerCssFile('css/product/ui-list.css');
	Yii::app()->clientScript->registerCssFile('css/product/ui-base.css');
	Yii::app()->clientScript->registerCssFile('css/product/ui-box.css');
	Yii::app()->clientScript->registerCssFile('css/product/ui-color.css');
	Yii::app()->clientScript->registerCssFile('css/product/pic.css');
	Yii::app()->clientScript->registerCssFile('css/product/ui-media.css'); 
	Yii::app()->clientScript->registerScriptFile('js/waiter/zepto.js');
	Yii::app()->clientScript->registerScriptFile('js/waiter/base64.js'); 
	Yii::app()->clientScript->registerScriptFile('js/waiter/pic.js');  		 	
?>
   <div class="waiter">
   	<a href="<?php echo $this->createUrl('/waiter/seat/index');?>"><div class="waiter-back" style="float:left;">返回座次列表</div></a>
   	<a href="<?php echo $this->createUrl('/waiter/product/cartList',array('cid'=>$this->companyId,'code'=>$this->seatNum));?>"><div class="waiter-back" style="float:right;">返回点单</div></a>
   	</div>
   <div class="top">
	<div class="productcate">
		<?php echo $parent['category_name'];?> >> <?php echo $child['category_name'];?><div class="moreCate">其它 </div>
	</div>
	<div class="allCate">
			<?php $this->renderPartial('parentcategory');?>
	</div>
	</div>
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
		<button class="foot" id="nextpage" ontouchstart="zy_touch('btn-newact')" onclick="getMorePic(1,<?php echo $child['category_id'];?>);">查看下8条</button>
		<div style="text-align:center;height:0.5em;">&nbsp;</div>

    </div>
    <!--content结束-->
</div>
<script type="text/javascript">
	var cat =<?php echo $child['category_id'];?>;
	
	window.onload=function(type,catgory,companyId,code)
	{
		type = 1;
		catgory = cat;
		companyId=<?php echo $this->companyId;?>;
		code=<?php echo $this->seatNum;?>;
		getPicList(type,catgory,companyId,code);
	}	
 $(document).ready(function(){
    $('.moreCate').click(function(){
    	if($('.category').is(":hidden")){
    		$('.category').css('display','block');
    	}else{
    		$('.category').css('display','none');
    	}
    	
    });
    $('#forum_list').on('click','.numplus',function(){
    	var id = $(this).attr('product-id');
 		var numObj = $(this).siblings('.num');
 		var numVal = parseInt(numObj.val());
 		$.ajax({
 			url:'<?php echo $this->createUrl('/waiter/product/createCart',array('cid'=>$this->companyId,'code'=>$this->seatNum));?>&id='+id,
 			success:function(msg){
 				if(msg){
 					numVal += 1;
 					numObj.val(numVal); 
 				}
 			},
 		});
    });
 	
     $('#forum_list').on('click','.numminus',function(){
     	var id = $(this).attr('product-id');
 		var numObj = $(this).siblings('.num');
 		var numVal = parseInt(numObj.val());
 		if(numVal>0){
 			$.ajax({
 			url:'<?php echo $this->createUrl('/waiter/product/deleteCartProduct',array('cid'=>$this->companyId,'code'=>$this->seatNum));?>&id='+id,
 			success:function(msg){
 				if(msg){
 					numVal -= 1;
 					numObj.val(numVal);
 				}
 			},
 		});
 		}
     });
 	$(window).on('touchend',function(e){
		var a = document.body.scrollHeight;
		var b = document.documentElement.clientHeight;
		var c = document.documentElement.scrollTop + document.body.scrollTop;
		//var c = document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop;
		var totalHeight = c+b+30;
		if(totalHeight >= a ){
			$('#nextpage').text('数据加载中……');
			getMorePic(1,cat);
		} 
	})
 });
</script>