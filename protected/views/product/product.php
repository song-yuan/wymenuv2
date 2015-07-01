<?php
/* @var $this ProductController */
	$baseUrl = Yii::app()->baseUrl;
	Yii::app()->clientScript->registerCssFile('../css/product/ui-btn.css');
	Yii::app()->clientScript->registerCssFile('../css/product/ui-img.css');
	Yii::app()->clientScript->registerCssFile('../css/product/ui-list.css');
	Yii::app()->clientScript->registerCssFile('../css/product/ui-base.css');
	Yii::app()->clientScript->registerCssFile('../css/product/ui-box.css');
	Yii::app()->clientScript->registerCssFile('../css/product/ui-color.css');
	Yii::app()->clientScript->registerCssFile('../css/product/pic.css');
	Yii::app()->clientScript->registerCssFile('../css/product/ui-media.css'); 
	Yii::app()->clientScript->registerCssFile('../css/product.css');
	Yii::app()->clientScript->registerCssFile('../css/product/reset.css');
	Yii::app()->clientScript->registerCssFile('../css/product/slick.css');

	Yii::app()->clientScript->registerScriptFile('../js/product/zepto.js');
	Yii::app()->clientScript->registerScriptFile('../js/product/base64.js'); 
	Yii::app()->clientScript->registerScriptFile('../js/product/pic.js');
        Yii::app()->clientScript->registerScriptFile($baseUrl.'/js/language/'.Yii::app()->language.'.js');
	$result = ProductClass::getCartInfo($this->companyId,$siteNoId);	
	$resArr = explode(':',$result);
	$price = $resArr[0];
	$nums = $resArr[1];
?>

	<?php $this->renderPartial('parentcategory',array('categoryId'=>$categoryId,'type'=>$type));?>
	<link href='../css/product/reset.css' rel='stylesheet' type='text/css'>
	<link href='../css/product/slick.css' rel='stylesheet' type='text/css'>
	<script type="text/javascript" src="../js/product/slick.min.js"></script>
	<script type="text/javascript" src="../js/product/classie.js"></script>
	<script type="text/javascript" src="../js/product/product.js"></script>
	<div id="page_0" class="up ub ub-ver" tabindex="0">
	<!--content开始-->
    <div id="content" class="ub-f1 tx-l t-bla ub-img6 res10">
        <div class="product-category"><?php if(!$type&&$pid){ echo ProductClass::getCategoryName($pid).' >>> '.ProductClass::getCategoryName($categoryId);}else{ if($type==1) echo yii::t('app','推荐品');elseif($type==2) echo yii::t('app','套餐');elseif($type==3) echo yii::t('app','点赞TOP10');else echo yii::t('app','点单TOP10');}?></div>
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
    		<span><?php echo yii::t('app','总价: ');?></span><span class="total-price"><?php echo Money::priceFormat($price);?></span>
    	</div>
    	<div class="bottom-middle">
    		<div class="product-nums"><?php echo $nums;?></div>
    	</div>
    	<div class="bottom-right">
    		<a href="orderList"><button class="see-order"><?php echo yii::t('app','订单>>');?></button></a>
    	</div>
    	<div class="clear"></div>
    </div>
</div>
<div class="product-mask">
	<div class="product-mask-info"><?php echo yii::t('app','点单信息');?></div>
	<div class="info">
	</div>
</div>
<!-- 加入订单动画 -->
<div class="aniele"></div>

<div class="large-pic">
</div>
<script type="text/javascript">
	var cat = '<?php echo $categoryId;?>';
	var t = '<?php echo $type;?>';
	var isPad = '<?php echo $isPad;?>';
	window.onload=function(type,catgory,pad)
	{
		type = t;
		catgory = cat;
		pad = isPad;
		getPicList(type,catgory,pad);
		$('.promptumenu_window').css('display','none');
	}	
</script>
