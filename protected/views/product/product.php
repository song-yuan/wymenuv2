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
	Yii::app()->clientScript->registerScriptFile('../js/product/jquery.js'); 
	Yii::app()->clientScript->registerScriptFile('../js/product/jquery.lazyload.js');  
	$result = ProductClass::getCartInfo($siteNoId);	
	$resArr = explode(':',$result);
	$price = $resArr[0];
	$nums = $resArr[1];
?>

	<?php $this->renderPartial('parentcategory',array('categoryId'=>$categoryId));?>
	<script type="text/javascript" src="../js/product/product.js"></script>
	<div id="page_0" class="up ub ub-ver" tabindex="0">
	<!--content开始-->
    <div id="content" class="ub-f1 tx-l t-bla ub-img6 res10">
        <div class="product-category">热点 >>> 推荐品</div>
		<div id="forum_list">
			<div class="outDiv" id="leftPic">
			</div>
			<div class="outDiv" id="rightPic">
			</div>
		</div>
    </div>
    <!--content结束-->
    <div class="bottom">
    	<div class="bottom-left">
    		<span>总价: </span><span class="total-price"><?php echo Money::priceFormat($price);?></span>
    	</div>
    	<div class="bottom-middle">
    		<div class="product-nums"><?php echo $nums;?></div>
    	</div>
    	<div class="bottom-right">
    		<a href="orderList"><button class="see-order">订单>></button></a>
    	</div>
    	<div class="clear"></div>
    </div>
</div>
<script type="text/javascript">
	var cat =<?php $cat = $categoryId?$categoryId:0; echo $cat;?>;
	
	window.onload=function(type,catgory)
	{
		type = 1;
		catgory = cat;
		getPicList(type,catgory);
	}	
</script>